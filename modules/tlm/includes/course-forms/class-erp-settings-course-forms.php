<?php
namespace WeDevs\ERP\TLM\CourseForms;

use WeDevs\ERP\Framework\ERP_Settings_Page;

/**
 * ERP Settings Course Form class
 */
class ERP_Settings_Course_Forms extends ERP_Settings_Page {

    use CourseForms;

    public $id = '';
    public $label = '';
    public $sections = [];

    protected $tlm_options = [];
    protected $active_plugin_list = [];
    protected $forms = [];

    /**
     * Class constructor
     */
    function __construct() {
        $this->tlm_options = $this->get_tlm_course_options();
        $this->active_plugin_list = $this->get_active_plugin_list();

        // option field type
        $this->action( 'erp_admin_field_match_form_fields', 'output_match_form_fields' );

        foreach ( $this->active_plugin_list as $slug => $plugin ) {
            $this->forms[ $slug ] = apply_filters( "tlm_get_{$slug}_forms", [] );
        }

        $this->admin_scripts();

        $this->id = 'course_forms';
        $this->label = __( 'Course Forms', 'erp' );
        $this->sections = $this->get_sections();
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
     * Include required CSS and JS
     *
     * @return void
     */
    public function admin_scripts() {
        $tlm_course_forms_settings = [
            'nonce'             => wp_create_nonce( 'erp_settings_course_forms' ),
            'plugins'           => array_keys( $this->active_plugin_list ),
            'forms'             => $this->forms,
            'mappedData'        => get_option( 'wperp_tlm_course_forms', '' ),
            'tlmOptions'        => $this->tlm_options,
            'scriptDebug'       => defined( 'SCRIPT_DEBUG' ) ? SCRIPT_DEBUG : false,
            'courseGroups'     => erp_tlm_get_course_groups_list(),
            'courseOwners'     => erp_tlm_get_tlm_user_dropdown(),
            'i18n' => [
                'notMapped'             => __( 'Not Set', 'erp' ),
                'labelOK'               => __( 'OK', 'erp' ),
                'labelCourseGroups'    => __( 'Course Group', 'erp' ),
                'labelSelectGroup'      => __( 'Select Course Group', 'erp' ),
                'labelCourseOwner'     => __( 'Course Owner', 'erp' ),
                'labelSelectOwner'      => __( 'Select Owner', 'erp' ),
            ],
        ];

        wp_enqueue_style( 'erp-sweetalert' );
        wp_enqueue_script( 'erp-sweetalert' );
        wp_enqueue_script( 'erp-vuejs' );
        wp_enqueue_script( 'erp-settings-course-forms', WPERP_TLM_ASSETS . '/js/erp-settings-course-forms.js', [ 'erp-vuejs', 'jquery', 'erp-sweetalert' ], WPERP_VERSION, true );
        wp_localize_script( 'erp-settings-course-forms', 'tlmCourseFormsSettings', $tlm_course_forms_settings );
    }

    /**
     * Get settings array
     *
     * This description will display when no supported plugin is active
     *
     * @return array
     */
    public function get_settings() {

        $fields = [

            [
                'title' => __( 'Course Forms Integration', 'erp' ),
                'type' => 'title',
                'desc' => sprintf(
                            '%s' . __( 'No supported course form plugin is currently active. WP ERP has built-in support for <strong>Course Form 7</strong> and <strong>Ninja Forms</strong>.', 'erp' ) . '%s',
                            '<section class="notice notice-warning cfi-hide-submit"><p>',
                            '</p></section>'
                        ),
                'id' => 'course_form_options'
            ],

            [ 'type' => 'sectionend', 'id' => 'course_form_options' ],

        ];

        return $fields;
    }

    /**
     * Get sections
     *
     * @return array
     */
    public function get_sections() {
        $sections = [];

        if ( !empty( $this->active_plugin_list ) ) {
            foreach ( $this->active_plugin_list as $slug => $plugin ) {
                $sections[ $slug ] = $plugin['title'];
            }
        }

        return $sections;
    }

    /**
     * Get sections fields
     *
     * @param string $section current settings tab section
     *
     * @return array
     */
    public function get_section_fields( $section = '' ) {
        $fields = [];

        foreach ( $this->active_plugin_list as $slug => $plugin ) {
            $forms = $this->forms[ $slug ];

            if ( empty( $forms ) ) {
                /* If no form created with respective plugin this notice will show.
                   Also if there is no function hook to the "tlm_get_{$slug}_forms",
                   filter we'll see this notice */
                $fields[ $slug ] = [
                    [
                        'title' => $plugin['title'],
                        'type' => 'title',
                        'desc' => sprintf(
                                    '%s' . __( "You don't have any form created with %s!", 'erp' ) . '%s',
                                    '<section class="notice notice-warning cfi-hide-submit"><p>',
                                    $plugin['title'],
                                    '</p></section>'
                                ),
                        'id' => 'section_' . $slug
                    ],

                    [ 'type' => 'sectionend', 'id' => 'script_styling_options' ],
                ];
            } else {
                foreach ( $forms as $form_id => $form ) {
                    $fields[ $slug ][] = [
                        'title' => $form['title'],
                        'type' => 'title',
                        'desc' => '',
                        'id' => 'section_' . $form['name']
                    ];

                    $fields[ $slug ][] = [
                        'plugin'        => $slug,
                        'form_id'       => $form_id,
                        'type'          => 'match_form_fields',
                    ];

                    $fields[ $slug ][] = [ 'type' => 'sectionend', 'id' => 'section_' . $form['name'] ];
                }
            }
        }

        $section = !$section ? array_shift( $fields ) : $fields[ $section ];

        return $section;
    }

    /**
     * Hook new type of option field
     *
     * @param array $value contains the field configs
     *
     * @return void
     */
    public function output_match_form_fields( $value ) {
    ?>
        <tr class="cfi-table-container cfi-hide-submit">
            <td style="padding-left: 0; padding-top: 0;">
                <table
                    class="wp-list-table widefat fixed striped cfi-table"
                    id="<?php echo esc_attr( $value['plugin'] ) . '_' . esc_attr( $value['form_id'] ); ?>"
                    data-plugin="<?php echo esc_attr( $value['plugin'] ); ?>"
                    data-form-id="<?php echo esc_attr( $value['form_id'] ); ?>"
                    v-cloak
                >
                    <tbody>
                        <tr>
                            <th class="cfi-table-wide-column"><?php esc_html_e( 'Form Field', 'erp' ); ?></th>
                            <th class="cfi-table-wide-column"><?php esc_html_e( 'TLM Course Option' ); ?></th>
                            <th class="cfi-table-narrow-column">&nbsp;</th>
                        </tr>
                    </tbody>

                    <tbody class="cfi-mapping-row {{ lastOfTypeClass($index) }}" v-for="(field, title) in formData.fields">
                        <tr>
                            <td>{{ title }}</td>
                            <td>{{ getTLMOptionTitle(field) }}</td>
                            <td>
                                <button
                                    type="button"
                                    class="button button-default"
                                    v-on:click="resetMapping(field)"
                                    :disabled="isMapped(field)"
                                >
                                    <i class="dashicons dashicons-no-alt"></i>
                                </button>
                                <button type="button" class="button button-default" v-on:click="setActiveDropDown(field)">
                                    <i class="dashicons dashicons-screenoptions"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="cfi-option-row" v-show="field === activeDropDown">
                            <td colspan="3" class="cfi-course-options">
                                <button
                                    type="button"
                                    v-for="(option, optionTitle) in tlmOptions"
                                    v-if="!optionIsAnObject(option)"
                                    v-on:click="mapOption(field, option)"
                                    :class="['button', isOptionMapped(field, option) ? 'button-primary active' : '']"
                                >{{ optionTitle }}</button>

                                <span v-for="(option, options) in tlmOptions" v-if="optionIsAnObject(option)">
                                    <button
                                        type="button"
                                        v-for="(childOption, childOptionTitle) in options.options"
                                        v-if="optionIsAnObject(option)"
                                        v-on:click="mapChildOption(field, option, childOption)"
                                        :class="['button', isChildOptionMapped(field, option, childOption) ? 'button-primary active' : '']"
                                    >{{ options.title + ' - ' + childOptionTitle }}</button>
                                </span>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">
                                <label>
                                    {{ i18n.labelCourseGroups }} <span>&nbsp;&nbsp;&nbsp;</span>
                                    <select class="cfi-course-group" v-model="formData.courseGroup">
                                        <option value="0">{{ i18n.labelSelectGroup }}</option>
                                        <option v-for="(groupId, groupName) in courseGroups" value="{{ groupId }}">{{ groupName }}</option>
                                    </select>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <label>
                                    {{ i18n.labelCourseOwner }} <span class="required">*</span>
                                    <select class="cfi-course-group" v-model="formData.courseOwner">
                                        <option value="0">{{ i18n.labelSelectOwner }}</option>
                                        <option v-for="(userId, user) in courseOwners" value="{{ userId }}">{{ user }}</option>
                                    </select>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <button
                                    type="button"
                                    class="button"
                                    v-on:click="reset_mapping"
                                ><?php esc_html_e( 'Reset', 'erp' ); ?></button>
                                <button
                                    type="button"
                                    class="button button-primary"
                                    v-on:click="save_mapping"
                                ><?php esc_html_e( 'Save Changes', 'erp' ); ?></button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </td>
            <td></td>
        </tr>
    <?php
    }

    /**
     * Ajax hook function to save the ERP Settings
     *
     * @return void prints json object
     */
    public function save_erp_settings() {
        $response = [
            'success' => false,
            'msg' => null
        ];

        if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['_wpnonce'] ), 'erp_settings_course_forms' ) ) {
            $this->send_error( __( 'Error: Nonce verification failed', 'erp' ) );
        }

        if ( !erp_tlm_is_current_user_manager() ) {
            $response['msg'] = __( 'Unauthorized operation', 'erp' );

        } if ( !empty( $_POST['plugin'] ) && !empty( $_POST['formId'] ) && !empty( $_POST['map'] ) ) {

            $required_options = $this->get_required_tlm_course_options();

            // if map contains full_name, then remove first and last names from required options
            if ( in_array( 'full_name' , $_POST['map'] ) ) {
                $index = array_search( 'first_name' , $required_options );
                unset( $required_options[ $index ] );

                $index = array_search( 'last_name' , $required_options );
                unset( $required_options[ $index ] );

                array_unshift( $required_options, 'full_name' );
            }

            $diff = array_diff( $required_options, array_map( 'sanitize_text_field', wp_unslash( $_POST['map'] ) ) );

            if ( !empty( $diff ) ) {
                $required_options = array_map( function ( $option ) {
                    return ucwords( str_replace( '_' , ' ', $option ) );
                }, $required_options );

                $response['msg'] = sprintf(
                    __( '%s fields are required', 'erp' ),
                    implode( ', ' , $required_options )
                );

            } else if ( empty( $_POST['courseOwner'] ) && absint( $_POST['courseOwner'] ) ) {
                $response['msg'] = __( 'Please set a course owner.', 'erp' );

            } else {
                $settings = get_option( 'wperp_tlm_course_forms' );

                $settings[ sanitize_text_field( wp_unslash( $_POST['plugin'] ) ) ][ sanitize_text_field( wp_unslash( $_POST['formId'] ) ) ] = [
                    'map' => array_map( 'sanitize_text_field', wp_unslash( $_POST['map'] ) ),
                    'course_group' => isset( $_POST['courseGroup'] ) ? sanitize_text_field( wp_unslash( $_POST['courseGroup'] ) ) : '',
                    'course_owner' => isset( $_POST['courseOwner'] ) ? sanitize_text_field( wp_unslash( $_POST['courseOwner'] ) ) : ''
                ];

                update_option( 'wperp_tlm_course_forms', $settings );

                $response = [
                    'success' => true,
                    'msg' => __( 'Settings saved successfully', 'erp' )
                ];
            }


        } else if ( empty( $_POST['forms'] ) ) {
            $response['msg'] = __( 'No settings data found', 'erp' );
        }

        wp_send_json( $response );
    }

    /**
     * Ajax hook function to reset ERP Settings for a form
     *
     * @return void prints json object
     */
    public function reset_erp_settings() {
        $response = [
            'success' => false,
            'msg' => null
        ];

        if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['_wpnonce'] ), 'erp_settings_course_forms' ) ) {
            $this->send_error( __( 'Error: Nonce verification failed', 'erp' ) );
        }

        if ( !erp_tlm_is_current_user_manager() ) {
            $response['msg'] = __( 'Unauthorized operation', 'erp' );

        } else if ( !empty( $_POST['plugin'] ) && !empty( $_POST['formId'] ) ) {
            $settings = get_option( 'wperp_tlm_course_forms' );

            if ( !empty( $settings[ $_POST['plugin'] ][ $_POST['formId'] ] ) ) {
                $map = $settings[ sanitize_text_field( wp_unslash( $_POST['plugin'] ) ) ][ sanitize_text_field( wp_unslash( $_POST['formId'] ) ) ]['map'];

                unset( $settings[ $_POST['plugin'] ][ $_POST['formId'] ] );

                update_option( 'wperp_tlm_course_forms', $settings );

                // map the $map array to null values
                $map = array_map( function () {
                    return null;
                }, $map);

                $response = [
                    'success' => true,
                    'msg' => __( 'Settings reset successfully', 'erp' ),
                    'map' => $map,
                    'courseGroup' => 0,
                    'courseOwner' => 0,
                ];

            } else {
                $response['msg'] = __( 'Nothing to reset', 'erp' );
            }
        }

        wp_send_json( $response );
    }

}
