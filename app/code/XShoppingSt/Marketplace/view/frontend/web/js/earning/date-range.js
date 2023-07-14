 /*jshint jquery:true*/
 define([
    "jquery",
    "jquery/ui",
    'mage/calendar'
], function ($) {
    'use strict';
    $.widget('mage.earningDateRange', {
        _create: function () {
            var self = this;
            $(".wk-mp-design").dateRange({
                'dateFormat':'mm/dd/yy',
                'from': {
                    'id': 'earning-from-date'
                },
                'to': {
                    'id': 'earning-to-date'
                }
            });
        }
    });
    return $.mage.earningDateRange;
});
