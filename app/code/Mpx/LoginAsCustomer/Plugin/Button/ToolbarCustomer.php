<?php

namespace Mpx\LoginAsCustomer\Plugin\Button;

class ToolbarCustomer
{
    /**
     * Set return empty string
     *
     * @param \Magefan\LoginAsCustomer\Block\Adminhtml\Customer\Edit\Login $subject
     * @param string $result
     * @return string
     */
    public function afterGetButtonData(\Magefan\LoginAsCustomer\Block\Adminhtml\Customer\Edit\Login $subject, $result)
    {
        return "";
    }
}
