<?php
namespace XShoppingSt\Marketplace\Api\Data;

/**
 * Marketplace Feedback Interface
 * @api
 */
interface FeedbackInterface
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
     * @return \XShoppingSt\Marketplace\Api\Data\FeedbackInterface
     */
    public function setId($id);
}
