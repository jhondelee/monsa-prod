{!! Form::open(['route'=>'brochure.upload_file' ,'enctype'=>"multipart/form-data",  'class'=>'form-horizontal']) !!}   

<div id="uploadModal" class="modal fade" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
               <h3>Upload File</h3>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            </div>
            <div class="modal-body">

                <div class="form-group">

                  <label class="col-sm-3 control-label">Name <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                       {!! Form::text('name',null, ['class'=>'form-control name','required' =>'', 'id'=>'name']) !!}
                    </div>


                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">File <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        
                        {!! Form::file('item_picture');  !!}
                        
                    </div>
                </div> 

                 <div class="form-group">

                       <label class="col-sm-3 control-label">Remarks</label>
                    <div class="col-sm-6">
                         {!! Form::textarea('remarks',null, array('class' => 'form-control', 'rows' => 2,'id'=>'notes')) !!}
                    </div>

                </div>
 
            
            </div>
                <div class="modal-footer">
                    {!! Form::submit('Upload', ['class' => 'btn btn-primary btn-save']) !!}
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>                                 
                </div>
            
        </div>
     </div>
 </div>
    
        
{{Form::close()}}

