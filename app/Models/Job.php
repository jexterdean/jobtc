<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'jobs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = ['user_id','company_id','title','description','photo'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    //protected $hidden = ['password', 'remember_token'];

     public function user() {
        return $this->belongsTo('App\Models\User');
    }
    
    public function applicants() {
        return $this->hasMany('App\Models\Applicant');
    }
}
