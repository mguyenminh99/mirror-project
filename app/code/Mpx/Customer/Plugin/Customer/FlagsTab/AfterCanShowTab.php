<?php

namespace Mpx\Customer\Plugin\Customer\FlagsTab;

use Webkul\Marketplace\Block\Adminhtml\FlagsTab;

class AfterCanShowTab
{
    /**
     * Hidden Menu FlagsTab
     *
     * @param FlagsTab $subject
     * @param $result
     * @return false
     */
    public function afterCanShowTab(FlagsTab $subject, $result): bool
    {
        return false;
    }
}
