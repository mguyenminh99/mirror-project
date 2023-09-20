<?php

namespace Mpx\ShipmentInstruction\Controller\Mui\Export;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Response\Http\FileFactory;
use Mpx\ShipmentInstruction\Model\ShippingExportHistoryFactory;
use XShoppingSt\Marketplace\Helper\Data;
use XShoppingSt\MarketplaceBaseShipping\Model\ShippingSettingFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;
use Mpx\ShipmentInstruction\Model\ResourceModel\ShipmentInstruction\CollectionFactory as ShipmentInstructionCollectionFactory;
use Mpx\ShipmentInstruction\Service\ShipmentInstruction\B2CloudExport;
use Mpx\ShipmentInstruction\Helper\Constant;
use Magento\Framework\Session\SessionManagerInterface;

/**
 * Class GridToCsv used to export the grid data in to csv.
 */
class GridToCsv extends Action
{
    /**
     * @var B2CloudExport
     */
    protected $b2CloudExport;

    /**
     * @var ShippingSettingFactory
     */
    protected $shippingSettingFactory;

    /**
     * @var FileFactory
     */
    protected $httpFile;

    /**
     * @var Data
     */
    protected $helperData;

    protected $shipmentInstructions;

    /**
     * @var \Mpx\ShipmentInstruction\Helper\Data
     */
    protected $shipmentInstructionHelper;

    /**
     * @var ShippingExportHistoryFactory
     */
    protected $shippingExportHistoryFactory;

    /**
     * @var SessionManagerInterface
     */
    protected $sessionManager;

    /**
     * @param B2CloudExport $b2CloudExport
     * @param Context $context
     * @param FileFactory $httpFile
     * @param Data $helperData
     * @param ShipmentInstructionCollectionFactory $shipmentInstructionCollectionFactory
     * @param \Mpx\ShipmentInstruction\Helper\Data $shipmentInstructionHelper
     * @param ShippingSettingFactory $shippingSettingFactory
     */
    public function __construct(
        B2CloudExport $b2CloudExport,
        Context $context,
        FileFactory $httpFile,
        Data $helperData,
        SessionManagerInterface $sessionManager,
        ShipmentInstructionCollectionFactory $shipmentInstructionCollectionFactory,
        \Mpx\ShipmentInstruction\Helper\Data $shipmentInstructionHelper,
        ShippingSettingFactory $shippingSettingFactory,
        ShippingExportHistoryFactory $shippingExportHistoryFactory
    ) {
        parent::__construct($context);
        $this->b2CloudExport = $b2CloudExport;
        $this->httpFile = $httpFile;
        $this->shippingSettingFactory = $shippingSettingFactory;
        $this->helperData = $helperData;
        $this->sessionManager = $sessionManager;
        $this->shipmentInstructions = $shipmentInstructionCollectionFactory->create()
            ->addFieldToFilter('instructed_qty', ['gt' => 0]);
        if ($this->sessionManager->getData(Constant::SHIPMENT_PAGE_KEY) === Constant::UN_EXPORTED_SHIPMENT_INSTRUCTION_GRID_CODE) {
            $this->shipmentInstructions->addFieldToFilter('csv_export_id', ['null' => true]);
        } else {
            $this->shipmentInstructions->addFieldToFilter('csv_export_id', ['eq' => $this->sessionManager->getData(Constant::CSV_EXPORT_ID_KEY)]);
        }
        $this->shipmentInstructionHelper = $shipmentInstructionHelper;
        $this->shippingExportHistoryFactory = $shippingExportHistoryFactory;
    }

