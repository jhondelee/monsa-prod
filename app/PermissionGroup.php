<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    protected $table = 'permission_group';   
    
    public $timestamps = false;
    
    public function permission()
    {
        return $this->belongsTo('App\Permission', 'id', 'group_id');
    }
}
