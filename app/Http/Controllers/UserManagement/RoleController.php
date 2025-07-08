<?php

namespace App\Http\Controllers\UserManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use App\Http\Requests\RoleRequest;
use App\Factories\Role\Factory as RoleFactory;
use App\Factories\Permission\Factory as PermissionFactory;
use Illuminate\Support\Facades\Input;
use App\Role;
use App\Permission;
use App\User;
use App\RolePermission;
use DB;
use Route;
    

class RoleController extends Controller
{
    /**
     *  Initialized Dependencies and default value
     */
    public function __construct(
                RoleFactory $role, 
                PermissionFactory $permission,
                Datatables $dataTables
    ){                  
        $this->role = $role;
        $this->permission = $permission;
        $this->dataTables = $dataTables;
        
        $this->data['title'] = 'User Management';
        
        $this->data['views'] = 'pages.user_management.role.';
        
        $this->data['columns'] = [
                            'ID'           => 'id', 
                            'Display Name' => 'display_name',                            
                            'Role Name'    => 'name',                                                       
                            'Action'       => 'action'
                        ];                   
    }
    
    /**
     *  View Role List
     */
    public function index()
    {

        $title = $this->data['title'];
        $roles = Role::all();
        $routes = DB::table('permissions')->select('id','route_name')->orderBy('route_name')->get();

        return view($this->data['views'].'index', compact('roles','routes','title'));
    }
    
    /**
     *  API DataTables Role List
     */
    public function dataTable()
    {
        unset($this->data['columns']['Action']);
        
        $this->role->setColumns($this->data['columns']);
        
        $getData = $this->role->getByLevel()->get();
        
        return $this->dataTables::of($getData)->addColumn('action', function($getData) {
                                        return $getData->id;
                                    })->make(true);
    }
    
    /**
     *  Add New Role
     */    
    public function add()
    {
        $routes = Permission::all();
 
        return view($this->data['views'].'create',['routes'=>$routes]);
    }
    
    /**
     *  Submit New Role
     */    
    public function doAdd(RoleRequest $request)
    {
        $roleData  = New Role;
        
        $roleData->level        = Role::ACCOUNTING_ROLE_ID;        
        $roleData->name         = $request->name;
        $roleData->display_name = $request->display_name; 
        $roleData->activated    = 1;

        $roleData->save();
        
        $id =  $roleData->id;

        // To avoid change top level user             
        if ($id == Role::ADMIN_ROLE_ID or $id == Role::MANAGER_ROLE_ID  
            or $id == Role::OPERATION_ROLE_ID or $id == Role::ACCOUNTING_ROLE_ID ) {
                $roleData->level = $id;
        } 

        if ($request->has('routes')){
             $routes = Input::get('routes');
                if (count($routes) > 0){
                     foreach($routes as $permission){

                            $rolepermission                 =  New RolePermission;
                            $rolepermission->role_id        =  $roleData->level;
                            $rolepermission->permission_id   =  $permission;
                            $rolepermission->save();

                     }
            }
        }

         return redirect()->route('role.index')->with('success','Role has been saved successfully.');

    }
    
    /**
     *  Edit Role
     *  
     *  @param int $id Role ID
     */    
    public function edit($id)
    {
        $role = $this->role->find($id);

        $routes = Permission::all();

        $permissions = DB::table('role_permission')->select('permission_id')->where('role_id',$id)->get();

        return view($this->data['views'].'edit',compact('role','routes','permissions'));
    }
    
    /**
     *  Submit Edit Role
     *  
     *  @param int $id Role ID
     */    
    public function doEdit($id, RoleRequest $request)
    {
       $roleData  = Role::findOrfail($id);
                    
        if ($id == Role::ADMIN_ROLE_ID or $id == Role::MANAGER_ROLE_ID  
            or $id == Role::OPERATION_ROLE_ID or $id == Role::ACCOUNTING_ROLE_ID ) {
                $roleData->level = $id;
        } 

        $roleData->name         = $request->name;
        $roleData->display_name = $request->display_name; 
        $roleData->activated    = 1;

        $roleData->save();

        if ($request->has('routes')){

            $roles = DB::table('role_permission')->select('id')->where('role_id',$id)->get();
            
             if (count($roles) > 0){
                    foreach($roles as $role){
                        $rolepermission  =  RolePermission::findOrfail($role->id);
                        $rolepermission->delete();
                  }
             }


             $routes = Input::get('routes');
                if (count($routes) > 0){
                     foreach($routes as $permission){

                            $rolepermission                 =  New RolePermission;
                            $rolepermission->role_id        =  $roleData->level;
                            $rolepermission->permission_id  =  $permission;
                            $rolepermission->save();

                     }
            }
        }

        return redirect()->route('role.index')->with('success','Role has been updated successfully.'); 
    }

}
