<?php
namespace XShoppingSt\Marketplace\Block\Account\Navigation;

/**
 * Marketplace Navigation link
 *
 */
class ShippingMenu extends \XShoppingSt\Marketplace\Block\Account\Navigation
{
    /**
     * isShippineAvlForSeller
     * @return boolean
     */
    public function isShippineAvlForSeller()
    {
        $activeCarriers = $this->shipconfig->getActiveCarriers();
        $status = false;
        foreach ($activeCarriers as $carrierCode => $carrierModel) {
            $allowToSeller = $this->_scopeConfig->getValue(
                'carriers/'.$carrierCode.'/allow_seller'
            );
            if ($allowToSeller) {
                $status = true;
            }
        }
        return $status;
    }
}
