<?php

namespace App\Factories\SalesReport;

interface SetInterface {
    
     public function getindex();

     public function paymentAll($startdate, $enddate);

     public function paymentperMode($startdate, $enddate, $paymode);

     public function paymentCashMode($startdate, $enddate, $paymode);

    public function paymentGCashMode($startdate, $enddate, $paymode);

    public function GCashReceiver($startdate, $enddate, $paymode);
    
 }
