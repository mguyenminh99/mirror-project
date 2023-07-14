<?php

namespace Mpx\Marketplace\Plugin\Product;

use Magento\Authorization\Model\UserContextInterface;
use Magento\Catalog\Model\ProductRepository;
use Mpx\Marketplace\Helper\Constant;
use XShoppingSt\Marketplace\Model\ProductFactory;
use Mpx\Marketplace\Helper\CommonFunc as MpxMarketplaceHelper;

/**
 *  Class AfterSave
 */
class AfterSave
{
    /**
     * @var ProductFactory
     */
    protected $productFactory;

    /**
     * @var UserContextInterface
     */
    protected $userContext;

    /**
     * @var MpxMarketplaceHelper
     */
    protected $mpxMarketplaceHelper;

    /**
     * @param ProductFactory $productFactory
     * @param UserContextInterface $userContext
     */
    public function __construct(
        ProductFactory $productFactory,
        UserContextInterface $userContext,
        MpxMarketplaceHelper            $mpxMarketplaceHelper
    )
    {
        $this->productFactory = $productFactory;
        $this->userContext = $userContext;
        $this->mpxMarketplaceHelper = $mpxMarketplaceHelper;
    }

    /**
     * @param ProductRepository $subject
     * @param $product
     * @return \Magento\Catalog\Api\Data\ProductInterface|\Magento\Catalog\Model\Product|string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function afterSave(ProductRepository $subject, $product)
    {
        $sellerId = $this->mpxMarketplaceHelper->getSkuPrefix();
        $product->setSku($sellerId . Constant::UNICODE_HYPHEN_MINUS . $product->getSku());
        try {
            $product->save();
            $mpProduct = $this->productFactory->create();
            $mpProduct->setMageproductId($product->getEntityId());
            $mpProduct->setAdminassign(0);
            $mpProduct->setSellerId($sellerId);
            $mpProduct->setStatus($product->getStatus());
            $mpProduct->setIsApprove($product->getIsApproved());
            $mpProduct->setStoreId($product->getStoreId());
            $mpProduct->save();
        }catch (\Exception $e){
            return $e->getMessage();
        }
        return $subject->get($product->getSku(), false, $product->getStoreId());
    }
}
