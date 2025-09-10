<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Factories\Item\Factory as ItemFactory;
use App\Factories\SalesOrder\Factory as SalesOrderFactory;
use Illuminate\Support\Facades\Response;
use Yajra\Datatables\Datatables;
use App\Item;
use App\Inventory;
use App\Area;
use App\SalesOrder;
use App\SalesOrderItem;
use App\UnitOfMeasure; 
use App\Customer; 
use App\WarehouseLocation;
use App\User as Users;
use Carbon\Carbon;
use App\Factories\SalesOrder\PDF_MC_Table;
use App\MyPdf;
use Fpdf;
use DB;


class SalesController extends Controller
{

    public function __construct(
            Users $user,
            ItemFactory $items,
            SalesOrderFactory $salesorder
 
        )
    {
        $this->user = $user;
        $this->items = $items;
        $this->salesorders = $salesorder;
        $this->middleware('auth');

    }


    public function index()
    {   

        $salesorders         = $this->salesorders->getindex()->where('status','NEW')->sortByDesc('id');
        $posted_salesorder   = $this->salesorders->getindex()->where('status','POSTED')->sortByDesc('id');
        $cancel_salesorder   = $this->salesorders->getindex()->where('status','CANCELED')->sortByDesc('id');
        $closed_salesorder   = $this->salesorders->getindex()->where('status','CLOSED')->sortByDesc('id');
        
        

        return view('pages.salesorder.index',compact('salesorders','posted_salesorder','cancel_salesorder','closed_salesorder'));
    }


    public function create()
    {
   
        $items = $this->items->getindex();

        $creator = $this->user->getCreatedbyAttribute(auth()->user()->id);

        $employee_agent = $this->salesorders->employee_agent()->pluck('emp_name','id');

        $employee = $this->user->getemplist()->pluck('emp_name','id');

        $customer_id = Customer::pluck('name','id');

        $location  = WarehouseLocation::pluck('name','id');

        $salesorder_status = "NEW";

         $subAmount = 0;

          return view('pages.salesorder.create',compact('employee','employee_agent','creator','customer_id','items','salesorder_status','location','subAmount'));
    }


    public function store(Request $request)
    {

       $this->validate($request, [
            'so_date'       => 'required',
            'employee_id'   => 'required',
            'approved_by'   => 'required'
        ]);


        $unitCostTotal=0;

        $salesorder                     = New SalesOrder;

        $salesorder->so_number          = $this->salesorders->getSONo();

        $salesorder->so_date            = $request->so_date;

        $salesorder->remarks            = $request->remarks;

        $salesorder->customer_id        = $request->customer_id;

        $salesorder->employee_id        = $request->employee_id;

        $salesorder->sub_employee_id    = null;

        $salesorder->unit_cost_total    = $unitCostTotal;

        $salesorder->total_amount_discount     = $request->total_amount_discount;

        $salesorder->total_percent_discount     = $request->total_percent_discount;

        $salesorder->location           = $request->location;

        $salesorder->total_sales        = $request->total_sales;

        $salesorder->approved_by        = $request->approved_by;

        $salesorder->created_by         = auth()->user()->id;

        $salesorder->status             = 'NEW';

        $salesorder->save();

        $salesorder_id  = $salesorder->id;
        $salesorder_no  = $salesorder->so_number;
        $inven_Id       = $request->get('invenId');
        $setQty         = $request->get('setQty');
        $setPrice       = $request->get('setPrice');
        $setSRP         = $request->get('setSRP');
        $disAmount      = $request->get('dis_amount');
        $disPercent     = $request->get('dis_percent');
        $subAmount      = $request->get('gAmount');
        

        for ( $i=0 ; $i < count($inven_Id) ; $i++ ){

            $Inventory = Inventory::findOrfail($inven_Id[$i]);
            $Items  = Item::findOrfail($Inventory->item_id);

            $salesorder_items                       = New SalesOrderItem;

            $salesorder_items->sales_order_id       = $salesorder_id;

            $salesorder_items->so_number            = $salesorder_no;  

            $salesorder_items->inventory_id         = $inven_Id[$i];

            $salesorder_items->item_id              = $Inventory->item_id;

            $salesorder_items->order_quantity       = $setQty[$i];

            $salesorder_items->unit_cost            = $Items->unit_cost;

            $salesorder_items->srp                  = $setPrice[$i];

            $salesorder_items->set_srp              = $setSRP[$i];

            $salesorder_items->discount_amount      = $disAmount[$i];

            $salesorder_items->discount_percentage  = $disPercent[$i];

            $salesorder_items->sub_amount           = $subAmount[$i];

            $salesorder_items->save();



        }


        return redirect()->route('salesorder.index')

            ->with('success','Order has been saved successfully.');
        }
    

