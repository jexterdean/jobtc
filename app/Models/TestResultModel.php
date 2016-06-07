<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestResultModel extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'test_id',
        'question_id',
        'belongs_to',
        'unique_id',
        'answer',
        'result'
    ];
    protected $primaryKey = 'id';
    protected $table = 'test_result';
}
