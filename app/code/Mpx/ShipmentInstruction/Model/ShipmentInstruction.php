<?php
namespace Mpx\ShipmentInstruction\Model;

use Magento\Framework\Model\AbstractModel;
use Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionInterface;

class ShipmentInstruction extends AbstractModel implements ShipmentInstructionInterface
{
    /**
     * Cache tag shipment
     */
    public const CACHE_TAG = 'mpx_shipment_instruction';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'mpx_shipment_instruction';

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $dateTime;

    /**
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [])
    {
        $this->dateTime = $dateTime;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('Mpx\ShipmentInstruction\Model\ResourceModel\ShipmentInstruction');
    }

    /**
     * @return ShipmentInstruction
     */
    public function beforeSave()
    {
        if($this->hasDataChanges()){
            $this->setUpdatedAt($this->dateTime->gmtDate());
        }
        return parent::beforeSave();
    }

    /**
     * @return int
     */
    public function getSellerId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * @return mixed|string
     */
    public function getOrderIncrementId()
    {
        return $this->getData(self::INCREMENT_ORDER_ID);
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->getData(self::SKU);
    }

    /**
     * @return string
     */
    public function getProductName()
    {
        return $this->getData(self::PRODUCT_NAME);
    }

    /**
     * @return int
     */
    public function getInstructedQty()
    {
        return $this->getData(self::INSTRUCTED_QTY);
    }

    /**
     * @return string
     */
    public function getDestinationCustomerName()
    {
        return $this->getData(self::DESTINATION_CUSTOMER_NAME);
    }

    /**
     * @return string
     */
    public function getDestinationPostcode()
    {
        return $this->getData(self::DESTINATION_POSTCODE);
    }

    /**
     * @return string
     */
    public function getDestinationRegion()
    {
        return $this->getData(self::DESTINATION_REGION);
    }

    /**
     * @return string
     */
    public function getDestinationCity()
    {
        return $this->getData(self::DESTINATION_CITY);
    }

    /**
     * @return string
     */
    public function getDestinationStreet()
    {
        return $this->getData(self::DESTINATION_STREET);
    }

    /**
     * @return string
     */
    public function getDestinationTelephone()
    {
        return $this->getData(self::DESTINATION_TELEPHONE);
    }

    /**
     * @return string|null
     */
    public function getDesiredDeliveryDate()
    {
        return $this->getData(self::DESIRED_DELIVERY_DATE);
    }

    /**
     * @return string|null
     */
    public function getDesiredDeliveryTime()
    {
        return $this->getData(self::DESIRED_DELIVERY_TIME);
    }

    /**
     * @return string
     */
    public function getCarrierCode()
    {
        return $this->getData(self::CARRIER_CODE);
    }

    /**
     * @return string
     */
    public function getShippingLabelType()
    {
        return $this->getData(self::SHIPPING_LABEL_TYPE);
    }

    /**
     * @return int|null
     */
    public function getCsvExportId()
    {
        return $this->getData(self::CSV_EXPORT_ID);
    }

    /**
     * @return string|null
     */
    public function getScheduledShippingDate()
    {
        return $this->getData(self::SCHEDULED_SHIPPING_DATE);
    }

    /**
     * @return mixed|string
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @param int $sellerId
     * @return ShipmentInstructionInterface|ShipmentInstruction
     */
    public function setSellerId($sellerId)
    {
        return $this->setData(self::SELLER_ID, $sellerId);
    }

    /**
     * @param string $orderIncrementId
     * @return ShipmentInstructionInterface|ShipmentInstruction
     */
    public function setOrderIncrementId($orderIncrementId)
    {
        return $this->setData(self::INCREMENT_ORDER_ID, $orderIncrementId);
    }

    /**
     * @param string $sku
     * @return ShipmentInstructionInterface|ShipmentInstruction
     */
    public function setSku($sku)
    {
        return $this->setData(self::SKU, $sku);
    }

    /**
     * @param int $instructedQty
     * @return ShipmentInstructionInterface|ShipmentInstruction
     */
    public function setInstructedQty($instructedQty)
    {
        return $this->setData(self::INSTRUCTED_QTY, $instructedQty);
    }

    /**
     * @param string $destinationCustomerName
     * @return ShipmentInstructionInterface|ShipmentInstruction
     */
    public function setDestinationCustomerName($destinationCustomerName)
    {
        return $this->setData(self::DESTINATION_CUSTOMER_NAME, $destinationCustomerName);
    }

    /**
     * @param string $destinationPostcode
     * @return ShipmentInstructionInterface|ShipmentInstruction
     */
    public function setDestinationPostcode($destinationPostcode)
    {
        return $this->setData(self::DESTINATION_POSTCODE, $destinationPostcode);
    }

    /**
     * @param string $destinationRegion
     * @return ShipmentInstructionInterface|ShipmentInstruction
     */
    public function setDestinationRegion($destinationRegion)
    {
        return $this->setData(self::DESTINATION_REGION, $destinationRegion);
    }

    /**
     * @param string $destinationCity
     * @return ShipmentInstructionInterface|ShipmentInstruction
     */
    public function setDestinationCity($destinationCity)
    {
        return $this->setData(self::DESTINATION_CITY, $destinationCity);
    }

    /**
     * @param string $destinationStreet
     * @return ShipmentInstructionInterface|ShipmentInstruction
     */
    public function setDestinationStreet($destinationStreet)
    {
        return $this->setData(self::DESTINATION_STREET, $destinationStreet);
    }

    /**
     * @param string $destinationTelephone
     * @return ShipmentInstructionInterface|ShipmentInstruction
     */
    public function setDestinationTelephone($destinationTelephone)
    {
        return $this->setData(self::DESTINATION_TELEPHONE, $destinationTelephone);
    }

    /**
     * @param string $desiredDeliveryDate
     * @return ShipmentInstructionInterface|ShipmentInstruction
     */
    public function setDesiredDeliveryDate($desiredDeliveryDate)
    {
        return $this->setData(self::DESIRED_DELIVERY_DATE, $desiredDeliveryDate);
    }

    /**
     * @param string $desiredDeliveryTime
     * @return ShipmentInstructionInterface|ShipmentInstruction
     */
    public function setDesiredDeliveryTime($desiredDeliveryTime)
    {
        return $this->setData(self::DESIRED_DELIVERY_TIME, $desiredDeliveryTime);
    }

    /**
     * @param int $csvExportId
     * @return ShipmentInstructionInterface|ShipmentInstruction
     */
    public function setCsvExportId($csvExportId)
    {
        return $this->setData(self::CSV_EXPORT_ID, $csvExportId);
    }

    /**
     * @param string $scheduledShippingDate
     * @return ShipmentInstructionInterface|ShipmentInstruction
     */
    public function setScheduledShippingDate($scheduledShippingDate)
    {
        return $this->setData(self::SCHEDULED_SHIPPING_DATE, $scheduledShippingDate);
    }

    /**
     * @param string $createdAt
     * @return ShipmentInstructionInterface|ShipmentInstruction
     */
    public function setUpdatedAt($createdAt)
    {
        return $this->setData(self::UPDATED_AT, $createdAt);
    }
}