    /**
     * Export UI List data to CSV
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|(\Magento\Framework\Controller\Result\Redirect&\Magento\Framework\Controller\ResultInterface)|\Magento\Framework\Controller\ResultInterface|void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $sellerId = $this->helperData->getCustomerId();
        $this->shipmentInstructions->addFieldToFilter('seller_id', $sellerId);
        $shippingSettingData = $this->shippingSettingFactory->create()->load($sellerId, 'seller_id');
        if (!$this->isSetSellerShippingInfo($shippingSettingData)) {
            $this->messageManager->addErrorMessage(__('Please enter the required items on the shipping settings screen.'));
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        }
        // need entity IDs list before records grouped by to update sales_shipment_instruction.csv_export_id later
        $shipmentInstructionIds = $this->getShippingInstructionIds($this->shipmentInstructions);
        $this->shipmentInstructions->getSelect()->group([
            'seller_id',
            'increment_order_id',
            'destination_customer_name',
            'destination_postcode',
            'destination_region',
            'destination_city',
            'destination_street',
            'destination_telephone',
            'desired_delivery_date',
            'desired_delivery_time'
        ]);

        $exportData = $this->getShippingInstructionExportData($this->shipmentInstructions, $shippingSettingData, $sellerId);
        if ($this->sessionManager->getData(Constant::SHIPMENT_PAGE_KEY) === Constant::UN_EXPORTED_SHIPMENT_INSTRUCTION_GRID_CODE) {
            $shippingExportHistory = $this->createShippingExportHistoryRecord();
            $this->setCsvExportId($shipmentInstructionIds, $shippingExportHistory, $this->shipmentInstructions);
        }
        array_unshift($exportData, $this->b2CloudExport->getHeader());

        if (!empty($exportData)) {
            $csvFile = $this->shipmentInstructionHelper->createCsvFileFromData($exportData);
            return $this->httpFile->create('export.csv', $csvFile, 'var');
        }
    }

    /**
     * Create Shipping Export History Record
     *
     * @return \Mpx\ShipmentInstruction\Model\ShippingExportHistory
     * @throws \Exception
     */
    private function createShippingExportHistoryRecord() {
        $shippingExportHistory = $this->shippingExportHistoryFactory->create();
        $shippingExportHistory->setCarrierCode(Constant::YAMATO_TRANSPORT_CARRIER_CODE);
        $shippingExportHistory->setFormat($this->getRequest()->getParam('delivery_format'));
        $shippingExportHistory->save();
        return $shippingExportHistory;
    }

    /**
     * Check seller shipping info
     *
     * @param $shippingSettingData
     * @return bool
     */
    private function isSetSellerShippingInfo($shippingSettingData){
         return !empty($shippingSettingData->getTelephone())  &&
                !empty($shippingSettingData->getPostalCode()) &&
                !empty($shippingSettingData->getRegion())     &&
                !empty($shippingSettingData->getCity())       &&
                !empty($shippingSettingData->getStreet())     &&
                !empty($shippingSettingData->getCountryId());
    }

    /**
     * Set csv export id
     *
     * @param $shipmentInstructionIds
     * @param $shippingExportHistory
     * @param $shipmentInstructions
     * @return void
     */
    private function setCsvExportId($shipmentInstructionIds, $shippingExportHistory, $shipmentInstructions){
        $updateCsvExportIdData = [];
        foreach ($shipmentInstructionIds as $entityId) {
            $updateCsvExportIdData[] = [
                'entity_id' => $entityId,
                'csv_export_id' => $shippingExportHistory->getEntityId(),
                'scheduled_shipping_date' => date($this->getRequest()->getParam('estimated_ship_date'))
            ];
        }
        if ($updateCsvExportIdData) {
            $connection = $shipmentInstructions->getResource()->getConnection();
            $connection->insertOnDuplicate('sales_shipment_instruction',
                $updateCsvExportIdData,
                [
                    'entity_id',
                    'csv_export_id',
                    'scheduled_shipping_date'
                ]);
        }
    }

    /**
     * Get Shipping InstructionIds
     *
     * @param $shipmentInstructions
     * @return mixed
     */
    private function getShippingInstructionIds($shipmentInstructions){
        $selectedData = $this->getRequest()->getParam('selected');
        $excludedData = $this->getRequest()->getParam('excluded');
        if ($selectedData == 'false' || $excludedData == 'false') {
            $shipmentInstructionIds = $shipmentInstructions->getAllIds();
        } else if ($excludedData) {
            $shipmentInstructionIds = $shipmentInstructions->addFieldToFilter('entity_id', ['nin' => $excludedData])->getAllIds();
        } else {
            $shipmentInstructionIds = $shipmentInstructions->addFieldToFilter('entity_id', $selectedData)->getAllIds();
        }
        return $shipmentInstructionIds;
    }

    /**
     * Get shipping instruction export data
     *
     * @param $shipmentInstructions
     * @param $shippingSettingData
     * @param $sellerId
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getShippingInstructionExportData($shipmentInstructions, $shippingSettingData, $sellerId) {
        $exportData = [];
        foreach ($shipmentInstructions as $shipmentInstruction) {
            $exportData[] = $this->b2CloudExport->prepareExportData(
                $shipmentInstruction, $shippingSettingData, $sellerId);
        }
        return $exportData;
    }
}
