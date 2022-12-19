<?php

namespace Mpx\PaypalCheckout\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class PaypalCheckoutInfo
 *
 * Model PaypalCheckoutInfo
 */
class PaypalCheckoutInfo extends AbstractDb
{
    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init('paypal_checkout_info', 'id');
    }
}
