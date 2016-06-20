<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestCommunity extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'test_id',
        'order'
    ];
    protected $primaryKey = 'id';
    protected $table = 'test_community';
}
