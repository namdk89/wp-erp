<div class="tlm-customer-assign-company-wrap">

    <# if ( data.type == 'assign_company' ) { #>

        <div class="row">
            <label for="erp-select-customer-company"><?php esc_attr_e( 'Company Name', 'erp' ); ?> <span class="required">*</span></label>
            <select style="width:240px;" name="erp_assign_company_id" id="erp-select-customer-company" required="required" data-types="company" class="erp-tlm-course-list-dropdown" data-placeholder="<?php esc_attr_e( 'Select a company', 'erp' )?>">
                <option value=""><?php esc_attr_e( 'Select a Company', 'erp' ); ?></option>
            </select>
        </div>

    <# } else if ( data.type == 'assign_customer' ) { #>
        <div class="row">
            <label for="erp-select-customer-company"><?php esc_attr_e( 'Course Name', 'erp' ); ?> <span class="required">*</span></label>
            <select style="width:240px;" name="erp_assign_customer_id" id="erp-select-customer-company" required="required" data-types="course" class="erp-tlm-course-list-dropdown" data-placeholder="<?php esc_attr_e( 'Select a Course', 'erp' )?>">
                <option value=""><?php esc_attr_e( 'Select a Course', 'erp' ); ?></option>
            </select>
        </div>
    <# } #>

    <?php wp_nonce_field( 'wp-erp-tlm-assign-customer-company-nonce' ); ?>

    <input type="hidden" name="action" value="erp-tlm-customer-add-company">
    <input type="hidden" name="id" value="{{ data.id }}">
    <input type="hidden" name="assign_type" value="{{ data.type }}">
</div>
