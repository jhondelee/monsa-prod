<?php

namespace App\Factories\User;

interface SetInterface {
    
    public function find($id);
    
    public function get();
    
    public function setColumns(array $columns);
    
    public function getByLevel();

    public function getindex();
    
    public function save($request, $id);
    
    public function delete($id);

    public function getemp();

    public function employee($id);

}
