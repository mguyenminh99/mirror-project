<?php
namespace XShoppingSt\MpTimeDelivery\Model\Config;

class Days implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Days getter.
     *
     * @return array
     */
    public function toOptionArray()
    {
        $days = [
            ['value' => 'Sunday',       'label' => __('Sunday')],
            ['value' => 'Monday',       'label' => __('Monday')],
            ['value' => 'Tuesday',      'label' => __('Tuesday')],
            ['value' => 'Wednesday',    'label' => __('Wednesday')],
            ['value' => 'Thursday',     'label' => __('Thursday')],
            ['value' => 'Friday',       'label' => __('Friday')],
            ['value' => 'Saturday',     'label' => __('Saturday')],
        ];

        return $days;
    }
}
