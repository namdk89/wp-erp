<?php

/**
 * Register metabox widget in right side
 * for tlm dashbaord
 *
 * @since 1.0
 *
 * @return void
 */
function erp_tlm_dashboard_right_widgets_area() {
    erp_admin_dash_metabox( __( '<i class="fa fa-calendar-check-o"></i> Today\'s Schedules', 'erp' ), 'erp_tlm_dashboard_widget_todays_schedules' );
    erp_admin_dash_metabox( __( '<i class="fa fa-calendar-check-o"></i> Upcoming Schedules', 'erp' ), 'erp_tlm_dashboard_widget_upcoming_schedules' );
    erp_admin_dash_metabox( __( '<i class="fa fa-users"></i> Recently Added', 'erp' ), 'erp_tlm_dashboard_widget_latest_course' );


    erp_admin_dash_metabox( __( '<i class="fa fa-envelope"></i> Total Inbound Emails', 'erp' ), 'erp_tlm_dashboard_widget_inbound_emails' );
}

/**
 * Register metabox widget in left side
 * for tlm dashboard
 *
 * @since 1.0
 *
 * @return void
 */
function erp_tlm_dashboard_left_widgets_area() {
    erp_admin_dash_metabox( __( '<i class="fa fa-calendar"></i> My schedules', 'erp' ), 'erp_tlm_dashboard_widget_my_schedules' );
    erp_admin_dash_metabox( __( '<i class="fa fa-calendar"></i> Customer Statistics', 'erp' ), 'tlm_customer_statics' );
}

/**
 * TLM Dashboard Todays Schedules widgets
 *
 * @since 1.0
 *
 * @return void
 */
function erp_tlm_dashboard_widget_todays_schedules() {
    $todays_schedules = erp_tlm_get_todays_schedules_activity( get_current_user_id() );
    ?>
    <?php if ( $todays_schedules ): ?>

    <ul class="erp-list list-two-side list-sep erp-tlm-dashbaord-todays-schedules">
        <?php foreach ( $todays_schedules as $key => $schedule ) : ?>
            <li>
                <?php
                    $users_text   = '';
                    $invite_users = isset( $schedule['extra']['invite_course'] ) ? $schedule['extra']['invite_course'] : [];

                    if ( in_array( 'course', $schedule['course']['types'] ) ) {
                        $course_user = $schedule['course']['last_name'] . ' ' . $schedule['course']['first_name'];
                    } else if( in_array( 'company', $schedule['course']['types'] ) )  {
                        $course_user = $schedule['course']['company'];
                    }

                    array_walk( $invite_users, function( &$val ) {
                        $val = get_the_author_meta( 'display_name', $val );
                    });

                    if ( count( $invite_users) == 1 ) {
                        $users_text = sprintf( '%s <span>%s</span>', __( 'and', 'erp' ), reset( $invite_users ) );
                    } else if ( count( $invite_users) > 1 ) {
                        $users_text = sprintf( '%s <span class="erp-tips" title="%s">%d %s</span>', __( 'and', 'erp' ), implode( ',', $invite_users ), count( $invite_users ), __( 'Others') );
                    }


                    switch ( $schedule['log_type'] ) {
                        case 'meeting':
                            $icon = 'calendar';
                            $text = __( 'Meeting with', 'erp' );
                            $data_title = __( 'Log Activity - Meeting', 'erp' );
                            break;

                        case 'call':
                            $icon = 'phone';
                            $text = __( 'Call', 'erp' );
                            $data_title = __( 'Log Activity - Call', 'erp' );
                            break;

                        case 'email':
                            $icon = 'envelope-o';
                            $text = __( 'Send email to', 'erp' );
                            $data_title = __( 'Log Activity - Email', 'erp' );
                            break;

                        case 'sms':
                            $icon = 'comment-o';
                            $text = __( 'Send sms to', 'erp' );
                            $data_title = __( 'Log Activity - SMS', 'erp' );
                            break;

                        default:
                            $icon = '';
                            $text = '';
                            $data_title = '';
                            break;
                    }


                    echo wp_kses_post(
                        sprintf(
                            '<i class="fa fa-%s"></i> %s <a href="%s">%s</a> %s %s %s',
                            $icon,
                            $text,
                            erp_tlm_get_details_url( $schedule['course']['id'], $schedule['course']['types'] ),
                            $course_user,
                            $users_text,
                            __( 'at', 'erp' ),
                            date( 'g:ia', strtotime( $schedule['start_date'] ) )
                        )
                    );

                    do_action( 'erp_tlm_dashboard_widget_todays_schedules', $schedule );

                    $data_title = apply_filters( 'erp_tlm_dashboard_widget_todays_schedules_title', $data_title, $schedule );

                ?>
                | <a
                    href="#"
                    data-schedule_id="<?php echo esc_attr( $schedule['id'] ); ?>"
                    data-title="<?php echo esc_attr( $data_title ) ?>"
                    class="erp-tlm-dashbaord-show-details-schedule"
                ><?php esc_attr_e('Details', 'erp' ); ?></a>

            </li>
        <?php endforeach ?>
    </ul>
     <?php else : ?>
        <?php esc_attr_e( 'No schedules found', 'erp' ); ?>
    <?php endif;
}

