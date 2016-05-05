<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamProject extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'team_id',
        'project_id'
    ];
    protected $primaryKey = 'id';
    protected $table = 'team_project';
}
