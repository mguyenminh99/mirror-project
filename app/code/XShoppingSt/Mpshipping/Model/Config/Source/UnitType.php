<?php
namespace XShoppingSt\Mpshipping\Model\Config\Source;

class UnitType implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 0,
                'label' => __('KM')
            ],[
                'value' => 1,
                'label' => __('Miles')
            ]
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [0 => __('KM'), 1 => __('Miles')];
    }
}
