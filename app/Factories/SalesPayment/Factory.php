<?php

namespace App\Factories\SalesPayment;

use App\Factories\SalesPayment\SetInterface;
use DB;

class Factory implements SetInterface
{

    public function getindex()
    {
        $results = DB::select("
        SELECT p.id,
                 p.so_number,
                 c.name AS customer,
                 p.payment_type,
                 p.sales_total,
                 p.payment_status,
                 p.created_at
        FROM sales_payment p
        INNER JOIN sales_order o ON p.sales_order_id = o.id
        INNER JOIN customers c ON c.id = o.customer_id
        ORDER BY p.id DESC");

        return collect($results);
    } 

    public function showpayments($salespayment_id)
    {
        $results = DB::select("
            SELECT s.id,
                     s.date_payment,
                     s.trasanction_no,
                     m.name AS modes,
                     s.amount_collected,
                     CONCAT(e.firstname,' ',e.lastname) AS collected_by,
                     s.bank_name,
                     s.bank_account_no,
                     s.bank_account_name
            FROM sales_payment_terms s
            INNER JOIN mode_of_payments m ON s.payment_mode_id = m.id
            INNER JOIN employees e ON e.user_id = s.collected_by
            WHERE s.sales_payment_id = ?
            ORDER BY s.id DESC;",[$salespayment_id]);

        return collect($results);

    }

    public function totalpaid($salespayment_id)
    {
        $results = DB::select("
            SELECT SUM(amount_collected) AS amount 
            FROM sales_payment_terms 
            WHERE sales_payment_id = ?;",[$salespayment_id]);

        return collect($results);
    }

}
