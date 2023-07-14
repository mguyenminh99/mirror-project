<?php
namespace XShoppingSt\Marketplace\Block\Account\Navigation;

/**
 * Marketplace Navigation link
 *
 */
class PaymentMenu extends \XShoppingSt\Marketplace\Block\Account\Navigation
{
    /**
     * isPaymentAvlForSeller
     * @return boolean
     */
    public function isPaymentAvlForSeller()
    {
        $activeMethods = $this->paymentConfig->getActiveMethods();
        $status = false;
        foreach ($activeMethods as $methodCode => $methodModel) {
            if (preg_match('/mp[^a-b][^0-9][^A-Z]/', $methodCode)) {
                $status = true;
            }
        }
        return $status;
    }
}
