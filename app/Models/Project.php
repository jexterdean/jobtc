<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Project extends Model
{

    protected $fillable = ['company_id',
                           'user_id',
                           'ref_no', 
                           'project_title', 
                           'start_date', 
                           'deadline', 
                           'project_description', 
                           'rate_type', 
                           'rate_value', 
                           'project_progress'];

    protected $primaryKey = 'project_id';
    protected $table = 'project';

    public function team_member() {
        return $this->HasMany('App\Models\TeamMember','user_id');
    }
    
    public function team_project() {
        return $this->HasMany('App\Models\TeamProject','project_id');
    }
}
