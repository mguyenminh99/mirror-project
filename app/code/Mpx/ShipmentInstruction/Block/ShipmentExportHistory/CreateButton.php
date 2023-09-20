<?php
namespace Mpx\ShipmentInstruction\Block\ShipmentExportHistory;

use Magento\Framework\View\Element\Template;

class CreateButton extends Template
{
    /**
     * Get the button URL.
     *
     * @return string
     */
    public function getButtonUrl()
    {
        return $this->getUrl('shipmentinstruction/shipmentinstructionunexported/index');
    }
}
