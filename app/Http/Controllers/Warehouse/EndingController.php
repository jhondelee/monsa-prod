<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Factories\Item\Factory as ItemFactory;
use App\Factories\EndingInventory\Factory as EndingInventoryFactory;
use App\User as Users;
use App\Inventory;
use App\EndingInventory;
use App\EndingInventoryItem;
use App\WarehouseLocation;
use Carbon\Carbon;
use Fpdf;

class EndingController extends Controller
{
    public function __construct(
            Users $user,
            ItemFactory $items,
            EndingInventoryFactory $endinginventories
        )
    {
        $this->user = $user;
        $this->items = $items;
        $this->endinginventory = $endinginventories;
        $this->middleware('auth');  
    }

    public function index()
    {
        
        $endings = $this->endinginventory->getindex();
      

            return view('pages.warehouse.ending.index',compact('endings'));
               
    }

    public function create()
    {
        $items = $this->endinginventory->getinventory();

        $prepared_by = $this->user->getemplist()->pluck('emp_name','id');

        $location = WarehouseLocation::pluck('name','id');
      

            return view('pages.warehouse.ending.create',compact('prepared_by','items','location'));
               
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'ending_date' => 'required',
            'prepared_by'   => 'required'
        ]);
 
        $endinginventory = New EndingInventory;

        $endinginventory->ending_date = $request->ending_date;

        $endinginventory->prepared_by = $request->prepared_by;

        $endinginventory->status = "UNPOSTED";

        $endinginventory->save();

            $ending_inventory_id    = $endinginventory->id;

            $val_id                 = $request->get('id');

            $val_item_id            = $request->get('item_id');

            $val_item_unit_qty      = $request->get('item_unit_qty');

            $val_onhand_quantity    = $request->get('onhand_quantity');

        
            for ( $i=0 ; $i < count($val_id) ; $i++ ){

                $inventory = Inventory::findorfail($val_id[$i]);

                    $endingitems = New EndingInventoryItem;

                    $endingitems->ending_inventory_id   = $ending_inventory_id;

                    $endingitems->item_id               = $val_item_id[$i];

                    $endingitems->unit_quantity         = $val_item_unit_qty[$i];

                    $endingitems->onhand_quantity       = $val_onhand_quantity[$i];

                    $endingitems->unit_cost             = $inventory->unit_cost;

                    $endingitems->received_date         = $inventory->received_date;

                    $endingitems->location              = $inventory->location;

                    $endingitems->save();
            }    


        return redirect()->route('ending.index')

            ->with('success','Ending invetory item has been created successfully.');

    }

    
    public function edit($id)
    {
        $endinginventory = EndingInventory::findorfail($id);

        $items = $this->endinginventory->getEndingInventory($id);

        $prepared_by = $this->user->getCreatedbyAttribute($endinginventory->prepared_by);

            return view('pages.warehouse.ending.edit',compact('endinginventory','items','prepared_by'));
    }


    public function post($id)
    {
        $endinginventory = EndingInventory::findorfail($id);

        $endinginventory->status = 'POSTED';

        $endinginventory->save();


              return redirect()->route('ending.index')

            ->with('success','Ending invetory has been POSTED successfully.');
    }


    public function print($id) 
    {
        
        $endinginventory = EndingInventory::findorfail($id);
    
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
        $pdf::cell(190,1,"Ending Inventory Report",0,"","C");

        $pdf::Ln(4);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(95,6,"Ending Date:",0,"","R");
        $pdf::SetFont('Arial','B',9);
        $ending_date = Carbon::parse($endinginventory->ending_date);
        $pdf::cell(25,6,': '.$ending_date->format('M d, Y'),0,"","L");
    
        //Column Name
            $pdf::Ln(15);
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(25,6,"Item id.",0,"","C");
            $pdf::cell(65,6,"Item Name",0,"","L");

            $pdf::cell(20,6,"Unit",0,"","C");
            $pdf::cell(35,6,"On-Hand QTY",0,"","R");
            $pdf::cell(40,6,"Location",0,"","C");


         $pdf::Ln(1);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");
        
        $inventories = $this->endinginventory->getEndingInventory($id);

        foreach ($inventories as $key => $value) {

            $pdf::Ln(5);
            $pdf::SetFont('Arial','',9);
            $pdf::cell(25,6,$value->item_id,0,"","C");
            $pdf::cell(65,6,$value->name,0,"","L");

            $pdf::cell(20,6,$value->qty_units_code,0,"","C");
            $pdf::cell(35,6,number_format($value->onhand_quantity,2),0,"","R");
             $pdf::cell(40,6,$value->location,0,"","C");
        }

        $pdf::Ln(5);
            $pdf::SetFont('Arial','I',8);
            $pdf::cell(185,6,"--Nothing Follows--",0,"","C");

        $pdf::Ln(3);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");

        $count = count($inventories);

        $pdf::Ln(4);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(25,6,"Total Count:",0,"","L");
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(25,6,$count,0,"","L");

        $prepared_by = $this->user->getCreatedbyAttribute($endinginventory->prepared_by);
       
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
