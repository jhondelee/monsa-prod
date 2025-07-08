<?php

namespace App\Factories\Item;

use App\Factories\Item\SetInterface;
use App\Item;
use App\UnitOfMeasure;
use DB;

class Factory implements SetInterface
{

    public function getindex()
    {

     $results = DB::select("
        SELECT i.id,
        i.code,
        i.name,
        i.description,
        (case when ifnull(e.unit_quantity,0) > 0  then CONCAT('(',e.unit_quantity,') ',u.code) ELSE u.code END )as units,
        i.picture,
        i.unit_cost,
        i.srp,
        i.free, 
        i.activated
        FROM items i 
        INNER JOIN unit_of_measure u
        ON i.unit_id = u.id
        LEFT JOIN inventory e 
        ON i.id = e.item_id
        WHERE i.deleted_at is NULL
        ORDER BY i.id;");

        return collect($results);
    } 

   
   public function getitemname($name)
    {

     $results = DB::select("
             SELECT i.id,
        i.code,
        i.name,
        i.description,
         u.name as units,
         u.code as unit_code,
        i.picture,
        i.unit_cost,
        i.srp,
        i.free,
        i.activated
        FROM items i 
        INNER JOIN unit_of_measure u
        ON i.unit_id = u.id
        WHERE i.deleted_at is NULL and i.name = ?
        ORDER BY i.id;",[$name]);

         return collect($results);
    }

    public function getitemList()
    {

     $results = DB::select("
        SELECT i.id,
        i.code,
        i.name,
        i.description,
         u.name as units,
         u.code as unit_code,
        i.picture,
        i.unit_cost,
        i.srp,
        i.free,
        i.activated
        FROM items i 
        INNER JOIN unit_of_measure u
        ON i.unit_id = u.id
        WHERE i.deleted_at is NULL 
        ORDER BY i.id;");

        return collect($results);
    } 

    public function getItemNo()
    { 
    $orderObj = DB::table('items')->select('code')->latest('id')->first();
    $yr = date('Y');
    if ($orderObj) {
        $orderNr = $orderObj->code;
        $removed1char = substr($orderNr, 4);
        $generateOrder_nr = $stpad = $yr. str_pad($removed1char + 1, 5, "0", STR_PAD_LEFT);
        //$generateOrder_nr = $stpad = str_pad($removed1char + 1, 5, "0", STR_PAD_LEFT);
      } else {
          $generateOrder_nr = $yr. str_pad(1, 5, "0", STR_PAD_LEFT);
      }
      return $generateOrder_nr;    
     }

     public function getsupplieritems($id)
     {
        $results = DB::select("
            SELECT i.id,
            i.code,
            i.name,
            i.description,
            u.code as units,
            i.picture,
            i.free
            FROM items i 
            INNER JOIN unit_of_measure u
            ON i.unit_id = u.id
            INNER JOIN supplier_items s
            ON s.item_id = i.id
            WHERE i.deleted_at is NULL AND s.supplier_id = ?
            ORDER BY i.id;",[$id]);

        return collect($results);
     }


     public function getForPO($id)
    {

     $results = DB::select("
           SELECT i.id,
           i.code,
           i.name,
           i.description,
           u.code as units,
           o.quantity,
           i.unit_cost,
           o.unit_total_cost,
           i.free
           FROM items i 
           INNER JOIN unit_of_measure u
           ON i.unit_id = u.id
           INNER JOIN order_items o
           ON i.id = o.item_id
           WHERE i.deleted_at is NULL AND o.order_id = ?
           ORDER BY i.id;",[$id]);

        return collect($results);
    } 


    public function getiteminfo($id)
    {

     $results = DB::select("
        SELECT i.id,
        i.code,
        i.name,
        i.description,
        u.code as units,
        i.picture,
        i.unit_cost,
        i.unit_quantity,
        i.free
        FROM items i 
        INNER JOIN unit_of_measure u
        ON i.unit_id = u.id
        WHERE i.deleted_at is NULL AND i.id =?
        ORDER BY i.id;",[$id]);

        return collect($results);
    } 


    public function GetItemsOfsupplier($id)
    {
      $results = DB::select("
               SELECT e.id,
                     e.name AS item_name,
                     CONCAT('(',i.unit_quantity,') ',u.code) AS units,
                     i.onhand_quantity,
                     e.unit_cost,
                     i.`status`
               FROM inventory i
               INNER JOIN items e
               ON e.id = i.item_id
               INNER JOIN unit_of_measure u
               ON e.unit_id = u.id
               INNER JOIN supplier_items s
               ON s.item_id = i.item_id
               WHERE e.activated = 1 AND s.supplier_id = ?;",[$id]);

         return collect($results);
    }

   public function showSupplierItems($id)
   {
      $results=DB::select("
               SELECT e.id,
                      e.name AS item_name,
                      e.description,
                      u.code AS units
               FROM items e
               INNER JOIN unit_of_measure u
               ON e.unit_id = u.id
               INNER JOIN supplier_items s
               ON s.item_id = e.id
               WHERE e.activated = 1 AND s.supplier_id = ?;",[$id]);

      return collect($results);
      
   }


   public function additemSupplier($id)
   {
      $results=DB::select("
               SELECT e.id,
                     e.name AS item_name,
                     e.description,
                     CONCAT('(',i.unit_quantity,') ',u.code) AS units,
                     i.onhand_quantity,
                     i.`status`
               FROM inventory i
               INNER JOIN items e
               ON e.id = i.item_id
               INNER JOIN unit_of_measure u
               ON e.unit_id = u.id
               INNER JOIN supplier_items s
               ON s.item_id = i.item_id
               WHERE e.activated = 1 AND s.supplier_id = ?;",[$id]);

      return collect($results);
      
   }


  
}

