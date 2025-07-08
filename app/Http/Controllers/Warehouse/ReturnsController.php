<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Factories\Inventory\Factory as InventoryFactory;
use App\Factories\Item\Factory as ItemFactory;
use App\Factories\Returns\Factory as ReturnFactory;

use Fpdf;
use Carbon\Carbon;
use App\Item;
use App\Inventory;
use App\User as Users;
use App\SalesOrder;
use App\SalesOrderItem;
use App\SalesPayment;
use App\Returns;
use App\ReturnItems;
use App\WarehouseLocation;
use App\ItemReturntoSupplier;
use App\Customer;

class ReturnsController extends Controller
{
    public function __construct(
            Users $user,
            ItemFactory $items,
            InventoryFactory $inventory,
            ReturnFactory $returns
        )
    {
        $this->user = $user;
        $this->inventory = $inventory;
        $this->items = $items;
        $this->returns = $returns;
        $this->middleware('auth');  
    }
    

    public function index()
    {

        $returns =  $this->returns->getindex();

        $returntosuppliers = $this->returns->getreturntosupplier();

        return view('pages.warehouse.return.index',compact('returns','returntosuppliers'));
               
    }

    public function create()
    {
        $so_number = SalesOrder::whereIn('status',array('POSTED','CLOSED'))->pluck('so_number','so_number');
        
        $location  = WarehouseLocation::pluck('name','id');

        $received_by = $this->user->getemplist()->pluck('emp_name','id');

        return view('pages.warehouse.return.create',compact('so_number','location','received_by'));
               
    }

    public function getsalesorder(Request $request)
    {
        
        $so = SalesOrder::where('so_number',$request->so_num)->first();  

        $soitems = $this->returns->getsoitems($request->so_num);

        $customer =  Customer::findorfail($so->customer_id);

        return response()->json(['customer' => $customer , 'so' => $so , 'soitems'=> $soitems]);     

    }

    public function getreturnlist(Request $request)
    {
        
        $results = $this->returns->getreturnitems($request->id);

        return response()->json($results);     

    }

    
    public function store(Request $request)
    {

       $this->validate($request, [
            'location'  => 'required',
            'return_date'  => 'required',
            'received_by'   => 'required'
        ]);



       $returns = New Returns;

       $returns->reference_no   =   $this->returns->referenceNo();

       $returns->so_number      =    $request->so_number;

       $returns->return_date    =    $request->return_date;

       $returns->reason         =    $request->reason;

       $returns->amount         =    $request->total_amount;

       $returns->location       =    $request->location;

       $returns->status         =    0;

       $returns->received_by    =    $request->received_by;

       $returns->created_by     =    auth()->user()->id;

       $returns->save();

            $rid = $returns->id;
            $returnId = $request->get('return_id');
            $returnQty = $request->get('return_qty');

            for ( $i=0 ; $i < count($returnQty) ; $i++ ){
                
                $soitem = $this->returns->getsoitems($request->so_number)->where('id', $returnId[$i])->first();
          
                $returnItems = New ReturnItems;

                $returnItems->returns_id        =  $rid;

                $returnItems->item_id           = $soitem->item_id;

                $returnItems->item_quantity     = $soitem->order_quantity;

                $returnItems->return_quantity   = $returnQty[$i];

                $returnItems->unit_cost         = $soitem->unit_cost;

                $returnItems->srp               = $soitem->set_srp;

                $returnItems->save();

            }

         return redirect()->route('returns.index')

            ->with('success','Return has been saved successfully.');
    }

    public function edit($id)
    {

        $returns = Returns::findorfail($id);

        $so = SalesOrder::where('so_number',$returns->so_number)->first();

        $so_date = $so->so_date;

        $cs =  Customer::findorfail($so->customer_id);

        $customer = $cs->name;

        $location  = WarehouseLocation::pluck('name','id');

        $received_by = $this->user->getemplist()->pluck('emp_name','id');

        return view('pages.warehouse.return.edit',compact('returns','customer','location','so_date','received_by'));

    }


