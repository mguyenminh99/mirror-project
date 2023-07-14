<?php
namespace XShoppingSt\MpAssignProduct\Model\Product\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{
    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            [
                'label' => __('Disapproved'),
                'value' => 0,
            ],
            [
                'label' => __('Approved'),
                'value' => 1,
            ]
        ];
        return $options;
    }
}
