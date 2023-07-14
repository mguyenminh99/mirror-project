<?php
namespace XShoppingSt\Marketplace\Api\Data;

/**
 * Marketplace Orders interface.
 * @api
 */
interface OrdersInterface
{
    const ENTITY_ID = 'entity_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set ID
     *
     * @param int $id
     * @return \XShoppingSt\Marketplace\Api\Data\OrdersInterface
     */
    public function setId($id);

    /**
     * Get Created Time
     *
     * @return int|null
     */
    public function getCreatedAt();

    /**
     * Set Created Time
     *
     * @param int $createdAt
     * @return \XShoppingSt\Marketplace\Api\Data\OrdersInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get Updated Time
     *
     * @return int|null
     */
    public function getUpdatedAt();

    /**
     * Set Updated Time
     *
     * @param int $updatedAt
     * @return \XShoppingSt\Marketplace\Api\Data\OrdersInterface
     */
    public function setUpdatedAt($updatedAt);
}