    public function update(Request $request,$id)
    {

       $this->validate($request, [
            'location'  => 'required',
            'return_date'  => 'required',
            'received_by'   => 'required'
        ]);



       $returns = Returns::findorfail( $id);


       $returns->return_date    =    $request->return_date;

       $returns->reason         =    $request->reason;

       $returns->amount         =    $request->total_amount;

       $returns->location       =    $request->location;

       $returns->received_by    =    $request->received_by;

       $returns->save();
   
             $retitems = $this->returns->getreturnitems($id);
              
             foreach ($retitems as $key => $retitem) {
                    
                 $returnItems = ReturnItems::findorfail($retitem->id);

                  $returnItems->delete();
             }


            $rid = $id;
            $returnId = $request->get('item_id');
            $returnQty = $request->get('return_qty');

            for ( $i=0 ; $i < count($returnQty) ; $i++ ){
                
                $soitem = $this->returns->getsoitems($returns->so_number)->where('item_id',$returnId[$i])->first();

                $returnItems = New ReturnItems;

                $returnItems->returns_id        = $rid;

                $returnItems->item_id           = $soitem->item_id;
 
                $returnItems->item_quantity     = $soitem->order_quantity;

                $returnItems->return_quantity   = $returnQty[$i];

                $returnItems->unit_cost         = $soitem->unit_cost;

                $returnItems->srp               = $soitem->set_srp;

                $returnItems->save();

            }

         return redirect()->route('returns.index')

            ->with('success','Return has been saved successfully.');
    }

    public function posting($id)
    {
        
       $returns = Returns::findorfail($id);

       $returnItems = ReturnItems::where('returns_id',$returns->id)->where('return_quantity','>',0)->get();

       // Adding New Item at Inventory as Return
       foreach ($returnItems as $key => $returnitem) {

            $so = SalesOrder::where('so_number',$returns->so_number)->first();

            $items = Item::findorfail($returnitem->item_id);

            $inventory = New Inventory;
            $inventory->item_id           = $returnitem->item_id;
            $inventory->unit_quantity     = $returnitem->return_quantity;
            $inventory->onhand_quantity   = $returnitem->return_quantity;
            $inventory->unit_cost         = $returnitem->unit_cost;
            $inventory->location          = $so->location;
            $inventory->received_date     = $returns->return_date;
            $inventory->expiration_date   = NULL;
            $inventory->status            = 'Return';
            $inventory->consumable        = 2;
            $inventory->created_by        = auth()->user()->id;
            $inventory->save();

       }    

        $returns->status         =    1;

        $returns->save();

            // Less Amount Return item on Sales Order
           $sorder = SalesOrder::where('so_number',$returns->so_number)->first();

               $salesORder = SalesOrder::findorfail($sorder->id);

                   $lessReturn =  $salesORder->total_sales - $returns->amount;

                   $salesORder->total_sales     =  $lessReturn;

                   $salesORder->save();

            // Less amount of SRP and Quantity

            $getitemlists = ReturnItems::where('returns_id',$returns->id)->where('return_quantity','>',0)->get();

             foreach ($getitemlists as $key => $getitemlist){

                $soitems = SalesOrderItem::where('sales_order_id',$sorder->id)->where('item_id',$getitemlist->item_id)->first();

                    $updatesoitem = SalesOrderItem::findorfail($soitems->id);

                    $updatesoitem->order_quantity = $updatesoitem->order_quantity - $getitemlist->return_quantity;

                    $updatesoitem->sub_amount     = $updatesoitem->order_quantity * $updatesoitem->set_srp;

                    $updatesoitem->save();

           // Less amount on Sales Payment

            $getSalesPayment = SalesPayment::where('sales_order_id',$sorder->id)->first();

            $SalesPayment =SalesPayment::findorfail($getSalesPayment->id);

                    $sorder = SalesOrder::findorfail($sorder->id);

            $SalesPayment->sales_total =  $sorder->total_sales;

            $SalesPayment->save();


         }



        return redirect()->route('returns.index')

            ->with('success','Return has been posted successfully.');
    }

