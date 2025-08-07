<?php

namespace App\Factories\CommissionReport;

interface SetInterface {
   
   public function getctrCommission($start, $end, $empID);

   public function getCommissions($start, $end, $empID);
   
}
