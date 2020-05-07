<?php
namespace WeDevs\ERP\TLM\CourseForms;

use WeDevs\ERP\Framework\Traits\Hooker;

class CF7 {

    use Hooker;

    public function __construct() {
        $this->filter( 'erp_course_forms_plugin_list', 'add_to_plugin_list' );
        $this->action( 'tlm_get_course_form_7_forms', 'get_forms' );
        $this->action( 'wpcf7_submit', 'after_form_submit' );
    }

    /**
     * Add Course Form 7 to the integration plugin list
     *
     * @param array
     *
     * @return array
     */
    public function add_to_plugin_list( $plugins ) {
        $plugins['course_form_7'] = [
            'title' => __( 'Course Form 7', 'erp' ),
            'is_active' => class_exists( 'WPCF7_CourseForm' )
        ];

        return $plugins;
    }

    /**
     * Get all Course Form 7 forms and their fields
     *
     * @return array
     */
    public function get_forms() {
        $forms = [];

        $args = [
            'post_type' => 'wpcf7_course_form',
            'posts_per_page' => -1,
        ];

        $cf7_query = new \WP_Query( $args );

        if ( !$cf7_query->have_posts() ) {
            return $forms;

        } else {
            while ( $cf7_query->have_posts() ) {
                $cf7_query->the_post();
                global $post;


                $cf7 = \WPCF7_CourseForm::get_instance( $post->ID );

                $saved_settings = get_option( 'wperp_tlm_course_forms', '' );

                $forms[ $post->ID ] = [
                    'name' => $post->post_name,
                    'title' => $post->post_title,
                    'fields' => [],
                    'courseGroup' => '0',
                    'courseOwner' => '0'
                ];

                foreach ( $cf7->collect_mail_tags() as $tag ) {
                    $forms[ $post->ID ]['fields'][ $tag ] = '[' . $tag . ']';

                    if ( !empty( $saved_settings['course_form_7'][ $post->ID ]['map'][ $tag ] ) ) {
                        $tlm_option = $saved_settings['course_form_7'][ $post->ID ]['map'][ $tag ];
                    } else {
                        $tlm_option = '';
                    }

                    $forms[ $post->ID ]['map'][ $tag ] = !empty( $tlm_option ) ? $tlm_option : '';
                }

                if ( !empty( $saved_settings['course_form_7'][ $post->ID ]['course_group'] ) ) {
                    $forms[ $post->ID ]['courseGroup'] = $saved_settings['course_form_7'][ $post->ID ]['course_group'];
                }

                if ( !empty( $saved_settings['course_form_7'][ $post->ID ]['course_owner'] ) ) {
                    $forms[ $post->ID ]['courseOwner'] = $saved_settings['course_form_7'][ $post->ID ]['course_owner'];
                }
            }
        }

        return $forms;
    }

    /**
     * After Course Form 7 submission hook
     *
     * @return void
     */
    public function after_form_submit() {
        if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_wpnonce'] ), 'erp-nonce' ) ) {
            // die();
        }

        if ( ! isset( $_POST['_wpcf7'] ) ) {
            return;
        }

        // first check if submitted form has settings or not
        $cfi_settings = get_option( 'wperp_tlm_course_forms', '' );

        // if we don't have any setting, then do not proceed
        if ( empty( $cfi_settings['course_form_7'] ) ) {
            return;
        }

        $cf7_settings = $cfi_settings['course_form_7'];

        if ( in_array( sanitize_text_field( wp_unslash( $_POST['_wpcf7'] ) ) , array_keys( $cf7_settings ) ) ) {
            do_action( "wperp_integration_course_form_7_form_submit", array_map( 'sanitize_text_field', wp_unslash( $_POST ) ), 'course_form_7', sanitize_text_field( wp_unslash( $_POST['_wpcf7'] ) ) );
        }
    }

}