    public function destroy($id)
    {
        
       $returns = Returns::findorfail($id);

       $returns->delete();

            $retitems = ReturnItems::where('returns_id',$id)->get();

             foreach ($retitems as $key => $retitem) {
                
                 $returnItems = ReturnItems::findorfail($retitem->id);

                 $returnItems->delete();
             }


        return redirect()->route('returns.index')

            ->with('success','Return has been deleted successfully.');
    }



     public function print($id) 
    {
 
        $returns = Returns::findorfail($id);

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
        $pdf::cell(185,1,"Return Items Report",0,"","C");

        $pdf::Ln(15);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(35,6,"Reference No.:",0,"","L");
        $pdf::SetFont('Arial','',9);
        $pdf::cell(25,6,$returns->reference_no,0,"","L");


        $pdf::Ln(5);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(35,6,"Return Date:",0,"","L");
        $pdf::SetFont('Arial','',9);
        $return_date = Carbon::parse($returns->return_date);
        $pdf::cell(25,6,''.$return_date->format('M d, Y'),0,"","L");


        $pdf::Ln(7);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(35,6,"Sales Order No. :",0,"","L");
        $pdf::SetFont('Arial','',9);
        $pdf::cell(80,6,$returns->so_number,0,"","L");



        $so = SalesOrder::where('so_number',$returns->so_number)->first();


        $cs = Customer::findorfail($so->customer_id);


        $pdf::SetFont('Arial','B',9);
        $pdf::cell(20,6,"SO Date:",0,"","L");
        $pdf::SetFont('Arial','',9);
        $so_date = Carbon::parse($so->so_date);
        $pdf::cell(25,6,''.$so_date->format('M d, Y'),0,"","L");

        $pdf::Ln(5);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(35,6,"Reason : ",0,"","L");
        $pdf::SetFont('Arial','',9);
        $pdf::cell(80,6,$returns->reason,0,"","L");


        $pdf::SetFont('Arial','B',9);
        $pdf::cell(20,6,"Customer :",0,"","L");
        $pdf::SetFont('Arial','',9);
        $pdf::cell(25,6,$cs->name,0,"","L");


        //Column Name
            $pdf::Ln(10);
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(15,6,"No.",0,"","C");
            $pdf::cell(65,6,"Item Name",0,"","L");
            $pdf::cell(25,6,"Units",0,"","L");
            $pdf::cell(35,6,"Order Quantity",0,"","C");
            $pdf::cell(35,6,"Returned Quantity",0,"","C");
            
             


         $pdf::Ln(1);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");
        
          $returnItems =  $this->returns->getreturnitems($returns->id)->where('return_quantity','>',0);

        $ctr_item = 0;

        foreach ($returnItems as $key => $value) {

            $pdf::Ln(5);
            $pdf::SetFont('Arial','',9);
            $pdf::cell(15,6,$ctr_item = $ctr_item + 1,0,"","C");
            $pdf::cell(65,6,$value->item_name,0,"","L");
            $pdf::cell(25,6,$value->unit,0,"","L");
            $pdf::cell(35,6,number_format($value->item_quantity,2),0,"","C");
            $pdf::cell(35,6,number_format($value->return_quantity,2),0,"","C");

        }

        $pdf::Ln(5);
            $pdf::SetFont('Arial','I',8);
            $pdf::cell(185,6,"--Nothing Follows--",0,"","C");

        $pdf::Ln(3);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");

        $count = $this->returns->getreturnitems($returns->id)->count();

        $pdf::Ln(4);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(25,6,"Total Count:",0,"","L");
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(25,6,$count,0,"","L");

        $prepared_by = $this->user->getCreatedbyAttribute(auth()->user()->id);

        $pdf::Ln(10);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(275,6,"Prepared by:  ",0,"","C");
        $pdf::cell(25,6,"",0,"","C");

        $pdf::Ln(10);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(300  ,6,"      ".$prepared_by."      ",0,"","C");
        $pdf::cell(60,6,"      ".""."      ",0,"","C");


        $pdf::ln(0);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(300,6,"_________________________",0,"","C");
        $pdf::cell(60,6,"",0,"","C");




        $pdf::Ln();
        $pdf::Output();
        exit;


    }

}
