


 <div id="myModal" class="modal fade" aria-hidden="true">
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
                    <label class="col-sm-4 control-label">Employee <span class="text-danger">*</span></label>
                    <div class="col-sm-7">
                        {!! Form::select ('employee_id',$employee, null,['placeholder' => 'Select Agent...','class'=>'chosen-select','required'=>true])!!}
                    </div>
                </div>

                <div class="hr-line-dashed"></div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Start Date <span class="text-danger">*</span></label>
                    <div  class="col-sm-7 ">
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            {!! Form::text('start_date',null, ['class'=>'form-control start_date', 'required'=>true, 'id'=>'start_date']) !!}
                        </div>
                    </div>
                </div>

                <div class="hr-line-dashed"></div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">End Date <span class="text-danger">*</span></label>
                    <div  class="col-sm-7 ">
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            {!! Form::text('end_date',null, ['class'=>'form-control end_date', 'required'=>true, 'id'=>'end_date']) !!}
                        </div>
                    </div>
                </div>


                <div class="hr-line-dashed"></div>

            </div>
            <div class="modal-footer">
                {!! Form::submit('Generate', ['class' => 'btn btn-primary btn-save']) !!}
                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>                                 
            </div>
            
        </div>
     </div>
 </div>




