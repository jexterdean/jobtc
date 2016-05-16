<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'title'
    ];
    protected $primaryKey = 'id';
    protected $table = 'team';
}
