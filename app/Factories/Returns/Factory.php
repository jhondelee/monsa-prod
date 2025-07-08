<?php

namespace App\Factories\Returns;

use App\Factories\Returns\SetInterface;
use DB;

class Factory implements SetInterface
{


    public function getindex()
    {

        $results = DB::select("
            SELECT r.id, r.reference_no , 
                r.so_number , 
                r.return_date, 
                c.name AS customer_name, 
                r.received_by
            FROM returns r
            INNER JOIN sales_order s ON r.so_number = s.so_number
            INNER JOIN customers c ON c.id = s.customer_id
            ORDER BY r.id desc;");
     
        return collect($results);
    }

    
    public function referenceNo()
    { 

        $orderObj = DB::table('returns')->select('reference_no')->latest('id')->first();
   
        $yr = date('Y');
        if ($orderObj) {
            $orderNr = $orderObj->reference_no;
            $removed1char = substr($orderNr, 4);
            $generateOrder_nr = $stpad = $yr. str_pad($removed1char + 1, 5, "0", STR_PAD_LEFT);
            
            //$generateOrder_nr = $stpad = str_pad($removed1char + 1, 5, "0", STR_PAD_LEFT);

        } else {

            $generateOrder_nr = $yr. str_pad(1, 5, "0", STR_PAD_LEFT);
        }

        return $generateOrder_nr;   

    } 


    public function getsoitems($SoNum)
    {

        $results = DB::select("
            SELECT  s.id,
                     s.item_id,
                     i.name AS item_name,
                     i.description,
                     u.code AS unit,
                     s.order_quantity, 
                     s.unit_cost,
                     s.set_srp 
            FROM sales_order_items s
            INNER JOIN items i ON s.item_id = i.id
            INNER JOIN unit_of_measure u ON u.id = i.unit_id
            WHERE so_number = ?;",[$SoNum]);
     
        return collect($results);

    }


    public function getreturnitems($id)
    {

        $results = DB::select("
                SELECT s.id,
                     s.item_id ,
                     i.name AS item_name,
                     i.description,
                     u.code AS unit,
                     s.item_quantity, 
                     ifnull(s.return_quantity,0) AS return_quantity,
                     so.set_srp,
                     so.unit_cost,
                     REPLACE(FORMAT(ifnull(s.return_quantity * so.set_srp,0), 2), ',', '') AS amount
            FROM return_items s
            INNER JOIN returns r ON r.id = s.returns_id
            INNER JOIN sales_order_items so ON so.so_number = r.so_number AND s.item_id = so.item_id
            INNER JOIN items i ON s.item_id = i.id
            INNER JOIN unit_of_measure u ON u.id = i.unit_id
            WHERE s.returns_id = ?;",[$id]);
     
        return collect($results);

    }

    public function getreturntosupplier()
    {

        $results = DB::select("
                SELECT s.id,
                     s.item_id,
                     i.name AS item_name,
                     i.description,
                     u.code AS units,
                     CONCAT('(',s.return_unit_qty,') ',u.code) AS unit_qty,
                     r.name AS supplier_name,
                    s.return_date,
                    s.return_by
            FROM return_to_supplier s
            INNER JOIN items i ON s.item_id = i.id
            INNER JOIN unit_of_measure u ON u.id = i.unit_id
            INNER JOIN suppliers r ON r.id = s.supplier_id
            ORDER BY s.id Desc");
     
        return collect($results);

    }

     
}