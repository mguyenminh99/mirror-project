<?php

namespace Mpx\Customer\Plugin\Customer\Agreement;

use Magento\Paypal\Block\Adminhtml\Customer\Edit\Tab\Agreement;

class AfterCanShowTab
{
    /**
     * Hidden Menu Agreement
     *
     * @param Agreement $subject
     * @param $result
     * @return false
     */
    public function afterCanShowTab(Agreement $subject, $result): bool
    {
        return false;
    }
}
