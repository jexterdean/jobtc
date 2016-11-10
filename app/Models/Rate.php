<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $fillable = ['profile_id', 'rate_type', 'rate_value', 'currency'];

    protected $primaryKey = 'id';
    protected $table = 'rates';
}
