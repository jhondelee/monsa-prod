
{!! Form::open(array('route' => array('inventory.store'),'class'=>'form-horizontal','role'=>'form')) !!} 

 <div id="myModal" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h3 class="modal-title"></h3>
            </div>
            <div class="modal-body">
           
                {!! Form::token(); !!}
                {!! csrf_field() ; !!} 

              

                <div class="form-group">
                    <label class="col-sm-3 control-label">Item <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            {!! Form::select ('item_name',$inventoryItem, null,['placeholder' => 'Select Item...','class'=>'chosen-select','required'=>true, 'id'=>'inventory_item_id'])!!}
                        </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-3 control-label">Quantity <span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                        {!! Form::text('quantity',null, ['class'=>'form-control text-center quantity','placeholder'=>'0.00', 'required'=>true ,'id'=>'quantity']) !!}
                    </div>
                </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Expiry Date</label>
                <div  class="col-sm-4 ">
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        {!! Form::text('expiry_date',null, ['class'=>'form-control', 'required'=>false]) !!}
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Date Received <span class="text-danger">*</span></label>
                <div  class="col-sm-4 ">
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        {!! Form::text('received_date',null, ['class'=>'form-control', 'required'=>true]) !!}
                    </div>
                </div>
            </div>


                <div class="form-group">    
                    <div class="col-sm-4">
                         <input type="hidden" id="unit_price" name="unit_cost" value="0.00" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Location <span class="text-danger">*</span></label>
                        <div class="col-sm-5">
                        
                        {!! Form::select ('location',$location, null,['placeholder' => 'Choose Location...','class'=>'chosen-select','required'=>true,'id'=>'location'])!!}

                        </div>
                </div>

            
                <div class="form-group">
                    <label class="col-sm-3 control-label">Added by:</label>
                    <div class="col-sm-5">

                        {!! Form::select ('created_by',$created_by, null,['placeholder' => 'Choose Employee...','class'=>'chosen-select','required'=>true,'id'=>'created_by'])!!}

                    </div>
                </div>

    
            </div>
                <div class="modal-footer">
                    {!! Form::submit('Save Changes', ['class' => 'btn btn-primary btn-save']) !!}
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>                                 
                </div>
            
        </div>
     </div>
 </div>

{!! Form::close() !!} 
