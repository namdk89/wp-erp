<?php
namespace WeDevs\ERP\TLM\Emails;

use WeDevs\ERP\Email;
use WeDevs\ERP\Framework\Traits\Hooker;

/**
 * New Course Assigned
 */
class New_Course_Assigned extends Email {

    use Hooker;

    function __construct() {
        $this->id          = 'new-course-assigned';
        $this->title       = __( 'New Course Assigned', 'erp' );
        $this->description = __( 'New course assigned notification to employee.', 'erp' );

        $this->subject     = __( 'New course has been assigned to you', 'erp');
        $this->heading     = __( 'New Course Assigned', 'erp');

        $this->find = [
            'employee_name' => '{employee_name}',
            'course_name'    => '{course_name}',
            'created_by'    => '{created_by}',
        ];

        $this->action( 'erp_admin_field_' . $this->id . '_help_texts', 'replace_keys' );

        parent::__construct();
    }

    function get_args() {
        return [
            'email_heading' => $this->heading,
            'email_body'    => wpautop( $this->get_option( 'body' ) ),
        ];
    }

    public function trigger( $course_id ) {
        global $current_user;

        $course = erp_get_people( $course_id );

        if ( ! $course ) {
            return;
        }
        $last_name = isset( $course->last_name ) ? $course->last_name : '';
        $course_full_name = $course->last_name . ' ' . $first_name;
        $this->heading = $this->get_option( 'heading', $this->heading );
        $this->subject = $this->get_option( 'subject', $this->subject );

        $employee = new \WeDevs\ERP\HRM\Employee( intval( $course->course_owner ) );

        $this->recipient    = $employee->user_email;
        $this->replace = [
            'employee_name' => $employee->get_full_name(),
            'course_name'  => $course_full_name,
            'created_by'    => $current_user->display_name,
        ];

        if ( $employee ) {
            $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
        }
    }

}