/**
 * TLM Dashbaord upcoming schedules widgets
 *
 * @since 1.0
 *
 * @return void [html]
 */
function erp_tlm_dashboard_widget_upcoming_schedules() {
    $upcoming_schedules = erp_tlm_get_next_seven_day_schedules_activities( get_current_user_id() );
    ?>

    <?php if ( $upcoming_schedules ): ?>
        <ul class="erp-list list-two-side list-sep erp-tlm-dashbaord-upcoming-schedules">
            <?php foreach ( $upcoming_schedules as $key => $schedule ) : ?>
                <li>
                    <?php
                        $users_text   = '';
                        $invite_users = isset( $schedule['extra']['invite_course'] ) ? $schedule['extra']['invite_course'] : [];
                        $course_user = $schedule['course']['last_name'] . ' ' . $schedule['course']['first_name'];

                        array_walk( $invite_users, function( &$val ) {
                            $val = get_the_author_meta( 'display_name', $val );
                        });

                        if ( count( $invite_users) == 1 ) {
                            $users_text = sprintf( '%s <span>%s</span>', __( 'and', 'erp' ), reset( $invite_users ) );
                        } else if ( count( $invite_users) > 1 ) {
                            $users_text = sprintf( '%s <span class="erp-tips" title="%s">%d %s</span>', __( 'and', 'erp' ), implode( ', ', $invite_users ), count( $invite_users ), __( 'Others') );
                        }

                        if ( $schedule['log_type'] == 'meeting' ) {
                            echo ( sprintf( '%s <a href="%s">%s</a> %s %s %s %s %s', __( '<i class="fa fa-calendar"></i> Meeting with', 'erp' ), erp_tlm_get_details_url( $schedule['course']['id'], $schedule['course']['types'] ), $course_user, $users_text, __( 'on', 'erp' ), erp_format_date( $schedule['start_date'] ), __( 'at', 'erp' ), date( 'g:ia', strtotime( $schedule['start_date'] ) ) ) . " <a href='#' data-schedule_id=' " . $schedule['id'] . " ' data-title='" . $schedule['extra']['schedule_title'] . "' class='erp-tlm-dashbaord-show-details-schedule'>" . __( 'Details &rarr;', 'erp' ) . "</a>" );
                        }

                        if ( $schedule['log_type'] == 'call' ) {
                            echo ( sprintf( '%s <a href="%s">%s</a> %s %s %s %s %s', __( '<i class="fa fa-phone"></i> Call to', 'erp' ), erp_tlm_get_details_url( $schedule['course']['id'], $schedule['course']['types'] ), $course_user, $users_text, __( 'on', 'erp' ), erp_format_date( $schedule['start_date'] ), __( 'at', 'erp' ), date( 'g:ia', strtotime( $schedule['start_date'] ) ) ) . " <a href='#' data-schedule_id=' " . $schedule['id'] . " ' data-title='" . $schedule['extra']['schedule_title'] . "' class='erp-tlm-dashbaord-show-details-schedule'>" . __( 'Details &rarr;', 'erp' ) . "</a>" );
                        }
                    ?>
                </li>
            <?php endforeach ?>
        </ul>
    <?php else : ?>
        <?php esc_attr_e( 'No schedules found', 'erp' ); ?>
    <?php endif;
}

/**
 * Show all schedules in calendar
 *
 * @since 1.0
 *
 * @return void
 */
