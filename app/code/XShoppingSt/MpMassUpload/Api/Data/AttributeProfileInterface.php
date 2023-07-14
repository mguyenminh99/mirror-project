<?php
namespace XShoppingSt\MpMassUpload\Api\Data;

interface AttributeProfileInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    const ENTITY_ID = 'entity_id';
    /**#@-*/

    const SELLER_ID = 'seller_id';

    const PROFILE_NAME = 'profile_name';

    const ATTRIBUTE_SET_ID = 'attribute_set_id';

    const CREATED_DATE = 'created_date';

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
     * @return \XShoppingSt\MpMassUpload\Api\Data\ProfileInterface
     */
    public function setId($id);

    /**
     * Get Seller Id.
     *
     * @return int|null
     */
    public function getSellerId();

    /**
     * Set Seller Id.
     *
     * @param int $sellerId
     *
     * @return \XShoppingSt\MpMassUpload\Api\Data\ProfileInterface
     */
    public function setSellerId($sellerId);

    /**
     * Get Profile Name.
     *
     * @return string|null
     */
    public function getProfileName();

    /**
     * Set Profile Name.
     *
     * @param string $profileName
     *
     * @return \XShoppingSt\MpMassUpload\Api\Data\ProfileInterface
     */
    public function setProfileName($profileName);

    /**
     * Get Attribute Set Id.
     *
     * @return int|null
     */
    public function getAttributeSetId();

    /**
     * Set Attribute Set Id.
     *
     * @param int $attributeSetId
     *
     * @return \XShoppingSt\MpMassUpload\Api\Data\ProfileInterface
     */
    public function setAttributeSetId($attributeSetId);

    /**
     * Get Created Date.
     *
     * @return date|null
     */
    public function getCreatedDate();

    /**
     * Set Created Date.
     *
     * @param string $createdDate
     *
     * @return \XShoppingSt\MpMassUpload\Api\Data\ProfileInterface
     */
    public function setCreatedDate($createdDate);
}
