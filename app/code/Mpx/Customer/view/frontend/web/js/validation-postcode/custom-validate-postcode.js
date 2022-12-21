define(['jquery'], function ($) {
    'use strict';

    return function () {
        $.validator.addMethod(
            'validate-postcode',
            function (value) {
                return /^[0-9]{3}-?[0-9]{4}$/.test(value);
            },
            $.mage.__('Postal code is not correct.')
        );
    }
})
