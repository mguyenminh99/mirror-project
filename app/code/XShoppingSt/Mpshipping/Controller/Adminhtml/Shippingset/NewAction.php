<?php
namespace XShoppingSt\Mpshipping\Controller\Adminhtml\Shippingset;

class NewAction extends \XShoppingSt\Mpshipping\Controller\Adminhtml\Shippingset
{
    /**
     * @return void
     */
    public function execute()
    {
        $this->_forward('edit');
    }
    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization
                    ->isAllowed('XShoppingSt_Mpshipping::mpshippingset');
    }
}
