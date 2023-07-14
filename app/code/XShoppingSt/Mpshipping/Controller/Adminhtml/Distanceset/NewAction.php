<?php
namespace XShoppingSt\Mpshipping\Controller\Adminhtml\Distanceset;

class NewAction extends \XShoppingSt\Mpshipping\Controller\Adminhtml\Distanceset
{
    /**
     * @return void
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}