function erp_tlm_dashboard_widget_my_schedules() {
    $user_id        = get_current_user_id();
    $args           = [
        'created_by' => $user_id,
        'number'     => -1,
        'type'       => 'log_activity'
    ];

    $schedules      = erp_tlm_get_feed_activity( $args );
    $schedules_data = erp_tlm_prepare_calendar_schedule_data( $schedules );

    ?>
    <style>
        .fc-time {
            display:none;
        }
        .fc-title {
            cursor: pointer;
        }
        .fc-day-grid-event .fc-content {
            white-space: normal;
        }
    </style>

    <div id="erp-tlm-calendar"></div>
    <script>
        ;jQuery(document).ready(function($) {
            $('#erp-tlm-calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                editable: false,
                eventLimit: true,
                events: <?php echo json_encode( $schedules_data ); ?>,
                eventClick: function(calEvent, jsEvent, view) {
                    var scheduleId = calEvent.schedule.id;
                    $.erpPopup({
                        title: ( calEvent.schedule.extra.schedule_title ) ? calEvent.schedule.extra.schedule_title : '<?php esc_attr_e( 'Log Details', 'erp' ) ?>',
                        button: '',
                        id: 'erp-customer-edit',
                        onReady: function() {
                            var modal = this;

                            $( 'header', modal).after( $('<div class="loader"></div>').show() );

                            wp.ajax.send( 'erp-tlm-get-single-schedule-details', {
                                data: {
                                    id: scheduleId,
                                    _wpnonce: '<?php echo esc_attr( wp_create_nonce( 'wp-erp-tlm-nonce' ) ); ?>'
                                },

                                success: function( response ) {
                                    var startDate = wperp.dateFormat( response.start_date, 'j F' ),
                                        startTime = wperp.timeFormat( response.start_date ),
                                        endDate = wperp.dateFormat( response.end_date, 'j F' ),
                                        endTime = wperp.timeFormat( response.end_date );

                                    if ( ! response.end_date ) {
                                        var datetime = startDate + ' at ' + startTime;
                                    } else {
                                        if ( response.extra.all_day == 'true' ) {
                                            if ( wperp.dateFormat( response.start_date, 'Y-m-d' ) == wperp.dateFormat( response.end_date, 'Y-m-d' ) ) {
                                                var datetime = startDate;
                                            } else {
                                                var datetime = startDate + ' to ' + endDate;
                                            }
                                        } else {
                                            if ( wperp.dateFormat( response.start_date, 'Y-m-d' ) == wperp.dateFormat( response.end_date, 'Y-m-d' ) ) {
                                                var datetime = startDate + ' at ' + startTime + ' to ' + endTime;
                                            } else {
                                                var datetime = startDate + ' at ' + startTime + ' to ' + endDate + ' at ' + endTime;
                                            }
                                        }
                                    }

                                    var html = wp.template('erp-tlm-single-schedule-details')( { date: datetime, schedule: response } );
                                    $( '.content', modal ).html( html );
                                    $( '.loader', modal).remove();

                                    $('.erp-tips').tipTip( {
                                        defaultPosition: "top",
                                        fadeIn: 100,
                                        fadeOut: 100,
                                    } );

                                },

                                error: function( response ) {
                                    alert(response);
                                }

                            });
                        }
                    });
                },

            });
        });
    </script>
    <?php
}

/**
 * Latest course widget in tlm dashboard
 *
 * @since 1.0
 *
 * @return html|void
 */
