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
}
