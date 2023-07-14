<?php
namespace XShoppingSt\MpMassUpload\Controller\Adminhtml\Product;

use Magento\Backend\App\Action\Context;

class Finish extends \Magento\Backend\App\Action
{
    /**
     * @var \XShoppingSt\MpMassUpload\Model\ProfileFactory
     */
    protected $_profile;

    /**
     * @var \XShoppingSt\MpMassUpload\Helper\Data
     */
    protected $_massUploadHelper;

    /**
     * @param Context $context
     * @param \XShoppingSt\MpMassUpload\Helper\Data $massUploadHelper
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     */
    public function __construct(
        Context $context,
        \XShoppingSt\MpMassUpload\Helper\Data $massUploadHelper,
        \Magento\Framework\Json\Helper\Data $jsonHelper
    ) {
        $this->_massUploadHelper = $massUploadHelper;
        $this->_jsonHelper = $jsonHelper;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $result = [];
        $profileId = $this->getRequest()->getParam('id');
        $total = (int) $this->getRequest()->getParam('row');
        $skipCount = (int) $this->getRequest()->getParam('skip');
        $total = $total - $skipCount;
        $msg = '<div class="wk-mu-success wk-mu-box">';
        $msg .= __('Total %1 Product(s) Imported.', $total);
        $msg .= '</div>';
        $msg .= '<div class="wk-mu-note wk-mu-box">';
        $msg .= __('Finished Execution.');
        $msg .= '</div>';
        $result['msg'] = $msg;
        $this->_massUploadHelper->deleteProfile($profileId);
        $result = $this->_jsonHelper->jsonEncode($result);
        $this->getResponse()->representJson($result);
    }

    /**
     * Check for is allowed.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('XShoppingSt_MpMassUpload::run');
    }
}
