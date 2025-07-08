<?php

namespace App\Factories\Inventory;

interface SetInterface {
    
     public function getindex();
     
     public function showlocations($id);

     public function showItem($id);

     public function getconsumables();

      public function getinventory();

     public function InventoryStatusUpdate($inventory_id,$status);

     public function getItemStockLevel($id,$unitQty);

     public function getreturnindex();

      public function showstatus($status);


}
