<?php
namespace XShoppingSt\MpTimeDelivery\Block\Account;

use XShoppingSt\Marketplace\Helper\Data;
use XShoppingSt\MpTimeDelivery\Helper\Data as Helper;
use XShoppingSt\Marketplace\Model\ProductFactory;
use XShoppingSt\Marketplace\Model\OrdersFactory;
use XShoppingSt\Marketplace\Model\ResourceModel\Product\CollectionFactory;
use XShoppingSt\Marketplace\Model\SellertransactionFactory;
use XShoppingSt\Marketplace\Helper\Data as MpHelper;

class Navigation extends \XShoppingSt\Marketplace\Block\Account\Navigation
{
    /**
     * @var Data;
     */
    protected $mphelper;

    /**
     * @var Helper;
     */
    protected $helper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Magento\Customer\Model\Session $customerSession
     * @param ProductFactory $productFactory
     * @param OrdersFactory $ordersFactory
     * @param CollectionFactory $productCollection
     * @param SellertransactionFactory $sellertransaction
     * @param \Magento\Catalog\Model\ProductFactory $productModel
     * @param \Magento\Sales\Model\OrderFactory $orderModel
     * @param \XShoppingSt\Marketplace\Model\SaleslistFactory $saleslistModel
     * @param \Magento\Shipping\Model\Config $shipconfig
     * @param \Magento\Payment\Model\Config $paymentConfig
     * @param MpHelper $mpHelper
     * @param Helper $helper
     * @param Data $mphelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Customer\Model\Session $customerSession,
        ProductFactory $productFactory,
        OrdersFactory $ordersFactory,
        CollectionFactory $productCollection,
        SellertransactionFactory $sellertransaction,
        \Magento\Catalog\Model\ProductFactory $productModel,
        \Magento\Sales\Model\OrderFactory $orderModel,
        \XShoppingSt\Marketplace\Model\SaleslistFactory $saleslistModel,
        \Magento\Shipping\Model\Config $shipconfig,
        \Magento\Payment\Model\Config $paymentConfig,
        MpHelper $mpHelper,
        Helper $helper,
        Data $mphelper,
        array $data = []
    ) {
        $this->mphelper = $mphelper;
        $this->helper = $helper;
        parent::__construct(
            $context,
            $date,
            $customerSession,
            $productFactory,
            $ordersFactory,
            $productCollection,
            $sellertransaction,
            $productModel,
            $orderModel,
            $saleslistModel,
            $shipconfig,
            $paymentConfig,
            $mpHelper
        );
    }

    /**
     * Get Marketplace Helper Object
     *
     * @return object
     */
    public function getMpHelperObject()
    {
        return $this->mpHelper;
    }

    /**
     * Get Marketplace Helper Object
     *
     * @return object
     */
    public function getHelperObject()
    {
        return $this->helper;
    }
}
