<?php
if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_wpnonce'] ), 'erp-nonce' ) ) {
    // die();
}

$data         = [];
$start        = !empty( $_POST['start'] ) ? sanitize_text_field( wp_unslash( $_POST['start'] ) ) : false;
$end          = !empty( $_POST['end'] ) ? sanitize_text_field( wp_unslash( $_POST['end'] ) ): date('Y-m-d');
$filter_type  = !empty( $_POST['filter_type'] ) ? sanitize_text_field( wp_unslash( $_POST['filter_type'] ) ) : 'life_stage';

$reports      = erp_tlm_customer_reporting_query( $start, $end, $filter_type );

?><div class="wrap">
    <h2 class="report-title"><?php esc_attr_e( 'Customer Report', 'erp' ); ?></h2>
    <div class="erp-tlm-report-header-wrap">
        <?php erp_tlm_customer_report_filter_form(); ?>
        <button class="print" onclick="window.print()">Print</button>
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
            <?php if ( $filter_type === 'life_stage' ) :
                foreach ( $reports as $report ) {
                    $data[$report->life_stage] = $report->total;
                }
            ?>
            <tr>
                <td>All</td>
                <td><?php echo !empty( $data['initial'] )  ? esc_attr( $data['initial'] ) : 0; ?></td>
                <td><?php echo !empty( $data['learning'] )  ? esc_attr( $data['learning'] ) : 0; ?></td>
                <td><?php echo !empty( $data['pending'] )  ? esc_attr( $data['pending'] ) : 0; ?></td>
                <td><?php echo !empty( $data['dropped'] )  ? esc_attr( $data['dropped'] ) : 0; ?></td>
                <td><?php echo !empty( $data['completed'] )  ? esc_attr( $data['completed'] ) : 0; ?></td>
            </tr>

            <?php elseif ( $filter_type === 'course_owner' ) :
                foreach ( $reports as $report ) {
                    $data[ucwords( $report->course_owner )] = $report->owner_data;
                }

                foreach ( $data as $key => $value ) : ?>
                    <tr>
                        <td><?php echo esc_attr( $key ); ?></td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $detail->life_stage === 'initial' ) {
                                    $num = $detail->num;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $detail->life_stage === 'learning' ) {
                                    $num = $detail->num;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $detail->life_stage === 'pending' ) {
                                    $num = $detail->num;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $detail->life_stage === 'dropped' ) {
                                    $num = $detail->num;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $detail->life_stage === 'completed' ) {
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
                                if ( $detail->life_stage === 'initial' ) {
                                    $num = $detail->num;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $detail->life_stage === 'learning' ) {
                                    $num = $detail->num;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $detail->life_stage === 'pending' ) {
                                    $num = $detail->num;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $detail->life_stage === 'dropped' ) {
                                    $num = $detail->num;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $detail->life_stage === 'completed' ) {
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
                                if ( $key === 'initial' ) {
                                    $num = $detail;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $key === 'learning' ) {
                                    $num = $detail;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $key === 'pending' ) {
                                    $num = $detail;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $key === 'dropped' ) {
                                    $num = $detail;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $key === 'completed' ) {
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
                                if ( $key === 'initial' ) {
                                    $num = $detail;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $key === 'learning' ) {
                                    $num = $detail;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $key === 'pending' ) {
                                    $num = $detail;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $key === 'dropped' ) {
                                    $num = $detail;
                                }
                            }

                            echo esc_attr( $num );
                        ?>
                        </td>

                        <td>
                        <?php $num = 0;
                            foreach ( $value as $key => $detail ) {
                                if ( $key === 'completed' ) {
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

    .erp-tlm-report-filter-form {
        float: left;
        display: flex;
    }

    .erp-tlm-report-header-wrap {
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

        .erp-tlm-report-header-wrap {
            display: none;
        }

        .table.widefat.striped {
            margin-top: 20px;
        }
    }
</style>
