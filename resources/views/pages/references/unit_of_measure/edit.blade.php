
{!! Form::open(array('route' => array('unit_of_measure.update'),'class'=>'form-horizontal','role'=>'form')) !!} 

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
                    <label class="col-sm-2 control-label">Code </label>
                    <div class="col-sm-9">
                        {!! Form::text('code',null, ['class'=>'form-control','id'=>'code_edit']) !!}
                    </div>
                </div>

                <div class="hr-line-dashed"></div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Name <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        {!! Form::text('name',null, ['class'=>'form-control', 'required'=>'','id'=>'name_edit']) !!}
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


