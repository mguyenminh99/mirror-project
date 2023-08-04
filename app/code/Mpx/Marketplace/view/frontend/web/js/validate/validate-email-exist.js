define([
    "jquery",
    'mage/translate',
    'Magento_Ui/js/modal/alert',
    'mage/template',
    'mage/loader'
], function ($, $t, alert, mageTemplate, loader) {
    'use strict';
    $.widget('mage.validateEmailExist', {
        _create: function () {
            var self = this;
            $('#save_config').on('click' , function (e){
                $('input[name=email]').css('border-color' ,'#c2c2c2');
                $('.error-message-email-exist').remove();
                e.preventDefault();
                var email = $('input[name=email]').val();
                jQuery('body').trigger('processStart');
                $.ajax({
                    url: self.options.url,
                    method: "GET",
                    dataType: 'json',
                    data: {email : email},
                    showLoader : true,
                    success: function (data) {
                        if(data.is_email_exists === false){
                            $('.create-sub-seller').submit();
                        } else{
                            $('.create-sub-seller').validation('isValid');
                            $('input[name=email]').css('border-color' ,'#ed8380');
                            $('input[name=email]').parent().append('<div class="error-message-email-exist">' + $.mage.__('Email address is already registered.') + '</div>')
                        }
                        jQuery('body').trigger('processStop');
                    }
                });
            })
        },

    });
    return $.mage.validateEmailExist;
});
