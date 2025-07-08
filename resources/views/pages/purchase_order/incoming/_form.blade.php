{!! Form::token(); !!}
{!! csrf_field() ; !!} 
                                                                             

    
    
<div class="form-group">  
 

    <div class="hr-line-dashed"></div>

    <div class="form-group">
        <input type="hidden" id="order_id" name="order_id" value="{{$incomings->order_id}}" />

        <label class="col-sm-2 control-label">PO Number :</label>
       
        <div class="col-sm-3">

            <div class="col-md-7">
                <p class="form-control-static h5" id="po_number" name="po_number">{{$incomings->po_number}}</p>
                <input type="hidden" id="po_number_input" name="po_number_input" value="{{$incomings->po_number}}" />
            </div>


        </div>

        <label class="col-sm-2 control-label">PO Date :</label>
        <div class="col-sm-3">
            <div class="col-md-7">
                <p class="form-control-static h5" id="po_date" name="po_date">{{$po_details->po_date}}</p>
            </div>
        </div>


    </div>

    <div class="form-group">

        <label class="col-sm-2 control-label">Prepared by :</label>
        <div class="col-sm-3">
            <div class="col-md-7">
                <p class="form-control-static h5" id="prepared_by">{{$created_by}}</p>
            </div>
        </div>


        <label class="col-sm-2 control-label">Approved by :</label>
        <div class="col-sm-3">
            <div class="col-md-7">
                <p class="form-control-static h5" id="approved_by">{{$approved_by}}</p>
            </div>
        </div>

    </div>

 
    <div class="form-group">

        <label class="col-sm-2 control-label">Supplier :</label>
        <div class="col-sm-3">
            <div class="col-md-7">
                <p class="form-control-static h5" id="supplier">{{$supplier->name}}</p>
                 <input type="hidden" name="supplier_id" id="supplier_id" value="{{$supplier->id}}">
            </div>
        </div>

        <label class="col-sm-2 control-label">Warehouse <span class="text-danger">*</span></label>
        <div class="col-sm-3">
                 {!! Form::select ('location',$location, null,['placeholder' => 'Choose Location...','class'=>'chosen-select','required'=>true ,'id'=>'location'])!!}
        </div>
    </div>

    <div class="form-group">

          <label class="col-sm-2 control-label">DR Date <span class="text-danger">*</span></label>
        <div class="col-sm-3">
            <div class="input-group date">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                {!! Form::text('dr_date',null, ['class'=>'form-control','required'=>true , 'id'=>'dr_date']) !!}
            </div>
        </div>
      <label class="col-sm-2 control-label">DR Number <span class="text-danger">*</span></label>
        <div class="col-sm-3">
           {!! Form::text('dr_number',null, ['class'=>'form-control dr_number','required'=>true  ,'id'=>'dr_number']) !!}
        </div>

    </div>

     <div class="form-group">

   
        <label class="col-sm-2 control-label">Notes</label>
        <div class="col-sm-3">
             {!! Form::textarea('notes',null, array('class' => 'form-control', 'rows' => 3,'id'=>'notes')) !!}
        </div>

     
        <label class="col-sm-2 control-label">Received by <span class="text-danger">*</span></label>
        <div class="col-sm-3">
            {!! Form::select ('received_by',$received_by, null,['placeholder' => 'Select Employee...','class'=>'chosen-select','required'=>true,'id'=>'received_by'])!!}
        </div>

    </div>

    <div class="hr-line-dashed"></div>
    <div class="form-group">
        <div class="col-sm-3">
            <a class='btn btn-primary btn-xs btn-show-item' id="btn-show-item"><i class='fa fa-plus'></i> Item</a>
        </div>
    </div>
          
    <div class="table-responsive">
                                 
        <table class="table table-bordered" id="dTable-receive-item-table">                  

            <thead> 
                
                <tr>
                    
                    <th class="text-center">Item No.</th>
                    <th>Description</th>
                    <th>Unit</th>
                    <th class="text-center">Order Qty</th>
                    <th class="text-center">Rec'd Qty</th>
                    @if (!can('item.unit_cost'))
                    <th class="text-center">Unit Cost</th>
                    @endif 
                    <th class="text-center">Total Amout</th>

                </tr>

            </thead>

            <tbody>

                @foreach($incoming_items as $incoming_item)
                 <tr>
                    <td>
                        <input type='input' name='item_id[]' class='form-control input-sm text-center item_id' size='2' value="{{$incoming_item->id}}" readonly>
                    </td>
                    <td>{{$incoming_item->description}} @if($incoming_item->free == 1)<label class='label label-danger'>FREE</label> @endif</td>
                    <td>{{$incoming_item->units}}</td>

                    <td class='text-center'>
                         <input type='text' name='item_quantity[]' class='form-control input-sm text-center item_quantity' size='4'  value ="{{$incoming_item->quantity}}" id ='item_quantity' readonly='true'>
                     </td>
                    <td>
                        <input type='text' name='received_qty[]' class='form-control input-sm text-center _received_qty' size='4'  placeholder='0.00'  id ='_received_qty' value ="{{$incoming_item->received_quantity}}">
                    </td>
                    @if (!can('item.unit_cost'))
                    <td class='text-center'>
                         <input type='text' name='item_unit_cost[]' class='form-control input-sm text-right _item_unit_cost' size='4'  value ="{{$incoming_item->unit_cost}}" id ='_item_unit_cost'>
                     </td>
                    @endif 
                    </td>
                        <td class='text-center'>
                        <input type='text' name='total_amount[]' class='form-control input-sm text-right _total_amount' size='4' readonly='true'  placeholder='0.00' value ="{{$incoming_item->unit_total_cost}}"  id ='_total_amount'>
                     </td>
                </tr>         
                @endforeach
                                                                      
            </tbody>

        </table>
        
        <hr>
    </div>
      <div class="row">
            <div class="col-md-8 form-horizontal"></div>
                                
           <div class="col-md-4 form-horizontal">
                                   
                <div class="form-group">
                    <label class="col-md-6 control-label"> Amount Discount</label>
                        <div class="col-md-6">
                            {!! Form::text('discount_input',$incomings->discount, array('placeholder' => '0.00','class' => 'form-control text-right _discount_input','id'=>'_discount_input')) !!}
                        </div>
                </div>
                <div class="form-group">
                    <label class="col-md-6 control-label">Total Amount</label>
                        <div class="col-md-6">
                            {!! Form::text('grand_total_amount',$incomings->total_amount, array('placeholder' => '0.00','class' => 'form-control text-right _grand_total_amount','id'=>'_grand_total_amount', 'readonly' => 'true' )) !!}
                        </div>
                </div>
            </div>  
    </div> 
                               
    <div class="hr-line-dashed"></div>
    <div class="row">
        <div class="col-md-12 form-horizontal">
 
            <div class="ibox-tools pull-right">
                 
                <button type="button" class="btn btn-danger btn-close" id="btn-close"><i class="fa fa-reply">&nbsp;</i>Back</button>
                 
                @if ($incomings->status == 'RECEIVING')

                    {!! Form::submit(' Save Changes', ['class' => 'btn btn-primary']) !!}  
                
                @endif
               

             </div>
        </div>
    <div class="col-md-2 form-hori  zontal">

    </div>
    </div>

