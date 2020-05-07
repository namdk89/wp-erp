<div class="wrap">
    <h1>
        <?php esc_attr_e( 'TLM Help', 'erp' ); ?>
        <a href="https://wperp.com/docs/tlm/" target="_blank" class="page-title-action">
            <?php esc_attr_e( 'View all Documentations', 'erp' ); ?>
        </a>
    </h1>
    <?php
    $erp_doc_sections = array(
        __( 'General', 'erp' )            => array(
            __( 'What are the differences between TLM life stages?', 'erp' ) => 'https://wperp.com/docs/tlm/getting-started/course-stages/',
        ),
        __( 'Course Management', 'erp' ) => array(
            __( 'How to add a new course?', 'erp' )                                      => 'https://wperp.com/docs/tlm/courses-management/adding-a-new-course/',
            __( 'How can I search courses by using filters and search segment?', 'erp' ) => 'https://wperp.com/docs/tlm/courses-management/courses-filtering/',
            __( 'How to send Emails to course by using templates?', 'erp' )              => 'https://wperp.com/docs/tlm/courses-management/sending-a-mail-from-template/',
            __( 'How can I set up a meeting with a course?', 'erp' )                     => 'https://wperp.com/docs/tlm/courses-management/setting-up-a-meeting/',
            __( 'How to assign courses to the companies?', 'erp' )                       => 'https://wperp.com/docs/tlm/courses-management/assigning-a-course-to-a-company/',
        ),
        __( 'Company Management', 'erp' ) => array(
            __( 'How to add a new company?', 'erp' )                => 'https://wperp.com/docs/tlm/company-management/creating-or-updating-a-new-company/',
            __( 'How to add a company to a course group?', 'erp' ) => 'https://wperp.com/docs/tlm/company-management/adding-a-company-to-a-course-group/',
        ),
        __( 'Miscellaneous', 'erp' )      => array(
            __( 'How to create courses group?', 'erp' )                 => 'https://wperp.com/docs/tlm/course-groups/creating-groups/',
            __( 'How to create an event or log from calendar?', 'erp' )  => 'https://wperp.com/docs/tlm/course-groups/creating-groups/',
            __( 'How to use Subscription Form (TLM) in WP ERP?', 'erp' ) => 'https://wperp.com/docs/tlm/subscription-forms/',
            __( 'Do you have tutorials on youtube?', 'erp' )             => 'https://wperp.com/docs/tlm/tutorial-videos-on-youtube/'
        )
    );

    $sections = apply_filters( 'erp_tlm_help_docs', $erp_doc_sections );

    if ( ! empty( $sections ) ):?>
        <div id="dashboard-widgets-wrap">
            <div id="dashboard-widgets" class="metabox-holder">
                <?php foreach ( $sections as $section_title => $docs ): ?>
                    <div class="erp-help-section postbox-container">
                        <div class="metabox-holder">

                            <div class="meta-box-sortables">

                                <div class="postbox">
                                    <h2 class="hndle"><?php echo esc_html( $section_title ); ?></h2>

                                    <?php if ( !empty($docs) ) { ?>
                                        <div class="erp-help-questions">
                                            <ul>
                                                <?php foreach ($docs as $title => $link) { ?>
                                                    <?php
                                                    $tracking_url = add_query_arg(
                                                        array(
                                                            'utm_source'   => 'doc',
                                                            'utm_medium'   => 'erp',
                                                            'utm_campaign' => 'manik',
                                                            'utm_content'  => 'aion',
                                                        ),
                                                        untrailingslashit($link)
                                                    );
                                                    ?>

                                                    <li>
                                                        <a href="<?php echo esc_url_raw( $tracking_url ); ?>" target="_blank"><?php echo esc_html( $title ); ?> <span class="dashicons dashicons-arrow-right-alt2"></span></a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                </div>

                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>

    <?php endif; ?>

</div>



<style type="text/css" media="screen">
    .erp-help-questions li {
        margin: 0;
        border-bottom: 1px solid #eee;
    }

    .erp-help-questions li a {
        padding: 10px 15px;
        display: block;
    }

    .erp-help-questions li a:hover {
        background-color: #F5F5F5;
    }

    .erp-help-questions li:last-child {
        border-bottom: none;
    }

    .erp-help-questions li .dashicons {
        float: right;
        color: #ccc;
        margin-top: -3px;
    }

    @media screen and (min-width: 960px) {
        .erp-help-section .postbox-container{
            width: 100% !important;
        }

        .erp-help-section:nth-child(odd){
            clear:both !important;
        }

    }
</style>
