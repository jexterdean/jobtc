<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestCompleted extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'test_id',
        'unique_id',
        'belongs_to'
    ];
    protected $primaryKey = 'id';
    protected $table = 'test_completed';
}
