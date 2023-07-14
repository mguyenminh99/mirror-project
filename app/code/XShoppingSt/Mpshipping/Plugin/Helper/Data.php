<?php
namespace XShoppingSt\Mpshipping\Plugin\Helper;

class Data
{
    /**
     * function to run to change the return data of afterIsSeller.
     *
     * @param \XShoppingSt\Marketplace\Helper\Data $helperData
     * @param array $result
     *
     * @return bool
     */
    public function afterGetControllerMappedPermissions(
        \XShoppingSt\Marketplace\Helper\Data $helperData,
        $result
    ) {
        $result['mpshipping/shipping/add'] = 'mpshipping/shipping/view';
        $result['mpshipping/shipping/delete'] = 'mpshipping/shipping/view';
        $result['mpshipping/shipping/deletemethod'] = 'mpshipping/shipping/view';
        $result['mpshipping/shipping/edit'] = 'mpshipping/shipping/view';
        $result['mpshipping/shipping/index'] = 'mpshipping/shipping/view';
        $result['mpshipping/shippingset/delete'] = 'mpshipping/shippingset/view';
        $result['mpshipping/shippingset/save'] = 'mpshipping/shippingset/view';
        $result['mpshipping/shippingset/massdelete'] = 'mpshipping/shippingset/view';
        $result['mpshipping/shippingset/update'] = 'mpshipping/shippingset/view';
        $result['mpshipping/distanceset/delete'] = 'mpshipping/distanceset/view';
        $result['mpshipping/distanceset/save'] = 'mpshipping/distanceset/view';
        $result['mpshipping/distanceset/massdelete'] = 'mpshipping/distanceset/view';
        $result['mpshipping/distanceset/update'] = 'mpshipping/distanceset/view';
        return $result;
    }
}
