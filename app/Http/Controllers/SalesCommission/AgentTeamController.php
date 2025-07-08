<?php

namespace App\Http\Controllers\SalesCommission;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Factories\AgentTeam\Factory as AgentTeamFactory;
use App\AgentTeam;
use App\User as Users;
use DB;

class AgentTeamController extends Controller
{
     public function __construct(
            Users $user,
             AgentTeamFactory $agent_team
        )
    {
        $this->user = $user;
        $this->agentteam = $agent_team;
        $this->middleware('auth');  
    }

    public function index()
    {
        $agents = $this->agentteam->index();

        $employee = $this->user->getemplist()->pluck('emp_name','id');

        return view('pages.sales_commission.agent_team.index',compact('agents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employee_id = $this->user->getemplist()->pluck('emp_name','id');

        return view('pages.sales_commission.agent_team.create',compact('employee_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $agent_rate = New AgentTeam;

        $agent_rate->employee_id = $request->employee_id;

        $agent_rate->team_id = $request->employee_id;

        $agent_rate->share_percentage = $request->share_percentage;

        $agent_rate->save();

        $subagents = $request->get('sub_id');
        $subrates = $request->get('sub_rate');

        for ( $i=0 ; $i < count($subagents) ; $i++ ){

            $agent_rate = New AgentTeam;

            $agent_rate->employee_id = $request->employee_id;

            $agent_rate->team_id = $subagents[$i];

            $agent_rate->share_percentage = $subrates[$i];

            $agent_rate->save();

        }


        return redirect()->route('team.index')

            ->with('success','Agent Team has been saved successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showSubAgents(Request $request)
    {   
        
        $results = $this->agentteam->sub_agents($request->id);
        
        return response()->json($results);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $agents = AgentTeam::where('employee_id',$id)->first();

        $agentteams = AgentTeam::where('employee_id',$id)->get();

        $employee_id = $this->user->getemplist()->pluck('emp_name','id');

        return view('pages.sales_commission.agent_team.edit',compact('agentteams','employee_id','agents'));
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
        

        $agentlist = AgentTeam::where('employee_id',$id)->get();

        foreach ($agentlist as $key => $value) {
            $agent = AgentTeam::find($value->id);
            $agent->delete();

        }

        $agent_rate = New AgentTeam;

        $agent_rate->employee_id = $request->employee_id;

        $agent_rate->team_id = $request->employee_id;

        $agent_rate->share_percentage = $request->share_percentage;

        $agent_rate->save();

        $subagents = $request->get('sub_id');
        $subrates = $request->get('sub_rate');

        for ( $i=0 ; $i < count($subagents) ; $i++ ){

            $agent_rate = New AgentTeam;

            $agent_rate->employee_id = $request->employee_id;

            $agent_rate->team_id = $subagents[$i];

            $agent_rate->share_percentage = $subrates[$i];

            $agent_rate->save();

        }


        return redirect()->route('team.index')

            ->with('success','Agent team has been updated successfully.');
        }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $agentlist = AgentTeam::where('employee_id',$id)->get();

        foreach ($agentlist as $key => $value) {
            $agent = AgentTeam::find($value->id);
            $agent->delete();

        }

        return redirect()->route('team.index')

            ->with('success','Agent team has been deleted successfully.');
        
    }
}
