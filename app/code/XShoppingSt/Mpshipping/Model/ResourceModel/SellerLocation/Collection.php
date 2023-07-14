<?php
namespace XShoppingSt\Mpshipping\Model\ResourceModel\SellerLocation;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';
    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init(
            \XShoppingSt\Mpshipping\Model\SellerLocation::class,
            \XShoppingSt\Mpshipping\Model\ResourceModel\SellerLocation::class
        );
    }
}
