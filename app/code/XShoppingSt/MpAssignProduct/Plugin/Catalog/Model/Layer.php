<?php
namespace XShoppingSt\MpAssignProduct\Plugin\Catalog\Model;

class Layer
{
    /**
     * @var \XShoppingSt\MpAssignProduct\Helper\Data
     */
    protected $helper;

    /**
     * @var \XShoppingSt\MpAssignProduct\Model\AssociatesFactory
     */
    protected $associatesFactory;

    /**
     * @param \XShoppingSt\MpAssignProduct\Helper\Data $helper
     * @param \XShoppingSt\MpAssignProduct\Model\AssociatesFactory $associatesFactory
     */
    public function __construct(
        \XShoppingSt\MpAssignProduct\Helper\Data $helper,
        \XShoppingSt\MpAssignProduct\Model\AssociatesFactory $associatesFactory
    ) {
        $this->helper = $helper;
        $this->associatesFactory = $associatesFactory;
    }

    /**
     * Plugin for getProductCollection
     *
     * @param \Magento\Catalog\Model\Layer $subject
     * @return $result
     */
    public function afterGetProductCollection(
        \Magento\Catalog\Model\Layer $subject,
        $result
    ) {
        $assignProductsIds = $this->helper->getCollection()->getAllIds();
        $associateProductIds = $this->associatesFactory->create()->getCollection()->getAllIds();
        $assignProductsIds = array_merge($assignProductsIds, $associateProductIds);
        if (!empty($assignProductsIds)) {
            $result->addAttributeToFilter('entity_id', ['nin' => $assignProductsIds]);
        }
        return $result;
    }
}
