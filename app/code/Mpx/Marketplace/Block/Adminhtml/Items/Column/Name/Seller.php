<?php
/**
 * Mpx Software.
 *
 * @category  Mpx
 * @package   Mpx_Marketplace
 * @author    Mpx
 */

namespace Mpx\Marketplace\Block\Adminhtml\Items\Column\Name;

use XShoppingSt\Marketplace\Helper\Data as HelperData;

class Seller extends \XShoppingSt\Marketplace\Block\Adminhtml\Items\Column\Name\Seller
{
    /**
     * @var \XShoppingSt\Marketplace\Model\SaleslistFactory
     */
    protected $saleslistFactory;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlInterface;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerModel;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $_resource;

    /**
     * @var HelperData
     */
    protected $helper;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
     * @param \Magento\CatalogInventory\Api\StockConfigurationInterface $stockConfiguration
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Catalog\Model\Product\OptionFactory $optionFactory
     * @param \XShoppingSt\Marketplace\Model\SaleslistFactory $saleslistFactory
     * @param \Magento\Framework\UrlInterface $urlInterface
     * @param \Magento\Customer\Model\CustomerFactory $customerModel
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param HelperData $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\CatalogInventory\Api\StockConfigurationInterface $stockConfiguration,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\Product\OptionFactory $optionFactory,
        \XShoppingSt\Marketplace\Model\SaleslistFactory $saleslistFactory,
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Customer\Model\CustomerFactory $customerModel,
        \Magento\Framework\App\ResourceConnection $resource,
        HelperData $helper,
        array $data = []
    ) {
        $this->saleslistFactory = $saleslistFactory;
        $this->urlInterface = $urlInterface;
        $this->customerModel = $customerModel;
        $this->_resource = $resource;
        $this->helper = $helper;
        parent::__construct(
            $context,
            $stockRegistry,
            $stockConfiguration,
            $registry,
            $optionFactory,
            $saleslistFactory,
            $urlInterface,
            $customerModel,
            $data
        );
    }

    /**
     * Get Seller Name.
     *
     * @param string $id
     *
     * @return array
     */
    public function getUserInfo($id)
    {
        $sellerTable = $this->_resource->getTableName('marketplace_userdata');
        $storeId = $this->helper->getCurrentStoreId();
        $sellerId = 0;
        $order = $this->getOrder();
        $orderId = $order->getId();
        $marketplaceSalesCollection = $this->saleslistFactory->create()
            ->getCollection()
            ->addFieldToFilter(
                'mageproduct_id',
                ['eq' => $id]
            )
            ->addFieldToFilter(
                'order_id',
                ['eq' => $orderId]
            );
        $marketplaceSalesCollection->getSelect()->joinLeft(
            $sellerTable.' as seller',
            'seller.seller_id = main_table.seller_id',
            ['seller.shop_title']
        )->where('seller.store_id='.$storeId);
        if (count($marketplaceSalesCollection)) {
            foreach ($marketplaceSalesCollection as $mpSales) {
                $sellerId = $mpSales->getSellerId();
                $shopTitle = $mpSales->getShopTitle();
            }
        }
        if ($sellerId > 0) {
            $customer = $this->customerModel->create()->load($sellerId);
            if ($customer) {
                $returnArray = [];
                $returnArray['name'] = $customer->getName();
                $returnArray['id'] = $sellerId;
                $returnArray['shop_title'] = $shopTitle;

                return $returnArray;
            }
        }
    }
}
