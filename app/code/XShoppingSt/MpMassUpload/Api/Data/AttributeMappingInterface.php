<?php
namespace XShoppingSt\MpMassUpload\Api\Data;

interface AttributeMappingInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    const ENTITY_ID = 'entity_id';
    /**#@-*/

    const PROFILE_ID = 'profile_id';

    const FILE_ATTRIBUTE = 'file_attribute';

    const MAGE_ATTRIBUTE = 'mage_attribute';

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
     * @return \XShoppingSt\MpMassUpload\Api\Data\AttributeMappingInterface
     */
    public function setId($id);

    /**
     * Get Profile Id.
     *
     * @return int|null
     */
    public function getProfileId();

    /**
     * Set Profile Id.
     *
     * @param int $profileId
     *
     * @return \XShoppingSt\MpMassUpload\Api\Data\AttributeMappingInterface
     */
    public function setProfileId($profileId);

    /**
     * Get File Attribute.
     *
     * @return string|null
     */
    public function getFileAttribute();

    /**
     * Set File Attribute.
     *
     * @param int $fileAttribute
     *
     * @return \XShoppingSt\MpMassUpload\Api\Data\AttributeMappingInterface
     */
    public function setFileAttribute($fileAttribute);

    /**
     * Get Magento Attribute.
     *
     * @return string|null
     */
    public function getMageAttribute();

    /**
     * Set Magento Attribute.
     *
     * @param string $mageAttribute
     *
     * @return \XShoppingSt\MpMassUpload\Api\Data\AttributeMappingInterface
     */
    public function setMageAttribute($mageAttribute);
}
