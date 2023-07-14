<?php
namespace XShoppingSt\Mpshipping\Model\ResourceModel;

class SellerLocation extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('marketplace_shipping_location', 'entity_id');
    }
}
