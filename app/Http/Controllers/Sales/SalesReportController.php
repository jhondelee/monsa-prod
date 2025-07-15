<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Factories\Item\Factory as ItemFactory;
use App\Factories\SalesOrder\Factory as SalesOrderFactory;
use App\Factories\SalesPayment\Factory as SalesPaymentFactory;
use App\Factories\SalesReport\Factory as SalesReportFactory;
use Yajra\Datatables\Datatables;
use App\Item;
use App\Inventory;
use App\SalesOrder;
use App\SalesOrderItem;
use App\SalesPayment;
use App\SalesPaymentTerm;
use App\UnitOfMeasure; 
use App\Customer; 
use App\WarehouseLocation;
use App\ModeOfPayment;
use App\User as Users;
use Carbon\Carbon;

use Fpdf;
use DB;

class SalesReportController extends Controller
{
    public function __construct(
            Users $user,
            ItemFactory $items,
            SalesOrderFactory $salesorder,
            SalesPaymentFactory $salespayment,
            SalesReportFactory $salesreport
        )
    {
        $this->user = $user;
        $this->items = $items;
        $this->salesorders = $salesorder;
        $this->salespayment = $salespayment;
        $this->salesreport = $salesreport;
        $this->middleware('auth');
    }


    public function index()
    {   

        $salesorders = $this->salesorders->getindex()->where('status','NEW')->sortByDesc('id');   

        $paymode = ModeOfPayment::pluck('name','id');
        
        return view('pages.salesreport.index',compact('salesorders','paymode'));
    }

    public function print(Request $request)
    {       


        $paymode = $request->pay_mode;

        if (!$paymode){
            $paymode = 0;
        }

       return redirect()->route('salesreport.generate',[$request->start_date,$request->end_date,$paymode]);
    }

