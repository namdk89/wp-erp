<?php
namespace WeDevs\ERP\TLM\CLI;

/**
 * TLM CLI class
 */
class Commands extends \WP_CLI_Command {

    public function delete( $args, $assoc_args ) {
        global $wpdb;

        // truncate table
        $tables = [ 'erp_peoples', 'erp_peoplemeta', 'erp_people_type_relations', 'erp_tlm_course_activities', 'erp_tlm_course_subscriber' ];

        if ( in_array( 'with-groups', $assoc_args ) ) {
            $tables[] = 'erp_tlm_course_group';
        }

        foreach ( $tables as $table ) {
            $wpdb->query( 'TRUNCATE TABLE ' . $wpdb->prefix . $table );
        }

        \WP_CLI::success( "Tables deleted successfully!" );
    }

}

\WP_CLI::add_command( 'tlm', 'WeDevs\ERP\TLM\CLI\Commands' );
