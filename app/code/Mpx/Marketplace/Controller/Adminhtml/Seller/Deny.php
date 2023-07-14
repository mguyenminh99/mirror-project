<?php
/**
 * Mpx Software.
 *
 * @category  Mpx
 * @package   Mpx_Marketplace
 * @author    Mpx
 */

namespace Mpx\Marketplace\Controller\Adminhtml\Seller;

use Magento\Catalog\Model\Indexer\Product\Price\Processor;
use Magento\Catalog\Model\Product\Action as ProductAction;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Mpx\Marketplace\Helper\CommonFunc as MpxHelperData;
use XShoppingSt\Marketplace\Helper\Data as MpHelper;
use XShoppingSt\Marketplace\Helper\Email as MpEmailHelper;
use XShoppingSt\Marketplace\Model\ResourceModel\Product\CollectionFactory;
use XShoppingSt\Marketplace\Model\SellerFactory;
use Mpx\Marketplace\Helper\Constant;

/**
 * Class massDisapprove
 */
class Deny extends \XShoppingSt\Marketplace\Controller\Adminhtml\Seller\Deny
{

    public function __construct(
        \Magento\Backend\App\Action\Context             $context,
        Filter                                          $filter,
        \Magento\Framework\Stdlib\DateTime\DateTime     $date,
        \Magento\Framework\Stdlib\DateTime              $dateTime,
        \Magento\Store\Model\StoreManagerInterface      $storeManager,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        CollectionFactory                               $collectionFactory,
        Processor                                       $productPriceIndexerProcessor,
        SellerFactory                                   $sellerModel,
        ProductAction                                   $productAction,
        MpHelper                                        $mpHelper,
        MpEmailHelper                                   $mpEmailHelper,
        \Magento\Customer\Model\CustomerFactory         $customerModel,
        \XShoppingSt\Marketplace\Helper\Data                 $helper,
        MpxHelperData                                   $mpxHelperData
    ) {
        $this->mpxHelperData = $mpxHelperData;
        $this->helper = $helper;
        parent::__construct($context,
            $filter,
            $date,
            $dateTime,
            $storeManager,
            $productRepository,
            $collectionFactory,
            $productPriceIndexerProcessor,
            $sellerModel,
            $productAction,
            $mpHelper,
            $mpEmailHelper,
            $customerModel
        );
    }

    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        $limit_seller = $this->mpxHelperData->getConfigLimitSeller();
        $postData = $this->getRequest()->getParams();
        $allStores = $this->_storeManager->getStores();

        /** Update Seller Status(marketplace_userdata.is_seller) */
        $collection = $this->sellerModel->create()
            ->getCollection()
            ->addFieldToFilter('seller_id', $postData['seller_id']);

        if (isset($postData['seller_status_update_to']) == Constant::ENABLE_SELLER) {
            $sellerStatusUpdateTo = Constant::TEMPORARILY_SUSPENDED_SELLER_STATUS;
            $productStatusUpdateTo = \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_DISABLED;
        } else {
            $sellerStatusUpdateTo = Constant::ENABLED_SELLER_STATUS;
            $productStatusUpdateTo = \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED;
            if( $this->mpxHelperData->isRunOutOfSellerLimit()){
                $this->messageManager->addError(__('You cannot register more than %1 stores at this time',$limit_seller));
                $resultRedirect = $this->resultFactory->create(
                    ResultFactory::TYPE_REDIRECT
                );
                return $resultRedirect->setPath('*/*/');
            }
        }

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

        if ($sellerStatusUpdateTo != Constant::TEMPORARILY_SUSPENDED_SELLER_STATUS) {
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
        return $this->_authorization->isAllowed('XShoppingSt_Marketplace::seller');
    }
}
