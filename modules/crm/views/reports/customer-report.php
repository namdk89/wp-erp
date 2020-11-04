<?php
if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_wpnonce'] ), 'erp-nonce' ) ) {
    // die();
}

$data         = [];
$start        = !empty( $_POST['start'] ) ? sanitize_text_field( wp_unslash( $_POST['start'] ) ) : false;
$end          = !empty( $_POST['end'] ) ? sanitize_text_field( wp_unslash( $_POST['end'] ) ): date('Y-m-d');
$filter_type  = !empty( $_POST['filter_type'] ) ? sanitize_text_field( wp_unslash( $_POST['filter_type'] ) ) : 'life_stage';

$reports      = erp_crm_customer_reporting_query( $start, $end, $filter_type );

?><div class="wrap">
    <h2 class="report-title"><?php esc_attr_e( 'Customer Report', 'erp' ); ?></h2>
    <div class="erp-crm-report-header-wrap">
        <?php erp_crm_customer_report_filter_form(); ?>
        <button class="print" onclick="window.print()">Print</button>
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
            <?php if ( $filter_type === 'life_stage' ) :
                foreach ( $reports as $report ) {
                    $level = substr($report->life_stage, 0, 2);
                    $data[$level] += $report->total;
                }
            ?>
            <tr>
                <td>All</td>
                <td><?php echo !empty( $data['l0'] )  ? esc_attr( $data['l0'] ) : 0; ?></td>
                <td><?php echo !empty( $data['l1'] )  ? esc_attr( $data['l1'] ) : 0; ?></td>
                <td><?php echo !empty( $data['l2'] )  ? esc_attr( $data['l2'] ) : 0; ?></td>
                <td><?php echo !empty( $data['l3'] )  ? esc_attr( $data['l3'] ) : 0; ?></td>
                <td><?php echo !empty( $data['l4'] )  ? esc_attr( $data['l4'] ) : 0; ?></td>
                <td><?php echo !empty( $data['l5'] )  ? esc_attr( $data['l5'] ) : 0; ?></td>
                <td><?php echo !empty( $data['l6'] )  ? esc_attr( $data['l6'] ) : 0; ?></td>
                <td><?php echo !empty( $data['l7'] )  ? esc_attr( $data['l7'] ) : 0; ?></td>
                <td><?php echo !empty( $data['l8'] )  ? esc_attr( $data['l8'] ) : 0; ?></td>
            </tr>

            <?php elseif ( $filter_type === 'contact_owner' ) :
                foreach ( $reports as $report ) {
                    $data[ucwords( $report->contact_owner )] = $report->owner_data;
                }

                foreach ( $data as $key => $value ) : ?>
                    <tr>
                        <td><?php echo esc_attr( $key ); ?></td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $detail->life_stage === 'l0' ) {
                                    $num = $detail->num;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $detail->life_stage === 'l1' ) {
                                    $num = $detail->num;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $detail->life_stage === 'l2' ) {
                                    $num = $detail->num;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $detail->life_stage === 'l3' ) {
                                    $num = $detail->num;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $detail->life_stage === 'l4' ) {
                                    $num = $detail->num;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $detail->life_stage === 'l5' ) {
                                    $num = $detail->num;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $detail->life_stage === 'l6' ) {
                                    $num = $detail->num;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $detail->life_stage === 'l7' ) {
                                    $num = $detail->num;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $detail->life_stage === 'l8' ) {
                                    $num = $detail->num;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php elseif ( $filter_type === 'country' ) :
                foreach ( $reports as $report ) {
                    $data[ $report->country ] = $report->country_data;
                }

                foreach ( $data as $key => $value ) : ?>
                    <tr>
                        <td><?php echo esc_attr( $key ) !== -1 ? esc_html( erp_get_country_name( $key ) ) : 'Other'; ?></td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $detail->life_stage === 'l0' ) {
                                    $num = $detail->num;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $detail->life_stage === 'l1' ) {
                                    $num = $detail->num;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $detail->life_stage === 'l2' ) {
                                    $num = $detail->num;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $detail->life_stage === 'l3' ) {
                                    $num = $detail->num;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $detail->life_stage === 'l4' ) {
                                    $num = $detail->num;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $detail->life_stage === 'l5' ) {
                                    $num = $detail->num;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $detail->life_stage === 'l6' ) {
                                    $num = $detail->num;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $detail->life_stage === 'l7' ) {
                                    $num = $detail->num;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $detail->life_stage === 'l8' ) {
                                    $num = $detail->num;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>
                    </tr>
                <?php endforeach;

            elseif ( $filter_type === 'source' ) :

                foreach ( $reports as $key => $value ) : ?>
                    <tr>
                        <td><?php echo esc_attr( $key ); ?></td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $key === 'l0' ) {
                                    $num = $detail;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $key === 'l1' ) {
                                    $num = $detail;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $key === 'l2' ) {
                                    $num = $detail;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $key === 'l3' ) {
                                    $num = $detail;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $key === 'l4' ) {
                                    $num = $detail;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $key === 'l5' ) {
                                    $num = $detail;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $key === 'l6' ) {
                                    $num = $detail;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $key === 'l7' ) {
                                    $num = $detail;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $key === 'l8' ) {
                                    $num = $detail;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>
                    </tr>
                <?php endforeach;

            elseif ( $filter_type === 'group' ) :

                foreach ( $reports as $key => $value ) : ?>
                    <tr>
                        <td><?php echo esc_attr( $key ); ?></td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $key === 'l0' ) {
                                    $num = $detail;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $key === 'l1' ) {
                                    $num = $detail;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $key === 'l2' ) {
                                    $num = $detail;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $key === 'l3' ) {
                                    $num = $detail;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $key === 'l4' ) {
                                    $num = $detail;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $key === 'l5' ) {
                                    $num = $detail;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $key === 'l6' ) {
                                    $num = $detail;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $key === 'l7' ) {
                                    $num = $detail;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $key === 'l8' ) {
                                    $num = $detail;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php endif; ?>

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
        height: 25px;
    }

    .print {
        float: right;
    }

    .table.widefat.striped {
        margin-top: 10px;
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
