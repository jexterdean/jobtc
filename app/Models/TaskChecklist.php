<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskChecklist extends Model
{
    //

    protected $fillable = [
        'id',
        'task_id',
        'user_id',
        'checklist',
        'status'
    ];

    protected $primaryKey = 'id';
    protected $table = 'task_check_list';
}
