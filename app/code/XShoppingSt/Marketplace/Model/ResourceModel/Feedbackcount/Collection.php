<?php
namespace XShoppingSt\Marketplace\Model\ResourceModel\Feedbackcount;

use \XShoppingSt\Marketplace\Model\ResourceModel\AbstractCollection;

/**
 * XShoppingSt Marketplace ResourceModel Feedbackcount collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \XShoppingSt\Marketplace\Model\Feedbackcount::class,
            \XShoppingSt\Marketplace\Model\ResourceModel\Feedbackcount::class
        );
        $this->_map['fields']['entity_id'] = 'main_table.entity_id';
    }

    /**
     * Add filter by store
     *
     * @param int|array|\Magento\Store\Model\Store $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if (!$this->getFlag('store_filter_added')) {
            $this->performAddStoreFilter($store, $withAdmin);
        }
        return $this;
    }
}