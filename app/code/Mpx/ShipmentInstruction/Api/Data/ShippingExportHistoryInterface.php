<?php

namespace Mpx\ShipmentInstruction\Api\Data;

interface ShippingExportHistoryInterface
{
    const ENTITY_ID = 'entity_id';
    const CARRIER_CODE = 'carrier_code';
    const FORMAT = 'format';
    const CREATED_AT = 'created_at';

    /**
     * @return int
     */
    public function getEntityId();

    /**
     * @return string
     */
    public function getCarrierCode();

    /**
     * @return string
     */
    public function getFormat();


    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * Set Carrier Code
     *
     * @param string $carrierCode
     * @return \Mpx\ShipmentInstruction\Api\Data\ShippingExportHistoryInterface
     */
    public function setCarrierCode($carrierCode);

    /**
     * Set Format
     *
     * @param string $format
     * @return \Mpx\ShipmentInstruction\Api\Data\ShippingExportHistoryInterface
     */
    public function setFormat($format);

    /**
     * Set Created At
     *
     * @param string $createdAt
     * @return \Mpx\ShipmentInstruction\Api\Data\ShippingExportHistoryInterface
     */
    public function setCreatedAt($createdAt);
}
