<?php
namespace XShoppingSt\MpMassUpload\Controller\Adminhtml\Export;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

class Index extends \XShoppingSt\MpMassUpload\Controller\Adminhtml\Export
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $content = $resultPage->getLayout()->createBlock(\XShoppingSt\MpMassUpload\Block\Adminhtml\Export\Export::class);
        $resultPage->addContent($content);
        $resultPage->setActiveMenu('XShoppingSt_MpMassUpload::export');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Mass Products Export'));
        return $resultPage;
    }
}
