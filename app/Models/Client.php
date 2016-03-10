<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Client extends Model {

	protected $fillable = ['company_name','contact_person','email','phone','address','zipcode','city','state','country_id'];
	
	protected $primaryKey = 'client_id';
	protected $table = 'fp_client';

}
