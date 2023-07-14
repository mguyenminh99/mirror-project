<?php
namespace XShoppingSt\MpAssignProduct\Model\Config\Source;

class Options
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $data = [
                    ['value' => '0', 'label' => __('With Minimum Price')],
                    ['value' => '1', 'label' => __('With Maximum Price')],
                    ['value' => '2', 'label' => __('With Minimum Quantity')],
                    ['value' => '3', 'label' => __('With Maximum Quantity')],
                ];

        return $data;
    }
}
