<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Factories\SalesPayment\Factory as SalesPaymentFactory;
use Yajra\Datatables\Datatables;
use App\SalesOrder;
use App\SalesPayment;
use App\SalesPaymentTerm;
use App\ModeOfPayment;
use App\Customer; 
use App\User as Users;
use Carbon\Carbon;
use Fpdf;
use DB;

class SalesPaymentController extends Controller
{

    public function __construct(
            Users $user,
            SalesPaymentFactory $salespayment
        )
    {
        $this->user = $user;
        $this->salespayment = $salespayment;
        $this->middleware('auth');
    }


    public function index()
    {   

        return view('pages.salespayment.index');

    }


    public function datatable(Request $request)
    {

        $results =  $this->salespayment->getindex();

        return response()->json($results); 
      
    }


    public function getSOinfo(Request $request)
    {

        $results =  SalesOrder::where('so_number',$request->id)->first();
        
        return response()->json($results); 
      
    }


    public function create()
    {
        $so_number = SalesOrder::where('status','POSTED')
                        ->where('inventory_deducted',1)
                            ->pluck('so_number','so_number');

        $creator = $this->user->getCreatedbyAttribute(auth()->user()->id);

        // Modal info

        $payment_mode = ModeOfPayment::pluck('name','id');

        return view('pages.salespayment.create',compact('so_number','creator','payment_mode'));
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'so_number' => 'required',
            'payment_type'   => 'required',
            'created_by' => 'required'
        ]);


        $salesorder = SalesOrder::where('so_number',$request->so_number)->first();


        $sales_payments                     = New SalesPayment;

        $sales_payments->sales_order_id     =  $salesorder->id;

        $sales_payments->so_number          =  $request->so_number;

        $sales_payments->sales_total        =  $salesorder->total_sales;

        $sales_payments->payment_type       =  $request->payment_type;

        $sales_payments->payment_status     = $request->payment_status;

        $sales_payments->created_by         =  auth()->user()->id;

        $sales_payments->updated_by         =  0;

        $sales_payments->save();


        return redirect()->route('sales_payment.index')

            ->with('success','Payment details has been saved successfully.');

    }


    public function update($id)
    {   

        $salespayments = SalesPayment::findOrfail($id);

        $salesorder = SalesOrder::findOrfail($salespayments->sales_order_id);

        $customer = Customer::findOrfail($salesorder->customer_id);

        $payment_mode = ModeOfPayment::pluck('name','id');

        $collector = $this->user->getemplist()->pluck('emp_name','id');

        $creator = $this->user->getCreatedbyAttribute(auth()->user()->id);

        $total_paid =  $this->salespayment->totalpaid($id)->first();

        return view('pages.salespayment.collect',compact('payment_mode','salespayments','customer','collector','creator','salesorder','total_paid'));
    }


    public function storeitems(Request $request)
    {
        $this->validate($request, [
            'date_payment' => 'required',
            'trasanction_no' => 'required',
            'payment_mode_id' => 'required',
            'amount_collected' => 'required',
            'collected_by' => 'required',
        ]);

        $salespayments = SalesPayment::findOrfail($request->sales_payment_id);

        $total_paid =  $this->salespayment->totalpaid($request->sales_payment_id)->first();
 
        $totalSales = $salespayments->sales_total ;

        $totalPayment = $total_paid->amount +  $request->amount_collected;


            $paymentterms = New SalesPaymentTerm;

            $paymentterms->sales_payment_id     = $request->sales_payment_id;

            $paymentterms->date_payment         = $request->date_payment;

            $paymentterms->payment_mode_id      = $request->payment_mode_id;

            $paymentterms->trasanction_no       = $request->trasanction_no;

            $paymentterms->bank_name            = $request->bank_name;

            $paymentterms->bank_account_no      = $request->bank_account_no;

            $paymentterms->bank_account_name    = $request->bank_account_name;

            $paymentterms->amount_collected     = $request->amount_collected;

            $paymentterms->collected_by         = $request->collected_by;

            $paymentterms->created_by           = auth()->user()->id;

            $paymentterms->save();


        if ( $totalPayment > $totalSales ){


            $salesPayment = SalesPayment::find($request->sales_payment_id);

            $salesPayment->payment_status = 'Completed';

            $salesPayment->save();
            

            $salesorder = SalesOrder::findOrfail($salespayments->sales_order_id);

            $salesorder->status = 'CLOSED';

            $salesorder->save();


                return redirect()->route('sales_payment.update',$request->sales_payment_id)

                    ->with('warning','Customer has been made an Overpayment Transaction.');

        } elseif ( $totalPayment == $totalSales ) {


            $salesPayment = SalesPayment::find($request->sales_payment_id);

            $salesPayment->payment_status = 'Completed';

            $salesPayment->save();


            $salesorder = SalesOrder::findOrfail($salespayments->sales_order_id);

            $salesorder->status = 'CLOSED';

            $salesorder->save();

            
                return redirect()->route('sales_payment.update',$request->sales_payment_id)

                    ->with('success','Customer Amount Due has been Completed!');
        } else {

            return redirect()->route('sales_payment.update',$request->sales_payment_id)

                    ->with('success','Payment terms has been added successfully.');

        }


    }


    public function showpayments(Request $request)
    {

        $results =  $this->salespayment->showpayments($request->id);

        return response()->json($results); 
      
    }


    public function details(Request $request)
    {
        
        $results =  $this->salespayment->showpayments($request->spID)->where('id',$request->id)->first();

        return response()->json($results); 
    }


    public function edit($id)
    {
        
        $results =  SalesPaymentTerm::findOrfail($id);

        return response()->json($results); 


    }


    public function remove($id)
    {
        $SalesPaymentTerm =  SalesPaymentTerm::findOrfail($id);

        $SalesPaymentTerm->delete();

        return redirect()->route('sales_payment.update',$SalesPaymentTerm->sales_payment_id)

                    ->with('success','Payment terms has been remove successfully.');

    }

    public function destroy($id)
    {
        $salespayment = SalesPayment::find($id);

            $salesorder = SalesOrder::findOrfail($salespayment->sales_order_id);

            $salesorder->status = 'POSTED';

            $salesorder->save();

        
                $terms = SalesPaymentTerm::where('sales_payment_id',$id)->get();

                for ( $i=0 ; $i < count($terms) ; $i++ ){

                    $term = SalesPaymentTerm::findOrfail($terms[$i]->id);

                    $term->delete();

                }

        $salespayment->delete();

        return redirect()->route('sales_payment.index')

            ->with('success','Payment details has been deleted successfully.');

    }

    public function print($id)
    {
        
        $salesPayment = SalesPayment::find($id);
        $salesOrder = SalesOrder::findOrfail($salesPayment->sales_order_id);     
        
        $pdf = new Fpdf('P');
        $pdf::AddPage('P','A4');
        //$pdf::Image('/home/u648374046/domains/monsais.net/public_html/public/img/monsa-logo-header.jpg',10, 5, 30.00);
        $pdf::Image('img/temporary-logo.jpg',5, 5, 40.00);
        $pdf::SetFont('Arial','B',12);
        $pdf::SetY(20);     


        // Header
        //$pdf::Image('img/monsa-logo-header.jpg',90, 5, 25.00);
        //$pdf::Image('img/qplc_logo.jpg',5, 5, 40.00);
        $pdf::SetFont('Arial','B',12);
        $pdf::SetY(20);  

        $pdf::Ln(2);
        $pdf::SetFont('Arial','B',12);
        $pdf::SetXY($pdf::getX(), $pdf::getY());
        $pdf::cell(185,1,"Sales Payment",0,"","C");

        $pdf::Ln(18);
        $pdf::SetFont('Arial','B',9);
        $pdf::SetXY($pdf::getX(), $pdf::getY());
        $pdf::cell(20,6,"SO Number",0,"","L");
        $pdf::SetFont('Arial','',9);
        $pdf::cell(40,6,': '.$salesPayment->so_number,0,"","L");
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(100,6,"SO Date",0,"","R");
        $pdf::SetFont('Arial','',9);
        $so_date = Carbon::parse($salesOrder->so_date);
        $pdf::cell(30,6,': '.$so_date->format('M d, Y'),0,"","L");
        

        $pdf::Ln(6);
        $pdf::SetFont('Arial','B',9);
        $pdf::SetXY($pdf::getX(), $pdf::getY());
        $pdf::cell(20,6,"Customer",0,"","L");
        $pdf::SetFont('Arial','',9);
        $Customer = Customer::find($salesOrder->customer_id);
        $pdf::cell(40,6,': '.$Customer->name,0,"","L");

        $pdf::Ln(6);
        $pdf::SetFont('Arial','B',9);
        $pdf::SetXY($pdf::getX(), $pdf::getY());
        $pdf::cell(20,6,"Status",0,"","L");
        $pdf::SetFont('Arial','',9);
        $pdf::cell(40,6,': '.$salesPayment->payment_status,0,"","L");

        $pdf::Ln(6);
        $pdf::SetFont('Arial','B',9);
        $pdf::SetXY($pdf::getX(), $pdf::getY());
        $pdf::cell(20,6,"Payment Type",0,"","L");
        $pdf::SetFont('Arial','',9);
        $pdf::cell(40,6,': '.$salesPayment->payment_type,0,"","L");


        //Column Name
            $pdf::Ln(15);
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(10,6,"No.",0,"","L");
            $pdf::cell(30,6,"Payment Date",0,"","L");
            $pdf::cell(30,6,"Transaction No.",0,"","L");
            $pdf::cell(30,6,"Payment Mode",0,"","L");
            $pdf::cell(30,6,"Bank Name",0,"","L");
            $pdf::cell(30,6,"Collector",0,"","L");
            $pdf::cell(25,6,"Paid Amount",0,"","R");


         $pdf::Ln(1);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");

        $orde_items = $this->salespayment->showpayments($id);
        $order_number = 0;
        foreach ($orde_items as $key => $value) {

            $pdf::Ln(5);
            $pdf::SetFont('Arial','',9);
            $pdf::cell(10,6,$order_number=$order_number+1,0,"","L");
            $pdf::cell(30,6,$value->date_payment,0,"","L");
            $pdf::cell(30,6,$value->trasanction_no,0,"","L");
            $pdf::cell(30,6,$value->modes,0,"","L");
            $pdf::cell(30,6,$value->bank_name,0,"","L");
            $pdf::cell(30,6,$value->collected_by,0,"","L");
            $pdf::cell(25,6,number_format($value->amount_collected,2),0,"","R");
        }

        $pdf::Ln(5);
            $pdf::SetFont('Arial','I',8);
            $pdf::cell(185,6,"--Nothing Follows--",0,"","C");

        $pdf::Ln(3);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");


        $total_paid =  $this->salespayment->totalpaid($id)->first();

        $totalSales = $salesPayment->sales_total ;

        $totalBalance =  $totalSales - $total_paid->amount;


        $pdf::Ln(10);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(150,6,"Total Amount :",0,"","R");
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,number_format($totalSales,2),0,"","R");

        $pdf::Ln(5);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(150,6,"Total Paid :",0,"","R");
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,number_format($total_paid->amount,2),0,"","R");
        
        $pdf::Ln(5);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(150,6,"Total Balance :",0,"","R");
        $pdf::SetFont('Arial','B',10);
        $pdf::cell(30,6,number_format($totalBalance,2),0,"","R");


        $preparedby = $this->user->getCreatedbyAttribute($salesPayment->created_by);


        $pdf::Ln(10);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(60,6,"      ".$preparedby."      ",0,"","C");
        //$pdf::cell(60,6,"      ".""."      ",0,"","C");
        //$pdf::cell(60,6,"      ".$approveddby."      ",0,"","C");

        $pdf::ln(0);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(60,6,"_________________________",0,"","C");
        //$pdf::cell(60,6,"",0,"","C");
        //$pdf::cell(60,6,"_________________________",0,"","C");


        $pdf::Ln(4);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(60,6,"Prepared by",0,"","C");
        //$pdf::cell(60,6,"",0,"","C");
        //$pdf::cell(60,6,"Approved by",0,"","C");


        $pdf::Ln();
        $pdf::Output();
        exit;
    }
}
