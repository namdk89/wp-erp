<?php
if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_wpnonce'] ), 'erp-nonce' ) ) {
    // die();
}

$data         = [];
$start        = !empty( $_POST['start'] ) ? sanitize_text_field( wp_unslash( $_POST['start'] ) ) : false;
$end          = !empty( $_POST['end'] ) ? sanitize_text_field( wp_unslash( $_POST['end'] ) ): date('Y-m-d');
$filter_type  = !empty( $_POST['filter_type'] ) ? sanitize_text_field( wp_unslash( $_POST['filter_type'] ) ) : 'this_year';

$reports      = erp_tlm_growth_reporting_query( $start, $end, $filter_type );

?><div class="wrap">
    <h2 class="report-title"><?php esc_attr_e( 'Growth Report', 'erp' ); ?></h2>
    <div class="erp-tlm-report-header-wrap">
        <?php erp_tlm_growth_report_filter_form(); ?>
        <button class="print" onclick="window.print()">Print</button>
    </div>

    <div class="growth-chart-container">
        <canvas id="growth-chart"></canvas>
    </div>

    <table class="table widefat striped">
        <thead>
            <tr>
                <th><?php esc_attr_e( 'Label', 'erp' ); ?></th>
                <th><?php esc_attr_e( 'Initital', 'erp' ); ?></th>
                <th><?php esc_attr_e( 'Learning', 'erp' ); ?></th>
                <th><?php esc_attr_e( 'Pending', 'erp' ); ?></th>
                <th><?php esc_attr_e( 'Dropped', 'erp' ); ?></th>
                <th><?php esc_attr_e( 'Completed', 'erp' ); ?></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach( $reports as $key => $report ) : ?>

                <tr>
                    <td><?php echo esc_html( $key ) ?></td>
                    <td><?php echo !empty( $report['initial'] ) ? esc_attr( $report['initial'] ) : 0; ?></td>
                    <td><?php echo !empty( $report['learning'] ) ? esc_attr( $report['learning'] ) : 0; ?></td>
                    <td><?php echo !empty( $report['pending'] ) ? esc_attr( $report['pending'] ) : 0; ?></td>
                    <td><?php echo !empty( $report['dropped'] ) ? esc_attr( $report['dropped'] ) : 0; ?></td>
                    <td><?php echo !empty( $report['completed'] ) ? esc_attr( $report['completed'] ) : 0; ?></td>
                </tr>

            <?php endforeach; ?>
        </tbody>
    </table>

</div>

<style>
    .report-title {
        padding-bottom: 10px !important;
    }

    .erp-tlm-report-filter-form {
        float: left;
        display: flex;
    }

    .erp-tlm-report-header-wrap {
        height: 40px;
    }

    .print {
        float: right;
    }

    .table.widefat.striped {
        margin-top: 10px;
    }

    .growth-chart-container {
        height: 400px;
        margin-bottom: 50px;
    }

    @media print {
        .report-title {
            text-align: center;
        }

        .erp-tlm-report-header-wrap {
            display: none;
        }

        .table.widefat.striped {
            margin-top: 20px;
        }
    }
</style>
