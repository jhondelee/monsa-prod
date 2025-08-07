<?php

namespace App\Factories\CommissionReport;

use App\Factories\CommissionReport\SetInterface;
use DB;

class Factory implements SetInterface
{

    public function getctrCommission($start, $end, $empID)
    {
        $results = DB::select("
        SELECT 
            DATE_FORMAT(so.so_date,'%m-%d-%Y')  AS so_date , 
            a.name AS area
        FROM sales_payment sp
        INNER JOIN sales_order so ON so.id = sp.sales_order_id AND so.status='CLOSED'
        INNER JOIN customers c ON c.id = so.customer_id
        INNER JOIN areas a ON a.id = c.area_id
        WHERE so.so_date BETWEEN ? AND ? AND so.employee_id = ?
        GROUP BY  a.name,so.so_date
        ORDER BY  a.name,so.so_date",[$start, $end, $empID]);

        return collect($results);
    }

    public function getCommissions($start, $end, $empID)
    {
        $results = DB::select("
        SELECT 
            DATE_FORMAT(so.so_date,'%m-%d-%Y')  AS so_date , 
            a.name AS area, 
            (sp.sales_total + IFNULL(r.amount,0)) AS total_sales,
            r.amount AS total_returns,
            so.total_sales AS total_amount,
            (cr.rate) AS rates
        FROM sales_payment sp
        INNER JOIN sales_order so ON so.id = sp.sales_order_id AND so.status='CLOSED'
        INNER JOIN customers c ON c.id = so.customer_id
        INNER JOIN areas a ON a.id = c.area_id
        INNER JOIN assign_area aa ON aa.employee_id = so.employee_id 
        INNER JOIN commission_rate cr ON cr.id = aa.area_id 
        LEFT  JOIN returns r ON r.so_number = sp.so_number
        LEFT JOIN return_items ri ON r.id = ri.returns_id
        WHERE so.so_date BETWEEN ? AND ? AND so.employee_id = ?
        GROUP BY a.name,so.so_date,sp.sales_total,so.total_sales,cr.rate,r.amount
        ORDER BY  a.name,so.so_date",[$start, $end, $empID]);

        return collect($results);
    } 

  
}
