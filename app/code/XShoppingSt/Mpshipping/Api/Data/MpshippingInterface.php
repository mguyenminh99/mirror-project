<?php
namespace XShoppingSt\Mpshipping\Api\Data;

interface MpshippingInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const MPSHIPPING_ID = 'mpshipping_id';
    /**#@-*/

    /**
     * Get Mpshipping ID
     *
     * @return int|null
     */
    public function getMpshippingId();
    /**
     * Set Mpshipping ID
     *
     * @param int $id
     * @return \XShoppingSt\Mpshipping\Api\Data\MpshippingInterface
     */
    public function setMpshippingId($id);
}
