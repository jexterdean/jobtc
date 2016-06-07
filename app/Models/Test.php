<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'id',
        'user_id',
        'title',
        'description',
        'length',
        'version',
        'average_score',
        'test_photo',
        'start_message',
        'completion_message',
        'order',
        'completion_image',
        'completion_sound',
        'default_tags'
    ];
    protected $primaryKey = 'id';
    protected $table = 'test';
}
