define([
    "jquery",
    'mage/template',
    'mage/url',
    "mage/translate",
    "jquery/file-uploader"
], function ($, mageTemplate, urlBuilder) {
    'use strict';
    $.widget('mage.exportModal', {
        options: {},
        _create: function () {
            var self = this;
            var selectElement = $('select[name=delivery_format]');
            var deliveryFormat = self.options.deliveryFormat;
            if (self.options.isShipmentInsExportedPage) {
                for (var i = 0; i < selectElement[0].length; i++) {
                    if (selectElement[0][i].value === deliveryFormat) {
                        selectElement[0][i].selected = true;
                        break;
                    }
                }
            }
        }
    });
    return $.mage.exportModal;
});
