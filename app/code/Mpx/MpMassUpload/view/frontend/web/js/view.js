define([
    "jquery",
    'Magento_Ui/js/modal/alert',
    'Magento_Ui/js/modal/modal',
    "jquery/ui"
], function ($, alert, modal) {
    'use strict';
    $.widget('mpmassupload.view', {
        options: {},
        _create: function () {
            var self = this;
            $(document).ready(function () {
                var dataflowProfileMsg = self.options.dataflowProfileMsg;
                var noMatchingProfileMsg = self.options.noMatchingProfileMsg;
                var selectBox = $("<select id=\"attribute_profile_id\" name=\"attribute_profile_id\" title=\"Dataflow Profile\" />");
                $("<option />", {value: '', text: dataflowProfileMsg}).appendTo(selectBox);
                $(selectBox).insertAfter("#dataFlow");
                var attributeSetInfo = self.options.attributeSetInfo;
                var attributeDetails = self.options.attributeDetails;
                var row = self.options.row;
                var linkSampleRow = self.options.linkSampleRow;
                var sampleRow = self.options.sampleRow;
                var defaultUrl = self.options.defaultUrl;
                var infoUrl = self.options.infoUrl;
                var attributeProfilesCollection = self.options.attributeProfilesCollection;
                var defaultTitle = $(".wk-mu-options-content").html();
                var defaultContent = $(".wk-mu-custom-attribute").html();
                var multiSelectDefaultTitle = $(".wk-multi-select-options-content").html();
                var options = [];
                $(document).on('click', '#downloadable', function (event) {
                    var val = $(this).prop("checked");
                    if (val == true) {
                        $("#wk_massupload_form .fieldset").append(row);
                    } else {
                        $("#link_file").remove();
                        $("#is_link_sample").remove();
                        $("#link_sample_file").remove();
                        $("#is_sample").remove();
                        $("#sample_file").remove();
                    }
                });
                $(document).on('click', '#is_link_samples', function (event) {
                    var val = $(this).prop("checked");
                    if (val == true) {
                        $("#wk_massupload_form .fieldset").append(linkSampleRow);
                    } else {
                        $("#link_sample_file").remove();
                    }
                });
                $(document).on('click', '#is_samples', function (event) {
                    var val = $(this).prop("checked");
                    if (val == true) {
                        $("#wk_massupload_form .fieldset").append(sampleRow);
                    } else {
                        $("#sample_file").remove();
                    }
                });
                $(document).on('click', '#run-profile', function (event) {
                    var id = $("#profile").val();
                    if (id == "") {
                        alert({
                            title: 'Warning',
                            content: "<div class='wk-warning-content'>Please Select Profile</div>",
                            actions: {
                                always: function (){}
                            }
                        });
                    } else {
                        var url = defaultUrl+id;
                        window.open(url);
                    }
                });
                $(document).on('change', '#attribute_set', function (event) {
                    $('#attribute_profile_id').empty();
                    var id = $(this).val();
                    if (id != "") {
                        setCustomAttributeData(id);
                    } else {
                        $(".wk-mu-custom-attribute").html(defaultContent);
                    }
                    var attributeId = $("#attribute_set").val();
                    var profileName = "";
                    var countOption = 0;
                    $.each(attributeProfilesCollection, function( index, valueArray ) {
                        $.each(valueArray, function( index, value ) {
                            if(index == 'attribute_set_id' && value == attributeId) {
                                profileName = valueArray['profile_name'];
                                $("<option />", {value: attributeId, text: profileName}).appendTo(selectBox);
                                countOption++;
                            }
                        });
                    });
                    if (countOption == 0) {
                        $("<option />", {value: '', text: noMatchingProfileMsg}).appendTo(selectBox);
                    }
                });
                $(document).on('change', '#attribute_info', function (event) {
                    showLoader();
                    var code = $(this).val();
                    if (code == "") {
                        setDefaultContent(defaultTitle);
                        hideLoader();
                    } else {
                        if (code in options) {
                            setOptions(options[code]);
                            hideLoader();
                        } else {
                            $.ajax({
                                url: infoUrl,
                                type: 'POST',
                                dataType: 'json',
                                data: { code : code },
                                success: function (data) {
                                    options[code] = data;
                                    setOptions(data);
                                    hideLoader();
                                }
                            });
                        }
                    }
                });
                $(document).on('change', '#multi_select_attribute_info', function (event) {
                    showMultiSelectLoader();
                    var code = $(this).val();
                    if (code == "") {
                        setDefaultMultiSelectAttributeContent(multiSelectDefaultTitle);
                        hideMultiSelectLoader();
                    } else {
                        if (code in options) {
                            setMultiSelectOptions(options[code]);
                            hideMultiSelectLoader();
                        } else {
                            $.ajax({
                                url: infoUrl,
                                type: 'POST',
                                dataType: 'json',
                                data: { code : code },
                                success: function (data) {
                                    options[code] = data;
                                    setMultiSelectOptions(data);
                                    hideMultiSelectLoader();
                                }
                            });
                        }
                    }
                });
                $(document).on('change', '#massupload_csv', function (event) {
                    var fileName = $(this).val();
                    var ext = fileName.split('.').pop().toLowerCase();
                    if (ext == 'csv') {
                        validateFile(ext, 'csv', "#massupload_csv", false);
                    } else if (ext == 'xml') {
                        validateFile(ext, 'xml', "#massupload_csv", false);
                    } else {
                        validateFile(ext, 'xls', "#massupload_csv", false);
                    }
                });
                $(document).on('change', '#massupload_image', function (event) {
                    var fileName = $(this).val();
                    var ext = fileName.split('.').pop().toLowerCase();
                    validateFile(ext, 'zip', "#massupload_image", true);
                });
                function validateFile(ext, val, elementId, isZipFile)
                {
                    var content = $.mage.__('Invalid file type.');
                    if(isZipFile){
                        content =  $.mage.__('Please specify a zip file for image upload.');
                    }
                    if (ext != val) {
                        $(elementId).val('');
                        alert({
                            title: $.mage.__('Warning'),
                            content: "<div class='wk-warning-content'>"+content+"</div>",
                            buttons: [{
                                text: $.mage.__('Close Btn'),
                                class: 'action-primary action-accept',
                                click: function () {
                                    this.closeModal(true);
                                }
                            }]
                        });
                    }
                }
                function setDefaultContent(defaultTitle)
                {
                    $(".wk-mu-options-content").empty();
                    $(".wk-mu-options-content").append(defaultTitle);
                }
                function setOptions(json)
                {
                    $(".wk-mu-options-content").empty();
                    $.each(json, function (key, value) {
                        $(".wk-mu-options-content").append("<div class='wk-mu-options-item'>"+value+"</div>");
                    });
                }
                function showLoader()
                {
                    $(".wk-mu-sa-overlay").removeClass("wk-display-none");
                }
                function hideLoader()
                {
                    $(".wk-mu-sa-overlay").addClass("wk-display-none");
                }
                function setCustomAttributeData(id)
                {
                    var result = [];
                    var data = attributeSetInfo[id];
                    $.each(data, function (key, value) {
                        result.push(attributeDetails[value]);
                    });
                    var attributes = result.join(", ");
                    $(".wk-mu-custom-attribute").html(attributes);
                }
                function setMultiSelectOptions(json)
                {
                    $(".wk-multi-select-options-content").empty();
                    $.each(json, function (key, value) {
                        $(".wk-multi-select-options-content").append("<div class='wk-mu-options-item'>"+value+"</div>");
                    });
                }
                function setDefaultMultiSelectAttributeContent(multiSelectDefaultTitle)
                {
                    $(".wk-multi-select-options-content").empty();
                    $(".wk-multi-select-options-content").append(multiSelectDefaultTitle);
                }
                function showMultiSelectLoader()
                {
                    $(".wk-multi-select-overlay").removeClass("wk-display-none");
                }
                function hideMultiSelectLoader()
                {
                    $(".wk-multi-select-overlay").addClass("wk-display-none");
                }

                $(document).on('click', '.wk-mp-btn', function (e) {
                    if($('input[name=massupload_csv]').val() === ""){
                        e.preventDefault();
                        $("#modal-content").modal("openModal");
                    }
                })

            });
            var options = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                title: self.options.titleWarning,
                buttons: [{
                    text: self.options.buttonClose,
                    class: 'modal-close',
                    click: function (){
                        this.closeModal();
                    }
                }]
            };

            modal(options, $('#modal-content'));
        }
    });
    return $.mpmassupload.view;
});
