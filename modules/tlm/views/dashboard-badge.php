<?php
    $courses_count  = erp_tlm_customer_get_status_count( 'course' );
    $companies_count = erp_tlm_customer_get_status_count( 'company' );
?>

<div class="erp-badge-box box-tlm">
    <h2><?php esc_attr_e( 'TLM', 'erp' ); ?>
        <a href="<?php echo esc_url_raw( admin_url( 'admin.php?page=erp-tlm' ) ); ?>" class="btn"><?php esc_attr_e( 'Visit Dashboard', 'erp' ); ?></a>
    </h2>

    <ul class="erp-badge-tlm-count">

        <li class="erp-count-box">
            <div class="count-inner">
                <h3><?php echo wp_kses_post( number_format_i18n( $courses_count['all']['count'], 0 ) ); ?></h3>
                <p>
                    <?php echo wp_kses_post( sprintf( _n( 'Course', 'Courses', $courses_count['all']['count'], 'erp' ), number_format_i18n( $companies_count['all']['count'] ), 0 ) ); ?>
                </p>

                <ul class="erp-info-box-list">
                    <?php
                    foreach ( $courses_count as $course_key => $course_value ) {
                        if ( $course_key == 'all' || $course_key == 'trash' ) {
                            continue;
                        }
                        ?>
                        <li>
                            <a href="<?php echo esc_url_raw( add_query_arg( [ 'page' => 'erp-tlm','section' => 'courses', 'status' => $course_key ], admin_url( 'admin.php' ) ) ); ?>">
                                <?php
                                    $singular = $course_value['label'];
                                    $plural = erp_pluralize( $singular );

                                    $plural = apply_filters( "erp_tlm_life_stage_plural_of_{$course_key}", $plural, $singular );

                                    echo wp_kses_post( sprintf( _n( "<span>{$singular}</span> <span>%s</span>", "<span>{$plural}</span> <span>%s</span>", $course_value['count'], 'erp' ), number_format_i18n( $course_value['count'] ), 0 ) );
                                ?>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>

            <div class="count-footer">
                <a href="<?php echo esc_url_raw( admin_url( 'admin.php?page=erp-tlm&section=courses' ) ); ?>"><?php esc_attr_e( 'View all Courses', 'erp' ); ?></a>
            </div>
        </li><!-- .count-box -->


        <li class="erp-count-box">
            <div class="count-inner">
                <h3><?php echo wp_kses_post( number_format_i18n( $companies_count['all']['count'], 0 ) ); ?></h3>
                <p>
                    <?php echo wp_kses_post( sprintf( _n( 'Company', 'Companies', $companies_count['all']['count'], 'erp' ), number_format_i18n( $companies_count['all']['count'] ), 0 ) ); ?>
                </p>

                <ul class="erp-info-box-list">
                    <?php
                    foreach ( $companies_count as $company_key => $company_value ) {
                        if ( $company_key == 'all' || $company_key == 'trash' ) {
                            continue;
                        }
                        ?>
                        <li>
                            <a href="<?php echo esc_url_raw( add_query_arg( [ 'page' => 'erp-tlm', 'section' => 'companies', 'status' => $company_key ], admin_url( 'admin.php' ) ) ); ?>">
                                <?php
                                    $singular = $company_value['label'];
                                    $plural = erp_pluralize( $singular );

                                    $plural = apply_filters( "erp_tlm_life_stage_plural_of_{$company_key}", $plural, $singular );

                                    echo wp_kses_post( sprintf( _n( "<span>{$singular}</span> <span>%s</span>", "<span>{$plural}</span> <span>%s</span>", $company_value['count'], 'erp' ), number_format_i18n( $company_value['count'] ), 0 ) );
                                ?>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>

            <div class="count-footer">
                <a href="<?php echo esc_url_raw( add_query_arg( ['page' => 'erp-tlm', 'section' => 'companies'], admin_url('admin.php') ) ); ?>"><?php esc_attr_e( 'View all Companies', 'erp' ); ?></a>
            </div>
        </li><!-- .count-box -->

    </ul>
</div>
