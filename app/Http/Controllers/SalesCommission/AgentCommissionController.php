<?php

namespace App\Http\Controllers\SalesCommission;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Factories\AgentCommission\Factory as AgentCommissionFactory;
use App\Factories\AgentTeam\Factory as AgentTeamFactory;
use App\Factories\SalesOrder\Factory as SalesOrderFactory;
use App\User as Users;
use App\Area;
use App\CommissionRate;
use App\AssignArea;
use App\AgentCommission;
use App\AgentCommissionItem;
use DB;

class AgentCommissionController extends Controller
{
     public function __construct(
            Users $user,
            AgentCommissionFactory $agent_commission,
            AgentTeamFactory $agent_team,
            SalesOrderFactory $salesorder
         )
    {
        $this->user = $user;
        $this->agentcommission = $agent_commission;
        $this->agentteam = $agent_team;
        $this->salesorders = $salesorder;
        $this->middleware('auth');  
    }


    public function index()
    {
        $agentcommissions = $this->agentcommission->index();

        $employee = $this->user->getemplist()->pluck('emp_name','id');

        return view('pages.sales_commission.commission.index',compact('employee','agentcommissions'));
    }

    public function create()
    {

        $employee = $this->salesorders->employee_agent()->pluck('emp_name','id');

        $creator = $this->user->getCreatedbyAttribute(auth()->user()->id);

        $total_com = 0;

        return view('pages.sales_commission.commission.create',compact('employee','creator','total_com'));
    }

     public function store(Request $request)
     {
        $this->validate($request,[
            'employee_id' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
        ]);

        $sale_items = $this->agentcommission->getsalesCom($request->employee_id)->whereBetween('so_date',[$request->from_date,$request->to_date]); 

        $agentcommission = New AgentCommission;

        $agentcommission->employee_id = $request->employee_id;

        $agentcommission->from_date = $request->from_date;

        $agentcommission->to_date = $request->to_date;

        $agentcommission->total_sales = $request->total_sales_amount;


        $agentcommission->created_by = Auth()->user()->id;

        $agentcommission->save();


        foreach ($sale_items as $key => $value) {

            $agentcommissionitems = New AgentCommissionItem;

            $agentcommissionitems->agent_commission_id = $agentcommission->id;

            $agentcommissionitems->sales_order_id = $value->id;

            $agentcommissionitems->so_date = $value->so_date;

            $agentcommissionitems->so_status = $value->so_status;

            $agentcommissionitems->sub_agent = $value->sub_employee_id;

            $agentcommissionitems->total_amount = $value->total_sales;

            $agentcommissionitems->save();
        }


        return redirect()->route('commission.index')

            ->with('success','Agent Commission has been saved successfully.');

     }

     public function getsalesCom(Request $request)
     {
        
        $results = $this->agentcommission->getsalesCom($request->id)->whereBetween('so_date',[$request->sdate,$request->edate]);   

        return response()->json($results); 
        
     }

    public function agentEarned(Request $request)
     {
        
        $results = $this->agentteam->agentsEarned($request->id);

        return response()->json($results); 
        
     }



     public function edit($id)
     {

        $agentcommission = AgentCommission::find($id);

        $employee = $this->user->getemplist()->pluck('emp_name','id');

        $creator = $this->user->getCreatedbyAttribute(auth()->user()->id);


        $earned = $this->agentcommission->getsalesCom($agentcommission->employee_id)->whereBetween('so_date',[$agentcommission->from_date,
            $agentcommission->to_date]);


        $total_com = 0;
        foreach ($earned as $key => $value) {
            
             $total_com =  $total_com + floatval($value->amount_com);
        }



        return view('pages.sales_commission.commission.edit',compact('employee','creator','agentcommission','total_com'));
          
     }



     public function update(Request $request,$id)
     {
        $this->validate($request,[
            'employee_id' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
        ]);

        $sale_items = $this->agentcommission->getsalesCom($request->employee_id)->whereBetween('so_date',[$request->from_date,$request->to_date]); 
        
        $comitems = AgentCommissionItem::where('agent_commission_id',$id)->get();

            if(count($comitems) > 0)
             {
                foreach ($comitems as $key => $comitem) {

                    $items = AgentCommissionItem::findOrfail($comitem->id);

                    $items->delete();
                }

             }
                


        foreach ($sale_items as $key => $value) {

            $agentcommissionitems = New AgentCommissionItem;

            $agentcommissionitems->agent_commission_id = $id;

            $agentcommissionitems->sales_order_id = $value->id;

            $agentcommissionitems->so_date = $value->so_date;

            $agentcommissionitems->so_status = $value->so_status;

            $agentcommissionitems->sub_agent = $value->sub_employee_id;

            $agentcommissionitems->total_amount = $value->total_sales;

            $agentcommissionitems->save();
        }

        $agentcommission = AgentCommission::find($id);

        $agentcommission->employee_id = $request->employee_id;

        $agentcommission->from_date = $request->from_date;

        $agentcommission->to_date = $request->to_date;

        $agentcommission->total_sales = $request->total_sales_amount;

        $agentcommission->save();


        return redirect()->route('commission.index')

            ->with('success','Agent Commission has been updated successfully.');

     }


     public function destroy($id)
     {  

        $AgentComs = AgentCommission::find($id);

        $AgentComs->delete();

        
        $comitems = AgentCommissionItem::where('agent_commission_id',$id)->get();

            if(count($comitems) > 0)
             {
                foreach ($comitems as $key => $comitem) {

                    $items = AgentCommissionItem::findOrfail($comitem->id);

                    $items->delete();
                }

             }
            

        return redirect()->route('commission.index')

            ->with('success','Agent Commission has been deleted successfully.');

     }

}
