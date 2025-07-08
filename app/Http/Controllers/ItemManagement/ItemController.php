<?php

namespace App\Http\Controllers\ItemManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Factories\Item\Factory as ItemFactory;
use App\Item;
use App\UnitOfMeasure; 
use App\User as Users;


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

     public function index()
    {
        $items = $this->items->getindex();

        return view('pages.item_management.items.index',compact('items'));
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
            'unit_id'       => 'required',
            'unit_quantity' => 'required'
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

        $item->created_by = $employee;

        if ( $request->hasFile('item_picture') )  {
            $item_picture = time().'.'.$request->item_picture->getClientOriginalExtension();
            $request->item_picture->move(public_path('item_image'), $item_picture );    
            $item->picture = $item_picture;
        }

        $item->save();

          return redirect()->route('item.index')

            ->with('success','Item has been updated successfully.');


    }

    
    public function destroy($id)
    {
        
        $items = Item::findOrfail($id);
        
        $items->delete();

        return redirect()->route('item.index')

            ->with('success','Item has been deleted successfully.');
    }

}
