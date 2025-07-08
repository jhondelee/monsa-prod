<?php

namespace App\Http\Controllers\Warehouse;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Factories\Inventory\Factory as InventoryFactory;
use App\Factories\Item\Factory as ItemFactory;
use App\Factories\Condemn\Factory as CondemnFactory;

use App\Inventory;
use App\User as Users;
use App\Item;
use App\Condemn;
use App\CondemnItems;
use App\WarehouseLocation;
use Carbon\Carbon;
use Fpdf;
use DB;

class CondemnController extends Controller
{
     public function __construct(
            Users $user,
            ItemFactory $items,
            InventoryFactory $inventory,
            CondemnFactory $condemn
        )
    {
        $this->user = $user;
        $this->inventory = $inventory;
        $this->items = $items;
        $this->condemn = $condemn;
        $this->middleware('auth');  
    }


    public function index()
    {
        $condemns  = $this->condemn->getindex();

        return view('pages.warehouse.condemn.index',compact('condemns'));
    }


    public function create()
    {
            $creator = $this->user->getCreatedbyAttribute(auth()->user()->id);

         $approver = $this->user->getemplist()->pluck('emp_name','id');

         $location = WarehouseLocation::pluck('name','id');

        return view('pages.warehouse.condemn.create',compact('creator','approver','location'));
    }


    public function inventorysourcedataTable(Request $request)
    {   
        $results = $this->condemn->AddfromInventoryItem($request->id);
        
        return response()->json($results);
    }


    public function consumablesourcedataTable(Request $request)
    {   
        $results = $this->condemn->AddfromConsumableItem($request->id);
        
        return response()->json($results);
    }


    public function returnsourcedataTable(Request $request)
    {   
        $results = $this->condemn->AddfromReturnItem($request->id);
        
        return response()->json($results);
    }

    public function condemitemdataTable(Request $request)
    {   
        $results = $this->condemn->getCondemnItesms($request->id);
        
        return response()->json($results);
    }




    public function store(Request $request)
    {
       
       $this->validate($request, [
            'reference_no'  => 'required',
            'condemn_date'  => 'required',
            'approved_by'   => 'required',
            'source'        => 'required'
        ]);

       $condemn = New Condemn;

       $condemn->reference_no   = $request->reference_no;

       $condemn->condemn_date   = $request->condemn_date;

       $condemn->location       = $request->source;

       $condemn->reason         = $request->reason;

       $condemn->status         = 0;

       $condemn->created_by     = auth()->user()->id;

       $condemn->approved_by    = $request->approved_by;

       $condemn->save();

            $item_id = $request->get('item_id');
            $itemQty = $request->get('qty_value');
            $source  = $request->get('get_source');

            for ( $i=0 ; $i < count($item_id) ; $i++ ){
           
                $GetInvenItem = Inventory::findorfail($item_id[$i]);

                $SetItems = Item::findorfail($GetInvenItem->item_id);

                    $condemnItems = New CondemnItems;

                    $condemnItems->condemn_id       = $condemn->id;

                    $condemnItems->inventory_id     = $item_id[$i];

                    $condemnItems->source           = $source[$i];

                    $condemnItems->item_id          = $SetItems->id;

                    $condemnItems->unit_quantity    = $itemQty[$i];

                    $condemnItems->unit_cost        = $SetItems->unit_cost;
                
                    $condemnItems->Save();

            }


        return redirect()->route('condemn.index')

            ->with('success','Condemn Item has been saved successfully.');

    }


    public function edit($id)
    {

        $condemn = Condemn::findorfail($id);

        $creator = $this->user->getCreatedbyAttribute($condemn->created_by);

        $approver = $this->user->getemplist()->pluck('emp_name','id');

        $location = WarehouseLocation::pluck('name','id');

        return view('pages.warehouse.condemn.edit',compact('condemn','approver','location','creator'));
    }

    public function update(Request $request, $id)
    {
       
       $this->validate($request, [
            'reference_no'  => 'required',
            'condemn_date'  => 'required',
            'approved_by'   => 'required',
            'source'        => 'required'
        ]);

       $condemn = Condemn::findorfail($id);

       $condemn->reference_no   = $request->reference_no;

       $condemn->condemn_date   = $request->condemn_date;

       $condemn->location       = $request->source;

       $condemn->reason         = $request->reason;

       $condemn->status         = 0;

       $condemn->created_by     = auth()->user()->id;

       $condemn->approved_by    = $request->approved_by;

       $condemn->save();

            $item_id = $request->get('item_id');
            $itemQty = $request->get('qty_value');
            $source  = $request->get('get_source');

            // Delete all condemnt Items
            $condemnItems = CondemnItems::where('condemn_id',$id)->get();

            if(count($condemnItems) > 0)
            {
                foreach ($condemnItems as $key => $condemnItem) 
                {
                    $items = CondemnItems::findOrfail($condemnItem->id);

                    $items->delete();
                }

            }


            // Add all udpated condemn Items
            for ( $i=0 ; $i < count($item_id) ; $i++ ){
           
                $GetInvenItem = Inventory::findorfail($item_id[$i]);

                $SetItems = Item::findorfail($GetInvenItem->item_id);

                    $condemnItems = New CondemnItems;

                    $condemnItems->condemn_id       = $condemn->id;

                    $condemnItems->inventory_id     = $item_id[$i];

                    $condemnItems->source           = $source[$i];

                    $condemnItems->item_id          = $SetItems->id;

                    $condemnItems->unit_quantity    = $itemQty[$i];

                    $condemnItems->unit_cost        = $SetItems->unit_cost;
                
                    $condemnItems->Save();

            }


        return redirect()->route('condemn.index')

            ->with('success','Condemn Item has been saved successfully.');

    }


