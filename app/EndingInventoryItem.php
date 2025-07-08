<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EndingInventoryItem extends Model
{
    protected $table = 'ending_inventory_items';

    public function ending_inventory()
    {
        return $this->belongsTo('App\EndingInventory', 'id', 'ending_inventory_id');
    }

    public function items()
    {
        return $this->belongsTo('App\Item', 'id', 'item_id');
    }
}
