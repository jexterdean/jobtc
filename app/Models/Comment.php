<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {

	protected $fillable = ['comment_id','belongs_to','unique_id','comment','username'];
	
	protected $primaryKey = 'comment_id';
	protected $table = 'fp_comment';

}
