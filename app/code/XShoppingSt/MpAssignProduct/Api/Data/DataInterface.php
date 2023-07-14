<?php
namespace XShoppingSt\MpAssignProduct\Api\Data;

interface DataInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    const ENTITY_ID = 'id';
    /**#@-*/

    /**
     * Get ID.
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set ID.
     *
     * @param int $id
     *
     * @return \XShoppingSt\MpAssignProduct\Api\Data\DataInterface
     */
    public function setId($id);
}
