<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskCheckListPermission extends Model
{
     //

    protected $fillable = [
        'task_id',
        'user_id'
    ];

    protected $primaryKey = 'id';
    protected $table = 'task_check_list_permissions';
    
    
    public function tasklist() {
        return $this->belongsTo('App\Models\TaskCheckList');
    }
}
