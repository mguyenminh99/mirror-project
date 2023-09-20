<?php

namespace Mpx\ShipmentInstruction\Block\Export;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Stdlib\DateTime\DateTimeFactory;
use Mpx\ShipmentInstruction\Model\ShipmentInstructionFactory;
use Mpx\ShipmentInstruction\Model\ShippingExportHistoryFactory;
use Magento\Framework\Session\SessionManagerInterface;
use Mpx\ShipmentInstruction\Helper\Constant;

class CsvExportModal extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $dateTimeFactory;

    /**
     * @var ShippingExportHistoryFactory
     */
    protected $shippingExportHistoryFactory;

    /**
     * @var ShipmentInstructionFactory
     */
    protected $shipmentInstructionFactory;

    /**
     * @var SessionManagerInterface
     */
    protected $sessionManager;

    /**
     * @param Context $context
     * @param DateTimeFactory $dateTimeFactory
     */
    public function __construct(
        Context $context,
        DateTimeFactory $dateTimeFactory,
        ShippingExportHistoryFactory $shippingExportHistoryFactory,
        SessionManagerInterface $sessionManager,
        ShipmentInstructionFactory $shipmentInstructionFactory
    ) {
        $this->dateTimeFactory = $dateTimeFactory->create();
        $this->sessionManager = $sessionManager;
        $this->shippingExportHistoryFactory = $shippingExportHistoryFactory;
        $this->shipmentInstructionFactory = $shipmentInstructionFactory;
        parent::__construct($context);
    }

    /**
     * Get Current Date
     *
     * @return string
     */
    public function getCurrenDate() {
        return $this->dateTimeFactory->gmtDate('Y-m-d');
    }

    /**
     * Get Delivery Format
     *
     * @return string
     */
    public function getDeliveryFormat() {
        return $this->shippingExportHistoryFactory->create()->load($this->sessionManager->getData(Constant::CSV_EXPORT_ID_KEY))->getFormat();
    }

    /**
     * Get Estimated Ship Date
     *
     * @return string|null
     */
    public function getEstimatedShipDate() {
        return $this->shipmentInstructionFactory->create()->load($this->sessionManager->getData(Constant::CSV_EXPORT_ID_KEY), 'csv_export_id')->getScheduledShippingDate();
    }

    /**
     * Check UI shipment page
     *
     * @return bool
     */
    public function isShipmentInsExportedPage() {
        return $this->sessionManager->getData(Constant::SHIPMENT_PAGE_KEY) === Constant::EXPORTED_SHIPMENT_INSTRUCTION_GRID_CODE;
    }
}
