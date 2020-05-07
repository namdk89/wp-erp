<div class="erp-customer-form erp-form">

    <div class="erp-grid-container">
        <div class="col-4 main-column">
            <div class="erp-tlm-modal-right">
            <# if ( _.contains( data.types, 'company' ) ) { #>
                <span class="required space-top">* <?php esc_attr_e( 'Fields are required', 'erp' ) ?></span>

                <?php do_action( 'erp_tlm_company_form_top' ); ?>
            <# } else { #>
                <span class="required space-top">* <?php esc_attr_e( 'Fields are required', 'erp' ); ?></span>

                <?php $custom_attr_length = apply_filters( 'erp_tlm_custom_attr_length', 30 ); ?>

                <?php do_action( 'erp_tlm_course_form_top' ); ?>
            <# } #>

            <div class="erp-grid-container">
                <fieldset class="no-border genaral-info">
                    <div class="row">
                    <# if ( _.contains( data.types, 'course' ) ) { #>
                        <div class="col-3" data-selected="{{ data.course_student }}">
                            <label for="erp-popup-course-student" ><?php esc_attr_e( 'Course Student', 'erp' ); ?><span class="required">*</span></label>
                            <select name="course[meta][course_student]" id="erp-popup-course-student" class="erp-student-select erp-select2" data-parent="ol" required>
                            <?php echo wp_kses( erp_tlm_get_course_options(),
                                    array(
                                        'option' => array(
                                            'value' => array(),
                                            'selected' => array()
                                        ),
                                    ) ); ?>
                            </select>
                        </div>
                    <# } else if ( _.contains( data.types, 'company' ) ) { #>
                        <div class="col-3 full-width customer-company-name clearfix">
                            <?php erp_html_form_input( array(
                                'label'       => __( 'Company Student', 'erp' ),
                                'name'        => 'course[meta][course_company]',
                                'required'    => true,
                                'type'        => 'select',
                                'class'       => 'erp-company-select erp-select2',
                                'options'     => erp_tlm_get_company_dropdown( [ '' => '--Select--' ] )
                            ) ); ?>
                        </div>
                    <# } #>

                        <div class="col-3 purchased-field">
                            <?php erp_html_form_input( array(
                                'label'    => __( 'Course Purchased', 'erp' ),
                                'required' => true,
                                'value'    => '{{ data.purchased }}',
                                'id'       => 'erp-tlm-new-course-purchased',
                                'type'     => 'select',
                                'class'    => 'erp-purchased-select erp-select2',
                                'options'  => array( '' => __( '--Select--', 'erp' ) )
                            ) ); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-3" data-selected="{{ data.life_stage }}">
                            <?php erp_html_form_input( array(
                                'label' => __( 'Life Stage', 'erp' ),
                                'name'  => 'course[meta][life_stage]',
                                'required' => true,
                                'type'  => 'select',
                                'class' => 'erp-select2',
                                'options' => erp_tlm_get_life_stages_dropdown_raw( [ '' => __( '--Select--', 'erp' ) ] )
                            ) ); ?>
                        </div>
                        
                        <div class="col-3">
                            <?php erp_html_form_input( array(
                                'label'    => __( 'Course Length', 'erp' ),
                                'required' => true,
                                'value'    => '{{ data.length }}',
                                'id'       => 'erp-tlm-new-course-length',
                                'type'     => 'number'
                            ) ); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3" data-selected = "{{ data.assign_to.id }}">
                            <?php erp_html_form_input( array(
                                'label'       => __( 'Course Teacher', 'erp' ),
                                'name'        => 'course[meta][course_teacher]',
                                'required'    => true,
                                'type'        => 'select',
                                'id'          => 'erp-tlm-course-teacher-id',
                                'class'       => 'erp-select2 erp-tlm-course-teacher-class',
                                'options'     => erp_tlm_get_tlm_teacher_dropdown( [ '' => '--Select--' ] )
                            ) ); ?>
                        </div>

                        <?php if ( current_user_can( 'administrator' ) || current_user_can( 'erp_tlm_manager' ) ): ?>
                            <div class="col-3" data-selected = "{{ data.assign_to.id }}">
                                <?php erp_html_form_input( array(
                                    'label'       => __( 'Course Owner', 'erp' ),
                                    'name'        => 'course[meta][course_owner]',
                                    'required'    => true,
                                    'type'        => 'select',
                                    'id'          => 'erp-tlm-course-owner-id',
                                    'class'       => 'erp-select2 erp-tlm-course-owner-class',
                                    'options'     => erp_tlm_get_tlm_user_dropdown( [ '' => '--Select--' ] )
                                ) ); ?>
                            </div>
                        <?php elseif ( current_user_can( 'erp_tlm_agent' ) ): ?>
                            <input type="hidden" name="course[meta][course_owner]" value="<?php echo esc_attr( get_current_user_id() ); ?>">
                        <?php endif ?>

                        <# if ( _.contains( data.types, 'company' ) ) { #>
                            <?php do_action( 'erp_tlm_company_form_basic' ); ?>
                        <# } else { #>
                            <?php do_action( 'erp_tlm_course_form_basic' ); ?>
                        <# } #>

                    </div>
                </fieldset>

                <fieldset class="course-schedule-info">
                    <legend><?php esc_attr_e( 'Course Schedule Info', 'erp' ) ?></legend>
                    <div class="row">
                        <div class="col-3">
                            <?php erp_html_form_input( array(
                                'label' => __( 'Start Date', 'erp' ),
                                'name'  => 'course[meta][start_date]',
                                'value' => '{{ data.start_date }}',
                                'class' => 'erp-date-field erp-tlm-date-field'
                            ) ); ?>
                        </div>

                        <div class="col-3">
                            <?php erp_html_form_input( array(
                                'label' => __( 'Force End Date', 'erp' ),
                                'name'  => 'course[meta][force_end_date]',
                                'value' => '{{ data.force_end_date }}',
                                'class' => 'erp-date-field erp-tlm-date-field'
                            ) ); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3" style="overflow: hidden; white-space: nowrap; justify-content: space-between; display: flex;">
                            <select style="-moz-appearance: none; -webkit-appearance: none; border: none">
                                <option>Mon</option>
                                <option>Tue</option>
                                <option>Wed</option>
                                <option>Thu</option>
                                <option>Fri</option>
                                <option>Sat</option>
                                <option>Sun</option>
                            </select>
                            <input style="width: 40%; border: none; box-shadow: none;" type="text" placeholder="07.00" size="10" autocomplete="off">
                                <span style="padding-top: 6px">:</span>
                                <input style="width: 40%; border: none; box-shadow: none;" type="text" placeholder="08.00" size="10" autocomplete="off">
                        </div>
                    </div>
                </fieldset>

                <p class="advanced-fields">
                    <input type="checkbox" id="advanced_fields">
                    <label for="advanced_fields">{{ __('Show Advanced Fields', 'erp') }}</label>
                </p>

                <fieldset class="others-info">
                    <legend><?php esc_attr_e( 'Others Info', 'erp' ) ?></legend>

                    <div class="row">

                        <# if ( _.contains( data.types, 'course' ) ) { #>
                        <div class="col-3">
                            <?php erp_html_form_input( array(
                                'label' => __( 'Date of Birth', 'erp' ),
                                'name'  => 'course[meta][date_of_birth]',
                                'value' => '{{ data.date_of_birth }}',
                                'class' => 'erp-date-field erp-tlm-date-field'
                            ) ); ?>
                        </div>
                        <div class="col-3">
                            <?php erp_html_form_input( array(
                                'label' => __( 'Age (years)', 'erp' ),
                                'name'  => 'course[meta][course_age]',
                                'value' => '{{ data.course_age }}',
                                'class' => '',
                                'type'  => 'number',
                                'custom_attr' => [ 'min' => 1, 'step' => 1 ]
                            ) ); ?>
                        </div>
                        <# } #>

                        <div class="col-3">
                            <?php erp_html_form_input( array(
                                'label' => __( 'Mobile', 'erp' ),
                                'name'  => 'course[main][mobile]',
                                'value' => '{{ data.mobile }}'
                            ) ); ?>
                        </div>

                        <div class="col-3">
                            <?php erp_html_form_input( array(
                                'label' => __( 'Website', 'erp' ),
                                'name'  => 'course[main][website]',
                                'value' => '{{ data.website }}'
                            ) ); ?>
                        </div>

                        <div class="col-3">
                            <?php erp_html_form_input( array(
                                'label' => __( 'Fax Number', 'erp' ),
                                'name'  => 'course[main][fax]',
                                'value' => '{{ data.fax }}'
                            ) ); ?>
                        </div>

                        <div class="col-3">
                            <?php erp_html_form_input( array(
                                'label' => __( 'Address 1', 'erp' ),
                                'name'  => 'course[main][street_1]',
                                'value' => '{{ data.street_1 }}'
                            ) ); ?>
                        </div>

                        <div class="col-3">
                            <?php erp_html_form_input( array(
                                'label' => __( 'Address 2', 'erp' ),
                                'name'  => 'course[main][street_2]',
                                'value' => '{{ data.street_2 }}'
                            ) ); ?>
                        </div>

                        <div class="col-3">
                            <?php erp_html_form_input( array(
                                'label' => __( 'City', 'erp' ),
                                'name'  => 'course[main][city]',
                                'value' => '{{ data.city }}'
                            ) ); ?>
                        </div>

                        <div class="col-3" data-selected="{{ data.country }}">
                            <label for="erp-popup-country"><?php esc_attr_e( 'Country', 'erp' ); ?></label>
                            <select name="course[main][country]" id="erp-popup-country" class="erp-country-select erp-select2" data-parent="ol">
                                <?php $country = \WeDevs\ERP\Countries::instance(); ?>
                                <?php echo wp_kses( $country->country_dropdown( erp_get_country() ),
                                    array(
                                        'option' => array(
                                            'value' => array(),
                                            'selected' => array()
                                        ),
                                    ) ); ?>
                            </select>
                        </div>

                        <div class="col-3 state-field" data-selected="{{ data.state }}">
                            <?php erp_html_form_input( array(
                                'label'   => __( 'Province / State', 'erp' ),
                                'name'    => 'course[main][state]',
                                'id'      => 'erp-state',
                                'type'    => 'select',
                                'class'   => 'erp-state-select erp-select2',
                                'options' => array( '' => __( '- Select -', 'erp' ) )
                            ) ); ?>
                        </div>

                        <div class="col-3">
                            <?php erp_html_form_input( array(
                                'label' => __( 'Post Code/Zip Code', 'erp' ),
                                'name'  => 'course[main][postal_code]',
                                'value' => '{{ data.postal_code }}'
                            ) ); ?>
                        </div>

                        <# if ( _.contains( data.types, 'company' ) ) { #>
                            <?php do_action( 'erp_tlm_company_form_other' ); ?>
                        <# } else { #>
                            <?php do_action( 'erp_tlm_course_form_other' ); ?>
                        <# } #>

                    </div>
                </fieldset>

                <?php if ( erp_tlm_get_course_group_dropdown() ) : ?>
                    <fieldset class="course-group">
                        <legend><?php esc_attr_e( 'Course Group', 'erp' ) ?></legend>

                        <div class="row">
                            <div class="col-6" id="erp-tlm-course-subscriber-group-checkbox" data-selected = "{{ data.group_id }}">
                                <?php erp_html_form_input( array(
                                    'label'       => __( 'Assign Group', 'erp' ),
                                    'name'        => 'group_id[]',
                                    'type'        => 'multicheckbox',
                                    'id'          => 'erp-tlm-course-group-id',
                                    'class'       => 'erp-tlm-course-group-class',
                                    'options'     => erp_tlm_get_course_group_dropdown()
                                ) ); ?>
                            </div>

                            <# if ( _.contains( data.types, 'company' ) ) { #>
                                <?php do_action( 'erp_tlm_company_form_course_group' ); ?>
                            <# } else { #>
                                <?php do_action( 'erp_tlm_course_form_course_group' ); ?>
                            <# } #>

                        </div>

                    </fieldset>

                    <?php endif; ?>

                    <fieldset class="additional-info">
                    <legend><?php esc_attr_e( 'Additional Info', 'erp' ) ?></legend>

                    <div class="row">

                        <div class="col-3" data-selected="{{ data.source }}">
                            <?php erp_html_form_input( array(
                                'label'   => __( 'Course Source', 'erp' ),
                                'name'    => 'course[meta][source]',
                                'id'      => 'erp-source',
                                'type'    => 'select',
                                'class'   => 'erp-source-select',
                                'options' => erp_tlm_course_sources()
                            ) ); ?>
                        </div>

                        <div class="col-3">
                            <?php erp_html_form_input( array(
                                'label'   => __( 'Others', 'erp' ),
                                'name'    => 'course[main][other]',
                                'value'   => '{{ data.other }}'
                            ) ); ?>
                        </div>

                        <div class="col-3">
                            <?php erp_html_form_input( array(
                                'label'   => __( 'Notes', 'erp' ),
                                'name'    => 'course[main][notes]',
                                'value'   => '{{ data.notes }}',
                                'type'   => 'textarea',
                            ) ); ?>
                        </div>

                        <# if ( _.contains( data.types, 'company' ) ) { #>
                            <?php do_action( 'erp_tlm_company_form_additional' ); ?>
                        <# } else { #>
                            <?php do_action( 'erp_tlm_course_form_additional' ); ?>
                        <# } #>

                    </div>
                    </fieldset>

                </div>

                <# if ( _.contains( data.types, 'company' ) ) { #>
                <?php do_action( 'erp_tlm_company_form_bottom' ); ?>
                <# } else { #>
                <?php do_action( 'erp_tlm_course_form_bottom' ); ?>
                <# } #>

                <input type="hidden" name="course[main][id]" id="erp-customer-id" value="{{ data.id }}">
                <input type="hidden" name="course[main][user_id]" id="erp-customer-user-id" value="{{ data.user_id }}">

                <# if ( _.contains( data.types, 'company' ) ) { #>
                <input type="hidden" name="course[main][type]" id="erp-customer-type" value="company">
                <# } else if ( _.contains( data.types, 'course' ) ) { #>
                <input type="hidden" name="course[main][type]" id="erp-customer-type" value="course">
                <# } #>

                <input type="hidden" name="action" id="erp-customer-action" value="erp-tlm-customer-new">
                <?php wp_nonce_field( 'wp-erp-tlm-customer-nonce' ); ?>

                <# if ( _.contains( data.types, 'company' ) ) { #>
                <?php do_action( 'erp_tlm_company_form' ); ?>
                <# } else { #>
                <?php do_action( 'erp_tlm_course_form' ); ?>
                <# } #>

                </div>
                </div>
                </div>
        </div> <!-- col 4 end -->
