 /*jshint jquery:true*/
define([
    "jquery",
    'mage/translate',
    'Magento_Ui/js/modal/alert',
    "jquery/ui",
    'mage/calendar'
], function ($, $t, alert) {
    'use strict';
    $.widget('mage.productDateRange', {
        _create: function () {
            var self = this;
            $(".wk-mp-body").dateRange({
                'dateFormat':'mm/dd/yy',
                'from': {
                    'id': 'special-from-date'
                },
                'to': {
                    'id': 'special-to-date'
                }
            });
        }
    });
    return $.mage.productDateRange;
});
