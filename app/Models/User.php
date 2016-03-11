<?php

namespace App\Models;


use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Model implements
    AuthenticatableContract,
    CanResetPasswordContract
{

    use EntrustUserTrait,Authenticatable,  CanResetPassword;

    protected $fillable = ['username', 'password', 'client_id', 'name',
        'email', 'phone', 'user_status', 'user_status_detail', 'user_avatar'];

    protected $primaryKey = 'user_id';
    protected $table = 'fp_user';

    protected $hidden = array('password', 'remember_token');

}
