<?php
namespace XShoppingSt\MpMassUpload\Controller\Adminhtml\Run;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Profile extends \Magento\Backend\App\Action
{
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var \XShoppingSt\MpMassUpload\Helper\Data
     */
    protected $_massUploadHelper;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $_jsonHelper;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param \XShoppingSt\MpMassUpload\Helper\Data $massUploadHelper
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \XShoppingSt\MpMassUpload\Helper\Data $massUploadHelper,
        \Magento\Framework\Json\Helper\Data $jsonHelper
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_massUploadHelper = $massUploadHelper;
        $this->_jsonHelper = $jsonHelper;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('XShoppingSt_MpMassUpload::run');
    }

    /**
     * Save action.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $helper = $this->_massUploadHelper;
        if ($helper->isValidProfile()) {
            $resultPage = $this->_resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->prepend(__('Run Profile'));
            return $resultPage;
        } else {
            $resultRedirect = $this->resultRedirectFactory->create();
            $this->messageManager->addError(__('Invalid Profile.'));
            return $resultRedirect->setPath('*/*/');
        }
    }
}