    public function getInventoryItems(Request $request)
    {
        
        $results = $this->salesorders->getInventoryItems($request->id);   

        return response()->json($results);     

    }

    // Get the Customer Price Setup

    public function getcustomeritems(Request $request)
    {
       $invenId = Inventory::findOrfail($request->id); 
       $areas = Customer::where('id',$request->cs)->select('area_id')->first();    

       $csPrice = $this->salesorders->getCSitems($request->cs)->where('item_id',$invenId->item_id)->first(); 
       $addPrice = $this->salesorders->getaddeditemprice($invenId->item_id, $areas->area_id)->first();
       $newSRP = 0;
       $noaddedPrice = 0;

       if ( !$csPrice  )  {

        $csPrice = $this->salesorders->getSetItems($invenId->item_id)->first();

            if ( !$addPrice ) {
                
                $newSRP = $csPrice->set_srp;

            } else {

                $newSRP = $addPrice->set_srp;
                $noaddedPrice = 1;

            }

       } 

        if ( !$addPrice ) {

                
                 $newSRP = $csPrice->set_srp;
            } else {

               $newSRP = $addPrice->set_srp;
                $noaddedPrice = 1;

        }
           



        return response()->json(['invenId' => $invenId, 'csPrice' => $csPrice, 'newSRP' => $newSRP, 
                                'noaddedPrice' => $noaddedPrice]);       
        
    }


    public function getForSOitems(Request $request)
    {

        $results = $this->salesorders->getForSOitems($request->id);

        return response()->json($results); 
      
    }

    
    public function edit($id)
    {

        $items = $this->items->getindex();

        $creator = $this->user->getCreatedbyAttribute(auth()->user()->id);

        $employee_agent = $this->salesorders->employee_agent()->pluck('emp_name','id');

        $employee = $this->user->getemplist()->pluck('emp_name','id');

        $customer_id = Customer::pluck('name','id');

        $location  = WarehouseLocation::pluck('name','id');

        $salesorder = SalesOrder::find($id);

        $salesorder_status = $salesorder->status;

        $subAmount = $this->salesorders->getActualAmount($id)->sum('subAmount');

        $subAmount = number_format($subAmount,2,'.','');

        $deductStatus = $salesorder->inventory_deducted;

        return view('pages.salesorder.edit',compact('salesorder','employee_agent','creator','employee','customer_id','items','salesorder_status','location','deductStatus','subAmount'));

    }


    public function update(Request $request,$id)
    {

        $this->validate($request, [
            'so_date'       => 'required',
            'employee_id'   => 'required',
            'approved_by'   => 'required'
        ]);



        $unitCostTotal=0;

        $salesorder                     = SalesOrder::findOrfail($id);

        $salesorder->so_number          = $request->so_number;

        $salesorder->so_date            = $request->so_date;

        $salesorder->remarks            = $request->remarks;

        $salesorder->customer_id        = $request->customer_id;

        $salesorder->employee_id        = $request->employee_id;

        $salesorder->sub_employee_id    = null;

        $salesorder->unit_cost_total    = $unitCostTotal;

        $salesorder->total_amount_discount     = $request->total_amount_discount;

        $salesorder->total_percent_discount     = $request->total_percent_discount;

        $salesorder->location           = $request->location;

        $salesorder->total_sales        = $request->total_sales;

        $salesorder->approved_by        = $request->approved_by;

        $salesorder->updated_by         = auth()->user()->id;

        $salesorder->status             = 'NEW';

        $salesorder->save();

        $salesorder_id  = $salesorder->id;
        $salesorder_no  = $salesorder->so_number;
        $inven_Id       = $request->get('invenId');
        $setQty         = $request->get('setQty');
        $setPrice       = $request->get('setPrice');
        $setSRP         = $request->get('setSRP');
        $disAmount      = $request->get('dis_amount');
        $disPercent     = $request->get('dis_percent');
        $subAmount      = $request->get('gAmount');
        
        if(count($request->get('invenId')) > 0);
        {

            $so_items = SalesOrderItem::where('sales_order_id',$id)->get();

             if(count($so_items) > 0)
             {
                foreach ($so_items as $key => $so_item) 
                {
                    $salesorderitems = SalesOrderItem::findOrfail($so_item->id);

                    $salesorderitems->delete();
                }

             }
        }


        for ( $i=0 ; $i < count($inven_Id) ; $i++ ){

            $Inventory = Inventory::findOrfail($inven_Id[$i]);
            $Items  = Item::findOrfail($Inventory->item_id);

            $salesorder_items                       = New SalesOrderItem;

            $salesorder_items->sales_order_id       = $salesorder_id;

            $salesorder_items->so_number            = $salesorder_no;  

            $salesorder_items->inventory_id         = $inven_Id[$i];

            $salesorder_items->item_id              = $Inventory->item_id;

            $salesorder_items->order_quantity       = $setQty[$i];

            $salesorder_items->unit_cost            = $Items->unit_cost;

            $salesorder_items->srp                  = $setPrice[$i];

            $salesorder_items->set_srp              = $setSRP[$i];

            $salesorder_items->discount_amount      = $disAmount[$i];

            $salesorder_items->discount_percentage  = $disPercent[$i];

            $salesorder_items->sub_amount           = $subAmount[$i];

            $salesorder_items->save();

        }


        return redirect()->route('salesorder.index')

            ->with('success','Sales Order has been updated successfully.');
    
    }


