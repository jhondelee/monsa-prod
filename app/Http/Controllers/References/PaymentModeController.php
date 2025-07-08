<?php

namespace App\Http\Controllers\References;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\ModeOfPayment;
use App\User as Users;
use DB;

class PaymentModeController extends Controller
{
    public function __construct(Users $user)
    {
        $this->user = $user;
        
        $this->middleware('auth');
    }


    public function index()
    {
        $paymentmodes = DB::select("SELECT m.id, m.name, CONCAT(e.firstname,' ',e.lastname) AS created_by, 
                        m.created_at FROM mode_of_payments m INNER JOIN employees e ON e.id = m.created_by ;");
        
        return view('pages.references.payment_mode.index',compact('paymentmodes'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {


        $this->validate($request, ['name' => 'required',]);

        $paymentmodes = New ModeOfPayment;

        $paymentmodes->name = $request->name;

        $paymentmodes->created_by = auth()->user()->id;

        $paymentmodes->save();

        return redirect()->route('payment_mode.index')

            ->with('success','Mode Of Payment has been saved successfully.');

    }


    public function edit()
    {
        //
    }

    public function update(Request $request)
    {


        $this->validate($request, ['name' => 'required',]);

        $paymentmodes = ModeOfPayment::findOrfail($request->id);

        $paymentmodes->name = $request->name;

        $paymentmodes->created_by = auth()->user()->id;

        $paymentmodes->save();

        return redirect()->route('payment_mode.index')

            ->with('success','Mode Of Payment has been updated successfully.');

    }

    public function destroy($id)
    {
        
        $paymentmodes = ModeOfPayment::findOrfail($id);
        
        $paymentmodes->delete();

        return redirect()->route('payment_mode.index')

            ->with('success','Mode Of Payment deleted successfully.');
    }
}
