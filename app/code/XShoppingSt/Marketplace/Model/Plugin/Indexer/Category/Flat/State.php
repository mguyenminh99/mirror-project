<?php
namespace XShoppingSt\Marketplace\Model\Plugin\Indexer\Category\Flat;

class State
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Framework\Registry $registry
    ) {
        $this->_registry = $registry;
    }

    /**
     * function to run to change the retun data of afterIsFlatEnabled.
     *
     * @param \Magento\Catalog\Model\Indexer\Category\Flat\State $state
     * @param array $result
     *
     * @return bool
     */
    public function afterIsFlatEnabled(
        \Magento\Catalog\Model\Indexer\Category\Flat\State $state,
        $result
    ) {
        if ($this->_registry->registry('mp_flat_catalog_flag')) {
            $result = 0;
        }
        return $result;
    }
}
