<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Company extends Model
{

    //protected $fillable = ['company_name', 'contact_person', 'email', 'phone', 'address', 'zipcode', 'city', 'state', 'country_id'];
    protected $fillable = [ 'name',  'email', 'phone','number_of_employees','address_1','address_2','province','zipcode','website','country_id'];

    protected $primaryKey = 'id';
    protected $table = 'companies';

    public function profile() {
        return $this->hasOne('App\Models\Profile');
    }
}
