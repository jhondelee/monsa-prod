<?php

namespace App;
use App\SalesOrder;
use Carbon\Carbon;
use Fpdf;

class MyPdf extends Fpdf
{
    public function Header($id)
    {       
         $salesorders = SalesOrder::find($id); 
        
            // Set font for header
            MyPdf::SetFont('Arial', 'B', 14);

            // Move to the right
             MyPdf::Cell(80);

            // Title

             MyPdf::Cell(40, 10, ' ', 0, "", 'C');
      
            MyPdf::SetY(10);
            MyPdf::Cell(70); 
            MyPdf::Cell(50, 10, 'Delivery Receipt', 1, "", 'C');

            //logo
            MyPdf::Image('img/temporary-logo.jpg',3, 3, 25);
            MyPdf::SetFont('Arial','B',13);
            MyPdf::SetY(20); 

       


            MyPdf::Ln(4);
            MyPdf::SetFont('Arial','B',11);
            MyPdf::SetXY(MyPdf::getX(), MyPdf::getY());
            MyPdf::cell(30,6,"DR Number",0,"","L");
            MyPdf::SetFont('Arial','',11);
            MyPdf::cell(30,6,': '.$salesorders->so_number,0,"","L");
            MyPdf::SetFont('Arial','B',11);
            MyPdf::cell(100,6,"DR Date",0,"","R");
            MyPdf::SetFont('Arial','',11);
            $so_date = Carbon::parse($salesorders->so_date);
            MyPdf::cell(30,6,': '.$so_date->format('M d, Y'),0,"","L");

            MyPdf::Ln(4);
            MyPdf::SetFont('Arial','B',11);
            MyPdf::SetXY(MyPdf::getX(), MyPdf::getY());
            MyPdf::cell(30,6,"Customer",0,"","L");
            MyPdf::SetFont('Arial','',11);
            $customer = Customer::find($salesorders->customer_id);
            MyPdf::cell(40,6,': '.$customer->name,0,"","L");

            MyPdf::Ln(4);
            MyPdf::SetFont('Arial','B',11);
            MyPdf::SetXY(MyPdf::getX(), MyPdf::getY());
            MyPdf::cell(30,6,"Address/Area ",0,"","L");
            MyPdf::SetFont('Arial','',11);
            MyPdf::cell(30,6,': '.$customer->address,0,"","L");


            MyPdf::SetFont('Arial','B',11);
            MyPdf::cell(100,6,"Terms",0,"","R");
            MyPdf::SetFont('Arial','',11);
            $so_date = Carbon::parse($salesorders->so_date);
            MyPdf::cell(30,6,': '.'___________',0,"","L");

            MyPdf::Ln(4);
            MyPdf::SetFont('Arial','B',11);
            MyPdf::SetXY(MyPdf::getX(), MyPdf::getY());
            MyPdf::cell(30,6,"Contact#",0,"","L");
            MyPdf::SetFont('Arial','',11);
            MyPdf::cell(40,6,': '.$customer->contact_number1,0,"","L");

             //Column Name
           MyPdf::Ln(10);
           MyPdf::SetFont('Arial','B',11);
            if(($salesorders->total_amount_discount == 0) && ($salesorders->total_percent_discount == 0)){
                MyPdf::cell(25,6,"Qty",0,"","C");
                MyPdf::cell(15,6,"Unit",0,"","L");
                MyPdf::cell(85,6,"Item Name",0,"","L");
                MyPdf::cell(30,6,"SRP",0,"","R");
                MyPdf::cell(30,6,"Amount",0,"","R");
            }elseif(($salesorders->total_amount_discount > 0) && ($salesorders->total_percent_discount == 0)){
                
                MyPdf::cell(15,6,"Qty",0,"","C");
                MyPdf::cell(15,6,"Unit",0,"","L");
                MyPdf::cell(70,6,"Item Name",0,"","L");
                MyPdf::cell(20,6,"SRP",0,"","R");
                MyPdf::cell(20,6,"Disc.",0,"","C");
                MyPdf::cell(20,6,"Price",0,"","R");
                MyPdf::cell(25,6,"Amount",0,"","R");

            }elseif (($salesorders->total_amount_discount == 0) && ($salesorders->total_percent_discount > 0)){

                MyPdf::cell(15,6,"Qty",0,"","C");
                MyPdf::cell(15,6,"Unit",0,"","L");
                MyPdf::cell(70,6,"Item Name",0,"","L");
                MyPdf::cell(20,6,"SRP",0,"","R");
                MyPdf::cell(20,6,"% Disc.",0,"","C");
                MyPdf::cell(20,6,"Price",0,"","R");
                MyPdf::cell(25,6,"Amount",0,"","R");

            }elseif (($salesorders->total_amount_discount > 0) && ($salesorders->total_percent_discount > 0)){
                MyPdf::cell(15,6,"Qty",0,"","C");
                MyPdf::cell(15,6,"Unit",0,"","L");
                MyPdf::cell(60,6,"Item Name",0,"","L");                
                MyPdf::cell(20,6,"SRP",0,"","R");
                MyPdf::cell(15,6,"P Disc.",0,"","C");
                MyPdf::cell(15,6,"% Disc.",0,"","C");
                MyPdf::cell(20,6,"Price",0,"","R");
                MyPdf::cell(25,6,"Amount",0,"","R");
            }


        MyPdf::Ln(1);
        MyPdf::SetFont('Arial','',9);
        MyPdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");
 
    }

    public function Footer()
    {
        

        MyPdf::SetY(-30);
        MyPdf::SetFont('Arial','I',10);
        MyPdf::cell(110,6,"",0,"","L");
        MyPdf::cell(40,0,"NOTE: Received the above MDSE. in good order",0,"","L");
        MyPdf::Ln(1);
        MyPdf::SetFont('Arial','',10);
        MyPdf::cell(90,0,"NOTE: *Price subject to change without prior notice",0,"","L");
        MyPdf::Ln(5);
        MyPdf::cell(275,0,"By : ______________________",0,"","C");
        

    }
}