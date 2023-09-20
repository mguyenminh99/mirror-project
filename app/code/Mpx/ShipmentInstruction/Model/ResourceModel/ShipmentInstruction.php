<?php

namespace Mpx\ShipmentInstruction\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ShipmentInstruction extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('sales_shipment_instruction', 'entity_id');
    }
}
