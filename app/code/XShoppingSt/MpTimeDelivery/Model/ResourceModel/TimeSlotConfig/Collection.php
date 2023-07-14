<?php
namespace XShoppingSt\MpTimeDelivery\Model\ResourceModel\TimeSlotConfig;

use \XShoppingSt\MpTimeDelivery\Model\ResourceModel\AbstractCollection;
use XShoppingSt\MpTimeDelivery\Model\TimeSlotConfig;
use XShoppingSt\MpTimeDelivery\Model\ResourceModel\TimeSlotConfig as SlotConfig;

/**
 * XShoppingSt MpTimeDelivery ResourceModel Seller collection
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
            TimeSlotConfig::class,
            SlotConfig::class
        );
    }

    /**
     * Add filter by store
     *
     * @param  int|array|\Magento\Store\Model\Store $store
     * @param  bool                                 $withAdmin
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
