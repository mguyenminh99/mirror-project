<?php
namespace XShoppingSt\Marketplace\Model\Config\Source;

/**
 * Used in abusing flag Reason Status.
 */
class Reasonstatus
{
    /**
     * Options getter.
     *
     * @return array
     */
    public function toOptionArray()
    {
        $data = [
            ['value' => '1', 'label' => __('Yes, Required')],
            ['value' => '0', 'label' => __('No')]
        ];
        return $data;
    }
}
