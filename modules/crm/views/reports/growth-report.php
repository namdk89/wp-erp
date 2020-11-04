<?php
if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_wpnonce'] ), 'erp-nonce' ) ) {
    // die();
}

$data         = [];
$start        = !empty( $_POST['start'] ) ? sanitize_text_field( wp_unslash( $_POST['start'] ) ) : false;
$end          = !empty( $_POST['end'] ) ? sanitize_text_field( wp_unslash( $_POST['end'] ) ): date('Y-m-d');
$filter_type  = !empty( $_POST['filter_type'] ) ? sanitize_text_field( wp_unslash( $_POST['filter_type'] ) ) : 'this_year';

$reports      = erp_crm_growth_reporting_query( $start, $end, $filter_type );

?><div class="wrap">
    <h2 class="report-title"><?php esc_attr_e( 'Growth Report', 'erp' ); ?></h2>
    <div class="erp-crm-report-header-wrap">
        <?php erp_crm_growth_report_filter_form(); ?>
        <button class="print" onclick="window.print()">Print</button>
    </div>

    <div class="growth-chart-container">
        <canvas id="growth-chart"></canvas>
    </div>

    <table class="table widefat striped">
        <thead>
            <tr>
                <th><?php esc_attr_e( 'Label', 'erp' ); ?></th>
                <th><?php esc_attr_e( 'L0', 'erp' ); ?></th>
                <th><?php esc_attr_e( 'L1', 'erp' ); ?></th>
                <th><?php esc_attr_e( 'L2', 'erp' ); ?></th>
                <th><?php esc_attr_e( 'L3', 'erp' ); ?></th>
                <th><?php esc_attr_e( 'L4', 'erp' ); ?></th>
                <th><?php esc_attr_e( 'L5', 'erp' ); ?></th>
                <th><?php esc_attr_e( 'L6', 'erp' ); ?></th>
                <th><?php esc_attr_e( 'L7', 'erp' ); ?></th>
                <th><?php esc_attr_e( 'L8', 'erp' ); ?></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach( $reports as $key => $report ) : 
                $data['l0'] = $report['l00'];
                $data['l1'] = $report['l11'] + $report['l12'] + $report['l13'] + $report['l14'] + $report['l15'];
                $data['l2'] = $report['l21'] + $report['l22'] + $report['l23'] + $report['l24'];
                $data['l3'] = $report['l31'] + $report['l32'] + $report['l33'] + $report['l34'] + $report['l35'];
                $data['l4'] = $report['l41'] + $report['l42'] + $report['l43'];
                $data['l5'] = $report['l50'];
                $data['l6'] = $report['l61'] + $report['l62'] + $report['l63'];
                $data['l7'] = $report['l70'];
                $data['l8'] = $report['l80'];
            ?>

                <tr>
                    <td><?php echo esc_html( $key ) ?></td>
                    <td><?php echo !empty( $data['l0'] ) ? esc_attr( $data['l0'] ) : 0; ?></td>
                    <td><?php echo !empty( $data['l1'] ) ? esc_attr( $data['l1'] ) : 0; ?></td>
                    <td><?php echo !empty( $data['l2'] ) ? esc_attr( $data['l2'] ) : 0; ?></td>
                    <td><?php echo !empty( $data['l3'] ) ? esc_attr( $data['l3'] ) : 0; ?></td>
                    <td><?php echo !empty( $data['l4'] ) ? esc_attr( $data['l4'] ) : 0; ?></td>
                    <td><?php echo !empty( $data['l5'] ) ? esc_attr( $data['l5'] ) : 0; ?></td>
                    <td><?php echo !empty( $data['l6'] ) ? esc_attr( $data['l6'] ) : 0; ?></td>
                    <td><?php echo !empty( $data['l7'] ) ? esc_attr( $data['l7'] ) : 0; ?></td>
                    <td><?php echo !empty( $data['l8'] ) ? esc_attr( $data['l8'] ) : 0; ?></td>
                </tr>

            <?php endforeach; ?>
        </tbody>
    </table>

</div>

<style>
    .report-title {
        padding-bottom: 10px !important;
    }

    .erp-crm-report-filter-form {
        float: left;
        display: flex;
    }

    .erp-crm-report-header-wrap {
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

        .erp-crm-report-header-wrap {
            display: none;
        }

        .table.widefat.striped {
            margin-top: 20px;
        }
    }
</style>
