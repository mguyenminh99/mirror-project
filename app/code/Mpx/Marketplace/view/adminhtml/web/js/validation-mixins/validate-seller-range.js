define(
    [
        'jquery'
    ],
    function ($) {
        'use strict';

        return function (target) {
            
            $.validator.addMethod(
                "validate-seller-registration-limit",
                function(value) {
                    return ((value >= 1) && (value <= 999));
                },
                $.mage.__("Set the maximum number of stores that can be registered within the range of 1 to 999.")
            );

            return target;
        }
    }
);
