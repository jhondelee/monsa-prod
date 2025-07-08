  
{!! Form::open(array('route' => array('calendar.store','method'=>'POST'),'class'=>'form-horizontal')) !!}

<div id="eventModal" class="modal fade" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                 <h3>Create Event Schedule</h3>
            </div>
            <div class="modal-body">

                <div class="form-group">

                  <label class="col-sm-3 control-label">Title <span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                       {!! Form::text('title',null, ['class'=>'form-control title','required' =>'', 'id'=>'title']) !!}
                    </div>
                    <input type="hidden" name="start_date" value="" id="start_date">
                    <input type="hidden" name="end_date" value="" id="end_date">
                </div>

            
            </div>
                <div class="modal-footer">
                    {!! Form::submit('Save', ['class' => 'btn btn-primary btn-save']) !!}
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>                                 
                </div>
            
        </div>
     </div>
 </div>
    
        
{{Form::close()}}

