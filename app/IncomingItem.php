<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncomingItem extends Model
{
    protected $table = 'incoming_items';

    public function incomings()
    {
        return $this->belongsTo('App\Incoming', 'id', 'incoming_id');
    }

    public function items()
    {
        return $this->belongsTo('App\Item', 'id', 'item_id');
    }

}
