<?php
namespace XShoppingSt\Marketplace\Model\Config\Source;

/**
 * Source model for element with enable and disable variants.
 */
class Enabledisable implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Value which equal Enable for Enabledisable dropdown.
     */
    const ENABLE_VALUE = 1;
    /**
     * Value which equal Disable for Enabledisable dropdown.
     */
    const DISABLE_VALUE = 0;
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::ENABLE_VALUE, 'label' => __('Enabled')],
            ['value' => self::DISABLE_VALUE, 'label' => __('Disabled')],
        ];
    }
}
