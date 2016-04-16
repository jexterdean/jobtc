<?php
/**
 * Created by PhpStorm.
 * User: ralph
 * Date: 3/24/16
 * Time: 12:49 AM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{


    protected $fillable = [
        'title','category_id','url','descriptions','tags','comments','task_id'
    ];
    protected $primaryKey = 'id';
    protected $table = 'links';
}