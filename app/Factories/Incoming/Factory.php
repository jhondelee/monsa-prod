<?php

namespace App\Factories\Incoming;

use App\Factories\Incoming\SetInterface;
use DB;

class Factory implements SetInterface
{

    public function getindex()
    {

     $results = DB::select("
        SELECT i.id,
          i.order_id,
          i.po_number,
          i.dr_number,
          i.dr_date,
          s.name AS supplier,
          CONCAT(e.firstname,' ',e.middlename,'. ',e.lastname) AS received_by,
          i.`status` FROM incomings i
          INNER JOIN employees e
          ON i.received_by = e.id
          INNER JOIN orders o
          ON o.id = i.order_id
          INNER JOIN suppliers s
          ON s.id = o.supplier_id
          ORDER BY i.id DESC;");

        return collect($results);
    } 

    public function getIncomingItems($id)
    {

     $results = DB::select("
            SELECT 
                i.item_id as id,
                e.code,
                e.name,
                e.description,
                u.code AS units,
                i.unit_cost,
                ifnull(s.quantity,0) as quantity,
                i.received_quantity,
                i.unit_total_cost,
                e.free,
                n.status
            FROM incomings n
            INNER JOIN incoming_items i
            ON n.id = i.incoming_id
            INNER JOIN items e
            ON e.id = i.item_id
            INNER JOIN unit_of_measure u
            ON u.id = e.unit_id
            LEFT JOIN order_items s
            ON s.order_id = n.order_id AND s.item_id = i.item_id
            WHERE i.incoming_id = ?;",[$id]);

        return collect($results);
    } 

  
}