    public function cancel($id)
    {

        $salesorder = SalesOrder::findOrfail($id);

        $salesorder->status = 'CANCELED';

        $salesorder->save();

        
        return redirect()->route('salesorder.index')

            ->with('success','Order has been canceled successfully.');

    }


    public function post($id)
    {

        $salesorder = SalesOrder::findOrfail($id);

        $salesorder->status = 'POSTED';

        $salesorder->save();

        
        return redirect()->route('salesorder.index')

            ->with('success','Order has been posted successfully.');

    }



    public function deduct($id)
    {

        $so_items = SalesOrderItem::where('sales_order_id',$id)->get();

            if(count($so_items) > 0)
            {
                foreach ($so_items as $key => $so_item) 
                {                        
                    $salesorderitems = SalesOrderItem::findOrfail($so_item->id);

                    $items = Item::findOrfail($salesorderitems->item_id);

                        $inventory = Inventory::findOrfail($salesorderitems->inventory_id);

                        $resultQTY = $inventory->unit_quantity - $salesorderitems->order_quantity;

                        $inventory->unit_quantity = $resultQTY;

                        $inventory->onhand_quantity = $resultQTY;

                        $inventory->save();
                }
            }

        $salesorder = SalesOrder::findOrfail($id);

        $salesorder->inventory_deducted = 1;

        $salesorder->save();
        
        return redirect()->route('salesorder.index')

            ->with('success','Sales Order has been deducted to Invntory successfully.');

    }


    public function destroy($id)
    {

        $salesorder = SalesOrder::find($id);

        $salesorder_items = SalesOrderItem::where('sales_order_id',$id)->get();

          if(count($salesorder_items) > 0)
            {
                foreach ($salesorder_items as $key => $salesorder_item) 
                {
                    $salesorderitems = SalesOrderItem::findOrfail($salesorder_item->id);

                    $salesorderitems->delete();
                }

            }

        $salesorder->delete();

        
        return redirect()->route('salesorder.index')

            ->with('success','Order has been deleted successfully.');

    }

    

