<?php

namespace Mpx\Customer\Plugin\Customer\OrderButton;

use Magento\Customer\Block\Adminhtml\Edit\OrderButton;

class AfterGetButtonData
{
    /**
     * Hidden Order Button
     *
     * @param OrderButton $subject
     * @param $result
     * @return array
     */
    public function afterGetButtonData(OrderButton $subject, $result): array
    {
        return $data = [];
    }
}
