<?php

namespace App\Factories\Customer;

interface SetInterface {
    
     public function getindex();

     public function getCustomerItemSrp($customerID);

     public function getItemFromCustomer($customerID,$itemID);

     public function getAreasItemSrp($areaID);

}
