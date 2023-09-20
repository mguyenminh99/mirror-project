<?php
namespace Mpx\ShipmentInstruction\Model\ResourceModel\ShipmentInstruction;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Mpx\ShipmentInstruction\Model\ShipmentInstruction as Model;
use Mpx\ShipmentInstruction\Model\ResourceModel\ShipmentInstruction as ResourceModel;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }

    /**
     * @param $orderIncrementId
     * @param $sku
     * @return $this
     */
    public function addFilterOrderItem($orderIncrementId, $sku)
    {
        $this->addFieldToFilter('increment_order_id', $orderIncrementId);
        $this->addFieldToFilter('sku', $sku);
        return $this;
    }
}
