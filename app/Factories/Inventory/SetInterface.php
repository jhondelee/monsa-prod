<?php

namespace App\Factories\Inventory;

interface SetInterface {
    
     public function getindex();
     
     public function showlocations($id);

     public function showItem($id);

     public function getconsumables();

      public function getinventory();
}
