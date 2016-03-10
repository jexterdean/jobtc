<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timer extends Model {

	protected $fillable = ['timer_id','username','project_id','start_time','end_time'];
	
	protected $primaryKey = 'timer_id';
	protected $table = 'fp_timer';

}
