<?php

namespace App\Factories\Auth;

interface SetInterface {
    
    public function getPermissionList();
    
    public function getUser();

    public function getEmployee();

    public function getEmployeeName();
    
    public function getRole();


}
