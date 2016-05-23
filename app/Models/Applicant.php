<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Applicant extends Model implements AuthenticatableContract, CanResetPasswordContract{
    
    use Authenticatable, CanResetPassword;
    
    protected $connection = 'mysql';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'applicants';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = ['job_id','name','email','phone','resume','photo','password','remember_token'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    //protected $hidden = ['password', 'remember_token'];

     public function user() {
        return $this->belongsTo('App\Models\User');
    }
    
    public function job() {
        return $this->belongsTo('App\Models\Job');
    }
    
    public function status() {
        return $this->hasMany('App\Models\Status','applicant_id','id');
    }
    
    /*public function comment() {
        return $this->hasMany('App\Comment','applicant_id','id');
    }*/
}

