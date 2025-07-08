<?php

namespace App\Factories\User;

use App\Factories\User\SetInterface;
use App\Factories\Auth\Factory as AuthFactory;
use App\Employee;
use App\User;
use DB;

class Factory implements SetInterface
{
    private $columns;
    
    /**
     *  Initialize Dependencies
     */    
    public function __construct(
                        User $user, 
                        Employee $employee, 
                        AuthFactory $auth
    ){
        $this->model    = $user;
        $this->employee = $employee;
        $this->auth     = $auth;
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
    
    public function getDownlineId($parent_id)
    {
        $ids[] = $parent_id;        
        $getIds[] = $parent_id;
        
        while (true) {
            $getData = $this->model->whereIn('parent_id', $ids)->select('id')->get();            
            if ($getData->count() < 1) break; 
            
            $new_ids = [];
            foreach($getData as $user) {
                $new_ids[] = $user->id;
                $getIds[] = $user->id;
            }
            
            $ids = $new_ids;            
        }
        
        return $getIds;
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
    
    /**
     *  Check if Id is Director's Id
     *  
     *  @param int|bool $id
     *  @return int|bool
     */
    public function isNotDirectorId($id=false)
    {
        $id = $id ? $id : $this->auth->getUser()->id;
        
        if ($id > $this->model::ADMIN_USER_ID) {
            return $id;
        }
        
        return false;
    }
    
    /**
     *  Get By Filter Level
     *  
     *  @return Resources
     */
    public function getByLevel()
    {
        $id = self::isNotDirectorId();
        
        $getData = $this->model->hasRoleLevel();        
            
        if ($this->columns) {
            $getData = $this->model
                            ->hasRoleLevel()
                            ->select($this->columns);
        }
        
        if ($id) return $getData->whereIn('id', self::getDownlineId($id));
        
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
        $authUser = $this->auth->getUser();
        
        $userData = $this->find($id);
        
        if (! $id) {
            $userData = $this->model;
            $userData->created_by = $authUser->id;            
        }              
        
        $userData->hash_id    = '1';
        $userData->parent_id  = $authUser->id;
        //$userData->name       = $request->name;        
        $userData->email      = $request->email;
        $userData->username   = $request->username;
        $userData->activated  = $request->activated;        
        $userData->updated_by = $authUser->id;
        
        if ($request->has('password')) $userData->password = bcrypt($request->password);
        

        $userData->save();
        
        if ($userData->save()) {
            
            $employeeData = $userData->employees;
            
            if (! $id) $employeeData = $this->employee;
  
            $employeeData->emp_number    = $request->emp_number;  
            $employeeData->firstname     = $request->firstname;  
            $employeeData->lastname      = $request->lastname;  
            $employeeData->middlename    = $request->middlename; 
            $employeeData->department    = $request->has('department')?$request->department:'';  
            $employeeData->position      = $request->position;                          
            $employeeData->status        = $request->has('status')?1:0;
            
            if ($userData->employees()->save($employeeData)) {
                $userData->roles()->detach();
                $userData->roles()->attach($request->role_id);
                
                return $userData->id;
            }
        }
        
        
        return false;
    }
    /**
     *  Disply Data
     *  
     *  @param int|bool $id
     *  @return int|null
     */    
    public function getindex()
    {
       $results = DB::select("
        SELECT 
            u.id,
            concat(e.firstname,' ',e.lastname) as name,
            u.username,
            u.email,
            r.display_name as role,
            u.updated_at,
            u.activated as activated_status,
            e.position
        from users u
        inner join employees e
        on u.id = e.user_id
        inner join user_role ur
        on u.id= ur.user_id
        inner join roles r
        on ur.role_id = r.id;");
        return collect($results);
    } 

     public function getemp()
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

     public function employee($id)
    {
       $results = DB::select("
        SELECT 
            concat(e.firstname,' ',e.lastname) as emp_name
        FROM employees e
        inner join users u
        on e.user_id = u.id
        where u.id=?",[$id]);
        return collect($results);
    } 

    /**
     *  Delete Request Data
     *  
     *  @param int|bool $id
     *  @return int|null
     */    
    public function delete($id)
    {
 
        return $this->find($id)->delete();
    }   

}

