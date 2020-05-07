<?php
$course_tags = wp_get_object_terms( $customer->id, 'erp_tlm_tag', array('orderby' => 'name', 'order' => 'ASC'));
$course_tags = wp_list_pluck($course_tags, 'name');
$course_list_url = add_query_arg( ['page' => 'erp-tlm', 'section' => 'courses'], admin_url('admin.php') );
?>
<div class="wrap erp erp-tlm-customer erp-single-customer" id="wp-erp" v-cloak>
    <h2><?php esc_attr_e( 'Course #', 'erp' ); echo esc_attr( $customer->id ); ?>
        <a href="<?php echo esc_url_raw( $course_list_url ); ?>" id="erp-course-list" class="add-new-h2"><?php esc_attr_e( 'Back to Course list', 'erp' ); ?></a>

        <?php if ( current_user_can( 'erp_tlm_edit_course', $customer->id ) || current_user_can( erp_tlm_get_manager_role() ) ): ?>
            <span class="edit">
                <a href="#" @click.prevent="editCourse( 'course', '<?php echo esc_attr( $customer->id ); ?>', '<?php esc_attr_e( 'Edit this course', 'erp' ); ?>' )" data-id="<?php echo esc_attr( $customer->id ); ?>" data-single_view="1" title="<?php esc_attr_e( 'Edit this Course', 'erp' ); ?>" class="add-new-h2"><?php esc_attr_e( 'Edit this Course', 'erp' ); ?></a>
            </span>

            <?php if ( ! $customer->user_id && erp_tlm_current_user_can_make_wp_user() ): ?>
                <span class="make-wp-user">
                    <a href="#" @click.prevent="makeWPUser( 'course', '<?php echo esc_attr( $customer->id ); ?>', '<?php esc_attr_e( 'Make WP User', 'erp' ); ?>', '<?php echo esc_attr( $customer->email ) ?>' )" data-single_view="1" title="<?php esc_attr_e( 'Make this course as a WP User', 'erp' ); ?>" class="add-new-h2"><?php esc_attr_e( 'Make WP User', 'erp' ); ?></a>
                </span>
            <?php endif ?>
        <?php endif ?>
    </h2>

    <div class="erp-grid-container erp-single-customer-content">
        <div class="row">

            <div class="col-2 column-left erp-single-customer-row" id="erp-customer-details">
                <div class="left-content">
                    <div class="customer-image-wraper">
                        <div class="row">
                            <div class="col-2 avatar">
                                <?php echo wp_kses_post( $customer->get_avatar(100) ); ?>
                            </div>
                            <div class="col-4 details">
                                <h3><?php echo esc_attr( $customer->get_full_name() ); ?></h3>

                                <?php if ( $customer->get_email() ): ?>
                                    <p>
                                        <i class="fa fa-envelope"></i>&nbsp;
                                        <?php echo wp_kses_post( erp_get_clickable( 'email', $customer->get_email() ) ); ?>
                                    </p>
                                <?php endif ?>

                                <?php if ( $customer->get_mobile() != '—' ): ?>
                                    <p>
                                        <i class="fa fa-phone"></i>&nbsp;
                                        <?php echo wp_kses_post( $customer->get_mobile() ); ?>
                                    </p>
                                <?php endif ?>

                                <ul class="erp-list list-inline social-profile">
                                    <?php $social_field = erp_tlm_get_social_field(); ?>

                                    <?php foreach ( $social_field as $social_key => $social_value ) : ?>
                                        <?php $social_field_data = $customer->get_meta( $social_key, true ); ?>

                                        <?php if ( ! empty( $social_field_data ) ): ?>
                                            <li><a href="<?php echo esc_url_raw( $social_field_data ); ?>"><?php echo wp_kses_post( $social_value['icon'] ); ?></a></li>
                                        <?php endif ?>
                                    <?php endforeach ?>

                                    <?php do_action( 'erp_tlm_course_social_fields', $customer ); ?>
                                </ul>

                            </div>
                        </div>
                    </div>

                    <div class="postbox customer-basic-info">
                        <div class="erp-handlediv" title="<?php esc_attr_e( 'Click to toggle', 'erp' ); ?>"><br></div>
                        <h3 class="erp-hndle"><span><?php esc_attr_e( 'Basic Info', 'erp' ); ?></span></h3>
                        <div class="inside">
                            <ul class="erp-list separated">
                                <li><?php erp_print_key_value( __( 'First Name', 'erp' ), $customer->get_first_name() ); ?></li>
                                <li><?php erp_print_key_value( __( 'Last Name', 'erp' ), $customer->get_last_name() ); ?></li>
                                <li><?php erp_print_key_value( __( 'Date of Birth', 'erp' ), $customer->get_birthday() ); ?></li>
                                <li><?php erp_print_key_value( __( 'Age', 'erp' ), $customer->get_course_age() ); ?></li>
                                <li><?php erp_print_key_value( __( 'Phone', 'erp' ), $customer->get_phone() ); ?></li>
                                <li><?php erp_print_key_value( __( 'Fax', 'erp' ), $customer->get_fax() ); ?></li>
                                <li><?php erp_print_key_value( __( 'Website', 'erp' ), $customer->get_website() ); ?></li>
                                <li><?php erp_print_key_value( __( 'Street 1', 'erp' ), $customer->get_street_1() ); ?></li>
                                <li><?php erp_print_key_value( __( 'Street 2', 'erp' ), $customer->get_street_2() ); ?></li>
                                <li><?php erp_print_key_value( __( 'City', 'erp' ), $customer->get_city() ); ?></li>
                                <li><?php erp_print_key_value( __( 'State', 'erp' ), $customer->get_state() ); ?></li>
                                <li><?php erp_print_key_value( __( 'Country', 'erp' ), $customer->get_country() ); ?></li>
                                <li><?php erp_print_key_value( __( 'Postal Code', 'erp' ), $customer->get_postal_code() ); ?></li>
                                <li><?php erp_print_key_value( __( 'Source', 'erp' ), $customer->get_source() ); ?></li>
                                <li><?php erp_print_key_value( __( 'Life stage', 'erp' ), $customer->get_life_stage() ); ?></li>

                                <?php do_action( 'erp_tlm_single_course_basic_info', $customer ); ?>
                            </ul>

                            <div class="erp-tlm-assign-course">
                                <div class="inner-wrap">
                                    <h4><?php esc_attr_e( 'Course Owner', 'erp' ); ?></h4>
                                    <div class="user-wrap">
                                        <div class="user-wrap-content">
                                            <?php
                                                $tlm_user_id = $customer->get_course_owner();
                                                if ( !empty( $tlm_user_id ) ) {
                                                    $user        = get_user_by( 'id', $tlm_user_id );
                                                    $user_string = esc_html( $user->display_name );
                                                    $user_email  = $user->get('user_email');
                                                } else {
                                                    $user_string = '';
                                                }
                                            ?>
                                            <?php if ( $tlm_user_id && ! empty( $user ) ): ?>
                                                <?php echo wp_kses_post( erp_tlm_get_avatar( $tlm_user_id, $user_email, $tlm_user_id, 32 ) ); ?>
                                                <div class="user-details">
                                                    <a href="#"><?php echo esc_attr( get_the_author_meta( 'display_name', $tlm_user_id ) ); ?></a>
                                                    <span><?php echo esc_attr(  get_the_author_meta( 'user_email', $tlm_user_id ) ); ?></span>
                                                </div>
                                            <?php else: ?>
                                                <div class="user-details">
                                                    <p><?php esc_attr_e( 'Nobody', 'erp' ) ?></p>
                                                </div>
                                            <?php endif ?>

                                            <div class="clearfix"></div>

                                        </div>
                                    </div>

                                    <?php if ( current_user_can( 'erp_tlm_edit_course' ) ): ?>
                                        <span @click.prevent="assignCourse()" id="erp-tlm-edit-assign-course-to-agent"><i class="fa fa-pencil-square-o"></i></span>
                                    <?php endif ?>

                                    <div class="assign-form erp-hide">
                                        <form action="" method="post">

                                            <div class="tlm-aget-search-select-wrap">
                                                <select name="erp_select_assign_course" id="erp-select-user-for-assign-course" style="width: 300px; margin-bottom: 20px;" data-placeholder="<?php esc_attr_e( 'Search a tlm agent', 'erp' ) ?>" data-val="<?php echo esc_attr( $tlm_user_id ); ?>" data-selected="<?php echo esc_attr( $user_string ); ?>">
                                                    <option value=""><?php esc_attr_e( 'Select a agent', 'erp' ); ?></option>
                                                    <?php if ( $tlm_user_id ): ?>
                                                        <option value="<?php echo esc_attr( $tlm_user_id ) ?>" selected><?php echo esc_attr( $user_string ); ?></option>
                                                    <?php endif ?>
                                                </select>
                                            </div>

                                            <input type="hidden" name="assign_course_id" value="<?php echo esc_attr( $customer->id ); ?>">
                                            <input type="hidden" id="course_id" name="course_id" value="<?php echo esc_attr( $customer->id ); ?>">
                                            <input type="hidden" name="assign_course_user_id" value="<?php echo esc_attr( $customer->user_id ); ?>">
                                            <input type="submit" @click.prevent="saveAssignCourse()" class="button button-primary save-edit-assign-course" name="erp_assign_courses" value="<?php esc_attr_e( 'Assign', 'erp' ); ?>">
                                            <input type="submit" @click.prevent="cancelAssignCourse()" class="button cancel-edit-assign-course" value="<?php esc_attr_e( 'Cancel', 'erp' ); ?>">
                                            <span class="erp-loader erp-hide assign-form-loader"></span>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- .postbox -->

                    <div class="postbox erp-customer-tag-div" id="tagsdiv-post_tag">
                        <div class="erp-handlediv" title="<?php esc_attr_e( 'Click to toggle', 'erp' ); ?>"><br></div>
                        <h3 class="erp-hndle"><span><?php esc_attr_e( 'Tag', 'erp' ); ?></span></h3>
                        <div class="inside">
                            <div class="tagsdiv" id="tagsdiv-erp-tlm-tag">
                                <div class="nojs-tags hide-if-js">
                                    <label for="tax-input-post_tag">Add or remove tags</label>
                                    <p><textarea name="tax_input[erp_tlm_tag]" rows="3" cols="20" class="the-tags" id="tax-input-erp_tlm_tag" aria-describedby="new-tag-post_tag-desc">
                                            <?php echo esc_attr( implode(',', $course_tags) );?>
                                        </textarea></p>
                                </div>

                                <div class="jaxtag">
                                    <div class="ajaxtag hide-if-no-js">
                                        <label class="screen-reader-text" for="new-tag-erp-tlm-tag"></label>
                                        <p>
                                            <input style="width: 82%;" data-wp-taxonomy="erp_tlm_tag" type="text" id="new-tag-erp-tlm-tag" name="newtag[erp_tlm_tag]" class="newtag form-input-tip" size="16" autocomplete="on" aria-describedby="new-tag-erp-tlm-tag-desc" value="" />
                                            <input type="button" id="add-tlm-tag" class="button tagadd" value="<?php esc_attr_e('Add', 'erp'); ?>" /></p>
                                    </div>
                                    <p class="howto" id="new-tag-erp-tlm-tag-desc"><?php esc_attr_e('Separate tags with commas', 'erp') ?></p>

                                    <p><?php ?></p>
                                </div>
                                <ul class="tagchecklist" role="list" style="margin-bottom: 0;"></ul>
                            </div>
                        </div>
                    </div>


                    <course-company-relation
                        :id="<?php echo esc_attr( $customer->id ); ?>"
                        type="course_companies"
                        add-button-txt="<?php esc_attr_e( 'Assign a company', 'erp' ) ?>"
                        title="<?php esc_attr_e( 'Companies', 'erp' ); ?>"
                    ></course-company-relation>

                    <course-assign-group
                        :id="<?php echo esc_attr( $customer->id ); ?>"
                        add-button-txt="<?php esc_attr_e( 'Assign Course Groups', 'erp' ) ?>"
                        title="<?php esc_attr_e( 'Course Group', 'erp' ); ?>"
                        is-permitted="<?php echo esc_attr( current_user_can( 'erp_tlm_edit_course', $customer->id ) ); ?>"
                    ></course-assign-group>

                    <?php do_action( 'erp_tlm_course_left_widgets', $customer ); ?>
                </div>
            </div>

            <div class="col-4 column-right">
                <?php include WPERP_TLM_VIEWS . '/course/feeds.php'; ?>
            </div>

        </div>
    </div>

</div>
