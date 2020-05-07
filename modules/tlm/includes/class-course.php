<?php
namespace WeDevs\ERP\TLM;

/**
* Customer Class
*
* @since 1.0
*
* @package WP-ERP|TLM
*/
class Course extends \WeDevs\ERP\People {

    protected $course_type;

    /**
     * Load parent constructor
     *
     * @since 1.0
     *
     * @param int|object $course
     */
    public function __construct( $course = null, $type = null ) {
        if ( ! is_object( $course ) ) {
            $course = absint( $course );
        }

        parent::__construct( $course );
        $this->types = $type ? (array)$type : $this->types;
    }

    /**
     * Get the user info as an array
     *
     * @return array
     */
    public function to_array() {
        $fields = array(
            'id'            => 0,
            'user_id'       => '',
            'first_name'    => '',
            'last_name'     => '',
            'company'       => '',
            'avatar'        => array(
                'id'  => 0,
                'url' => ''
            ),
            'life_stage'    => '',
            'email'         => '',
            'date_of_birth' => '',
            'phone'         => '',
            'mobile'        => '',
            'website'       => '',
            'fax'           => '',
            'street_1'      => '',
            'street_2'      => '',
            'city'          => '',
            'country'       => '',
            'state'         => '',
            'postal_code'   => '',
            'types'         => [],
            'notes'         => '',
            'other'         => '',
            'currency'      => '',
            'course_owner'      => '',
            'social'        => [],
            'source'        => '',
            'assign_to'     => [
                'id'           => 0,
                'avatar'       => '',
                'first_name'   => '',
                'last_name'    => '',
                'display_name' => '',
                'email'        => '',
            ],
            'course_age'   => '',
            'group_id'      => [],
        );

        $social_field = erp_tlm_get_social_field();

        foreach ( $social_field as $social_key => $social_value ) {
            $fields['social'][$social_key] = '';
        }

        $fields['types'] = $this->types;

        if ( $this->id ) {
            foreach ( $this->data as $key => $value ) {
                $fields[$key] = $value;
            }

            $avatar_id              = (int) $this->get_meta( 'photo_id', true );
            $fields['avatar']['id'] = $avatar_id;

            if ( $avatar_id ) {
                $fields['avatar']['url'] = wp_get_attachment_url( $avatar_id );
                $fields['avatar']['img'] = $this->get_avatar();
            } else {
                $fields['avatar']['url'] = erp_tlm_get_avatar_url( $this->id, $this->email, $this->user_id );
                $fields['avatar']['img'] = $this->get_avatar();
            }

            foreach ( $fields['social'] as $key => $value ) {
                $fields['social'][$key] = $this->get_meta( $key, true );
            }


            $course_groups           = erp_tlm_get_editable_assign_course( $this->id );
            $fields['course_groups'] = $course_groups;
            $fields['group_id']       = wp_list_pluck( $course_groups, 'group_id' );

            $course_owner_id = $this->course_owner;

            if ( $course_owner_id ) {
                $user = \get_user_by( 'id', $course_owner_id );

                $course_owner = [
                    'id'           => $user->ID,
                    'avatar'       => get_avatar_url( $user->ID ),
                    'first_name'   => $user->first_name,
                    'last_name'    => $user->last_name,
                    'display_name' => $user->display_name,
                    'email'        => $user->user_email
                ];

                $fields['assign_to']      = $course_owner;
            }

            $fields['life_stage']     = $this->life_stage;
            $fields['date_of_birth']  = $this->get_meta( 'date_of_birth', true );
            $fields['source']         = $this->get_meta( 'source', true );
            $fields['course_age']    = $this->get_meta( 'course_age', true );
            $fields['created']        = $this->created;
            $fields['created_by']     = $this->created_by;
            $fields['details_url']    = $this->get_details_url();
        }

        return apply_filters( 'erp_tlm_get_courses_fields', $fields, $this->data, $this->id, $this->types );
    }

    /**
     * Get single customer page view url
     *
     * @return string the url
     */
    public function get_details_url() {
        if ( $this->id ) {

            if ( in_array( 'course', $this->types ) ) {
                return add_query_arg( ['page' => 'erp-tlm', 'section' => 'courses', 'action' => 'view' , 'id' => $this->id ], admin_url('admin.php') );
            }

            if ( in_array( 'company', $this->types ) ) {
                return add_query_arg( ['page' => 'erp-tlm', 'section' => 'companies', 'action' => 'view' , 'id' => $this->id ], admin_url('admin.php') );
            }
        }
    }

    /**
     * Get an customer avatar
     *
     * @param  integer  avatar size in pixels
     *
     * @return string  image with HTML tag
     */
    public function get_avatar( $size = 32 ) {
        if ( $this->id ) {

            $user_photo_id = $this->get_meta( 'photo_id', true );

            if ( ! empty( $user_photo_id ) ) {
                $image = wp_get_attachment_thumb_url( $user_photo_id );
                return sprintf( '<img src="%1$s" alt="" class="avatar avatar-%2$s photo" height="auto" width="%2$s" />', $image, $size );
            }
        }

        $avatar = get_avatar( $this->email, $size );

        if ( ! $avatar ) {
            $image = WPERP_ASSETS . '/images/mystery-person.png';
            $avatar = sprintf( '<img src="%1$s" alt="" class="avatar avatar-%2$s photo" height="auto" width="%2$s" />', $image, $size );
        }

        return $avatar;
    }