    public function printSO($id)
    {

      // Instantiate your custom PDF class
        $pdf = new MyPdf();
        $pdf::AliasNbPages();
        // Define an alias for the total number of pages
       

        // Add the first page (this automatically calls Header() and Footer())
        $pdf::AddPage('P','A4');
       
       
        // Set the font for the body content
        $sales_order_items = $this->salesorders->getForSOitems($id);
        $salesorders = SalesOrder::find($id);
        $nOitems = count($sales_order_items) - 1;
        $pglist = 38;
        if ($pglist > $nOitems){
            $pglist = $pglist - 3;
        }
        if ($pglist == $nOitems){
            $pglist = 38;
        }
        $var = $nOitems / $pglist;

        //Setup Page
        $ctr = 0;
        if (round($var) < $var)
            {
                $ctr = round($var) + 1;
            } else {
                $ctr =round($var);
            }  

        // Add some body content
        $order_number = 0;
        $total_discount_amount = 0;

        $sub = 0;
        $n = 0;
        
        for ($x = 1; $x <= $ctr; $x++){
            $pdf->header($id);
                for ($i = $n; $i <= $nOitems; $i++) {
                        
                        $pdf::Ln(5);
                        $pdf::SetFont('Arial','',11);
                        if(($salesorders->total_amount_discount == 0) && ($salesorders->total_percent_discount == 0)){
                            $pdf::cell(25,6,$sales_order_items[$i]->order_quantity,0,"","C");
                            $pdf::cell(15,6,$sales_order_items[$i]->unti_code,0,"","L");
                            $pdf::cell(85,6,$sales_order_items[$i]->description,0,"","L");
                            $pdf::cell(30,6,number_format($sales_order_items[$i]->srp,2),0,"","R");
                            $pdf::cell(30,6,number_format($sales_order_items[$i]->sub_amount,2),0,"","R");


                        }elseif(($salesorders->total_amount_discount > 0) && ($salesorders->total_percent_discount == 0)){
                            $pdf::cell(15,6,$sales_order_items[$i]->order_quantity,0,"","C");
                            $pdf::cell(15,6,$sales_order_items[$i]->unti_code,0,"","L");
                            $pdf::cell(70,6,$sales_order_items[$i]->description,0,"","L");
                            $pdf::cell(20,6,number_format($sales_order_items[$i]->srp,2),0,"","R");

                            $amntDisc = $sales_order_items[$i]->srp - $sales_order_items[$i]->set_srp;

                            $pdf::cell(20,6,number_format($amntDisc,2),0,"","C");
                            $pdf::cell(20,6,number_format($sales_order_items[$i]->set_srp,2),0,"","R");
                            $pdf::cell(25,6,number_format($sales_order_items[$i]->sub_amount,2),0,"","R");
                         

                        }elseif (($salesorders->total_amount_discount == 0) && ($salesorders->total_percent_discount > 0)){
                            $pdf::cell(15,6,$sales_order_items[$i]->order_quantity,0,"","C");
                            $pdf::cell(15,6,$sales_order_items[$i]->unti_code,0,"","L");
                            $pdf::cell(70,6,$sales_order_items[$i]->description,0,"","L");
                            $pdf::cell(20,6,number_format($sales_order_items[$i]->srp,2),0,"","R");

                            $PercDisc = $sales_order_items[$i]->srp - $sales_order_items[$i]->set_srp; 

                            $pdf::cell(20,6,number_format($PercDisc,2),0,"","C");
                            $pdf::cell(20,6,number_format($sales_order_items[$i]->set_srp,2),0,"","R");
                            $pdf::cell(25,6,number_format($sales_order_items[$i]->sub_amount,2),0,"","R");

                        }elseif (($salesorders->total_amount_discount > 0) && ($salesorders->total_percent_discount > 0)){
                            $pdf::cell(15,6,$sales_order_items[$i]->order_quantity,0,"","C");
                            $pdf::cell(15,6,$sales_order_items[$i]->unti_code,0,"","L");
                            $pdf::cell(60,6,$sales_order_items[$i]->description,0,"","L");
                            $pdf::cell(20,6,number_format($sales_order_items[$i]->srp,2),0,"","R");

                            $amntDisc = $sales_order_items[$i]->srp * ($sales_order_items[$i]->discount_percentage / 100);

                            $pdf::cell(15,6,number_format($sales_order_items[$i]->discount_amount,2),0,"","C");
                            $pdf::cell(15,6,number_format($amntDisc,2),0,"","C");
                            $pdf::cell(20,6,number_format($sales_order_items[$i]->set_srp,2),0,"","R");
                            $pdf::cell(25,6,number_format($sales_order_items[$i]->sub_amount,2),0,"","R");

                        }

                        $sub = $sub + $sales_order_items[$i]->sub_amount;




                    if ($i == $pglist){
                        
                        $n = $i + 1;   
                        $pglist = $pglist + 38;

                        $pdf::Ln(1);
                        $pdf::SetFont('Arial','',11);
                        $pdf::cell(30,6,"______________________________________________________________________________________",0,"","L");
                        $pdf::Ln(5);
                        $pdf::SetFont('Arial','B',11);
                        $pdf::cell(155,6,"Subamount :",0,"","R");
                        $pdf::SetFont('Arial','B',11);
                        $pdf::cell(30,6,number_format($sub,2),0,"","R");
                        $sub = 0 ;
                        $pdf::Ln(1);
                        $pdf::SetFont('Arial','',11);
                       $pdf::cell(30,6,"______________________________________________________________________________________",0,"","L");

                        goto targetLocation;

                    }



                    if($i == $nOitems){
                        $n = $n + $i;
                        $pdf::Ln(1);
                        $pdf::SetFont('Arial','',11);
                         $pdf::cell(30,6,"______________________________________________________________________________________",0,"","L"); 
                        $pdf::Ln(5);
                        $pdf::SetFont('Arial','B',11);
                        $pdf::cell(155,6,"Subamount :",0,"","R");
                        $pdf::SetFont('Arial','B',11);
                        $pdf::cell(30,6,number_format($sub,2),0,"","R");
                        $sub = 0 ;
                        $pdf::Ln(1);
                        $pdf::SetFont('Arial','',11);
                        $pdf::cell(30,6,"______________________________________________________________________________________",0,"","L");    
                               $pdf::Ln(5);
                            $pdf::SetFont('Arial','I',9);
                            $pdf::cell(185,6,"--Nothing Follows--",0,"","C");

                        $pdf::Ln(3);
                        $pdf::SetFont('Arial','',11);
                        $pdf::cell(30,6,"______________________________________________________________________________________",0,"","L");
                       
                        $subAmount = $this->salesorders->getActualAmount($salesorders->id)->sum('subAmount');

                            $subAmount = number_format($subAmount,2,'.','');

                            $total_discount_amount = $subAmount - $salesorders->total_sales; 

                        $pdf::Ln(5);
                        $pdf::SetFont('Arial','B',11);
                        $pdf::cell(155,6,"SubTotal :",0,"","R");
                        $pdf::SetFont('Arial','B',11);
                        $pdf::cell(30,6,number_format($subAmount,2),0,"","R");

                        $pdf::Ln(1);
                        $pdf::SetFont('Arial','',11);
                        $pdf::cell(30,6,"______________________________________________________________________________________",0,"","L");
                        if(($salesorders->total_amount_discount > 0) && ($salesorders->total_percent_discount == 0)){
                            $pdf::Ln(5);
                            $pdf::SetFont('Arial','B',11);
                            $pdf::cell(155,6,"Amount Discount :",0,"","R");
                            $pdf::SetFont('Arial','',11);
                            $pdf::cell(30,6,number_format($total_discount_amount,2),0,"","R");
                        }elseif (($salesorders->total_amount_discount == 0) && ($salesorders->total_percent_discount > 0)){
                            $pdf::Ln(5);
                            $pdf::SetFont('Arial','B',11);
                            $pdf::cell(155,6,"Amount Discount :",0,"","R");
                            $pdf::SetFont('Arial','',11);
                            $pdf::cell(30,6,number_format($total_discount_amount,2),0,"","R");
                        }elseif (($salesorders->total_amount_discount > 0) && ($salesorders->total_percent_discount > 0)){
                            $pdf::Ln(5);
                            $pdf::SetFont('Arial','B',11);
                            $pdf::cell(155,6,"Amount Discount :",0,"","R");
                            $pdf::SetFont('Arial','',11);
                            $pdf::cell(30,6,number_format($total_discount_amount,2),0,"","R");
                        }

                        $pdf::Ln(5);
                        $pdf::SetFont('Arial','B',11);
                        $pdf::cell(155,6,"Total Amount :",0,"","R");
                        $pdf::SetFont('Arial','B',11);
                        $pdf::cell(30,6,number_format($salesorders->total_sales,2),0,"","R");

                        goto targetLocation;
                    }

                    
                    
                }

            targetLocation:

            $pdf->footer();
        }
    


        $pdf::Output();
        exit;


    }

