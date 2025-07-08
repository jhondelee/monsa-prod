<?php

namespace App\Http\Controllers\PurchaseOrder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Factories\Item\Factory as ItemFactory;
use App\Factories\Order\Factory as OrderFactory;
use App\Item;
use App\Order;
use App\OrderItems;
use App\UnitOfMeasure; 
use App\Supplier; 
use App\User as Users;
use Carbon\Carbon;
use Fpdf;
use DB;

class OrderController extends Controller
{

    public function __construct(
            Users $user,
            ItemFactory $items,
            OrderFactory $orders
        )
    {
        $this->user = $user;
        $this->items = $items;
        $this->orders = $orders;
        $this->middleware('auth');
    }


    public function index()
    {

        $orders         = $this->orders->getindex()->where('status','NEW')->sortByDesc('id');
        $posted_order   = $this->orders->getindex()->where('status','POSTED')->sortByDesc('id');
        $cancel_order   = $this->orders->getindex()->where('status','CANCELED')->sortByDesc('id');
        $closed_order   = $this->orders->getindex()->where('status','CLOSED')->sortByDesc('id');
        

        return view('pages.purchase_order.orders.index',compact('orders','posted_order','cancel_order','closed_order'));
    }


    public function create()
    {
        
        $items = $this->items->getindex();

        $creator = $this->user->getCreatedbyAttribute(auth()->user()->id);

        $approver = $this->user->getemplist()->pluck('emp_name','id');

        $supplier = Supplier::pluck('name','id');

        $order_status = "NEW";

        return view('pages.purchase_order.orders.create',compact('creator','approver','supplier','items','order_status'));
    }

    public function store(Request $request)
    {
       
        $this->validate($request, [
            'po_date'       => 'required',
            'supplier_id'   => 'required',
            'approved_by'   => 'required'
        ]);

        $orders                  = New Order;

        $orders->po_number      = $this->orders->getPONo();

        $orders->po_date        = $request->po_date;

        $orders->remarks        = $request->remarks;

        $orders->supplier_id    = $request->supplier_id;

        $orders->discount       = $request->discount;

        $orders->grand_total    = $request->grand_total;

        $orders->approved_by    = $request->approved_by;

        $orders->created_by     = auth()->user()->id;

        $orders->status         = 'NEW';

        $orders->save();

        $orders_id      = $orders->id;
        $item_id        = $request->get('item_id');
        $item_quantity  = $request->get('quantity');
        $unit_cost      = $request->get('unit_cost');
        $total_amount   = $request->get('total_amount');
        

        for ( $i=0 ; $i < count($item_id) ; $i++ ){

            $items = $this->items->getindex()->where('id', $item_id[$i])->first();

            $order_items                    = New OrderItems;

            $order_items->order_id          = $orders_id;

            $order_items->item_id           = $items->id;

            $order_items->quantity          = $item_quantity[$i];

            $order_items->unit_cost         = $unit_cost[$i];

            $order_items->unit_total_cost   = floatval($total_amount[$i]);

            $order_items->save();


        }


        return redirect()->route('order.index')

            ->with('success','Order has been saved successfully.');
    }
    

    public function getItems(Request $request)
    {
        
        $results = $this->items->getindex()->where('id', $request->id)->first();   

        return response()->json($results);       
        
    }

    public function getPOitems(Request $request)
    {
     
        $results = $this->items->getForPO($request->id);   

        return response()->json($results);       
        
    }


    public function supplierItems(Request $request)
    {
        
        $results = $this->items->GetItemsOfsupplier($request->id);   

        return response()->json($results);       
        
    }


    public function edit($id)
    {

        $items = $this->items->getindex();

        $creator = $this->user->getCreatedbyAttribute(auth()->user()->id);

        $approver = $this->user->getemplist()->pluck('emp_name','id');

        $supplier = Supplier::pluck('name','id');

        $order = Order::find($id);

        $order_status = $order->status;

        return view('pages.purchase_order.orders.edit',compact('order','creator','approver','supplier','items','order_status'));

    }


    public function update(Request $request,$id)
    {
          $this->validate($request, [
            'po_date'       => 'required',
            'supplier_id'   => 'required',
            'approved_by'   => 'required'
        ]);


        $orders                  = Order::find($id);

        $orders->po_number      = $request->po_number;

        $orders->po_date        = $request->po_date;

        $orders->remarks        = $request->remarks;

        $orders->supplier_id    = $request->supplier_id;

        $orders->discount       = $request->discount;

        $orders->grand_total    = $request->grand_total;

        $orders->approved_by    = $request->approved_by;

        $orders->save();

        $item_id        = $request->get('id');
        $item_quantity  = $request->get('quantity');
        $unit_cost      = $request->get('unit_cost');
        $total_amount   = $request->get('unit_total_cost');



         if(count($request->get('id')) > 0);
        {

            $order_items = OrderItems::where('order_id',$id)->get();

             if(count($order_items) > 0)
             {
                foreach ($order_items as $key => $order_item) 
                {
                    $orderitems = OrderItems::findOrfail($order_item->id);

                    $orderitems->delete();
                }

             }



            for ( $i=0 ; $i < count($item_id) ; $i++ ){

                $items = $this->items->getindex()->where('id', $item_id[$i])->first();

                $order_items                    = New OrderItems;

                $order_items->order_id          = $id;

                $order_items->item_id           = $items->id;

                $order_items->quantity          = $item_quantity[$i];

                $order_items->unit_cost         = $unit_cost[$i];

                $order_items->unit_total_cost   = $total_amount[$i];

                $order_items->save();

                               
            }

             return redirect()->route('order.index')

                ->with('success','Order has been saved successfully.');


        } 
   
        {

            return redirect()->back()->with('error','Please add item to purchase!');

        }

        


    }


