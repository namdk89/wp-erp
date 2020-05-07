<?php
namespace WeDevs\ERP\TLM\Models;

use WeDevs\ERP\Framework\Model;
use WeDevs\ERP\Framework\Models\People;

class Course extends People {

    public function tags(){
        global $wpdb;
       return $this->belongsToMany('WeDevs\ERP\TLM\Models\TLMTag', "{$wpdb->prefix}erp_tlm_course_tag", 'course_id', 'tag_id')->withTimestamps();
    }

}
