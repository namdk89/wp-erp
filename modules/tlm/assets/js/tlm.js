/* jshint devel:true */
/* global wpErpTml */
/* global wp */

;(function ($) {
    'use strict';

    var isRequestDone = false;

    var WeDevs_ERP_TLM = {

        initialize: function () {
            // Course Group
            $('.erp-tlm-course-group').on('click', 'a.erp-new-course-group', this.courseGroup.create);
            $('.erp-tlm-course-group').on('click', 'span.edit a', this.courseGroup.edit);
            $('.erp-tlm-course-group').on('click', 'a.submitdelete', this.courseGroup.remove);

            //course Tag
            $('document').ready(this.courseTag.init);
            $('#add-tlm-tag').on('click', this.courseTag.add);
            $('.erp-customer-tag-div').on('keypress', '.newtag', this.courseTag.add);

            // Subscriber course
            $('.erp-tlm-subscriber-course').on('click', 'a.erp-new-subscriber-course', this.subscriberCourse.create);
            $('.erp-tlm-subscriber-course').on('click', 'span.edit a', this.subscriberCourse.edit);
            $('.erp-tlm-subscriber-course').on('click', 'a.submitdelete', this.subscriberCourse.remove);

            // Populate state according to country
            $('body').on('change', 'select.erp-country-select', this.populateState);
            $('body').on('change', 'select.erp-student-select', this.populatePurchased);

            // handle postbox toggle
            $('body').on('click', 'div.erp-handlediv', this.handlePostboxToggle);

            // When create modal open
            $('body').on( 'click', '#erp-customer-new', this.whenOpenTLMModal );
            $('body').on( 'click', '#erp-company-new', this.whenOpenTLMModal );
            $('body').on( 'click', '#erp-customer-edit', this.whenOpenTLMModal );
            $('body').on( 'click', '#erp-tlm-new-course', this.whenOpenTLMModal );

            // TLM Dashboard
            $('.tlm-dashboard').on('click', 'a.erp-tlm-dashbaord-show-details-schedule', this.dashboard.showScheduleDetails);

            $('body').on('change', 'input[type=checkbox][name="all_day"]', this.triggerCustomerScheduleAllDay);
            $('body').on('change', 'input[type=checkbox][name="allow_notification"]', this.triggerCustomerScheduleAllowNotification);
            $('body').on('change', 'select#erp-tlm-feed-log-type', this.triggerLogType);

            // Save Replies in settings page
            $('body').on('click', 'a#erp-tlm-add-save-replies', this.saveReplies.create);
            $('body').on('click', 'a.erp-tlm-delete-save-replies', this.saveReplies.remove);
            $('body').on('click', 'a.erp-tlm-save-replies-edit', this.saveReplies.edit);

            $('body').on('change', 'select#erp-tlm-template-shortcodes', this.saveReplies.setShortcodes);


            // Report
            if ( 'this_year' == $('#tlm-filter-duration').val() ) {
                $('.custom-filter').hide();
            }

            $( 'body').on( 'change', '#tlm-filter-duration', this.report.customFilter );

            // TLM tag
            this.initTagAddByEnterPressed();

            // Erp ToolTips using tiptip
            this.initCourseListAjax();
            this.initTipTips();
        },

        initTagAddByEnterPressed: function() {
            var enter_key = 13;

            $( '.newtag' ).on( 'keyup', function(e) {
                var code = e.keyCode || e.which;

                if ( code == enter_key ) {
                    $( '#add-tlm-tag' ).trigger('click');
                }
            } );
        },

        initTipTips: function () {
            $('.erp-tlm-tips').tipTip({
                defaultPosition: "top",
                fadeIn: 100,
                fadeOut: 100,
            });
        },

        initDateField: function () {
            $('.erp-tlm-date-field').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                yearRange: '-50:+5',
            });
        },

        /**
         * Timepicker initialize
         *
         * @return {[void]}
         */
        initTimePicker: function () {
            $('.erp-time-field').timepicker({
                'scrollDefault': 'now',
                'step': 15
            });
        },

        /**
         * Handle postbox toggle effect
         *
         * @param  {object} e
         *
         * @return {void}
         */
        handlePostboxToggle: function (e) {
            e.preventDefault();
            var self = $(this),
                postboxDiv = self.closest('.postbox');

            if (postboxDiv.hasClass('closed')) {
                postboxDiv.removeClass('closed');
            } else {
                postboxDiv.addClass('closed');
            }
        },
        initCourseListAjax: function() {
            $( 'select.erp-tlm-course-list-dropdown' ).select2({
                allowClear: true,
                placeholder: $(this).attr('data-placeholder'),
                minimumInputLength: 1,
                ajax: {
                    url: wpErpTml.ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    escapeMarkup: function (m) {
                        return m;
                    },
                    data: function (params) {
                        return {
                            s: params.term, // search term
                            _wpnonce: wpErpTml.nonce,
                            types: $(this).attr('data-types').split(','),
                            action: 'erp-search-tlm-courses'
                        };
                    },
                    processResults: function (data, params) {
                        var terms = [];

                        if (data) {
                            $.each(data.data, function (id, text) {
                                terms.push({
                                    id: id,
                                    text: text
                                });
                            });
                        }

                        if (terms.length) {
                            return {results: terms};
                        } else {
                            return {results: ''};
                        }
                    },
                    cache: true
                }
            });
        },

        /**
         * When open TLM modal to create course
         *
         * @param  {object} e
         *
         * @return {void}
         */
        whenOpenTLMModal: function(e) {
            $( '#advanced_fields' ).click( function( evt ) {
                if ( $( this ).is(' :checked ') ) {
                    $( '.others-info' ).show();
                    $( '.course-group' ).show();
                    $( '.additional-info' ).show();
                } else {
                    $( '.others-info' ).hide();
                    $( '.course-group' ).hide();
                    $( '.additional-info' ).hide();
                }
            } );
        },
        /**
         * Populate the state dropdown based on selected country
         *
         * @return {void}
         */
        populateState: function () {

            wpErpTml.wpErpCountries = wpErpCountries;

            if (typeof wpErpTml.wpErpCountries === 'undefined') {
                return false;
            }

            var self = $(this),
                country = self.val(),
                parent = self.closest(self.data('parent')),
                empty = '<option value="">- '+ __('Select', 'erp') +' -</option>';

            if (wpErpTml.wpErpCountries[country]) {
                var options = '',
                    state = wpErpTml.wpErpCountries[country];

                for (var index in state) {
                    options = options + '<option value="' + index + '">' + state[index] + '</option>';
                }

                if ($.isArray(wpErpTml.wpErpCountries[country])) {
                    $('.erp-state-select').html(empty);
                } else {
                    $('.erp-state-select').html(options);
                }

            } else {
                $('.erp-state-select').html(empty);
            }
        },
        /**
         * Populate the purchased dropdown based on selected student
         *
         * @return {void}
         */
        populatePurchased: function () {
            var self = $(this),
                student = self.val(),
                empty = '<option value="">--'+ __('Select', 'erp') +'--</option>';

            if (student == '') {
                $('.erp-purchased-select').html(empty);
            } else {
                var options = '',

                options = options + '<option value="' + 0 + '">' + student + '</option>';
                options = options + '<option value="' + 0 + '">' + 'V3' + '</option>';

                $('.erp-purchased-select').html(options);
            }
        },
        triggerCustomerScheduleAllDay: function () {
            var self = $(this);

            if (self.is(':checked')) {
                self.closest('div.schedule-datetime').find('.erp-time-field').attr('disabled', 'disabled').hide();
                self.closest('div.schedule-datetime').find('.datetime-sep').hide();
            } else {
                self.closest('div.schedule-datetime').find('.erp-time-field').removeAttr('disabled').show();
                self.closest('div.schedule-datetime').find('.datetime-sep').show();
            }
            ;
        },

        triggerCustomerScheduleAllowNotification: function () {
            var self = $(this);

            if (self.is(':checked')) {
                self.closest('.erp-tlm-new-schedule-wrapper').find('#schedule-notification-wrap').show();
            } else {
                self.closest('.erp-tlm-new-schedule-wrapper').find('#schedule-notification-wrap').hide();
            }
        },

        triggerLogType: function () {
            var self = $(this);

            if (self.val() == 'meeting') {
                self.closest('.feed-log-activity').find('.log-email-subject').hide();
                self.closest('.feed-log-activity').find('.log-selected-course').show();
            } else if (self.val() == 'email') {
                self.closest('.feed-log-activity').find('.log-email-subject').show();
                self.closest('.feed-log-activity').find('.log-selected-course').hide();
            } else {
                self.closest('.feed-log-activity').find('.log-email-subject').hide();
                self.closest('.feed-log-activity').find('.log-selected-course').hide();
            }
        },

        dashboard: {

            showScheduleDetails: function (e) {
                e.preventDefault();
                var self = $(this),
                    scheduleId = self.data('schedule_id');

                $.erpPopup({
                    title: self.attr('data-title'),
                    button: '',
                    id: 'erp-customer-edit',
                    onReady: function () {
                        var modal = this;

                        $('header', modal).after($('<div class="loader"></div>').show());

                        wp.ajax.send('erp-tlm-get-single-schedule-details', {
                            data: {
                                id: scheduleId,
                                _wpnonce: wpErpTml.nonce
                            },

                            success: function (response) {
                                var startDate = wperp.dateFormat(response.start_date, 'j F'),
                                    startTime = wperp.timeFormat(response.start_date),
                                    endDate = wperp.dateFormat(response.end_date, 'j F'),
                                    endTime = wperp.timeFormat(response.end_date);

                                if (response.extra.all_day == 'true') {
                                    if (wperp.dateFormat(response.start_date, 'Y-m-d') == wperp.dateFormat(response.end_date, 'Y-m-d')) {
                                        var datetime = startDate;
                                    } else {
                                        var datetime = startDate + ' to ' + endDate;
                                    }
                                } else {
                                    if (wperp.dateFormat(response.start_date, 'Y-m-d') == wperp.dateFormat(response.end_date, 'Y-m-d')) {
                                        var datetime = startDate + ' at ' + startTime + ' to ' + endTime;
                                    } else {
                                        var datetime = startDate + ' at ' + startTime + ' to ' + endDate + ' at ' + endTime;
                                    }
                                }

                                var html = wp.template('erp-tlm-single-schedule-details')({
                                    date: datetime,
                                    schedule: response
                                });
                                $('.content', modal).html(html);
                                $('.loader', modal).remove();

                                $('.erp-tips').tipTip({
                                    defaultPosition: "top",
                                    fadeIn: 100,
                                    fadeOut: 100,
                                });
                            },

                            error: function (response) {
                                modal.showError(response);
                            }

                        });
                    }
                });

            }
        },

        courseGroup: {

            pageReload: function () {
                $('.erp-tlm-course-group-list-table-wrap').load(window.location.href + ' .erp-tlm-course-group-list-table-inner');
            },

            create: function (e) {
                e.preventDefault();
                var self = $(this);
                $.erpPopup({
                    title: self.attr('title'),
                    button: wpErpTml.add_submit,
                    id: 'erp-tlm-new-course-group',
                    content: wperp.template('erp-tlm-new-course-group')({data: {}}).trim(),
                    extraClass: 'smaller',

                    onSubmit: function (modal) {
                        modal.disableButton();

                        wp.ajax.send({
                            data: this.serialize(),
                            success: function (res) {
                                WeDevs_ERP_TLM.courseGroup.pageReload();
                                modal.enableButton();
                                modal.closeModal();
                            },
                            error: function (error) {
                                modal.enableButton();
                                modal.showError(error);
                            }
                        });
                    },

                    onReady: function () {
                        var modal = this;

                        $('div.row[data-checked]', modal).each(function (key, val) {
                            var self = $(this),
                                checked = self.data('checked');

                            if (checked !== '') {
                                self.find('input[value="' + checked + '"]').attr('checked', 'checked');
                            }
                        });
                    }
                }); //popup
            },

            edit: function (e) {
                e.preventDefault();

                var self = $(this),
                    query_id = self.data('id');

                $.erpPopup({
                    title: self.attr('title'),
                    button: wpErpTml.update_submit,
                    id: 'erp-tlm-edit-course-group',
                    extraClass: 'smaller',
                    onReady: function () {
                        var modal = this;

                        $('header', modal).after($('<div class="loader"></div>').show());

                        wp.ajax.send('erp-tlm-edit-course-group', {
                            data: {
                                id: query_id,
                                _wpnonce: wpErpTml.nonce
                            },
                            success: function (res) {
                                var html = wp.template('erp-tlm-new-course-group')(res);
                                $('.content', modal).html(html);
                                $('.loader', modal).remove();

                                $('div.row[data-checked]', modal).each(function (key, val) {
                                    var self = $(this),
                                        checked = self.data('checked');

                                    if (checked !== '') {
                                        self.find('input[value="' + checked + '"]').attr('checked', 'checked');
                                    }
                                });
                            }
                        });
                    },

                    onSubmit: function (modal) {
                        modal.disableButton();

                        wp.ajax.send({
                            data: this.serialize(),
                            success: function (res) {
                                WeDevs_ERP_TLM.courseGroup.pageReload();
                                modal.enableButton();
                                modal.closeModal();
                            },
                            error: function (error) {
                                modal.enableButton();
                                modal.showError(error);
                            }
                        });
                    }

                });
            },

            remove: function (e) {
                e.preventDefault();

                var self = $(this);

                if (confirm(wpErpTml.delConfirm)) {
                    wp.ajax.send('erp-tlm-course-group-delete', {
                        data: {
                            '_wpnonce': wpErpTml.nonce,
                            id: self.data('id')
                        },
                        success: function () {
                            self.closest('tr').fadeOut('fast', function () {
                                $(this).remove();
                                WeDevs_ERP_TLM.courseGroup.pageReload();
                            });
                        },
                        error: function (response) {
                            alert(response);
                        }
                    });
                }
            }
        },

        courseTag: {
            init:function () {
                $(document).on('click', '.ntdelbutton', function () {
                    var tags = $('#tax-input-erp_tlm_tag').val();
                    var course_id = $('#course_id').val();

                    wp.ajax.send('erp_tlm_update_course_tag', {
                        data: {
                            _wpnonce: wpErpTml.nonce,
                            tags: tags,
                            course_id: course_id,
                        },
                        success: function (res) {

                        }
                    });
                });
            },
            add: function () {
                var tags = $('#tax-input-erp_tlm_tag').val();
                var course_id = $('#course_id').val();

                wp.ajax.send('erp_tlm_update_course_tag', {
                    data: {
                        _wpnonce: wpErpTml.nonce,
                        tags: tags,
                        course_id: course_id,
                    },
                    success: function (res) {
                        console.log(res);
                    }
                });
            },
        },

        subscriberCourse: {
            pageReload: function () {
                $('.erp-tlm-subscriber-course-list-table-wrap').load(window.location.href + ' .erp-tlm-subscriber-course-list-table-inner');
            },

            create: function (e) {
                e.preventDefault();
                var self = $(this);

                $.erpPopup({
                    title: self.attr('title'),
                    button: wpErpTml.add_submit,
                    id: 'erp-tlm-assign-subscriber-course',
                    extraClass: 'smaller',
                    onReady: function (modal) {

                        var modal = this;

                        $('header', modal).after($('<div class="loader"></div>').show());

                        wp.ajax.send('erp-tlm-exclued-already-assigned-course', {
                            data: {
                                _wpnonce: wpErpTml.nonce
                            },
                            success: function (res) {
                                var html = wp.template('erp-tlm-assign-subscriber-course')({data: {}});
                                $('.content', modal).html(html);

                                _.each($('.select2').find('option'), function (el, i) {
                                    var optionVal = $(el).val();
                                    if (_.contains(res, optionVal)) {
                                        $(el).attr('disabled', 'disabled');
                                    }
                                    ;
                                });

                                WeDevs_ERP_TLM.initCourseListAjax();
                                $('.loader', modal).remove();
                            }
                        });

                    },

                    onSubmit: function (modal) {

                        if ($("input:checkbox:checked").length > 0) {
                            modal.disableButton();
                            wp.ajax.send({
                                data: this.serialize(),
                                success: function (res) {
                                    WeDevs_ERP_TLM.subscriberCourse.pageReload();
                                    modal.enableButton();
                                    modal.closeModal();
                                },
                                error: function (error) {
                                    modal.enableButton();
                                    alert(error);
                                }
                            });
                        } else {
                            modal.showError(wpErpTml.checkedConfirm);
                        }
                    }
                }); //popup
            },

            edit: function (e) {
                e.preventDefault();

                var self = $(this),
                    query_id = self.data('id'),
                    name = self.data('name');

                $.erpPopup({
                    title: self.attr('title'),
                    button: wpErpTml.update_submit,
                    id: 'erp-tlm-edit-course-subscriber',
                    extraClass: 'smaller',
                    onReady: function () {
                        var modal = this;

                        $('header', modal).after($('<div class="loader"></div>').show());

                        wp.ajax.send('erp-tlm-edit-course-subscriber', {
                            data: {
                                id: query_id,
                                name: name,
                                _wpnonce: wpErpTml.nonce
                            },
                            success: function (res) {
                                var html = wp.template('erp-tlm-assign-subscriber-course')({
                                    group_id: res.groups,
                                    user_id: query_id
                                });
                                $('.content', modal).html(html);
                                _.each($('input[type=checkbox].erp-tlm-course-group-class'), function (el, i) {
                                    var optionsVal = $(el).val();
                                    if (_.contains(res.groups, optionsVal) && res.results[optionsVal].status == 'subscribe') {
                                        $(el).prop('checked', true);
                                    }
                                    if (_.contains(res.groups, optionsVal) && res.results[optionsVal].status == 'unsubscribe') {
                                        $(el).closest('label').find('span.checkbox-value')
                                            .append('<span class="unsubscribe-group">' + res.results[optionsVal].unsubscribe_message + '</span>');
                                    }
                                    ;

                                });

                                $('.loader', modal).remove();
                            }
                        });
                    },

                    onSubmit: function (modal) {
                        modal.disableButton();

                        wp.ajax.send({
                            data: this.serialize(),
                            success: function (res) {
                                if (e.target.id == 'erp-course-update-assign-group') {
                                    $('.course-group-content').load(window.location.href + ' .course-group-list', function () {
                                        WeDevs_ERP_TLM.initTipTips();
                                    });
                                } else {
                                    WeDevs_ERP_TLM.subscriberCourse.pageReload();
                                }

                                modal.enableButton();
                                modal.closeModal();
                            },
                            error: function (error) {
                                modal.enableButton();
                                modal.showError(error);
                            }
                        });
                    }

                });
            },
            remove: function (e) {
                e.preventDefault();

                var self = $(this);

                if (confirm(wpErpTml.delConfirm)) {
                    wp.ajax.send('erp-tlm-course-subscriber-delete', {
                        data: {
                            '_wpnonce': wpErpTml.nonce,
                            group_id: self.data('group_id'),
                            id: self.data('id')
                        },
                        success: function () {
                            self.closest('tr').fadeOut('fast', function () {
                                $(this).remove();
                                WeDevs_ERP_TLM.courseGroup.pageReload();
                            });
                        },
                        error: function (response) {
                            alert(response);
                        }
                    });
                }
            }
        },

        saveReplies: {

            pageReload: function () {
                $('td.erp-tlm-templates-wrapper').load(window.location.href + ' table.erp-tlm-templates-table');
            },

            create: function (e) {
                e.preventDefault();

                var self = $(this);

                $.erpPopup({
                    title: self.attr('title'),
                    button: wpErpTml.add_submit,
                    id: 'erp-tlm-new-save-replies',
                    content: wperp.template('erp-tlm-new-save-replies')({data: {}}).trim(),
                    extraClass: 'larger',

                    onSubmit: function (modal) {
                        modal.disableButton();

                        wp.ajax.send({
                            data: this.serialize(),
                            success: function (res) {
                                WeDevs_ERP_TLM.saveReplies.pageReload();
                                modal.enableButton();
                                modal.closeModal();
                            },
                            error: function (error) {
                                modal.enableButton();
                                modal.showError(error);
                            }
                        });
                    }
                }); //popup

            },

            edit: function (e) {
                e.preventDefault();

                var self = $(this),
                    query_id = self.data('id');

                $.erpPopup({
                    title: self.attr('title'),
                    button: wpErpTml.update_submit,
                    id: 'erp-tlm-edit-save-replies',
                    extraClass: 'larger',
                    onReady: function () {
                        var modal = this;

                        $('header', modal).after($('<div class="loader"></div>').show());

                        wp.ajax.send('erp-tlm-edit-save-replies', {
                            data: {
                                id: query_id,
                                _wpnonce: wpErpTml.nonce
                            },
                            success: function (res) {
                                var html = wp.template('erp-tlm-new-save-replies')(res);
                                $('.content', modal).html(html);
                                $('.loader', modal).remove();
                            }
                        });
                    },

                    onSubmit: function (modal) {
                        modal.disableButton();

                        wp.ajax.send({
                            data: this.serialize(),
                            success: function (res) {
                                WeDevs_ERP_TLM.saveReplies.pageReload();
                                modal.enableButton();
                                modal.closeModal();
                            },
                            error: function (error) {
                                modal.enableButton();
                                modal.showError(error);
                            }
                        });
                    }

                });
            },

            remove: function (e) {
                e.preventDefault();

                var self = $(this);

                if (confirm(wpErpTml.delConfirm)) {
                    wp.ajax.send('erp-tlm-delete-save-replies', {
                        data: {
                            _wpnonce: wpErpTml.nonce,
                            id: self.data('id')
                        },
                        success: function () {
                            self.closest('tr').fadeOut('fast', function () {
                                $(this).remove();
                            });
                        },
                        error: function (response) {
                            alert(response);
                        }
                    });
                }
            },

            setShortcodes: function (e) {
                e.preventDefault();
                var self = $(this);
                var element = document.querySelector("trix-editor");
                element.editor.insertString(self.val());
                self.val('');
            }

        },

        report: {
            customFilter: function () {
                if ( 'custom' == this.value ) {
                    $('.custom-filter').show();
                } else {
                    $('.custom-filter').hide();
                }
            }
        }
    };

    $(function () {
        WeDevs_ERP_TLM.initialize();
    });

})(jQuery);
