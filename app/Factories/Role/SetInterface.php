<?php

namespace App\Factories\Role;

interface SetInterface {
    
    public function find($id);
    
    public function get();
    
    public function save($request, $id);
    
    public function setColumns(Array $columns);

    public function getAssignList();
    
    public function getRoleList();
}

