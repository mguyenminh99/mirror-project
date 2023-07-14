<?php
namespace XShoppingSt\Marketplace\Controller\Order\Shipment;

/**
 * XShoppingSt Marketplace Order Shipment Email Controller.
 */
class Email extends \XShoppingSt\Marketplace\Controller\Order
{
    public function execute()
    {
        $helper = $this->helper;
        $isPartner = $helper->isSeller();
        if ($isPartner == 1) {
            $shipmentId = $this->getRequest()->getParam('shipment_id');
            if ($shipment = $this->_initShipment()) {
                try {
                    $this->_objectManager->create(
                        \Magento\Sales\Api\ShipmentManagementInterface::class
                    )->notify($shipment->getEntityId());
                    $this->messageManager->addSuccess(
                        __('The message has been sent.')
                    );
                } catch (\Magento\Framework\Exception\LocalizedException $e) {
                    $this->messageManager->addError($e->getMessage());
                } catch (\Exception $e) {
                    $this->helper->logDataInLogger(
                        "Controller_Order_Shipment_Email execute : ".$e->getMessage()
                    );
                    $this->messageManager->addError(
                        __('Failed to send the Shipping email.')
                    );
                }

                return $this->resultRedirectFactory->create()->setPath(
                    '*/*/view',
                    [
                        'order_id' => $shipment->getOrder()->getId(),
                        'shipment_id' => $shipmentId,
                        '_secure' => $this->getRequest()->isSecure(),
                    ]
                );
            } else {
                return $this->resultRedirectFactory->create()->setPath(
                    '*/*/history',
                    [
                        '_secure' => $this->getRequest()->isSecure(),
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
