
{!! Form::open(array('route' => array('item.update_price'),'class'=>'form-horizontal','role'=>'form')) !!} 

 <div id="editModal" class="modal fade" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h3 class="modal-title"></h3>
            </div>
            <div class="modal-body">
           
                {!! Form::token(); !!}
                {!! csrf_field() ; !!} 
                {!! Form::hidden('id',null, ['class'=>'form-control','id'=>'id_edit']) !!}

                <div class="form-group">
                    <label class="col-sm-4 control-label">Item: <span class="text-danger">*</span></label>
                    <div class="col-sm-7"> 
                        {!! Form::text('description',null, ['class'=>'form-control', 'readonly'=> true,'id'=>'descript_edit']) !!}
                    </div>
                </div>  

                @if (!can('item.unit_cost'))
                <div class="form-group">
                    <label class="col-sm-4 control-label">Unit Cost <span class="text-danger"></span></label>
                    <div class="col-sm-7">
                        {!! Form::text('unit_cost',null, ['class'=>'form-control', 'required'=> false,'id'=>'unit_cost_edit']) !!}
                    </div>
                </div>
                @endif  
                <div class="hr-line-dashed"></div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Srp: <span class="text-danger"></span></label>
                    <div class="col-sm-7">
                        {!! Form::text('unit_srp',null, ['class'=>'form-control','placeholder'=>'0.00','id'=>'srp_edit']) !!}
                    </div>
                </div>

                <div class="hr-line-dashed"></div>

            </div>
            <div class="modal-footer">
                {!! Form::submit('Save Changes', ['class' => 'btn btn-primary btn-update']) !!}
                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>                                 
            </div>
            
        </div>
     </div>
 </div>

{!! Form::close() !!} 


