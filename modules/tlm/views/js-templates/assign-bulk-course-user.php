<div class="erp-tlm-bulk-course-user-wrap" >

    <div class="row" id="erp-tlm-course-assign-user-select">
        <?php erp_html_form_input( array(
            'label'       => __( 'Assign to User', 'erp' ),
            'name'        => 'assign_user_id',
            'required'    => true,
            'type'        => 'select',
            'id'          => 'erp-tlm-course-owner-id',
            'class'       => 'erp-select2 erp-tlm-course-owner-class',
            'options'     => erp_tlm_get_tlm_user_dropdown( [ '' => '--Select--' ] )
        ) ); ?>
    </div>

    <?php wp_nonce_field( 'wp-erp-tlm-bulk-course-user' ); ?>

    <input type="hidden" name="action" value="erp-tlm-bulk-course-user">
    <input type="hidden" name="user_id" value="{{ data.user_id }}">
</div>
