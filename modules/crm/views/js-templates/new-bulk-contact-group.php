<div class="erp-crm-bulk-contact-subscriber-wrap">

    <div class="row" id="erp-crm-contact-subscriber-group-checkbox">
        <?php erp_html_form_input( array(
            'label'       => __( 'Assign Group', 'erp' ),
            'name'        => 'group_id[]',
            'type'        => 'multicheckbox',
            'id'          => 'erp-crm-contact-group-id',
            'class'       => 'erp-crm-contact-group-class',
            'options'     => erp_crm_get_contact_group_dropdown()
        ) ); ?>
    </div>

    <div class="row" id="erp-crm-contact-owner-select">
        <?php erp_html_form_input( array(
            'label'       => __( 'Contact Owner', 'erp' ),
            'name'        => 'contact_owner',
            'type'        => 'select',
            'id'          => 'erp-crm-contact-group-id',
            'class'       => 'erp-crm-contact-group-class',
            'options'     => erp_crm_get_crm_leader_dropdown([ '' => '--Select--' ])
        ) ); ?>
    </div>

    <?php wp_nonce_field( 'wp-erp-crm-bulk-contact-subscriber' ); ?>

    <input type="hidden" name="action" value="erp-crm-bulk-contact-subscriber">
    <input type="hidden" name="user_id" value="{{ data.user_id }}">
</div>
