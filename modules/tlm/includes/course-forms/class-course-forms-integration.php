<?php
namespace WeDevs\ERP\TLM\CourseForms;

/**
 * TLM Course Forms class
 */
class Course_Forms_Integration {

    use CourseForms;


    /**
     * The class constructor
     */
    public function __construct() {
        // ajax hooks
        $this->action( 'wp_ajax_erp_settings_save_course_form', 'save_erp_settings' );
        $this->action( 'wp_ajax_erp_settings_reset_course_form', 'reset_erp_settings' );

        // Hook save_submitted_form_data function to
        // the respective plugin functions
        $this->add_form_submission_actions();
    }

    /**
     * Initializes the class
     *
     * Checks for an existing instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Save admin settings via ajax
     *
     * @return void
     */
    public function save_erp_settings() {
        ERP_Settings_Course_Forms::init()->save_erp_settings();
    }

    /**
     * Reset admin settings via ajax
     *
     * @return void
     */
    public function reset_erp_settings() {
        ERP_Settings_Course_Forms::init()->reset_erp_settings();
    }

    /**
     * Hook save_submitted_form_data function
     *
     * For a particular plugin, we'll need a function that provide submitted
     * data to the save_submitted_form_data function. In order to do this,
     * we'll need to supply the data in a do_action call like
     * do_action( "wperp_integration_{$slug}_form_submit", $submitted_data, $plugin_slug, $form_id )
     *
     * @return void
     */
    protected function add_form_submission_actions() {
        foreach ( $this->get_active_plugin_list() as $slug => $plugin ) {
            $this->action( "wperp_integration_{$slug}_form_submit", 'save_submitted_form_data', 10, 3 );
        }
    }

    /**
     * Save form sumitted data as new TLM Course
     *
     * @param array $data submitted form data
     * @param string $plugin plugin slug defined in get_plugin_list function
     * @param string $form_id submitted form id
     *
     * @return void
     */
    public function save_submitted_form_data( $data, $plugin, $form_id ) {
        $cfi_settings = get_option( 'wperp_tlm_course_forms', '' );

        if ( ! empty( $cfi_settings[ $plugin ][ $form_id ] ) ) {
            $settings       =  $cfi_settings[ $plugin ][ $form_id ]['map'];
            $course_owner  =  $cfi_settings[ $plugin ][ $form_id ]['course_owner'];
            $course_group  =  $cfi_settings[ $plugin ][ $form_id ]['course_group'];

            $course = [
                'type' => 'course'
            ];

            foreach ( $settings as $field => $option ) {
                if ( !empty( $option ) ) {
                    if ( 'full_name' === $option ) {
                        $name_arr = explode( ' ', $data[ $field ] );

                        if ( count( $name_arr ) > 1 ) {
                            $course[ 'last_name' ] = array_pop( $name_arr );
                            $course[ 'first_name' ] = implode( ' ' , $name_arr );
                        }
                    } else {
                        // check for nested options like social.facebook
                        $is_nested = preg_match_all( '/(.*)\.(.*)/', $option, $match );

                        if ( $is_nested ) {
                            $course[ $match[1][0] ][ $match[2][0] ] = $data[ $field ];
                        } else {
                            $course[ $option ] = $data[ $field ];
                        }

                    }
                }
            }

            if ( ! empty( $course_group ) ) {
                $course['course_group'] = $course_group;
            }

            if ( ! empty( $course_owner ) ) {
                $course['course_owner'] = $course_owner;
            }

            $people_id = erp_insert_people( $course );

            if ( ! is_wp_error( $people_id ) ) {
                $customer = new \WeDevs\ERP\TLM\Course( absint( $people_id ), 'course' );

                $customer->update_life_stage('lead');
                $customer->update_meta( 'source', 'course_form' );

                if ( ! empty( $cfi_settings[ $plugin ][ $form_id ]['course_owner'] ) ) {
                    $customer->update_meta( 'course_owner', $cfi_settings[ $plugin ][ $form_id ]['course_owner'] );
                }

                if ( ! empty( $cfi_settings[ $plugin ][ $form_id ]['course_group'] ) ) {
                    $groups = array( $cfi_settings[ $plugin ][ $form_id ]['course_group'] );
                    erp_tlm_edit_course_subscriber( $groups, $people_id );
                }

                // update meta data
                $people = new \WeDevs\ERP\Framework\Models\People();
                $people_columns = $people->getFillable();
                $people_columns = array_merge( $people_columns, [ 'type', 'id' ] );

                foreach ( $course as $option => $value ) {
                    if ( ! in_array( $option, $people_columns ) ) {

                        if ( is_array( $value ) ) {
                            foreach ( $value as $child_option => $child_value ) {
                                $customer->update_meta( $child_option, $child_value );
                            }

                        } else {
                            $customer->update_meta( $option, $value );
                        }

                    }
                }

                do_action( 'erp_save_course_form_data', $customer, $data, $plugin, $form_id );
            }

        }
    }

}
