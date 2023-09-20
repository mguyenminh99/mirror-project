<?php

namespace Mpx\ShipmentInstruction\Service;

use Magento\Sales\Api\OrderRepositoryInterface;
use Mpx\ShipmentInstruction\Helper\Data as ShipmentHelper;
use Mpx\ShipmentInstruction\Model\ShipmentInstructionFactory;
use XShoppingSt\Marketplace\Helper\Data;

class ShipmentInstructionService
{
    /**
     * @var Data
     */
    public $helperData;

    /**
     * @var ShipmentInstructionFactory
     */
    public $shipmentInstructionFactory;

    /**
     * @var OrderRepositoryInterface
     */
    public $orderRepository;

    /**
     * @var ShipmentHelper
     */
    public $shipmentHelper;

    /**
     * @param Data $helperData
     * @param ShipmentInstructionFactory $shipmentInstructionFactory
     * @param OrderRepositoryInterface $orderRepository
     * @param ShipmentHelper $shipmentHelper
     */
    public function __construct(
        Data $helperData,
        ShipmentInstructionFactory $shipmentInstructionFactory,
        OrderRepositoryInterface $orderRepository,
        ShipmentHelper $shipmentHelper
    ) {
        $this->helperData = $helperData;
        $this->shipmentInstructionFactory = $shipmentInstructionFactory;
        $this->orderRepository = $orderRepository;
        $this->shipmentHelper = $shipmentHelper;
    }

    /**
     * @param $item
     * @param $order
     * @return array
     */
    public function setShipmentInstructionData($item, $order){
        $shippingAddress = $order->getShippingAddress();
        $data = [];
        $data["seller_id"] = $this->helperData->getCustomerId();
        $data["increment_order_id"] = $order->getIncrementId();
        $data["sku"] = $item->getSku();
        $data["product_name"] = $item->getName();
        $data["instructed_qty"] = $item->getQtyOrdered() - $item->getQtyShipped();
        $data["destination_customer_name"] = $shippingAddress->getLastname() . " " . $shippingAddress->getFirstname();
        $data["destination_postcode"] = $shippingAddress->getPostcode();
        $data["destination_region"] = $shippingAddress->getRegion();
        $data["destination_city"] = $shippingAddress->getCity();
        $data["destination_street"] = implode("\n", $shippingAddress->getStreet());
        $data["destination_telephone"] = $shippingAddress->getTelephone();
        if($item->getDeliveryDate() && strtotime($item->getDeliveryDate())){
            $data["desired_delivery_date"] = $item->getDeliveryDate();
        }
        if ($item->getDeliveryTime() && strtotime($item->getDeliveryTime())){
            $data["desired_delivery_time"] = $item->getDeliveryTime();
        }
        $data["shipping_label_type"] = $shippingAddress->getAddressType();
        return $data;
    }

    /**
     * @param $order
     * @return array
     */
    public function getAllOrderItems($order){
        return $this->shipmentHelper->getItemsToCreateShipmentInstruction($order);
    }

    /**
     * @param $orderId
     * @return bool|void
     */
    public function createShipmentInstructionRecord($orderId){
        $order = $this->orderRepository->get($orderId);
        $items = $this->getAllOrderItems($order);
        if ($items) {
            try {
                foreach ($items as $item){
                    $shippingInstruction = $this->shipmentInstructionFactory->create();
                    $data = $this->setShipmentInstructionData($item, $order);
                    $shippingInstruction->setData($data);
                    $shippingInstruction->save();
                }
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
        return false;
    }

    /**
     * @param $shipmentInstruction
     * @return bool|void
     */
    public function isCsvExported($shipmentInstruction){
        return ( is_numeric($shipmentInstruction->getCsvExportId()) );
    }


    /**
     * @param $shipmentInstruction
     * @param $data
     * @return bool|\Magento\Framework\Controller\Result\Redirect
     */
    public function saveShipmentInstructionData($shipmentInstruction, $data){
        if ($this->isCsvExported($shipmentInstruction)) {
            $shipmentInstruction->setCsvExportId(null);
            $shipmentInstruction->setScheduledShippingDate(null);
        } else {
            $shipmentInstruction->setScheduledShippingDate($data->getParam('scheduled_shipping_date'));
        }
        $shipmentInstruction->setProductName($data->getParam('product_name'));
        $shipmentInstruction->setInstructedQty($data->getParam('shipping_instruction_qty'));
        $shipmentInstruction->setDestinationCustomerName($data->getParam('customer_name'));
        $shipmentInstruction->setDestinationPostcode($data->getParam('destination_postcode'));
        $shipmentInstruction->setDestinationRegion($data->getParam('region_id'));
        $shipmentInstruction->setDestinationCity($data->getParam('customer_city'));
        $shipmentInstruction->setDestinationStreet(implode("\n", $data->getParam('customer_street')));
        $shipmentInstruction->setDesiredDeliveryDate($data->getParam('desired_delivery_date'));
        $shipmentInstruction->setDesiredDeliveryTime($data->getParam('desired_delivery_time'));
        $shipmentInstruction->save();
        return true;
    }

    /**
     * @param $shipmentInstruction
     * @param $data
     * @return bool
     */
    public function saveDuplicatedShipmentInstructionRecord($shipmentInstruction, $data){
        try{
            $shipmentInstruction->isObjectNew(true);
            $shipmentInstruction->setCsvExportId(null);
            $shipmentInstruction->setEntityId(null);
            $shipmentInstruction->setCreatedAt(null);
            $shipmentInstruction->setScheduledShippingDate(null);
            $shipmentInstruction->setProductName($data->getParam('product_name'));
            $shipmentInstruction->setInstructedQty($data->getParam('shipping_instruction_qty'));
            $shipmentInstruction->setDestinationCustomerName($data->getParam('customer_name'));
            $shipmentInstruction->setDestinationPostcode($data->getParam('destination_postcode'));
            $shipmentInstruction->setDestinationRegion($data->getParam('region_id'));
            $shipmentInstruction->setDestinationCity($data->getParam('customer_city'));
            $shipmentInstruction->setDestinationStreet(implode("\n", $data->getParam('customer_street')));
            $shipmentInstruction->setDesiredDeliveryDate($data->getParam('desired_delivery_date'));
            $shipmentInstruction->setDesiredDeliveryTime($data->getParam('desired_delivery_time'));
            $shipmentInstruction->save();
            return true;
        }catch(\Exception $e){
            return false;
        }
    }

    /**
     * @param $entityId
     * @return bool
     */
    public function deleteRecord($entityId){
        $shipmentInstruction = $this->shipmentInstructionFactory->create()->load($entityId);
        try{
            $shipmentInstruction->delete();
            return true;
        }catch (\Exception $e){
            return false;
        }
    }
}