     public function printSOd($id)
    {
        $salesorders = SalesOrder::find($id);       
  
        $pdf = new MyPdf('P');
        $pdf::AliasNbPages();
        $pdf::AddPage('P','A4');
        $pdf->header($id);
        

        $sales_order_items = $this->salesorders->getForSOitems($id);
        $order_number = 0;
        $total_discount_amount = 0;

        $ctr  = 0;
        $bal = 3;
        $sub = 0;

        foreach ($sales_order_items as $key => $value) {
            $pdf::Ln(5);
            $pdf::SetFont('Arial','',9);
            $ctr =$ctr+1;   


            if(($salesorders->total_amount_discount == 0) && ($salesorders->total_percent_discount == 0)){
                $pdf::cell(25,6,$value->order_quantity,0,"","C");
                $pdf::cell(15,6,$value->unti_code,0,"","L");
                $pdf::cell(85,6,$value->description,0,"","L");
                $pdf::cell(30,6,number_format($value->srp,2),0,"","R");
                $pdf::cell(30,6,number_format($value->sub_amount,2),0,"","R");


            }elseif(($salesorders->total_amount_discount > 0) && ($salesorders->total_percent_discount == 0)){
                $pdf::cell(15,6,$value->order_quantity,0,"","C");
                $pdf::cell(15,6,$value->unti_code,0,"","L");
                $pdf::cell(70,6,$value->description,0,"","L");
                $pdf::cell(20,6,number_format($value->srp,2),0,"","R");

                    $amntDisc = $value->srp - $value->set_srp;

                $pdf::cell(20,6,number_format($amntDisc,2),0,"","C");
                $pdf::cell(20,6,number_format($value->set_srp,2),0,"","R");
                $pdf::cell(25,6,number_format($value->sub_amount,2),0,"","R");
             

            }elseif (($salesorders->total_amount_discount == 0) && ($salesorders->total_percent_discount > 0)){
                $pdf::cell(15,6,$value->order_quantity,0,"","C");
                $pdf::cell(15,6,$value->unti_code,0,"","L");
                $pdf::cell(70,6,$value->description,0,"","L");
                $pdf::cell(20,6,number_format($value->srp,2),0,"","R");

                   $PercDisc = $value->srp - $value->set_srp; 

                $pdf::cell(20,6,number_format($PercDisc,2),0,"","C");
                $pdf::cell(20,6,number_format($value->set_srp,2),0,"","R");
                $pdf::cell(25,6,number_format($value->sub_amount,2),0,"","R");

            }elseif (($salesorders->total_amount_discount > 0) && ($salesorders->total_percent_discount > 0)){
                $pdf::cell(15,6,$value->order_quantity,0,"","C");
                $pdf::cell(15,6,$value->unti_code,0,"","L");
                $pdf::cell(60,6,$value->description,0,"","L");
                                
                $pdf::cell(20,6,number_format($value->srp,2),0,"","R");

                     $amntDisc = $value->srp * ($value->discount_percentage / 100);

                $pdf::cell(15,6,number_format($value->discount_amount,2),0,"","C");
                $pdf::cell(15,6,number_format($amntDisc,2),0,"","C");
                $pdf::cell(20,6,number_format($value->set_srp,2),0,"","R");
                $pdf::cell(25,6,number_format($value->sub_amount,2),0,"","R");

            }
              $sub = $sub + $value->sub_amount;
              if ($ctr == $bal){
                $pdf::Ln(1);
                $pdf::SetFont('Arial','',9);
                $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");
                $bal = $bal + 3;
                $pdf::Ln(5);
                $pdf::SetFont('Arial','B',9);
                $pdf::cell(155,6,"Subamount :",0,"","R");
                $pdf::SetFont('Arial','B',9);
                $pdf::cell(30,6,number_format($sub,2),0,"","R");
                $sub = 0 ;
                $pdf::Ln(1);
                $pdf::SetFont('Arial','',9);
                $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");
              } else {
                if (count($sales_order_items) < $bal){

                    $pdf::Ln(1);
                    $pdf::SetFont('Arial','',9);
                    $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");
                    $bal = $bal + 3;
                    $pdf::Ln(5);
                    $pdf::SetFont('Arial','B',9);
                    $pdf::cell(155,6,"Subamount :",0,"","R");
                    $pdf::SetFont('Arial','B',9);
                    $pdf::cell(30,6,number_format($sub,2),0,"","R");
                    $sub = 0 ;
                    $pdf::Ln(1);
                    $pdf::SetFont('Arial','',9);
                    $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");
                    }
            }
        }

           

        $pdf::Ln(5);
            $pdf::SetFont('Arial','I',8);

            $pdf::cell(185,6,"--Nothing Follows--",0,"","C");

        $pdf::Ln(3);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");
        
            $subAmount = $this->salesorders->getActualAmount($salesorders->id)->sum('subAmount');

            $subAmount = number_format($subAmount,2,'.','');

            $total_discount_amount = $subAmount - $salesorders->total_sales; 

        $pdf::Ln(5);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(155,6,"Subamount :",0,"","R");
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(30,6,number_format($subAmount,2),0,"","R");

        $pdf::Ln(1);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");
            if(($salesorders->total_amount_discount > 0) && ($salesorders->total_percent_discount == 0)){
                $pdf::Ln(5);
                $pdf::SetFont('Arial','B',9);
                $pdf::cell(155,6,"Amount Discount :",0,"","R");
                $pdf::SetFont('Arial','',9);
                $pdf::cell(30,6,number_format($total_discount_amount,2),0,"","R");
            }elseif (($salesorders->total_amount_discount == 0) && ($salesorders->total_percent_discount > 0)){
                $pdf::Ln(5);
                $pdf::SetFont('Arial','B',9);
                $pdf::cell(155,6,"Amount Discount :",0,"","R");
                $pdf::SetFont('Arial','',9);
                $pdf::cell(30,6,number_format($total_discount_amount,2),0,"","R");
            }elseif (($salesorders->total_amount_discount > 0) && ($salesorders->total_percent_discount > 0)){
                $pdf::Ln(5);
                $pdf::SetFont('Arial','B',9);
                $pdf::cell(155,6,"Amount Discount :",0,"","R");
                $pdf::SetFont('Arial','',9);
                $pdf::cell(30,6,number_format($total_discount_amount,2),0,"","R");
            }

        $pdf::Ln(5);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(155,6,"Total Amount :",0,"","R");
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(30,6,number_format($salesorders->total_sales,2),0,"","R");

        $pdf->footer();

        $pdf::Ln();
        $pdf::Output();
        exit;
    }




