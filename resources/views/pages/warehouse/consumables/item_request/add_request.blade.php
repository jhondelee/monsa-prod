    
{!! Form::open(array('route' => array('consumables.add_request'),'class'=>'form-horizontal','role'=>'form')) !!} 

 <div id="myModalrequest" class="modal fade" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h3 class="modal-title"></h3>
            </div>
            <div class="modal-body">
           
                {!! Form::token(); !!}
                {!! csrf_field() ; !!} 

                <div class="form-group">
                    <input type="hidden" name="inven_id" id="inven_id">
                    <label class="col-sm-4 control-label">Reference No.<span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                            {!! Form::text('reference_no',null, ['class'=>'form-control reference_no', 'required'=>true ,'id'=>'reference_no']) !!}
                        </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Item Name<span class="text-danger"></span></label>
                        <div class="col-sm-8">
                            {!! Form::text('item_name',null, ['class'=>'form-control item_name', 'readonly'=>true ,'id'=>'item_name']) !!}
                        </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Units<span class="text-danger"></span></label>
                        <div class="col-sm-4">
                            {!! Form::text('item_name',null, ['class'=>'form-control text-center item_name', 'readonly'=>true ,'id'=>'item_units']) !!}
                        </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Request Quantity <span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                        {!! Form::text('quantity',null, ['class'=>'form-control text-center quantity','placeholder'=>'0.00', 'required'=>true ,'id'=>'quantity']) !!}
                    </div>
                </div>


            </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

                    <button type="submit" class="btn btn-primary btn-save" name="btnSD" value="0">Save Only</button>

                    <button type="submit" class="btn btn-success" name="btnSD" value="1"><i class="fa fa-exclamation-circle"></i>&nbsp;Save & Deduct</button>

        
                 


                </div>
            
        </div>
     </div>
 </div>

{!! Form::close() !!} 
