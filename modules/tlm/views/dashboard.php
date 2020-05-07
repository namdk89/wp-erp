<?php
    $courses_count  = erp_tlm_customer_get_status_count( 'course' );
    $companies_count = erp_tlm_customer_get_status_count( 'company' );
?>
<div class="wrap erp tlm-dashboard">
    <h2><?php esc_attr_e( 'TLM Dashboard', 'erp' ); ?></h2>

    <div class="erp-single-container">
        <div class="erp-area-left">
            <div class="erp-info-box">
                <div class="erp-info-box-item">
                    <div class="erp-info-box-item-inner">
                        <div class="erp-info-box-content">
                            <div class="erp-info-box-content-row">
                                <div class="erp-info-box-content-left">
                                    <h3><?php echo esc_attr( number_format_i18n( $courses_count['all']['count'], 0 ) ); ?></h3>
                                    <p>
                                        <?php echo wp_kses_post( sprintf( _n( 'Course', 'Courses', $courses_count['all']['count'], 'erp' ), number_format_i18n( $companies_count['all']['count'] ), 0 ) ); ?>
                                    </p>
                                </div>
                                <div class="erp-info-box-content-right">
                                    <ul class="erp-info-box-list">
                                        <?php
                                        foreach ( $courses_count as $course_key => $course_value ) {
                                            if ( $course_key == 'all' || $course_key == 'trash' ) {
                                                continue;
                                            }
                                            $course_url = add_query_arg( [ 'page' => 'erp-tlm','section' => 'courses', 'status' => $course_key ], admin_url( 'admin.php' ) );
                                            ?>
                                            <li>
                                                <a href="<?php echo esc_url_raw( $course_url ) ?>">
                                                    <i class="fa fa-square" aria-hidden="true"></i>&nbsp;
                                                    <?php echo esc_attr( $course_value['count'] . ' ' . $course_value['label'] ); ?>
                                                </a>
                                            </li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="erp-info-box-footer">
                            <a href="<?php echo esc_url_raw( admin_url( 'admin.php?page=erp-tlm&section=courses' ) ); ?>"><?php esc_attr_e( 'View all Courses', 'erp' ); ?></a>
                        </div>
                    </div>
                </div>
                <div class="erp-info-box-item">
                    <div class="erp-info-box-item-inner">
                        <div class="erp-info-box-content">
                            <div class="erp-info-box-content-row">
                                <div class="erp-info-box-content-left">
                                    <h3><?php echo wp_kses_post( number_format_i18n( $companies_count['all']['count'], 0 ) ); ?></h3>
                                    <p>
                                        <?php echo wp_kses_post( sprintf( _n( 'Company', 'Companies', $companies_count['all']['count'], 'erp' ), number_format_i18n( $companies_count['all']['count'] ), 0 ) ); ?>
                                    </p>
                                </div>
                                <div class="erp-info-box-content-right">
                                    <ul class="erp-info-box-list">
                                        <?php
                                        foreach ( $companies_count as $company_key => $company_value ) {
                                            if ( $company_key == 'all' || $company_key == 'trash' ) {
                                                continue;
                                            }
                                            $company_url = add_query_arg( [ 'page'    => 'erp-tlm',
                                                                              'section' => 'companies',
                                                                              'status'  => $company_key
                                            ], admin_url( 'admin.php' ) )

                                            ?>
                                            <li>
                                                <a href="<?php echo esc_url_raw( $company_url ) ; ?>">
                                                    <i class="fa fa-square" aria-hidden="true"></i>&nbsp;
                                                    <?php echo esc_attr( $company_value['count'] . ' ' . $company_value['label'] ); ?>
                                                </a>
                                            </li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php $companies_url = add_query_arg( ['page' => 'erp-tlm', 'section' => 'companies'], admin_url('admin.php') ); ?>
                        <div class="erp-info-box-footer">
                            <a href="<?php echo  esc_url_raw( $companies_url ); ?>"><?php esc_attr_e( 'View all Companies', 'erp' ); ?></a>
                        </div>
                    </div>
                </div>
            </div>

            <?php do_action( 'erp_tlm_dashboard_widgets_left' ); ?>

        </div><!-- .erp-area-left -->

        <div class="erp-area-right">
            <?php do_action( 'erp_tlm_dashboard_widgets_right' ); ?>
        </div>
    </div>
</div>
