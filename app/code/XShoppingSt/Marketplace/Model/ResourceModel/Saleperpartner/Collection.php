<?php
namespace XShoppingSt\Marketplace\Model\ResourceModel\Saleperpartner;

use \XShoppingSt\Marketplace\Model\ResourceModel\AbstractCollection;

/**
 * XShoppingSt Marketplace ResourceModel Saleperpartner collection
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
            \XShoppingSt\Marketplace\Model\Saleperpartner::class,
            \XShoppingSt\Marketplace\Model\ResourceModel\Saleperpartner::class
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

    /**
     * @return this
     */
    public function getTotalAmountRemain()
    {
        $this->getSelect()
        ->columns('SUM(amount_remain) AS amount_remain')
        ->group('seller_id');
        return $this;
    }
}
