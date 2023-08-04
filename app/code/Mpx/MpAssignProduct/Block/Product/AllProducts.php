<?php

namespace Mpx\MpAssignProduct\Block\Product;

use XShoppingSt\MpAssignProduct\Model\ResourceModel\Items\CollectionFactory;

class AllProducts extends \XShoppingSt\MpAssignProduct\Block\Product\AllProducts
{
    /**
     * @var \Mpx\Marketplace\Helper\CommonFunc
     */
    protected $commonFunc;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        CollectionFactory $itemsCollectionFactory,
        \Mpx\Marketplace\Helper\CommonFunc $commonFunc,
        array $data = [],
        \XShoppingSt\MpAssignProduct\Helper\Data $mpAssignHelper = null
    ) {
        $this->commonFunc = $commonFunc;
        parent::__construct($context, $customerSession, $itemsCollectionFactory, $data, $mpAssignHelper);
    }

    /**
     * @return bool|\Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getAllProducts()
    {
        if (!$this->_productList) {
            $customerId = $this->commonFunc->getOriginSellerId($this->_customerSession->getCustomerId());
            $sellercollection = $this->_itemsCollection
                ->create()
                ->addFieldToFilter('seller_id', $customerId);
            $sellercollection->setOrder('created_at', 'desc');
            $this->_productList = $sellercollection;
        }
        return $this->_productList;
    }
}
