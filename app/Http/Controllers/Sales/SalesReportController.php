<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
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

        //Column Name
            $pdf::Ln(6);
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(20,6,"Payment Date",0,"","C");
            $pdf::cell(30,6,"Address",0,"","C");
            $pdf::cell(25,6,"DR No.",0,"","C");
            $pdf::cell(30,6,"Customer",0,"","C");
            $pdf::cell(30,6,"Payment Mode",0,"","C");
            $pdf::cell(25,6,"Amount",0,"","R");
            $pdf::cell(25,6,"Status",0,"","R");

         $pdf::Ln(1);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");
        
        $payments =  $this->salesreport->paymentAll($start,$end);
        $tatolAmount = 0;
        foreach ($payments as $key => $payment) {
            $pdf::Ln(5);
            $pdf::SetFont('Arial','',9);
            $pdf::cell(25,6,$payment->date_payment,0,"","L");
            $pdf::cell(30,6,$payment->address,0,"","L");
            $pdf::cell(25,6,$payment->so_number,0,"","L");
            $pdf::cell(35,6,$payment->cs_name,0,"","L");
            $pdf::cell(30,6,$payment->paymode,0,"","L");
            $pdf::cell(15,6,number_format($payment->amount_collected,2),0,"","R");
            $pdf::cell(25,6,$payment->payment_status,0,"","R");

            $tatolAmount = $tatolAmount + $payment->amount_collected;
        }

        $pdf::Ln(5);
            $pdf::SetFont('Arial','I',8);
            $pdf::cell(185,6,"--Nothing Follows--",0,"","C");

        $pdf::Ln(3);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");


        $pdf::Ln(5);
        $pdf::SetFont('Arial','B',10);
        $pdf::cell(130,6,"Total:",0,"","R");
        $pdf::SetFont('Arial','B',10);
        $pdf::cell(30,6,number_format( $tatolAmount,2),0,"","R");

       

        $preparedby = $this->user->getCreatedbyAttribute(auth()->user()->id);
       

 

        $pdf::Ln(10);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(35,6,"Prepared by",0,"","C");
        $pdf::cell(60,6,"",0,"","C");

       $pdf::Ln(5);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(60,6,"      ".$preparedby."      ",0,"","C");
        $pdf::cell(60,6,"      ".""."      ",0,"","C");

        $pdf::ln(0);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(60,6,"_________________________",0,"","C");
        $pdf::cell(60,6,"",0,"","C");

        $pdf::Ln();
        $pdf::Output();
        exit;

        return $pdf;
    }


    
}
