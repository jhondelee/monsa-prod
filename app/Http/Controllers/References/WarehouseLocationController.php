<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\WarehouseLocation;
use App\User as Users;

class WarehouseLocationController extends Controller
{

    public function __construct(Users $user)
    {
        $this->user = $user;

        $this->middleware('auth');
    }

  public function index()
    {
        $warehouse_locations = WarehouseLocation::all();

        return view('pages.references.location.index',compact('warehouse_locations'));
    }

     public function create()
    {
        //
    }

    public function store(Request $request)
    {


        $this->validate($request, ['name' => 'required',]);

       $employee = $this->user->getCreatedbyAttribute(auth()->user()->id);

        $warehouse_locations = New WarehouseLocation;

        $warehouse_locations->name = $request->name;

        $warehouse_locations->created_by = $employee;

        $warehouse_locations->save();

        return redirect()->route('location.index')

            ->with('success','Warehouse Location has been saved successfully.');

    }


    public function edit()
    {
        //
    }

    public function update(Request $request)
    {


        $this->validate($request, ['name' => 'required',]);

        $employee = $this->user->getCreatedbyAttribute(auth()->user()->id);


        $warehouse_locations = WarehouseLocation::findOrfail($request->id);

        $warehouse_locations->name = $request->name;

        $warehouse_locations->created_by = $employee;

        $warehouse_locations->save();

        return redirect()->route('location.index')

            ->with('success','Warehouse Location has been updated successfully.');

    }


    public function destroy($id)
    {
        
        $warehouse_locations = WarehouseLocation::findOrfail($id);
        
        $warehouse_locations->delete();

        return redirect()->route('location.index')

            ->with('success','Warehouse Location deleted successfully.');
    }


}
