<?php
namespace XShoppingSt\Mpshipping\Model\ResourceModel;

/**
 * Mpshipping mysql resource
 */
class Mpshippingset extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('marketplace_tablerate_superset', 'entity_id');
    }
}
