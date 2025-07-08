<?php

namespace App\Factories\SalesOrder;

use App\Factories\SalesOrder\SetInterface;
use DB;

class Factory implements SetInterface
{

    public function getindex()
    {
        $results = DB::select("
        SELECT o.id,
            o.so_number,
            o.so_date,
            s.name AS customer,
            CONCAT(e.firstname,' ',e.middlename,'. ',e.lastname) AS sales_agent,
            o.total_sales,
            o.status,
            o.inventory_deducted
        FROM sales_order o
        INNER JOIN customers s
        ON o.customer_id = s.id
        INNER JOIN employees e
        ON o.employee_id = e.id
        ORDER BY o.id DESC");

        return collect($results);
    } 

    public function getSONo()
    { 
    $orderObj = DB::table('sales_order')->select('so_number')->latest('id')->first();
   
    $yr = date('Y');
    if ($orderObj) {
        $orderNr = $orderObj->so_number;
        $removed1char = substr($orderNr, 4);
        $generateOrder_nr = $stpad = $yr. str_pad($removed1char + 1, 5, "0", STR_PAD_LEFT);
        //$generateOrder_nr = $stpad = str_pad($removed1char + 1, 5, "0", STR_PAD_LEFT);
      } else {
          $generateOrder_nr = $yr. str_pad(1, 5, "0", STR_PAD_LEFT);
      }
      return $generateOrder_nr;    
     }
  
    public function getInventoryItems($id)
    {
        $results = DB::select("
            SELECT i.id,
                    e.id AS item_id,
                     e.name AS item_name,
                     e.description,
                     CONCAT(i.unit_quantity,' - ',u.code) AS untis,
                    i.unit_quantity,
                     i.onhand_quantity,
                     e.srp,
                     i.status
            FROM  items e
            LEFT JOIN  inventory i ON i.item_id = e.id
            LEFT JOIN unit_of_measure u ON u.id = e.unit_id 
            WHERE e.deleted_at IS NULL AND i.location = ? AND i.consumable = 0;",[$id]);

        return collect($results);

    }

    public function getCSitems($cs)
    {
        $results = DB::select("
            SELECT  c.item_id,
                    i.name AS item_name,
                    i.description,
                    u.code AS units,
                    i.srp,
                    ifnull(c.srp_discounted,0) as dis_amount,
                    ifnull(c.percentage_discount,0) as dis_percent,
                    c.set_srp,
                    i.unit_cost
            FROM customer_prices c
            INNER JOIN items i ON c.item_id = i.id
            INNER JOIN unit_of_measure u ON u.id = i.unit_id
            WHERE c.customer_id = ? AND activated_discount = 1",[$cs]);

        return collect($results);

    }

   public function getSetItems($id)
    {
        $results = DB::select("
            SELECT  i.id as item_id,
                    i.name AS item_name,
                    i.description,
                    u.code AS units,
                    i.srp,
                    '0.00' AS  dis_amount,
                    '0.00' AS dis_percent,
                    i.srp as set_srp
            FROM items i 
            INNER JOIN unit_of_measure u ON u.id = i.unit_id
            WHERE i.id = ?;",[$id]);

        return collect($results);
    }

   public function getForSOitems($id)
    {
        $results = DB::select("
                SELECT i.id,
                     e.name AS item_name,
                     e.description,
                     CONCAT('(',i.unit_quantity,') ',u.code) AS units,
                     u.code as unti_code,
                     s.order_quantity,
                    s.unit_cost,
                    s.srp,
                    s.set_srp,
                    s.discount_amount,
                    s.discount_percentage,
                    s.sub_amount,
                    CONCAT(e.description,' - ',cast(s.order_quantity  AS INT),' ',u.code) as draftname
               FROM inventory i
               INNER JOIN items e
               ON e.id = i.item_id
               INNER JOIN unit_of_measure u
               ON e.unit_id = u.id
               INNER JOIN sales_order_items s
               ON s.item_id = i.item_id
               WHERE  i.consumable = 0  AND s.sales_order_id = ?;",[$id]);

        return collect($results);

    }

    public function employee_agent()
    {
       $results = DB::select("
        SELECT e.id, CONCAT(e.firstname ,' ',e.lastname ) AS emp_name FROM agent_team a
        LEFT JOIN employees e ON a.employee_id = e.id
        GROUP BY e.id, CONCAT(e.firstname ,' ',e.lastname )");
        return collect($results);
    } 

    public function getaddeditemprice($itemID,$areaID)
    {
       $results = DB::select("
        SELECT srp_added as dis_amount, percentage_added as dis_percent,set_srp FROM area_prices
        WHERE item_id = ? AND area_id = ? AND activated_added = 1",[$itemID,$areaID]);
        return collect($results);
    } 
}
    