{!! Form::token(); !!}
{!! csrf_field() ; !!} 

    <div class="form-group">
                    
        <label class="col-sm-2 control-label">Main Agent <span class="text-danger">*</span></label>
            
        <div class="col-sm-3">
                        
            {!! Form::select ('employee_id',$employee_id, null,['placeholder' => 'Select Agent...','class'=>'chosen-select employee_id','required'=>true])!!}
            
        </div>

        <label class="col-sm-2 control-label">% Rate</label>
        
        <div class="col-sm-2">
            
            {!! Form::text('share_percentage',null, ['class'=>'form-control share_percentage', 'required'=> true]) !!}
                    
        </div>
                
    </div>  

    <div class="hr-line-dashed"></div>

    <div class="form-group">
                    
        <label class="col-sm-2 control-label">Sub Agent </label>
            
        <div class="col-sm-3">
                        
            {!! Form::select ('sub_agent',$employee_id, null,['placeholder' => 'Select Sub Agent...','class'=>'chosen-select sub_agent'])!!}
            
        </div>

        <label class="col-sm-2 control-label">Sub Rate</label>
        
        <div class="col-sm-2">
            
            {!! Form::text('sub_rate',null, ['class'=>'form-control sub_rate']) !!}
                    
        </div>
                
        <div class="col-sm-2">
            
          <button type="button" class="btn btn-info  btn-sm btn-add_sub" id="btn-add_sub">Add</button>
                    
        </div>
    </div>  

    <div class="table-responsive">
           
        <table class="table table-bordered dTable-sub-table" id="dTable-sub-table">
            
            <thead> 
                
                <tr>
                                        
                    <th class="text-center">ID</th>
                    <th class="text-center">Sub Agent</th>
                    <th class="text-center">Rate</th>
                    <th class="text-center">Action</th>
                
                </tr>
            
            </thead>
                                
            <tbody >
                                             
            </tbody>
                                
        </table> 
                             
    </div>
                        
    <hr>

    <div class="row">

        <div class="col-md-12 form-horizontal">

            <div class="ibox-tools pull-right">

                <button type="button" class="btn btn-danger btn-close" id="btn-close">Close</button>

                 &nbsp;
                
                {!! Form::submit(' Save Changes ', ['class' => 'btn btn-primary']) !!}                 
                 

            </div>

        </div>

    </div>
                
        


    
