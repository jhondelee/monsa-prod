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
        $results = DB::select("SELECT DATE_FORMAT(e.date_payment,'%m-%d-%Y') as date_payment, c.address, o.so_number, c.name AS cs_name , m.name AS paymode, e.amount_collected,s.payment_status
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
        $results = DB::select("SELECT eDATE_FORMAT(e.date_payment,'%m-%d-%Y') as date_payment, c.address, o.so_number, c.name AS cs_name , m.name AS paymode, e.amount_collected,s.payment_status
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
        $results = DB::select("SELECT DATE_FORMAT(e.date_payment,'%m-%d-%Y') as date_payment, c.address, o.so_number, c.name AS cs_name ,e.amount_collected
            FROM sales_payment_terms e
            INNER JOIN sales_payment s ON s.id = e.sales_payment_id
            INNER JOIN sales_order o ON o.id = s.sales_order_id
            INNER JOIN customers c ON c.id = o.customer_id
            INNER JOIN mode_of_payments m ON m.id = e.payment_mode_id
            WHERE date_payment BETWEEN ? AND ? AND m.id = ?
            ORDER BY c.address,date_payment ASC;",[$startdate,$enddate,$paymode]);

        return collect($results);
    }

    public function paymentGCashMode($startdate, $enddate, $paymode)
    {
        $results = DB::select("SELECT DATE_FORMAT(e.date_payment,'%m-%d-%Y') as date_payment, c.address, o.so_number, c.name AS cs_name , e.trasanction_no, 
            e.amount_collected,CONCAT(p.firstname,' ',p.lastname) AS collected_by
            FROM sales_payment_terms e
            INNER JOIN sales_payment s ON s.id = e.sales_payment_id
            INNER JOIN sales_order o ON o.id = s.sales_order_id
            INNER JOIN customers c ON c.id = o.customer_id
            INNER JOIN mode_of_payments m ON m.id = e.payment_mode_id
            INNER JOIN employees p ON p.user_id = e.collected_by
            WHERE date_payment BETWEEN ? AND ? AND m.id = ?
            ORDER BY c.address,date_payment ASC;",[$startdate,$enddate,$paymode]);

        return collect($results);
    }

    public function GCashReceiver($startdate, $enddate, $paymode)
    {
        $results = DB::select("SELECT CONCAT(p.firstname,' ',p.lastname) AS collected_by
            FROM sales_payment_terms e
            INNER JOIN employees p ON p.user_id = e.collected_by
            WHERE e.date_payment BETWEEN ? AND ? AND e.payment_mode_id = ?
            GROUP BY  CONCAT(p.firstname,' ',p.lastname) ORDER BY  CONCAT(p.firstname,' ',p.lastname);",[$startdate,$enddate,$paymode]);

        return collect($results);
    }


}
