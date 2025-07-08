<?php

namespace App;	

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Item extends Model
{

	use SoftDeletes;

	protected $table = 'items';  

	 public function unit_of_measure()
    {
        return $this->belongsTo('App\UnitOfMeasure', 'id', 'unit_id');
    }
    
}
