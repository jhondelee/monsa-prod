<?php

namespace App\Factories\Role;

use App\Factories\Role\SetInterface;
use App\Factories\Auth\Factory as AuthFactory;
use App\Factories\User\Factory as UserFactory;
use App\Role;

class Factory implements SetInterface
{
    private $columns;
    
    /**
     *  Initialize Dependencies
     */    
    public function __construct(
                        Role $role, 
                        AuthFactory $auth,
                        UserFactory $user
    ){
        $this->model = $role;      
        $this->auth  = $auth;      
        $this->user  = $user;        
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
        $roleData = $this->model->find($id);
        
        if (! $id) $roleData = $this->model;     
        
        $roleData->level        = $this->model::ACCOUNTING_ROLE_ID;        
        $roleData->name         = $request->name;
        $roleData->display_name = $request->display_name; 
        $roleData->activated    = 1;
        
        // To avoid change top level user             
        if ($id == $this->model::ADMIN_ROLE_ID or 
            $id == $this->model::MANAGER_ROLE_ID or
            $id == $this->model::OPERATION_ROLE_ID) {
                $roleData->level = $id;
        } 
        
        if ($roleData->save()) {   
        
            $roleData->permissions()->detach();
            
            if ($request->has('routes')) {
                if (count($routes) > 0) {                                
                    foreach($routes as $permission_id) {
                        if (! preg_match('/[a-z]/i', $permission_id)) $roleData->permissions()->attach($permission_id);
                    }                      
                }                
            }
            
            return $roleData->id;
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
     *  Get Assign List By User Session
     *  
     *  @return array
       */  
    public function getAssignList()
    {
        $id = $this->user->isNotDirectorId();
        
        $assignList = [];
        
        $employeers = self::getByLevel()
                            ->has('employees')                            
                            ->with(['employees' => function($query) use($id) {
                                if ($id) $query->whereIn('id', $this->user->getDownlineId($id));
                            }])
                            ->get();
                            
        $roleId = $this->auth->getRole()->id;                                                  
        
        foreach($employeers as $role) {
            foreach($role->employees as $employee) {
                $name = $employee->firstname . ' ' . $employee->lastname;
                $assignList[$role->display_name][$employee->user_id] = $name;
                
                if ($role->id == $roleId) break;
            }
        }        
        
        return $assignList;
    }
    
    /**
     *  Get Role List By User Session
     *  
     *  @return array
     */

    public function getRoleList()
    {
        $roleList = [];
        
        $roles = self::getByLevel()->get();
        
        $currentRole = $this->auth->getRole()->id;
        
        foreach($roles as $role) {
            //if ($role->id != $currentRole) $roleList[$role->id] = $role->display_name;
            $roleList[$role->id] = $role->display_name;
        }      
          
        return $roleList;
    }    
    
    /**
     *  Get By Filter Level
     *  
     *  @return Resources
     */
    public function getByLevel()
    {
        $level = $this->auth->getRole()->level;
        
        if ($this->auth->getRole()->id <= $this->model::MANAGER_ROLE_ID) {
            $level -= 1;
        }            
        
        $getData = $this->model->level($level); 

        if ($this->columns) {
            $getData = $this->model
                            ->level($level)
                            ->select($this->columns);
        }
        
        return $getData;
    }

}
