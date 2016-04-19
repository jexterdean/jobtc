<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskChecklist extends Model
{
    //

    protected $fillable = [
        'task_id',
        'user_id',
        'checklist',
        'is_finished'
    ];

    protected $primaryKey = 'id';
    protected $table = 'task_check_list';
}
