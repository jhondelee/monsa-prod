<?php

namespace App\Http\Controllers\PurchaseOrder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Factories\Item\Factory as ItemFactory;
use App\Factories\Order\Factory as OrderFactory;
use App\Factories\Incoming\Factory as IncomingFactory;
use App\Item;
use App\Order;
use App\OrderItems;
use App\Incoming;
use App\IncomingItem;
use App\UnitOfMeasure; 
use App\Supplier; 
use App\User as Users;
use Carbon\Carbon;
use Fpdf;
use DB;

class IncomingController extends Controller
{
    
    public function __construct(
            Users $user,
            ItemFactory $items,
            OrderFactory $orders,
            IncomingFactory $incomings
        )
    {
        $this->user = $user;
        $this->items = $items;
        $this->orders = $orders;
        $this->incomings = $incomings;
        $this->middleware('auth');  
    }


     public function index()
    {
        $incomings = $this->incomings->getindex();

        return view('pages.purchase_order.incoming.index',compact('incomings'));
    }


    public function create()
    {
        $po_number = Order::where('status','POSTED')->pluck('po_number','id');

        $received_by = $this->user->getemplist()->pluck('emp_name','id');

        return view('pages.purchase_order.incoming.create',compact('received_by','po_number'));
    }


    public function receiving(Request $request) 
    {

        $po_details = Order::findOrfail($request->id);
    
        $approved_by = $this->user->getCreatedbyAttribute($po_details->approved_by);

        $created_by = $this->user->getCreatedbyAttribute($po_details->created_by);

        $po_items   = $this->items->getForPO($request->id);

        $supplier = Supplier::findOrfail($po_details->supplier_id);
        
        return response()->json(['po_details' => $po_details, 'po_items' => $po_items,'approved_by'=>$approved_by,'created_by'=> $created_by, 'supplier' => $supplier ]);


    }


     public function store(Request $request) 
    {

        $this->validate($request, [
            'dr_number' => 'required',
            'dr_date'   => 'required',
            'received_by' => 'required'
        ]);
 
        $incomings                  = New Incoming;

        $incomings->order_id        = $request->order_id;

        $incomings->po_number       = $request->po_number_input;

        $incomings->dr_number       = $request->dr_number;

        $incomings->dr_date         = $request->dr_date;

        $incomings->notes           = $request->notes;

        $incomings->discount        = $request->discount_input;

        $incomings->total_amount    = $request->total_amount_input;

        $incomings->received_by     = $request->received_by;

        $incomings->status          = 'RECEIVING';

        $incomings->save();
        
        $incoming_id            = $incomings->id;
        $item_id                = $request->get('item_id');
        $received_qty           = $request->get('received_qty');
        $item_unit_cost         = $request->get('unit_cost');


        for ( $i=0 ; $i < count($item_id) ; $i++ ){

            $items = $this->items->getForPO($request->order_id)->where('id', $item_id[$i])->first();

            $incoming_items                         = New IncomingItem;

            $incoming_items->incoming_id            = $incoming_id ;

            $incoming_items->item_id                = $items->id;

            $incoming_items->received_quantity      = $received_qty[$i];

            $incoming_items->unit_cost              = $item_unit_cost[$i];

            $incoming_items->unit_total_cost        = $items->unit_total_cost;

            $incoming_items->save();


        }


        return redirect()->route('incoming.index')

            ->with('success','Incoming item has been saved successfully.');
    }



    public function destroy($id)
    {

        $incomings = Incoming::findOrfail($id);

        $incomingitems = IncomingItem::where('incoming_id',$id)->get();

            if(count($incomingitems) > 0)
            {
                foreach ($incomingitems as $key => $incomingitem) 
                {
                    $items = IncomingItem::findOrfail($incomingitem->id);

                    $items->delete();
                }

            }

        $incomings->delete();

        return redirect()->route('incoming.index')

            ->with('success','Incoming item has been deleted successfully.');
    }



    public function edit($id)
    {
        $incomings = Incoming::findOrfail($id);

        $po_details  = Order::findOrfail($incomings->order_id);

        $received_by = $this->user->getemplist()->pluck('emp_name','id');

        $created_by = $this->user->getCreatedbyAttribute($po_details->created_by);

        $approved_by = $this->user->getCreatedbyAttribute($po_details->approved_by);

        $supplier = Supplier::findOrfail($po_details->supplier_id);

        $incoming_items   = $this->incomings->getIncomingItems($id);

        return view('pages.purchase_order.incoming.edit',compact('supplier','received_by','incomings','po_details','created_by','approved_by','incoming_items'));
    }



    public function update(Request $request,$id) 
    {

        $this->validate($request, [
            'dr_number' => 'required',
            'dr_date'   => 'required',
            'received_by' => 'required'
        ]);

        $incomings                  = Incoming::findOrfail($id);

        $incomings->order_id        = $request->order_id;

        $incomings->po_number       = $request->po_number_input;

        $incomings->dr_number       = $request->dr_number;

        $incomings->dr_date         = $request->dr_date;

        $incomings->notes           = $request->notes;

        $incomings->discount        = $request->discount_input;

        $incomings->total_amount    = $request->total_amount_input;

        $incomings->received_by     = $request->received_by;

        $incomings->status          = 'RECEIVING';

        $incomings->save();
        
        $incoming_id            = $incomings->id;
        $item_id                = $request->get('item_id');
        $received_qty           = $request->get('received_qty');
        $item_unit_cost         = $request->get('unit_cost');


        $incomingitems = IncomingItem::where('incoming_id',$id)->get();

            if(count($incomingitems) > 0)
            {
                foreach ($incomingitems as $key => $incomingitem) 
                {
                    $items = IncomingItem::findOrfail($incomingitem->id);

                    $items->delete();
                }

            }



        for ( $i=0 ; $i < count($item_id) ; $i++ ){

            $items = $this->items->getForPO($request->order_id)->where('id', $item_id[$i])->first();

            $incoming_items                         = New IncomingItem;

            $incoming_items->incoming_id            = $incoming_id ;

            $incoming_items->item_id                = $items->id;

            $incoming_items->received_quantity      = $received_qty[$i];

            $incoming_items->unit_cost              = $item_unit_cost[$i];

            $incoming_items->unit_total_cost        = $items->unit_total_cost;

            $incoming_items->save();


        }


        return redirect()->route('incoming.index')

            ->with('success','Incoming item has been saved successfully.');
    }



