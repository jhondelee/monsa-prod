<?php

namespace App\Http\Controllers\RouteManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Factories\Permission\Factory as PermissionFactory;
use App\Factories\PermissionGroup\Factory as PermissionGroupFactory;
use Yajra\Datatables\Datatables;
use App\Http\Requests\PermissionRequest;
use Route;


class PermissionController extends Controller
{
    /**
     *  Initialized Dependencies and default value
     */
    public function __construct(
                PermissionFactory $permission, 
                PermissionGroupFactory $permissionGroup,
                Datatables $dataTables
    ){                  
        $this->permission = $permission;
        $this->permissionGroup = $permissionGroup;
        $this->dataTables = $dataTables;
        
        $this->data['title'] = 'Route Management';
        
        $this->data['views'] = 'pages.route_management.permission.';
        
        $this->data['columns'] = [
                            'ID'           => 'id',
                            'Display Name' => 'display_name',
                            'Route Name'   => 'route_name',                            
                            'Icon'         => 'icon_class',
                            'Group'        => 'group_id as groupname',
                            'Display'      => 'display_status as isdisplay',
                            'Order'        => 'sort',
                            'Action'       => 'action',
                        ];
    }
    
    /**
     *  View Permission List
     */
    public function index()
    {   
        $permissions = $this->permission->getindex();
        return view($this->data['views'].'index', compact('permissions'));
    }
    
    /**
     *  API DataTables Permission List
     */
    public function dataTable()
    {
        unset($this->data['columns']['Action']);   
        
        $this->permission->setColumns($this->data['columns']);
        
        $getData = $this->permission->get()->get();
        
        return $this->dataTables::of($getData)->addColumn('action', function($getData) {
                                        return $getData->id;
                                    })->make(true);
    }
    
    /**
     *  Add New Permission
     */    
    public function add()
    {
        $this->data['groups'] = dropdownList($this->permissionGroup->get()->all());

        $except = $this->permission->get()->pluck('route_name')->toArray();        
        $this->data['routeList'] = getRouteNameList($except); 
        
        return view($this->data['views'].'create', $this->data);
    }
    
    /**
     *  Submit New Permission
     */    
    public function doAdd(PermissionRequest $request)
    {
        if ($this->permission->save($request));

         return redirect()->route('permission.index')

            ->with('success','Route created successfully!');
        
        return response(__('app.form.failed_create'), 400);
    }
    
    /**
     *  Edit Permission
     *  
     *  @param int $id Permission ID
     */    
    public function edit($id)
    {
        $this->data['groups'] = dropdownList($this->permissionGroup->get()->all());

        $except = $this->permission->get()->pluck('route_name')->toArray();        
        $this->data['routeList'] = getRouteNameList($except);        
        
        $this->data['permission'] = $this->permission->find($id);
        
        return view($this->data['views'].'edit', $this->data);
    }
    
    /**
     *  Submit Edit Permission
     *  
     *  @param int $id Permission ID
     */    
    public function doEdit($id, PermissionRequest $request)
    {
        if ($this->permission->save($request, $id));

        return redirect()->route('permission.index')

            ->with('success','Route updated successfully!');
        
        return response(__('app.form.failed_update'), 400);    
    }
}

