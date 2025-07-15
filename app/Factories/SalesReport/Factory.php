<?php

namespace App\Factories\SalesReport;

use App\Factories\SalesReport\SetInterface;
use DB;

class Factory implements SetInterface
{

    public function getindex()
    {
        $results = DB::select("
        SELECT * FROM sales_payment");

        return collect($results);
    } 

    public function paymentAll($startdate, $enddate)
    {
        $results = DB::select("SELECT e.date_payment, c.address, o.so_number, c.name AS cs_name , m.name AS paymode, e.amount_collected,s.payment_status
            FROM sales_payment_terms e
            INNER JOIN sales_payment s ON s.id = e.sales_payment_id
            INNER JOIN sales_order o ON o.id = s.sales_order_id
            INNER JOIN customers c ON c.id = o.customer_id
            INNER JOIN mode_of_payments m ON m.id = e.payment_mode_id
            WHERE date_payment BETWEEN ? AND ?
            ORDER BY c.address,date_payment ASC;",[$startdate,$enddate]);

        return collect($results);
    }

    public function paymentperMode($startdate, $enddate, $paymode)
    {
        $results = DB::select("SELECT e.date_payment, c.address, o.so_number, c.name AS cs_name , m.name AS paymode, e.amount_collected,s.payment_status
            FROM sales_payment_terms e
            INNER JOIN sales_payment s ON s.id = e.sales_payment_id
            INNER JOIN sales_order o ON o.id = s.sales_order_id
            INNER JOIN customers c ON c.id = o.customer_id
            INNER JOIN mode_of_payments m ON m.id = e.payment_mode_id
            WHERE date_payment BETWEEN ? AND ? AND m.id = ?
            ORDER BY c.address,date_payment ASC;",[$startdate,$enddate,$paymode]);

        return collect($results);
    }

    public function paymentCashMode($startdate, $enddate, $paymode)
    {
        $results = DB::select("SELECT e.date_payment, c.address, o.so_number, c.name AS cs_name ,e.amount_collected
            FROM sales_payment_terms e
            INNER JOIN sales_payment s ON s.id = e.sales_payment_id
            INNER JOIN sales_order o ON o.id = s.sales_order_id
            INNER JOIN customers c ON c.id = o.customer_id
            INNER JOIN mode_of_payments m ON m.id = e.payment_mode_id
            WHERE date_payment BETWEEN ? AND ? AND m.id = ?
            ORDER BY c.address,date_payment ASC;",[$startdate,$enddate,$paymode]);

        return collect($results);
    }
}
    