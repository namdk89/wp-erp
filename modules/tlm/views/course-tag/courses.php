<div class="wrap erp-tlm-subscriber-course" id="wp-erp">

    <h2><?php esc_attr_e( 'Courses', 'erp' ); ?>
        <a href="#" id="erp-new-subscriber-course" class="erp-new-subscriber-course add-new-h2" title="<?php esc_attr_e( 'Assign a Course', 'erp' ); ?>"><?php esc_attr_e( 'Assign a Course', 'erp' ); ?></a>
        <?php $course_group_url = add_query_arg( [ 'page' => 'erp-tlm', 'section' => 'course-groups' ], admin_url( 'admin.php' ) ); ?>
        <a href="<?php echo  esc_url_raw( $course_group_url ); ?>" class="add-new-h2" title="<?php esc_attr_e( 'Back to Course Group', 'erp' ); ?>"><?php esc_attr_e( 'Back to Course Group', 'erp' ); ?></a>
    </h2>

    <div class="list-table-wrap erp-tlm-subscriber-course-list-table-wrap">
        <div class="list-table-inner erp-tlm-subscriber-course-list-table-inner">

            <form method="get">
                <input type="hidden" name="page" value="erp-tlm">
                <input type="hidden" name="section" value="course-groups">
                <input type="hidden" name="groupaction" value="view-subscriber">
                <?php
                $customer_table = new \WeDevs\ERP\TLM\Tag_Courses_List_Table();
                $customer_table->prepare_items();
                // $customer_table->search_box( __( 'Search Course Group', 'erp' ), 'erp-tlm-course-group-search' );
                $customer_table->views();

                $customer_table->display();
                ?>
            </form>

        </div><!-- .list-table-inner -->
    </div><!-- .list-table-wrap -->
</div>
