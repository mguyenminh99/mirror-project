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
        $data = $this->getRequest()->getParams();
        $allStores = $this->_storeManager->getStores();
        $status = \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_DISABLED;
        $enabledProductStatus = \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED;

        $collection = $this->sellerModel->create()
            ->getCollection()
            ->addFieldToFilter('seller_id', $data['seller_id']);

        if ($item->getIsSeller() == self::TEMPORARILY_SUSPENDED_SELLER_STATUS) {

            $sellerProduct = $this->collectionFactory->create()
                ->addFieldToFilter(
                    'seller_id',
                    $data['seller_id']
                );
            foreach ($collection as $value) {
                $autoId = $value->getId();
                $value = $this->sellerModel->create()->load($autoId);
                $value->setIsSeller(self::ENABLED_SELLER_STATUS);
                $value->save();
            }

            /** Enable product */
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
                    ['status' => $enabledProductStatus]
                );
                foreach ($allStores as $eachStoreId => $storeId) {
                    $this->productAction->updateAttributes(
                        $productIds,
                        ['status' => $enabledProductStatus],
                        $storeId
                    );
                }

                $this->productAction->updateAttributes($productIds, ['status' => $enabledProductStatus], 0);

                $this->_productPriceIndexerProcessor->reindexList($productIds);

            }
        } else {
            $sellerProduct = $this->collectionFactory->create()
                ->addFieldToFilter(
                    'seller_id',
                    $data['seller_id']
                );

            foreach ($collection as $value) {
                $autoId = $value->getId();
                $value = $this->sellerModel->create()->load($autoId);
                $value->setIsSeller(self::TEMPORARILY_SUSPENDED_SELLER_STATUS);
                $value->save();
            }

            /** Disable product  */
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
                    ['status' => $status]
                );
                foreach ($allStores as $eachStoreId => $storeId) {
                    $this->productAction->updateAttributes(
                        $productIds,
                        ['status' => $status],
                        $storeId
                    );
                }
                $this->productAction->updateAttributes($productIds, ['status' => $status], 0);

                $this->_productPriceIndexerProcessor->reindexList($productIds);
            }
        }
        $seller = $this->customerModel->create()->load($data['seller_id']);
        if (isset($data['notify_seller']) && $data['notify_seller'] == 1) {
            $helper = $this->mpHelper;

            $adminStoremail = $helper->getAdminEmailId();
            $adminEmail=$adminStoremail? $adminStoremail:$helper->getDefaultTransEmailId();
            $adminUsername = $helper->getAdminName();
            $emailTempVariables['myvar1'] = $seller->getName();
            $emailTempVariables['myvar2'] = $data['seller_deny_reason'];
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

        if ($item->getIsSeller() == self::TEMPORARILY_SUSPENDED_SELLER_STATUS) {
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
