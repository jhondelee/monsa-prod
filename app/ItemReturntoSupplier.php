<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemReturntoSupplier extends Model
{
    protected $table = 'return_to_supplier';  

    public $timestamps = false;

    public function supplier()
    {
        return $this->belongsTo('App\Supplier', 'id', 'supplier_id');
    }

    public function item()
    {
        return $this->belongsTo('App\Item', 'id', 'item_id');
    }
}
