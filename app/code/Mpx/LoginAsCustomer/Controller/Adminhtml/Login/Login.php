<?php

namespace Mpx\LoginAsCustomer\Controller\Adminhtml\Login;

class Login extends \Magefan\LoginAsCustomer\Controller\Adminhtml\Login\Login
{

    /**
     * Login as customer action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $request = $this->getRequest();
        $customerId = (int) $request->getParam('customer_id');
        if (!$customerId) {
            $customerId = (int) $request->getParam('entity_id');
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if (!$this->config->isEnabled()) {
            $msg = __(strrev('.remotsuC sA nigoL > snoisnetxE nafegaM > noitarugifnoC > serotS ot etagivan esaelp noisnetxe eht elbane ot ,delbasid si remotsuC sA nigoL nafegaM'));
            $this->messageManager->addErrorMessage($msg);
            return $resultRedirect->setPath('marketplace/seller/index');
        } elseif ($this->config->isKeyMissing()) {
            $msg = __(strrev('.remotsuC sA nigoL > snoisnetxE nafegaM > noitarugifnoC > serotS ni yek tcudorp eht yficeps esaelP .noos delbasid yllacitamotua eb lliw noisnetxE remotsuC sA nigoL .gnissim si yeK tcudorP remotsuC sA nigoL nafegaM'));
            $this->messageManager->addErrorMessage($msg);
            return $resultRedirect->setPath('marketplace/seller/index');
        }

        $customerStoreId = $request->getParam('store_id');

        if (!isset($customerStoreId) && $this->config->getStoreViewLogin()) {
            $this->messageManager->addNoticeMessage(__('Please select a Store View to login in.'));
            return $resultRedirect->setPath('loginascustomer/login/manual', ['entity_id' => $customerId ]);
        }

        $login = $this->loginModel->setCustomerId($customerId);

        $login->deleteNotUsed();

        $customer = $login->getCustomer();

        if (!$customer->getId()) {
            $this->messageManager->addErrorMessage(__('Customer with this ID are no longer exist.'));
            return $resultRedirect->setPath('marketplace/seller/index');
        }

        /* Check if customer's company is active */
        $tmpCustomer = $this->customerRepository->getById($customer->getId());
        if ($tmpCustomer->getExtensionAttributes() !== null) {
            $companyAttributes = null;
            if (method_exists($tmpCustomer->getExtensionAttributes(), 'getCompanyAttributes')) {
                $companyAttributes = $tmpCustomer->getExtensionAttributes()->getCompanyAttributes();
            }

            if ($companyAttributes !== null) {
                $companyId = $companyAttributes->getCompanyId();
                if ($companyId) {
                    try {
                        $company = $this->getCompanyRepository()->get($companyId);
                        if ($company->getStatus() != 1) {
                            $this->messageManager->addErrorMessage(__('You cannot login as customer. Customer\'s company is not active.'));
                            return $resultRedirect->setPath('marketplace/seller/index');
                        }
                    } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {}
                }
            }
        }
        /* End check */

        $user = $this->authSession->getUser();
        $login->generate($user->getId());

        if (!$customerStoreId) {
            $customerStoreId = $this->getCustomerStoreId($customer);
        }

        if ($customerStoreId) {
            $store = $this->storeManager->getStore($customerStoreId);
        } else {
            $store = $this->storeManager->getDefaultStoreView();
        }

        $redirectUrl = $this->url->setScope($store)
            ->getUrl('loginascustomer/login/index', ['secret' => $login->getSecret(), '_nosid' => true]);

        $this->getResponse()->setRedirect($redirectUrl);
    }
}
