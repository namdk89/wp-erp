<?php
namespace WeDevs\ERP\TLM;

/**
* Form request data handler class
*
* @since 1.0
*
* @package WP-ERP\TLM
*/
class Form_Handler {

    /**
     * Hook all actions
     *
     * @since 1.0
     *
     * @return void
     */
    public function __construct() {
        add_action( 'admin_head', [ $this, 'handle_canonical_url' ], 10 );
        add_action( 'erp_hr_after_employee_permission_set', [ $this, 'tlm_permission_set' ], 10, 2 );

        $tlm = sanitize_title( esc_html__( 'TLM', 'erp' ) );
        add_action( "admin_init", [ $this, 'course_groups_bulk_action' ] );
    }

    /**
     * TLM Permission set
     *
     * @since 1.0.1
     *
     * @param  array $post
     * @param  object $user
     *
     * @return void
     */
    public static function tlm_permission_set( $post, $user ) {
        $enable_tlm_manager = isset( $post['tlm_manager'] ) ? filter_var( $post['tlm_manager'], FILTER_VALIDATE_BOOLEAN ) : false;
        $enable_tlm_agent   = isset( $post['tlm_agent'] ) ? filter_var( $post['tlm_agent'], FILTER_VALIDATE_BOOLEAN ) : false;
        $enable_tlm_teacher = isset( $post['tlm_teacher'] ) ? filter_var( $post['tlm_teacher'], FILTER_VALIDATE_BOOLEAN ) : false;

        $tlm_manager_role = erp_tlm_get_manager_role();
        $tlm_agent_role = erp_tlm_get_agent_role();
        $tlm_teacher_role = erp_tlm_get_teacher_role();

        // TODO::We are duplicating \WeDevs\ERP\TLM\User_Profile->update_user() process here,
        // which we shouldn't. We should update above method and use that.
        if ( current_user_can( $tlm_manager_role ) ) {
            if ( $enable_tlm_manager ) {
                $user->add_role( $tlm_manager_role );
            } else {
                $user->remove_role( $tlm_manager_role );
            }

            if ( $enable_tlm_agent ) {
                $user->add_role( $tlm_agent_role );
            } else {
                $user->remove_role( $tlm_agent_role );
            }

            if ( $enable_tlm_teacher ) {
                $user->add_role( $tlm_teacher_role );
            } else {
                $user->remove_role( $tlm_teacher_role );
            }
        }
    }

    /**
     * Handle canonical url for course|company page
     *
     * @since 1.1.0
     *
     * @return void
     */
    public function handle_canonical_url() {
        if ( erp_is_courses_page() ) {
            ?>
                <script>
                    window.history.replaceState = false;
                </script>
            <?php
        }
    }

    /**
     * Handle course subscriber bulk actions
     *
     * @since 1.0
     *
     * @return void
     */
    public function course_groups_bulk_action() {

        if ( current_user_can( 'erp_tlm_agent' ) ) {
            return;
        }

        if ( ! isset( $_REQUEST['_wpnonce'] ) || ! isset( $_GET['page'] ) ) {
            return;
        }

        if ( empty( $_GET['section'] ) ||  $_GET['section'] != 'course-groups' ) {
            return;
        }

        if ( isset( $_GET['groupaction'] ) && $_GET['groupaction'] == 'view-subscriber' ) {
            if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'bulk-coursesubscribers' ) ) {
                return;
            }
        } else {
            if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'bulk-coursegroups' ) ) {
                return;
            }
        }


        $customer_table = new \WeDevs\ERP\TLM\Course_Subscriber_List_Table();
        $action         = $customer_table->current_action();

        if ( $action ) {

            $redirect_uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
            $redirect = remove_query_arg( array( '_wp_http_referer', '_wpnonce', 'filter_group' ),  $redirect_uri );

            switch ( $action ) {

                case 'filter_group':
                    wp_redirect( $redirect );
                    exit();

                case 'course_group_delete':
                    if ( isset( $_GET['course_group'] ) && !empty( $_GET['course_group'] ) ) {
                        $groups = array_map( 'sanitize_text_field', wp_unslash( $_GET['course_group'] ) );
                        erp_tlm_course_group_delete( $groups );
                    }
                    wp_redirect( $redirect );
                    exit();

                case 'delete':

                    if ( isset( $_GET['suscriber_course_id'] ) && !empty( $_GET['filter_course_group'] ) ) {
                        erp_tlm_course_subscriber_delete( sanitize_text_field( wp_unslash( $_GET['suscriber_course_id'] ) ), sanitize_text_field( wp_unslash( $_GET['filter_course_group'] ) ) );
                    }

                    wp_redirect( $redirect );
                    exit();

                default:
                    wp_redirect( $redirect );
                    exit();
            }
        }
    }
}
