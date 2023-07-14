define([
    "jquery",
    "jquery/ui",
], function ($) {
    'use strict';
    $.widget('mpmassupload.profile', {
        options: {},
        _create: function () {
            var self = this;
            $(document).ready(function () {
                var skipCount = 0;
                var importUrl = self.options.importUrl;
                var finishUrl = self.options.finishUrl;
                var total = self.options.productCount;
                var id = self.options.profileId;
                var deleteLabel = self.options.deleteLabel;
                var completeLabel = self.options.completeLabel;
                var noProductImportLabel = self.options.noProductImportLabel;
                var productImageNotFoundErrorCode = self.options.productImageNotFoundErrorCode;
                var productImageDuplicateErrorCode = self.options.productImageDuplicateErrorCode;
                if (total > 0) {
                    var postData = self.options.postData;
                    importProduct(1, postData);
                }
                if(total == 0) { finishImporting(0, 0); }
                function importProduct(count, postData)
                {
                    $.ajax({
                        type: 'post',
                        url:importUrl,
                        async: true,
                        dataType: 'json',
                        data : postData,
                        success:function (data) {
                            if (data['error'] == 1) {
                                $(".fieldset").append(data['msg']);
                                skipCount++;
                            } else if (data['error'] == 2) {
                                $(".fieldset").append(data['msg']);
                                location.href = self.options.sellerGroupUrl;
                                return;
                            } else if (data['error'] == productImageNotFoundErrorCode || data['error'] == productImageDuplicateErrorCode) {
                                $(".fieldset").append(data['msg'])
                            } else {
                                if (data['config_error'] == 1) {
                                    $(".fieldset").append(data['msg']);
                                }
                            }
                            var width = (100/total)*count;
                            $(".wk-mu-progress-bar-current").animate({width: width+"%"},'slow', function () {
                                if (total == 1 && skipCount ==1) {
                                    $(".fieldset").append('<div class="wk-mu-success wk-mu-box">'+noProductImportLabel+'</div>');
                                    $(".wk-mu-info-bar").addClass("wk-no-padding");
                                    $(".wk-mu-importing-loader").remove();
                                    $(".wk-mu-info-bar-content").text(completeLabel);
                                } else {
                                    if (count == total) {
                                        finishImporting(count, skipCount, postData);
                                        $(".wk-mu-info-bar-content").text(deleteLabel);
                                    } else {
                                        count++;
                                        $(".wk-current").text(count);
                                        postData = data['next_row_data'];
                                        importProduct(count, postData);
                                    }
                                }
                            });
                        },error: function () {
                            $(".fieldset").append("<div class='wk-mu-error wk-mu-box'>"+$.mage.__("File format is incorrect.")+"</div>");
                            $(".wk-mu-info-bar").addClass("wk-no-padding");
                            $(".wk-mu-importing-loader").remove();
                            $('.btn-to-product-list').show();
                        }
                    });
                }
                function finishImporting(count, skipCount, postData)
                {
                    $.ajax({
                        type: 'post',
                        url:finishUrl,
                        async: true,
                        dataType: 'json',
                        data : { row:count, id:id, skip:skipCount },
                        success:function (data) {
                            $('.btn-to-product-list').show();
                            $(".fieldset").append(data['msg']);
                            $(".wk-mu-info-bar").addClass("wk-no-padding");
                            $(".wk-mu-info-bar").text(completeLabel);
                        }
                    });
                }
            });
        }
    });
    return $.mpmassupload.profile;
});
