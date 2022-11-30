define([
    "jquery", "Magento_Ui/js/modal/modal"
], function removeSeller($, modal) {
    'use strict';
    var options = {
        type: 'popup',
        responsive: false,
        buttons: [
            {
                text: $.mage.__('Cancel'),
                click: function () {
                    $('#marketplace_is_seller_remove').prop( "checked", false );
                    this.closeModal();
                }
            },
            {
                text: $.mage.__('OK'),
                click: function () {
                    this.closeModal();
                }
            }
        ]
    };
    var popup = modal(options, $('#modal_remove_seller'));

    $('#marketplace_is_seller_remove').click(function() {
        if($('#marketplace_is_seller_remove').is(':checked'))
        {
            $('#modal_remove_seller').modal('openModal');
        }
    });
    $('.action-close').click(function() {
        $('#marketplace_is_seller_remove').prop( "checked", false );
    });
});
