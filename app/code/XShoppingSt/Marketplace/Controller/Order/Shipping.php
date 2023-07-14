<?php
namespace XShoppingSt\Marketplace\Controller\Order;

/**
 * XShoppingSt Marketplace Order Print PDF Header Infomation View Controller.
 */
class Shipping extends \XShoppingSt\Marketplace\Controller\Order
{
    /**
     * XShoppingSt\Marketplace\Controller\Order\Shipping.
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $helper = $this->helper;
        $isPartner = $helper->isSeller();
        if ($isPartner == 1) {
            /** @var \Magento\Framework\View\Result\Page $resultPage */
            $resultPage = $this->_resultPageFactory->create();
            if ($helper->getIsSeparatePanel()) {
                $resultPage->addHandle('marketplace_layout2_order_shipping');
            }
            $resultPage->getConfig()->getTitle()->set(
                __('Manage Print PDF Header Info')
            );

            return $resultPage;
        } else {
            return $this->resultRedirectFactory->create()->setPath(
                'marketplace/account/becomeseller',
                ['_secure' => $this->getRequest()->isSecure()]
            );
        }
    }
}
