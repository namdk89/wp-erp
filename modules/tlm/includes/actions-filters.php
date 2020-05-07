<?php

// Actions *****************************************************************/
add_action( 'user_register', 'erp_tlm_new_admin_as_manager' );
add_action( 'erp_per_minute_scheduled_events', 'erp_tlm_customer_schedule_notification' );
add_action( 'wp_ajax_erp_tlm_track_email_opened', 'erp_tlm_track_email_opened' );
add_action( 'wp_ajax_nopriv_erp_tlm_track_email_opened', 'erp_tlm_track_email_opened' );
add_action( 'erp_tlm_dashboard_widgets_right', 'erp_tlm_dashboard_right_widgets_area' );
add_action( 'erp_tlm_dashboard_widgets_left', 'erp_tlm_dashboard_left_widgets_area' );
add_action( 'plugins_loaded', 'erp_tlm_course_forms' );
add_action( 'erp_settings_pages', 'erp_tlm_settings_pages_course_forms' );
add_action( 'erp_settings_pages', 'erp_tlm_settings_pages' );
add_action( 'erp_hr_permission_management', 'erp_tlm_permission_management_field' );
add_action( 'admin_footer-users.php', 'erp_tlm_user_bulk_actions' );
add_action( 'load-users.php', 'erp_tlm_handle_user_bulk_actions' );
add_action( 'admin_notices', 'erp_tlm_user_bulk_actions_notices' );
add_action( 'user_register', 'erp_tlm_create_course_from_created_user' );
add_action( 'erp_tlm_inbound_email_scheduled_events', 'erp_tlm_check_new_inbound_emails' );
add_action( 'erp_tlm_inbound_email_scheduled_events', 'erp_tlm_poll_gmail' );
add_action( 'updated_user_meta', 'erp_tlm_sync_people_meta_data', 10, 4 );
add_action( 'added_user_meta', 'erp_tlm_sync_people_meta_data', 10, 4 );
add_action( 'delete_user', 'erp_tlm_course_on_delete' );
add_action( 'erp_daily_scheduled_events', 'erp_tlm_send_birthday_greetings' );

// Register the taxonomies
add_action( 'init', 'erp_tlm_add_tag_taxonomy' );

// Filters *****************************************************************/
add_filter( 'erp_map_meta_caps', 'erp_tlm_map_meta_caps', 10, 4 );
add_filter( 'erp_get_people_pre_query', 'erp_tlm_course_advance_filter', 10, 2 );
add_filter( 'erp_get_people_pre_query', 'erp_tlm_is_people_belongs_to_saved_search', 10, 2 );
add_filter( 'woocommerce_prevent_admin_access', 'erp_tlm_wc_prevent_admin_access' );
add_filter( 'erp_login_redirect', 'erp_tlm_login_redirect', 10, 2);
add_filter( 'editable_roles', 'erp_tlm_filter_editable_roles' );
add_filter( 'tlm_vue_customer_script', 'tlm_vue_customer_script_dep' );
