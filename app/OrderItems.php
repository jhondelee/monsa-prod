<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    protected $table = 'order_items';

    public function orders()
    {
        return $this->belongsTo('App\Order', 'id', 'order_id');
    }

    public function item()
    {
        return $this->belongsTo('App\Item', 'id', 'item_id');
    }

}
