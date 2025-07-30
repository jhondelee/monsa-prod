<?php

namespace App\Factories\SalesReport;

interface SetInterface {
    
     public function getindex();

     public function paymentAll($startdate, $enddate);

     public function paymentperMode($startdate, $enddate, $paymode);

     public function paymentCashMode($startdate, $enddate, $paymode);

     public function paymentGCashMode($startdate, $enddate, $paymode);

     public function GetGCashReceiver($startdate, $enddate, $paymode);
 
     public function GetCheQueStatus($startdate, $enddate, $paymode);

     public function AllpaymenCustomer($startdate, $enddate, $customer);

     public function GetCustomerPaymodeStatus($startdate, $enddate, $customer);

     public function CustomerPaymode($startdate, $enddate, $customer,$mode);

     public function GetCustomerGCashReceiver($startdate, $enddate, $customer,$mode);   

     public function GetCustomerCheQueStatus($startdate, $enddate, $customer,$mode);  

     public function CollectCustomerSales($startdate,$enddate);

     public function CollectSalesItems($startdate,$enddate);

     public function CollectCustomerBalance($areas);
    
 }
