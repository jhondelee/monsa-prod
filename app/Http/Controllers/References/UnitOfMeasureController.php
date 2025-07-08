<?php

namespace App\Http\Controllers\References;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\UnitOfMeasure;
use App\User as Users;

class UnitOfMeasureController extends Controller
{
    public function __construct(Users $user)
    {
        $this->user = $user;
        
        $this->middleware('auth');
    }

    public function index()
    {
        $unit_of_measures = UnitOfMeasure::all();

        return view('pages.references.unit_of_measure.index',compact('unit_of_measures'));
    }

     public function create()
    {
        //
    }

    public function store(Request $request)
    {


        $this->validate($request, ['name' => 'required',]);

       $employee = $this->user->getCreatedbyAttribute(auth()->user()->id);

        $unit_of_measures = New UnitOfMeasure;

        $unit_of_measures->code = $request->code;

        $unit_of_measures->name = $request->name;

        $unit_of_measures->created_by = $employee;

        $unit_of_measures->save();

        return redirect()->route('unit_of_measure.index')

            ->with('success','Unit of Measure has been saved successfully.');

    }


    public function edit()
    {
        //
    }

    public function update(Request $request)
    {


        $this->validate($request, ['name' => 'required',]);

        $employee = $this->user->getCreatedbyAttribute(auth()->user()->id);


        $unit_of_measures = UnitOfMeasure::findOrfail($request->id);

        $unit_of_measures->code = $request->code;

        $unit_of_measures->name = $request->name;

        $unit_of_measures->created_by = $employee;

        $unit_of_measures->save();

        return redirect()->route('unit_of_measure.index')

            ->with('success','Unit of Measure has been updated successfully.');

    }

    public function destroy($id)
    {
        
        $unit_of_measures = UnitOfMeasure::findOrfail($id);
        
        $unit_of_measures->delete();

        return redirect()->route('unit_of_measure.index')

            ->with('success','Unit Of Measure deleted successfully.');
    }


}
    