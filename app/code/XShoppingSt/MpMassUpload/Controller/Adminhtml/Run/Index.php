<?php
namespace XShoppingSt\MpMassUpload\Controller\Adminhtml\Run;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

class Index extends \XShoppingSt\MpMassUpload\Controller\Adminhtml\Run
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $content = $resultPage->getLayout()->createBlock(\XShoppingSt\MpMassUpload\Block\Adminhtml\Run\Run::class);
        $resultPage->addContent($content);
        $resultPage->setActiveMenu('XShoppingSt_MpMassUpload::run');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Mass Upload'));
        return $resultPage;
    }
}
