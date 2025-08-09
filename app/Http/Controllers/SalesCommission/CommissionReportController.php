<?php

namespace App\Http\Controllers\SalesCommission;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Factories\CommissionReport\Factory as CommissionReportFactory;
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

class CommissionReportController extends Controller
{
    public function __construct(
            Users $user,
            CommissionReportFactory $commission_report,
            SalesOrderFactory $salesorder,
            AgentTeamFactory $agent_team
         )
    {
        $this->user = $user;
        $this->commission_report = $commission_report;
        $this->agentteam = $agent_team;
        $this->salesorders = $salesorder;
        $this->middleware('auth');  
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employee = $this->salesorders->employee_agent()->pluck('emp_name','id');

        return view('pages.sales_commission.report.index',compact('employee'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function print_commission(Request $request)
    {

            $this->validate($request, [
            'start_date' => 'required',
            'end_date'   => 'required',
            'agent_id' => 'required',
        ]);


        return redirect()->route('commission_report.generate_commission',[
                    $request->start_date,
                    $request->end_date,
                    $request->agent_id
                ]);
    }

    public function generate_commission($start, $end, $empID)
    {
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
        $pdf::cell(185,1,"Sales Commission Report",0,"","C");

        $pdf::Ln(5);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(17,6,"Date Range ",0,"","L");
        $pdf::SetFont('Arial','',9);
        $from_date = Carbon::parse($start);
        $end_date = Carbon::parse($end);
        $pdf::cell(30,6,' : '.$from_date->format('M d, Y').' - '.$end_date->format('M d, Y'),0,"","L");


        //Column Name
            $pdf::Ln(10);
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(35,6,"SO Date",0,"","L");
            $pdf::cell(40,6,"Area",0,"","L");
            $pdf::cell(35,6,"Total Sales",0,"","R");
            $pdf::cell(35,6,"Total Return",0,"","R");
            $pdf::cell(35,6,"Total Amount",0,"","R");


        $pdf::Ln(1);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");

        $totalSales = 0;
        $totalReturn = 0;
        $tatolAmount = 0;
        $totalCommission = 0;

            $ctrCom = $this->commission_report->getctrCommission($start, $end, $empID);
            $commissions = $this->commission_report->getCommissions($start, $end, $empID);  

        for ($i=0; $i < count($ctrCom); $i++) { 

            $sales = 0; $returns = 0; $amount = 0 ; $commission = 0;

            foreach ($commissions as $key => $cms) {
                
                    if($ctrCom[$i]->so_date == $cms->so_date AND $ctrCom[$i]->area == $cms->area) {

                        $sales = $sales + $cms->total_sales;
                        $returns = $returns + $cms->total_returns;
                        $amount = $amount + $cms->total_amount;
                        $commission = $commission + (number_format($cms->rates,3) * $cms->total_amount);

                    } 
            }

            $pdf::Ln(5);
            $pdf::SetFont('Arial','',9);
            $pdf::cell(35,6,$ctrCom[$i]->so_date,0,"","L");
            $pdf::cell(40,6,$ctrCom[$i]->area,0,"","L");
            $pdf::cell(35,6,number_format($sales,2),0,"","R");
            $pdf::cell(35,6,number_format($returns,2),0,"","R");
            $pdf::cell(35,6,number_format($amount,2),0,"","R");
                            
                            
            $totalSales = $totalSales + $sales;
            $totalReturn = $totalReturn + $returns;
            $tatolAmount = $tatolAmount + $amount;
            $totalCommission = $totalCommission + $commission;  
            
        }


        $pdf::Ln(5);
        $pdf::SetFont('Arial','I',8);
        $pdf::cell(190,6,"--Nothing Follows--",0,"","C");

        $pdf::Ln(1);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");


            $pdf::Ln(5);
            $pdf::SetFont('Arial','B',10);
            $pdf::cell(80,6,"Total:",0,"","L");
            $pdf::SetFont('Arial','B',10);
            $pdf::cell(30,6,number_format( $totalSales,2),0,"","R");
            $pdf::SetFont('Arial','B',10);
            $pdf::cell(35,6,number_format( $totalReturn,2),0,"","R");
             $pdf::SetFont('Arial','B',10);
            $pdf::cell(35,6,number_format( $tatolAmount,2),0,"","R");


        $agents = $this->agentteam->agentsEarned($empID);
        
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

            $pdf::SetFont('Arial','',9);
            $pdf::cell(10  ,6,' '.$agent->rates.'%',0,"","L");
                $com = $totalCommission * ($agent->rates / 100);
 
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(25,6,number_format($com,2),0,"","R");
        }
        
        $pdf::Ln(1);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(80,6,"__________",0,"","R");
            $pdf::Ln(5);
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(50,6,"Total:",0,"","L");
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(30,6,number_format( $totalCommission,2),0,"","R");


        $pdf::Ln();
        $pdf::Output();
        exit;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
