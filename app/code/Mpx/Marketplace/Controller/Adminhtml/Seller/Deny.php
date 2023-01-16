<?php
/**
 * Mpx Software.
 *
 * @category  Mpx
 * @package   Mpx_Marketplace
 * @author    Mpx
 */

namespace Mpx\Marketplace\Controller\Adminhtml\Seller;

use Magento\Framework\Controller\ResultFactory;

/**
 * Class massDisapprove
 */
class Deny extends \Webkul\Marketplace\Controller\Adminhtml\Seller\Deny
{
    const TEMPORARILY_SUSPENDED_SELLER_STATUS = 3;
    const ENABLED_SELLER_STATUS = 1;

    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        $postData = $this->getRequest()->getParams();
        $allStores = $this->_storeManager->getStores();

        if (isset($postData['seller_status_update_to']) == 'enable_seller') {
            $sellerStatusUpdateTo = self::TEMPORARILY_SUSPENDED_SELLER_STATUS;
            $productStatusUpdateTo = \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_DISABLED;
        } else {
            $sellerStatusUpdateTo = self::ENABLED_SELLER_STATUS;
            $productStatusUpdateTo = \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED;
        }

        /** Update Seller Status(marketplace_userdata.is_seller) */
        $collection = $this->sellerModel->create()
            ->getCollection()
            ->addFieldToFilter('seller_id', $postData['seller_id']);

        foreach ($collection as $value) {
            $entityId = $value->getId();
            $value = $this->sellerModel->create()->load($entityId);
            $value->setIsSeller($sellerStatusUpdateTo);
            $value->save();
        }

        /** Update Seller Product Status */
        $sellerProduct = $this->collectionFactory->create()
            ->addFieldToFilter(
                'seller_id',
                $postData['seller_id']
            );

        if ($sellerProduct->getSize()) {

            $productIds = $sellerProduct->getAllIds();
            $conditionArr = [];

            foreach ($productIds as $key => $id) {
                $condition = "`mageproduct_id`=" . $id;
                array_push($conditionArr, $condition);
            }

            $conditionData = implode(' OR ', $conditionArr);

            $sellerProduct->setProductData(
                $conditionData,
                ['status' => $productStatusUpdateTo]
            );

            foreach ($allStores as $store) {
                $this->productAction->updateAttributes(
                    $productIds,
                    ['status' => $productStatusUpdateTo],
                    $store->getId()
                );
            }

            $this->productAction->updateAttributes($productIds, ['status' => $productStatusUpdateTo], 0);
            $this->_productPriceIndexerProcessor->reindexList($productIds);
        }
        $seller = $this->customerModel->create()->load($postData['seller_id']);
        if (isset($postData['notify_seller']) && $postData['notify_seller'] == 1) {
            $helper = $this->mpHelper;

            $adminStoremail = $helper->getAdminEmailId();
            $adminEmail=$adminStoremail? $adminStoremail:$helper->getDefaultTransEmailId();
            $adminUsername = $helper->getAdminName();
            $emailTempVariables['myvar1'] = $seller->getName();
            $emailTempVariables['myvar2'] = $postData['seller_deny_reason'];
            $senderInfo = [
                'name' => $adminUsername,
                'email' => $adminEmail,
            ];
            $receiverInfo = [
                'name' => $seller->getName(),
                'email' => $seller->getEmail(),
            ];
            $this->mpEmailHelper->sendSellerDenyMail(
                $emailTempVariables,
                $senderInfo,
                $receiverInfo
            );
        }
        $this->_eventManager->dispatch(
            'mp_deny_seller',
            ['seller' => $seller]
        );

        if ($sellerStatusUpdateTo != self::TEMPORARILY_SUSPENDED_SELLER_STATUS) {
            $this->messageManager->addSuccess(__('Seller has been Reopened.'));
        } else {
            $this->messageManager->addSuccess(__('Seller has been Denied.'));
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(
            ResultFactory::TYPE_REDIRECT
        );
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Check for is allowed
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_Marketplace::seller');
    }
}
