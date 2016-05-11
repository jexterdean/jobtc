<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Company extends Model
{

    //protected $fillable = ['company_name', 'contact_person', 'email', 'phone', 'address', 'zipcode', 'city', 'state', 'country_id'];
    protected $fillable = [ 'name',  'email', 'phone', 'address', 'country'];

    protected $primaryKey = 'id';
    protected $table = 'companies';

}
