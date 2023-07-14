<?php

namespace Mpx\MpMassUpload\Helper;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollection;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory as AttributeCollection;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableProTypeModel;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as CustomerCollection;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory as AttributeSetCollection;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Filesystem\Io\File as fileUpload;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\Xml\Parser;
use XShoppingSt\Marketplace\Model\ResourceModel\Seller\CollectionFactory as SellerCollection;
use XShoppingSt\MpMassUpload\Api\AttributeMappingRepositoryInterface;
use XShoppingSt\MpMassUpload\Api\AttributeProfileRepositoryInterface;
use XShoppingSt\MpMassUpload\Api\ProfileRepositoryInterface;
use XShoppingSt\MpMassUpload\Model\ResourceModel\AttributeMapping\CollectionFactory as AttributeMapping;
use XShoppingSt\MpMassUpload\Model\ResourceModel\AttributeProfile\CollectionFactory as AttributeProfile;
use Mpx\MpMassUpload\Helper\Constant;

class Data extends \XShoppingSt\MpMassUpload\Helper\Data
{
    /**
     * @var \Mpx\Marketplace\Helper\CommonFunc
     */
    protected $mpxHelperData;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Eav\Model\Entity $entity
     * @param \Magento\Eav\Model\Config $config
     * @param \Magento\Framework\Data\Form\FormKey $formKey
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \XShoppingSt\MpMassUpload\Model\ProfileFactory $profile
     * @param \XShoppingSt\Marketplace\Controller\Product\SaveProduct $saveProduct
     * @param SellerCollection $sellerCollectionFactory
     * @param CategoryCollection $categoryCollectionFactory
     * @param AttributeCollection $attributeCollectionFactory
     * @param CustomerCollection $customerCollectionFactory
     * @param AttributeSetCollection $attributeSetCollectionFactory
     * @param File $fileDriver
     * @param \Magento\Framework\File\Csv $csvReader
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
     * @param \XShoppingSt\MpMassUpload\Model\Zip $zip
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param \Magento\Customer\Model\Group $customerGroup
     * @param Parser $parser
     * @param File $file
     * @param AttributeProfile $attributeProfile
     * @param AttributeProfileRepositoryInterface $attributeProfileRepository
     * @param AttributeMapping $attributeMapping
     * @param AttributeMappingRepositoryInterface $attributeMappingRepository
     * @param ProfileRepositoryInterface $profileRepository
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Magento\Downloadable\Helper\File $fileHelper
     * @param \XShoppingSt\Marketplace\Helper\Data $marketplaceHelper
     * @param \XShoppingSt\Marketplace\Model\ProductFactory $mpProduct
     * @param ConfigurableProTypeModel $configurableProTypeModel
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezoneInterface
     * @param fileUpload $fileUpload
     * @param DirectoryList $directoryList
     * @param SerializerInterface $serializerInterface
     * @param \Magento\Framework\Pricing\Helper\Data $pricingHelper
     * @param \Mpx\Marketplace\Helper\CommonFunc $mpxHelperData
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Eav\Model\Entity $entity,
        \Magento\Eav\Model\Config $config,
        \Magento\Framework\Data\Form\FormKey $formKey,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \XShoppingSt\MpMassUpload\Model\ProfileFactory $profile,
        \XShoppingSt\Marketplace\Controller\Product\SaveProduct $saveProduct,
        SellerCollection $sellerCollectionFactory,
        CategoryCollection $categoryCollectionFactory,
        AttributeCollection $attributeCollectionFactory,
        CustomerCollection $customerCollectionFactory,
        AttributeSetCollection $attributeSetCollectionFactory,
        File $fileDriver,
        \Magento\Framework\File\Csv $csvReader,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \XShoppingSt\MpMassUpload\Model\Zip $zip,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Customer\Model\Group $customerGroup,
        Parser $parser,
        File $file,
        AttributeProfile $attributeProfile,
        AttributeProfileRepositoryInterface $attributeProfileRepository,
        AttributeMapping $attributeMapping,
        AttributeMappingRepositoryInterface $attributeMappingRepository,
        ProfileRepositoryInterface $profileRepository,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Downloadable\Helper\File $fileHelper,
        \XShoppingSt\Marketplace\Helper\Data $marketplaceHelper,
        \XShoppingSt\Marketplace\Model\ProductFactory $mpProduct,
        ConfigurableProTypeModel $configurableProTypeModel,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezoneInterface,
        fileUpload $fileUpload, DirectoryList $directoryList,
        SerializerInterface $serializerInterface,
        \Magento\Framework\Pricing\Helper\Data $pricingHelper,
        \Mpx\Marketplace\Helper\CommonFunc $mpxHelperData
    ) {
        $this->mpxHelperData = $mpxHelperData;
        parent::__construct(
            $context,
            $storeManager,
            $customerSession,
            $filesystem,
            $entity,
            $config,
            $formKey,
            $productFactory,
            $profile,
            $saveProduct,
            $sellerCollectionFactory,
            $categoryCollectionFactory,
            $attributeCollectionFactory,
            $customerCollectionFactory,
            $attributeSetCollectionFactory,
            $fileDriver,
            $csvReader,
            $fileUploaderFactory,
            $zip,
            $objectManager,
            $resource,
            $customerGroup,
            $parser,
            $file,
            $attributeProfile,
            $attributeProfileRepository,
            $attributeMapping,
            $attributeMappingRepository,
            $profileRepository,
            $jsonHelper,
            $fileHelper,
            $marketplaceHelper,
            $mpProduct,
            $configurableProTypeModel,
            $timezoneInterface,
            $fileUpload,
            $directoryList,
            $serializerInterface,
            $pricingHelper
        );
    }

    /**
     * Validate Product Data
     *
     * @param array $data
     * @param string $profileType
     * @param int $row
     *
     * @return array
     */
    public function validateFields($data, $profileType, $row)
    {
        $data = $this->prepareProductDataIfNotSet($data, $profileType);
        if (empty($data['product'])) {
            $result['error'] = 1;
            $result['data'] = $data;
            $result['msg'] = __('Skipped row %1. product data can not be empty.', $row);
            return $result;
        } else {
            $name = $data['product']['name'];
            $sku = $data['product']['sku'];
            $skuWithPrefix = $this->mpxHelperData->formatSku($sku);
            $description = $data['product']['description'];
            $weight = $data['product']['weight'];
            if (strlen($name) <= 0) {
                $result['error'] = 1;
                $result['data'] = $data;
                $result['msg'] = __('Skipped row %1. product name can not be empty.', $row);
                return $result;
            }
            if (strlen($description) <= 0) {
                $result['error'] = 1;
                $result['data'] = $data;
                $result['msg'] = __('Skipped row %1. product description can not be empty.', $row);
                return $result;
            }
            if (($profileType != 'virtual') && ($profileType != 'downloadable') && strlen($weight) <= 0) {
                $result['error'] = 1;
                $result['data'] = $data;
                $result['msg'] = __('Skipped row %1. product weight can not be empty.', $row);
                return $result;
            }
            if (strlen($sku) <= 0) {
                $result['error'] = 1;
                $result['data'] = $data;
                $result['msg'] = __('Skipped row %1. product sku can not be empty.', $row);
                return $result;
            }
            if (!preg_match('/^[0-9]+$/', $data['product']['stock'])) {
                $result['error'] = 1;
                $result['data'] = $data;
                $result['msg'] = __('Use integer for row %1 stock value', $row);
                return $result;
            }
            $productId = $this->_product->create()->getIdBySku($skuWithPrefix);
            if ($productId) {
                $data = $this->existingProductDataMapping($data, $productId);
            }
        }
        return ['error' => 0, 'data' => $data];
    }

    /**
     * Validate and Set Default Values for Fields
     *
     * @param array $persistentData
     * @param array $fields
     *
     * @return array
     */
    public function setFieldsValue(&$persistentData, $fields)
    {
        foreach ($fields['product'] as $key => $field) {
            if (!isset($persistentData['product'][$key])) {
                $persistentData['product'][$key] = $field;
            }
        }
        return $persistentData;
    }

    /**
     * Calculate Product Row Data
     *
     * @param int $sellerId
     * @param int $profileId
     * @param int $row
     * @param string $profileType
     *
     * @return array
     */
    public function calculateProductRowData($sellerId, $profileId, $row, $profileType)
    {
        $uploadedFileRowData = $this->getUploadedFileRowData($profileId);
        $mainRow = $row;
        $isConfigurableAllowed = $this->isProductTypeAllowed('configurable');
        if ($profileType == 'configurable' && $isConfigurableAllowed) {
            $rowIndexArr = $this->getConfigurableFormatCsv($uploadedFileRowData, 1);
            if (!empty($rowIndexArr[$row])) {
                $row = $rowIndexArr[$row];
            }
            $childRowIndexArr = $this->getConfigurableFormatCsv($uploadedFileRowData, 0);
            if (!empty($childRowIndexArr[$mainRow])) {
                $childRowArr = $childRowIndexArr[$mainRow];
            } else {
                $childRowArr = [];
            }
        }
        if (!array_key_exists($row, $uploadedFileRowData)) {
            $wholeData['error'] = 1;
            $wholeData['msg'] = __('Product data for row %1 does not exist', $mainRow);
        }
        // Prepare product row data
        $i=0;
        $j=0;
        $data = [];
        if (!empty($uploadedFileRowData[$row])) {
            $data = $uploadedFileRowData[$row];
        }
        $customData = [];
        $customData['product'] = [];
        $csvAttributeList = [];
        foreach ($uploadedFileRowData[0] as $value) {
            $csvAttributeList[$value] = $value;
            if (isset($data[$i])) {
                $customData['product'][$value] = $data[$i];
            } else {
                $customData['product'][$value] = '';
            }
            $i++;
        }
        $data = $customData;
        $validate = $this->validateFields(
            $data,
            $profileType,
            $mainRow
        );
        if ($validate['error']) {
            $wholeData['error'] = $validate['error'];
            $wholeData['msg'] = $validate['msg'];
        }
        $data = $validate['data'];
        /*Calculate product weight*/
        $hasWeight = 1;
        $isDownloadableAllowed = $this->isProductTypeAllowed('downloadable');
        $isVirtualAllowed = $this->isProductTypeAllowed('virtual');
        if (($profileType == 'virtual' && $isVirtualAllowed) ||
            ($profileType == 'downloadable' && $isDownloadableAllowed)) {
            $weight = 0;
            $hasWeight = 0;
        } else {
            $weight = $data['product']['weight'];
        }
        /*Get Category ids by category name (set by comma seperated)*/
        $categoryIds = $this->getCategoryIds($data['product']['category']);
        /*Get $taxClassId by tax*/
        $taxClassId = $this->getAttributeOptionIdbyOptionText("tax_class_id","消費税10%");
        if ((int)$data['product']['stock'] >= 1) {
            $isInStock = (int) true;
        } else {
            $data['product']['stock'] = 0;
            $isInStock = (int) false;
        }
        $attributeSetId = $this->getAttributeSetId($profileId);
        $wholeData['form_key'] = $this->getFormKey();
        $wholeData['type'] = $profileType;
        $wholeData['set'] = $attributeSetId;
        if (!empty($data['id'])) {
            $wholeData['id'] = $data['id'];
            $wholeData['product_id'] = $data['product_id'];
            $wholeData['product']['website_ids'] = $data['product']['website_ids'];
            $wholeData['product']['url_key'] = $data['product']['url_key'];
            if (!empty($data['product']['weight']) && $data['product']['weight'] != 0) {
                $weight = $data['product']['weight'];
                $hasWeight = 1;
            }
        }
        $wholeData['product']['category_ids'] = $categoryIds;
        $wholeData['product']['name'] = $data['product']['name'];
        $wholeData['product']['short_description'] = $data['product']['short_description'];
        $wholeData['product']['description'] = $data['product']['description'];
        $wholeData['product']['sku'] = $data['product']['sku'];
        $wholeData['product']['price'] = $data['product']['price'];
        $wholeData['product']['visibility'] = 4;
        $wholeData['product']['tax_class_id'] = $taxClassId;
        $wholeData['product']['product_has_weight'] = $hasWeight;
        $wholeData['product']['weight'] = 1;
        $wholeData['product']['stock_data']['manage_stock'] = 1;
        $wholeData['product']['stock_data']['use_config_manage_stock'] = 1;
        $wholeData['product']['quantity_and_stock_status']['qty'] = $data['product']['stock'];
        $wholeData['product']['quantity_and_stock_status']['is_in_stock'] = $isInStock;
        $wholeData['product']['meta_title'] = $data['product']['meta_title'];
        $wholeData['product']['meta_keyword'] = $data['product']['meta_keyword'];
        $wholeData['product']['meta_description'] = $data['product']['meta_description'];
        /*START :: Set Special Price Info*/
        $wholeData = $this->processSpecialPriceData($wholeData, $data);
        /*Set Image Info*/
        $wholeData = $this->processImageDataMpx($wholeData, $data, $profileId, $mainRow);
        /*Set Downloadable Data*/
        $isDownloadableAllowed = $this->isProductTypeAllowed('downloadable');
        if ($profileType == 'downloadable' && $isDownloadableAllowed) {
            $wholeData = $this->processDownloadableData($wholeData, $data, $profileId);
        }
        /*Set Configurable Data*/
        $isConfigurableAllowed = $this->isProductTypeAllowed('configurable');
        if ($profileType == 'configurable' && $isConfigurableAllowed) {
            $wholeData = $this->processConfigurableData(
                $wholeData,
                $data,
                $mainRow,
                $childRowArr,
                $uploadedFileRowData,
                $profileId
            );
        }
        /*Set Custom Attributes Values*/
        if ($this->canSaveCustomAttribute()) {
            $wholeData = $this->validateCsvForRequiredCustomAttributes($csvAttributeList, $wholeData);
            if (!isset($wholeData['error'])) {
                $wholeData = $this->processCustomAttributeData($wholeData, $data, $mainRow);
            }
        }
        /*Set Custom Options Values*/
        if ($this->canSaveCustomOption()) {
            $wholeData = $this->processCustomOptionData($wholeData, $data);
        }
        /*Set product Status */
        if (!$this->getProductApprovalRequiredStatus()) {
            if (isset($data['product']['status'])) {
                $wholeData['status'] = $data['product']['status'];
            }
        }
        /*Set mapped category for Attribute Mapping */
        if ($this->isAttributeMappingEnabled()) {
            if (isset($data['product']['attribute_mapping_category'])) {
                $category = $data['product']['attribute_mapping_category'];
                if (strpos($category, ',') !== false) {
                    $wholeData['error'] = 1;
                    $wholeData['msg'] = __(
                        'Skipped row %1. Multiple categories are not allowed for attribute mapping',
                        $mainRow
                    );
                } else {
                    $categoryId = $this->getCategoryIds($category);
                    $wholeData['product']['attribute_mapping_category'] = $categoryId[0];
                }
            } else {
                $wholeData['product']['attribute_mapping_category'] =  '';
            }
        }
        /*Set Cross-sell Up-sell and related product data */
        if (isset($data['product']['related_skus']) && !empty($data['product']['related_skus'])) {
            $wholeData = $this->setRelatedCrossUpSellProductData($wholeData, $data, 'related');
        }
        if (isset($data['product']['crosssell_skus']) && !empty($data['product']['crosssell_skus'])) {
            $wholeData = $this->setRelatedCrossUpSellProductData($wholeData, $data, 'crosssell');
        }
        if (isset($data['product']['upsell_skus']) && !empty($data['product']['upsell_skus'])) {
            $wholeData = $this->setRelatedCrossUpSellProductData($wholeData, $data, 'upsell');
        }
        $wholeData = $this->utf8Converter($wholeData);
        return $wholeData;
    }

    /**
     * Get Sample Csv File Urls.
     *
     * @return array
     */
    public function getSampleCsv()
    {
        $result = [];
        $mediaDirectory = $this->_storeManager
            ->getStore()
            ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        $url = $mediaDirectory.'marketplace/massupload/samples/';
        $result[] = $url.'product_import_jp.csv';
        $result[] = $url.'downloadable.csv';
        $result[] = $url.'config.csv';
        $result[] = $url.'virtual.csv';
        return $result;
    }

    /**
     * Get Csv Product Type
     *
     * @param array $uploadedFileRowData
     *
     * @return string
     */
    public function getProductType($uploadedFileRowData)
    {
        return 'simple';
    }

    /**
     * Process Image Data
     *
     * @param array $wholeData
     * @param array $data
     * @param int $profileId
     *
     * @return array $wholeData
     */
    public function processImageDataMpx($wholeData, $data, $profileId, $mainRow)
    {
        if (!empty($data['product']['images'])) {
            $data['product']['images'] = strtok($data['product']['images'], '?');
            $sku = $data['product']['sku'];
            $images = array_unique($this->getArrayFromString($data['product']['images']));
            $i = 0;
            $j = 0;
            foreach ($images as $key => $value) {
                // Show error when importing the wrong image type allowed
                if (isset(pathinfo($value)['extension']) && !in_array(pathinfo($value)['extension'], Constant::ALLOWED_PRODUCT_IMAGE_FILE_TYPE)) {
                    $wholeData['error'] = Constant::PRODUCT_IMAGE_NOT_FOUND_ERROR_CODE;
                    $wholeData['image'] = "";
                    $wholeData['msg'] = __('%1 unable to acquire product images.', $mainRow);
                    continue;
                }
                // Show error when importing duplicated images
                if (isset($data['product']['duplicated_image_index']) && in_array($key, $data['product']['duplicated_image_index'])){
                    $wholeData['error'] = Constant::PRODUCT_IMAGE_DUPLICATE_ERROR_CODE;
                    $wholeData['image'] = "";
                    $wholeData['msg'] = __('Image import processing was skipped because an image with the same name already exists.');
                    continue;
                }
                $imageName = '/'.$profileId.'/'.$sku.'/'.$value;
                $imagePath = $this->getMediaPath().'tmp/catalog/product'.$imageName;
                /** upload image with url */
                if (\strpos($value, 'http') !== false) {
                    $filePath        =  $this->getMediaPath().'tmp/catalog/product/'.$profileId.'/';
                    $destinationPath = $filePath.$sku;
                    $this->_file->createDirectory($destinationPath);
                    $fileInfo = $this->fileUpload->getPathInfo($value);
                    $basename = $fileInfo['basename'];
                    $imageName = '/'.$profileId.'/'.$sku.'/'.$basename;
                    $imagePath = $this->getMediaPath().'tmp/catalog/product'.$imageName;
                    if ($this->_fileDriver->isExists($imagePath)) {
                        $j++;
                        $imageName = '/'.$profileId.'/'.$sku.'/'.$basename.' ('.$j.')';
                        $imagePath = $this->getMediaPath().'tmp/catalog/product'.$imageName;
                    };
                    $tmpDirectory    = $this->getMediaDirTmpDir();
                    $this->fileUpload->checkAndCreateFolder($tmpDirectory);
                    $baseFileName = $tmpDirectory.'/catalog/product'.$imageName;
                    $result = $this->fileUpload->read(str_replace(" ", "%20", $value), $baseFileName);
                }
                if (!empty(trim($value)) && $this->_fileDriver->isExists($imagePath)) {
                    $i++;
                    $wholeData['product']['media_gallery']['images'][$key]['position'] = $i;
                    $wholeData['product']['media_gallery']['images'][$key]['media_type'] = '';
                    $wholeData['product']['media_gallery']['images'][$key]['video_provider'] = '';
                    $wholeData['product']['media_gallery']['images'][$key]['file'] = $imageName.'.tmp';
                    $wholeData['product']['media_gallery']['images'][$key]['value_id'] = '';
                    $wholeData['product']['media_gallery']['images'][$key]['label'] = '';
                    $wholeData['product']['media_gallery']['images'][$key]['disabled'] = '';
                    $wholeData['product']['media_gallery']['images'][$key]['removed'] = '';
                    $wholeData['product']['media_gallery']['images'][$key]['video_url'] = '';
                    $wholeData['product']['media_gallery']['images'][$key]['video_title'] = '';
                    $wholeData['product']['media_gallery']['images'][$key]['video_description'] = '';
                    $wholeData['product']['media_gallery']['images'][$key]['video_metadata'] = '';
                    $wholeData['product']['media_gallery']['images'][$key]['role'] = '';
                    if ($i == 1) {
                        $wholeData['product']['image'] = $imageName.'.tmp';
                        $wholeData['product']['small_image'] = $imageName.'.tmp';
                        $wholeData['product']['thumbnail'] = $imageName.'.tmp';
                    }
                } else {
                    $wholeData['error'] = Constant::PRODUCT_IMAGE_NOT_FOUND_ERROR_CODE;
                    $wholeData['msg'] = __('%1 unable to acquire product images.', $mainRow);
                }
            }
        } else {
            $wholeData['error'] = Constant::PRODUCT_IMAGE_NOT_FOUND_ERROR_CODE;
            $wholeData['msg'] = __('%1 unable to acquire product images.', $mainRow);
        }
        return $wholeData;
    }

    /**
     * Save Product
     *
     * @param int $sellerId
     * @param int $row
     * @param array $wholeData
     *
     * @return array
     */
    public function saveProduct($sellerId, $row, $wholeData)
    {
        $result = ['error' => 0, 'config_error' => 0, 'msg' => ''];
        try {
            /* Check if authorized seller */
            if (!empty($wholeData['id']) && empty($wholeData['error'])) {
                $productId = $wholeData['id'];
                $rightseller = $this->isRightSeller($productId, $sellerId);
                if (!$rightseller) {
                    $wholeData['msg'] = __(
                        'Skipped row %1. Product is already assigned to other seller.',
                        $row
                    );
                    $wholeData['error'] = 1;
                }
            }
            if ($row == 1) {
                $this->_customerSession->setSuccesProductCount(0);
            }
            $uploadedPro = $wholeData['total_row_count'];
            $successProCount = (int) $this->_customerSession->getSuccesProductCount();
            /*Set Product Add Status According to seller Group*/
            if ($this->isSellerGroupEnable() && !$this->checkProductAllowedStatus($uploadedPro, $successProCount)) {
                $result['error'] = 1;
                if ($this->getAllowedProductQty()) {
                    $result['message'] =
                        __('You are not allowed to add more than %1 Product(s)', $this->getAllowedProductQty());
                } else {
                    $result['message'] = __('YOUR GROUP PACK IS EXPIRED...');
                }
            } elseif ($this->isSellerMembershipEnable() &&
                ($this->getConfigFeeAppliedFor() == 0 && !$this->isMembershipFeePaid())) {
                $erroFlag = 1;
                $data = $this->isMembershipFeePaid($erroFlag);
                if ($data['status']) {
                    $result['error'] = 1;
                    $result['message'] = __('Seller Membership : %1 ', $data['msg']);
                }
            } else {
                if (isset($wholeData['error']) && ($wholeData['error']) == 1) {
                    $result['error'] = $wholeData['error'];
                    $result['msg'] = $wholeData['msg'];
                } else {
                    $result = $this->_saveProduct->saveProductData($sellerId, $wholeData);
                    if (isset($wholeData['error']) && (($wholeData['error']) == Constant::PRODUCT_IMAGE_NOT_FOUND_ERROR_CODE
                            || ($wholeData['error']) == Constant::PRODUCT_IMAGE_DUPLICATE_ERROR_CODE)) {
                        $result['error'] = $wholeData['error'];
                        $result['msg'] = $wholeData['msg'];
                    }
                    $isInStock = 1;
                    if (!(int)$wholeData['product']['quantity_and_stock_status']['qty']) {
                        $isInStock = 0;
                    }
                    $result['is_in_stock'] = $isInStock;
                    if (!empty($result['product_id'])) {
                        $productId = (int) $result['product_id'];
                        $successProCount = (int) $this->_customerSession->getSuccesProductCount();
                        $successProCount++;
                        $this->_customerSession->setSuccesProductCount($successProCount);
                    }
                }
            }
        } catch (\Exception $e) {
            $result['msg'] = __('Skipped row %1. %2', $row, $e->getMessage());
            $result['error'] = 1;
        }
        $result['total_row_count'] = $wholeData['total_row_count'];
        $result['row'] = $row;
        if ($wholeData['total_row_count'] != $row) {
            $nextRow = $row+1;
            $result['next_row_data'] = $this->calculateProductRowData(
                $sellerId,
                $wholeData['profile_id'],
                $nextRow,
                $wholeData['type']
            );
            $result['next_row_data']['profile_id'] = $wholeData['profile_id'];
            $result['next_row_data']['row'] = $nextRow;
            $result['next_row_data']['total_row_count'] = $wholeData['total_row_count'];
            $result['next_row_data']['seller_id'] = $sellerId;
        }
        if ($result['error'] == 1) {
            if (!empty($result['message'])) {
                $result['msg'] = $result['message'];
            }
            return $result;
        } else {
            if (empty($result['product_id'])) {
                $result['product_id'] = 0;
            }
            $productId = (int) $result['product_id'];
        }
        if ($productId == 0) {
            $result['error'] = 1;
            $result['msg'] = __('Skipped row %1. error in importing product.', $row);
        }

        return $result;
    }

    /**
     * Maps product data if already existing
     *
     * @param array $data
     * @param int $productId
     * @return array
     */
    public function existingProductDataMapping($data, $productId)
    {
        $product = $this->_product->create()->load($productId);
        // Existing Product Data Mapping Start
        $productArray = $product->getData();
        foreach ($data['product'] as $key => $value) {
            if (empty($value)) {
                if ($key == 'stock') {
                    $data['product']['stock'] = $productArray['quantity_and_stock_status']['qty'];
                }
                if ($key == 'type') {
                    $data['product']['type'] = $productArray['type_id'];
                }
                if (array_key_exists($key, $productArray)) {
                    $data['product'][$key] = $productArray[$key];
                }
            }
        }
        //Existing Product Image Data Mapping Start
        if (!empty($data['product']['images']) && !empty($productArray['image'])) {
            $importImages = array_unique($this->getArrayFromString($data['product']['images']));
            foreach ($importImages as $key => $importImagesValue) {
                foreach ($productArray['media_gallery']['images'] as $productImages) {
                    if (pathinfo($importImagesValue)['basename'] == pathinfo($productImages['file'])['basename']) {
                        $data['product']['duplicated_image_index'][] = $key;
                    }
                }
            }
        }
        $data['id'] = $productId;
        $data['product_id'] = $productId;
        $data['product']['website_ids'][] = $product->getStore()->getWebsiteId();
        $data['product']['url_key'] = $product->getUrlKey();

        return $data;
    }
}
