 

{!! Form::open(array('route' => array('commission_report.print'),'class'=>'form-horizontal','role'=>'form')) !!} 
 <div id="myModalReport" class="modal fade" aria-hidden="true">
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
                <label class="col-sm-3 control-label">Start Date <span class="text-danger">*</span></label>
                <div  class="col-sm-8">
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        {!! Form::text('start_date',null, ['class'=>'form-control start_date','id'=>'start_date', 'required'=>true]) !!}
                    </div>
                </div>
            </div>
                <div class="hr-line-dashed"></div>
            <div class="form-group">
                <label class="col-sm-3 control-label">End Date <span class="text-danger">*</span></label>
                <div  class="col-sm-8 ">
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        {!! Form::text('end_date',null, ['class'=>'form-control end_date','id'=>'end_date', 'required'=>true]) !!}
                    </div>
                </div>
            </div>

                <div class="hr-line-dashed"></div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Main Agent <span class="text-danger"></span></label>
                        <div class="col-sm-8">
                        
                        {!! Form::select ('agent_id',$employee,null,['placeholder' => 'Choose Customer..','class'=>'chosen-select Agent','id'=>'agent_id'])!!}

                        </div>
                </div>

                
            </div>
            <div class="modal-footer">
                {!! Form::submit('Submit', ['class' => 'btn btn-primary btn-submit']) !!}
                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>                                 
            </div>
            
        </div>
     </div>
 </div>
{!! Form::close() !!}