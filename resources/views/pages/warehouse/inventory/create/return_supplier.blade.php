    
{!! Form::open(array('route' => array('inventory.return_to_supplier'),'class'=>'form-horizontal','role'=>'form')) !!} 

 <div id="myModalsupplier" class="modal fade" aria-hidden="true">
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
                            <input type="hidden" name="item_rtn_id" id="item_rtn_id" value="">
                            {!! Form::text('item_return_to_supplier',null, ['class'=>'form-control item_return_to_supplier', 'readonly']) !!}
                        </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-3 control-label">Unit Qty <span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                        {!! Form::text('unti_qty',null, ['class'=>'form-control text-center unti_qty','placeholder'=>'0.00', 'required'=>true ,'id'=>'unti_qty']) !!}
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
                         <input type="hidden" id="inven_id" name="inven_id" value="" />
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">Supplier <span class="text-danger">*</span></label>
                        <div class="col-sm-5">
                        
                        {!! Form::select ('supplier_id',$suppliers, null,['class'=>'chosen-select supplier_id','required'=>true])!!}

                        </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Location <span class="text-danger">*</span></label>
                        <div class="col-sm-5">
                        
                        {!! Form::select ('location',$location, null,['placeholder' => 'Choose Location...','class'=>'chosen-select location','required'=>true])!!}

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