    public function post($id)
    {

        $incomings = Incoming::find($id);

        $incomings->status = 'CLOSED';

        $incomings->save();

           $itemid =  IncomingItem::where('incoming_id',$id)->pluck('item_id');

            for ( $i=0 ; $i < count($itemid) ; $i++ ){

                $items              = Item::findOrfail($itemid[i]);

                $items->unit_cost   = $items->unit_cost;

                $items->save();

            }

        
        return redirect()->route('incoming.index')

            ->with('success','Incoming item has been posted successfully.');

    }


     public function print($id)
    {
        
        $incomings = Incoming::find($id);   
        
        $pdf = new Fpdf('P');
        $pdf::AddPage('P','A4');
        $pdf::Image('img/monsa-logo-header.jpg',10, 5, 30.00);
        //$pdf::Image('img/temporary-logo.jpg',5, 5, 40.00);
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
        $pdf::cell(185,1,"Received Orders",0,"","C");

        $pdf::Ln(18);
        $pdf::SetFont('Arial','B',9);
        $pdf::SetXY($pdf::getX(), $pdf::getY());
        $pdf::cell(20,6,"PO Number",0,"","L");
        $pdf::SetFont('Arial','',9);
        $pdf::cell(40,6,': '.$incomings->po_number,0,"","L");
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(100,6,"DR Date",0,"","R");
        $pdf::SetFont('Arial','',9);
        $po_date = Carbon::parse($incomings->dr_date);
        $pdf::cell(30,6,': '.$po_date->format('M d, Y'),0,"","L");
        

        $orders = Order::find($incomings->order_id);

        $pdf::Ln(6);    
        $pdf::SetFont('Arial','B',9);
        $pdf::SetXY($pdf::getX(), $pdf::getY());
        $pdf::cell(20,6,"Supplier",0,"","L");
        $pdf::SetFont('Arial','',9);
        $supplier = Supplier::find($orders->supplier_id);
        $pdf::cell(40,6,': '.$supplier->name,0,"","L");

        $pdf::Ln(6);
        $pdf::SetFont('Arial','B',9);
        $pdf::SetXY($pdf::getX(), $pdf::getY());
        $pdf::cell(20,6,"Status",0,"","L");
        $pdf::SetFont('Arial','',9);
        $pdf::cell(40,6,': '.$incomings->status,0,"","L");

        $pdf::Ln(6);
        $pdf::SetFont('Arial','B',9);
        $pdf::SetXY($pdf::getX(), $pdf::getY());
        $pdf::cell(20,6,"Remarks",0,"","L");
        $pdf::SetFont('Arial','',9);
        $pdf::cell(40,6,': '.$incomings->notes,0,"","L");


        //Column Name
            $pdf::Ln(15);
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(25,6,"Item No.",0,"","C");
            $pdf::cell(75,6,"Item Name",0,"","L");

            $pdf::cell(20,6,"Unit",0,"","C");
            $pdf::cell(30,6,"Quantity",0,"","C");
            $pdf::cell(30,6,"Received Qty",0,"","C");


         $pdf::Ln(1);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");
        
        $incoming_items   = $this->incomings->getIncomingItems($id);
        
        foreach ($incoming_items as $key => $value) {

            $pdf::Ln(5);
            $pdf::SetFont('Arial','',9);
            $pdf::cell(25,6,$value->code,0,"","C");
            $pdf::cell(75,6,$value->name,0,"","L");

            $pdf::cell(20,6,$value->units,0,"","C");
            $pdf::cell(30,6,number_format($value->quantity,2),0,"","C");
            $pdf::cell(30,6,number_format($value->received_quantity,2),0,"","C");
        }

        $pdf::Ln(5);
            $pdf::SetFont('Arial','I',8);
            $pdf::cell(185,6,"--Nothing Follows--",0,"","C");

        $pdf::Ln(3);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");



        $pdf::Ln(10);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(150,6,"Discount :",0,"","R");
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,number_format($orders->discount,2),0,"","R");

        $pdf::Ln(5);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(150,6,"Total Amount :",0,"","R");
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,number_format($orders->grand_total,2),0,"","R");

       

        $received_by = $this->user->getCreatedbyAttribute($incomings->received_by);
       

 

        $pdf::Ln(25);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(35,6,"Received by",0,"","C");
        $pdf::cell(60,6,"",0,"","C");

       $pdf::Ln(10);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(60,6,"      ".$received_by."      ",0,"","C");
        $pdf::cell(60,6,"      ".""."      ",0,"","C");


        $pdf::ln(0);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(60,6,"_________________________",0,"","C");
        $pdf::cell(60,6,"",0,"","C");




        $pdf::Ln();
        $pdf::Output();
        exit;

    }


}
