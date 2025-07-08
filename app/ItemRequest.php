<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemRequest extends Model
{
    protected $table = 'item_request';  

    public function inventory()
    {
        return $this->belongsTo('App\Inventory', 'id', 'inventory_id');
    }

    public function unit_of_measure()
    {
        return $this->belongsTo('App\Item', 'id', 'item_id');
    }
}
