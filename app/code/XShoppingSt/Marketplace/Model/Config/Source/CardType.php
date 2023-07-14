<?php
namespace XShoppingSt\Marketplace\Model\Config\Source;

/**
 * Seller Information Display Card Type options
 */
class CardType
{
    /**
     * Options getter.
     *
     * @return array
     */
    public function toOptionArray()
    {
        $data = [
            ['value' => '1', 'label' => __('Type 1')],
            ['value' => '2', 'label' => __('Type 2')],
        ];
        return $data;
    }
}
