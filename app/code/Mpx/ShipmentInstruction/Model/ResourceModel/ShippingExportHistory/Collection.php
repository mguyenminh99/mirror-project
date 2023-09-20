<?php
namespace Mpx\ShipmentInstruction\Model\ResourceModel\ShippingExportHistory;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Mpx\ShipmentInstruction\Model\ShippingExportHistory as Model;
use Mpx\ShipmentInstruction\Model\ResourceModel\ShippingExportHistory as ResourceModel;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
