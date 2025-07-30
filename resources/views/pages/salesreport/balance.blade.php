 

{!! Form::open(array('route' => array('salesreport.printbalance'),'class'=>'form-horizontal','role'=>'form')) !!} 
 <div id="myBalanceReport" class="modal fade" aria-hidden="true">
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
                <label class="col-sm-3 control-label">Schedule Date <span class="text-danger">*</span></label>
                <div  class="col-sm-8">
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        {!! Form::text('sched_date',null, ['class'=>'form-control sched_date','id'=>'sched_date', 'required'=>true]) !!}
                    </div>
                </div>
            </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Areas <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                        
                        {!! Form::select ('areas_id',$areas,null,['class'=>'chosen-select area_id','multiple style'=>'width:350px;', 'multiple' => 'multiple','tabindex'=>'4' ,'id'=>'area_id','required'=>true])!!}

                        </div>
                </div>

                <div class="hr-line-dashed"></div>

            </div>
            <div class="modal-footer">
                {!! Form::submit('Submit', ['class' => 'btn btn-primary btn-submit']) !!}
                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>                                 
            </div>
            
        </div>
     </div>
 </div>
{!! Form::close() !!}

