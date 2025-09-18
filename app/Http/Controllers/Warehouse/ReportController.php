<?php

namespace App\Http\Controllers\Warehouse;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Factories\Inventory\Factory as InventoryFactory;
use App\Factories\Item\Factory as ItemFactory;
use App\Factories\Incoming\Factory as IncomingFactory;
use App\Factories\StockTransfer\Factory as StockTransferFactory;
use App\Factories\SalesOrder\Factory as SalesOrderFactory;
use App\User as Users;
use App\Item;
use App\Incoming;
use App\Inventory;
use App\InventoryMovement;
use App\WarehouseLocation;
use App\Supplier;
use App\SupplierItems; 
use App\ItemReturntoSupplier;
use App\ModeOfPayment;
use App\Customer; 
use App\Area; 
use Carbon\Carbon;
use Fpdf;
use DB;


class ReportController extends Controller
{
    public function __construct(
            Users $user,
            ItemFactory $items,
            InventoryFactory $inventory,
            IncomingFactory $incomings,
            StockTransferFactory $stocktransfer,
            SalesOrderFactory $salesorder
        )
    {
        $this->user = $user;
        $this->inventory = $inventory;
        $this->items = $items;
        $this->incomings = $incomings;
        $this->stocktransfer =  $stocktransfer;
        $this->salesorders = $salesorder;
        $this->middleware('auth');  
    }

    public function index()
    {   

        $salesorders = $this->salesorders->getindex()->where('status','NEW')->sortByDesc('id');   

        $paymode = ModeOfPayment::pluck('name','id');

        $customers = Customer::pluck('name','id');

        $areas = Area::pluck('name','id');
        
        return view('pages.warehouse.reports.index',compact('salesorders','paymode','customers','areas'));

    }

}
