<?php

namespace App\Factories\PermissionGroup;

use App\Factories\PermissionGroup\SetInterface;
use App\PermissionGroup;

class Factory implements SetInterface
{
    private $columns;
    
    /**
     *  Initialize Dependencies
     */
    public function __construct()
    {
        $this->model = new PermissionGroup;        
    }
    
    /**
     *  Find By ID
     *  
     *  @param int $id
     *  @return Resources
     */

    public function find($id)
    {
        return $this->model->find($id);
    }   
    
    /**
     *  Get All
     *  
     *  @return Resources
     */
    public function get()
    {
        $getData = $this->model;
        
        if ($this->columns) {
            $getData = $this->model->select($this->columns);
        }
        
        return $getData;
    }
    
    /**
     *  Insert or Update Request Data
     *  
     *  @param Resources $request
     *  @param int|bool $id
     *  @return int|bool
     */
    public function save($request, $id=false)
    {
        $data = $this->model->find($id);
        
        if (! $id) $data = $this->model;     
        
        $data->name       = $request->name;        
        $data->icon_class = $request->icon_class;
        $data->sort       = $request->sort;  

        if ($data->save()) {
            
            return $data->id;
        }
        
        return false;
    } 

    /**
     *  Set Table Columns
     *  
     *  @param array $columns
     *  @return array
     */
    public function setColumns(Array $columns)
    {
        $this->columns = $columns;
    } 

    /**
     *  Get All Group by Permission
     *  
     *  @return Resources
     */     
    public function getByPermission()
    {
        return $this->model
                    ->has('permission')
                    ->get()
                    ->sortBy('sort');
    }  
}
