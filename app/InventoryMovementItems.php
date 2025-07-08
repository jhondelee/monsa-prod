<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryMovementItems extends Model
{
    protected $table = 'inventory_movement_items';

    public function items()
    {
        return $this->belongsTo('App\Item', 'id', 'item_id');
    }

    public function inventory()
    {
        return $this->belongsTo('App\Iventory', 'id', 'inventory_id');
    }

    public function inventory_movement()
    {
        return $this->belongsTo('App\InventoryMovement', 'id', 'inventory_movement_id');
    }

}
