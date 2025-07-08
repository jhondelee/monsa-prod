<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incoming extends Model
{
    protected $table = 'incomings';

    public function orders()
    {
        return $this->belongsTo('App\Order', 'id', 'order_id');
    }

}
