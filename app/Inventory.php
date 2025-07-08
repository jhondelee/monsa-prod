<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory';

    public function items()
    {
        return $this->belongsTo('App\Item', 'id', 'item_id');
    }

}
