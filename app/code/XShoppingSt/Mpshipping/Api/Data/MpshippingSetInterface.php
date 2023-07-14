<?php
namespace XShoppingSt\Mpshipping\Api\Data;

interface MpshippingSetInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ENTITY_ID = 'entity_id';
    /**#@-*/

    /**
     * Get Entity ID
     *
     * @return int|null
     */
    public function getEntityId();
    /**
     * Set Entity ID
     *
     * @param int $id
     * @return \XShoppingSt\Mpshipping\Api\Data\MpshippingSetInterface
     */
    public function setEntityId($id);
}
