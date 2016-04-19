<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;


class Client extends Model implements AuthenticatableContract, CanResetPasswordContract
{

    use Authenticatable, CanResetPassword;
    
    protected $connection = 'mysql_jobtc';
    
    protected $table = 'clients';
    
    protected $fillable = ['id','company_name', 'contact_person', 'email', 'phone', 'address', 'zipcode', 'city', 'state', 'country_id','password','remember_token'];

    //protected $primaryKey = 'client_id';
    

}
