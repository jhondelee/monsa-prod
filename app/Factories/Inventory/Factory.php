<?php

namespace App\Factories\Inventory;

use App\Factories\Inventory\SetInterface;
use App\Inventory;
use App\Item;
use DB;

class Factory implements SetInterface
{

    public function getindex()
    {
     $results = DB::select("
       SELECT
            i.id,
            e.id as item_id,
            e.name,
            e.description,
            u.code AS units,
            e.srp,
            i.unit_quantity,
            w.name as location,
            e.picture,
            i.`status`
        FROM inventory i
        INNER JOIN items e
        ON i.item_id = e.id AND e.activated = 1
        INNER JOIN unit_of_measure u
        ON u.id = e.unit_id
        INNER JOIN warehouse_location w
        ON w.id = i.location
        WHERE  e.deleted_at is NULL AND i.consumable = 0
        AND i.unit_quantity > 0;");

        return collect($results);
    } 

    public function getreturnindex()
    {
     $results = DB::select("
       SELECT
            i.id,
            e.id as item_id,
            e.name,
            e.description,
            u.code AS units,
            e.srp,
            i.onhand_quantity,
            w.name as location,
            w.id as loc_id,
            e.picture,
            i.`status`,
            i.unit_quantity
        FROM inventory i
        INNER JOIN items e
        ON i.item_id = e.id AND e.activated = 1
        INNER JOIN unit_of_measure u
        ON u.id = e.unit_id
        INNER JOIN warehouse_location w
        ON w.id = i.location
        WHERE  e.deleted_at is NULL AND i.consumable = 2
        AND i.unit_quantity > 0;");

        return collect($results);
    } 



    public function addInventoryItem(){
          $results = DB::SELECT ( '
                   SELECT i.id, 
                    concat(i.description," - ",u.code) as item_name 
                    FROM items i
                    LEFT JOIN unit_of_measure u
                    ON i.unit_id = u.id
                    WHERE  i.deleted_at is NULL AND i.activated=1;');
          return collect($results);
    }


    public function showItem($id)
    {
            $results = DB::SELECT ('
                    SELECT  i.id,
                           i.name,
                           i.code,
                           i.srp,
                           i.description,
                           u.name as units,
                           i.picture,
                           e.name AS supplier_name
                    FROM items i
                    INNER JOIN unit_of_measure u
                    ON u.id = i.unit_id
                    INNER JOIN supplier_items s
                    ON s.item_id = i.id
                    INNER JOIN suppliers e
                    ON e.id = s.supplier_id 
                    WHERE i.id = ?',[$id]);
         return collect($results);
    } 

        public function showlocations($id)
    {
            $results = DB::SELECT ('
                    SELECT  e.id,
                            a.name AS location,
                            i.code,
                           u.code AS units,
                           e.onhand_quantity,
                           e.unit_quantity,
                           e.received_date
                    FROM  inventory e
                    INNER JOIN items i
                    ON e.item_id = i.id
                    INNER JOIN unit_of_measure u
                    ON u.id = i.unit_id
                    INNER JOIN warehouse_location a
                    ON a.id = e.location
                    WHERE i.activated = 1 AND i.id = ?',[$id]);
         return collect($results);
    }


     public function getconsumables()
    {

     $results = DB::select("
       SELECT
            i.id,
            e.id as item_id,
            e.name,
            e.description,
            u.code AS units,
            e.srp,
            i.onhand_quantity,
            w.name as location,
            e.picture,
            i.`status`
        FROM inventory i
        INNER JOIN items e
        ON i.item_id = e.id AND e.activated = 1
        INNER JOIN unit_of_measure u
        ON u.id = e.unit_id
        INNER JOIN warehouse_location w
        ON w.id = i.location
        WHERE  e.deleted_at is NULL AND i.consumable = 1;");

        return collect($results);
    } 

    public function requestlist()
    {
        $results = DB::select("
            SELECT i.id,
                     i.reference_no,
                     e.name AS item_name,
                     e.description,
                     u.code AS units,
                     i.request_qty,
                     i.posted,
                     (CONCAT(o.firstname,' ',o.lastname)) AS emp_name,
                      i.inventory_id,
                      i.created_at
            FROM item_request i
            INNER JOIN items e ON i.item_id = e.id
            INNER JOIN unit_of_measure u ON u.id = e.unit_id 
            INNER JOIN employees o ON o.user_id = i.created_by
            ORDER BY id desc");

        return collect($results);
    }

    public function getinventory()
    {
     $results = DB::select("
        SELECT
            e.id as item_id,
            e.name,
            e.description,
            u.code AS units,
            e.srp,
            SUM(i.onhand_quantity) AS onhand_quantity,
            i.`status`
        FROM inventory i
        INNER JOIN items e
        ON i.item_id = e.id AND e.activated = 1
        INNER JOIN unit_of_measure u
        ON u.id = e.unit_id
        INNER JOIN warehouse_location w
        ON w.id = i.location
        WHERE  e.deleted_at is NULL AND i.consumable = 0
        GROUP BY e.id,e.name,e.description,e.srp,i.status,u.code;");

        return collect($results);
    }


    public function getItemStockLevel($id, $unitQty)
    {
        $items = Item::find($id);

        $safelvl = $items->safety_stock_level;

        $critlvl = $items->criticl_stock_level;

        $status ='';

        If ( $unitQty >= $safelvl )
        {

            $status = "In Stock";

        }

        If ( $unitQty < $safelvl && $unitQty > $critlvl )
        {

            $status = "Reorder";

        }


        If ( $unitQty <= $critlvl &&  $unitQty > 0 )
        {

            $status = "Critical";

        }

        If ( $unitQty = 0 ){

            $status = "Out of Stock";

        }
       


        return $status;

    }
  

    public function InventoryStatusUpdate($inventory_id, $status)
    {
        $inventory = Inventory::find($inventory_id);

        $inventory->status =  $status;

        $inventory->save();

     }
  
   public function showstatus($status)
    {
     $results = DB::select("
           SELECT
                i.id,
                e.id as item_id,
                e.name,
                e.description,
                u.code AS units,
                e.srp,
                i.onhand_quantity,
                w.name as location,
                e.picture,
                i.`status`
            FROM inventory i
            INNER JOIN items e
            ON i.item_id = e.id AND e.activated = 1
            INNER JOIN unit_of_measure u
            ON u.id = e.unit_id
            INNER JOIN warehouse_location w
            ON w.id = i.location
            WHERE  e.deleted_at is NULL AND i.consumable = 0
            AND i.unit_quantity > 0 AND i.`status` = ? ",[$status]);

        return collect($results);
    } 
}
