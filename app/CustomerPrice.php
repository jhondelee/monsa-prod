<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerPrice extends Model
{
    protected $table = 'customer_prices';


    public function customer()
    {
        return $this->belongsTo('App\Customer', 'id', 'customer_id');
    }


    public function items()
    {
        return $this->belongsTo('App\Item', 'id', 'item_id');
    }



}