    public function generate($start,$end,$mode)
    {       


        $pdf = new Fpdf('P');
        $pdf::AddPage('P','A4');

        $pdf::SetFont('Arial','',7);
        $pdf::cell(170,0,date("Y-m-d") ,0,"","R");
        date_default_timezone_set("singapore");
        $pdf::cell(0,0,date("h:i A"),0,"","L");

        $pdf::Image('img/temporary-logo.jpg',2, 2, 30.00);
        $pdf::SetFont('Arial','B',12);
        $pdf::SetY(20);     

        // Header
        $pdf::SetFont('Arial','B',12);
        $pdf::SetY(20);  

        $pdf::Ln(2);
        $pdf::SetFont('Arial','B',12);
        $pdf::SetXY($pdf::getX(), $pdf::getY());
        $pdf::cell(185,1,"Sales Payment Report",0,"","C");

        $pdf::Ln(6);    
        $pdf::SetFont('Arial','B',9);
        $pdf::SetXY($pdf::getX(), $pdf::getY());
        $pdf::cell(15,6,"Start Date",0,"","L");
        $pdf::SetFont('Arial','',9);
        $pdf::cell(40,6,': '.$start,0,"","L");
        $pdf::Ln(4); 
        $pdf::SetFont('Arial','B',9);
        $pdf::SetXY($pdf::getX(), $pdf::getY());
        $pdf::cell(15,6,"End Date",0,"","L");
        $pdf::SetFont('Arial','',9);
        $pdf::cell(40,6,': '.$end,0,"","L");
        $title = 'All Paymode';
      

        $ModeName = Str::lower($title);

        if ($mode > 0){
            $payname = ModeOfPayment::findOrfail($mode);
            $ModeName = Str::lower($payname->name);
            $title = $payname->name;
        }

        $pdf::SetFont('Arial','BU',12);
        $pdf::SetXY($pdf::getX(), $pdf::getY());
        $pdf::cell(70,1,"$title",0,"","C");

        if ($ModeName == 'cash'){
                
            $payments =  $this->salesreport->paymentCashMode($start,$end,$mode);
        }   

        if ($ModeName == 'gcash'){
                
            $payments =  $this->salesreport->paymentGCashMode($start,$end,$mode);
        } 

        if ($ModeName == 'cheque'){
                
            $payments =  $this->salesreport->paymentGCashMode($start,$end,$mode);
        } 

        if ($mode == 0){
        
           $payments =  $this->salesreport->paymentAll($start,$end);

        } 
        
        // All Payments Column Header                    
        if ($mode == 0){

            $pdf::Ln(6);
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(20,6,"Payment Date",0,"","C");
            $pdf::cell(30,6,"Address",0,"","C");
            $pdf::cell(25,6,"DR No.",0,"","C");
            $pdf::cell(30,6,"Customer",0,"","C");
            $pdf::cell(30,6,"Payment Mode",0,"","C");
            $pdf::cell(25,6,"Amount",0,"","R");
            $pdf::cell(25,6,"Status",0,"","R");
        }

        // Cash Payments Column Header 
        if ($ModeName == 'cash'){

            $pdf::Ln(6);
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(35,6,"Payment Date",0,"","L");
            $pdf::cell(50,6,"Address",0,"","L");
            $pdf::cell(35,6,"DR No.",0,"","L");
            $pdf::cell(35,6,"Customer",0,"","L");
            $pdf::cell(30,6,"Amount",0,"","R");
        }

        // GCash Payments Column Header 
        if ($ModeName == 'gcash'){

            $pdf::Ln(6);
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(35,6,"Payment Date",0,"","L");
            $pdf::cell(40,6,"Address",0,"","L");
            $pdf::cell(20,6,"DR No.",0,"","L");
            $pdf::cell(35,6,"Customer",0,"","L");
            $pdf::cell(20,6,"Transac. No.",0,"","L");
            $pdf::cell(33,6,"Amount",0,"","R");
        }

         $pdf::Ln(1);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");

        
        $tatolAmount = 0;

        foreach ($payments as $key => $payment) {

            if ($mode == 0){

                $pdf::Ln(5);
                $pdf::SetFont('Arial','',9);
                $pdf::cell(20,6,$payment->date_payment,0,"","L");
                $pdf::cell(35,6,$payment->address,0,"","L");
                $pdf::cell(25,6,$payment->so_number,0,"","L");
                $pdf::cell(35,6,$payment->cs_name,0,"","L");
                $pdf::cell(30,6,$payment->paymode,0,"","L");
                $pdf::cell(15,6,number_format($payment->amount_collected,2),0,"","R");
                $pdf::cell(25,6,$payment->payment_status,0,"","R");

                $tatolAmount = $tatolAmount + $payment->amount_collected;

            }

            if ($ModeName == 'cash'){

                $pdf::Ln(5);
                $pdf::SetFont('Arial','',9);
                $pdf::cell(35,6,$payment->date_payment,0,"","L");
                $pdf::cell(50,6,$payment->address,0,"","L");
                $pdf::cell(35,6,$payment->so_number,0,"","L");
                $pdf::cell(35,6,$payment->cs_name,0,"","L");
                $pdf::cell(30,6,number_format($payment->amount_collected,2),0,"","R");

                $tatolAmount = $tatolAmount + $payment->amount_collected;

            }


        }

        if ($ModeName == 'gcash'){

            $subtotal = 0;

            $receivers =  $this->salesreport->GCashReceiver($start,$end,$mode); 

            foreach ($receivers  as $key => $receiver) {

                $pdf::Ln(5);
                $pdf::SetFont('Arial','B',9);
                $pdf::cell(30,6,$receiver->collected_by,0,"","L");

                foreach ($payments as $key => $payment) {

                    if ($receiver->collected_by == $payment->collected_by) {

                        $pdf::Ln(5);
                        $pdf::SetFont('Arial','',9);
                        $pdf::cell(35,6,$payment->date_payment,0,"","C");
                        $pdf::cell(40,6,$payment->address,0,"","L");
                        $pdf::cell(20,6,$payment->so_number,0,"","L");
                        $pdf::cell(40,6,$payment->cs_name,0,"","L");
                        $pdf::cell(20,6,$payment->trasanction_no,0,"","L");
                        $pdf::cell(30,6,number_format($payment->amount_collected,2),0,"","R");

                        $subtotal = $subtotal + $payment->amount_collected;

                    }                
                }
                $pdf::Ln(1);
                $pdf::SetFont('Arial','',9);
                $pdf::cell(185,6,"________",0,"","R");

                $pdf::Ln(4);
                $pdf::SetFont('Arial','B',9);
                $pdf::cell(165,6,"Subtotal:",0,"","R");
                $pdf::cell(20,6,number_format($subtotal,2),0,"","R");

                $tatolAmount = $tatolAmount + $subtotal;
                $subtotal = 0;
            }
                
        }

        $pdf::Ln(5);
            $pdf::SetFont('Arial','I',8);
            $pdf::cell(185,6,"--Nothing Follows--",0,"","C");

        $pdf::Ln(3);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");

        $preparedby = $this->user->getCreatedbyAttribute(auth()->user()->id);

        // All Payment Grand Total
        if ($mode == 0){

            $pdf::Ln(5);
            $pdf::SetFont('Arial','',9);
            $pdf::cell(100,6,"Prepared by:",0,"","L");
            $pdf::SetFont('Arial','B',10);
            $pdf::cell(30,6,"Total:",0,"","R");
            $pdf::SetFont('Arial','B',10);
            $pdf::cell(30,6,number_format( $tatolAmount,2),0,"","R");

        }
        // Cash Grand Total
        if ($ModeName == 'cash'){

            $pdf::Ln(5);
            $pdf::SetFont('Arial','',9);
            $pdf::cell(125,6,"Prepared by:",0,"","L");
            $pdf::SetFont('Arial','B',10);
            $pdf::cell(30,6,"Total:",0,"","R");
            $pdf::SetFont('Arial','B',10);
            $pdf::cell(30,6,number_format( $tatolAmount,2),0,"","R");

        }

        // GCash Grand Total
        if ($ModeName == 'gcash'){

            $pdf::Ln(5);
            $pdf::SetFont('Arial','',9);
            $pdf::cell(125,6,"Prepared by:",0,"","L");
            $pdf::SetFont('Arial','B',10);
            $pdf::cell(30  ,6,"Total:",0,"","R");
            $pdf::SetFont('Arial','B',10);
            $pdf::cell(30,6,number_format( $tatolAmount,2),0,"","R");

        }

    
            $pdf::Ln(7);
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(60,6,"      ".$preparedby."      ",0,"","L");
            $pdf::ln(0);
            $pdf::SetFont('Arial','',9);
            $pdf::cell(60,6,"______________________",0,"","L");


        $pdf::Ln();
        $pdf::Output();
        exit;

  
    }


    
}
