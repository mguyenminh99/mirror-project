var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/view/shipping': {
                'XShoppingSt_MpTimeDelivery/js/view/shipping': true
            },
            'XShoppingSt_OneStepCheckout/js/view/shipping': {
                'XShoppingSt_MpTimeDelivery/js/view/shipping':true
            },
            'Magento_Checkout/js/view/payment/default': {
                'XShoppingSt_MpTimeDelivery/js/view/payment/default': true
            }
        }
    },
    "map": {
        "*": {
            'Magento_Checkout/js/model/shipping-save-processor/default': 'XShoppingSt_MpTimeDelivery/js/model/shipping-save-processor/default',
        }
    }
};
