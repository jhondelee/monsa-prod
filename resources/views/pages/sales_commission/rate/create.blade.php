
{!! Form::open(array('route' => array('commission_rate.store'),'class'=>'form-horizontal','role'=>'form')) !!} 

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
                    <label class="col-sm-4 control-label">Percentage Rate </label>
                    <div class="col-sm-7">
                        {!! Form::text('rate',null, ['class'=>'form-control', 'required'=> true]) !!}
                    </div>
                </div>

                <div class="hr-line-dashed"></div>

            </div>
            <div class="modal-footer">
                {!! Form::submit('Save Changes', ['class' => 'btn btn-primary btn-save']) !!}
                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>                                 
            </div>
            
        </div>
     </div>
 </div>

{!! Form::close() !!} 


