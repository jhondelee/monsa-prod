<?php

namespace App\Http\Controllers\ItemManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Factories\Item\Factory as ItemFactory;
use Yajra\Datatables\Datatables;
use App\Item;
use App\UnitOfMeasure; 
use App\User as Users;
use Fpdf;


class ItemController extends Controller
{
     public function __construct(
            Users $user,
            ItemFactory $items
        )
    {
        $this->user = $user;
        $this->items = $items;
        $this->middleware('auth');
    }

     public function index(Request $request)
    {
        $item_name = Item::pluck('name','name');

        $val = $request->val;

        return view('pages.item_management.items.index',compact('item_name','val'));
       
    }


    public function create()
    {
        
        $units = UnitOfMeasure::pluck('name','id');

        return view('pages.item_management.items.create',compact('units'));
    }



    public function store(Request $request)
    {

        $this->validate($request, [
            'name'          => 'required',
            'description'   => 'required',
            'unit_id'       => 'required'
        ]);


        $employee = $this->user->getCreatedbyAttribute(auth()->user()->id);

        $item = New Item;

        $item->code =  $this->items->getItemNo();;

        $item->name = $request->name;

        $item->description = $request->description;

        $item->unit_id = $request->unit_id;

        $item->unit_quantity = $request->unit_quantity;

        $item->safety_stock_level = $request->safety_stock_level;

        $item->criticl_stock_level = $request->criticl_stock_level;

        $item->srp = $request->srp;

        $item->unit_cost = $request->unit_cost;

        $item->free = $request->free;

        $item->activated = $request->activated;

        $item->created_by = $employee;

     
        if ( $request->hasFile('item_picture') )  {
            $item_picture = time().'.'.$request->item_picture->getClientOriginalExtension();
            $request->item_picture->move(public_path('item_image'), $item_picture );    
            $item->picture = $item_picture;
        }



        $item->save();

      

        return redirect()->route('item.index')

            ->with('success','Item has been saved successfully.');

    }

    
    public function edit($id)
    {
        
        $item = Item::findorfail($id);

        $units = UnitOfMeasure::pluck('name','id');
    
        return view('pages.item_management.items.edit',compact('item','units'));

    }


    public function update(request $request,$id)
    {
        
        $this->validate($request, [
            'code'          => 'required',
            'name'          => 'required',
            'description'   => 'required',
            'unit_id'       => 'required'
        ]);


        $employee = $this->user->getCreatedbyAttribute(auth()->user()->id);

        $item = Item::findorfail($id);

        $item->code = $request->code;

        $item->name = $request->name;

        $item->description = $request->description;

        $item->unit_id = $request->unit_id;

        $item->unit_quantity = $request->unit_quantity;

        $item->safety_stock_level = $request->safety_stock_level;

        $item->criticl_stock_level = $request->criticl_stock_level;

        $item->srp = $request->srp;

        $item->unit_cost = $request->unit_cost;

        $item->free = $request->free;

        $item->activated = $request->activated;

        $item->created_by = $employee;

        if ( $request->hasFile('item_picture') )  {
            $item_picture = time().'.'.$request->item_picture->getClientOriginalExtension();
            $request->item_picture->move(public_path('item_image'), $item_picture );    
            $item->picture = $item_picture;
        }

        $item->save();

        $val = $request->name;

        return redirect()->route('item.index',['val'=>$val])

            ->with('success','Item has been updated successfully.');


    }

    
    public function destroy($id)
    {
        
        $items = Item::findOrfail($id);
        
        $items->delete();

        return redirect()->route('item.index')

            ->with('success','Item has been deleted successfully.');
    }


    public function update_price(request $request)
    {
        $items = Item::findOrfail($request->id);
    
        $items->srp = $request->unit_srp;

        $items->unit_cost = $request->unit_cost;

        $items->save();

        $val = $items->name;

        return redirect()->route('item.index',['val'=>$val])

            ->with('success','Item SRP and Unit Cost has been update successfully.');
    }



    public function datatable(Request $request)
    {

        if (!$request->value==true){
           $results = $this->items->getitemList();
        }else{
            $results = $this->items->getitemname($request->value);
            
        }
        
        return response()->json($results);

    }

    public function getname(Request $request)
    {

        if (!$request->value==true){
           $results = $this->items->getitemList();
        }else{
           $results = $this->items->getitemname($request->value);
            
        }

        return response()->json($results);
    }

}
