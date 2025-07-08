<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Factories\Inventory\Factory as InventoryFactory;
use App\Factories\Item\Factory as ItemFactory;
use App\Factories\Incoming\Factory as IncomingFactory;
use App\User as Users;
use App\Item;
use App\Incoming;
use App\Inventory;
use App\InventoryMovement;
use App\WarehouseLocation;
    use Carbon\Carbon;
use Fpdf;
use DB;

class InventoryController extends Controller
{
    public function __construct(
            Users $user,
            ItemFactory $items,
            InventoryFactory $inventory,
            IncomingFactory $incomings
        )
    {
        $this->user = $user;
        $this->inventory = $inventory;
        $this->items = $items;
        $this->incomings = $incomings;
        $this->middleware('auth');  
    }

    public function index()
    {
        $transferLists = InventoryMovement::all();
       
        $inventories = $this->inventory->getindex();

        $inventoryItem =  $this->inventory->addInventoryItem()->pluck('item_name','id');

        $location = WarehouseLocation::pluck('name','id');

        $id = auth()->user()->id;

        $created_by = $this->user->getemplist()->where('id',$id)->pluck('emp_name','id');

        return view('pages.warehouse.inventory.index',compact('location','inventories','transferLists','inventoryItem','created_by'));
               
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
            'received_date' => 'required',
            'location'      => 'required',
            'created_by'   => 'required'
        ]);

        $items = $this->items->getiteminfo($request->item_name)->first();

        $item_unit_qty = $items->unit_quantity * $request->quantity;

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
            $pdf::cell(30,6,number_format($value->onhand_quantity,2),0,"","C");
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


}
