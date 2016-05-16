<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    protected $fillable = ['comment_id', 'belongs_to', 'unique_id', 'comment', 'user_id'];

    protected $primaryKey = 'comment_id';
    protected $table = 'comment';

}
