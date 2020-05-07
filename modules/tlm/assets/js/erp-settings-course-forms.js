;(function($) {
    'use strict';

    // remove default submit button
    if ($('.cfi-hide-submit').length) {
        $('p.submit').remove();
    }

    Vue.config.debug = tlmCourseFormsSettings.scriptDebug;

    // when get_option returns null, localized var for mappedData prints "" instead of {}
    if ( '[object Object]' !== Object.prototype.toString.call(tlmCourseFormsSettings.mappedData) ) {
        tlmCourseFormsSettings.mappedData = {};
    }

    // vue instances for every mapping table
    $('.cfi-table').each(function () {
        var table = $(this),
            id = table.attr('id'),
            plugin = table.data('plugin'),
            formId = table.data('form-id');

        if ( '[object Object]' !== Object.prototype.toString.call(tlmCourseFormsSettings.mappedData[plugin]) ) {
            tlmCourseFormsSettings.mappedData[plugin] = {};
        }

        new Vue({
            el: '#' + id,
            data: {
                i18n: tlmCourseFormsSettings.i18n,
                plugin: plugin,
                formId: formId,
                formData: tlmCourseFormsSettings.forms[plugin][formId],
                totalFields: 0,
                tlmOptions: tlmCourseFormsSettings.tlmOptions,
                courseGroups: tlmCourseFormsSettings.courseGroups,
                courseOwners: tlmCourseFormsSettings.courseOwners,
                activeDropDown: null
            },

            computed: {
                totalFields: function () {
                    return Object.keys(this.formData.fields).length;
                }
            },

            methods: {

                lastOfTypeClass: function (index) {
                    return index === (this.totalFields - 1) ? 'cfi-mapping-row-last' : '';
                },

                getTLMOptionTitle: function (field) {
                    var option = this.formData.map[field],
                        title = '';

                    if (option && option.indexOf('.') < 0) {
                        title = this.tlmOptions[ option ];

                    } else if (option) {
                        var arr = option.split('.');
                        title = this.tlmOptions[arr[0]].title + ' - ' + this.tlmOptions[arr[0]].options[arr[1]];
                    }

                    return title ? title : this.i18n.notMapped;
                },

                optionIsAnObject: function (option) {
                    return '[object Object]' === Object.prototype.toString.call(this.tlmOptions[option]);
                },

                mapOption: function (field, option) {
                    this.formData.map[field] = option;
                },

                mapChildOption: function (field, option, childOption) {
                    this.formData.map[field] = option + '.' + childOption;
                },

                isMapped: function (field) {
                    return !this.formData.map[field];
                },

                isOptionMapped: function (field, option) {
                    return this.formData.map[field] === option;
                },

                isChildOptionMapped: function (field, option, childOption) {
                    return this.formData.map[field] === (option + '.' + childOption);
                },

                resetMapping: function (field) {
                    this.formData.map[field] = null;
                },

                setActiveDropDown: function (field) {
                    this.activeDropDown = (field === this.activeDropDown) ? null: field;
                },

                save_mapping: function (e) {
                    e.preventDefault();
                    this.makeAjaxRequest('erp_settings_save_course_form');
                },

                reset_mapping: function (e) {
                    e.preventDefault();
                    this.makeAjaxRequest('erp_settings_reset_course_form');
                },

                makeAjaxRequest: function (action) {
                    var self = this;

                    $.ajax({
                        url: ajaxurl,
                        method: 'post',
                        dataType: 'json',
                        data: {
                            action: action,
                            _wpnonce: tlmCourseFormsSettings.nonce,
                            plugin: this.plugin,
                            formId: this.formId,
                            map: self.formData.map,
                            courseGroup: self.formData.courseGroup,
                            courseOwner: self.formData.courseOwner,
                        }

                    }).done(function (response) {

                        if ('erp_settings_reset_course_form' === action && response.success) {
                            self.$set('formData.map', response.map);
                            self.$set('formData.courseGroup', response.courseGroup);
                            self.$set('formData.courseOwner', response.courseOwner);
                        }

                        var type = response.success ? 'success' : 'error';

                        if (response.msg) {
                            swal({
                                title: '',
                                text: response.msg,
                                type: type,
                                confirmButtonText: self.i18n.labelOK,
                                confirmButtonColor: '#008ec2'
                            });
                        }

                    });
                }

            },

            watch: {
                'formData.map': {
                    deep: true,
                    handler: function (newVal) {
                        this.formData.map = newVal;
                    }
                },

                'formData.courseGroup': function (newVal) {
                    this.formData.courseGroup = newVal;
                }
            }
        });
    });
})(jQuery);
