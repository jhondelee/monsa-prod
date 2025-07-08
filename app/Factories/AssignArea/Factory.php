<?php

namespace App\Factories\AssignArea;

use App\Factories\AssignArea\SetInterface;
use DB;

class Factory implements SetInterface
{

    public function getindex()
    {

     $results = DB::select("
                SELECT a.id,
                        CONCAT(e.firstname,' ',e.lastname) AS emp_name,
                        c.rate,
                        r.name AS area_assigned,
                        a.created_at,
                        a.employee_id,
                        a.area_id,
                        a.rate_id 
                FROM assign_area a
                INNER JOIN employees e ON e.id = a.employee_id 
                INNER JOIN areas r ON r.id = a.area_id
                INNER JOIN commission_rate c ON c.id = a.rate_id;");
     
        return collect($results);
    } 
}