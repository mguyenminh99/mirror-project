<?php

namespace Mpx\Marketplace\Block\Order;

use Magento\Sales\Model\Order;
use Magento\Customer\Model\Customer;
use Magento\Sales\Model\Order\Address;
use Magento\Sales\Model\Order\Address\Renderer as AddressRenderer;
use Magento\Downloadable\Model\Link;
use Magento\Downloadable\Model\Link\Purchased;
use Magento\Store\Model\ScopeInterface;
use Magento\Downloadable\Model\ResourceModel\Link\Purchased\Item\CollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Webkul\Marketplace\Model\OrdersFactory as MpOrderModel;
use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\InvoiceFactory;
use Webkul\Marketplace\Model\SaleslistFactory;
use Magento\Catalog\Api\ProductRepositoryInterfaceFactory;
use Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory as OrderItemCollection;
use Mpx\Marketplace\Helper\Constant;

/**
 * Save shipment order
 *
 * class View
 */
class View extends \Webkul\Marketplace\Block\Order\View
{

    /**
     * @var \Magento\Framework\App\State
     */
    protected $_appState;

    /**
     * @param Order $order
     * @param Customer $customer
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param AddressRenderer $addressRenderer
     * @param Link\PurchasedFactory $purchasedFactory
     * @param \Magento\Sales\Block\Order\Item\Renderer\DefaultRenderer $defaultRenderer
     * @param CollectionFactory $itemsFactory
     * @param MpOrderModel $mpOrderModel
     * @param Creditmemo $creditmemoModel
     * @param Creditmemo\ItemFactory $creditmemoItem
     * @param InvoiceFactory $invoiceModel
     * @param SaleslistFactory $saleslistModel
     * @param \Webkul\Marketplace\Helper\Orders $ordersHelper
     * @param ProductRepositoryInterfaceFactory $productRepository
     * @param \Magento\Shipping\Model\Config $shippingConfig
     * @param \Magento\Shipping\Model\CarrierFactory $carrierFactory
     * @param OrderItemCollection $itemCollectionFactory
     * @param \Magento\Framework\App\State $appState
     * @param array $data
     */
    public function __construct(
        Order $order,
        Customer $customer,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Element\Template\Context $context,
        AddressRenderer $addressRenderer,
        \Magento\Downloadable\Model\Link\PurchasedFactory $purchasedFactory,
        \Magento\Sales\Block\Order\Item\Renderer\DefaultRenderer $defaultRenderer,
        CollectionFactory $itemsFactory,
        MpOrderModel $mpOrderModel,
        Creditmemo $creditmemoModel,
        \Magento\Sales\Model\Order\Creditmemo\ItemFactory $creditmemoItem,
        InvoiceFactory $invoiceModel,
        SaleslistFactory $saleslistModel,
        \Webkul\Marketplace\Helper\Orders $ordersHelper,
        ProductRepositoryInterfaceFactory $productRepository,
        \Magento\Shipping\Model\Config $shippingConfig,
        \Magento\Shipping\Model\CarrierFactory $carrierFactory,
        OrderItemCollection $itemCollectionFactory,
        \Magento\Framework\App\State $appState,
        array $data = []
    ) {
        $this->_appState = $appState;
        parent::__construct(
            $order,
            $customer,
            $customerSession,
            $coreRegistry,
            $context,
            $addressRenderer,
            $purchasedFactory,
            $defaultRenderer,
            $itemsFactory,
            $mpOrderModel,
            $creditmemoModel,
            $creditmemoItem,
            $invoiceModel,
            $saleslistModel,
            $ordersHelper,
            $productRepository,
            $shippingConfig,
            $carrierFactory,
            $itemCollectionFactory,
            $data
        );
    }

