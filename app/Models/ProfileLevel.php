<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileLevel extends Model
{
    protected $fillable = ['profile_id','profile_ids_above','profile_ids_equal','profile_ids_below'];

    protected $primaryKey = 'id';
    protected $table = 'profile_levels';
    
}
