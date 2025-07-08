<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierItems extends Model
{
    protected $table = 'supplier_items';
    protected $fillable = [
    	'supplier_id',
    	'item_id'
    ];

    public $timestamps = false;

}
