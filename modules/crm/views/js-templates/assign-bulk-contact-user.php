<div class="erp-crm-bulk-contact-user-wrap" >

    <div class="row" id="erp-crm-contact-assign-user-select">
        <?php erp_html_form_input( array(
            'label'       => __( 'Assign to User', 'erp' ),
            'name'        => 'assign_user_id',
            'required'    => true,
            'type'        => 'select',
            'id'          => 'erp-crm-contact-owner-id',
            'class'       => 'erp-select2 erp-crm-contact-owner-class',
            'options'     => erp_crm_get_crm_user_dropdown( [ '' => '--Select--' ] )
        ) ); ?>
    </div>

    <?php wp_nonce_field( 'wp-erp-crm-bulk-contact-user' ); ?>

    <input type="hidden" name="action" value="erp-crm-bulk-contact-user">
    <input type="hidden" name="user_id" value="{{ data.user_id }}">
</div>
