<div class="erp-tlm-bulk-course-subscriber-wrap">

    <div class="row" id="erp-tlm-course-subscriber-group-checkbox">
        <?php erp_html_form_input( array(
            'label'       => __( 'Assign Group', 'erp' ),
            'name'        => 'group_id[]',
            'type'        => 'multicheckbox',
            'id'          => 'erp-tlm-course-group-id',
            'class'       => 'erp-tlm-course-group-class',
            'options'     => erp_tlm_get_course_group_dropdown()
        ) ); ?>
    </div>

    <?php wp_nonce_field( 'wp-erp-tlm-bulk-course-subscriber' ); ?>

    <input type="hidden" name="action" value="erp-tlm-bulk-course-subscriber">
    <input type="hidden" name="user_id" value="{{ data.user_id }}">
</div>
