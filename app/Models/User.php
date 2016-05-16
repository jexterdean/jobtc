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

    protected $fillable = [
        'username',
        'password',
        'client_id',
        'accounts_id',
        'name',
        'email',
        'phone',
        'user_status',
        'user_status_detail',
        'user_avatar',

        'timezone_id',
        'ticketit_admin',
        'ticketit_agent',
    ];

    protected $primaryKey = 'user_id';
    protected $table = 'user';

    protected $hidden = array('password', 'remember_token');

}
