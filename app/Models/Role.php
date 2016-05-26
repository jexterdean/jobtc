<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
     protected $fillable = ['company_id','company_division_id','name','slug','description','level'];

    protected $primaryKey = 'id';
    protected $table = 'roles';
    
    public function company() {
        return $this->belongsTo('App\Models\Company');
    }
    
}
