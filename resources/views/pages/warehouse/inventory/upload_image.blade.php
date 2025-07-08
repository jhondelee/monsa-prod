{!! Form::open(['route'=>'inventory.upload_image' ,'enctype'=>"multipart/form-data",  'class'=>'form-horizontal']) !!}   
{!! Form::hidden('id', $showItems->id, ['id'=>'id']) !!}
<div id="uploadModal" class="modal fade" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
               <h3>Upload Item Image</h3>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            </div>
            <div class="modal-body">
            
                <div class="form-group">
                    <label class="col-sm-4 control-label">Item Picture <span class="text-danger">*</span></label>
                    <div class="col-sm-5">
                        
                        {!! Form::file('item_picture');  !!}
                        
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

