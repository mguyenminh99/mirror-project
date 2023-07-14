<?php
namespace XShoppingSt\Marketplace\Model\ResourceModel;

class ProductFlagReason extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('marketplace_productflag_reason', 'entity_id');
    }
}
