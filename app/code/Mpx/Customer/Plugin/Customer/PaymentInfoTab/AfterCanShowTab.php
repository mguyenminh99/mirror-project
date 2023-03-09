<?php

namespace Mpx\Customer\Plugin\Customer\PaymentInfoTab;

use Webkul\Marketplace\Block\Adminhtml\Customer\Edit\PaymentInfoTab;

class AfterCanShowTab
{
    /**
     * Hidden Menu PaymentInfoTab
     *
     * @param PaymentInfoTab $subject
     * @param $result
     * @return false
     */
    public function afterCanShowTab(PaymentInfoTab $subject, $result): bool
    {
        return false;
    }
}
