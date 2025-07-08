<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesOrderItem extends Model
{
	protected $table = 'sales_order_items'; 

    public $timestamps = false;

    public function sales_ordet()
    {
        return $this->belongsTo('App\SalesOrder', 'id', 'sales_order_id');
    }
}
