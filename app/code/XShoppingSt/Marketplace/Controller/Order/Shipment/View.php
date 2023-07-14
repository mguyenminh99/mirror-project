<?php
namespace XShoppingSt\Marketplace\Controller\Order\Shipment;

/**
 * XShoppingSt Marketplace Order Shipment View Controller.
 */
class View extends \XShoppingSt\Marketplace\Controller\Order
{
    /**
     * XShoppingSt Marketplace Order Shipment View Controller
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $helper = $this->helper;
        $isPartner = $helper->isSeller();
        if ($isPartner == 1) {
            if ($shipment = $this->_initShipment()) {
                /** @var \Magento\Framework\View\Result\Page $resultPage */
                $resultPage = $this->_resultPageFactory->create();
                if ($helper->getIsSeparatePanel()) {
                    $resultPage->addHandle('marketplace_layout2_order_shipment_view');
                }
                $resultPage->getConfig()->getTitle()->set(
                    __('Order #%1', $shipment->getOrder()->getRealOrderId())
                );
                return $resultPage;
            } else {
                return $this->resultRedirectFactory->create()->setPath(
                    '*/*/history',
                    [
                        '_secure'=>$this->getRequest()->isSecure()
                    ]
                );
            }
        } else {
            return $this->resultRedirectFactory->create()->setPath(
                'marketplace/account/becomeseller',
                ['_secure' => $this->getRequest()->isSecure()]
            );
        }
    }
}
