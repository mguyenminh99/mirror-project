<?php
namespace XShoppingSt\MpTimeDelivery\Plugin\Marketplace\Observer;

use Magento\Sales\Api\OrderRepositoryInterface;

class SalesOrderPlaceAfterObserver
{
    /**
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository
    ) {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Add slot info for seller email notification
     *
     * @param \XShoppingSt\Marketplace\Observer\SalesOrderPlaceAfterObserver $subject
     * @param object $item
     * @param array $result
     * @return array
     */
    public function beforeGetProductOptionData(
        \XShoppingSt\Marketplace\Observer\SalesOrderPlaceAfterObserver $subject,
        $item,
        $result
    ) {
        $order = $this->orderRepository->get($item->getData()['order_id']);
        $deliveryDate = '';
        $deliveryTime = '';
        foreach ($order->getItems() as $item) {
            $deliveryDate = $item->getDeliveryDate();
            $deliveryTime = $item->getDeliveryTime();
        }
        $slotInfo = [
            [
                'label' => __('Delivery Date/Day: '),
                'value' => $deliveryDate
            ],
            [
                'label' => __('Delivery Time: '),
                'value' => $deliveryTime
            ]
        ];
        $result = array_merge($result, $slotInfo);
        return [$item, $result];
    }
}
