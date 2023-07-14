define(
    [
    'jquery',
    'XShoppingSt_MpTimeDelivery/js/view/seller-time-slots',
    'Magento_Customer/js/customer-data'
    ],
    function ($, sellerTimeSlots, customerData) {
        'use strict';

        return function (Payment) {
            return Payment.extend(
                {
                    getData: function () {
                        var data = this._super();
                        if (sellerTimeSlots().isEnabled && (this.getSlotInfo()).length != null) {
                            data['extension_attributes'] = {
                                seller_data: this.getSlotInfo(),
                            }
                        }
                        return data;
                    },
                    getSlotInfo: function () {
                        return JSON.stringify(customerData.get('changeevent-slots')());
                    }
                }
            );
        }
    }
);
