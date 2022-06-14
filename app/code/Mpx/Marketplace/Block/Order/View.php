<?php

namespace Mpx\Marketplace\Block\Order;

/**
 * Save shipment order
 *
 * class View
 */
class View extends \Webkul\Marketplace\Block\Order\View
{
    /**
     * Get All Carriers
     *
     * @return array
     */
    public function getCarriers()
    {
        $carriers = [];
        $carrierInstances = $this->_getCarriersInstances();
        $carriers['custom'] = __('Custom Value');
        foreach ($carrierInstances as $code => $carrier) {
            if ($carrier->isTrackingAvailable()) {
                $carriers[$code] = $carrier->getConfigData('title');
            }
        }
        return $carriers;
    }

    /**
     * Get all Carriers
     *
     * @return array
     */
    protected function _getCarriersInstances()
    {
        $shippingConfig = $this->shippingConfig;
        return $shippingConfig->getAllCarriers();
    }
}
