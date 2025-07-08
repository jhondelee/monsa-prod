<?php

namespace App\Factories\Condemn;

use App\Factories\Condemn\SetInterface;
use DB;

class Factory implements SetInterface
{

    public function getindex()
    {

     $results = DB::select("
            SELECT c.id,
                c.reference_no,
                c.condemn_date,
                c.reason,
                c.`status`,
                CONCAT(e.firstname,' ',e.lastname) AS created_by
             FROM condemn c
          INNER JOIN employees e
          ON c.created_by = e.id
          ORDER BY c.id DESC;");

        return collect($results);
    } 
  

    public function AddfromInventoryItem($source)
    {
            $results = DB::SELECT ("
                   SELECT  w.id as inventory_id,
                           i.id AS item_id,
                           i.code AS item_code,
                           i.description as name,
                           u.code as units,
                           w.unit_quantity as onhand_quantity,
                           o.name AS location,
                           w.received_date
                    FROM inventory w
                    INNER JOIN items i
                    ON i.id = w.item_id
                    INNER JOIN unit_of_measure u
                    ON u.id = i.unit_id
                    INNER JOIN warehouse_location o
                    ON o.id = w.location
                    WHERE w.location = ? AND w.unit_quantity > 0 AND w.consumable = 0
                    ORDER BY w.received_date DESC;",[$source]);

         return collect($results);
    }

    public function AddfromConsumableItem($source)
    {
            $results = DB::SELECT ("
                   SELECT  w.id as inventory_id,
                           i.id AS item_id,
                           i.code AS item_code,
                           i.description as name,
                           u.code as units,
                           w.unit_quantity as onhand_quantity,
                           o.name AS location,
                           w.received_date
                    FROM inventory w
                    INNER JOIN items i
                    ON i.id = w.item_id
                    INNER JOIN unit_of_measure u
                    ON u.id = i.unit_id
                    INNER JOIN warehouse_location o
                    ON o.id = w.location
                    WHERE w.location = ? AND w.unit_quantity > 0 AND w.consumable = 1
                    ORDER BY w.received_date DESC;",[$source]);

         return collect($results);
    }

    public function AddfromReturnItem($source)
    {
            $results = DB::SELECT ("
                   SELECT  w.id as inventory_id,
                           i.id AS item_id,
                           i.code AS item_code,
                           i.description as name,
                           u.code as units,
                           w.unit_quantity as onhand_quantity,
                           o.name AS location,
                           w.received_date
                    FROM inventory w
                    INNER JOIN items i
                    ON i.id = w.item_id
                    INNER JOIN unit_of_measure u
                    ON u.id = i.unit_id
                    INNER JOIN warehouse_location o
                    ON o.id = w.location
                    WHERE w.location = ? AND w.unit_quantity > 0 AND w.consumable = 2
                    ORDER BY w.received_date DESC;",[$source]);

         return collect($results);
    }

    public function getCondemnItesms($id)
    {
            $results = DB::SELECT ("              
                SELECT c.id,
                        c.source,
                        i.description as name,
                        u.code AS units,
                        c.unit_quantity 
                FROM condemn_items c
                INNER JOIN items i ON c.item_id = i.id
                INNER JOIN unit_of_measure u ON u.id = i.unit_id
                WHERE c.condemn_id = ?",[$id]);

         return collect($results);
    }
}
