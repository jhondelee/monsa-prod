<?php

namespace App\Factories\Customer;

use App\Factories\Customer\SetInterface;
use DB;

class Factory implements SetInterface
{

    public function getindex()
    {

     $results = DB::select("
                SELECT c.id,
                         c.name AS customer_name,
                         a.name AS customer_area,
                         c.address,
                         CONCAT(e.firstname,' ',e.lastname) AS created_by
                FROM customers c
                INNER JOIN areas a ON a.id = c.area_id
                INNER JOIN employees e ON c.created_by = e.id
          ORDER BY c.name;");

        return collect($results);
    } 

    public function getCustomerItemSrp($customerID)
    {

     $results = DB::select("
                SELECT c.id,
                        c.item_id,
                         i.name AS item_name,
                         i.description AS item_descript,
                         u.code AS item_units,
                         ifnull(c.unit_cost,0) AS item_cost,
                         ifnull(i.srp,0) AS item_srp,
                         ifnull(c.srp_discounted,0) AS amountD,
                         ifnull(c.percentage_discount,0) AS perD,
                         c.activated_discount AS disc_active,
                         ifnull(c.set_srp,0) AS setSRP
                FROM customer_prices c
                INNER JOIN items i ON c.item_id = i.id
                INNER JOIN unit_of_measure u ON i.unit_id = u.id
                WHERE c.customer_id = ?;",[$customerID]);

        return collect($results);
    } 
  
    public function getItemFromCustomer($customerID,$itemID)
    {
         $results = DB::select("
                SELECT * 
                FROM customer_prices 
                WHERE customer_id = ? AND item_id = ?;",[$customerID,$itemID]);

        return collect($results);
    }


    public function getAreasItemSrp($areaID)
    {

     $results = DB::select("
                SELECT c.id,
                        c.item_id,
                         i.name AS item_name,
                         i.description AS item_descript,
                         u.code AS item_units,
                         ifnull(c.unit_cost,0) AS item_cost,
                         ifnull(i.srp,0) AS item_srp,
                         ifnull(c.srp_added,0) AS amountD,
                         ifnull(c.percentage_added,0) AS perD,
                         c.activated_added AS disc_active,
                         ifnull(c.set_srp,0) AS setSRP
                FROM area_prices c
                INNER JOIN items i ON c.item_id = i.id
                INNER JOIN unit_of_measure u ON i.unit_id = u.id
                WHERE c.area_id = ?;",[$areaID]);

        return collect($results);
    }

}
