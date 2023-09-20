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
            var currentDate = new Date();
            var scheduledShippingDateElement = $('input[name=scheduled_shipping_date]');
            var saveButton = $('#shipment-instruction-save-btn');
            var inputs = document.querySelectorAll('.ip-change');
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
                    text: self.options.buttonContinue,
                    class: 'submit-instruction',
                    click: function (){
                        $('#edit-shipment-instruction').submit();
                    }
                }]
            };
            var scheduledShippingDateOptions = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                title: self.options.shipmentTitle,
                buttons: [{
                    text: self.options.buttonClose,
                    class: 'modal-close',
                    click: function (){
                        this.closeModal();
                    }
                }]
            };
            var editOptions = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                title: self.options.shipmentTitle,
                buttons: [{
                    text: self.options.buttonConfirm,
                    class: 'modal-close',
                    click: function (){
                        this.closeModal();
                    }
                }]
            };
            var deleteOptions = {
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
                    text: self.options.buttonConfirm,
                    class: 'delete',
                    click: function (){
                        window.location.href = urlBuilder.build("shipmentinstruction/shipmentinstruction/delete?entity_id="+self.options.recordId);
                    }
                }]
            };

            modal(options, $('#modal-content'));
            modal(editOptions, $('#modal-csv-export-id-warning'));

            var isInputChanged = true;
            if (self.options.csvExportId !== null && self.options.csvExportId !== '') {
                saveButton.attr("disabled", "disabled");
                inputs.forEach(function (input) {
                    input.addEventListener("input", function () {
                        if (isInputChanged) {
                            $('#modal-csv-export-id-warning').modal("openModal");
                            saveButton.removeAttr("disabled");
                            isInputChanged = false;
                        }
                    })
                })
            }

            $('.ip-change').on("keydown", function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                }
            })

            modal(deleteOptions, $('#modal-delete-warning'));

            $('#delete-btn').on("click", function (e) {
                e.preventDefault();
                $('#modal-delete-warning').modal("openModal");
            })

            saveButton.on("click", function(e){
                var shippingInstructionQty = $('input[name=shipping_instruction_qty]').val();
                if(parseInt(shippingInstructionQty) > parseInt(self.options.orderQty)){
                    e.preventDefault();
                    $("#modal-content").modal("openModal");
                }
            })

            modal(scheduledShippingDateOptions, $('#modal-scheduled-shipping-date-warning'));

            scheduledShippingDateElement.on("change", function () {
                var scheduledShippingDate = Date.parse(scheduledShippingDateElement.val());
                if (isNaN(scheduledShippingDate)) {
                    scheduledShippingDateElement.addClass('validate-japanese-date')
                }
                if (scheduledShippingDate < currentDate.getTime()) {
                    $("#modal-scheduled-shipping-date-warning").modal("openModal");
                }
            })
        }
    });
    return $.mage.shipmentInstructionWarning;
});
