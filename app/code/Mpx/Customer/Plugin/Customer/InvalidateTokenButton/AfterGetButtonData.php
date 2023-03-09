<?php

namespace Mpx\Customer\Plugin\Customer\InvalidateTokenButton;

use Magento\Customer\Block\Adminhtml\Edit\InvalidateTokenButton;

class AfterGetButtonData
{
    /**
     * Hidden Invalidate Token Button
     *
     * @param InvalidateTokenButton $subject
     * @param $result
     * @return array
     */
    public function afterGetButtonData(InvalidateTokenButton $subject, $result): array
    {
        return $data = [];
    }
}
