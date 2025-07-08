    
{!! Form::open(array('route' => array('inventory.return_to_inventory'),'class'=>'form-horizontal','role'=>'form')) !!} 

 <div id="myModalRtnInventory" class="modal fade" aria-hidden="true">
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
                        <div class="col-sm-7">
                            <input type="hidden" name="item_id" id="item_id" value="">
                            {!! Form::text('item_return_to_supplier',null, ['class'=>'form-control', 'readonly', 'id'=>'item_return_to_supplier']) !!}
                        </div>
                </div>


                 <div class="form-group">
                    <label class="col-sm-3 control-label">Unit Qty <span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                        {!! Form::text('unti_qty_o',null, ['class'=>'form-control text-center unti_qty_o', 'readonly'=>true ,'id'=>'unti_qty_o']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Return Qty <span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                        {!! Form::text('unti_qty_i',null, ['class'=>'form-control text-center unti_qty_i','required'=>true ,'id'=>'unti_qty_i']) !!}
                    </div>
                </div>


            <div class="form-group">
                <label class="col-sm-3 control-label">Date Return <span class="text-danger">*</span></label>
                <div  class="col-sm-4 ">
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        {!! Form::text('return_date',null, ['class'=>'form-control return_date', 'required'=>true]) !!}
                    </div>
                </div>
            </div>


                <div class="form-group">    
                    <div class="col-sm-4">
                         <input type="hidden" id="inven_item_id" name="inven_item_id" value="" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Location <span class="text-danger">*</span></label>
                        <div class="col-sm-5">
                        
                        {!! Form::select ('inv_loc',$location, null,['placeholder' => 'Choose Location...','class'=>'chosen-select','required'=>true,'id'=>'inv_loc'])!!}

                        </div>
                </div>

            
                <div class="form-group">
                    <label class="col-sm-3 control-label">Return by:</label>
                    <div class="col-sm-5">

                        {!! Form::select ('return_by',$created_by, null,['placeholder' => 'Choose Employee...','class'=>'chosen-select return_by','required'=>true])!!}

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
