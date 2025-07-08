 
 {!! Form::token(); !!}
 {!! csrf_field() ; !!} 



<div class="row">
    <div class="col-sm-3">
        <input type='hidden'  name='transfer_order' id='transfer_order' value="{{$WarehouseMovements->transfer_order}}">       <div class="form-group"><label>Reference No. <span class="text-danger">*</span></label> 
         {!! Form::text('reference_no',null, ['class'=>'form-control reference_no', 'required'=>true ,'id'=>'reference_no']) !!}
        </div>       
        <div class="form-group"><label>Source Location <span class="text-danger">*</span></label> 
            {!! Form::select ('source',['Warehouse'=>'Warehouse','Raw Materials'=>'Raw Materials'], null,['placeholder' => 'Choose Source Location...','class'=>'chosen-select required source'])!!}
        </div>
         <span class="help-block m-b-none">
            @if ($errors->has('source'))
             	<strong class="red">{{ $errors->first('source') }}</strong>
            @endif
        </span>

	    <div class="form-group"><label>Destination Location <span class="text-danger">*</span></label> 
	        {!! Form::select ('destination',['Warehouse'=>'Warehouse','Raw Materials'=>'Raw Materials'], null,['placeholder' => 'Choose Destination Location...','class'=>'chosen-select required destination'])!!}
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
        	<button type="button" class="btn btn-rounded btn-xs btn-warning" id="add-item-modal"><i class="fa fa-plus">&nbsp;</i>Add Items</button>
            <div class="table-responsive">
            	<table class="table table-bordered" id="create_transfer_order" >
                    <thead > 
                        <tr >
                            <th>ID</th>
                            <th>Item Name</th>
                            <th>Destination</th>
                            <th>Qty</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($WarehouseMovementItems as $WarehouseMovementItem)
                            <tr>
                                <td>{{$WarehouseMovementItem->warehouse_item_id}}
                                    <input type='hidden'  name='item_id[]' id='item_id' value="{{$WarehouseMovementItem->warehouse_item_id}}" >
                                </td>
                                <td>{{$WarehouseMovementItem->description}}</td>
                                <td>{{$WarehouseMovementItem->to_location}}</td>
                                <td><input type='text'  name='qty_value[]' class='text-center' size='8' value="{{$WarehouseMovementItem->quantity}}" readonly></td>
                                <td><a class='btn btn-xs btn-danger' id='delete_line'><i class='fa fa-minus'></i></td></tr>
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
            @if ($WarehouseMovements->status == 'POSTED')
               
                   <a href="{{route('stock_transfer.print',$WarehouseMovements->id)}}" class="btn btn-primary btn-print"><i class="fa fa-print">&nbsp;</i>Print</a> &nbsp;
                   <button type="button" class="btn btn-primary" id='btn-cancel'><i class="fa fa-reply">&nbsp;</i>Back</button>

            @endif
            @if ($WarehouseMovements->status == 'CREATED')
                @if (!can('stock_transfer.post'))
                    <button type="button" class="btn btn-success" onclick="confirmPost('{{$WarehouseMovements->id}}'); return false;"><i class="fa fa-exclamation-circle">&nbsp;</i>Post</button>
                @endif
                <a href="{{route('stock_transfer.print',$WarehouseMovements->id)}}" class="btn btn-primary btn-print"><i class="fa fa-print">&nbsp;</i>Print</a>&nbsp;

                {!! Form::submit('Save Changes', ['class' => 'btn btn-primary']) !!}  
            @endif
            

			
            
        </p>
    </div>

</div>