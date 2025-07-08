<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Factories\auth\Factory as AuthFactory;

class Role extends Model
{
    const ADMIN_ROLE_ID = 1;
    
    const MANAGER_ROLE_ID = 2;

    const OPERATION_ROLE_ID = 3;

    const ACCOUNTING_ROLE_ID = 4;

    
     public function users()
    {
        return $this->belongsToMany('App\User', 'user_role');        
    }
    
    public function employees()
    {
        return $this->belongsToMany('App\Employee', 'user_role', 'role_id', 'user_id');
    }
    
    public function permissions()
    {
        return $this->belongsToMany('App\Permission', 'role_permission');
    }
    
    public function scopeLevel($query, $level=false)
    {
        $auth = new AuthFactory;
        
        $level = $level ? $level : $auth->getRole()->level;
        
        return $query->where('level', '>=', $level);
    }

    public function hasPermissionById($id)
    {
        $permission = $this->permissions()->find($id);
        
        if (! is_null($permission)) return true;
        
        return false;
    }
    
}
