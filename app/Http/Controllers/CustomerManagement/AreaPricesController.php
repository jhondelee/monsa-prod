<?php

namespace App\Http\Controllers\CustomerManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Factories\Customer\Factory as CustomerFactory;
use App\Factories\Item\Factory as ItemFactory;
use App\User as Users;
use App\Item;
use App\AreaPrices;
use App\Area;


class AreaPricesController extends Controller
{
    public function __construct(
            Users $user,
            CustomerFactory $customer,
            ItemFactory $items
        )
    {
        $this->user = $user;
        $this->customer = $customer;
        $this->items = $items;
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $areas = Area::all();

  
        return view('pages.customer_management.area_prices.index',compact('areas'));
    }


    public function getAreasItemSrp(Request $request)
    {
        $results = $this->customer->getAreasItemSrp($request->id);

            return response()->json($results);
    }


    public function getAddAllItems(Request $request)
    {
        $results =  $this->items->getitemList();

            return response()->json($results);
    }

    public function getSelectedItems(Request $request)
    {
            $results =  $this->items->getitemname($request->value);

            return response()->json($results);
    }

    public function getItemCost(Request $request)
    {
            $UnitCost =  $this->items->getiteminfo($request->id)->first();

                $areaPrices = New AreaPrices;

                $areaPrices->area_id                = $request->area_id;

                $areaPrices->item_id                = $request->id;

                $areaPrices->unit_cost              = $UnitCost->unit_cost;

                $areaPrices->save();

                $cspriceID = $areaPrices->id;
              
            return response()->json(['UnitCost' => $UnitCost, 'cspriceID' => $cspriceID]);

    }

     public function doDelete(Request $request)
    {

            $areaPrices = AreaPrices::findOrfail($request->id);

            $item_name = Item::findOrfail($areaPrices->item_id);

            $areaPrices->delete();

            $results = $item_name->description;

        return response()->json($results);
    }

    public function doUpdate(Request $request)
    {

                if (isset($request->chk_active)){
                    $activeDisc = 1;
                }else{
                    $activeDisc = 0;
                }

                
                $areaPrices = AreaPrices::findOrfail($request->id);

                $areaPrices->area_id                = $request->areaid;

                $areaPrices->item_id                = $request->item_id;

                $areaPrices->unit_cost              = $request->item_cost;

                $areaPrices->srp                    = $request->srp;

                $areaPrices->srp_added              = $request->srpD;

                $areaPrices->percentage_added       = $request->perD;              

                $areaPrices->activated_added        = $activeDisc;

                $areaPrices->set_srp                = $request->set_srp;

                $areaPrices->save();
                

                if(isset($request->set_srp)){
                    $results =$request->set_srp;
                }else{
                    $results =0.00;
                }

        return response()->json($results);
    }

     public function doDeactive(Request $request)
    {

            $areaPrices = AreaPrices::findOrfail($request->id);             

            $areaPrices->activated_added     = 0;

            $areaPrices->set_srp                = 0.00;

            $areaPrices->save();


                $results =0.00;
           

        return response()->json($results);
    }

    public function store(Request $request)
    {

    
    }

    public function edit($id)
    {
       
        $areas = Area::find($id);

        $items = $this->items->getitemList();

        $item_name = Item::pluck('name','name');

        return view('pages.customer_management.area_prices.edit',compact('areas','items','item_name'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