function erp_tlm_dashboard_widget_latest_course() {
    $courses  = erp_get_peoples( [ 'type' => 'course', 'orderby' => 'created', 'order' => 'DESC', 'number' => 5 ] );
    $companies = erp_get_peoples( [ 'type' => 'company', 'orderby' => 'created', 'order' => 'DESC', 'number' => 5 ] );

    $tlm_life_stages = erp_tlm_get_life_stages_dropdown_raw();
    ?>

    <h4><?php esc_attr_e( 'Courses', 'erp' ); ?></h4>

    <?php if ( $courses ) { ?>

        <ul class="erp-list erp-latest-course-list">
            <?php foreach ( $courses as $course ) : ?>
                <?php
                    $course_obj = new WeDevs\ERP\TLM\Course( (int)$course->id );
                    $life_stage = $course_obj->get_life_stage();
                ?>
                <li>
                    <div class="avatar">
                        <?php echo wp_kses_post( $course_obj->get_avatar(28) ); ?>
                    </div>
                    <div class="details">
                        <p class="course-name"><a href="<?php echo esc_attr( $course_obj->get_details_url() ); ?>"><?php echo esc_attr( $course_obj->get_full_name() ); ?></a></p>
                        <p class="course-stage"><?php echo isset( $tlm_life_stages[ $life_stage ] ) ? esc_attr( $tlm_life_stages[ $life_stage ] ) : ''; ?></p>
                    </div>
                    <span class="course-created-time erp-tips" title="<?php echo wp_kses_post( sprintf( '%s %s', __( 'Created on', 'erp' ), erp_format_date( $course->created ) ) ) ?>"><i class="fa fa-clock-o"></i></span>
                </li>
            <?php endforeach ?>
        </ul>

    <?php } else { ?>
        <?php esc_attr_e( 'No courses found', 'erp' ); ?>
    <?php } ?>

    <hr>

    <h4><?php esc_attr_e( 'Companies', 'erp' ); ?></h4>

    <?php if ( $companies ) { ?>
        <ul class="erp-list erp-latest-course-list">
            <?php foreach ( $companies as $company ) : ?>
                <?php
                    $company_obj = new WeDevs\ERP\TLM\Course( intval( $company->id ) );
                    $life_stage = $company_obj->get_life_stage();
                ?>
                <li>
                    <div class="avatar">
                        <?php echo wp_kses_post( $company_obj->get_avatar(28) ); ?>
                    </div>

                    <div class="details">
                        <p class="course-name"><a href="<?php echo esc_url_raw( $company_obj->get_details_url() ); ?>"><?php echo esc_attr( $company_obj->get_full_name() ); ?></a></p>
                        <p class="course-stage"><?php echo isset( $tlm_life_stages[ $life_stage ] ) ? esc_attr( $tlm_life_stages[ $life_stage ] ) : ''; ?></p>
                    </div>
                    <span class="course-created-time erp-tips" title="<?php echo wp_kses_post( sprintf( '%s %s', __( 'Created on', 'erp' ), erp_format_date( $company->created ) ) ) ?>"><i class="fa fa-clock-o"></i></span>
                </li>
            <?php endforeach ?>
        </ul>
    <?php
    } else {
        esc_attr_e( 'No companies found', 'erp' );
    }
}

/**
 * TLM Dashboard Inbound Emails widget.
 *
 * @since 1.0
 *
 * @return void [html]
 */
function erp_tlm_dashboard_widget_inbound_emails() {
    $total_emails_count = get_option( 'wp_erp_inbound_email_count', 0 );
    echo wp_kses_post( '<h1 style="text-align: center;">' . $total_emails_count . '</h1>' );
}

/**
 * TLM Dashboard customer statics widget.
 *
 * @since 1.0
 *
 * @return void [html]
 */
function tlm_customer_statics() {
    wp_enqueue_script( 'erp-jvectormap' );
    wp_enqueue_script( 'erp-jvectormap-world-mill' );
    wp_enqueue_style( 'erp-jvectormap' );

    echo '<div id="erp-hr-customer-statics" style="width: 100%; height: 300px;"></div>';
    $customer_countries = array();
    if ( false == get_transient( 'erp_customer_countries_widget' ) ) {
        global $wpdb;
        $countries = $wpdb->get_results( 'SELECT country FROM ' . $wpdb->prefix . 'erp_peoples', OBJECT );

        $codes     = array();
        foreach ( $countries as $code_of ) {
            if( !is_null($code_of->country)){
                $codes[] = $code_of->country;
            }
        }

        $customer_countries = array_count_values( $codes );
        set_transient( 'erp_customer_countries_widget', $customer_countries, time() + ( 3 * HOUR_IN_SECONDS ) );
    } else {
        $customer_countries = get_transient( 'erp_customer_countries_widget' );
    }

    ob_start();
    ?>
    <script>
        jQuery(document).ready(function () {
            jQuery('#erp-hr-customer-statics').vectorMap({
                map: 'world_mill',
                backgroundColor: '#e0e0e0',
                zoomOnScroll: false,
                series: {
                    regions: [{
                        values: <?php echo json_encode( $customer_countries ); ?>,
                        scale: ['#C8EEFF', '#0071A4'],
                        normalizeFunction: 'polynomial'
                    }]
                },
                onRegionTipShow: function (e, el, code) {
                    if (typeof <?php echo json_encode( $customer_countries ); ?>[code] === 'undefined') {
                        el.html('No data');
                    } else {
                        el.html(el.html() + ': ' + <?php echo json_encode( $customer_countries ); ?>[code]);
                    }
                }
            });
        });
    </script>
    <?php
    $output = ob_get_contents();
    ob_get_clean();
    echo $output;

}

function tlm_vue_customer_script_dep( $dep ) {
    if ( defined( 'WPERP_DOC' ) ) {
        array_unshift($dep,'erp-document-upload','erp-document', 'erp-document-entry' );
    }
    return $dep;
}
