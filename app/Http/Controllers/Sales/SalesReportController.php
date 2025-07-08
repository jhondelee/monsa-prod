<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Factories\Item\Factory as ItemFactory;
use App\Factories\SalesOrder\Factory as SalesOrderFactory;
use Yajra\Datatables\Datatables;
use App\Item;
use App\Inventory;
use App\SalesOrder;
use App\SalesOrderItem;
use App\UnitOfMeasure; 
use App\Customer; 
use App\WarehouseLocation;
use App\User as Users;
use Carbon\Carbon;

class SalesReportController extends Controller
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


    
}
