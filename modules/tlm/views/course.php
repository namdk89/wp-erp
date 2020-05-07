<?php
if ( isset( $_GET['filter_assign_course' ] ) && !empty( $_GET['filter_assign_course' ] ) ) {
    $id = intval( $_GET['filter_assign_course'] );
    $custom_data = [
        'filter_assign_course' => [
            'id'           => $id,
            'display_name' => get_the_author_meta( 'display_name', $id )
        ],
        'searchFields' => array_keys( erp_tlm_get_serach_key( 'course' ) )
    ];
} elseif ( isset( $_GET['filter_course_company' ] ) && !empty( $_GET['filter_course_company' ] ) ) {
    $id = intval( $_GET['filter_course_company'] );
    $custom_data = [
        'filter_course_company' => [
            'id'           => $id,
            'display_name' => erp_get_people( $id )->company
        ],
        'searchFields' => array_keys( erp_tlm_get_serach_key( 'course' ) )
    ];
} else {
    $custom_data = [
        'searchFields' => array_keys( erp_tlm_get_serach_key( 'course' ) )
    ];
}
?>

<div class="wrap erp-tlm-customer erp-tlm-customer-listing" id="wp-erp" v-cloak>

    <h2>
        <?php esc_attr_e( 'Course', 'erp' ); ?>
        <?php if ( current_user_can( 'erp_tlm_add_course' ) ): ?>
            <a href="#" @click.prevent="addCourse( 'course', '<?php esc_attr_e( 'Add New Course', 'erp' ); ?>' )" id="erp-customer-new" class="erp-course-new add-new-h2"><?php esc_attr_e( 'Add New Course', 'erp' ); ?></a>
        <?php endif ?>

        <a href="#" @click.prevent="addSearchSegment()" id="erp-course-search-segmen" class="erp-search-segment add-new-h2">{{{ segmentBtnText }}}</a>
    </h2>

    <!-- Advance search filter vue component -->
    <advance-search :show-hide-segment="showHideSegment"></advance-search>

    <!-- vue table for displaying course list -->
    <vtable v-ref:vtable
        wrapper-class="erp-tlm-list-table-wrap"
        table-class="customers"
        row-checkbox-id="erp-tlm-customer-id-checkbox"
        row-checkbox-name="customer_id"
        action="erp-tlm-get-courses"
        :wpnonce="wpnonce"
        page = "<?php echo esc_url_raw( add_query_arg( [ 'page' => 'erp-tlm' ], admin_url( 'admin.php' ) ) ); ?>"
        per-page="20"
        :fields=fields
        :item-row-actions=itemRowActions
        :search="search"
        :top-nav-filter="topNavFilter"
        :bulkactions="bulkactions"
        :extra-bulk-action="extraBulkAction"
        :additional-params="additionalParams"
        :remove-url-params="removeUrlParams"
        :custom-data = '<?php echo json_encode( $custom_data, JSON_UNESCAPED_UNICODE ); ?>'
    ></vtable>

</div>
