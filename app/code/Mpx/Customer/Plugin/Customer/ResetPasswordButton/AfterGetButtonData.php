<?php

namespace Mpx\Customer\Plugin\Customer\ResetPasswordButton;


use Magento\Customer\Block\Adminhtml\Edit\ResetPasswordButton;

class AfterGetButtonData
{
    /**
     * Hidden Reset Password Button
     *
     * @param ResetPasswordButton $subject
     * @param $result
     * @return array
     */
    public function afterGetButtonData(ResetPasswordButton $subject, $result): array
    {
        return $data = [];
    }
}
