 
 {!! Form::token(); !!}
 {!! csrf_field() ; !!} 



<div class="row">

    <div class="col-sm-3">
        <div class="form-group"><label>Reference No. <span class="text-danger">*</span></label> 
         {!! Form::text('reference_no',$inventorymovements->reference_no, ['class'=>'form-control reference_no', 'required'=>true ,'id'=>'reference_no']) !!}
        </div>       
        <div class="form-group"><label>Source Location <span class="text-danger">*</span></label> 
            {!! Form::select ('source',$location,null,['placeholder' => 'Choose Source Location...','class'=>'chosen-select required source'])!!}
        </div>
         <span class="help-block m-b-none">
            @if ($errors->has('source'))
             	<strong class="red">{{ $errors->first('source') }}</strong>
            @endif
        </span>

	    <div class="form-group"><label>Destination Location <span class="text-danger">*</span></label> 
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
                    {!! Form::text('transfer_date',$inventorymovements->transfer_date, ['class'=>'form-control', 'required'=>true]) !!}
            </div>
          
        </div>               
                            
	    <div class="form-group"><label>Notes</label>
	        {!! Form::textarea('notes',$inventorymovements->notes, array('class' => 'form-control','rows' => 2,'cols' => 4,'id'=>'notes')) !!}
	    </div>
    </div>               

                            
        <div class="col-sm-9">
            @if ($inventorymovements->status == 'CREATED')
            <button type="button" class="btn btn-w-m btn-xs btn-warning" id="add-item-modal"><i class="fa fa-plus">&nbsp;</i>Add Items</button>
            @endif
        	

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
                        @foreach($movementItems as $movementItem)
                            <tr>
                                <td>{{$movementItem->item_id}}
                                <input type='hidden'  name='item_id[]' id='item_id' value="{{$movementItem->item_id}}" >
                                </td>
                                <td>{{$movementItem->to_location}}</td>
                                <td>{{$movementItem->description}}</td>
                                <td class="text-center">{{$movementItem->units}}</td>
                                <td class="text-center"><input type='text'  name='qty_value[]' class='text-center' size='8' value="{{$movementItem->quantity}}" readonly></td>
                                <td class="text-center"><a class='btn btn-xs btn-danger' id='delete_line'><i class='fa fa-minus'></i></td></tr>
                            </tr>
                        @endforeach
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
            

                <a href="{{route('inventory.index')}}" class="btn btn-primary">
                    <i class="fa fa-reply">&nbsp;</i>Back
                </a> 


            @if ($inventorymovements->status == 'CREATED')

                @if (!can('transfer.post'))
                    <button type="button" class="btn btn-success" onclick="confirmPost('{{$inventorymovements->id}}'); return false;"><i class="fa fa-exclamation-circle">&nbsp;</i>Post&nbsp;</button>
                @endif

                {!! Form::submit('Save Changes', ['class' => 'btn btn-primary']) !!}  
            @endif
            

			
            
        </p>
    </div>

</div>