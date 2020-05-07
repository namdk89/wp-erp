<?php
namespace WeDevs\ERP\TLM;

use WeDevs\ERP\Framework\Traits\Hooker;

/**
 * HR Email handler class
 */
class Emailer {

    use Hooker;

    function __construct() {
        $this->filter( 'erp_email_classes', 'register_emails' );
    }

    function register_emails( $emails ) {

        $emails['New_Task_Assigned']        = new Emails\New_Task_Assigned();
        $emails['New_Course_Assigned']     = new Emails\New_Course_Assigned();
        $emails['Birthday_Greetings']       = new Emails\Birthday_Greetings();

        return apply_filters( 'erp_tlm_email_classes', $emails );
    }
}
