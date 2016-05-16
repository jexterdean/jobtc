<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyDivision extends Model
{
    protected $fillable = [ 'name',  'email', 'phone', 'address', 'country'];

    protected $primaryKey = 'id';
    protected $table = 'company_divisions';
}
