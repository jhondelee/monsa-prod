<?php

namespace App\Factories\Dashboard;


interface SetInterface {

	
      public function sales_of_previous_month();

      public function sales_of_current_month();

      public function current_year();

      public function order_of_previous_month();

      public function order_of_current_month();

      public function getinactivecs();

      public function gettopsalesteam();

      public function getMonthSales();


}
