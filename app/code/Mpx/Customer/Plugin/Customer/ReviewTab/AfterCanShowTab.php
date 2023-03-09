<?php

namespace Mpx\Customer\Plugin\Customer\ReviewTab;

use Magento\Review\Block\Adminhtml\ReviewTab;

class AfterCanShowTab
{
    /**
     *  Hidden Menu ReviewTab
     *
     * @param ReviewTab $subject
     * @param $result
     * @return false
     */
    public function afterCanShowTab(ReviewTab $subject, $result): bool
    {
        return false;
    }
}
