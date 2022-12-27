/**
 * Mpx Software.
 *
 * @category  Mpx
 * @package   Mpx_Marketplace
 * @author    Mpx
 */
define([
    'Magento_Ui/js/grid/columns/column',
    'jquery',
    'mage/template',
    'text!Mpx_Marketplace/templates/grid/cells/deny/seller.html',
    'Magento_Ui/js/modal/modal'
], function (Column, $, mageTemplate, denyPreviewTemplate) {
    'use strict';

    return Column.extend({
        defaults: {
            bodyTmpl: 'ui/grid/cells/html',
            fieldClass: {
                'data-grid-html-cell': true
            }
        },
        gethtml: function (row) {
            return row[this.index + '_html'];
        },
        getFormaction: function (row) {
            return row[this.index + '_formaction'];
        },
        getSellerid: function (row) {
            return row[this.index + '_sellerid'];
        },
        getLabel: function (row) {
            return row[this.index + '_html']
        },
        getTitle: function (row) {
            return row[this.index + '_title']
        },
        getSubmitlabel: function (row) {
            return row[this.index + '_submitlabel']
        },
        getCancellabel: function (row) {
            return row[this.index + '_cancellabel']
        },
        getIsSeller: function (row) {
            const DISABLED_SELLER_ID = 3;
            if(row['is_seller'] == DISABLED_SELLER_ID){
                return true;
            }
            return false;
        },
        preview: function (row) {
            var modalHtml = mageTemplate(
                denyPreviewTemplate,
                {
                    html: this.gethtml(row),
                    title: this.getTitle(row),
                    label: this.getLabel(row),
                    formaction: this.getFormaction(row),
                    sellerid: this.getSellerid(row),
                    submitlabel: this.getSubmitlabel(row),
                    cancellabel: this.getCancellabel(row),
                    linkText: $.mage.__('Go to Details Page'),
                    notifyMsg: $.mage.__('Notify Seller by Email'),
                    isseller : this.getIsSeller(row)
                }
            );
            var previewPopup = $('<div/>').html(modalHtml);
            previewPopup.modal({
                title: this.getTitle(row),
                innerScroll: true,
                modalClass: '_image-box',
                buttons: []}).trigger('openModal');
        },
        getFieldHandler: function (row) {
            return this.preview.bind(this, row);
        }
    });
});
