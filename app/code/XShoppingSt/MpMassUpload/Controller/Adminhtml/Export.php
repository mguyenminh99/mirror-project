<?php
namespace XShoppingSt\MpMassUpload\Controller\Adminhtml;

use Magento\Backend\App\Action;

abstract class Export extends \Magento\Backend\App\Action
{
    /**
     * Check for is allowed.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('XShoppingSt_MpMassUpload::export');
    }
}
