<?php

namespace App\Factories\Permission;

interface SetInterface {
    
    public function find($id);   
    
    public function get();
    
    public function save($request, $id);

    public function getindex();
    
    public function setColumns(Array $columns);    
}
