<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesPaymentTerm extends Model
{
    //sales_payment_terms
    protected $table = 'sales_payment_terms'; 

    public $timestamps = false;

    public function sales_payment()
    {
        return $this->belongsTo('App\SalesPayment', 'id', 'sale_payment_id');
    }

    public function mode_of_payment()
    {
        return $this->belongsTo('App\ModeOfPayment', 'id', 'payment_mode_id');
    }
}
