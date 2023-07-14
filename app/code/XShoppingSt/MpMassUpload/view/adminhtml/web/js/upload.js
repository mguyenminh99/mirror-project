define([
"jquery",
"jquery/ui",
], function ($) {
    'use strict';
    $.widget('mpmassupload.upload', {
        options: {},
        _create: function () {
            var self = this;
            var row = self.options.row;
            var linkSampleRow = self.options.linkSampleRow;
            var sampleRow = self.options.sampleRow;
            var attributeProfilesCollection = self.options.attributeProfilesCollection;
            var noMatchingProfileMsg = self.options.noMatchingProfileMsg;
            $(document).ready(function () {
                $('#is_downloadable').on('click', function () {
                    var val = $(this).prop("checked");
                    if (val == true) {
                        $("#base_fieldset").append(row);
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
                        $("#base_fieldset").append(linkSampleRow);
                    } else {
                        $("#link_sample_file").remove();
                    }
                });
                $(document).on('click', '#is_samples', function (event) {
                    var val = $(this).prop("checked");
                    if (val == true) {
                        $("#base_fieldset").append(sampleRow);
                    } else {
                        $("#sample_file").remove();
                    }
                });
                $(document).on('change', '#attribute_set', function (event) {
                    $('#attribute_profile_id').empty();
                    var attributeId = $("#attribute_set").val();
                    var profileName = "";
                    var countOption = 0;
                    $.each(attributeProfilesCollection, function( index, valueArray ) {
                        $.each(valueArray, function( index, value ) {
                            if(index == 'attribute_set_id' && value == attributeId) {
                                profileName = valueArray['profile_name'];
                                $("<option />", {value: attributeId, text: profileName}).appendTo('#attribute_profile_id');
                                countOption++;
                            }
                        });
                      });
                      if (countOption == 0) {
                        $("<option />", {value: '', text: noMatchingProfileMsg}).appendTo('#attribute_profile_id');
                      }
                });
            });
        }
    });
    return $.mpmassupload.upload;
});
