define(['jquery'], function ($) {
    'use strict';

    return function () {
        $.validator.addMethod(
            'validate-telephone',
            function (value) {
                return /^[0-9\-]*$/.test(value);
            },
            $.mage.__('Phone number is not correct. The characters that can be used are numbers and hyphens.')
        );
    }
})
