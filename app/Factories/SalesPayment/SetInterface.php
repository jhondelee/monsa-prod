<?php

namespace App\Factories\SalesPayment;

interface SetInterface {
    
     public function getindex();

      public function showpayments($salespayment_id);

       public function totalpaid($salespayment_id);
}
