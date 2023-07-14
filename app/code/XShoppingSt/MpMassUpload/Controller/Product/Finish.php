<?php
namespace XShoppingSt\MpMassUpload\Controller\Product;

use Magento\Framework\App\Action\Context;

class Finish extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \XShoppingSt\MpMassUpload\Helper\Data $massUploadHelper
     */
    protected $_massUploadHelper;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $_jsonHelper;

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
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        if (!empty($this->getRequest()->getPost())) {
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
    }
}
