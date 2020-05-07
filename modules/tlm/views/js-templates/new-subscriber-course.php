<div class="erp-tlm-course-subscriber-wrap">

    <# if ( ! data.group_id ) { #>
        <div class="row" data-selected = "'{{ data.user_id }}'">
            <label for="erp-select-customer-company"><?php esc_attr_e( 'Course/Company', 'erp' ); ?> <span class="required">*</span></label>
            <select style="width:240px;" name="user_id" id="erp-tlm-course-subscriber-user" required="required" data-types="course,company" class="erp-tlm-course-list-dropdown" data-placeholder="<?php esc_attr_e( 'Select a Course or company', 'erp' )?>">
                <option value=""><?php esc_attr_e( 'Select a course or company', 'erp' ); ?></option>
            </select>
        </div>
    <# } #>

    <?php $course_groups = erp_tlm_get_course_group_dropdown(); ?>

    <?php if( count( $course_groups ) > 0 ) : ?>
        <div class="row" id="erp-tlm-course-subscriber-group-checkbox" data-checked = "{{ data.group_id }}">
            <?php erp_html_form_input( array(
                'label'       => __( 'Assign Group', 'erp' ),
                'name'        => 'group_id[]',
                'type'        => 'multicheckbox',
                'id'          => 'erp-tlm-course-group-id',
                'class'       => 'erp-tlm-course-group-class',
                'options'     => $course_groups
            ) ); ?>
        </div>
    <?php else : ?>
        <p><?php echo wp_kses_post( sprintf( '%s <a href="%s">%s</a>', __( 'No group founds. Please add group first', 'erp' ), add_query_arg( [ 'page' => 'erp-tlm', 'section' => 'course-groups' ], admin_url( 'admin.php' ) ), __( 'Add New Group', 'erp' ) ) ); ?></p>
    <?php endif; ?>

    <?php wp_nonce_field( 'wp-erp-tlm-course-subscriber' ); ?>

    <# if ( ! data.group_id ) { #>
        <input type="hidden" name="action" value="erp-tlm-course-subscriber">
    <# } else { #>
        <input type="hidden" name="user_id" value="{{ data.user_id }}">
        <input type="hidden" name="action" value="erp-tlm-course-subscriber-edit">
    <# } #>

</div>
