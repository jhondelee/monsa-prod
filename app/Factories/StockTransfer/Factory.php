<?php

namespace App\Factories\StockTransfer;

use App\Factories\StockTransfer\SetInterface;
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
        WHERE  e.deleted_at is NULL;");

        return collect($results);
    } 

    public function AddTransferItem($source)
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

    
    public function getTransferList()
    {

     $results = DB::select("
        SELECT i.id,
            i.reference_no,
            a.name AS w_sourse,
            b.name AS w_destination,
            i.created_by,
            i.transfer_date,
            i.status
        FROM inventory_movement i
        INNER JOIN warehouse_location a
        ON a.id = i.source
        INNER JOIN warehouse_location b
        ON b.id = i.destination;");

        return collect($results);
    } 

    public function getforTransferitems($movement_id)
    {
        $results = DB::select("
            SELECT 
                m.inventory_id as item_id,
                w.name AS to_location,
                i.description,
                u.code AS units,
                m.quantity,
                o.name AS from_location
        FROM inventory_movement_items m
        INNER JOIN items i ON i.id = m.item_id
        INNER JOIN warehouse_location w ON w.id = m.to_location
        INNER JOIN warehouse_location o ON o.id = m.from_locaton  
        INNER JOIN unit_of_measure u ON u.id = i.unit_id 
        WHERE m.inventory_movement_id  = ?;",[$movement_id]);

        return collect($results);
    }

  
}
