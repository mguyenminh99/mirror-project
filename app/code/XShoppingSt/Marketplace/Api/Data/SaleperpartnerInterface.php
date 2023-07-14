<?php
namespace XShoppingSt\Marketplace\Api\Data;

/**
 * Marketplace Saleperpartner interface.
 * @api
 */
interface SaleperpartnerInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ENTITY_ID    = 'entity_id';
    /**#@-*/

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
     * @return \XShoppingSt\Marketplace\Api\Data\SaleperpartnerInterface
     */
    public function setId($id);
}
