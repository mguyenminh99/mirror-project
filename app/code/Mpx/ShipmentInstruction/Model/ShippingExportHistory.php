<?php
namespace Mpx\ShipmentInstruction\Model;

use Magento\Framework\Model\AbstractModel;
use Mpx\ShipmentInstruction\Api\Data\ShippingExportHistoryInterface;

class ShippingExportHistory extends AbstractModel implements ShippingExportHistoryInterface
{
    /**
     * Cache tag shipment
     */
    public const CACHE_TAG = 'mpx_shipping_export_history';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'mpx_shipping_export_history';

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
        $this->_init('Mpx\ShipmentInstruction\Model\ResourceModel\ShippingExportHistory');
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
    public function getFormat()
    {
        return $this->getData(self::FORMAT);
    }

    /**
     * @return mixed|string
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @param string $carrierCode
     * @return ShippingExportHistoryInterface|ShippingExportHistory
     */
    public function setCarrierCode($carrierCode)
    {
        return $this->setData(self::CARRIER_CODE, $carrierCode);
    }

    /**
     * @param string $format
     * @return ShippingExportHistoryInterface|ShippingExportHistory
     */
    public function setFormat($format)
    {
        return $this->setData(self::FORMAT, $format);
    }

    /**
     * @param string $createdAt
     * @return ShippingExportHistoryInterface|ShippingExportHistory
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }
}
