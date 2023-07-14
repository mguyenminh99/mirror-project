<?php
namespace XShoppingSt\MpAssignProduct\Controller\Adminhtml;

use Magento\Backend\App\Action;

abstract class Product extends \Magento\Backend\App\Action
{
    /**
     * Check for is allowed.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('XShoppingSt_MpAssignProduct::product');
    }
}
