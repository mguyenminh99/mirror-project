<?php

namespace Mpx\MpAssignProduct\Block\Product;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollection;
use XShoppingSt\Marketplace\Helper\Data as marketplaceHelper;
use XShoppingSt\Marketplace\Model\ResourceModel\Product\CollectionFactory;
use XShoppingSt\MpAssignProduct\Model\ResourceModel\Items\CollectionFactory as AssignProductCollection;

class ProductList extends \XShoppingSt\MpAssignProduct\Block\Product\ProductList
{
    /**
     * @var \Mpx\Marketplace\Helper\CommonFunc
     */
    protected $commonFunc;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \XShoppingSt\MpAssignProduct\Helper\Data $helper,
        ProductCollection $productCollectionFactory,
        CollectionFactory $mpProductCollectionFactory,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        AssignProductCollection $assignProductCollection,
        \Magento\Framework\Pricing\Helper\Data $pricingHelper,
        marketplaceHelper $marketplaceHelper,
        \Magento\Catalog\Helper\Image $catalogImage,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceInterface,
        \Magento\Checkout\Helper\Cart $checkoutHelper,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Mpx\Marketplace\Helper\CommonFunc $commonFunc,
        array $data = []
    ) {
        $this->commonFunc = $commonFunc;
        parent::__construct($context, $customerSession, $helper, $productCollectionFactory, $mpProductCollectionFactory, $productStatus, $productVisibility, $assignProductCollection, $pricingHelper, $marketplaceHelper, $catalogImage, $priceInterface, $checkoutHelper, $jsonHelper, $data);
    }

    /**
     * @return bool|\Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getAllProducts()
    {
        if (!$this->_productList) {
            $queryString = $this->_assignHelper->getQueryString();
            $page=($this->getRequest()->getParam('p'))? $this->getRequest()->getParam('p') : 1;
            $pageSize=($this->getRequest()->getParam('limit'))? $this->getRequest()->getParam('limit') : 1;

            if ($queryString != '') {
                $customerId = $this->commonFunc->getOriginSellerId($this->_customerSession->getCustomerId());
                $sellercollection = $this->_mpProductCollection
                    ->create()
                    ->addFieldToFilter('seller_id', ['eq' => $customerId])
                    ->addFieldToSelect('mageproduct_id');
                $products = [];
                foreach ($sellercollection as $data) {
                    array_push($products, $data->getMageproductId());
                }
                $assignProductIds = $this->_assignProductCollection
                    ->create()->getAllIds();
                $products = array_merge($products, $assignProductIds);
                $sellerAssigncollection = $this->_assignProductCollection
                    ->create()
                    ->addFieldToFilter('seller_id', $customerId)
                    ->addFieldToSelect('product_id');
                foreach ($sellerAssigncollection as $data) {
                    array_push($products, $data->getProductId());
                }

                $allowedTypes = $this->_assignHelper->getAllowedProductTypes();
                $collection = $this->_productCollection
                    ->create()
                    ->addFieldToSelect('*')
                    ->addFieldToFilter('name', ['like' => '%'.$queryString.'%']);
                $collection->addFieldToFilter('type_id', ['in' => $allowedTypes]);
                if (count($products) > 0) {
                    $collection->addFieldToFilter('entity_id', ['nin' => $products]);
                }
                $collection->addAttributeToFilter('status', ['in' => $this->_productStatus->getVisibleStatusIds()]);
                $collection->setVisibility($this->_productVisibility->getVisibleInSiteIds());
                $collection->setOrder('created_at', 'desc');
            } else {
                $collection = $this->_productCollection
                    ->create()
                    ->addFieldToSelect('*')
                    ->addFieldToFilter('entity_id', 0);
            }
            $collection->setPageSize($pageSize);
            $collection->setCurPage($page);
            $this->_productList = $collection;
        }
        return $this->_productList;
    }
}
