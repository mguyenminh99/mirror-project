<?php

namespace Mpx\Customer\Plugin\Customer\AssignCategoryTab;

use XShoppingSt\Marketplace\Block\Adminhtml\Customer\Edit\AssignCategoryTab;

class AfterCanShowTab
{
    /**
     * Hidden Menu AssignCategoryTab
     *
     * @param AssignCategoryTab $subject
     * @param $result
     * @return false
     */
    public function afterCanShowTab(AssignCategoryTab $subject, $result): bool
    {
        return false;
    }
}
