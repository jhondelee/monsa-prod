 
 {!! Form::token(); !!}
 {!! csrf_field() ; !!} 
    


<div class="row">
    <div class="col-sm-3">
        <div class="form-group">
            <label>Reference No. <span class="text-danger">*</span></label> 
            {!! Form::text('reference_no',null, ['class'=>'form-control reference_no', 'required'=>true ,'id'=>'reference_no']) !!}
        </div>
        <div class="form-group">
            <label>Source Location <span class="text-danger">*</span></label> 
            
            {!! Form::select ('source',$location, null,['placeholder' => 'Choose Source Location...','class'=>'chosen-select required source'])!!}
        </div>
         <span class="help-block m-b-none">
            @if ($errors->has('source'))
             	<strong class="red">{{ $errors->first('source') }}</strong>
            @endif
        </span>

	    <div class="form-group">
            <label>Destination Location <span class="text-danger">*</span></label> 
	        {!! Form::select ('destination',$location, null,['placeholder' => 'Choose Destination Location...','class'=>'chosen-select required destination'])!!}
	        <span class="help-block m-b-none">
	            @if ($errors->has('destination'))
	                    <strong class="red">{{ $errors->first('destination') }}</strong>
	            @endif
	        </span>
	    </div>
        
        <div class="form-group">
            <label>Date Transfer <span class="text-danger">*</span></label> 
                
            <div class="input-group date">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    {!! Form::text('transfer_date',null, ['class'=>'form-control', 'required'=>true]) !!}
            </div>
          
        </div>               
                            
	    <div class="form-group"><label>Notes</label>
	        {!! Form::textarea('notes',null, array('class' => 'form-control','rows' => 2,'cols' => 4,'id'=>'notes')) !!}
	    </div>
    </div>               

                            
        <div class="col-sm-9">
        	<button type="button" class="btn btn-w-m btn-xs btn-warning" id="add-item-modal"><i class="fa fa-plus">&nbsp;</i>Add Items</button>
            <div class="table-responsive">
            	<table class="table table-bordered" id="create_transfer_order" >
                    <thead > 
                        <tr >
                            <th>ID</th>
                            <th>Destination</th>
                            <th>Item Name</th>
                            <th class="text-center">Units</th>
                            <th class="text-center">Req Qty</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                                    
                </table>
            </div>
        </div>
                            
       <div class="row">
            <div class="col-lg-12">
            </div>
        </div>                 
                            
    <div class="ibox-tools pull-right">
        <p> 
			<button type="button" class="btn btn-w-m btn-danger" id='btn-cancel'>Cancel</button>

			 {!! Form::submit('Save Changes', ['class' => 'btn btn-primary','id'=>'submit_form']) !!}  
            
        </p>
    </div>

</div>