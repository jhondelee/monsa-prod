<?php

namespace App\Factories\Dashboard;

use App\Factories\Dashboard\SetInterface;
use DB;

class Factory implements SetInterface
{
    //-- SALES
    public function sales_of_previous_month()
    {
        $results = DB::select("
                    SELECT SUM(total_sales) AS total_sales,
                            DATE_FORMAT(so_date, '%M %Y') AS sales_monthyear
                    FROM sales_order
                    WHERE MONTH(so_date) = MONTH( DATE_ADD(CURRENT_DATE(),INTERVAL -1 MONTH ))
                    AND STATUS ='POSTED' GROUP BY DATE_FORMAT(so_date, '%M %Y')");

        return collect($results);
    }   

    
    public function sales_of_current_month()
    {
        $results = DB::select("
                    SELECT SUM(total_sales) AS total_sales
                    FROM sales_order
                    WHERE MONTH(so_date) = MONTH(CURRENT_DATE())
                    AND STATUS ='POSTED';");

        return collect($results);
    } 


    public function current_year()
    {
        $results = DB::select("SELECT DATE_FORMAT(DATE_ADD(CURRENT_DATE(),INTERVAL -1 MONTH ), '%M %Y') as cur_yr");

        return collect($results);
    }   

    //-- PURCHASE ORDER 
    public function order_of_previous_month()
    {
        $results = DB::select("
                    SELECT SUM(total_amount) AS total_order,
                            DATE_FORMAT(dr_date, '%M %Y') AS order_monthyear
                    FROM incomings
                    WHERE MONTH(dr_date) = MONTH( DATE_ADD(CURRENT_DATE(),INTERVAL -1 MONTH ))
                    AND STATUS ='CLOSED' GROUP BY DATE_FORMAT(dr_date, '%M %Y')");

        return collect($results);
    }   

    
    public function order_of_current_month()
    {
        $results = DB::select("
                    SELECT SUM(total_amount) AS total_order
                    FROM incomings
                    WHERE MONTH(dr_date) = MONTH(CURRENT_DATE())
                    AND STATUS ='CLOSED';");

        return collect($results);
    } 

    public function getinactivecs()
    {
        $results = DB::select("
    SELECT  s.id, c.name AS cs_name, t.so_date,
          CONCAT(DATEDIFF(CURRENT_DATE(),t.so_date),' Days') AS Last_trans,
          CASE 
            WHEN (DATEDIFF(CURRENT_DATE(),t.so_date)  > 14) && (DATEDIFF(CURRENT_DATE(),t.so_date) <= 21) THEN 'No Transaction'
            WHEN (DATEDIFF(CURRENT_DATE(),t.so_date)  > 21) && (DATEDIFF(CURRENT_DATE(),t.so_date) <= 30) THEN 'Follow Up'
            WHEN (DATEDIFF(CURRENT_DATE(),t.so_date)  > 30) THEN 'Lost Customer'
        END AS trans_stat
        FROM   (
                SELECT DISTINCT  customer_id, MAX(so_date) AS so_date ,max(id) AS id
                FROM sales_order 
                GROUP BY customer_id
            ) t
    left JOIN sales_order s ON s.id = t.id
    INNER JOIN customers c ON s.customer_id = c.id
    WHERE (DATEDIFF(CURRENT_DATE(),t.so_date)  < 60) AND (DATEDIFF(CURRENT_DATE(),t.so_date)  > 14)
    ORDER BY t.so_date;");

        return collect($results);
    }

    public function gettopsalesteam()
    {
        $results = DB::select("
        SELECT CONCAT(e.firstname,' ' ,e.lastname ) AS emp_name , SUM(o.total_sales) AS sales , s.sales AS last_sales  
        FROM sales_order o
        INNER JOIN employees e ON e.id = o.employee_id 
        LEFT JOIN (
                        SELECT employee_id, SUM(total_sales) AS sales
                        FROM sales_order 
                        WHERE WEEK(so_date) =  (WEEK(CURRENT_DATE())-1) 
                        GROUP BY employee_id
                        ) s ON s.employee_id = o.employee_id
        WHERE WEEK(so_date) =  WEEK(CURRENT_DATE()) 
        GROUP BY CONCAT(e.firstname,' ' ,e.lastname ) ,s.sales ORDER BY SUM(o.total_sales) DESC LIMIT 3");

        return collect($results); 
    }

        public function getMonthSales()
    {
        $results = DB::select("
            SELECT MONTHNAME(o.so_date) AS Months, 
            YEAR(o.so_date),
            s.Total_Sales
            FROM sales_order o
            INNER JOIN (
                            SELECT MONTHNAME(so_date) AS Months, SUM(total_sales) AS Total_Sales 
                            FROM sales_order
                            WHERE STATUS ='POSTED' AND YEAR(so_date) = YEAR(CURRENT_DATE)
                            GROUP BY MONTHNAME(so_date)
                            ) s  ON s.Months = MONTHNAME(o.so_date)
            GROUP BY YEAR(o.so_date), MONTHNAME(o.so_date), s.Total_Sales  ORDER BY MONTHNAME(o.so_date) DESC
        ");

        return collect($results); 
    }
}



