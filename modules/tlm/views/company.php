<?php
if ( isset( $_GET['filter_assign_course' ] ) && !empty( $_GET['filter_assign_course' ] ) ) {
    $id = intval( $_GET['filter_assign_course'] );

    $custom_data = [
        'filter_assign_course' => [
            'id' => $id,
            'display_name' => get_the_author_meta( 'display_name', $id )
        ],
        'searchFields' => array_keys( erp_tlm_get_serach_key( 'company' ) )
    ];
} else {
    $custom_data = [
        'searchFields' => array_keys( erp_tlm_get_serach_key( 'company' ) )
    ];
}
?>
<div class="wrap erp-tlm-customer erp-tlm-customer-listing" id="wp-erp">

    <h2><?php esc_attr_e( 'Company', 'erp' ); ?>
        <?php if ( current_user_can( 'erp_tlm_add_course' ) ): ?>
            <a href="#" @click.prevent="addCourse( 'company', '<?php esc_attr_e( 'Add New Company', 'erp' ); ?>' )" id="erp-company-new" class="erp-course-new add-new-h2" data-type="company" title="<?php esc_attr_e( 'Add New Company', 'erp' ); ?>"><?php esc_attr_e( 'Add New Company', 'erp' ); ?></a>
        <?php endif; ?>

        <a href="#" @click.prevent="addSearchSegment()" id="erp-course-search-segmen" class="erp-search-segment add-new-h2" v-text="( showHideSegment ) ? '<?php esc_attr_e( 'Hide Search Segment', 'erp' ); ?>' : '<?php esc_attr_e( 'Add Search Segment', 'erp' ); ?>'"></a>
    </h2>

    <!-- Advance search filter vue component -->
    <advance-search :show-hide-segment="showHideSegment"></advance-search>

    <!-- vue table for displaying course list -->
    <vtable v-ref:vtable
        wrapper-class="erp-tlm-list-table-wrap"
        table-class="customers"
        row-checkbox-id="erp-tlm-company-id-checkbox"
        row-checkbox-name="company_id"
        action="erp-tlm-get-courses"
        :wpnonce="wpnonce"
        page = "<?php echo esc_url_raw( add_query_arg( [ 'page' => 'erp-tlm', 'section' => 'companies' ], admin_url( 'admin.php' ) ) ); ?>"
        per-page="20"
        :fields=fields
        :item-row-actions=itemRowActions
        :search="search"
        :top-nav-filter="topNavFilter"
        :bulkactions="bulkactions"
        :extra-bulk-action = "extraBulkAction"
        :additional-params = "additionalParams"
        :custom-data = '<?php echo json_encode( $custom_data, JSON_UNESCAPED_UNICODE ); ?>'
    ></vtable>
</div>
