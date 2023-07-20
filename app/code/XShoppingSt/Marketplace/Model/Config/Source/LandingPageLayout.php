<?php
namespace XShoppingSt\Marketplace\Model\Config\Source;

/**
 * Landing Page Layout options.
 */
class LandingPageLayout
{
    /**
     * Options getter.
     *
     * @return array
     */
    public function toOptionArray()
    {
        $data = [
            ['value' => '1', 'label' => __('Layout 1')],
            ['value' => '2', 'label' => __('Layout 2')],
            ['value' => '3', 'label' => __('Layout 3')]
        ];
        return $data;
    }
}