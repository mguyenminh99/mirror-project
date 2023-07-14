<?php
namespace XShoppingSt\Marketplace\Model\ResourceModel;

class ProductFlags extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('marketplace_productflags', 'entity_id');
    }
}
