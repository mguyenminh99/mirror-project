define([
    "jquery",
    'mage/template',
    'Magento_Ui/js/modal/alert',
    'Magento_Ui/js/lib/validation/validator',
    "mage/translate",
    "jquery/file-uploader"
], function ($, mageTemplate, alert, validator) {
    'use strict';
    $.widget('mage.validateImageSize', {
        options: {},
        _create: function () {
            let shouldShowAlert = true,
                element = this.options.element,
                maxFileSize = this.options.maxFileSize,
                isMultipleFileUploading = $('#fileupload').length;

            function getFileIdQuery(data, shouldAppendProgressBarQuery) {
                return '#' + data.fileId + (shouldAppendProgressBarQuery ? ' .progressbar-container .progressbar' : '')
            }

            function validateFileSize(file) {
                return validator('validate-max-size', file.size, maxFileSize).passed
            }

            function showErrorMessage(message) {
                alert({
                    content: message
                });
            }

            $(element).click(function () {
                shouldShowAlert = true;
            })

            $(element).fileupload({
                dropZone: isMultipleFileUploading ? '[data-tab-panel=image-management]' : $(this).closest('[role="dialog"]'),
                sequentialUploads: true,
                acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
                add: function (e, data) {
                    let progressTmpl = mageTemplate('#media_gallery_content_Uploader-template'),
                        tmpl;

                    data.files = data.files.filter(function (file) {
                        data.fileId = Math.random().toString(33).substr(2, 18)
                        if (isMultipleFileUploading) {
                            if (!validateFileSize(file)) {
                                return false;
                            }
                            tmpl = progressTmpl({
                                data: {
                                    name: file.name,
                                    size: typeof file.size == "undefined" ?
                                        $.mage.__('We could not detect a size.') :
                                        file.size,
                                    id: data.fileId
                                }
                            });
                            $(tmpl).appendTo('#media_gallery_content_Uploader');
                            return true;
                        } else {
                            if (!validateFileSize(file)) {
                                showErrorMessage($.mage.__('Image files larger than 2MB cannot be uploaded.'));
                                return false;
                            }
                            return true;
                        }
                    })

                    if (isMultipleFileUploading) {
                        if (data.files.length) {
                            $(this).fileupload('process', data).done(function () {
                                data.submit();
                            });
                        } else if (shouldShowAlert) {
                            showErrorMessage($.mage.__('Image files larger than 2MB cannot be uploaded.'));
                            shouldShowAlert = false
                        }
                    } else if (data.files.length) {
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(data.files[0]);
                        $(this).get(0).files = dataTransfer.files;
                    }
                },
                done: function (e, data) {
                    if (isMultipleFileUploading) {
                        if (data.result && !data.result.error) {
                            $('#media_gallery_content').trigger('addItem', data.result);
                        } else {
                            $(getFileIdQuery(data, false))
                                .delay(2000)
                                .hide('highlight');
                            showErrorMessage($.mage.__('We don\'t recognize or support this file extension type.'))
                        }
                        $(getFileIdQuery(data, false)).remove();
                    } else {
                        let tempErrorMessage = document.createElement("div");
                        $(getFileIdQuery(data, true)).css('width', '100%');
                        $('[data-action="show-error"]').children(".message").remove();

                        if (data.result && !data.result.hasOwnProperty('errorcode')) {
                            $(getFileIdQuery(data, true)).removeClass('upload-progress').addClass('upload-success');
                        } else {
                            tempErrorMessage.className = "message message-warning warning";
                            tempErrorMessage.innerHTML = data.result.error;
                            $('[data-action="show-error"]').append(tempErrorMessage);
                            $(getFileIdQuery(data, true)).removeClass('upload-progress').addClass('upload-failure');
                        }
                    }
                },
                progress: function (e, data) {
                    let progress = parseInt(data.loaded / data.total * 100 + '', 10);
                    $(getFileIdQuery(data, true)).css('width', progress + '%');
                },
                fail: function (e, data) {
                    let query = $(getFileIdQuery(data, !isMultipleFileUploading)).removeClass('upload-progress').addClass('upload-failure');
                    if (isMultipleFileUploading) {
                        query.delay(2000)
                            .hide('highlight')
                            .remove();
                    }
                }
            });

            $(element).fileupload('option', {
                process: [{
                    action: 'load',
                    fileTypes: /^image\/(gif|jpeg|png)$/
                }, {
                    action: 'resize',
                    maxWidth: this.options.maxWidth,
                    maxHeight: this.options.maxHeight
                }, {
                    action: 'save'
                }]
            });
        }
    });
    return $.mage.validateImageSize;
});
