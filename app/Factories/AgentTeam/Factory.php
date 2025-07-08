<?php

namespace App\Factories\AgentTeam;

use App\Factories\AgentTeam\SetInterface;
use DB;

class Factory implements SetInterface
{

	public function index()
	{
		$results = DB::select("SELECT a.employee_id AS id,
				  CONCAT(emp.firstname ,' ',emp.lastname ) AS main_agent,
				  a.created_at
		FROM agent_team a
		INNER JOIN employees emp ON a.employee_id = emp.id
		GROUP BY emp.firstname,emp.lastname,a.employee_id,a.created_at
		ORDER BY a.id desc");

		return collect($results);
	}


	public function sub_agents($mainID)
	{
		$results = DB::select("
				SELECT a.employee_id, a.team_id ,
		CONCAT(e.firstname ,' ',e.lastname ) AS sub_agent,
		a.share_percentage FROM agent_team a 
		INNER JOIN employees e ON a.team_id = e.id 
		WHERE a.employee_id = ?
		AND a.employee_id <> a.team_id",[$mainID]);

		return collect($results);
	}

	public function agentsEarned($empID)
	{
		$results = DB::select("
				SELECT a.employee_id, a.team_id ,
		CONCAT(e.firstname ,' ',e.lastname ) AS sub_agent,
		a.share_percentage as rates FROM agent_team a 
		INNER JOIN employees e ON a.team_id = e.id 
		WHERE a.employee_id = ? ",[$empID]);

		return collect($results);
	}
}


