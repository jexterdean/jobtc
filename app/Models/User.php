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

    protected $fillable = ['email', 'password' ,'name','phone', 'photo' ,'user_status'];

    protected $primaryKey = 'user_id';
    protected $table = 'user';

    protected $hidden = array('password', 'remember_token');
    
    public function profile() {
        return $this->hasMany('App\Models\Profile');
    }
}
