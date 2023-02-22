<?php

namespace Mpx\TimeDelivery\Block\Options;

class Option extends \Webkul\MpTimeDelivery\Block\Options\Option
{
    /**
     * @var string
     */
    protected $_template = 'Webkul_MpTimeDelivery::account/options/option.phtml';

    /**
     * Provide already save values
     *
     * @return array
     */
    public function getTimeSlotsValue()
    {
        $collection = $this->timeSlotCollection->create()
            ->addFieldToFilter(
                'seller_id',
                ['eq' => $this->getCurrentCustomerId()]
            );
        $collection->getSelect()->group('seller_id')->group('start_time')->group('end_time');
        $values = [];
        if ($collection->getSize()) {
            foreach ($collection as $slot) {
                $value = [];
                $value['id'] = $slot->getEntityId();
                $value['entity_id'] = $slot->getEntityId();
                $value['item_count'] = 1;
                $value['seller_id'] = $this->getCurrentCustomerId();
                $value['day'] = $slot->getDeliveryDay();
                $value['start'] = $this->dateTime->gmtDate('h:i A', $slot->getStartTime());
                $value['end'] = $this->dateTime->gmtDate('h:i A', $slot->getEndTime());
                $value['quota'] = $slot->getOrderCount();
                $values[] = $this->helper->getJson()->serialize($value);
            }
        }

        return $values;
    }
}
