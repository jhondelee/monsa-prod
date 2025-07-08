<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';

    public function area()
    {
        return $this->belongsTo('App\Area', 'id', 'area_id');
    }

}
