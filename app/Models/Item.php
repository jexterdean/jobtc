<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Item extends Model
{

    protected $fillable = ['item_id', 'billing_id', 'item_name', 'item_quantity', 'unit_price', 'item_descrption'];
    protected $primaryKey = 'item_id';
    protected $table = 'fp_item';

}
