<?php
namespace XShoppingSt\Mpshipping\Controller\Adminhtml\Shipping;

use XShoppingSt\Mpshipping\Controller\Adminhtml\Shipping as ShippingController;
use Magento\Framework\Controller\ResultFactory;

class Index extends ShippingController
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('XShoppingSt_Mpshipping::mpshipping');
        $resultPage->getConfig()->getTitle()->prepend(__('Marketplace Table Rate Shipping Manager'));
        $resultPage->addBreadcrumb(
            __('Marketplace Table Rate Shipping Manager'),
            __('Marketplace Table Rate Shipping Manager')
        );
        $resultPage->addContent(
            $resultPage
            ->getLayout()
            ->createBlock(
                \XShoppingSt\Mpshipping\Block\Adminhtml\Shipping\Edit::class
            )
        );
        $resultPage->addLeft(
            $resultPage
            ->getLayout()
            ->createBlock(
                \XShoppingSt\Mpshipping\Block\Adminhtml\Shipping\Edit\Tabs::class
            )
        );
        return $resultPage;
    }
}