    /**
     * Get first name
     *
     * @since 1.0
     *
     * @return string
     */
    public function get_first_name() {
        if ( $this->id ) {
            if ( $this->is_wp_user() ) {
                return \get_user_by( 'id', $this->user_id )->first_name;
            } else {
                return $this->first_name;
            }
        }
    }

    /**
     * Get last name
     *
     * @since 1.0
     *
     * @return string
     */
    public function get_last_name() {
        if ( $this->id ) {
            if ( $this->is_wp_user() ) {
                return \get_user_by( 'id', $this->user_id )->last_name;
            } else {
                return $this->last_name;
            }
        }
    }

    /**
     * Get phone number
     *
     * @since 1.0
     *
     * @return string
     */
    public function get_phone() {
        if ( $this->id ) {
            return ( $this->phone ) ? erp_get_clickable( 'phone', $this->phone ) : '—';
        }
    }

    /**
     * Get mobile number
     *
     * @since 1.0
     *
     * @return string
     */
    public function get_mobile() {
        if ( $this->id ) {
            return ( $this->mobile ) ? erp_get_clickable( 'phone', $this->mobile ) : '—';
        }
    }

    /**
     * Get fax number
     *
     * @since 1.0
     *
     * @return string
     */
    public function get_fax() {
        if ( $this->id ) {
            return ( $this->fax ) ? $this->fax : '—';
        }
    }

    /**
     * Get street 1 address
     *
     * @since 1.0
     *
     * @return string
     */
    public function get_street_1() {
        if ( $this->id ) {
            return ( $this->street_1 ) ? $this->street_1 : '—';
        }
    }

    /**
     * Get street 2 address
     *
     * @since 1.0
     *
     * @return string
     */
    public function get_street_2() {
        if ( $this->id ) {
            return ( $this->street_2 ) ? $this->street_2 : '—';
        }
    }

    /**
     * Get city name
     *
     * @since 1.0
     *
     * @return string
     */
    public function get_city() {
        if ( $this->id ) {
            return ( $this->city ) ? $this->city : '—';
        }
    }

    /**
     * Get country name
     *
     * @since 1.0
     *
     * @return string
     */
    public function get_country() {
        if ( $this->id ) {
            return ( $this->country != '-1' ) ? erp_get_country_name( $this->country ) : '—';
        }
    }

    /**
     * Get state name
     *
     * @since 1.0
     *
     * @return string
     */
    public function get_state() {
        if ( $this->id ) {
            return ( $this->state != '-1' ) ? erp_get_state_name( $this->country, $this->state ) : '—';
        }
    }

    /**
     * Get postal code/Zip Code
     *
     * @since 1.0
     *
     * @return string
     */
    public function get_postal_code() {
        if ( $this->id ) {
            return ( $this->postal_code ) ? $this->postal_code : '—';
        }
    }

    /**
     * Get notes
     *
     * @since 1.0
     *
     * @return string
     */
    public function get_notes() {
        if ( $this->id ) {
            return ( $this->notes ) ? $this->notes : '—';
        }
    }

    /**
     * Get birth date
     *
     * @since 1.0
     *
     * @return string
     */
    public function get_birthday() {
        $birth_day = $this->get_meta( 'date_of_birth', true );
        if ( $birth_day ) {
            return erp_format_date( $birth_day );
        }
    }

    /**
     * Get course age
     *
     * @since 1.1.7
     *
     * @return string
     */
    public function get_course_age() {
        $course_age = $this->get_meta( 'course_age', true );
        return $course_age ? $course_age : '—';
    }

    /**
     * Get the course source
     *
     * @since 1.0
     *
     * @return string
     */
    public function get_source() {
        $sources = erp_tlm_course_sources();
        $source = $this->get_meta( 'source', true );

        if ( array_key_exists( $source , $sources ) ) {
            $source = $sources[ $source ];
        }

        return $source;
    }

    /**
     * Get life stage
     *
     * @since 1.0
     *
     * @return string
     */
    public function get_life_stage() {
        return $this->life_stage;
    }

    /**
     * Update life stage
     * @since 1.2.7
     *
     * @param $stage
     *
     * @return bool|string|\WP_Error
     */
    public function update_life_stage( $stage ) {
        if( $this->life_stage == $stage ){
            return true;
        }

        if( ! in_array( $stage, array_keys(erp_tlm_get_life_stages_dropdown_raw())) ){
            return new \WP_Error( 'unknown-erp-life-stage', __( 'Life stage does not exists', 'erp' ) );
        }

        $this->update_property('life_stage', $stage);
    }

    /**
     * Get course owner
     *
     * @since 1.0
     *
     * @return string
     */
    public function get_course_owner() {
        $course_owner = $this->course_owner;

        return $course_owner;
    }

    /**
     * @since 1.2.7
     *
     * @param $course_owner
     */
    public function update_course_owner($course_owner){
        $this->update_property('course_owner', $course_owner);
    }

    /**
     * Get course hash
     *
     * @since 1.2.7
     *
     * @return string
     */
    public function get_course_hash() {
        $course_hash = $this->hash;

        return $course_hash;
    }

    /**
     * @since 1.2.7
     *
     * @param $hash
     */
    public function update_course_hash( $hash ){

        $this->update_property('hash', $hash );
    }
}
