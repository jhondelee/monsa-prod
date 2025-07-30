 

{!! Form::open(array('route' => array('inventory.update'),'class'=>'form-horizontal','role'=>'form')) !!} 
 <div id="myInventoryModal" class="modal fade" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h3 class="modal-title">Edit On-Hand Quantity</h3>
            </div>
            <div class="modal-body">
           
                {!! Form::token(); !!}
                {!! csrf_field() ; !!} 
                {!! Form::hidden('inventory_id',null, ['class'=>'form-control inventory_id','id'=>'inventory_id'])!!}
                <div class="form-group">
                    <label class="col-sm-3 control-label">Item Name <span class="text-danger"></span></label>
                    <div class="col-sm-6">
                        {!! Form::text('item_name',null, ['class'=>'form-control text-center item_name', 'readonly','id'=>'item_name']) !!}
                    </div>
                </div>
                  <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Description <span class="text-danger"></span></label>
                    <div class="col-sm-6">
                        {!! Form::text('item_descript',null, ['class'=>'form-control text-center item_descript', 'readonly','id'=>'item_descript']) !!}
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Unit <span class="text-danger"></span></label>
                    <div class="col-sm-6">
                        {!! Form::text('item_unit',null, ['class'=>'form-control text-center item_unit', 'readonly','id'=>'item_unit']) !!}
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Quantity <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        {!! Form::text('item_quantity',null, ['class'=>'form-control text-center item_quantity','placeholder'=>'0.00', 'required'=>true ,'id'=>'item_quantity']) !!}
                    </div>
                </div>
                  <div class="hr-line-dashed"></div>

            </div>
            <div class="modal-footer">
                {!! Form::submit('Save Changes', ['class' => 'btn btn-primary btn-submit']) !!}
                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>                                 
            </div>
            
        </div>
     </div>
 </div>
{!! Form::close() !!}

