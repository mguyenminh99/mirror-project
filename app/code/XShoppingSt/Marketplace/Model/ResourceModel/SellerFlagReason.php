<?php
namespace XShoppingSt\Marketplace\Model\ResourceModel;

class SellerFlagReason extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('marketplace_sellerflag_reason', 'entity_id');
    }
}
