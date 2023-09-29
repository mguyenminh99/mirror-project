<?php

namespace Mpx\ShipmentInstruction\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ShippingExportHistory extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('sales_shipment_shipping_label_export_history', 'entity_id');
    }
}
