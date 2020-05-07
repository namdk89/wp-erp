<?php
namespace WeDevs\ERP\TLM\Emails;

use WeDevs\ERP\Email;
use WeDevs\ERP\Framework\Traits\Hooker;

/**
 * Birthday wish
 */
class Birthday_Greetings extends Email {

    use Hooker;

    function __construct() {
        $this->id             = 'birthday-greetings';
        $this->title          = __( 'Birthday Greetings To Courses', 'erp' );
        $this->description    = __( 'Birthday greetings email to courses.', 'erp' );

        $this->subject        = __( 'Birthday Greetings to {last_name} {first_name}', 'erp');
        $this->heading        = __( 'Happy Birthday :)', 'erp');

        $this->find = [
            'first-name'      => '{first_name}',
            'last-name'       => '{last_name}'
        ];

        $this->action( 'erp_admin_field_' . $this->id . '_help_texts', 'replace_keys' );

        parent::__construct();
    }

    public function trigger() {
        $courses =  erp_get_peoples( [
            'type'  =>  'course'
        ] );

        if ( ! $courses ) {
            return;
        }

        foreach ( $courses as $course ) {
            $birthday = erp_people_get_meta( $course->id, 'date_of_birth', true );
            $current_date = date('Y-m-d');

            if ( $birthday != $current_date ) {
                continue;
            }

            $this->recipient   = $course->email;
            $this->heading     = $this->get_option( 'heading', $this->heading );
            $this->subject     = $this->get_option( 'subject', $this->subject );

            $first_name        = isset( $course->first_name ) ? $course->first_name : '';
            $last_name         = isset( $course->last_name ) ? $course->last_name : '';
            $this->replace = [
                'first-name'      => $first_name,
                'last-name'       => $last_name
            ];

            $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
        }
    }

    /**
     * Get template args
     *
     * @return array
     */
    function get_args() {
        return [
            'email_heading' => $this->get_heading(),
            'email_body'    => wpautop( $this->get_option( 'body' ) )
        ];
    }

}