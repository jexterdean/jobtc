<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoRoom extends Model
{
     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'video_rooms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['room_name','room_type','streams'];
    
    public function video_tags() {
        return $this->hasOne('App\Models\VideoTag');
    }
    
    public function tags() {
        return $this->hasOne('App\Models\Tag','unique_id');
    }
}
