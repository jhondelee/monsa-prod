<?php

namespace App\Factories\Item;

interface SetInterface {
    
     public function getindex();
    
     public function getItemNo();

     public function getForPO($id);
     
     public function getiteminfo($id);

     public function getsupplierItems($id);

     public function showSupplierItems($id);

     public function additemSupplier($id);

     public function getitemList();

       public function getitemname($name);
     
}
