<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CondemnItems extends Model
{
    
    protected $table = 'condemn_items';


    public function condemn()
    {
        return $this->belongsTo('App\Condemn', 'id', 'condemn_id');
    }


    public function items()
    {
        return $this->belongsTo('App\Item', 'id', 'item_id');
    }


        public function inventory()
    {
        return $this->belongsTo('App\Inventory', 'id', 'inventory_id');
    }



}
