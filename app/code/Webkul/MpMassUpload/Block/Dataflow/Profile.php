<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_MpMassUpload
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\MpMassUpload\Block\Dataflow;

/*
 * Webkul MpMassUpload Dataflow Profile Block
 */
use Magento\Customer\Model\Customer;
use Webkul\MpMassUpload\Model\ResourceModel\AttributeProfile\CollectionFactory;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSetModel;
use Webkul\MpMassUpload\Api\AttributeProfileRepositoryInterface;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory as AttributeGroup;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory as ProductAttribute;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute as EavAttribute;
use Webkul\MpMassUpload\Api\AttributeMappingRepositoryInterface;

class Profile extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Webkul\Marketplace\Helper\Data
     */
    public $marketplaceHelper;

    /**
     * @var CollectionFactory
     */
    public $_profileCollectionFactory;

    /** @var \Webkul\MpMassUpload\Model\AttributeProfile */
    public $attributeProfileLists;

    /**
     * @var \Magento\Eav\Model\Entity
     */
    protected $_entity;

    /**
     * @var AttributeSet
     */
    protected $_setCollection;

    /**
     * @var AttributeSetModel
     */
    protected $_attributeSetModel;

    /**
     * @var AttributeProfileRepositoryInterface
     */
    protected $_attributeProfileRepository;

    /**
     * @var AttributeGroup
     */
    protected $_attributeGroup;

    /**
     * @var ProductAttribute
     */
    protected $_productAttributeCollection;

    /**
     * @var EavAttribute
     */
    protected $_eavAttribute;

    /**
     * @var AttributeMappingRepositoryInterface
     */
    protected $_attributeMappingRepository;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Webkul\Marketplace\Helper\Data                  $marketplaceHelper
     * @param CollectionFactory                                $profileCollectionFactory
     * @param AttributeSet                                     $setCollection
     * @param \Magento\Eav\Model\Entity                        $entity
     * @param AttributeSetModel                                $attributeSetModel
     * @param AttributeProfileRepositoryInterface              $attributeProfileRepository
     * @param AttributeGroup                                   $attributeGroup
     * @param ProductAttribute                                 $productAttributeCollection
     * @param EavAttribute                                     $eavAttribute
     * @param AttributeMappingRepositoryInterface              $attributeMappingRepository
     * @param array                                            $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Webkul\Marketplace\Helper\Data $marketplaceHelper,
        CollectionFactory $profileCollectionFactory,
        AttributeSet $setCollection,
        \Magento\Eav\Model\Entity $entity,
        AttributeSetModel $attributeSetModel,
        AttributeProfileRepositoryInterface $attributeProfileRepository,
        AttributeGroup $attributeGroup,
        ProductAttribute $productAttributeCollection,
        EavAttribute $eavAttribute,
        AttributeMappingRepositoryInterface $attributeMappingRepository,
        array $data = []
    ) {
        $this->_profileCollectionFactory = $profileCollectionFactory;
        $this->marketplaceHelper = $marketplaceHelper;
        $this->_entity = $entity;
        $this->_setCollection = $setCollection;
        $this->_attributeSetModel = $attributeSetModel;
        $this->_attributeProfileRepository = $attributeProfileRepository;
        $this->_attributeGroup = $attributeGroup;
        $this->_productAttributeCollection = $productAttributeCollection;
        $this->_eavAttribute = $eavAttribute;
        $this->_attributeMappingRepository = $attributeMappingRepository;
        parent::__construct($context, $data);
    }

    /**
     * Get Attribute Set Collection.
     *
     * @return collection object
     */
    public function getAttributeSetCollection()
    {
        $allowedAttributeSets = $this->marketplaceHelper->getAllowedAttributesetIds();
        $allowedAttributeSetIds = explode(',', $allowedAttributeSets);
        $entityTypeId = $this->_entity
              ->setType('catalog_product')
              ->getTypeId();
        $attributeSetCollection = $this->_setCollection
              ->create()
              ->addFieldToFilter(
                  'attribute_set_id',
                  ['in' => $allowedAttributeSetIds]
              )
              ->setEntityTypeFilter($entityTypeId);
        return $attributeSetCollection;
    }

    /**
     * Get Attribute Set Name By Id.
     * @param int $attrSetId
     *
     * @return collection object
     */
    public function getAttributeSetNameById($attrSetId)
    {
        $attributeSet = $this->_attributeSetModel->load($attrSetId);
        return $attributeSet->getAttributeSetName();
    }

    public function getCustomerId()
    {
        return $this->marketplaceHelper->getCustomerId();
    }

    /**
     * @return bool|\Webkul\MpMassUpload\Model\ResourceModel\Saleslist\Collection
     */
    public function getDataFlowProfiles()
    {
        if (!($customerId = $this->getCustomerId())) {
            return false;
        }
        if (!$this->attributeProfileLists) {
            $collection = $this->_profileCollectionFactory->create()
            ->addFieldToSelect(
                '*'
            )
            ->addFieldToFilter(
                'seller_id',
                $this->getCustomerId()
            )
            ->setOrder(
                'created_date',
                'desc'
            );
            $this->attributeProfileLists = $collection;
        }

        return $this->attributeProfileLists;
    }

    /**
     * @return $this
     */
    public function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getDataFlowProfiles()) {
            $pager = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class,
                'massupload.dataflow.profile.pager'
            )
            ->setCollection(
                $this->getDataFlowProfiles()
            );
            $this->setChild('pager', $pager);
            $this->getDataFlowProfiles()->load();
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * Get Dataflow Profile By Id.
     * @param int $id
     *
     * @return collection object
     */
    public function getDataflowProfileById($id)
    {
        $attributeProfile = $this->_attributeProfileRepository->get($id);
        return $attributeProfile;
    }

    /**
     * Get getMappedProfileFields By Profile Id.
     * @param int $id
     *
     * @return collection object
     */
    public function getMappedProfileFields($profileId)
    {
        $mappedProfileFields = $this->_attributeMappingRepository
            ->getByProfileId($profileId);
        return $mappedProfileFields;
    }

   /**
    * get all attributes
    *
    * @param int $attributeSetId
    * @return void
    */
    public function getAllAttributes($attributeSetId)
    {
        $attributeids = [];
        $groups = $this->_attributeGroup->create()
            ->setAttributeSetFilter($attributeSetId)
            ->setSortOrder()
            ->load();
        foreach ($groups as $node) {
            $nodeChildren = $this->loadData($node);
            if ($nodeChildren->getSize() > 0) {
                foreach ($nodeChildren->getItems() as $child) {
                    array_push($attributeids, $child->getAttributeId());
                }
            }
        }
        return $attributeids;
    }

    /**
     * catalog resource data
     *
     * @param int $id
     * @return void
     */
    public function getCatalogResourceEavAttribute($id)
    {
        return $this->_eavAttribute->load($id);
    }

    /**
     * load data
     *
     * @param object $node
     * @return void
     */
    public function loadData($node)
    {
        $nodeChildren = [];
        $nodeChildren = $this->_productAttributeCollection->create()
                ->setAttributeGroupFilter($node->getId())
                ->addVisibleFilter()
                ->load();
        return $nodeChildren;
    }

    /**
     * Return the Customer seller status.
     *
     * @return bool|0|1
     */
    public function isSeller()
    {
        return $this->marketplaceHelper->isSeller();
    }
}
