define([
    "jquery",
    'mage/template',
    'Magento_Ui/js/modal/alert',
    'Magento_Ui/js/lib/validation/validator',
    'mage/url',
    'Magento_Ui/js/modal/modal',
    "mage/translate",
    "jquery/file-uploader"
], function ($, mageTemplate, alert, validator, urlBuilder, modal) {
    'use strict';
    $.widget('mage.shipmentInstructionWarning', {
        options: {},
        _create: function () {
            var self = this;
            var options = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                title: self.options.shipmentTitle,
                buttons: [{
                    text: self.options.buttonCancel,
                    class: 'modal-close',
                    click: function (){
                        this.closeModal();
                    }
                },{
                    text: self.options.buttonCreate,
                    class: 'submit-instruction',
                    click: function (){
                        var url = urlBuilder.build($('#createShipmentInstructionBtn').attr('data-url'));
                        window.location.href = url;
                    }
                }]
            };

            modal(options, $('#modal-content'));
            $("#createShipmentInstructionBtn").on('click',function(){
                $("#modal-content").modal("openModal");
            });

        }
    });
    return $.mage.shipmentInstructionWarning;
});
