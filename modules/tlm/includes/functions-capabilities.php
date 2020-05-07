<?php

/**
 * The manager role for TLM user
 *
 * @since 1.0
 *
 * @return string
 */
function erp_tlm_get_manager_role() {
    return apply_filters( 'erp_tlm_get_manager_role', 'erp_tlm_manager' );
}

/**
 * The Tlm Agent role for TLM user
 *
 * @since 1.0
 *
 * @return string
 */
function erp_tlm_get_agent_role() {
    return apply_filters( 'erp_tlm_get_agent_role', 'erp_tlm_agent' );
}

/**
 * The Tlm teacher role for TLM user
 *
 * @since 1.0
 *
 * @return string
 */
function erp_tlm_get_teacher_role() {
    return apply_filters( 'erp_tlm_get_teacher_role', 'erp_tlm_teacher' );
}

/**
 * When a new administrator is created,
 * make him TLM Manager by default
 *
 * @since 1.0
 *
 * @param  int  $user_id
 *
 * @return void
 */
function erp_tlm_new_admin_as_manager( $user_id ) {
    $user = get_user_by( 'id', $user_id );

    if ( $user && in_array('administrator', $user->roles) ) {
        $user->add_role( erp_tlm_get_manager_role() );
    }
}

/**
 * Return a user's TLM roles
 *
 * @since 1.0
 *
 * @param int $user_id
 *
 * @return string
 */
function erp_tlm_get_user_role( $user_id = 0 ) {

    // if user_id is not set or 0, then the user is current user
    if ( ! $user_id ) {
        global $current_user;
        $user = $current_user;
    } else {
        $user = get_userdata( $user_id );
    }

    $role = false;

    // User has roles so look for a HR one
    if ( ! empty( $user->roles ) ) {

        // Look for a ac role
        $roles = array_intersect(
            array_values( $user->roles ),
            array_keys( erp_tlm_get_roles() )
        );

        if ( !empty( $roles ) ) {
            $role = array_shift( $roles );
        }
    }

    return apply_filters( 'erp_tlm_get_user_role', $role, $user_id, $user );
}

/**
 * Get dynamic roles for TLM
 *
 * @since 1.0
 *
 * @return array
 */
function erp_tlm_get_roles() {
    $roles = [
        erp_tlm_get_manager_role() => [
            'name'         => __( 'TLM Manager', 'erp' ),
            'public'       => false,
            'capabilities' => erp_tlm_get_caps_for_role( erp_tlm_get_manager_role() )
        ],

        erp_tlm_get_agent_role() => [
            'name'         => __( 'TLM Agent', 'erp' ),
            'public'       => false,
            'capabilities' => erp_tlm_get_caps_for_role( erp_tlm_get_agent_role() )
        ],

        erp_tlm_get_teacher_role() => [
            'name'         => __( 'TLM Teacher', 'erp' ),
            'public'       => false,
            'capabilities' => erp_tlm_get_caps_for_role( erp_tlm_get_teacher_role() )
        ],
    ];

    return apply_filters( 'erp_tlm_get_roles', $roles );
}

/**
 * Get caps for individual Roles
 *
 * @since 1.0
 *
 * @param  string $role
 *
 * @return array
 */
function erp_tlm_get_caps_for_role( $role = '' ) {
	$caps = [];

    // Which role are we looking for?
    switch ( $role ) {

        case erp_tlm_get_manager_role():
            $caps = [
                'read'                     => true,
                'upload_files'             => true,
                'erp_tlm_list_course'     => true,
                'erp_tlm_add_course'      => true,
                'erp_tlm_edit_course'     => true,
                'erp_tlm_delete_course'   => true,
                'erp_tlm_manage_activites' => true,
                'erp_tlm_manage_dashboard' => true,
                'erp_tlm_manage_schedules' => true,
                'erp_tlm_manage_groups'    => true,
                'erp_tlm_create_groups'    => true,
                'erp_tlm_edit_groups'      => true,
                'erp_tlm_delete_groups'    => true,

                // 'erp_tlm_view_reports'     => true,
            ];

            break;

        case erp_tlm_get_agent_role():
            $caps = [
                'read'                     => true,
                'upload_files'             => true,
                'erp_tlm_list_course'     => true,
                'erp_tlm_add_course'      => true,
                'erp_tlm_edit_course'     => true,
                'erp_tlm_delete_course'   => true,
                'erp_tlm_manage_activites' => true,
                'erp_tlm_manage_dashboard' => true,
                'erp_tlm_manage_schedules' => true,
                'erp_tlm_manage_groups'    => true,
            ];
            break;

        case erp_tlm_get_teacher_role():
            $caps = [
                'read'                     => true,
                'upload_files'             => true,
                'erp_tlm_list_course'     => true,
            ];
            break;
    }

    return apply_filters( 'erp_tlm_get_caps_for_role', $caps, $role );
}

/**
 * Check is current user is manager
 *
 * @since 1.0
 *
 * @return boolean
 */
function erp_tlm_is_current_user_manager() {
    $current_user_role = erp_tlm_get_user_role( get_current_user_id() );

    if ( erp_tlm_get_manager_role() !=  $current_user_role ) {
        return false;
    }

    return true;
}

/**
 * Check is current user is TLM Agent
 *
 * @since 1.0
 *
 * @return boolean
 */
