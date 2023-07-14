<?php
namespace XShoppingSt\MpMassUpload\Api\Data;

interface ProfileInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    const ENTITY_ID = 'id';
    /**#@-*/

    const SELLER_ID = 'customer_id';

    const PROFILE_NAME = 'profile_name';

    const PRODUCT_TYPE = 'product_type';

    const ATTRIBUTE_SET_ID = 'attribute_set_id';

    const IMAGE_FILE = 'image_file';

    const LINK_FILE = 'link_file';

    const SAMPLE_FILE = 'sample_files';

    const DATA_ROW = 'data_row';

    const FILE_TYPE = 'file_type';

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
    public function getCustomerId();

    /**
     * Set Seller Id.
     *
     * @param int $sellerId
     *
     * @return \XShoppingSt\MpMassUpload\Api\Data\ProfileInterface
     */
    public function setCustomerId($sellerId);

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
     * Get Product Type.
     *
     * @return string|null
     */
    public function getProductType();

    /**
     * Set Product Type.
     *
     * @param string $productType
     *
     * @return \XShoppingSt\MpMassUpload\Api\Data\ProfileInterface
     */
    public function setProductType($productType);

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
     * Get Image File.
     *
     * @return string|null
     */
    public function getImageFile();

    /**
     * Set Image File.
     *
     * @param string $imageFile
     *
     * @return \XShoppingSt\MpMassUpload\Api\Data\ProfileInterface
     */
    public function setImageFile($imageFile);

    /**
     * Get Link File.
     *
     * @return string|null
     */
    public function getLinkFile();

    /**
     * Set Link File.
     *
     * @param string $linkFile
     *
     * @return \XShoppingSt\MpMassUpload\Api\Data\ProfileInterface
     */
    public function setLinkFile($linkFile);

    /**
     * Get Sample File.
     *
     * @return string|null
     */
    public function getSampleFile();

    /**
     * Set Sample File.
     *
     * @param string $sampleFile
     *
     * @return \XShoppingSt\MpMassUpload\Api\Data\ProfileInterface
     */
    public function setSampleFile($sampleFile);

    /**
     * Get Data Row.
     *
     * @return string|null
     */
    public function getDataRow();

    /**
     * Set Data Row.
     *
     * @param string $dataRow
     *
     * @return \XShoppingSt\MpMassUpload\Api\Data\ProfileInterface
     */
    public function setDataRow($dataRow);

    /**
     * Get File Type.
     *
     * @return string|null
     */
    public function getFileType();

    /**
     * Set File Type.
     *
     * @param string $fileType
     *
     * @return \XShoppingSt\MpMassUpload\Api\Data\ProfileInterface
     */
    public function setFileType($fileType);

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
