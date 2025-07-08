<?php

namespace App\Factories\Order;

use App\Factories\Order\SetInterface;
use DB;

class Factory implements SetInterface
{

    public function getindex()
    {
        $results = DB::select("
        SELECT o.id,
            o.po_number,
            o.po_date,
            s.name AS supplier,
            o.grand_total,
            o.status
        FROM orders o
        INNER JOIN suppliers s
        ON o.supplier_id = s.id
        WHERE o.deleted_at IS null
        ORDER BY o.id DESC");

        return collect($results);
    } 

    public function getPONo()
    { 
    $orderObj = DB::table('orders')->select('po_number')->latest('id')->first();
   
    $yr = date('Y');
    if ($orderObj) {
        $orderNr = $orderObj->po_number;
        $removed1char = substr($orderNr, 4);
        $generateOrder_nr = $stpad = $yr. str_pad($removed1char + 1, 5, "0", STR_PAD_LEFT);
        //$generateOrder_nr = $stpad = str_pad($removed1char + 1, 5, "0", STR_PAD_LEFT);
      } else {
          $generateOrder_nr = $yr. str_pad(1, 5, "0", STR_PAD_LEFT);
      }
      return $generateOrder_nr;    
     }

     public function orderToSupplier($id)
     {
         $results = DB::SELECT("
             SELECT 
                i.id,
                i.name,
                i.description,
                i.free,
                (case when ifnull(e.unit_quantity,0) > 0  then CONCAT('(',e.unit_quantity,') ',u.code) ELSE u.code END ) AS  units,
                IFNULL(e.onhand_quantity,0) as onhand_quantity,
                (case when e.unit_quantity != 0 OR NULL then 'In Stock' ELSE 'Out of Stock'  END ) AS  status
            FROM supplier_items s
            INNER JOIN items i ON s.item_id = i.id
            LEFT  join inventory e ON e.item_id = s.item_id
            INNER JOIN unit_of_measure u ON u.id = i.unit_id
            WHERE i.activated = 1 AND s.supplier_id = ?
            ORDER BY e.unit_quantity DESC",[$id]);

          return collect($results);
     }
  
}
