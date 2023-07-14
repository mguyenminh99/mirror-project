<?php
namespace XShoppingSt\MarketplaceBaseShipping\Block\Product;

/*
 * XShoppingSt Marketplace Product Create Block
 */
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Category;
use Magento\GoogleOptimizer\Model\Code as ModelCode;
use XShoppingSt\Marketplace\Helper\Data as HelperData;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\DB\Helper as FrameworkDbHelper;
use Magento\Catalog\Helper\Category as CategoryHelper;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Serialize\SerializerInterface;

class Create extends \XShoppingSt\Marketplace\Block\Product\Create
{
    /**
     * Get Product Type.
     */
    public function getProductType()
    {
        $ProductType = $this->getRequest()->getParam('type');
        return $ProductType;
    }
}
