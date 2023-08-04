<?php

namespace Mpx\MpAssignProduct\Observer;

use Magento\Customer\Model\Session;

class AssignSeller extends \XShoppingSt\MpAssignProduct\Observer\AssignSeller
{
    /**
     * @var \Mpx\Marketplace\Helper\CommonFunc
     */
    protected $commonFunc;


    /**
     * @param \XShoppingSt\MpAssignProduct\Helper\Data $helper
     * @param Session $customerSession
     * @param \XShoppingSt\Marketplace\Model\ResourceModel\Product\CollectionFactory $sellerProductCollectionFactory
     * @param \Mpx\Marketplace\Helper\CommonFunc $commonFunc
     */
    public function __construct(
        \XShoppingSt\MpAssignProduct\Helper\Data $helper,
        Session $customerSession,
        \XShoppingSt\Marketplace\Model\ResourceModel\Product\CollectionFactory $sellerProductCollectionFactory,
        \Mpx\Marketplace\Helper\CommonFunc $commonFunc
    ) {
        $this->commonFunc = $commonFunc;
        parent::__construct($helper, $customerSession, $sellerProductCollectionFactory);
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $params = $observer->getData();
        $ids = [];
        if (array_key_exists(0, $params)) {
            $sellerId = $this->commonFunc->getOriginSellerId($this->_customerSession->getCustomerId());
            if (array_key_exists('id', $params[0])) {
                $productId = $params[0]['id'];
                if (!$this->_assignHelper->hasAssignedProducts($productId)) {
                    return;
                }
                $sellerProducts = $this->_sellerProductCollectionFactory
                    ->create()
                    ->addFieldToFilter(
                        'mageproduct_id',
                        $productId
                    )->addFieldToFilter(
                        'seller_id',
                        $sellerId
                    );
                if ($this->_customerSession->getAssignProductIds()) {
                    $ids = $this->_customerSession->getAssignProductIds();
                }
                if ($sellerProducts->getSize()) {
                    $ids[] = $productId;
                    $this->_customerSession->setAssignProductIds($ids);
                    $this->_assignHelper->assignSeller($productId);
                }
            }
        }
    }
}
