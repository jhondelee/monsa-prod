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
use App\Area; 
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

        $customers = Customer::pluck('name','id');

        $areas = Area::pluck('name','id');
        
        return view('pages.salesreport.index',compact('salesorders','paymode','customers','areas'));

    }

    public function print(Request $request)
    {       

        $this->validate($request, [
            'start_date' => 'required',
            'end_date'   => 'required',
        ]);

        $paymode = $request->pay_mode;

        if (!$paymode){
            $paymode = 0;
        }

        if (!$request->customer_id){

            return redirect()->route('salesreport.generate',[
                    $request->start_date,
                    $request->end_date,
                    $paymode
                ]);

        } else {

            return redirect()->route('salesreport.cs_generate',[
                $request->start_date,
                $request->end_date,
                $request->customer_id,
                $paymode
            ]);
        } 
    }


    public function generate($start,$end,$mode)
    {       


        $pdf = new Fpdf('P');
        $pdf::AddPage('P','A4');

        $pdf::SetFont('Arial','',7);
        $pdf::cell(170,0,date("m-d-Y") ,0,"","R");
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
        $pdf::cell(15,6,"Start Date",0,"","L");
        $pdf::SetFont('Arial','',9);
        $startdate = Carbon::parse($start);
        $pdf::cell(40,6,': '.$startdate->format('m-d-Y') ,0,"","L");
        $pdf::Ln(4); 
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(15,6,"End Date",0,"","L");
        $pdf::SetFont('Arial','',9);
        $enddate = Carbon::parse($end);
        $pdf::cell(40,6,': '.$enddate->format('m-d-Y'),0,"","L");
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

        if ($ModeName == 'cheque' or $ModeName == 'bank deposit' or $ModeName == 'bank transfer'){
                
            $payments =  $this->salesreport->paymentCheQuehMode($start,$end,$mode);
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

        // CheQue Payments Column Header 
        if ($ModeName == 'cheque'){

            $pdf::Ln(6);
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(30,6,"Payment Date",0,"","C");
            $pdf::cell(35,6,"Address",0,"","L");
            $pdf::cell(20,6,"DR No.",0,"","L");
            $pdf::cell(35,6,"Customer",0,"","L");
            $pdf::cell(35,6,"Post Dated",0,"","L");
            $pdf::cell(20,6,"Amount",0,"","R");
        }

        // Bank Deposit Payments Column Header 
        if ($ModeName == 'bank deposit' or $ModeName == 'bank transfer'){

            $pdf::Ln(6);
            $pdf::SetFont('Arial','B',8);
            $pdf::cell(20,6,"Payment Date",0,"","L");
            $pdf::cell(25,6,"Address",0,"","L");
            $pdf::cell(20,6,"DR No.",0,"","L");
            $pdf::cell(30,6,"Customer",0,"","L");
            $pdf::cell(25,6,"Bank Name",0,"","L");
            $pdf::cell(25,6,"BA No.",0,"","L");
            $pdf::cell(25,6,"BA Name",0,"","L");
            $pdf::cell(20,6,"Amount",0,"","R");
        }

         $pdf::Ln(1);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"___________________________________________________________________________________________________________",0,"","L");

        
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

            if ($ModeName == 'bank deposit' or $ModeName == 'bank transfer'){

                $pdf::Ln(5);
                $pdf::SetFont('Arial','',8);
                $pdf::cell(20,6,$payment->date_payment,0,"","L");
                $pdf::cell(25,6,$payment->address,0,"","L");
                $pdf::cell(20,6,$payment->so_number,0,"","L");
                $pdf::cell(30,6,$payment->cs_name,0,"","L");
                $pdf::cell(25,6,$payment->bank_name,0,"","L");
                $pdf::cell(25,6,$payment->bank_account_no,0,"","L");
                $pdf::cell(25,6,$payment->bank_account_name,0,"","L");
                $pdf::cell(20,6,number_format($payment->amount_collected,2),0,"","R");

                $tatolAmount = $tatolAmount + $payment->amount_collected;

            }

        }

        if ($ModeName == 'gcash'){

            $subtotal = 0;

            $receivers =  $this->salesreport->GetGCashReceiver($start,$end,$mode); 

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

        if ($ModeName == 'cheque'){

            $subtotal = 0;

            $statuses =  $this->salesreport->GetCheQueStatus($start,$end,$mode); 

            foreach ($statuses  as $key => $status) {

                $pdf::Ln(5);
                $pdf::SetFont('Arial','B',9);
                $pdf::cell(30,6,$status->status,0,"","L");

                foreach ($payments as $key => $payment) {

                    if ($status->status == $payment->status) {

                        $pdf::Ln(5);
                        $pdf::SetFont('Arial','',9);
                        $pdf::cell(30,6,$payment->date_payment,0,"","C");
                        $pdf::cell(35,6,$payment->address,0,"","L");
                        $pdf::cell(20,6,$payment->so_number,0,"","L");
                        $pdf::cell(40,6,$payment->cs_name,0,"","L");
                        $pdf::cell(40,6,$payment->post_dated,0,"","L");
                        $pdf::cell(20,6,number_format($payment->amount_collected,2),0,"","R");

                        $subtotal = $subtotal + $payment->amount_collected;

                    }                
                }
                $pdf::Ln(1);
                $pdf::SetFont('Arial','',9);
                $pdf::cell(185,6,"________",0,"","R");

                $pdf::Ln(4);
                $pdf::SetFont('Arial','B',9);
                $pdf::cell(165,6,"Total:",0,"","R");
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
        $pdf::cell(30,6,"___________________________________________________________________________________________________________",0,"","L");

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

        // Cheque Grand Total
        if ($ModeName == 'cheque'){

            $pdf::Ln(5);
            $pdf::SetFont('Arial','',9);
            $pdf::cell(125,6,"Prepared by:",0,"","L");
           // $pdf::SetFont('Arial','B',9);
           // $pdf::cell(30  ,6,"Total:",0,"","R");
            //$pdf::SetFont('Arial','B',9);
           // $pdf::cell(30,6,number_format( $tatolAmount,2),0,"","R");

        }

        // Bank Deposit & Transfer Grand Total
        if ( $ModeName == 'bank deposit' or $ModeName == 'bank transfer'){

            $pdf::Ln(5);
            $pdf::SetFont('Arial','',8);
            $pdf::cell(130,6,"Prepared by:",0,"","L");
            $pdf::SetFont('Arial','B',8);
            $pdf::cell(30  ,6,"Total:",0,"","R");
            $pdf::SetFont('Arial','B',8);
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


    public function cs_generate($start,$end,$customer,$mode)
    {

        $pdf = new Fpdf('P');
        $pdf::AddPage('P','A4');

        $pdf::SetFont('Arial','',7);
        $pdf::cell(170,0,date("m-d-Y") ,0,"","R");
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
        $pdf::cell(185,1,"Customer Payment Report",0,"","C");

        $pdf::Ln(6);    
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(20,6,"Start Date",0,"","L");
        $pdf::SetFont('Arial','',9);
        $startdate = Carbon::parse($start);
        $pdf::cell(40,6,': '.$startdate->format('m-d-Y') ,0,"","L");
        $pdf::Ln(4); 
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(20,6,"End Date",0,"","L");
        $pdf::SetFont('Arial','',9);
        $enddate = Carbon::parse($end);
        $pdf::cell(40,6,': '.$enddate->format('m-d-Y'),0,"","L");


        $title = 'All Payments';


        $ModeName = Str::lower($title);

        if ($mode > 0){
            $payname = ModeOfPayment::findOrfail($mode);
            $ModeName = Str::lower($payname->name);
            $title = $payname->name;
        }


        $pdf::SetFont('Arial','BU',12);
        $pdf::cell(70,1,"$title",0,"","C");

        $pdf::Ln(4);
        $pdf::SetFont('Arial','B',9);
        $pdf::SetXY($pdf::getX(), $pdf::getY());
        $pdf::cell(20,6,"Customer",0,"","L");
        $pdf::SetFont('Arial','B',9);
        $csName = Customer::findOrfail($customer);
        $pdf::cell(40,6,': '.$csName->name,0,"","L");
        $pdf::Ln(4);
        $pdf::SetFont('Arial','B',9);
        $pdf::SetXY($pdf::getX(), $pdf::getY());
        $pdf::cell(20,6,"Address",0,"","L");
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(40,6,': '.$csName->address,0,"","L");
      



        if ($ModeName == 'cash' ){
                
            $payments =  $this->salesreport->CustomerPaymode($start,$end,$customer,$mode);
        }   

        if ($ModeName == 'gcash'){
                
            $payments =  $this->salesreport->CustomerPaymode($start,$end,$customer,$mode);
        } 

        if ($ModeName == 'cheque' or $ModeName == 'bank deposit' or $ModeName == 'bank transfer'){
                
            $payments =  $this->salesreport->CustomerPaymode($start,$end,$customer,$mode);
        } 

        if ($mode == 0){
        
           $payments =  $this->salesreport->AllpaymenCustomer($start,$end,$customer);

        } 
        
        // All Payments Column Header                    
        if ($mode == 0){

            $pdf::Ln(8);
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(35,6,"Payment Date",0,"","");   
            $pdf::cell(30,6,"DR No.",0,"","L");
            $pdf::cell(30,6,"Transac. No.",0,"","L");
            $pdf::cell(30,6,"Payment Mode",0,"","L");
            $pdf::cell(30,6,"Post Dated",0,"","L");
            $pdf::cell(25,6,"Amount",0,"","R");
            $pdf::cell(35,6,"Status",0,"","R");
        }

        // Cash Payments Column Header 
        if ($ModeName == 'cash' or $ModeName == 'gcash'){

            $pdf::Ln(6);
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(35,6,"Payment Date",0,"","L");
            $pdf::cell(30,6,"DR No.",0,"","L");
            $pdf::cell(30,6,"Transac. No.",0,"","L");
            $pdf::cell(30,6,"Payment Mode",0,"","L");
            $pdf::cell(30,6,"Status",0,"","L");
            $pdf::cell(30,6,"Amount",0,"","R");
        }


        // CheQue Payments Column Header 
        if ($ModeName == 'cheque'){

            $pdf::Ln(6);
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(35,6,"Payment Date",0,"","C");;
            $pdf::cell(35,6,"DR No.",0,"","L");
            $pdf::cell(30,6,"Transac. No.",0,"","L");
            $pdf::cell(30,6,"Post Dated",0,"","L");
            $pdf::cell(30,6,"Paymode",0,"","L");
            $pdf::cell(30,6,"Amount",0,"","R");
        }

        // Bank Deposit Payments Column Header 
        if ($ModeName == 'bank deposit' or $ModeName == 'bank transfer'){

            $pdf::Ln(6);
            $pdf::SetFont('Arial','B',8);
            $pdf::cell(35,6,"Payment Date",0,"","L");
            $pdf::cell(35,6,"DR No.",0,"","L");
            $pdf::cell(30,6,"Bank Name",0,"","L");
            $pdf::cell(30,6,"BA No.",0,"","L");
            $pdf::cell(30,6,"BA Name",0,"","L");
            $pdf::cell(30,6,"Amount",0,"","R");
        }

         $pdf::Ln(1);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"___________________________________________________________________________________________________________",0,"","L");

        
        $tatolAmount = 0;

        foreach ($payments as $key => $payment) {


            if ($ModeName == 'cash'){

                $pdf::Ln(5);
                $pdf::SetFont('Arial','',9);
                $pdf::cell(35,6,$payment->date_payment,0,"","L");
                $pdf::cell(30,6,$payment->so_number,0,"","L");
                $pdf::cell(30,6,$payment->trasanction_no,0,"","L");
                $pdf::cell(30,6,$payment->paymode,0,"","L");
                $pdf::cell(30,6,$payment->status,0,"","L");
                $pdf::cell(30,6,number_format($payment->amount_collected,2),0,"","R");

                $tatolAmount = $tatolAmount + $payment->amount_collected;

            }

            if ($ModeName == 'bank deposit' or $ModeName == 'bank transfer'){

                $pdf::Ln(5);
                $pdf::SetFont('Arial','',8);
                $pdf::cell(35,6,$payment->date_payment,0,"","L");
                $pdf::cell(35,6,$payment->so_number,0,"","L");
                $pdf::cell(30,6,$payment->bank_name,0,"","L");
                $pdf::cell(30,6,$payment->bank_account_no,0,"","L");
                $pdf::cell(30,6,$payment->bank_account_name,0,"","L");
                $pdf::cell(30,6,number_format($payment->amount_collected,2),0,"","R");

                $tatolAmount = $tatolAmount + $payment->amount_collected;

            }

        }

        if ($ModeName == 'gcash'){

            $subtotal = 0;

            $receivers =  $this->salesreport->GetCustomerGCashReceiver($start,$end,$customer,$mode); 

            foreach ($receivers  as $key => $receiver) {

                $pdf::Ln(5);
                $pdf::SetFont('Arial','B',9);
                $pdf::cell(30,6,$receiver->collected_by,0,"","L");

                foreach ($payments as $key => $payment) {

                    if ($receiver->collected_by == $payment->collected_by) {

                        $pdf::Ln(5);
                        $pdf::SetFont('Arial','',9);
                        $pdf::cell(35,6,$payment->date_payment,0,"","L");
                        $pdf::cell(30,6,$payment->so_number,0,"","L");
                        $pdf::cell(30,6,$payment->trasanction_no,0,"","L");
                        $pdf::cell(30,6,$payment->paymode,0,"","L");
                        $pdf::cell(30,6,$payment->status,0,"","L");
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

        if ($ModeName == 'cheque'){

            $subtotal = 0;

            $statuses =  $this->salesreport->GetCustomerCheQueStatus($start,$end,$customer,$mode); 

            foreach ($statuses  as $key => $status) {

                $pdf::Ln(5);
                $pdf::SetFont('Arial','B',9);
                $pdf::cell(30,6,$status->status,0,"","L");

                foreach ($payments as $key => $payment) {

                    if ($status->status == $payment->status) {

                        $pdf::Ln(5);
                        $pdf::SetFont('Arial','',9);
                        $pdf::cell(35,6,$payment->date_payment,0,"","C");
                        $pdf::cell(35,6,$payment->so_number,0,"","L");
                        $pdf::cell(30,6,$payment->trasanction_no,0,"","L");
                        $pdf::cell(30,6,$payment->post_dated,0,"","L");
                        $pdf::cell(30,6,$payment->paymode,0,"","L");
                        $pdf::cell(30,6,number_format($payment->amount_collected,2),0,"","R");

                        //$subtotal = $subtotal + $payment->amount_collected;
                        $tatolAmount = $tatolAmount + $payment->amount_collected;
                    }                
                }
                /*
                $pdf::Ln(1);
                $pdf::SetFont('Arial','',9);
                $pdf::cell(185,6,"________",0,"","R");

                $pdf::Ln(4);
                $pdf::SetFont('Arial','B',9);
                $pdf::cell(165,6,"Total:",0,"","R");
                $pdf::cell(20,6,number_format($subtotal,2),0,"","R");

                $tatolAmount = $tatolAmount + $subtotal;
                $subtotal = 0;
                */
            }
                
        }

                

        if ($mode == 0){

            $subtotal = 0;

            $statuses =  $this->salesreport->GetCustomerPaymodeStatus($start,$end,$customer); 
            
            foreach ($statuses  as $key => $status) {

                $pdf::Ln(5);
                $pdf::SetFont('Arial','B',9);
                $pdf::cell(30,6,$status->status,0,"","L");

                foreach ($payments as $key => $payment) {

                    if ($status->status == $payment->status) {

                        $pdf::Ln(5);
                        $pdf::SetFont('Arial','',9);
                        $pdf::cell(35,6,$payment->date_payment,0,"","C");
                        $pdf::cell(30,6,$payment->so_number,0,"","L");
                        $pdf::cell(30,6,$payment->trasanction_no,0,"","L");
                        $pdf::cell(30,6,$payment->paymode,0,"","L");
                        $pdf::cell(25,6,$payment->post_dated,0,"","L");
                        $pdf::cell(35,6,number_format($payment->amount_collected,2),0,"","R");

                        $subtotal = $subtotal + $payment->amount_collected;

                    }                
                }
                $pdf::Ln(1);
                $pdf::SetFont('Arial','',9);
                $pdf::cell(185,6,"________",0,"","R");

                $pdf::Ln(4);
                $pdf::SetFont('Arial','B',9);
                $pdf::cell(165,6,"Total:",0,"","R");
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
        $pdf::cell(30,6,"___________________________________________________________________________________________________________",0,"","L");

        $preparedby = $this->user->getCreatedbyAttribute(auth()->user()->id);

        // All Payment Grand Total
        if ($mode == 0){

            $pdf::Ln(5);
            $pdf::SetFont('Arial','',9);
            $pdf::cell(100,6,"Prepared by:",0,"","L");
           // $pdf::SetFont('Arial','B',10);
            //$pdf::cell(30,6,"Total:",0,"","R");
           // $pdf::SetFont('Arial','B',10);
           // $pdf::cell(30,6,number_format( $tatolAmount,2),0,"","R");

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

        // Cheque Grand Total
        if ($ModeName == 'cheque'){

            $pdf::Ln(5);
            $pdf::SetFont('Arial','',9);
            $pdf::cell(130,6,"Prepared by:",0,"","L");
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(30  ,6,"Total:",0,"","R");
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(30,6,number_format( $tatolAmount,2),0,"","R");

        }

        // Bank Deposit & Transfer Grand Total
        if ( $ModeName == 'bank deposit' or $ModeName == 'bank transfer'){

            $pdf::Ln(5);
            $pdf::SetFont('Arial','',8);
            $pdf::cell(130,6,"Prepared by:",0,"","L");
            $pdf::SetFont('Arial','B',8);
            $pdf::cell(30  ,6,"Total:",0,"","R");
            $pdf::SetFont('Arial','B',8);
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

    
    public function printsales(Request $request)
    {       

        $this->validate($request, [
            'start_date' => 'required',
            'end_date'   => 'required',
        ]);


        if($request->items_generate =='no'){

            return redirect()->route('salesreport.salesgenerate',[
            $request->start_date,
            $request->end_date
            ]);

        } else {

            return redirect()->route('salesreport.itemsgenerate',[
            $request->start_date,
            $request->end_date
            ]);
        }


    }

    
    public function salesgenerate($start,$end)
    {

        $pdf = new Fpdf('P');
        $pdf::AddPage('P','A4');

        $pdf::SetFont('Arial','',7);
        $pdf::cell(170,0,date("m-d-Y") ,0,"","R");
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
        $pdf::cell(185,1,"Sales Report",0,"","C");

        $pdf::Ln(6);    
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(20,6,"Start Date",0,"","L");
        $pdf::SetFont('Arial','',9);
        $startdate = Carbon::parse($start);
        $pdf::cell(40,6,': '.$startdate->format('m-d-Y') ,0,"","L");
        $pdf::Ln(4); 
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(20,6,"End Date",0,"","L");
        $pdf::SetFont('Arial','',9);
        $enddate = Carbon::parse($end);
        $pdf::cell(40,6,': '.$enddate->format('m-d-Y'),0,"","L");


        //$title = 'Sales Report';

       // $pdf::SetFont('Arial','BU',12);
        //$pdf::cell(70,1,"$title",0,"","C");


        
        // All Sales Column Header                    

            $pdf::Ln(8);
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(25,6,"SO Date",0,"","");   
            $pdf::cell(25,6,"DR No.",0,"","L");
            $pdf::cell(35,6,"Customer",0,"","L");
            $pdf::cell(30,6,"Area",0,"","L");
            $pdf::cell(25,6,"Amount",0,"","R");
            $pdf::cell(25,6,"Balance",0,"","R");
            $pdf::cell(25,6,"Paid Amount",0,"","R");
        
         $pdf::Ln(1);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"___________________________________________________________________________________________________________",0,"","L");

        
        $totalAmount = 0;
        $totalCollect = 0;
        $totalBalance = 0;

        $sales =  $this->salesreport->CollectCustomerSales($start,$end);

        foreach ($sales as $key => $sale) {


                $pdf::Ln(5);
                $pdf::SetFont('Arial','',9);
                $pdf::cell(25,6,$sale->so_date,0,"","L");
                $pdf::cell(25,6,$sale->so_number,0,"","L");
                $pdf::cell(35,6,$sale->cs_name,0,"","L");
                $pdf::cell(30,6,$sale->area_name,0,"","L");
                $pdf::cell(25,6,number_format($sale->total_sales,2),0,"","R");
                $pdf::cell(25,6,number_format($sale->balance,2),0,"","R");
                $pdf::cell(25,6,number_format($sale->amount_collected,2),0,"","R");

                $totalAmount  = $totalAmount + $sale->total_sales;
                $totalCollect = $totalCollect + $sale->amount_collected;
                $totalBalance = $totalBalance + $sale->balance;
   

        }

      
        $pdf::Ln(5);
            $pdf::SetFont('Arial','I',8);
            $pdf::cell(185,6,"--Nothing Follows--",0,"","C");

        $pdf::Ln(3);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"___________________________________________________________________________________________________________",0,"","L");

        $preparedby = $this->user->getCreatedbyAttribute(auth()->user()->id);

            $pdf::Ln(5);
            $pdf::SetFont('Arial','',9);
            $pdf::cell(85,6,"Prepared by:",0,"","L");
            $pdf::SetFont('Arial','B',10);
            $pdf::cell(30,6,"Total:",0,"","R");
            $pdf::SetFont('Arial','B',10);
            $pdf::cell(25,6,number_format( $totalAmount,2),0,"","R");
            $pdf::SetFont('Arial','B',10);
            $pdf::cell(25,6,number_format( $totalBalance,2),0,"","R");
            $pdf::SetFont('Arial','B',10);
            $pdf::cell(25,6,number_format( $totalCollect,2),0,"","R");

        
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

    public function itemsgenerate($start,$end)
    {

        $pdf = new Fpdf('P');
        $pdf::AddPage('P','A4');

        $pdf::SetFont('Arial','',7);
        $pdf::cell(170,0,date("m-d-Y") ,0,"","R");
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
        $pdf::cell(185,1,"Item Sales Report",0,"","C");

        $pdf::Ln(6);    
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(20,6,"Start Date",0,"","L");
        $pdf::SetFont('Arial','',9);
        $startdate = Carbon::parse($start);
        $pdf::cell(40,6,': '.$startdate->format('m-d-Y') ,0,"","L");
        $pdf::Ln(4); 
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(20,6,"End Date",0,"","L");
        $pdf::SetFont('Arial','',9);
        $enddate = Carbon::parse($end);
        $pdf::cell(40,6,': '.$enddate->format('m-d-Y'),0,"","L");


        //$title = 'Sales Report';

       // $pdf::SetFont('Arial','BU',12);
        //$pdf::cell(70,1,"$title",0,"","C");


        
        // All Sales Column Header                    

            $pdf::Ln(8);
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(20,6,"Code",0,"","");   
            $pdf::cell(30,6,"Item",0,"","L");
            $pdf::cell(65,6,"Description",0,"","L");
            $pdf::cell(15,6,"Unit",0,"","L");
            $pdf::cell(25,6,"Orders Qty",0,"","R");
            $pdf::cell(35,6,"Sub Amount",0,"","R");
        
         $pdf::Ln(1);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"___________________________________________________________________________________________________________",0,"","L");

        
        $totalAmount = 0;

        $salesitems =  $this->salesreport->CollectSalesItems($start,$end);

        foreach ($salesitems as $key => $salesitem) {


                $pdf::Ln(5);
                $pdf::SetFont('Arial','',9);
                $pdf::cell(20,6,$salesitem->code,0,"","L");
                $pdf::cell(30,6,$salesitem->item_name,0,"","L");
                $pdf::cell(65,6,$salesitem->description,0,"","L");
                $pdf::cell(15,6,$salesitem->unit,0,"","L");
                $pdf::cell(25,6,$salesitem->order_qty,0,"","R");
                $pdf::cell(35,6,number_format($salesitem->sub_amount,2),0,"","R");

                $totalAmount  = $totalAmount + $salesitem->sub_amount;
   

        }

      
        $pdf::Ln(5);
            $pdf::SetFont('Arial','I',8);
            $pdf::cell(185,6,"--Nothing Follows--",0,"","C");

        $pdf::Ln(3);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"___________________________________________________________________________________________________________",0,"","L");

        $preparedby = $this->user->getCreatedbyAttribute(auth()->user()->id);

            $pdf::Ln(5);
            $pdf::SetFont('Arial','',9);
            $pdf::cell(135,6,"Prepared by:",0,"","L");
            $pdf::SetFont('Arial','B',10);
            $pdf::cell(30,6,"Total:",0,"","R");
            $pdf::SetFont('Arial','B',10);
            $pdf::cell(25,6,number_format( $totalAmount,2),0,"","R");
        
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
    


    public function printbalance(Request $request)
    {

        $this->validate($request, [
            'sched_date' => 'required',
            'areas_id'   => 'required',
        ]);

        
        return redirect()->route('salesreport.balancegenerate',[
        $request->sched_date,
        $request->areas_id]);

    }

    public function balancegenerate($scheddate,$areas)
    {

        $pdf = new Fpdf('P');
        $pdf::AddPage('P','A4');

        $pdf::SetFont('Arial','',7);
        $pdf::cell(170,0,date("m-d-Y") ,0,"","R");
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
        $pdf::cell(185,1,"Customer Balance Report",0,"","C");

        $pdf::Ln(6);    
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(20,6,"Date Schedule",0,"","L");
        $pdf::SetFont('Arial','',9);
        $date = Carbon::parse($scheddate);
        $pdf::cell(40,6,': '.$date->format('m-d-Y') ,0,"","L");



        //$title = 'Sales Report';

       // $pdf::SetFont('Arial','BU',12);
        //$pdf::cell(70,1,"$title",0,"","C");


        
        // All Sales Column Header                    

            $pdf::Ln(8);
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(30,6,"DR No.",0,"","");   
            $pdf::cell(45,6,"Customers",0,"","L");
            $pdf::cell(45,6,"Areas",0,"","L");
            $pdf::cell(30,6,"Balance",0,"","R");
            $pdf::cell(30,6,"SO Date",0,"","R");
        
         $pdf::Ln(1);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"___________________________________________________________________________________________________________",0,"","L");

        
        $totalAmount = 0;

        $balances =  $this->salesreport->CollectCustomerBalance($areas);

        foreach ($balances as $key => $balance) {


                $pdf::Ln(5);
                $pdf::SetFont('Arial','',9);
                $pdf::cell(30,6,$balance->so_number,0,"","L");
                $pdf::cell(45,6,$balance->customer,0,"","L");
                $pdf::cell(45,6,$balance->areas,0,"","L");
                $pdf::cell(30,6,number_format($balance->balances,2),0,"","R");
                $pdf::cell(30,6,$balance->so_date,0,"","R");

                $totalAmount  = $totalAmount + $balance->balances;
   

        }

      
        $pdf::Ln(5);
            $pdf::SetFont('Arial','I',8);
            $pdf::cell(185,6,"--Nothing Follows--",0,"","C");

        $pdf::Ln(3);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"___________________________________________________________________________________________________________",0,"","L");

        $preparedby = $this->user->getCreatedbyAttribute(auth()->user()->id);

            $pdf::Ln(5);
            $pdf::SetFont('Arial','',9);
            $pdf::cell(135,6,"Prepared by:",0,"","L");
            $pdf::SetFont('Arial','B',10);
            $pdf::cell(30,6,"Total:",0,"","R");
            $pdf::SetFont('Arial','B',10);
            $pdf::cell(25,6,number_format( $totalAmount,2),0,"","R");
        
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
