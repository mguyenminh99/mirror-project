<?php
namespace XShoppingSt\Mpshipping\Controller\Adminhtml\Shippingset;

use XShoppingSt\Mpshipping\Controller\Adminhtml\Shippingset as ShippingsetController;
use Magento\Framework\Controller\ResultFactory;

class Index extends ShippingsetController
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('XShoppingSt_Mpshipping::mpshippingset');
        $resultPage->getConfig()->getTitle()->prepend(__('Marketplace Super Shipping Set Manager'));
        $resultPage->addBreadcrumb(
            __('Marketplace Super Shipping Set Manager'),
            __('Marketplace Super Shipping Set Manager')
        );
        return $resultPage;
    }
}
