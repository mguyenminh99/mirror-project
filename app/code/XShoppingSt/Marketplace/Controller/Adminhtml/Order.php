<?php
namespace XShoppingSt\Marketplace\Controller\Adminhtml;

use Magento\Backend\App\Action;

/**
 * XShoppingSt Marketplace admin order controller.
 */
abstract class Order extends \Magento\Backend\App\Action
{
    /**
     * Check for is allowed.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('XShoppingSt_Marketplace::order');
    }
}
