define([
    'underscore',
    "jquery",
    'Magento_Ui/js/grid/columns/multiselect'
], function (_, $, MultiSelect) {
    'use strict';

    return MultiSelect.extend({
        defaults: {
            listens: {
                '${ $.provider }:reloaded': 'selectAll',
            },
        },
    });
});
