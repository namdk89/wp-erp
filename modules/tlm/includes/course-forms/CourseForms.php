<?php
namespace WeDevs\ERP\TLM\CourseForms;

use WeDevs\ERP\Framework\Traits\Ajax;
use WeDevs\ERP\Framework\Traits\Hooker;
use WeDevs\ERP\Framework\ERP_Settings_Page;
use WeDevs\ERP\TLM\Course;

trait CourseForms {

    use Ajax;
    use Hooker;

    /**
     * Integrated plugin list
     *
     * Array keys are treated as slugs, may not same as respective plugin id.
     * Hyphen is not acceptable in slug names.
     *
     * @return array
     */
    public function get_plugin_list() {
        return apply_filters( 'erp_course_forms_plugin_list', [] );
    }

    /**
     * Get the plugins which are currently installed and active
     *
     * @return return
     */
    public function get_active_plugin_list() {
        return array_filter( $this->get_plugin_list(), function ( $plugin ) {
            return !empty( $plugin['is_active'] );
        } );
    }

    /**
     * The required TLM Course options
     *
     * @return array
     */
    public function get_required_tlm_course_options() {
        return apply_filters( 'erp_course_forms_required_options', [
            'first_name', 'last_name', 'email'
        ] );
    }

    /**
     * Available TLM course options/fields
     *
     * @return array
     */
    public function get_tlm_course_options() {
        $options = [];
        $course = new Course( null, 'course' );
        $tlm_options = $course->to_array();

        $ignore_options = apply_filters( 'erp_course_forms_ignore_options', [
            'id', 'user_id', 'avatar', 'life_stage', 'type', 'source'
        ] );

        // add full_name as the tlm course option
        $tlm_options = array_merge( [ 'full_name' => '' ], $tlm_options );

        foreach ( $tlm_options as $option => $option_val ) {

            if ( !in_array( $option, $ignore_options ) ) {

                if ( empty( $option_val ) ) {
                    $options[ $option ] = ucwords( str_replace( '_', ' ', $option ) );
                } else {
                    $options[ $option ] = [
                        'title' => ucwords( str_replace( '_', ' ', $option ) ),
                        'options' => []
                    ];

                    foreach ( $option_val as $child_option => $child_option_val ) {
                        $options[ $option ]['options'][ $child_option ] = ucwords( str_replace( '_', ' ', $child_option ) );
                    }
                }

            }

        }

        return $options;
    }

}
