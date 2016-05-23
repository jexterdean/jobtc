<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'applicant_tags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = ['user_id','applicant_id','job_id','status'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    //protected $hidden = ['password', 'remember_token'];

     public function user() {
        return $this->hasOne('App\Models\User','id','user_id');
    }
}
