<?php

namespace App\Factories\Auth;

use App\Factories\Auth\SetInterface;

class Factory implements SetInterface
{   
    /** 
     *  Get Assinged Navigation List
     *  
     *  @return App\Permission
     */
    public function getPermissionList()
    {
        return auth()->user()
                     ->roles()
                     ->first()
                     ->permissions()
                     ->get()
                     ->where('display_status', 1)
                     ->sortBy('sort');
    }
    
    /** 
     *  Get Authenticated User Details
     *  
     *  @return App\User
     */
    public function getUser()
    {
        return auth()->user();
    }
    
    /** 
     *  Get Authenticated Employee Details
     *  
     *  @return App\Employee
     */
    public function getEmployee()
    {
        return auth()->user()
                     ->employees()
                     ->first();
    }
    
    /** 
     *  Get Authenticated Employee Details
     *  
     *  @return App\Employee
     */
    public function getEmployeeName()
    {
        return $this->getEmployee()->firstname . ' ' . $this->getEmployee()->lastname;
    }
    
    /** 
     *  Get Authenticated Role Details
     *  
     *  @return App\Role
     */    
    public function getRole()
    {
        return auth()->user()
                     ->roles()
                     ->first();
    }    

}
