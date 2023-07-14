<?php
namespace XShoppingSt\Mpshipping\Model;

class Shippingtype
{
    public function toOptionArray()
    {
        $data = [
                    [
                        'value' => 'fixed',
                        'label' => 'Fixed',
                    ],
                    [
                        'value' => 'free',
                        'label' => 'Free',
                    ],
            ];

        return  $data;
    }
}