    public function printDraft_test($id)
    {

        $salesorders = SalesOrder::find($id);         
        $customer = Customer::findOrfail($salesorders->customer_id);
        $area = Area::findOrfail($customer->area_id);


       $pdf = new Fpdf('P');

            $pdf->col = 0;
            $pdf::AcceptPageBreak(false);
            //My Loop Controls
            $column = 0; //Control which column to add data
            $columnLenght = 0; //monitor if we need to change the column
            $maxLinesPerColumn = 85;
            $wrapCharacters = 75; //do not exceed this char qty per line
            $collectedDescription = ""; //stack Description here

        //$pdf::AddPage('P','A4');
 
            //$pdf::AddPage(['L', 'Letter']);

            $pdf::SetFont('Arial', 'B');
            $pdf::SetFontSize(15);

            // $mid_x = 10; // the middle of the "PDF screen", fixed by now.        
            //$pdf::Text($mid_x - ($pdf::GetStringWidth('TITLE') / 2), 10, 'Sales Order');

            $pdf::AddPage(['L', 'A4']);
            $pdf::SetFontSize(9);
        
            $pdf::SetXY($pdf::getX(), $pdf::getY());
            $pdf::cell(10,1,$customer->name." | ".$area->name. " | ".$customer->address,0,"","L");
            $pdf::Ln(4);

            $pdf::SetXY($pdf::getX(), $pdf::getY());
            $pdf::cell(10,1,"Sales Order",0,"","L");
            $pdf::Ln(3);


        $salesorder_items = $this->salesorders->getForSOitems($id);

        //$salesorder_items =  item::orderBy('code', 'DESC')->take(300)->get()->random(150);

        $order_number=0;

        foreach ($salesorder_items as $key => $g) {
  
        $chunks = explode("\n", wordwrap(strip_tags($g->draftname), $wrapCharacters));
        $actionCollect = 1;
        $collectedDescription = "";

       $order_number = $order_number+1;

        for ($i = 0; $i < count($chunks); $i++) {
                if ($columnLenght > $maxLinesPerColumn && $column == 2) {
                    $column = 0;
                    $columnLenght = 1;

                    $pdf::AddPage(['L', 'A4']);
                    $pdf::Footer("d");
                    $pdf->col = $column;
                    $x = 10 + $column * 95;
                    $pdf::SetLeftMargin($x);
                    $pdf::SetX($x);
                    $pdf::SetY(8);
                    $actionCollect = 0;
                } else if ($columnLenght > $maxLinesPerColumn && $column < 2) {
                    $columnLenght = 1;
                    $column++;
                    $pdf->col = $column;
                    $x = 10 + $column * 95;
                    $pdf::SetLeftMargin($x);
                    $pdf::SetX($x);
                    $pdf::SetY(8);
                    $actionCollect = 0;
                } else {
                    $columnLenght ++;
                    $actionCollect = 1;
                }

                if ($i == 0) {
                    $columnLenght ++;
                    $pdf::SetFont('Arial', 'B');
                    $term = html_entity_decode($order_number);
                    //$pdf::MultiCell(90, 3, $term, 'R'); 
                    $pdf::Ln(1);
                }

                $collectedDescription.=strip_tags($chunks[$i]);

                if (count($chunks) - 1 && $i == 0) {

                    $pdf::SetFont('Arial');
                    $pdf::MultiCell(90, 3, $collectedDescription, 'R');
                    $actionCollect = 0;
                    $collectedDescription = "";
                } else {
                    $pdf::SetFont('Arial');
                    $pdf::MultiCell(90, 3, strip_tags($chunks[$i]), 'R');
                    $actionCollect = 0;
                    $collectedDescription = "";
                }
            }
        }
        $pdf::Ln(2);
        $pdf::SetFont('Arial','I',6);
        $pdf::cell(20,6,"--Nothing Follows--",0,"","C");

        $pdf::Ln(1);
        $pdf::Output();
        exit;
       
    }

