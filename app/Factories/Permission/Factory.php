<?php

namespace App\Factories\Permission;

use App\Factories\Permission\SetInterface;
use App\Permission;
use App\PermissionGroup;
use DB;

class Factory implements SetInterface
{
    private $columns;
    
    /**
     *  Initialize Dependencies
     */
    public function __construct()
    {
        $this->model = new Permission;     
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
        
        $data->route_name     = $request->route_name;        
        $data->display_name   = $request->display_name;
        $data->display_status = $request->display_status; 
        $data->icon_class     = $request->icon_class; 
        $data->group_id       = $request->group_id; 
        $data->sort           = $request->sort;  

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
    public function setColumns(array $columns)
    {
        $this->columns = $columns;
    }  

    public function getindex()
    {

     $results = DB::select("
        SELECT 
            p.id,
            p.route_name,
            p.display_name,
            p.display_status as isdisplay,
            p.icon_class,
            g.name as groupname,
            p.sort
        FROM permissions p 
        LEFT JOIN permission_group g on p.group_id=g.id
        ORDER BY p.route_name");
        return collect($results);
    
    }      
}
