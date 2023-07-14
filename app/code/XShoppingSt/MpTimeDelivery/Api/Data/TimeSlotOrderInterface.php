<?php
namespace XShoppingSt\MpTimeDelivery\Api\Data;

interface TimeSlotOrderInterface
{
    const ENTITY_ID             = 'id';
    const SELLER_ID             = 'seller_id';
    const SLOT_ID               = 'slot_id';
    const ORDER_ID              = 'order_id';
    const SELECTED_DATE         = 'selected_date';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get Seller ID
     *
     * @return int|null
     */
    public function getSellerId();

    /**
     * Get Slot ID
     *
     * @return int|null
     */
    public function getSlotId();

    /**
     * Get Order ID
     *
     * @return int|null
     */
    public function getOrderId();

    /**
     * Get selected date
     *
     * @return int|null
     */
    public function getSelectedDate();

    /**
     * Set ID
     *
     * @return \XShoppingSt\MpTimeDelivery\Api\Data\TimeSlotOrderInterface
     */
    public function setId($id);

    /**
     * Set Seller ID
     *
     * @return \XShoppingSt\MpTimeDelivery\Api\Data\TimeSlotOrderInterface
     */
    public function setSellerId($sellerId);

    /**
     * Set Slot ID
     *
     * @return \XShoppingSt\MpTimeDelivery\Api\Data\TimeSlotOrderInterface
     */
    public function setSlotId($id);

    /**
     * Set Order ID
     *
     * @return \XShoppingSt\MpTimeDelivery\Api\Data\TimeSlotOrderInterface
     */
    public function setOrderId($orderId);

    /**
     * Set selected date
     *
     * @return \XShoppingSt\MpTimeDelivery\Api\Data\TimeSlotOrderInterface
     */
    public function setSelectedDate($date);
}
