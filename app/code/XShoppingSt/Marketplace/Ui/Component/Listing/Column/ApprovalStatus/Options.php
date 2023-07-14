<?php
namespace XShoppingSt\Marketplace\Ui\Component\Listing\Column\ApprovalStatus;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Options
 */
class Options implements OptionSourceInterface
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
                'value' => 0
            ],
            [
                'label' => __('Approved'),
                'value' => 1
            ]
        ];
        return $options;
    }
}
