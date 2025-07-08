<?php

namespace App\Http\Controllers\SalesCommission;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Factories\AssignArea\Factory as AssignAreaFactory;
use App\User as Users;
use App\Area;
use App\CommissionRate;
use App\AssignArea;
use DB;

class AssignedAreaController extends Controller
{
     public function __construct(
            Users $user,
            AssignAreaFactory $assing_areas
        )
    {
        $this->user = $user;
        $this->assignedarea = $assing_areas;
        $this->middleware('auth');  
    }

    public function index()
    {
        $employee = $this->user->getemplist()->pluck('emp_name','id');

        $areas = Area::pluck('name','id');

        $rates = CommissionRate::pluck('rate','id');

        $assigned_areas = $this->assignedarea->getindex();

        return view('pages.sales_commission.assign_area.index',compact('rates','areas','employee','assigned_areas'));
    }


    public function store(Request $request)
    {

        $this->validate($request,[
            'employee_id' => 'required',
            'area' => 'required',
            'rate' => 'required',
        ]);

        $assigned = New AssignArea;

        $assigned->employee_id = $request->employee_id;

        $assigned->area_id = $request->area;

        $assigned->rate_id = $request->rate;

        $assigned->created_by = auth()->user()->id;

        $assigned->save();


        return redirect()->route('assign_area.index')

            ->with('success','Assigned Area has been saved successfully.');

    }

    public function update(Request $request)
    {
        $this->validate($request,[
            'employee_id' => 'required',
            'area' => 'required',
            'rate' => 'required',
        ]);

        $assigned = AssignArea::find($request->id);

        $assigned->employee_id = $request->employee_id;

        $assigned->area_id = $request->area;

        $assigned->rate_id = $request->rate;

        $assigned->save();


        return redirect()->route('assign_area.index')

            ->with('success','Assigned Area has been update successfully.');
    }

    public function destroy($id)
    {

        $assigned = AssignArea::find($id);

        $assigned->delete();


        return redirect()->route('assign_area.index')

            ->with('success','Assigned Area has been deleted successfully.');
    }
}
