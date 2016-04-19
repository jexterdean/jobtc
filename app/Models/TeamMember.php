<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'created_by',
        'project_id',
        'user_id'
    ];
    protected $primaryKey = 'id';
    protected $table = 'team_member';
}
