<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Area;
use App\User as Users;


class AreaController extends Controller
{
    public function __construct(Users $user)
    {
        $this->user = $user;
        $this->middleware('auth');
    }

    public function index()
    {
        $areas = Area::all();

        return view('pages.references.area.index',compact('areas'));
    }


    public function edit()
    {
        //
    }


    public function store(Request $request)
    {

        $this->validate($request, ['name' => 'required',]);

        $employee = $this->user->getCreatedbyAttribute(auth()->user()->id);

        $areas = New Area;

        $areas->name = $request->name;

        $areas->add_cost = $request->add_cost;

        $areas->add_percentage = $request->add_percentage;

        $areas->created_by = $employee;

        $areas->save();


        return redirect()->route('area.index')

            ->with('success','Area has been saved successfully.');

    }

    
    public function update(Request $request)
    {


        $this->validate($request, ['name' => 'required',]);

        $employee = $this->user->getCreatedbyAttribute(auth()->user()->id);


        $areas = Area::findOrfail($request->id);
   
        $areas->name = $request->name;

        $areas->add_cost = $request->add_cost;

        $areas->add_percentage = $request->add_percentage;

        $areas->created_by = $employee;

        $areas->save();
        

        return redirect()->route('area.index')

            ->with('success','Area has been updated successfully.');

    }

    
    public function destroy($id)
    {
        
        $areas = Area::findOrfail($id);
        
        $areas->delete();

        return redirect()->route('area.index')

            ->with('success','Area deleted successfully.');
    }

}
 
