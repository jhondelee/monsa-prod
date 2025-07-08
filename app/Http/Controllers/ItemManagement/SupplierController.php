<?php

namespace App\Http\Controllers\ItemManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Factories\Item\Factory as ItemFactory;
use App\Supplier;
use App\SupplierItems;
use App\User as Users;

class SupplierController extends Controller
{
     public function __construct(Users $user,ItemFactory $items)
    {
        $this->user = $user;
        $this->items = $items;
        $this->middleware('auth');
    }

     public function index()
    {
        $suppliers = Supplier::all();

        return view('pages.item_management.suppliers.index',compact('suppliers'));
    }




    public function create()
    {

        return view('pages.item_management.suppliers.create');
    }



    public function store(Request $request)
    {

        $this->validate($request, [
            'name'             => 'required',
            'address'          => 'required',
            'contact_person'   => 'required',
            'contact_number'   => 'required'
        ]);


        $employee = $this->user->getCreatedbyAttribute(auth()->user()->id);

        $suppliers = New Supplier;

        $suppliers->name = $request->name;

        $suppliers->address = $request->address;

        $suppliers->contact_person = $request->contact_person;

        $suppliers->contact_number = $request->contact_number;

        $suppliers->created_by = $employee;

        $suppliers->save();


        return redirect()->route('supplier.index')

            ->with('success','Supplier has been saved successfully.');

    }

    
    public function edit($id)
    {

        $supplier =  Supplier::find($id);
   
        return view('pages.item_management.suppliers.edit',compact('supplier'));

    }


    public function update(request $request,$id)
    {

        $this->validate($request, [
            'name'             => 'required',
            'address'          => 'required',
            'contact_person'   => 'required',
            'contact_number'   => 'required'
        ]);

        $employee = $this->user->getCreatedbyAttribute(auth()->user()->id);

        $suppliers = Supplier::findOrfail($id);

        $suppliers->name = $request->name;

        $suppliers->address = $request->address;

        $suppliers->contact_person = $request->contact_person;

        $suppliers->contact_number = $request->contact_number;

        $suppliers->created_by = $employee;

        $suppliers->save();


        return redirect()->route('supplier.index')

            ->with('success','Supplier has been updated successfully.');


    }

    
    public function destroy($id)
    {
        
        $suppliers = Supplier::findOrfail($id);
        
        $suppliers->delete();

        return redirect()->route('supplier.index')

            ->with('success','Supplier deleted successfully.');
    }


    public function items($id)
    {

        $suppliers = Supplier::findOrfail($id);
        
        $items = $this->items->getindex();
        
        return view('pages.item_management.suppliers.supplied_items',compact('suppliers','items'));
    }

    public function add_items($id)
    {

        $suppliers = Supplier::findOrfail($id);
        
        return view('pages.item_management.suppliers.supplied_items',compact('suppliers'));
    }

    
    public function supplied(Request $request)
    {
        
        $results = $this->items->getindex()->where('id', $request->id)->first();   

        return response()->json($results);       
        
    }


    public function storeitems(Request $request,$id)
    {

        if(count($request->get('id')) > 0)
        {

             $supplier_items = SupplierItems::where('supplier_id',$id)->get();

             if(count($supplier_items) > 0)
             {
                foreach ($supplier_items as $key => $supplier_item) 
                {
                    $supplier_item = SupplierItems::findOrfail($supplier_item->id);

                    $supplier_item->delete();
                }

             }

             $item_id = $request->get('id');

             for ( $i = 0 ; $i < count($item_id) ; $i++)
             {
                $items = $this->items->getindex()->where('id', $item_id[$i])->first(); 

                $supplieritem               = New SupplierItems;
                $supplieritem->supplier_id  = $id;
                $supplieritem->item_id      = $item_id[$i];
                $supplieritem->save();
             }

             return redirect()->route('supplier.index')

                        ->with('success','Items has beed saved successfully!');

        }
        
    }

    public function showitems(Request $request)
    {

        $results = $this->items->getsupplieritems($request->id);   

        return response()->json($results);       
        
    }
}
