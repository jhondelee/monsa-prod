<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Factories\Inventory\Factory as InventoryFactory;
use App\Factories\Item\Factory as ItemFactory;
use App\Factories\Incoming\Factory as IncomingFactory;
use App\Factories\StockTransfer\Factory as StockTransferFactory;
use App\User as Users;
use App\Item;
use App\Incoming;
use App\Inventory;
use App\InventoryMovement;
use App\WarehouseLocation;
use App\Supplier;
use App\SupplierItems; 
use App\ItemReturntoSupplier;
use Carbon\Carbon;
use Fpdf;
use DB;

class InventoryController extends Controller
{
    public function __construct(
            Users $user,
            ItemFactory $items,
            InventoryFactory $inventory,
            IncomingFactory $incomings,
            StockTransferFactory $stocktransfer
        )
    {
        $this->user = $user;
        $this->inventory = $inventory;
        $this->items = $items;
        $this->incomings = $incomings;
        $this->stocktransfer =  $stocktransfer;
        $this->middleware('auth');  
    }

    public function index()
    {
        $transferLists = $this->stocktransfer->getTransferList();

        $returnLists = $this->inventory->getreturnindex();
       
        $inventories = $this->inventory->getindex();

        $inventoryItem =  $this->inventory->addInventoryItem()->pluck('item_name','id');

        $location = WarehouseLocation::pluck('name','id');

        $suppliers = Supplier::pluck('name','id');

        $user_id = auth()->user()->id;

        $created_by = $this->user->getemplist()->where('id',$user_id)->pluck('emp_name','id');

        //update inventory status
        $invStocks = Inventory::all();

        foreach ($invStocks as $key => $stock) {
            
           $UpdateStatus = $this->inventory->getItemStockLevel($stock->item_id, $stock->unit_quantity);

           $this->inventory->InventoryStatusUpdate($stock->id, $UpdateStatus);
        }


        return view('pages.warehouse.inventory.index',compact('location','inventories','transferLists','inventoryItem','returnLists','created_by','user_id','suppliers'));
               
    }

    public function create()
    {
        
    }

