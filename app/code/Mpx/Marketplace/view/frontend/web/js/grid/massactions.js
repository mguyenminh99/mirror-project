define([
    'jquery',
    'Magento_Ui/js/modal/confirm',
    'mage/translate'
], function ($, confirm, $t) {
    'use strict';

    return function (target) {
        return target.extend({
            _confirm: function (action, callback) {
                var confirmData = action.confirm,
                    data = this.getSelections(),
                    total = data.total ? data.total : 0,
                    confirmMessage = confirmData.message + ' (' + total + $t('record') + ')';

                confirm({
                    title: confirmData.title,
                    content: confirmMessage,
                    actions: {
                        confirm: callback
                    }
                });
            }
        });
    };
});
