<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PermissionGroup;
class Permission extends Model
{
    public function roles()
    {
        return $this->belongsToMany('App\Role', 'role_permission');
    }
    
    public function groups()
    {
        return $this->hasOne('App\PermissionGroup', 'id', 'group_id');
    }
    
    public function getGroupnameAttribute($value)
    {
        return $value ? PermissionGroup::select('name')->find($value)->name : '';
    }
    
    public function getIsdisplayAttribute($value)
    {
        return $value ? 'Yes' : 'No';
    }
    
}
