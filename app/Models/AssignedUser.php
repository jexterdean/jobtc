<?php
/**
 * Created by PhpStorm.
 * User: ralph
 * Date: 3/10/16
 * Time: 10:49 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignedUser extends Model
{

    protected $fillable = ['id', 'belongs_to', 'unique_id', 'username'];
    protected $primaryKey = 'id';
    protected $table = 'assigned_user';

}