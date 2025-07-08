<?php

namespace App\Factories\SalesOrder;

interface SetInterface {
    
     public function getindex();
    
     public function getSONo();

     public function getInventoryItems($id);

     public function getCSitems($cs);

     public function getSetItems($id);

     public function getForSOitems($id);

     public function employee_agent();

     public function getaddeditemprice($itemID,$areaID);
     

}
