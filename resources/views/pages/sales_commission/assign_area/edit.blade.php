
{!! Form::open(array('route' => array('assign_area.update'),'class'=>'form-horizontal','role'=>'form')) !!} 

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
                <div class="editform"> 
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Agent Name <span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            {!! Form::select ('employee_id',$employee, null,['placeholder' => 'Select Agent...','class'=>'chosen-select','required'=>true, 'id'=>'emp_id_edit'])!!}
                        </div>
                    </div>    

                    <div class="hr-line-dashed"></div>
                    
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Rate <span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            {!! Form::select ('rate',$rates, null,['placeholder' => 'Select Rate Percentage...','class'=>'chosen-select','required'=>true ,'id'=>'rates_edit'])!!}
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Assign Area <span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            {!! Form::select ('area',$areas, null,['placeholder' => 'Select Area...','class'=>'chosen-select','required'=>true ,'id'=>'area_edit'])!!}
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                {!! Form::submit('Save Changes', ['class' => 'btn btn-primary btn-update']) !!}
                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>                                 
            </div>
            
        </div>
     </div>
 </div>

{!! Form::close() !!} 


