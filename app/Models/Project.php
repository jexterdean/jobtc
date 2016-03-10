<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Project extends Model {

	protected $fillable = ['client_id','ref_no','project_title','start_date','deadline','project_descrption','rate_type','rate_value','project_progress'];
	
	protected $primaryKey = 'project_id';
	protected $table = 'fp_project';

}
