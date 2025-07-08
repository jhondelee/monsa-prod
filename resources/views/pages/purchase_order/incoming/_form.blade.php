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
            </div>
        </div>

    </div>

    <div class="form-group">

          <label class="col-sm-2 control-label">DR Date <span class="text-danger">*</span></label>
        <div class="col-sm-3">
            <div class="input-group date">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                {!! Form::text('dr_date',null, ['class'=>'form-control', 'id'=>'dr_date']) !!}
            </div>
        </div>
      <label class="col-sm-2 control-label">DR Number <span class="text-danger">*</span></label>
        <div class="col-sm-3">
           {!! Form::text('dr_number',null, ['class'=>'form-control dr_number' ,'id'=>'dr_number']) !!}
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

        <label class="col-sm-2 control-label">Discount :</label>
        <div class="col-sm-3">
            <div class="col-md-7"><p class="form-control-static h4" id="discount">{{$incomings->discount}}</p></div>
            <input type="hidden" id="discount_input" name="discount_input" value="{{$incomings->discount}}" />
        </div>

        <label class="col-sm-2 control-label">Total Amount :</label>
        <div class="col-sm-3">
            <div class="col-md-7"><p class="form-control-static h4" id="total_amount">{{number_format($incomings->total_amount,2)}}</p></div>
            <input type="hidden" id="total_amount_input" name="total_amount_input" value="{{$incomings->total_amount}}"/>
        </div>

    </div>

    <div class="hr-line-dashed"></div>


                                
    <div class="table-responsive">
                                 
        <table class="table table-bordered" id="dTable-receive-item-table">                  

            <thead> 
                
                <tr>
                    
                    <th class="text-center">Item No.</th>
                    <th class="text-center">Item Code</th>
                    <th>Description</th>
                    <th>Unit</th>
                    <th>Unit Cost</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-center">Rec'd Qty</th>
                </tr>

            </thead>

            <tbody>

                @foreach($incoming_items as $incoming_item)
                 <tr>
                    <td>
                        <input type='input' name='item_id[]' class='form-control input-sm text-center item_id' size='2' value="{{$incoming_item->id}}" readonly>
                    </td>
                    <td>{{$incoming_item->name}}</td>
                    <td>{{$incoming_item->description}}</td>
                    <td>{{$incoming_item->units}}</td>
                     <td class='text-center'>
                         <input type='text' name='unit_cost[]' class='form-control input-sm text-center unit_cost' size='4'  value ="{{$incoming_item->unit_cost}}" id ='unit_cost'>
                     </td>
                    <td class='text-center'>
                         <input type='text' name='item_quantity[]' class='form-control input-sm text-center item_quantity' size='4'  value ="{{$incoming_item->quantity}}" id ='item_quantity' readonly='true'>
                     </td>
                    <td>
                        <input type='text' name='received_qty[]' class='form-control input-sm text-center received_qty' size='4'  placeholder='0.00'  id ='received_qty' value ="{{$incoming_item->received_quantity}}">
                    </td>
                </tr>         
                @endforeach
                                                                      
            </tbody>

        </table>
        
        <hr>
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

