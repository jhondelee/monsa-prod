<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesPayment extends Model
{
    //sales_payment
    protected $table = 'sales_payment'; 


    public function sales_order()
    {
        return $this->belongsTo('App\SalesOrder', 'id', 'sales_order_id');
    }
}
