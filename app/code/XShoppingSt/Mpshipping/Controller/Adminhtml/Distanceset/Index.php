<?php
namespace XShoppingSt\Mpshipping\Controller\Adminhtml\Distanceset;

use XShoppingSt\Mpshipping\Controller\Adminhtml\Distanceset as DistancesetController;
use Magento\Framework\Controller\ResultFactory;

class Index extends DistancesetController
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('XShoppingSt_Mpshipping::distanceset');
        $resultPage->getConfig()->getTitle()->prepend(__('Shipping By Distance'));
        $resultPage->addBreadcrumb(
            __('Shipping By Distance'),
            __('Shipping By Distance')
        );
        return $resultPage;
    }
}
