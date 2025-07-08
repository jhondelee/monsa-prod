<?php

namespace App\Factories\AgentCommission;

use App\Factories\AgentCommission\SetInterface;
use DB;

class Factory implements SetInterface
{

	public function index()
	{
		$results = DB::select("	SELECT a.id,
				  CONCAT(emp.firstname ,' ',emp.lastname ) AS sub_agent,
				  a.from_date,
				  a.to_date,
				  a.total_sales,
				  a.created_at
		FROM agent_commission a
		INNER JOIN employees emp ON a.employee_id = emp.id
		ORDER BY a.id desc");

		return collect($results);
	}

    public function getsalesCom($empId)
    {

     $results = DB::select("
   				 SELECT so.id,
                 so.so_number,
                 so.so_date,
				 	  so.status AS so_status,
					  CONCAT(sub.firstname ,' ',sub.lastname ) AS sub_agent,
					  so.sub_employee_id,
					  cs.name as cs_name,
					  cr.rate,
					  REPLACE(FORMAT(cr.rate * so.total_sales, 2), ',', '') AS amount_com,
			 		  so.total_sales
		FROM sales_order so
		LEFT  JOIN employees sub ON sub.id = so.sub_employee_id
		LEFT JOIN customers cs ON so.customer_id = cs.id
		LEFT JOIN assign_area aa ON aa.area_id = cs.area_id
		LEFT JOIN commission_rate cr ON cr.id = aa.rate_id
		WHERE so.employee_id = ? AND so.status IN( 'CLOSED','POSTED');",[$empId]);
     
        return collect($results);
    } 
}


