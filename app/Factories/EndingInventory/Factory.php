<?php

namespace App\Factories\EndingInventory;

use App\Factories\EndingInventory\SetInterface;
use DB;

class Factory implements SetInterface
{

    public function getindex()
    {

     $results = DB::select("
        SELECT i.id,
          i.ending_date,
          CONCAT(e.firstname,' ',e.middlename,'. ',e.lastname) AS prepared_by,
          i.`status` 
          FROM ending_inventory i
          INNER JOIN employees e
          ON i.prepared_by = e.id
          ORDER BY i.id DESC;");

        return collect($results);
    } 


    public function getinventory()
    {

     $results = DB::select("
            SELECT i.id,
                e.id AS item_id,
                e.code,
                e.name,
                e.description,
                u.code AS units,
                i.unit_quantity,
                i.onhand_quantity,
                i.unit_cost,
                i.received_date,
                c.name AS location
             FROM inventory i
             INNER JOIN items e
             ON i.item_id = e.id
             INNER JOIN unit_of_measure u
             ON u.id = e.unit_id 
             INNER JOIN warehouse_location c
             ON c.id = i.location
             WHERE i.unit_quantity > 0 AND i.onhand_quantity > 0
             ORDER BY i.id;");

        return collect($results);
    } 


    public function getEndingInventory($id)
    {

     $results = DB::select("
            SELECT i.id,
                e.id AS item_id,
                e.code,
                e.name,
                e.description,
                u.code AS units,
                CONCAT('(',i.unit_quantity,') ',u.code) AS qty_units_code,
                i.unit_quantity,
                i.onhand_quantity,
                i.unit_cost,
                i.received_date,
                c.name AS location
             FROM ending_inventory_items i
             INNER JOIN items e
             ON i.item_id = e.id
             INNER JOIN unit_of_measure u
             ON u.id = e.unit_id 
             INNER JOIN warehouse_location c
             ON c.id = i.location
             WHERE i.unit_quantity > 0 AND i.onhand_quantity > 0 AND i.ending_inventory_id = ?
             ORDER BY i.id;",[$id]);

        return collect($results);
    } 

  
}
