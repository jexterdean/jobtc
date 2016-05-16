<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'team_id',
        'user_id'
    ];
    protected $primaryKey = 'id';
    protected $table = 'team_member';
    
    public function team() {
        return $this->belongsToMany('App\Models\Team');
    }
    
    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