    public function destroy($id)
    {
       
       $condemn = Condemn::find($id);

       $condemn->delete();


            // Delete all condemnt Items
            $condemnItems = CondemnItems::where('condemn_id',$id)->get();

            if(count($condemnItems) > 0)
            {
                foreach ($condemnItems as $key => $condemnItem) 
                {
                    $items = CondemnItems::find($condemnItem->id);

                    $items->delete();
                }

            }



        return redirect()->route('condemn.index')

            ->with('success','Condemn Item has been deleted successfully.');

    }

    public function post($id)
    {

        $conitem = CondemnItems::where('condemn_id',$id)->get();


            for ($i=0; $i < count($conitem); $i++) { 
                
                $inventoryItem = Inventory::findorfail($conitem[$i]->inventory_id);

                        $items = Item::findorfail($inventoryItem->item_id);

                        $itemUnitQty = ($items->unit_quantity * $conitem[$i]->unit_quantity);

                $inventoryItem->unit_quantity = $inventoryItem->unit_quantity - $conitem[$i]->unit_quantity;

                $inventoryItem->onhand_quantity = $inventoryItem->onhand_quantity - $itemUnitQty;

                 $inventoryItem->save();
            }

        $condemn = Condemn::findorfail($id);

        $condemn->status = 1;

        $condemn->save();


        return redirect()->route('condemn.index')

            ->with('success','Condemn Item has been deleted successfully.');
    }


    public function print($id)
    {
        $condemn = Condemn::find($id);       
        
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
        $pdf::cell(185,1,"Condemn Item",0,"","C");

        $pdf::Ln(18);
        $pdf::SetFont('Arial','B',9);
        $pdf::SetXY($pdf::getX(), $pdf::getY());
        $pdf::cell(25,6,"Reference No.",0,"","L");
        $pdf::SetFont('Arial','',9);
        $pdf::cell(40,6,': '.$condemn->reference_no,0,"","L");
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(100,6,"Condemn Date",0,"","R");
        $pdf::SetFont('Arial','',9);
        $po_date = Carbon::parse($condemn->condemn_date);
        $pdf::cell(30,6,': '.$po_date->format('M d, Y'),0,"","L");
        

        $pdf::Ln(6);
        $pdf::SetFont('Arial','B',9);
        $pdf::SetXY($pdf::getX(), $pdf::getY());
        $pdf::cell(20,6,"Warehouse Location",0,"","L");
        $pdf::SetFont('Arial','',9);
        $location = WarehouseLocation::find($condemn->location);
        $pdf::cell(35,6,': '.$location->name,0,"","R");

        $pdf::Ln(6);
        $pdf::SetFont('Arial','B',9);
        $pdf::SetXY($pdf::getX(), $pdf::getY());
        $pdf::cell(20,6,"Status",0,"","L");
        $pdf::SetFont('Arial','',9);
        $status = 'Pending';
        if ($condemn->status > 0){
            $status = 'Posted';
        }
        $pdf::cell(40,6,': '.$status,0,"","L");

        $pdf::Ln(6);
        $pdf::SetFont('Arial','B',9);
        $pdf::SetXY($pdf::getX(), $pdf::getY());
        $pdf::cell(20,6,"Reason",0,"","L");
        $pdf::SetFont('Arial','',9);
        $pdf::cell(40,6,': '.$condemn->reason,0,"","L");


        //Column Name
            $pdf::Ln(15);
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(10,6,"No.",0,"","L");
            $pdf::cell(40,6,"Source",0,"","L");
            $pdf::cell(60,6,"Item Name",0,"","L");
            $pdf::cell(25,6,"Unit",0,"","C");
            $pdf::cell(30,6,"Quantity",0,"","C");


         $pdf::Ln(1);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");

        $condemn_items = $this->condemn->getCondemnItesms($id);
        $item_no = 0;
        foreach ($condemn_items as $key => $value) {

            $pdf::Ln(5);
            $pdf::SetFont('Arial','',9);
            $pdf::cell(10,6,$item_no=$item_no+1,0,"","L");
            $pdf::cell(40,6,$value->source,0,"","L");
            $pdf::cell(60,6,$value->name,0,"","L");
            $pdf::cell(25,6,$value->units,0,"","C");
            $pdf::cell(30,6,number_format($value->unit_quantity,2),0,"","C");
        }

        $pdf::Ln(5);
            $pdf::SetFont('Arial','I',8);
            $pdf::cell(185,6,"--Nothing Follows--",0,"","C");

        $pdf::Ln(3);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");


        /*
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
        */
       

        $preparedby = $this->user->getCreatedbyAttribute($condemn->created_by);
       

        $approveddby = $this->user->getCreatedbyAttribute($condemn->approved_by);
       

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
