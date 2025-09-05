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
            MyPdf::Cell(80); 
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
           MyPdf::SetFont('Arial','B',9);
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
        // Line break
          /*
        $salesorders = SalesOrder::find($id); 

        // Logo
        MyPdf::SetFont('Arial','',7);
        MyPdf::cell(170,0,date("Y-m-d") ,0,"","R");
        date_default_timezone_set("singapore");
        MyPdf::cell(0,0,date("h:i A"),0,"","L");

        MyPdf::Image('img/temporary-logo.jpg',3, 3, 25);
        MyPdf::SetFont('Arial','B',12);
        MyPdf::SetY(20);     

        // Header
        MyPdf::SetFont('Arial','B',12);
        MyPdf::SetY(20);  

        MyPdf::Ln(2);
        MyPdf::SetFont('Arial','B',12);
        MyPdf::SetXY(MyPdf::getX(),MyPdf::getY());
        MyPdf::cell(185,1,"Delivery Receipt",0,"","C");

        MyPdf::Ln(5);
        MyPdf::SetFont('Arial','B',9);
        MyPdf::SetXY(MyPdf::getX(), MyPdf::getY());
        MyPdf::cell(25,6,"DR Number",0,"","L");
        MyPdf::SetFont('Arial','',9);
        MyPdf::cell(40,6,': '.$salesorders->so_number,0,"","L");
        MyPdf::SetFont('Arial','B',9);
        MyPdf::cell(100,6,"DR Date",0,"","R");
        MyPdf::SetFont('Arial','',9);
        $so_date = Carbon::parse($salesorders->so_date);
        MyPdf::cell(30,6,': '.$so_date->format('M d, Y'),0,"","L");

        

        MyPdf::Ln(4);
        MyPdf::SetFont('Arial','B',9);
        MyPdf::SetXY(MyPdf::getX(), MyPdf::getY());
        MyPdf::cell(25,6,"Customer",0,"","L");
        MyPdf::SetFont('Arial','',9);
        $customer = Customer::find($salesorders->customer_id);
        MyPdf::cell(40,6,': '.$customer->name,0,"","L");


        MyPdf::Ln(4);
        MyPdf::SetFont('Arial','B',9);
        MyPdf::SetXY(MyPdf::getX(), MyPdf::getY());
        MyPdf::cell(25,6,"Address/Area ",0,"","L");
        MyPdf::SetFont('Arial','',9);
        MyPdf::cell(40,6,': '.$customer->address,0,"","L");


        MyPdf::SetFont('Arial','B',9);
        MyPdf::cell(100,6,"Terms",0,"","R");
        MyPdf::SetFont('Arial','',9);
        $so_date = Carbon::parse($salesorders->so_date);
        MyPdf::cell(30,6,': '.'___________',0,"","L");

        MyPdf::Ln(4);
        MyPdf::SetFont('Arial','B',9);
        MyPdf::SetXY(MyPdf::getX(), MyPdf::getY());
        MyPdf::cell(25,6,"Contact#",0,"","L");
        MyPdf::SetFont('Arial','',9);
        MyPdf::cell(40,6,': '.$customer->contact_number1,0,"","L");


        //Column Name
           MyPdf::Ln(10);
           MyPdf::SetFont('Arial','B',9);
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

        MyPdf::Ln(0);
        */
    }

    public function Footer()
    {
        

        MyPdf::SetY(-30);
        MyPdf::SetFont('Arial','I',8);
        MyPdf::cell(120,6,"",0,"","L");
        MyPdf::cell(60,0,"NOTE: Received the above MDSE. in good order",0,"","L");
        MyPdf::Ln(1);
        MyPdf::SetFont('Arial','',8);
        MyPdf::cell(115,0,"NOTE: *Price subject to change without prior notice",0,"","L");
        MyPdf::Ln(5);
        MyPdf::cell(285,0,"By : ______________________",0,"","C");
        

    }
}