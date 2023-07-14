<?php
namespace XShoppingSt\Marketplace\Controller\Adminhtml;

use Magento\Backend\App\Action;

/**
 * XShoppingSt Marketplace admin commission controller
 */
abstract class Commission extends \Magento\Backend\App\Action
{
    /**
     * Check for is allowed
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('XShoppingSt_Marketplace::commission');
    }
}
