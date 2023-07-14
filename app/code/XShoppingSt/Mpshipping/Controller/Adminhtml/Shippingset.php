<?php
namespace XShoppingSt\Mpshipping\Controller\Adminhtml;

use Magento\Backend\App\Action;

abstract class Shippingset extends \Magento\Backend\App\Action
{
    /**
     * Check for is allowed
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('XShoppingSt_Mpshipping::mpshippingset');
    }
}
