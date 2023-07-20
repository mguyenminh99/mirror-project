<?php
namespace XShoppingSt\MarketplaceBaseShipping\Controller\Order\Shipment;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

class CreateLabel extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Magento_Sales::shipment';

    /**
     * @var \Magento\Shipping\Controller\Adminhtml\Order\ShipmentLoader
     */
    protected $shipmentLoader;

    /**
     * @var \Magento\Shipping\Model\Shipping\LabelGenerator
     */
    protected $_labelGenerator;

    /**
     * @param Context $context
     * @param \Magento\Shipping\Controller\Adminhtml\Order\ShipmentLoader $shipmentLoader
     * @param \Magento\Shipping\Model\Shipping\LabelGenerator $labelGenerator
     */
    public function __construct(
        \XShoppingSt\MarketplaceBaseShipping\Helper\Data $baseShippingHelper,
        Context $context,
        \Magento\Shipping\Controller\Adminhtml\Order\ShipmentLoader $shipmentLoader,
        \XShoppingSt\MarketplaceBaseShipping\Model\Shipping\LabelGenerator $labelGenerator
    ) {
        $this->baseShippingHelper = $baseShippingHelper;
        $this->shipmentLoader = $shipmentLoader;
        $this->_labelGenerator = $labelGenerator;
        parent::__construct($context);
    }

    /**
     * Create shipping label action for specific shipment
     *
     * @return void
     */
    public function execute()
    {
        $response = new \Magento\Framework\DataObject();
        $displayErrors=$this->baseShippingHelper->displayErrors();
        try {
            $this->shipmentLoader->setShipmentId($this->getRequest()->getParam('shipment_id'));
            $shipment = $this->shipmentLoader->load();
            $this->_labelGenerator->create($shipment, $this->_request);
            $shipment->save();
            $this->messageManager->addSuccess(__('You created the shipping label.'));
            $response->setOk(true);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $response->setError(true);
            $response->setMessage($e->getMessage());
        } catch (\Exception $e) {
            $displayErrors->critical($e->getMessage());
            $response->setError(true);
            $response->setMessage(__('An error occurred while creating shipping label.'));
        }

        $this->getResponse()->representJson($response->toJson());
    }
}