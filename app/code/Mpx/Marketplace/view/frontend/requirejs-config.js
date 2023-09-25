/**
 * Mpx Software.
 *
 * @category  Mpx
 * @package   Mpx_Marketplace
 * @author    Mpx
 */

var config = {
    config: {
        mixins: {
            'mage/validation': {
                'Mpx_Marketplace/js/validation-mixins/japan-date-validation': true,
                'Mpx_Marketplace/js/validation-mixins/sku-validation': true
            },
            'XShoppingSt_Marketplace/js/order/shipment': {
                'Mpx_Marketplace/js/order/shipment': true
            },
            'Magento_Ui/js/grid/massactions':{
                'Mpx_Marketplace/js/grid/massactions': true
            }
        }
    },
    map: {
        '*': {
            sellerOrderShipment: 'Mpx_Marketplace/js/order/shipment',
            validateEmailExist: 'Mpx_Marketplace/js/validate/validate-email-exist',
            'Magento_Ui/js/grid/controls/columns':'Mpx_Marketplace/js/grid/controls/columns'
        }
    },
}
