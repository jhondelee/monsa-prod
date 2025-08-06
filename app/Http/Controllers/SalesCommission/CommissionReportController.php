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

class CommissionReportController extends Controller
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
    public function create()
    {
        //
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