    /**
     * Get Deploy Mode
     *
     * @return string
     */
    public function getDeployMode()
    {
        return $this->_appState->getMode();
    }
    /**
     * Check CanShip and Shipment
     *
     * @param Order $order
     * @return bool
     */
    public function canWkOrderCancel(Order $order):bool
    {
        if (!$order->canCancel()) {
            return false;
        }
        if ($this->isOrderHavingShipment($order)) {
            return false;
        }

        return true;
    }

    /**
     * Check Shipment
     *
     * @param Order $order
     * @return bool
     */
    public function isOrderHavingShipment(Order $order): bool
    {
        return $order->getShipmentsCollection()->getSize() > 0;
    }

    /**
     * Get All Carriers
     *
     * @return array
     */
    public function getCarriers()
    {
        $carriers = [];
        $carrierInstances = $this->_getCarriersInstances();
        foreach ($carrierInstances as $code => $carrier) {
            if ($carrier->isTrackingAvailable()) {
                $carriers[$code] = $carrier->getConfigData('title');
            }
        }
        $result = $this->softCarriers($carriers);
        $result['custom'] = __('Custom Value');
        return $result;
    }

    /**
     * Get all Carriers
     *
     * @return array
     */
    protected function _getCarriersInstances()
    {
        $shippingConfig = $this->shippingConfig;
        return $shippingConfig->getAllCarriers();
    }

    /**
     * Soft Carriers
     *
     * @param array $carriers
     * @return array
     */
    public function softCarriers($carriers): array
    {
        $softCarriers = array_replace(array_flip(Constant::LIST_CARRIERS_SOFT), $carriers);
        return $softCarriers;
    }

    /**
     * Get Default Carrier Code
     *
     * @return string
     */
    public function getDefaultCarrierCode()
    {
        return Constant::DEFAULT_CARRIER_CODE;
    }

    /**
     * Get Default Carrier Title
     *
     * @return string
     */
    public function getDefaultCarrierTitle()
    {
        return Constant::DEFAULT_CARRIER_TITLE;
    }


    /**
     * Get links
     *
     * @return array
     */
    public function getLinks()
    {
        $this->checkLinks();

        return $this->_links;
    }

    /**
     * Check link
     *
     * @return void
     */
    private function checkLinks()
    {
        $order = $this->getOrder();
        $orderId = $order->getId();
        $shipmentId = '';
        $invoiceId = '';
        $creditmemoId = '';
        $tracking = $this->ordersHelper->getOrderinfo($orderId);
        if ($tracking) {
            $shipmentId = $tracking->getShipmentId();
            $invoiceId = $tracking->getInvoiceId();
            $creditmemoId = $tracking->getCreditmemoId();
        }
        $this->_links['order'] = [
            'name' => 'order',
            'label' => __('Items Ordered'),
            'url' => $this->_urlBuilder->getUrl(
                'marketplace/order/view',
                [
                    'order_id' => $orderId,
                    '_secure' => $this->getRequest()->isSecure()
                ]
            ),
        ];
        if (!$order->hasInvoices()) {
            unset($this->_links['invoice']);
        } else {
            if ($invoiceId) {
                $this->_links['invoice'] = [
                    'name' => 'invoice',
                    'label' => __('Invoices'),
                    'url' => $this->_urlBuilder->getUrl(
                        'marketplace/order_invoice/view',
                        [
                            'order_id' => $orderId,
                            'invoice_id' => $invoiceId,
                            '_secure' => $this->getRequest()->isSecure()
                        ]
                    ),
                ];
            }
        }

        if (!$order->hasCreditmemos()) {
            unset($this->_links['creditmemo']);
        } else {
            if ($creditmemoId) {
                $this->_links['creditmemo'] = [
                    'name' => 'creditmemo',
                    'label' => __('Refunds'),
                    'url' => $this->_urlBuilder->getUrl(
                        'marketplace/order_creditmemo/viewlist',
                        [
                            'order_id' => $orderId,
                            '_secure' => $this->getRequest()->isSecure()
                        ]
                    ),
                ];
            }
        }
    }
}
