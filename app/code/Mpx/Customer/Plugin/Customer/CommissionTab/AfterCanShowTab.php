<?php

namespace Mpx\Customer\Plugin\Customer\CommissionTab;

use Webkul\Marketplace\Block\Adminhtml\Customer\Edit\CommissionTab;

class AfterCanShowTab
{
    /**
     * Hidden Menu CommissionTab
     *
     * @param CommissionTab $subject
     * @param $result
     * @return false
     */
    public function afterCanShowTab(CommissionTab $subject, $result): bool
    {
        return false;
    }
}
