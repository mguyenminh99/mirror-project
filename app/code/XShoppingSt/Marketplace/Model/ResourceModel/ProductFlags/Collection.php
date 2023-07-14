<?php
namespace XShoppingSt\Marketplace\Model\ResourceModel\ProductFlags;

use XShoppingSt\Marketplace\Model\ResourceModel\AbstractCollection;

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
            \XShoppingSt\Marketplace\Model\ProductFlags::class,
            \XShoppingSt\Marketplace\Model\ResourceModel\ProductFlags::class
        );
    }
}