    public function cancel($id)
    {

        $order = Order::find($id);

        $order->status = 'CANCELED';

         $order->save();

        
        return redirect()->route('order.index')

            ->with('success','Order has been canceled successfully.');

    }


    public function post($id)
    {

        $order = Order::find($id);

        $order->status = 'POSTED';

         $order->save();

        
        return redirect()->route('order.index')

            ->with('success','Order has been posted successfully.');

    }



    public function destroy($id)
    {

        $order = Order::find($id);

        $order_items = OrderItems::where('order_id',$id)->get();

          if(count($order_items) > 0)
            {
                foreach ($order_items as $key => $order_item) 
                {
                    $orderitems = OrderItems::findOrfail($order_item->id);

                    $orderitems->delete();
                }

            }

        $order->delete();

        
        return redirect()->route('order.index')

            ->with('success','Order has been canceled successfully.');

    }


    public function print($id)
    {

        $orders = Order::find($id);       
        
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
        $pdf::cell(185,1,"Purchse Order",0,"","C");

        $pdf::Ln(18);
        $pdf::SetFont('Arial','B',9);
        $pdf::SetXY($pdf::getX(), $pdf::getY());
        $pdf::cell(20,6,"PO Number",0,"","L");
        $pdf::SetFont('Arial','',9);
        $pdf::cell(40,6,': '.$orders->po_number,0,"","L");
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(100,6,"PO Date",0,"","R");
        $pdf::SetFont('Arial','',9);
        $po_date = Carbon::parse($orders->po_date);
        $pdf::cell(30,6,': '.$po_date->format('M d, Y'),0,"","L");
        

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
        $pdf::cell(40,6,': '.$orders->status,0,"","L");

        $pdf::Ln(6);
        $pdf::SetFont('Arial','B',9);
        $pdf::SetXY($pdf::getX(), $pdf::getY());
        $pdf::cell(20,6,"Remarks",0,"","L");
        $pdf::SetFont('Arial','',9);
        $pdf::cell(40,6,': '.$orders->remarks,0,"","L");


        //Column Name
            $pdf::Ln(15);
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(25,6,"Item No.",0,"","C");
            $pdf::cell(75,6,"Item Name",0,"","L");

            $pdf::cell(20,6,"Unit",0,"","C");
            $pdf::cell(20,6,"Quantity",0,"","C");
            $pdf::cell(20,6,"Price",0,"","C");
            $pdf::cell(20,6,"Amount",0,"","C");

         $pdf::Ln(1);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");

        $orde_items = $this->items->getForPO($id);;

        foreach ($orde_items as $key => $value) {

            $pdf::Ln(5);
            $pdf::SetFont('Arial','',9);
            $pdf::cell(25,6,$value->id,0,"","C");
            $pdf::cell(75,6,$value->name,0,"","L");
  
            $pdf::cell(20,6,$value->units,0,"","C");
            $pdf::cell(20,6,number_format($value->quantity,2),0,"","C");
            $pdf::cell(20,6,number_format($value->unit_cost,2),0,"","C");
            $pdf::cell(20,6,number_format($value->unit_total_cost,2),0,"","R");
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

       

        $preparedby = $this->user->getCreatedbyAttribute($orders->created_by);
       

        $approveddby = $this->user->getCreatedbyAttribute($orders->approved_by);
       

        $pdf::Ln(25);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(60,6,"      ".$preparedby."      ",0,"","C");
        $pdf::cell(60,6,"      ".""."      ",0,"","C");
        $pdf::cell(60,6,"      ".$approveddby."      ",0,"","C");

        $pdf::ln(0);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(60,6,"_________________________",0,"","C");
        $pdf::cell(60,6,"",0,"","C");
        $pdf::cell(60,6,"_________________________",0,"","C");


        $pdf::Ln(4);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(60,6,"Prepared by",0,"","C");
        $pdf::cell(60,6,"",0,"","C");
        $pdf::cell(60,6,"Approved by",0,"","C");


        $pdf::Ln();
        $pdf::Output();
        exit;

    }

}
