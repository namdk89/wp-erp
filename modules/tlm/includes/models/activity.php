<?php
namespace WeDevs\ERP\TLM\Models;

use WeDevs\ERP\Framework\Model;

/**
 * Class Dependents
 *
 * @package WeDevs\ERP\HRM\Models
 */
class Activity extends Model {
    protected $table = 'erp_tlm_course_activities';

    protected $fillable = [ 'user_id', 'type', 'message', 'email_subject', 'log_type', 'start_date', 'end_date', 'sent_notification', 'created_by', 'extra', 'created_at' ];

    public $timestamps = true;

    public function created_by() {
        return $this->belongsTo( '\WeDevs\ORM\WP\User', 'created_by');
    }

    public function course() {
        return $this->belongsTo( '\WeDevs\ERP\Framework\Models\People', 'user_id' );
    }

    public static function scopeSchedules( $query ) {
        return $query->where( 'start_date', '>', current_time( 'mysql' ) )
                    ->where( 'type', 'log_activity' )
                    ->where( 'sent_notification', false );
    }
}
