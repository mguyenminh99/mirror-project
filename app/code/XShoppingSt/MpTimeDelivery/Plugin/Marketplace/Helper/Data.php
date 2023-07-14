<?php
namespace XShoppingSt\MpTimeDelivery\Plugin\Marketplace\Helper;

class Data
{
    /**
     * function to run to change the return data of afterIsSeller.
     *
     * @param \XShoppingSt\Marketplace\Helper\Data $helperData
     * @param array                           $result
     *
     * @return bool
     */
    public function afterGetControllerMappedPermissions(
        \XShoppingSt\Marketplace\Helper\Data $helperData,
        $result
    ) {
        $result['timedelivery/account/index'] = 'timedelivery/account/save';
        return $result;
    }
}
