<div class="wrap erp-tlm-course-group" id="wp-erp">

    <h2><?php esc_attr_e( 'Course Groups', 'erp' ); ?>
        <?php if ( current_user_can( 'erp_tlm_create_groups' ) ): ?>
            <a href="#" id="erp-new-course-group" class="erp-new-course-group add-new-h2" title="<?php esc_attr_e( 'Add New Course Group ', 'erp' ); ?>"><?php esc_attr_e( 'Add New Course Group', 'erp' ); ?></a>
        <?php endif ?>

        <a href="<?php echo esc_url_raw( add_query_arg( [ 'page'=>'erp-tlm', 'section' => 'course-groups', 'groupaction' => 'view-subscriber' ], admin_url('admin.php') ) ); ?>" class="add-new-h2" title="<?php esc_attr_e( 'View all subscriber course', 'erp' ); ?>"><?php esc_attr_e( 'View all subscriber', 'erp' ); ?></a>
    </h2>

    <div class="list-table-wrap erp-tlm-course-group-list-table-wrap">
        <div class="list-table-inner erp-tlm-course-group-list-table-inner">

            <form method="get">
                <input type="hidden" name="page" value="erp-tlm">
                <input type="hidden" name="section" value="course-groups">
                <?php
                $customer_table = new \WeDevs\ERP\TLM\Course_Group_List_Table();
                $customer_table->prepare_items();
                $customer_table->search_box( __( 'Search Course Group', 'erp' ), 'erp-tlm-course-group-search' );
                $customer_table->views();

                $customer_table->display();
                ?>
            </form>

        </div><!-- .list-table-inner -->
    </div><!-- .list-table-wrap -->
</div>
