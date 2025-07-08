<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
     public function users()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    
    public function roles()
    {
        return $this->belongsToMany('App\Role', 'user_role', 'user_id', 'role_id');
    }
    
    public function getNameAttribute($value)
    {
        $name = self::select('firstname', 'lastname')->find($value);
        if (isset($name)) 
            return $name->firstname . ' ' . $name->lastname;
    }
    
    public function hasRoleLevel($level=false)
    {
        return self::whereHas('roles', function($query) use($level) {
                             $query->level($level); 
                        });
    } 
}
