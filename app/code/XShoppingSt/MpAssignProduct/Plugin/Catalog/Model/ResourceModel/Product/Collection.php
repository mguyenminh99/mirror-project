<?php
namespace XShoppingSt\MpAssignProduct\Plugin\Catalog\Model\ResourceModel\Product;

class Collection
{
    /**
     * @var \XShoppingSt\MpAssignProduct\Helper\Data
     */
    protected $helper;
    /**
     * @var \Magento\Framework\App\State
     */
    protected $_appState;

    /**
     * @var \XShoppingSt\MpAssignProduct\Model\AssociatesFactory
     */
    protected $associatesFactory;

    /**
     * @param \XShoppingSt\MpAssignProduct\Helper\Data $helper
     * @param \XShoppingSt\MpAssignProduct\Model\AssociatesFactory $associatesFactory
     * @param \Magento\Framework\App\State     $appState
     */
    public function __construct(
        \XShoppingSt\MpAssignProduct\Helper\Data $helper,
        \XShoppingSt\MpAssignProduct\Model\AssociatesFactory $associatesFactory,
        \Magento\Framework\App\State $appState
    ) {
        $this->helper = $helper;
        $this->associatesFactory = $associatesFactory;
        $this->_appState = $appState;
    }

    public function aroundAddAttributeToSelect(
        \Magento\Catalog\Model\ResourceModel\Product\Collection $subject,
        \Closure $proceed,
        $attribute,
        $joinType = false
    ) {
        $appState = $this->_appState;
        $areCode = $appState->getAreaCode();
        $result = $proceed($attribute, $joinType = false);
        $code = \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE;
        if ($appState->getAreaCode() == $code) {
            $assignProductsIds = $this->helper->getCollection()->getAllIds();
            $associateProductIds = $this->associatesFactory->create()->getCollection()->getAllIds();
            $assignProductsIds = array_merge($assignProductsIds, $associateProductIds);
            if (!empty($assignProductsIds)) {
                $result->addFieldToFilter('entity_id', ['nin' => $assignProductsIds]);
            }
        }
        return $result;
    }
}
