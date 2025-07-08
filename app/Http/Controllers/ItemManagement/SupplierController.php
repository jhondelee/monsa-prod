<?php

namespace App\Http\Controllers\ItemManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Factories\Item\Factory as ItemFactory;
use App\Supplier;
use App\SupplierItems;
use App\User as Users;
use Fpdf;


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



    public function print($id)
    {

        $suppliers = Supplier::find($id);       
        
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
        $pdf::cell(185,1,"Supplier Items",0,"","C");

        $pdf::Ln(18);
        $pdf::SetFont('Arial','B',9);
        $pdf::SetXY($pdf::getX(), $pdf::getY());
        $pdf::cell(30,6,"Supplier Name:",0,"","L");
        $pdf::SetFont('Arial','',9);
        $pdf::cell(50,6,': '.$suppliers->name,0,"","L");
        $pdf::SetFont('Arial','B',9);
        $pdf::Ln(6);
        $pdf::cell(30,6,"Address:",0,"","L");
        $pdf::SetFont('Arial','',9);
        $pdf::cell(50,6,': '.$suppliers->address,0,"","L");
        

        $pdf::Ln(6);
        $pdf::SetFont('Arial','B',9);
        $pdf::SetXY($pdf::getX(), $pdf::getY());
        $pdf::cell(30,6,"Contact Person:",0,"","L");
        $pdf::SetFont('Arial','',9);
        $pdf::cell(40,6,': '.$suppliers->contact_person,0,"","L");

        $pdf::Ln(6);
        $pdf::SetFont('Arial','B',9);
        $pdf::SetXY($pdf::getX(), $pdf::getY());
        $pdf::cell(30,6,"Contact Number:",0,"","L");
        $pdf::SetFont('Arial','',9);
        $pdf::cell(40,6,': '.$suppliers->contact_number,0,"","L");

        $pdf::Ln(8);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");

        //Column Name
            $pdf::Ln(5);
            $pdf::SetFont('Arial','B',9);
            $pdf::cell(30,6,"Item No.",0,"","C");
            $pdf::cell(50,6,"Item Name",0,"","L");
            $pdf::cell(75,6,"Description",0,"","L");
            $pdf::cell(20,6,"Unit",0,"","C");
 
        $pdf::Ln(1);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");

        $supplier_items = $this->items->showSupplierItems($id);;

        foreach ($supplier_items as $key => $value) {

            $pdf::Ln(5);
            $pdf::SetFont('Arial','',9);
            $pdf::cell(30,6,$value->id,0,"","C");
            $pdf::cell(50,6,$value->item_name,0,"","L");
            $pdf::cell(75,6,$value->description,0,"","L");
            $pdf::cell(20,6,$value->units,0,"","C");
        }

        $pdf::Ln(5);
            $pdf::SetFont('Arial','I',8);
            $pdf::cell(185,6,"--Nothing Follows--",0,"","C");

        $pdf::Ln(3);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(30,6,"_________________________________________________________________________________________________________",0,"","L");

        $pdf::Ln(5);
        $pdf::SetFont('Arial','B',10);
        $pdf::cell(35,6,"TOTAL COUNT:",0,"","L");
        $pdf::SetFont('Arial','B',10);
        $pdf::cell(5,6,$this->items->showSupplierItems($id)->count(),0,"","R");

        $preparedby = $this->user->getCreatedbyAttribute(auth()->user()->id);
             
        $pdf::Ln(10);
        $pdf::SetFont('Arial','B',9);
        $pdf::cell(260,6,"      ".$preparedby."      ",0,"","C");

        $pdf::ln(0);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(260,6,"_________________________",0,"","C");

        $pdf::Ln(4);
        $pdf::SetFont('Arial','',9);
        $pdf::cell(260,6,"Prepared by",0,"","C");    

        $pdf::Ln();
        $pdf::Output();
        exit;

    }
}
