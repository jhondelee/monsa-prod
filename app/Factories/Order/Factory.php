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

  
}
