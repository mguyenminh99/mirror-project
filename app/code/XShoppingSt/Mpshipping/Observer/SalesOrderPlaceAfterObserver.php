<?php
namespace XShoppingSt\Mpshipping\Observer;

use Magento\Framework\Event\Manager;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Session\SessionManager;

class SalesOrderPlaceAfterObserver implements ObserverInterface
{
    /**
     * @var eventManager
     */
    protected $_eventManager;
    /**
     * @var Magento\Framework\Session\SessionManager
     */
    protected $_coreSession;
    /**
     * @var XShoppingSt\Mpshipping\Helper\Data
     */
    protected $_mpshippingHelper;
    /**
     * @var XShoppingSt\Marketplace\Model\Orders
     */
    protected $_marketplaceOrders;

    /**
     * @param \Magento\Framework\Event\Manager        $eventManager
     * @param SessionManager                          $coreSession
     * @param \XShoppingSt\Mpshipping\Helper\Data          $mpshippingHelper
     * @param \XShoppingSt\Marketplace\Model\OrdersFactory $mpOrders
     */

    public function __construct(
        \Magento\Framework\Event\Manager $eventManager,
        SessionManager $coreSession,
        \XShoppingSt\Mpshipping\Helper\Data $mpshippingHelper,
        \XShoppingSt\Marketplace\Model\OrdersFactory $mpOrders
    ) {
        $this->_eventManager = $eventManager;
        $this->_coreSession = $coreSession;
        $this->_mpshippingHelper = $mpshippingHelper;
        $this->_marketplaceOrders = $mpOrders;
    }

    /**
     * Order place after event handler
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var $orderInstance Order */
        $order = $observer->getOrder();
        $lastOrderId = $observer->getOrder()->getId();
        $helper = $this->_mpshippingHelper;
        $shippingmethod = $order->getShippingMethod();
        $rateMethod = explode('x_shopping_st_shipping_', $shippingmethod);
        if (strpos($shippingmethod, 'x_shopping_st_shipping')!==false) {
            $shippingAll = $this->_coreSession->getShippingInfo();
            foreach ($shippingAll['x_shopping_st_shipping'] as $shipdata) {
                $collection = $this->_marketplaceOrders->create()
                    ->getCollection()
                    ->addFieldToFilter('order_id', ['eq'=>$lastOrderId])
                    ->addFieldToFilter('seller_id', ['eq'=>$shipdata['seller_id']])
                    ->getFirstItem();
                if ($collection->getEntityId()) {
                    $collection->setCarrierName($shipdata['submethod'][$rateMethod[1]]['method']);
                    $collection->setShippingCharges($shipdata['submethod'][$rateMethod[1]]['cost']);
                    $collection->save();
                }
            }
            $this->_coreSession->unsetShippingInfo();
        }
    }
}
