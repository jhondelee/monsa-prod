<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Employee;
use DB;

class User extends Authenticatable
{
    const ADMIN_USER_ID = 1;
    
    const MANAGER_USER_ID = 2;
    
    const ACTIVE_USER = 1;
    
    const INACTIVE_USER = 0;
    
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id', 'hash_id', 'email', 'username', 'password', 'activated', 'created_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function roles()
    {
        return $this->belongsToMany('App\Role', 'user_role');
    }
    
    public function employees()
    {
        return $this->hasOne('App\Employee');
    } 
    
    public function sales_areas()
    {
        return $this->belongsToMany('App\SalesArea', 'employee_sales_area', 'employee_id', 'sales_area_id');
    }    
    
    public function employee_devices()
    {
        return $this->hasOne('App\EmployeeDevice', 'employee_id');
    }
    
    public function getCreatedAtAttribute($value)
    {
        return dateFormat($value, 8);
    }
    
    public function getUpdatedAtAttribute($value)
    {
        return dateFormat($value, 8);
    }    
    
    public function getActivatedStatusAttribute($value)
    {
        return $value ? 'Yes' : 'No';
    }    
    
    public function getJoApproverStatusAttribute($value)
    {
        return $value ? 'Yes' : 'No';
    }
    
    public function getRoleAttribute($value)
    {
        return self::find($value)->roles()->select('display_name')->first()->display_name;
    }
    
    public function getParentAttribute($value)
    {
        $parentUser = Employee::select('firstname', 'lastname')->whereUserId($value)->first();
        return $parentUser->firstname . ' ' . $parentUser->lastname;
    }
    
    public function getCreatedbyAttribute($value)
    {
        $parentUser = Employee::select('firstname', 'lastname')->whereUserId($value)->first();
        return $parentUser->firstname . ' ' . $parentUser->lastname;
    }

    public function getemplist()
    {
       $results = DB::select("
        SELECT 
            u.id,
            concat(e.firstname,' ',e.lastname) as emp_name,
            e.position,
            e.user_id
        FROM employees e
        inner join users u
        on e.user_id = u.id
        where u.activated=1;");
        return collect($results);
    }
    
    public function hasRoleLevel($level=false)
    {
        return self::whereHas('roles', function($query) use($level) {
                             $query->level($level); 
                        });
    }
}
