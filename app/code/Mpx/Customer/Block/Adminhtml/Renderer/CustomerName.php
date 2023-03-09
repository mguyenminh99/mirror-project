<?php

namespace Mpx\Customer\Block\Adminhtml\Renderer;
use Magento\Framework\DataObject;

class CustomerName extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * Merge Column In Order Tab
     *
     * @param DataObject $row
     * @return string
     */
    public function render(DataObject $row): string
    {
        return $row->getData('lastname').' '.$row->getData('firstname');
    }
}
