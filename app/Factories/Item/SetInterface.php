<?php

namespace App\Factories\Item;

interface SetInterface {
    
     public function getindex();
    
     public function getItemNo();

     public function getForPO($id);
     
     public function getiteminfo($id);

     public function getsupplierItems($id);
}
