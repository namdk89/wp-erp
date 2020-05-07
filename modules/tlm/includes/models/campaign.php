<?php
namespace WeDevs\ERP\TLM\Models;

use WeDevs\ERP\Framework\Model;

/**
 * Class Dependents
 *
 * @package WeDevs\ERP\HRM\Models
 */
class Campaign extends Model {
    protected $table = 'erp_tlm_campaigns';

    protected $fillable = [ 'title', 'description' ];

    public $timestamps = true;

    /**
     * Set pivot relation with erp_tlm_campign_group table
     *
     * @since 1.0
     *
     * @return [type] [description]
     */
    public function groups() {
        return $this->belongsToMany( '\WeDevs\ERP\TLM\Models\CourseGroup', $this->getConnection()->db->prefix . 'erp_tlm_campaign_group', 'campaign_id', 'group_id' );
    }

}