    /*

    public function printDraft($id)
    {

        $salesorders = SalesOrder::find($id);       
        
        $pdf = new Fpdf('P');
        $pdf::AddPage('P','A4');
 
        $pdf::Ln(2);
        $pdf::SetFont('Arial','B',8);
        $pdf::SetXY($pdf::getX(), $pdf::getY());
        $pdf::cell(10,1,"Sales Order",0,"","L");

        $pdf::Ln(2);
        $pdf::SetFont('Arial','B',8);
        $pdf::SetXY($pdf::getX(), $pdf::getY());
        $pdf::cell(17,6,"SO Number",0,"","L");
        $pdf::SetFont('Arial','',8);
        $pdf::cell(40,6,': '.$salesorders->so_number,0,"","L");
        $pdf::Ln(3);
        $pdf::SetFont('Arial','B',8);
        $pdf::cell(17,6,"SO Date",0,"","L");
        $pdf::SetFont('Arial','',8);
        $so_date = Carbon::parse($salesorders->so_date);
        $pdf::cell(30,6,': '.$so_date->format('M d, Y'),0,"","L");

        //Column Name
            $pdf::Ln(3);
            $pdf::SetFont('Arial','B',8);
            $pdf::cell(5,6,"No.",0,"","L");
            $pdf::cell(60,6,"Description",0,"","L");

        $salesorder_items = $this->salesorders->getForSOitems($id);
        
        $order_number = 0;

            foreach ($salesorder_items as $key => $value) {

                $pdf::Ln(3);
                $pdf::SetFont('Arial','',8);
                $pdf::SetXY($pdf::getX(), $pdf::getY());
                $order_number = $order_number+1;
                if ($order_number <= 83){
                    $pdf::SetX(5);
                    $pdf::cell(5,6,$order_number ,0,"","L");
                    $pdf::cell(60,6,$value->draftname,0,"L",false);
                }
                if ($order_number >= 84 AND $order_number  <= 166){ 
 
                    $pdf::SetX(75);
                    $pdf::cell(5,6,$order_number ,0,"","L");
                    $pdf::cell(60,6,$value->draftname,0,"L",false);
                }
                if ($order_number >= 167 AND $order_number  <= 249){

                    $pdf::SetX(140);
                    $pdf::cell(5,6,$order_number ,0,"","L");
                    $pdf::cell (60,6,$value->draftname,0,"L",false);
                }
                
            }

        $pdf::Ln(3);
        $pdf::SetFont('Arial','I',6);
        $pdf::cell(20,6,"--Nothing Follows--",0,"","C");

        $pdf::Ln();
        $pdf::Output();
        exit;

    }

    
    public function getCreatePDF($flascard_id) {
    $flashcard = Flashcard::where('user_id', auth()->id())->findOrFail($flascard_id);

    $glossary = Glossary::with('flashcarditem')
            ->whereHas('flashcarditem', function ($query) use ($flascard_id) {
                $query->where('flashcard_id', '=', $flascard_id);
            })
            ->get();

    $pdf = app('FPDF');
    $pdf->col = 0;
    $pdf->AcceptPageBreak(false);
    //My Loop Controls
    $column = 0; //Control which column to add data
    $columnLenght = 0; //monitor if we need to change the column
    $maxLinesPerColumn = 51;
    $wrapCharacters = 75; //do not exceed this char qty per line
    $collectedDescription = ""; //stack Description here

    $pdf->AddPage(['L', 'Letter']);
    $pdf->SetFont('Arial', 'B');
    $pdf->SetFontSize(18);
    $mid_x = 135; // the middle of the "PDF screen", fixed by now.        
    $pdf->Text($mid_x - ($pdf->GetStringWidth('TITLE') / 2), 102, 'TITLE');
    $pdf->AddPage(['L', 'Letter']);
    $pdf->SetFontSize(7);

    foreach ($glossary as $key => $g) {
        $chunks = explode("\n", wordwrap(strip_tags($g->description), $wrapCharacters));
        $actionCollect = 1;
        $collectedDescription = "";


        for ($i = 0; $i < count($chunks); $i++) {
            if ($columnLenght > $maxLinesPerColumn && $column == 2) {
                $column = 0;
                $columnLenght = 1;

                $pdf->AddPage(['L', 'Letter']);
                $pdf->Footer("d");
                $pdf->col = $column;
                $x = 10 + $column * 95;
                $pdf->SetLeftMargin($x);
                $pdf->SetX($x);
                $pdf->SetY(8);
                $actionCollect = 0;
            } else if ($columnLenght > $maxLinesPerColumn && $column < 2) {
                $columnLenght = 1;
                $column++;
                $pdf->col = $column;
                $x = 10 + $column * 95;
                $pdf->SetLeftMargin($x);
                $pdf->SetX($x);
                $pdf->SetY(8);
                $actionCollect = 0;
            } else {
                $columnLenght ++;
                $actionCollect = 1;
            }

            if ($i == 0) {
                $columnLenght ++;
                $pdf->SetFont('Arial', 'B');
                $term = html_entity_decode($g->term);
                $pdf->MultiCell(90, 3, $term, 'R');
                $pdf->Ln(0);
            }

            $collectedDescription.=strip_tags($chunks[$i]);

            if (count($chunks) - 1 && $i == 0) {

                $pdf->SetFont('Arial');
                $pdf->MultiCell(90, 3, $collectedDescription, 'R');
                $actionCollect = 0;
                $collectedDescription = "";
            } else {
                $pdf->SetFont('Arial');
                $pdf->MultiCell(90, 3, strip_tags($chunks[$i]), 'R');
                $actionCollect = 0;
                $collectedDescription = "";
            }
        }

        $pdf->Ln(1);
    }
    }*/

}   