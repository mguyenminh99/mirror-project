<?php

namespace Mpx\Marketplace\Block\Order;

use Magento\Sales\Model\Order;

/**
 * Save shipment order
 *
 * class View
 */
class View extends \Webkul\Marketplace\Block\Order\View
{
    public const DEFAULT_CARRIER_CODE = 'yamato_transport';

    public const DEFAULT_CARRIER_TITLE = 'ヤマト運輸';

    protected const LIST_CARRIERS_SOFT = [
        'yamato_transport','sagawa_express','japan_post','seino_transportation','seino_super_express',
        'fukuyama_transporting','meitetsu_transport','tonami_transport','daiichi_freight',
        'niigata_unyu','chuetsu_group','okayama_shipping','kurume_transport','sanyo_auto_delivery',
        'nx_transport','eco_distribution','ems','dhl','fedex','ups','nippon_express','tnt','ocs',
        'usps','sf_express','aramex','sgh_global_japan'];
    
    /**
     * Check CanShip and Shipment
     *
     * @param Order $order
     * @return bool
     */
    public function canWkOrderCancel(Order $order):bool
    {
        if (!$order->canCancel()) {
            return false;
        }
        if ($this->isOrderHavingShipment($order)) {
            return false;
        }

        return true;
    }

    /**
     * Check Shipment
     *
     * @param Order $order
     * @return bool
     */
    public function isOrderHavingShipment(Order $order): bool
    {
        return $order->getShipmentsCollection()->getSize() > 0;
    }

    /**
     * Get All Carriers
     *
     * @return array
     */
    public function getCarriers()
    {
        $carriers = [];
        $carrierInstances = $this->_getCarriersInstances();
        foreach ($carrierInstances as $code => $carrier) {
            if ($carrier->isTrackingAvailable()) {
                $carriers[$code] = $carrier->getConfigData('title');
            }
        }
        $result = $this->softCarriers($carriers);
        $result['custom'] = __('Custom Value');
        return $result;
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

    /**
     * Soft Carriers
     *
     * @param array $carriers
     * @return array
     */
    public function softCarriers($carriers): array
    {
        $softCarriers = array_replace(array_flip(self::LIST_CARRIERS_SOFT), $carriers);
        return $softCarriers;
    }

    /**
     * Get Default Carrier Code
     *
     * @return string
     */
    public function getDefaultCarrierCode()
    {
        return self::DEFAULT_CARRIER_CODE;
    }

    /**
     * Get Default Carrier Title
     *
     * @return string
     */
    public function getDefaultCarrierTitle()
    {
        return self::DEFAULT_CARRIER_TITLE;
    }
}
