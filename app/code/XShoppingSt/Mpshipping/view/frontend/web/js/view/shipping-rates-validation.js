/*browser:true*/
/*global define*/
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/shipping-rates-validator',
        'Magento_Checkout/js/model/shipping-rates-validation-rules',
        'XShoppingSt_Mpshipping/js/model/shipping-rates-validator',
        'XShoppingSt_Mpshipping/js/model/shipping-rates-validation-rules'
    ],
    function (
        Component,
        defaultShippingRatesValidator,
        defaultShippingRatesValidationRules,
        xShoppingStShippingRatesValidator,
        xShoppingStShippingRatesValidationRules
    ) {
        'use strict';
        defaultShippingRatesValidator.registerValidator('x_shopping_st_shipping', xShoppingStShippingRatesValidator);
        defaultShippingRatesValidationRules.registerRules('x_shopping_st_shipping', xShoppingStShippingRatesValidationRules);

        return Component;
    }
);
