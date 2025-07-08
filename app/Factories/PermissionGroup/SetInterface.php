<?php

namespace App\Factories\PermissionGroup;

interface SetInterface {
      
    public function find($id);   
    
    public function get();
    
    public function save($request, $id=false);
    
    public function setColumns(Array $columns);
    
    public function getByPermission();
}
