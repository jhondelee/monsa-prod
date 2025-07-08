<?php

namespace App\Factories\Inventory;

use App\Factories\Inventory\SetInterface;
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
            CONCAT('(',i.unit_quantity,') ',u.code) AS units,
            e.srp,
            i.onhand_quantity,
            w.name as location,
            e.picture,
            i.`status`
        FROM inventory i
        INNER JOIN items e
        ON i.item_id = e.id
        INNER JOIN unit_of_measure u
        ON u.id = e.unit_id
        INNER JOIN warehouse_location w
        ON w.id = i.location
        WHERE  e.deleted_at is NULL AND i.consumable = 0;");

        return collect($results);
    } 


    public function addInventoryItem(){
          $results = DB::SELECT ( '
                   SELECT i.id, 
                    concat(i.id," - ",i.name," - ",i.description," - ",u.code) as item_name 
                    FROM items i
                    LEFT JOIN unit_of_measure u
                    ON i.unit_id = u.id
                    WHERE  i.deleted_at is NULL;');
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
                           i.picture
                    FROM items i
                    INNER JOIN unit_of_measure u
                    ON u.id = i.unit_id
                    WHERE i.id  = ?',[$id]);
         return collect($results);
    } 

        public function showlocations($id)
    {
            $results = DB::SELECT ('
                    SELECT  e.id,
                            a.name AS location,
                            i.code,
                           CONCAT("(",e.unit_quantity,") ",u.code) AS units,
                           e.onhand_quantity,
                           e.received_date
                    FROM  inventory e
                    INNER JOIN items i
                    ON e.item_id = i.id
                    INNER JOIN unit_of_measure u
                    ON u.id = i.unit_id
                    INNER JOIN warehouse_location a
                    ON a.id = e.location
                    WHERE i.id = ?',[$id]);
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
            CONCAT('(',i.unit_quantity,') ',u.code) AS units,
            e.srp,
            i.onhand_quantity,
            w.name as location,
            e.picture,
            i.`status`
        FROM inventory i
        INNER JOIN items e
        ON i.item_id = e.id
        INNER JOIN unit_of_measure u
        ON u.id = e.unit_id
        INNER JOIN warehouse_location w
        ON w.id = i.location
        WHERE  e.deleted_at is NULL AND i.consumable = 1;");

        return collect($results);
    } 

    public function getinventory()
    {
     $results = DB::select("
        SELECT
            e.id as item_id,
            e.name,
            e.description,
            CONCAT('(',SUM(i.unit_quantity),') ',u.code) AS units,
            e.srp,
            SUM(i.onhand_quantity) AS onhand_quantity,
            i.`status`
        FROM inventory i
        INNER JOIN items e
        ON i.item_id = e.id
        INNER JOIN unit_of_measure u
        ON u.id = e.unit_id
        INNER JOIN warehouse_location w
        ON w.id = i.location
        WHERE  e.deleted_at is NULL AND i.consumable = 0
        GROUP BY e.id,e.name,e.description,e.srp,i.status,u.code;");

        return collect($results);
    }
  
}
