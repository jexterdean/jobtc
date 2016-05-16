<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Project extends Model
{

    protected $fillable = ['company_id', 'ref_no', 'project_title', 'start_date', 'deadline', 'project_description', 'rate_type', 'rate_value', 'project_progress'];

    protected $primaryKey = 'project_id';
    protected $table = 'project';

    
    public function team_project() {
        return $this->HasOne('App\Models\TeamProject');
    }
}
