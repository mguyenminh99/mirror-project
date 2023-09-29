<?php
namespace Mpx\ShipmentInstruction\Block\ShipmentInstruction;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\ScopeInterface;
use Mpx\ShipmentInstruction\Model\ShipmentInstructionRepository;
use Mpx\ShipmentInstruction\Model\ResourceModel\ShipmentInstruction\CollectionFactory as ShipmentInstructionCollection;
use Magento\Directory\Model\ResourceModel\Region\CollectionFactory;
use XShoppingSt\MpTimeDelivery\Model\TimeSlotConfigProvider;
use Magento\Customer\Model\Session;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Sales\Model\OrderFactory;
use Mpx\ShipmentInstruction\Helper\Data as HelperData;

class Edit extends Template
{
    /**
     * @var OrderFactory
     */
    protected $orderFactory;

    /**
     * @var
     */
    protected $regionCollectionFactory;

    /**
     * @var ShipmentInstructionRepository
     */
    public $shipmentInstructionRepository;

    /**
     * @var \XShoppingSt\MpTimeDelivery\Model\ResourceModel\TimeSlotConfig\CollectionFactory
     */
    protected $timeSlotConfigCollecionFactory;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var ShipmentInstructionCollection
     */
    protected $shipmentInstructionCollection;

    /**
     * @param Context                   $context
     * @param array                     $data
     */
    public function __construct(
        \Magento\Sales\Model\OrderFactory $orderFactory,
        Context $context,
        ShipmentInstructionRepository $shipmentInstructionRepository,
        CollectionFactory $regionCollectionFactory,
        \XShoppingSt\MpTimeDelivery\Model\ResourceModel\TimeSlotConfig\CollectionFactory $timeSlotConfigCollecionFactory,
        Session $customerSession,
        DateTime $dateTime,
        HelperData $helperData,
        ShipmentInstructionCollection $shipmentInstructionCollection,
        array $data = []
    ) {
        $this->orderFactory = $orderFactory;
        $this->shipmentInstructionRepository = $shipmentInstructionRepository;
        $this->regionCollectionFactory = $regionCollectionFactory;
        $this->timeSlotConfigCollecionFactory = $timeSlotConfigCollecionFactory;
        $this->customerSession = $customerSession;
        $this->dateTime = $dateTime;
        $this->helperData = $helperData;
        $this->shipmentInstructionCollection = $shipmentInstructionCollection;
        parent::__construct($context, $data);
    }

    /**
     * @param $id
     * @return \Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getShipmentInstruction($id){
        return $this->shipmentInstructionRepository->get($id);
    }

    /**
     * @return array|null
     */
    public function getAllRegions(){
        return $this->regionCollectionFactory->create()->addCountryFilter("JP")->getData();
    }

    /**
     * @return array|null
     */
    public function getTimeSlotCollection()
    {
        return $this->timeSlotConfigCollecionFactory->create()->getData();
    }

    /**
     * @return false|string
     */
    public function getStartDayShip()
    {
        $customer = $this->customerSession->getCustomer();
        $minimumTimeRequired = $customer->getMinimumTimeRequired();
        return date('Y-m-d H:i:s', strtotime($this->dateTime->date(). ' + '.$minimumTimeRequired.' days'));
    }

    /**
     * @return mixed
     */
    public function getMaxDaysTimeSlotConfig()
    {
        return $this->_scopeConfig->getValue(TimeSlotConfigProvider::XPATH_MAX_DAYS, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @param $incrementId
     * @return float|null
     */
    public function getOrderQty($incrementId, $sku){
        $orderItems = $this->orderFactory->create()->loadByIncrementId($incrementId)->getAllItems();
        foreach ($orderItems as $item){
            if($item->getSku() == $sku){
                return $item->getQtyOrdered();
            }
        }
    }

    /**
     * @param $sellerId
     * @return \XShoppingSt\MpTimeDelivery\Model\ResourceModel\TimeSlotConfig\Collection
     */
    public function getTimeSlotConfig($sellerId){
        $collection =  $this->timeSlotConfigCollecionFactory->create();
        $collection->addFieldToFilter(
                'seller_id',
                ['eq' => $sellerId]
            )->getSelect()
            ->group('seller_id')
            ->group('start_time')
            ->group('end_time');
        return $collection;
    }

    /**
     * @param $time
     * @return false|string
     */
    public function getFormatTime($time){
        return $this->dateTime->gmtDate('h:i A', $time);
    }

    /**
     * @param $id
     * @return \Magento\Framework\DataObject
     */
    public function getShipmentInstructionCollectionById($id) {
        $shipmentInstruction = $this->shipmentInstructionCollection->create();
        $shipmentInstruction->getSelect()
            ->join(
                'sales_shipment_shipping_label_export_history as ssh',
                'main_table.csv_export_id=ssh.entity_id AND main_table.entity_id=' . $id,
                [
                    'ssh.carrier_code as delivery_company',
                    'ssh.format as format'
                ]
            );
        return $shipmentInstruction->getFirstItem();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getDeliveryCompanyName($id) {
        return $this->getShipmentInstructionCollectionById($id)->getDeliveryCompany();
    }

    /**
     * @param $id
     * @return array|\Magento\Framework\Phrase|mixed|null
     */
    public function getInvoiceType($id) {
        $invoiceType = $this->getShipmentInstructionCollectionById($id)->getFormat();
        $listInvoiceType = $this->helperData->getListInvoiceType();
        foreach ($listInvoiceType as $key => $value) {
            $key = strval($key);
            if ($invoiceType === $key) {
                $invoiceType = __($value);
            }
        }
        return $invoiceType;
    }
}
