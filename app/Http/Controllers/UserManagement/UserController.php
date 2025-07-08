<?php

namespace App\Http\Controllers\UserManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Factories\Role\Factory as RoleFactory;
use App\Factories\User\Factory as UserFactory;
use Yajra\Datatables\Datatables;
use App\Http\Requests\UserRequest;
use App\User;

class UserController extends Controller
{
    /**
     *  Initialized Dependencies and default value
     */
    public function __construct(
                        UserFactory $user, 
                        RoleFactory $role,
                        Datatables $dataTables
    ){                
        $this->user = $user;        
        $this->role = $role;
        $this->dataTables = $dataTables;
        
        $this->data['title'] = 'User Management';
        
        $this->data['views'] = 'pages.user_management.user.';
        
        $this->data['columns'] = [
                            'ID'            => 'id', 
                            'Employee Name' => 'id as name', 
                            'Username'      => 'username', 
                            'Email'         => 'email', 
                            'Role'          => 'id as role',   
                            'Updated At'    => 'updated_at',
                            'Status'        => 'activated as activated_status',
                            'Action'        => 'action'
                        ];                   
    }
    
    /**
     *  View User List
     */
    public function index()
    {
        $title = $this->data['title'];
        $users =$this->user->getindex();

        return view($this->data['views'].'index', compact('users','title'));
    }
    
    /**
     *  API DataTables User List
     */
    public function dataTable()
    {
        unset($this->data['columns']['Action']);
        
        $this->user->setColumns($this->data['columns']);
        
        $getData = $this->user->getByLevel()->get();
        
        return $this->dataTables::of($getData)->addColumn('action', function($getData) {
                                        return $getData->id;
                                    })->make(true);
    }
    
    /**
     *  Add New User
     */    
    public function add()
    {
        $this->data['assignList'] = $this->role->getAssignList(true);
        $this->data['roleList']   = $this->role->getRoleList();
        
        return view($this->data['views'].'create', $this->data);
    }
    
    /**
     *  Submit New User
     */    
    public function doAdd(UserRequest $request)
    {

        if ($this->user->save($request));

           return redirect()->route('user.index')

            ->with('success','User created successfully!');
        
        return response(__('app.form.failed_create'), 400);
    }
    
    /**
     *  Edit User
     *  
     *  @param int $id User ID
     */    
    public function edit($id)
    {
        $this->data['assignList'] = $this->role->getAssignList(true);
        $this->data['roleList']   = $this->role->getRoleList();
        
        $this->data['user'] = $this->user->find($id);

        return view($this->data['views'].'edit', $this->data);
    }
    
    /**
     *  Submit Edit User
     *  
     *  @param int $id User ID
     */    
    public function doEdit($id, UserRequest $request)
    {
        if ($this->user->save($request, $id));

        return redirect()->route('user.index')

            ->with('success','User updated successfully!.');
        
        return response(__('app.form.failed_update'), 400);    
    }
    
    /**
     *  Soft delete user
     */
    public function doDelete(Request $request, $id)
    {
        if(!$id){
            if ($request->method() == 'GET') {
            
                $this->data['user'] = $this->user->find($id); 

                $this->user->delete($id);   

                return view($this->data['views'].'index', $this->data)->with('success','User deleted successfully!.');            
            } 

            if ($this->user->delete($id));  

                return redirect()->route('user.index')

                    ->with('success','User deleted successfully!.');
                
                return response(__('app.form.failed_delete'), 400);

        }else{

            return redirect()->route('user.index')

                    ->with('success','Soft delete successfully!.');
        }
    }
}
