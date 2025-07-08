<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    public function supplier()
    {
        return $this->belongsTo('App\Supplier', 'id', 'supplier_id');
    }

}
