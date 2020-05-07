<?php
namespace WeDevs\ERP\TLM;

/**
 * Loads TLM users admin area
 *
 * @since 1.0
 *
 * @package WP-ERP\TLM
 * @subpackage Administration
 */
class User_Profile {

    /**
     * The TLM users admin loader
     *
     * @package WP-ERP\TLM
     * @subpackage Administration
     */
    public function __construct() {
        $this->setup_actions();
    }

    /**
     * Setup the admin hooks, actions and filters
     *
     * @since 1.0
     *
     * @return void
     */
    function setup_actions() {

        // Bail if in network admin
        if ( is_network_admin() ) {
            return;
        }

        add_action( 'erp_user_profile_role', array( $this, 'role' ) );
        add_action( 'erp_update_user', array( $this, 'update_user' ), 10, 2 );
    }

    /**
     * Update user role from user profile
     *
     * @since 1.0
     *
     * @param  integer $user_id
     * @param  object $post
     *
     * @return void
     */
    function update_user( $user_id, $post ) {

        $new_tlm_manager_role = isset( $post['tlm_manager'] ) ? sanitize_text_field( $post['tlm_manager'] ) : false;
        $new_tlm_agent_role   = isset( $post['tlm_agent'] ) ? sanitize_text_field( $post['tlm_agent'] ) : false;
        $new_tlm_teacher_role = isset( $post['tlm_teacher'] ) ? sanitize_text_field( $post['tlm_teacher'] ) : false;

        if ( ! $new_tlm_manager_role && ! $new_tlm_agent_role && ! $new_tlm_teacher_role ) {
            return;
        }

        // Bail if current user cannot promote the passing user
        if ( ! current_user_can( 'promote_user', $user_id ) ) {
            return;
        }

        $user = get_user_by( 'id', $user_id );

        if ( $new_tlm_manager_role ) {
            $user->add_role( $new_tlm_manager_role );
        } else {
            $user->remove_role( erp_tlm_get_manager_role() );
        }

        if ( $new_tlm_agent_role ) {
            $user->add_role( $new_tlm_agent_role );
        } else {
            $user->remove_role( erp_tlm_get_agent_role() );
        }

        if ( $new_tlm_teacher_role ) {
            $user->add_role( $new_tlm_teacher_role );
        } else {
            $user->remove_role( erp_tlm_get_teacher_role() );
        }
    }

    /**
     * Show roles fields
     *
     * @since 1.0
     *
     * @param  object $profileuser
     *
     * @return html|void
     */
    function role( $profileuser ) {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $is_manager = in_array( erp_tlm_get_manager_role(), $profileuser->roles ) ? 'checked' : '';
        $is_agent   = in_array( erp_tlm_get_agent_role(), $profileuser->roles ) ? 'checked' : '';
        $is_teacher = in_array( erp_tlm_get_teacher_role(), $profileuser->roles ) ? 'checked' : '';
        ?>
        <label for="erp-tlm-manager">
            <input type="checkbox" id="erp-tlm-manager" <?php echo esc_attr( $is_manager ); ?> name="tlm_manager" value="<?php echo esc_attr( erp_tlm_get_manager_role() ); ?>">
            <span class="description"><?php esc_attr_e( 'TLM Manager', 'erp' ); ?></span>
        </label>

        <label for="erp-tlm-agent">
            <input type="checkbox" id="erp-tlm-agent" <?php echo esc_html( $is_agent ); ?> name="tlm_agent" value="<?php echo esc_attr( erp_tlm_get_agent_role() ); ?>">
            <span class="description"><?php esc_attr_e( 'TLM Agent', 'erp' ); ?></span>
        </label>

        <label for="erp-tlm-teacher">
            <input type="checkbox" id="erp-tlm-teacher" <?php echo esc_html( $is_teacher ); ?> name="tlm_teacher" value="<?php echo esc_attr( erp_tlm_get_teacher_role() ); ?>">
            <span class="description"><?php esc_attr_e( 'TLM Teacher', 'erp' ); ?></span>
        </label>
        <?php
    }
}