function erp_tlm_is_current_user_tlm_agent() {
    $current_user_role = erp_tlm_get_user_role( get_current_user_id() );

    if ( erp_tlm_get_agent_role() !=  $current_user_role ) {
        return false;
    }

    return true;
}

/**
 * Check is current user is TLM Teacher
 *
 * @since 1.0
 *
 * @return boolean
 */
function erp_tlm_is_current_user_tlm_teacher() {
    $current_user_role = erp_tlm_get_user_role( get_current_user_id() );

    if ( erp_tlm_get_teacher_role() !=  $current_user_role ) {
        return false;
    }

    return true;
}

/**
 * Check tlm permission for users
 *
 * @since 1.0
 *
 * @param  object $employee
 *
 * @return void
 */
function erp_tlm_permission_management_field( $employee ) {

    if ( ! erp_tlm_is_current_user_manager() ) {
        return;
    }

    $is_manager = user_can( $employee->id, erp_tlm_get_manager_role() ) ? 'on' : 'off';
    $is_agent   = user_can( $employee->id, erp_tlm_get_agent_role() ) ? 'on' : 'off';
    $is_teacher   = user_can( $employee->id, erp_tlm_get_teacher_role() ) ? 'on' : 'off';

    erp_html_form_input( array(
        'label' => __( 'TLM Manager', 'erp' ),
        'name'  => 'tlm_manager',
        'type'  => 'checkbox',
        'tag'   => 'div',
        'value' => $is_manager,
        'help'  => __( 'This Employee is TLM Manager', 'erp'  )
    ) );

    erp_html_form_input( array(
        'label' => __( 'TLM Agent', 'erp' ),
        'name'  => 'tlm_agent',
        'type'  => 'checkbox',
        'tag'   => 'div',
        'value' => $is_agent,
        'help'  => __( 'This Employee is TLM agent', 'erp'  )
    ) );

    erp_html_form_input( array(
        'label' => __( 'TLM Teacher', 'erp' ),
        'name'  => 'tlm_teacher',
        'type'  => 'checkbox',
        'tag'   => 'div',
        'value' => $is_teacher,
        'help'  => __( 'This Employee is TLM teacher', 'erp'  )
    ) );
}

/**
 * Dynamically Map TLM capabilities
 *
 * @since 1.0
 *
 * @param  array   $caps
 * @param  string  $cap
 * @param  integer $user_id
 * @param  array   $args
 *
 * @return array
 */
function erp_tlm_map_meta_caps( $caps = array(), $cap = '', $user_id = 0, $args = array() ) {
    switch ( $cap ) {

        /**
         * TLM Manager -> can soft+hard delete own and others courses
         * TLM Manager && TLM Agent -> can soft+hard delete own and others courses
         * TLM Agent -> can only soft delete own courses
         * None -> cannot delete
         */
        case 'erp_tlm_edit_course':
        case 'erp_tlm_delete_course':
            $course_id      = isset( $args[0] ) ? $args[0] : false;
            $data_hard       = isset( $args[1] ) ? $args[1] : false;

            $tlm_manager_role = erp_tlm_get_manager_role();
            $tlm_agent_role   = erp_tlm_get_agent_role();

            if ( ! user_can( $user_id, $tlm_manager_role ) && user_can( $user_id, $tlm_agent_role ) ) {
                $course_user_id = \WeDevs\ERP\Framework\Models\People::select('user_id')->where( 'id', $course_id )->first();

                if ( isset( $course_user_id->user_id ) && $course_user_id->user_id ) {
                    $assign_id = get_user_meta( $course_user_id->user_id, 'course_owner', true );
                } else {
                    $assign_id = erp_people_get_meta( $course_id, 'course_owner', true );
                }

                if ( $assign_id != $user_id ) {
                    $caps = ['do_not_allow'];
                } else {
                    if ( $data_hard ) {
                        $caps = ['do_not_allow'];
                    }
                }

            } else if ( ! user_can( $user_id, $tlm_manager_role ) ) {
                $caps = ['do_not_allow'];
            }

        break;
    }

    return apply_filters( 'erp_tlm_map_meta_caps', $caps, $cap, $user_id, $args );

}

/**
 * Check permission to make WordPress User
 *
 * @since 1.1.18
 *
 * @return boolean
 */
function erp_tlm_current_user_can_make_wp_user() {
    $has_permission = false;

    if ( current_user_can( 'administrator' ) || erp_tlm_is_current_user_manager() ) {
        $has_permission = true;

    } else if ( erp_tlm_is_current_user_tlm_agent() && apply_filters( 'erp_tlm_agent_can_make_wp_user', true ) ) {
        $has_permission = true;
    }

    return $has_permission;
}


/**
 * Removes the non-public TLM roles from the editable roles array
 *
 * @param array $all_roles All registered roles
 *
 * @return array
 */
function erp_tlm_filter_editable_roles( $all_roles = [] ) {
    $roles = erp_tlm_get_roles();

    foreach ( $roles as $tlm_role_key => $tlm_role ) {

        if ( isset( $tlm_role['public'] ) && $tlm_role['public'] === false ) {

            // Loop through WordPress roles
            foreach ( array_keys( $all_roles ) as $wp_role ) {

                // If keys match, unset
                if ( $wp_role === $tlm_role_key ) {
                    unset( $all_roles[ $wp_role ] );
                }
            }
        }

    }

    return $all_roles;
}
