<?php

namespace App\Models;


use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Bican\Roles\Traits\HasRoleAndPermission;
use Bican\Roles\Contracts\HasRoleAndPermission as HasRoleAndPermissionContract;

class User extends Model implements
    AuthenticatableContract,
    CanResetPasswordContract,
    HasRoleAndPermissionContract
{

    use Authenticatable,  CanResetPassword,HasRoleAndPermission;
    
    protected $connection = 'mysql_jobtc';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email','user_type','first_name','last_name' , 'password'];
    
    /*protected $fillable = ['username', 'password', 'client_id', 'name',
        'email', 'phone', 'user_status', 'user_status_detail', 'user_avatar'];*/

    //protected $primaryKey = 'user_id';
    //protected $table = 'user';
    protected $table = 'users';

    protected $hidden = array('password', 'remember_token');

}
