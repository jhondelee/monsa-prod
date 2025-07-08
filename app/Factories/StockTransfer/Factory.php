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
            $results = DB::SELECT ('
                     SELECT  w.id as inventory_id,
                           i.id AS item_id,
                           i.code AS item_code,
                           i.name,
                           w.onhand_quantity,
                           w.location,
                           w.received_date
                    FROM inventory w
                    INNER JOIN items i
                    ON i.id = w.item_id
                    INNER JOIN unit_of_measure u
                    ON u.id = i.unit_id
                    WHERE w.location = ? AND w.unit_quantity > 0
                    ORDER BY w.received_date DESC;',[$source]);

         return collect($results);
    }
  
}
