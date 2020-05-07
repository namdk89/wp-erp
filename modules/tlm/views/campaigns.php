<div class="wrap erp-tlm-campaign" id="wp-erp">

    <h2><?php esc_attr_e( 'Campaigns', 'erp' ); ?>
        <a href="#" id="erp-customer-new" class="erp-course-new add-new-h2" data-type="course" title="<?php esc_attr_e( 'Add New Course', 'erp' ); ?>"><?php esc_attr_e( 'Add New Course', 'erp' ); ?></a>
    </h2>

    <div class="list-table-wrap">
        <div class="list-table-inner">

            <form method="get">
                <input type="hidden" name="page" value="erp-sales-campaigns">
                <?php
                $customer_table = new \WeDevs\ERP\TLM\Campaign_List_Table( 'course' );
                $customer_table->prepare_items();
                $customer_table->search_box( __( 'Search Course', 'erp' ), 'erp-customer-search' );
                $customer_table->views();

                $customer_table->display();
                ?>
            </form>

        </div><!-- .list-table-inner -->
    </div><!-- .list-table-wrap -->
</div>
