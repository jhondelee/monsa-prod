<?php

namespace App\Http\Controllers\SalesCommission;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User as Users;
use App\CommissionRate;
use App\AssignArea;
use DB;

class RateController extends Controller
{

    public function index()
    {
        $rates = DB::SELECT("SELECT c.id ,c.rate, CONCAT(e.firstname,' ',e.lastname) AS created_by, c.created_at FROM commission_rate c INNER JOIN employees e ON c.created_by = e.user_id ORDER BY c.id desc");

        return view('pages.sales_commission.rate.index',compact('rates'));
    }


    public function store(Request $request)
    {

        $this->validate($request, ['rate' => 'required',]);

        $rates = New CommissionRate;

        $rates->rate = $request->rate;

        $rates->created_by = auth()->user()->id;

        $rates->save();


        return redirect()->route('commission_rate.index')

            ->with('success','Rate has been saved successfully.');

    }

    public function update(Request $request)
    {
        $this->validate($request, ['rate' => 'required',]);

        $rates = CommissionRate::find($request->id);

        $rates->rate = $request->rate;

        $rates->save();


        return redirect()->route('commission_rate.index')

            ->with('success','Rate has been update successfully.');
    }

    public function destroy($id)
    {

        $rates = CommissionRate::find($id);

        $rates->delete();


        return redirect()->route('commission_rate.index')

            ->with('success','Rate has been deleted successfully.');
    }

}
