<?php
namespace XShoppingSt\MpMassUpload\Controller\Adminhtml\Upload;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

class Index extends \XShoppingSt\MpMassUpload\Controller\Adminhtml\Upload
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $content = $resultPage->getLayout()->createBlock(\XShoppingSt\MpMassUpload\Block\Adminhtml\Upload\Upload::class);
        $resultPage->addContent($content);
        $resultPage->setActiveMenu('XShoppingSt_MpMassUpload::upload');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Mass Upload'));
        return $resultPage;
    }
}
