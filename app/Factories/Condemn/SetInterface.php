<?php

namespace App\Factories\Condemn;

interface SetInterface {
    
     public function getindex();

     public function AddfromInventoryItem($source);

      public function AddfromReturnItem($source);

      public function AddfromConsumableItem($source);

     public function getCondemnItesms($id);

     
}
