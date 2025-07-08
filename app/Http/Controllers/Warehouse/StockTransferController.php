<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Factories\StockTransfer\Factory as StockTransferFactory;
use App\Factories\Inventory\Factory as InventoryFactory;
use App\Factories\Item\Factory as ItemFactory;
use App\Factories\Incoming\Factory as IncomingFactory;
use App\User as Users;
use App\Item;
use App\Incoming;
use App\Inventory;
use App\InventoryMovement;
use App\InventoryMovementItems;
use App\WarehouseLocation;
    use Carbon\Carbon;
use Fpdf;
use DB;



class StockTransferController extends Controller
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

        $location = WarehouseLocation::pluck('name','id');

        return view('pages.warehouse.stock_transfer.add',compact('location'));
    }

    public function create()
    {   

        $location = WarehouseLocation::pluck('name','id');

        return view('pages.warehouse.stock_transfer.add',compact('location'));
    }

    public function sourcedataTable(Request $request)
    {   
        $results = $this->stocktransfer->AddTransferItem($request->id);
        
        return response()->json($results);
    }



    public function store(Request $request)
    {   
        $this->validate($request, [
            'reference_no'  => 'required',
            'source'        => 'required',
            'destination'   => 'required',
            'transfer_date' => 'required'
        ]);


        $inv_movement = New InventoryMovement;

        $inv_movement->reference_no     = $request->reference_no;

        $inv_movement->source           = $request->source;

        $inv_movement->destination      = $request->destination;

        $inv_movement->notes            = $request->notes;

        $inv_movement->transfer_date    = $request->transfer_date;

        $inv_movement->created_by       = $this->user->getCreatedbyAttribute(auth()->user()->id);;

        $inv_movement->status           = 'CREATED';

        $inv_movement->save();


            $inven_id = $request->get('item_id');

            $req_quantity = $request->get('qty_value');


                for ( $i=0 ; $i < count($inven_id) ; $i++ ){

                    $inv_items = Inventory::findorfail($inven_id[$i]);

                        $inv_movement_items = New InventoryMovementItems;

                        $inv_movement_items->inventory_movement_id      = $inv_movement->id;

                        $inv_movement_items->inventory_id               = $inven_id[$i];

                        $inv_movement_items->item_id                    = $inv_items->item_id;

                        $inv_movement_items->quantity                   = $req_quantity[$i];

                        $inv_movement_items->from_locaton               = $request->source;

                        $inv_movement_items->to_location                = $request->destination;

                        $inv_movement_items->posted                     = 0;

                        $inv_movement_items->save();
                }    

        return redirect()->route('inventory.index')

            ->with('success','Stock Transfer has been successfully added.');
    }

    public function edit($id)
    {
        $inventorymovements = InventoryMovement::findorfail($id);

        $movementItems = $this->stocktransfer->getforTransferitems($id);

        $location = WarehouseLocation::pluck('name','id');

        return view('pages.warehouse.stock_transfer.edit',compact('inventorymovements','movementItems','location'));
    }

    public function update(Request $request,$id) 
    {   
        $this->validate($request, [
            'reference_no'  => 'required',
            'source'        => 'required',
            'destination'   => 'required',
            'transfer_date' => 'required'
        ]);


        $inv_movement = InventoryMovement::findorfail($id);

        $inv_movement->reference_no     = $request->reference_no;

        $inv_movement->source           = $request->source;

        $inv_movement->destination      = $request->destination;

        $inv_movement->notes            = $request->notes;

        $inv_movement->transfer_date    = $request->transfer_date;

        $inv_movement->created_by       = $this->user->getCreatedbyAttribute(auth()->user()->id);;

        $inv_movement->status           = 'CREATED';

        $inv_movement->save();


            $Movementitems = InventoryMovementItems::where('inventory_movement_id',$id)->get();

            if(count($Movementitems) > 0)
            {
                foreach ($Movementitems as $key => $Movementitem) 
                {
                    $items = InventoryMovementItems::findOrfail($Movementitem->id);

                    $items->delete();
                }

            }


            $inven_id = $request->get('item_id');

            $req_quantity = $request->get('qty_value');


                for ( $i=0 ; $i < count($inven_id) ; $i++ ){

                    $inv_items = Inventory::findorfail($inven_id[$i]);

                        $inv_movement_items = New InventoryMovementItems;

                        $inv_movement_items->inventory_movement_id      = $inv_movement->id;

                        $inv_movement_items->inventory_id               = $inven_id[$i];

                        $inv_movement_items->item_id                    = $inv_items->item_id;

                        $inv_movement_items->quantity                   = $req_quantity[$i];

                        $inv_movement_items->from_locaton               = $request->source;

                        $inv_movement_items->to_location                = $request->destination;

                        $inv_movement_items->posted                     = 0;

                        $inv_movement_items->save();
                }    

        return redirect()->route('inventory.index')

            ->with('success','Inventory Movement has been successfully updated.');
    }


    public function destroy($id) 
    {   

        $inv_movement = InventoryMovement::findorfail($id);

        $inv_movement->delete();

            $Movementitems = InventoryMovementItems::where('inventory_movement_id',$id)->get();

            if(count($Movementitems) > 0)
            {
                foreach ($Movementitems as $key => $Movementitem) 
                {
                    $items = InventoryMovementItems::findOrfail($Movementitem->id);

                    $items->delete();
                }

            }


        return redirect()->route('inventory.index')

            ->with('success','Inventory Movement has been successfully deleted.');
    }



    public function posting($id)
    {

        $Movementitems = InventoryMovementItems::where('inventory_movement_id',$id)->get();

            if(count($Movementitems) > 0)
            {
                foreach ($Movementitems as $key => $Movementitem) 
                {
                    $ItemMoves = InventoryMovementItems::findOrfail($Movementitem->id);

                    $itemsMngt = Item::findorfail($ItemMoves->item_id);

                        $itemUnitQty = ($itemsMngt->unit_quantity) * ($ItemMoves->quantity);

                        // Deduct to Inventory
                        $getItemInven = Inventory::findorfail($ItemMoves->inventory_id);

                        $getItemInven->unit_quantity    = $getItemInven->unit_quantity - $ItemMoves->quantity;

                        $getItemInven->onhand_quantity  = $getItemInven->onhand_quantity - $itemUnitQty;

                        $getItemInven->status           = 'In Stock';

                        $getItemInven->save();

                        // Add to other Warehouse
                        $addInvenItem = New Inventory;

                        $addInvenItem->item_id            = $ItemMoves->item_id;

                        $addInvenItem->unit_quantity      = $ItemMoves->quantity;

                        $addInvenItem->onhand_quantity    = $itemUnitQty;

                        $addInvenItem->unit_cost          = $getItemInven->unit_cost;

                        $addInvenItem->expiration_date    = $getItemInven->expiration_date;

                        $addInvenItem->received_date      = $getItemInven->received_date;

                        $addInvenItem->location           = $ItemMoves->to_location;

                        $addInvenItem->consumable         = 0;

                        $addInvenItem->created_by         = $getItemInven->created_by;

                        $addInvenItem->save();

                    $ItemMoves->posted = 1;

                    $ItemMoves->save();

                }

            }

        // Posting of record
        $inv_movement = InventoryMovement::findorfail($id);

        $inv_movement->status ='POSTED';

        $inv_movement->save();


        return redirect()->route('inventory.index')

            ->with('success','Inventory Movement has been successfully posted and transferred!');

    }



     public function print($id) 
    {
 
        $inv_movement = InventoryMovement::findorfail($id);

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
        $pdf::cell(185,1,"Stock Transfer Report",0,"","C");

        $pdf::Ln(6);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(160,6,"Reference No.:",0,"","R");
        $pdf::SetFont('Arial','',9);
        $today_date = Carbon::parse();
        $pdf::cell(25,6,$inv_movement->reference_no,0,"","R");

        $pdf::Ln(4);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(160,6,"Transfer Date:",0,"","R");
        $pdf::SetFont('Arial','',9);
        $today_date = Carbon::parse($inv_movement->transfer_date);
        $pdf::cell(25,6,''.$today_date->format('M d, Y'),0,"","R");
    
        //Column Name
            $pdf::Ln(10);
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(25,6,"No.",0,"","C");
            $pdf::cell(25,6,"Source",0,"","C");
            $pdf::cell(25,6,"Destination",0,"","L");
            $pdf::cell(55,6,"Item Name",0,"","L");
            $pdf::cell(20,6,"Unit",0,"","C");
            $pdf::cell(30,6,"Request Qty",0,"","C");
             


         $pdf::Ln(1);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");
        
        $transItems = $this->stocktransfer->getforTransferitems($id);
        
        $ctr_item = 0;

        foreach ($transItems as $key => $value) {

            $pdf::Ln(5);
            $pdf::SetFont('Arial','',9);
            $pdf::cell(25,6,$ctr_item = $ctr_item + 1,0,"","C");
            $pdf::cell(25,6,$value->from_location,0,"","L");
            $pdf::cell(25,6,$value->to_location,0,"","L");
            $pdf::cell(55,6,$value->description,0,"","L");
            $pdf::cell(20,6,$value->units,0,"","C");
            $pdf::cell(30,6,number_format($value->quantity,2),0,"","C");

        }

        $pdf::Ln(5);
            $pdf::SetFont('Arial','I',8);
            $pdf::cell(185,6,"--Nothing Follows--",0,"","C");

        $pdf::Ln(3);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");

        $count = $this->stocktransfer->getforTransferitems($id)->count();

        $pdf::Ln(4);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(25,6,"Total Count:",0,"","L");
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(25,6,$count,0,"","L");

        $prepared_by = $this->user->getCreatedbyAttribute(auth()->user()->id);
        
        $pdf::Ln(8);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(35,6,"Notes: ",0,"","C");
        $pdf::SetFont('Arial','',9);
        $pdf::cell(23,6,$inv_movement->notes,0,"","L");

        $pdf::SetFont('Arial','',9);
        $pdf::cell(155,6,"Prepared by:  ",0,"","C");
        $pdf::cell(25,6,"",0,"","C");

        $pdf::Ln(5);
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
