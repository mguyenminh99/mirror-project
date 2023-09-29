<?php
namespace Mpx\ShipmentInstruction\Api\Data;

interface ShipmentInstructionInterface
{
    const ENTITY_ID = 'entity_id';
    const SELLER_ID = 'seller_id';
    const INCREMENT_ORDER_ID = 'increment_order_id';
    const SKU = 'sku';
    const PRODUCT_NAME = 'product_name';
    const INSTRUCTED_QTY = 'instructed_qty';
    const DESTINATION_CUSTOMER_NAME = 'destination_customer_name';
    const DESTINATION_POSTCODE = 'destination_postcode';
    const DESTINATION_REGION = 'destination_region';
    const DESTINATION_CITY = 'destination_city';
    const DESTINATION_STREET = 'destination_street';
    const DESTINATION_TELEPHONE = 'destination_telephone';
    const DESIRED_DELIVERY_DATE = 'desired_delivery_date';
    const DESIRED_DELIVERY_TIME = 'desired_delivery_time';
    const CARRIER_CODE = 'carrier_code';
    const SHIPPING_LABEL_TYPE = 'shipping_label_type';
    const CSV_EXPORT_ID = 'csv_export_id';
    const SCHEDULED_SHIPPING_DATE = 'scheduled_shipping_date';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * @return int
     */
    public function getEntityId();

    /**
     * @return int
     */
    public function getSellerId();

    /**
     * @return string
     */
    public function getOrderIncrementId();

    /**
     * @return string
     */
    public function getSku();

    /**
     * @return string
     */
    public function getProductName();


    /**
     * @return int
     */
    public function getInstructedQty();


    /**
     * @return string
     */
    public function getDestinationCustomerName();


    /**
     * @return string
     */
    public function getDestinationPostcode();

    /**
     * @return string
     */
    public function getDestinationRegion();

    /**
     * @return string
     */
    public function getDestinationCity();

    /**
     * @return string
     */
    public function getDestinationStreet();

    /**
     * @return string
     */
    public function getDestinationTelephone();

    /**
     * @return string
     */
    public function getDesiredDeliveryDate();

    /**
     * @return string
     */
    public function getDesiredDeliveryTime();

    /**
     * @return string
     */
    public function getCarrierCode();

    /**
     * @return string
     */
    public function getShippingLabelType();

    /**
     * @return int
     */
    public function getCsvExportId();

    /**
     * @return string
     */
    public function getScheduledShippingDate();

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set Seller Id
     *
     * @param int $sellerId
     * @return \Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionInterface
     */
    public function setSellerId($sellerId);

    /**
     * Set order increment id
     *
     * @param int $orderIncrementId
     * @return \Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionInterface
     */
    public function setOrderIncrementId($orderIncrementId);

    /**
     * Set sku
     *
     * @param int $sku
     * @return \Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionInterface
     */
    public function setSku($sku);

    /**
     * Set instructed qty
     *
     * @param int $instructedQty
     * @return \Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionInterface
     */
    public function setInstructedQty($instructedQty);

    /**
     * Set destination Customer Name
     *
     * @param string $destinationCustomerName
     * @return \Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionInterface
     */
    public function setDestinationCustomerName($destinationCustomerName);

    /**
     * Set destination post code
     *
     * @param string $destinationPostcode
     * @return \Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionInterface
     */
    public function setDestinationPostcode($destinationPostcode);

    /**
     * Set destination region
     *
     * @param string $destinationRegion
     * @return \Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionInterface
     */
    public function setDestinationRegion($destinationRegion);

    /**
     * Set destination city
     *
     * @param string $destinationCity
     * @return \Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionInterface
     */
    public function setDestinationCity($destinationCity);

    /**
     * Set destination street
     *
     * @param string $destinationStreet
     * @return \Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionInterface
     */
    public function setDestinationStreet($destinationStreet);

    /**
     * Set destination Telephone
     *
     * @param string $destinationTelephone
     * @return \Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionInterface
     */
    public function setDestinationTelephone($destinationTelephone);

    /**
     * Set desired Delivery Date
     *
     * @param string $desiredDeliveryDate
     * @return \Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionInterface
     */
    public function setDesiredDeliveryDate($desiredDeliveryDate);

    /**
     * Set desired Delivery Time
     *
     * @param string $desiredDeliveryTime
     * @return \Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionInterface
     */
    public function setDesiredDeliveryTime($desiredDeliveryTime);

    /**
     * Set Csv Export Id
     *
     * @param int $csvExportId
     * @return \Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionInterface
     */
    public function setCsvExportId($csvExportId);

    /**
     * Set scheduled Shipping Date
     *
     * @param string $scheduledShippingDate
     * @return \Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionInterface
     */
    public function setScheduledShippingDate($scheduledShippingDate);

    /**
     * Set Created At
     *
     * @param string $createdAt
     * @return \Mpx\ShipmentInstruction\Api\Data\ShipmentInstructionInterface
     */
    public function setUpdatedAt($createdAt);
}
