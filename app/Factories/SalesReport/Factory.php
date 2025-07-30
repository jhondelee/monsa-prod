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
            WHERE e.date_payment BETWEEN ? AND ?
            ORDER BY c.address,date_payment ASC;",[$startdate,$enddate]);

        return collect($results);
    }

    public function paymentperMode($startdate, $enddate, $paymode)
    {
        $results = DB::select("SELECT DATE_FORMAT(e.date_payment,'%m-%d-%Y') as date_payment, c.address, o.so_number, c.name AS cs_name , m.name AS paymode, e.amount_collected,s.payment_status
            FROM sales_payment_terms e
            INNER JOIN sales_payment s ON s.id = e.sales_payment_id
            INNER JOIN sales_order o ON o.id = s.sales_order_id
            INNER JOIN customers c ON c.id = o.customer_id
            INNER JOIN mode_of_payments m ON m.id = e.payment_mode_id
            WHERE e.date_payment BETWEEN ? AND ? AND m.id = ?
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
            WHERE e.date_payment BETWEEN ? AND ? AND m.id = ?
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
            WHERE e.date_payment BETWEEN ? AND ? AND m.id = ?
            ORDER BY c.address,date_payment ASC;",[$startdate,$enddate,$paymode]);

        return collect($results);
    }

    public function GetGCashReceiver($startdate, $enddate, $paymode)
    {
        $results = DB::select("SELECT CONCAT(p.firstname,' ',p.lastname) AS collected_by
            FROM sales_payment_terms e
            INNER JOIN employees p ON p.user_id = e.collected_by
            WHERE e.date_payment BETWEEN ? AND ? AND e.payment_mode_id = ?
            GROUP BY  CONCAT(p.firstname,' ',p.lastname) ORDER BY  CONCAT(p.firstname,' ',p.lastname);",[$startdate,$enddate,$paymode]);

        return collect($results);
    }

  public function paymentCheQuehMode($startdate, $enddate, $paymode)
    {
        $results = DB::select("SELECT DATE_FORMAT(e.date_payment,'%m-%d-%Y') as date_payment, c.address, o.so_number, c.name AS cs_name , e.bank_name, e.bank_account_no, e.bank_account_name,
            e.amount_collected,e.status,DATE_FORMAT(e.post_dated,'%m-%d-%Y')  as post_dated
            FROM sales_payment_terms e
            INNER JOIN sales_payment s ON s.id = e.sales_payment_id
            INNER JOIN sales_order o ON o.id = s.sales_order_id
            INNER JOIN customers c ON c.id = o.customer_id
            INNER JOIN mode_of_payments m ON m.id = e.payment_mode_id   
            INNER JOIN employees p ON p.user_id = e.collected_by
            WHERE e.date_payment BETWEEN ? AND ? AND m.id = ?
            ORDER BY e.post_dated,c.address ASC;",[$startdate,$enddate,$paymode]);

        return collect($results);
    }

  public function GetCheQueStatus($startdate, $enddate, $paymode)
    {
        $results = DB::select("SELECT e.status
            FROM sales_payment_terms e
            INNER JOIN mode_of_payments m ON m.id = e.payment_mode_id
            WHERE e.date_payment BETWEEN ? AND ? AND m.id = ?
            GROUP BY e.status ORDER BY e.status;",[$startdate,$enddate,$paymode]);

        return collect($results);
    }

    public function AllpaymenCustomer($startdate, $enddate, $customer)
    {
        $results = DB::select("SELECT DATE_FORMAT(e.date_payment,'%m-%d-%Y') as date_payment,e.trasanction_no, DATE_FORMAT(e.post_dated,'%m-%d-%Y')  as post_dated, o.so_number, c.name AS cs_name , m.name AS paymode, e.amount_collected,e.status
            FROM sales_payment_terms e
            INNER JOIN sales_payment s ON s.id = e.sales_payment_id
            INNER JOIN sales_order o ON o.id = s.sales_order_id
            INNER JOIN customers c ON c.id = o.customer_id
            INNER JOIN mode_of_payments m ON m.id = e.payment_mode_id
            WHERE e.date_payment BETWEEN ? AND ? AND c.id = ? 
            ORDER BY e.status  ASC;",[$startdate,$enddate,$customer]);

        return collect($results);
    }

    public function GetCustomerPaymodeStatus($startdate, $enddate, $customer)
    {
        $results = DB::select("SELECT e.status
            FROM sales_payment_terms e
            INNER JOIN sales_payment s ON s.id = e.sales_payment_id
            INNER JOIN sales_order o ON o.id = s.sales_order_id
            INNER JOIN customers c ON c.id = o.customer_id
            INNER JOIN mode_of_payments m ON m.id = e.payment_mode_id 
             INNER JOIN employees p ON p.user_id = e.collected_by
            WHERE e.date_payment BETWEEN ? AND ? AND c.id = ? 
            GROUP BY e.status ORDER BY e.status;",[$startdate,$enddate,$customer]);

        return collect($results);
    }

    public function CustomerPaymode($startdate, $enddate, $customer,$mode)
    {
        $results = DB::select("SELECT DATE_FORMAT(e.date_payment,'%m-%d-%Y') as date_payment,e.trasanction_no, 
            DATE_FORMAT(e.post_dated,'%m-%d-%Y')  as post_dated, o.so_number, c.name AS cs_name , m.name AS paymode, e.amount_collected,e.status,
            e.bank_name, e.bank_account_no, e.bank_account_name,
            CONCAT(p.firstname,' ',p.lastname) AS collected_by
            FROM sales_payment_terms e
            INNER JOIN sales_payment s ON s.id = e.sales_payment_id
            INNER JOIN sales_order o ON o.id = s.sales_order_id
            INNER JOIN customers c ON c.id = o.customer_id
            INNER JOIN mode_of_payments m ON m.id = e.payment_mode_id
             INNER JOIN employees p ON p.user_id = e.collected_by
            WHERE e.date_payment BETWEEN ? AND ? AND c.id = ? AND e.payment_mode_id = ?
            ORDER BY e.status  ASC;",[$startdate,$enddate,$customer,$mode]);

        return collect($results);
    }


    public function GetCustomerGCashReceiver($startdate, $enddate, $customer,$mode)
    {
        $results = DB::select("
            SELECT CONCAT(p.firstname,' ',p.lastname) AS collected_by
            FROM sales_payment_terms e
            INNER JOIN sales_payment s ON s.id = e.sales_payment_id
            INNER JOIN sales_order o ON o.id = s.sales_order_id
            INNER JOIN customers c ON c.id = o.customer_id
            INNER JOIN mode_of_payments m ON m.id = e.payment_mode_id
            INNER JOIN employees p ON p.user_id = e.collected_by
            WHERE e.date_payment BETWEEN ? AND ? AND c.id = ? AND e.payment_mode_id = ?
            GROUP BY  CONCAT(p.firstname,' ',p.lastname) ORDER BY  CONCAT(p.firstname,' ',p.lastname);",[$startdate,$enddate,$customer,$mode]);

        return collect($results);
    }


    public function GetCustomerCheQueStatus($startdate, $enddate, $customer,$mode)
    {
        $results = DB::select("SELECT e.status
            FROM sales_payment_terms e
            INNER JOIN sales_payment s ON s.id = e.sales_payment_id
            INNER JOIN sales_order o ON o.id = s.sales_order_id
            INNER JOIN customers c ON c.id = o.customer_id
            INNER JOIN mode_of_payments m ON m.id = e.payment_mode_id
             INNER JOIN employees p ON p.user_id = e.collected_by
            WHERE e.date_payment BETWEEN ? AND ? AND c.id = ? AND e.payment_mode_id = ?
            GROUP BY e.status ORDER BY e.status;",[$startdate, $enddate, $customer,$mode]);

        return collect($results);
    }

    public function CollectCustomerSales($startdate,$enddate)
    {
        $results = DB::select("SELECT  DATE_FORMAT(s.so_date,'%m-%d-%Y') as so_date,s.so_number, c.name AS cs_name, a.name AS area_name,s.total_sales, 
            sum(e.amount_collected) AS amount_collected,
        ifnull( s.total_sales - sum(e.amount_collected),s.total_sales) AS balance
        FROM sales_order s
        INNER JOIN customers c ON c.id = s.customer_id
        INNER JOIN areas a ON a.id = c.area_id 
        INNER JOIN sales_payment p ON s.id = p.sales_order_id
        LEFT JOIN sales_payment_terms e ON e.sales_payment_id = p.id AND e.`status`='Complete'
        WHERE s.so_date BETWEEN ? AND ?
        GROUP BY s.so_date, s.so_number, c.name , a.name , s.total_sales
        ORDER BY a.name;",[$startdate, $enddate]);

        return collect($results);
    }

    public function CollectSalesItems($startdate,$enddate)
    {
        $results = DB::select("SELECT  i.code , i.name AS item_name, i.description , u.code AS unit, 
            sum(o.order_quantity) AS order_qty, SUM(o.sub_amount) AS sub_amount
            FROM sales_order s
            INNER JOIN sales_order_items o ON s.id = o.sales_order_id
            INNER JOIN items i ON i.id = o.item_id
            INNER JOIN unit_of_measure u ON u.id = i.unit_id 
            WHERE s.so_date BETWEEN ? AND ? AND o.order_quantity > 0
            GROUP BY i.code , i.name, i.description , u.code
            ORDER BY i.name;",[$startdate, $enddate]);

        return collect($results);
    }

    public function CollectCustomerBalance($areas)
    {
        $results = DB::select("SELECT a.so_number  , c.name AS customer, 
            e.name AS areas,(a.sales_total - sum(s.amount_collected)) AS balances, o.so_date
            FROM sales_payment_terms s
            INNER JOIN sales_payment a ON s.sales_payment_id = a.id
            INNER JOIN sales_order o ON o.id = a.sales_order_id
            INNER JOIN customers c ON o.customer_id = c.id
            INNER JOIN areas e ON e.id = c.area_id
            WHERE a.payment_status = 'Existing Balance' AND e.id = ?
            GROUP BY a.so_number, o.so_date , c.name, a.sales_total,e.name;",[$areas]);

        return collect($results);
    }

}
