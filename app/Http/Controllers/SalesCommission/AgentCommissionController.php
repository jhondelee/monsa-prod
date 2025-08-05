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
use App\SalesPayment;
use App\AgentCommission;
use App\AgentCommissionItem;
use Carbon\Carbon;
use Fpdf;
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

        $id = $agentcommission->id;

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


        return redirect()->route('commission.edit',[$id])

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

     public function post($id)
     {
        $AgentComs = AgentCommission::find($id);

        $AgentComs->agent_paid_status = 1;

        $AgentComs->save();       

        $agentItems = AgentCommissionItem::where('agent_commission_id',$id)->get();

        foreach ($agentItems as $key => $agentItem) {

             $salespayments = SalesPayment::where('sales_order_id',$agentItem->sales_order_id)->first();

             $paymenst = SalesPayment::find($salespayments->id);

             $paymenst->commission_status = 1;

             $paymenst->save();

        }
           

        return redirect()->route('commission.index')

            ->with('success','Agent Commission has been posted successfully.');
     }

     public function print($id)
     {
        $AgentComs = AgentCommission::find($id);
        $agent =$this->user->getCreatedbyAttribute($AgentComs->employee_id);


        $pdf = new Fpdf('P');
        $pdf::AddPage('P','A4');

        $pdf::SetFont('Arial','',7);
        $pdf::cell(170,0,date("m-d-Y") ,0,"","R");
        date_default_timezone_set("singapore");
        $pdf::cell(0,0,date("h:i A"),0,"","L");

        $pdf::Image('img/temporary-logo.jpg',2, 2, 30.00);
        $pdf::SetFont('Arial','B',12);
        $pdf::SetY(20);     

        // Header
        $pdf::SetFont('Arial','B',12);
        $pdf::SetY(20);  

        $pdf::Ln(2);
        $pdf::SetFont('Arial','B',11);
        $pdf::SetXY($pdf::getX(), $pdf::getY());
        $pdf::cell(185,1,"Agent Commission Report",0,"","C");

        $pdf::Ln(5);
        $pdf::SetFont('Arial','B',9);
        $pdf::SetXY($pdf::getX(), $pdf::getY());
        $pdf::cell(17,6,"Main Agent",0,"","L");
        $pdf::SetFont('Arial','',9);
        $pdf::cell(40,6,' : '.$agent,0,"","L");
        $pdf::Ln(5);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(17,6,"Date Range ",0,"","L");
        $pdf::SetFont('Arial','',9);
        $from_date = Carbon::parse($AgentComs->from_date);
        $end_date = Carbon::parse($AgentComs->end_date);
        $pdf::cell(30,6,' : '.$from_date->format('M d, Y').' - '.$end_date->format('M d, Y'),0,"","L");


        $pdf::Ln(5);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(17,6,"Status",0,"","L");
        $pdf::SetFont('Arial','',9);
         $stats = 'UNPAID';
        if ($AgentComs->agent_paid_status == 1){ 
            $stats = 'PAID';
        } 
        $pdf::cell(40,6,' : '.$stats,0,"","L");

        //Column Name
            $pdf::Ln(10);
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(25,6,"DR No.",0,"","L");
            $pdf::cell(25,6,"SO Date",0,"","L");
            $pdf::cell(25,6,"Status",0,"","L");
            $pdf::cell(40,6,"Customer",0,"","L");
            $pdf::cell(10,6,"Rate",0,"","L");
            $pdf::cell(30,6,"Comission",0,"","R");
            $pdf::cell(30,6,"Total Sales",0,"","R");

            $commissions = $this->agentcommission->getsalesCom($AgentComs->employee_id)
            ->whereBetween('so_date',[$AgentComs->from_date,$AgentComs->to_date]); 
  
        $pdf::Ln(1);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");

        $totalCommission = 0;
        $tatolAmount = 0;
            foreach ($commissions as $key => $commission) {

                $pdf::Ln(5);
                $pdf::SetFont('Arial','',9);
                $pdf::cell(25,6,$commission->so_number,0,"","L");
                $pdf::cell(25,6,$commission->so_date,0,"","L");
                $pdf::cell(25,6,$commission->so_status,0,"","L");
                $pdf::cell(40,6,$commission->cs_name,0,"","L");
                $pdf::cell(10,6,$commission->rate,0,"","L");
                $pdf::cell(30,6,number_format($commission->amount_com,2),0,"","R");
                $pdf::cell(30,6,number_format($commission->total_sales,2),0,"","R");
                
                $tatolAmount = $tatolAmount + $commission->total_sales;
                $totalCommission = $totalCommission + $commission->amount_com;
                
            }

        $pdf::Ln(5);
        $pdf::SetFont('Arial','I',8);
        $pdf::cell(190,6,"--Nothing Follows--",0,"","C");

        $pdf::Ln(1);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");


            $pdf::Ln(5);
            $pdf::SetFont('Arial','B',10);
            $pdf::cell(125  ,6,"Total:",0,"","R");
            $pdf::SetFont('Arial','B',10);
            $pdf::cell(30,6,number_format( $totalCommission,2),0,"","R");
            $pdf::SetFont('Arial','B',10);
            $pdf::cell(30,6,number_format( $tatolAmount,2),0,"","R");


        $agents = $this->agentteam->agentsEarned($AgentComs->employee_id);
        
        $pdf::Ln(5);
        $ctr = 0 ;
        foreach ($agents as $key => $agent) {
             $ctr =  $ctr+1;
            $pdf::Ln(5);
            $pdf::SetFont('Arial','B',9);
                if ($ctr == 1){
                     $pdf::cell(20  ,6,"Main Agent:",0,"","L");
                     $pdf::Ln(5);
                }
                if ($ctr == 2){
                     $pdf::cell(20  ,6,"Sub Agent: ",0,"","L");
                     $pdf::Ln(5);
                     $pdf::cell(10  ,6,' ',0,"","L");
                }else{
                    $pdf::cell(10  ,6,' ',0,"","L");
                }
               
            $pdf::SetFont('Arial','',9);
            $pdf::cell(35  ,6,' '.$agent->sub_agent,0,"","L");

                $com = $totalCommission * ($agent->rates/100);

            $pdf::SetFont('Arial','',9);
            $pdf::cell(25,6,number_format($com,2),0,"","R");
        }
        
        $pdf::Ln(1);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(70,6,"__________",0,"","R");
            $pdf::Ln(5);
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(45,6,"Total:",0,"","L");
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(25,6,number_format( $totalCommission,2),0,"","R");


        $pdf::Ln();
        $pdf::Output();
        exit;

     }

}