    public function upload_image(Request $request)
    {
        
        if ( $request->hasFile('item_picture') )  {
            $item = Item::findorfail($request->id);
            $item_picture = time().'.'.$request->item_picture->getClientOriginalExtension();
            $request->item_picture->move(public_path('item_image'), $item_picture );    
            $item->picture = $item_picture;
            $item->save();

             return redirect()->route('inventory.show',['id'=>$request->id])
                             ->with('success','Image uploaded successfully!');
        }else{

             return redirect()->route('inventory.show',['id'=>$request->id])
                             ->with('warning','No image uploaded!');
        }

       

    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'item_name'     => 'required',
            'quantity'      => 'required',
            'location'      => 'required',
            'created_by'   => 'required'
        ]);


        $ifexistItem = Inventory::where('item_id', $request->item_name)->where('location', $request->location)->first();

        if(!$ifexistItem)

            {

                $inventory = New Inventory;
                $inventory->item_id           = $request->item_name;
                $inventory->unit_quantity     = $request->quantity;
                $inventory->onhand_quantity   = 0;
                $inventory->unit_cost         = $request->unit_cost;
                $inventory->location          = $request->location;
                $inventory->received_date     = $request->received_date;
                $inventory->expiration_date   = $request->expiry_date;
                $inventory->status            = 'In Stock';
                $inventory->consumable         = 0;
                $inventory->created_by        = $request->created_by;
                $inventory->save();

            } else {

                $inventory = Inventory::findorfail($ifexistItem->id);
           
                    $newQty = $inventory->unit_quantity + $request->quantity;

                $inventory->unit_quantity = $newQty;

                $inventory->unit_cost   = $request->unit_cost;

                $inventory->save();

            }
                            /*
                            $items = $this->items->getiteminfo($request->item_name)->first();

                            $item_unit_qty = $request->quantity;

                            $inventory = New Inventory;
                            $inventory->item_id           = $request->item_name;
                            $inventory->unit_quantity     = $request->quantity;
                            $inventory->onhand_quantity   = $item_unit_qty;
                            $inventory->unit_cost         = $request->unit_cost;
                            $inventory->location          = $request->location;
                            $inventory->received_date     = $request->received_date;
                            $inventory->expiration_date   = $request->expiry_date;
                            $inventory->status            = 'In Stock';
                            $inventory->consumable         = 0;
                            $inventory->created_by        = $request->created_by;
                            $inventory->save();
                            */

        return redirect()->route('inventory.index')

            ->with('success','Item has been successfully added.');

    }


    public function iteminfo(Request $request) 
    {

        $results  = $this->items->getiteminfo($request->id)->first();
        
        return response()->json($results);

    }


    public function show($id)
    {
        $showItems = $this->inventory->showItem($id)->first();

        $showItemsLocations = $this->inventory->showlocations($id);

        return view('pages.warehouse.inventory.item_details',compact('showItems','showItemsLocations'));
    }


    public function print(Request $request) 
    {
 
    
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
        $pdf::cell(185,1,"Inventory Report",0,"","C");

        $pdf::Ln(4);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(86,6,"As of",0,"","R");
        $pdf::SetFont('Arial','',9);
        $today_date = Carbon::now();
        $pdf::cell(25,6,': '.$today_date->format('M d, Y'),0,"","L");
    
        //Column Name
            $pdf::Ln(15);
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(25,6,"Item id.",0,"","C");
            $pdf::cell(55,6,"Item Name",0,"","L");

            $pdf::cell(20,6,"Unit",0,"","C");
            $pdf::cell(30,6,"On-Hand QTY",0,"","C");
            $pdf::cell(33,6,"Location",0,"","C");
            $pdf::cell(30,6,"Status",0,"","C");


         $pdf::Ln(1);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");
        
        $inventories = $this->inventory->getindex();

        foreach ($inventories as $key => $value) {

            $pdf::Ln(5);
            $pdf::SetFont('Arial','',9);
            $pdf::cell(25,6,$value->item_id,0,"","C");
            $pdf::cell(55,6,$value->name,0,"","L");

            $pdf::cell(20,6,$value->units,0,"","C");
            $pdf::cell(30,6,number_format($value->unit_quantity,2),0,"","C");
             $pdf::cell(33,6,$value->location,0,"","C");
            $pdf::cell(30,6,($value->status),0,"","C");
        }

        $pdf::Ln(5);
            $pdf::SetFont('Arial','I',8);
            $pdf::cell(185,6,"--Nothing Follows--",0,"","C");

        $pdf::Ln(3);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");

        $count = $this->inventory->getindex()->count();

        $pdf::Ln(4);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(25,6,"Total Count:",0,"","L");
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(25,6,$count,0,"","L");

        $prepared_by = $this->user->getCreatedbyAttribute(auth()->user()->id);
       
        $pdf::Ln(25);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(35,6,"Prepared by",0,"","C");
        $pdf::cell(60,6,"",0,"","C");

       $pdf::Ln(10);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(60,6,"      ".$prepared_by."      ",0,"","C");
        $pdf::cell(60,6,"      ".""."      ",0,"","C");


        $pdf::ln(0);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(60,6,"_________________________",0,"","C");
        $pdf::cell(60,6,"",0,"","C");




        $pdf::Ln();
        $pdf::Output();
        exit;


    }

    public function print_inventory(Request $request) 
    {

   
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
        $pdf::cell(185,1,"Inventory Report",0,"","C");

        $pdf::Ln(4);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(86,6,"As of",0,"","R");
        $pdf::SetFont('Arial','',9);
        $today_date = Carbon::now();
        $pdf::cell(25,6,': '.$today_date->format('M d, Y'),0,"","L");
    
        //Column Name
            $pdf::Ln(15);
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(25,6,"Item id.",0,"","C");
            $pdf::cell(55,6,"Item Name",0,"","L");

            $pdf::cell(20,6,"Unit",0,"","C");
            $pdf::cell(30,6,"On-Hand QTY",0,"","C");
            $pdf::cell(35,6,"Location",0,"","C");


         $pdf::Ln(1);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");
        
        $inventories = $this->inventory->getinventory();
        
        foreach ($inventories as $key => $value) {

            $pdf::Ln(5);
            $pdf::SetFont('Arial','',9);
            $pdf::cell(25,6,$value->item_id,0,"","C");
            $pdf::cell(55,6,$value->name,0,"","L");

            $pdf::cell(20,6,$value->units,0,"","C");
            $pdf::cell(30,6,number_format($value->onhand_quantity,2),0,"","C");
            $pdf::cell(30,6,($value->status),0,"","C");
        }

        $pdf::Ln(5);
            $pdf::SetFont('Arial','I',8);
            $pdf::cell(185,6,"--Nothing Follows--",0,"","C");

        $pdf::Ln(3);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");

        $count = $this->inventory->getinventory()->count();

        $pdf::Ln(4);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(25,6,"Total Count:",0,"","L");
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(25,6,$count,0,"","L");

        $prepared_by = $this->user->getCreatedbyAttribute(auth()->user()->id);
       
        $pdf::Ln(25);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(35,6,"Prepared by",0,"","C");
        $pdf::cell(60,6,"",0,"","C");

       $pdf::Ln(10);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(60,6,"      ".$prepared_by."      ",0,"","C");
        $pdf::cell(60,6,"      ".""."      ",0,"","C");


        $pdf::ln(0);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(60,6,"_________________________",0,"","C");
        $pdf::cell(60,6,"",0,"","C");




        $pdf::Ln();
        $pdf::Output();
        exit;

    }


    public function return_to_supplier(Request $request)
    {
        
        $itemReturn = New ItemReturntoSupplier;

        $itemReturn->item_id =          $request->item_rtn_id;

        $itemReturn->supplier_id =      $request->supplier_id;

        $itemReturn->inventory_id =      $request->inven_id;

        $itemReturn->return_unit_qty =  $request->unti_qty;

        $itemReturn->return_date =      $request->return_date;

        $itemReturn->location =         $request->location;

        $itemReturn->return_by =        $request->return_by;

        $itemReturn->save();
    

        $items =Item::findorfail($request->item_rtn_id);

        $inventories = Inventory::findorfail($request->inven_id);

        $inventories->unit_quantity =  $inventories->unit_quantity - $request->unti_qty;

        $inventories->onhand_quantity =  $inventories->unit_quantity - $request->unti_qty;

        $inventories->save();


        return redirect()->route('inventory.index')

            ->with('success','Item returned to the supplier has been saved!.');

    }


    public function item_return_to_supplier(Request $request)
    {
        $SupplierItems = SupplierItems::where('item_id',$request->id)->first();

        $results = Supplier::findorfail($SupplierItems->supplier_id);

        return response()->json($results);
    }

    public function return_to_inventory(Request $request)
    {
        $this->validate($request, [
            'unti_qty_i'      => 'required',
            'return_date' => 'required',
            'inv_loc'      => 'required',
            'return_by'   => 'required'
        ]);


        $items = $this->items->getiteminfo($request->item_id)->first();
        
         $ifexistItem = Inventory::where('item_id', $items->id)->where('location', $request->inv_loc)->first();

        if(!$ifexistItem)

            {


                $inventory = New Inventory;

                $inventory->item_id           = $request->item_id;
                $inventory->unit_quantity     = $request->unti_qty_i;
                $inventory->onhand_quantity   = 0;
                $inventory->unit_cost         = $items->unit_cost;
                $inventory->location          = $request->inv_loc;
                $inventory->received_date     = $request->return_date;
                $inventory->status            = 'In Stock';
                $inventory->consumable         = 0;
                $inventory->created_by        = $request->return_by;

                $inventory->save();

            } else {

                $inventory = Inventory::findorfail($ifexistItem->id);
                
                    $newQty = $inventory->unit_quantity + $request->unti_qty_i;

                $inventory->unit_quantity = $newQty;

                $inventory->unit_cost   = $request->unit_cost;

                $inventory->save();

            }
                   

        //Less into return

        $inventories = Inventory::findorfail($request->inven_item_id);
      
        $untiQTY = $inventories->unit_quantity - $request->unti_qty_i;

        $inventories->unit_quantity =  $untiQTY;

        $inventories->onhand_quantity = 0;

        $inventories->save();
   

        return redirect()->route('inventory.index')

            ->with('success','Item has been returned to the inventory successfully!.');
    }




}
