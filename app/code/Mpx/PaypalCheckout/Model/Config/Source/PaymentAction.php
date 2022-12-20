<?php

namespace Mpx\PaypalCheckout\Model\Config\Source;

use Magento\Payment\Model\Method\AbstractMethod;

/**
 * Class PaymentAction
 * show option config PayPal
 */
class PaymentAction implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => AbstractMethod::ACTION_AUTHORIZE,
                'label' => __('Authorize ')
            ],
            [
                'value' =>  AbstractMethod::ACTION_AUTHORIZE_CAPTURE,
                'label' => __('Capture ')
            ]
        ];
    }
}
