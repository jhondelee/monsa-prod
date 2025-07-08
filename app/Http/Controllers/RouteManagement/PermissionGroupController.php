<?php

namespace App\Http\Controllers\RouteManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Factories\PermissionGroup\Factory as PermissionGroupFactory;
use Yajra\Datatables\Datatables;
use App\Http\Requests\PermissionGroupRequest;
use App\PermissionGroup;

class PermissionGroupController extends Controller
{
    /**
     *  Initialized Dependencies and default value
     */
    public function __construct(
                    PermissionGroupFactory $permissionGroup, 
                    Datatables $dataTables
    ){             
    
        $this->permissionGroup = $permissionGroup;
        $this->dataTables = $dataTables;
        
        $this->data['title'] = 'Route Management';
        
        $this->data['views'] = 'pages.route_management.permission_group.';
        
        $this->data['columns'] = [
                            'ID'           => 'id',
                            'Display Name' => 'name',
                            'Icon'         => 'icon_class',
                            'Order'        => 'sort',
                            'Action'       => 'action',
                        ];
    }
    
    /**
     *  Index Page
     *  
     *  @return Resources
     */
    public function index()
    {   
   
         $pgroups =  $this->permissionGroup->get()->all();

         return view($this->data['views'].'index', compact('pgroups'));


    }
    
    /**
     *  API DataTables
     *  
     *  @return json
     */
    public function dataTable()
    {
        unset($this->data['columns']['Action']);   
        
        $this->permissionGroup->setColumns($this->data['columns']);
        
        $get = $this->permissionGroup->get()->get();
        
        return $this->dataTables::of($get)->addColumn('action', function($get) {
                                        return $get->id;
                                    })->make(true);
    }
    
    /**
     *  Add New Page
     *  
     *  @return Resources
     */    
    public function add()
    {
        return view($this->data['views'].'create', $this->data);
    }
    
    /**
     *  Add New Action
     *  
     *  @param App\Http\Requests\ $request
     *  @return json
     */    
    public function doAdd(PermissionGroupRequest $request)
    {
        if ($this->permissionGroup->save($request)) ;

         return redirect()->route('pgroup.index')

            ->with('success','Group created successfully!');

        return response(__('app.form.failed_create'), 400);

        
    }
    
    /**
     *  Edit Page
     *  
     *  @param int $id ID
     *  @return Resources
     */    
    public function edit($id)
    {
        $this->data['pgroup'] = $this->permissionGroup->find($id);
        
        return view($this->data['views'].'edit', $this->data);
    }
    
    /**
     *  Edit Action
     *  
     *  @param int $id ID
     *  @param App\Http\Requests\ $request
     *  @return json
     */    
    public function doEdit($id, PermissionGroupRequest $request)
    {
        if ($this->permissionGroup->save($request, $id));

            return redirect()->route('pgroup.index')

            ->with('success','Group updated successfully!');
        
        return response(__('app.form.failed_update'), 400);    
    }
}