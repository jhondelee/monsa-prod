 
 {!! Form::token(); !!}
 {!! csrf_field() ; !!} 
    


<div class="row">
    <div class="col-sm-12">
       <div class="form-group">
            <label class="col-sm-2 control-label">Ending Date <span class="text-danger">*</span></label>
            <div  class="col-sm-3 ">
                <div class="input-group date">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    {!! Form::text('ending_date',null, ['class'=>'form-control', 'required'=>true]) !!}
                </div>
            </div>

            <label class="col-sm-2 control-label">Prepared by <span class="text-danger">*</span></label>
            <div class="col-sm-3">
                {!! Form::select ('prepared_by',$prepared_by, null,['placeholder' => 'Select Employee...','class'=>'chosen-select','required'=>true,'id'=>'prepared_by'])!!}
            </div>
        </div>
              
        <div class="hr-line-dashed"></div>
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable-ending-inventory" >
                <thead > 
                    <tr >
                        <th class="text-center">ID</th>
                        <th>Item Code</th>
                        <th>Description</th>
                        <th class="text-center">Unit</th>
                        <th class="text-center">On-Hand Qty</th>
                        <th class="text-center">Location</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td class="text-center">
                            <input type="input"   class="text-center" size ="3" id="id" name="id[]" value="{{$item->id}}">
                            <input type="hidden" id="item_id" name="item_id[]" value="{{$item->item_id}}">
                        </td>
                        <td>{{$item->name}}</td>
                        <td>{{$item->description}}</td>
                        <td class="text-center">
                        <input type="input" size ="3" id="item_unit_qty" name="item_unit_qty[]" value="{{$item->unit_quantity}}" class="text-center">
                        {{$item->units}}
                        </td>
                        <td class="text-center">
                            <input type="input" size ="10" id="onhand_quantity" name="onhand_quantity[]" class="text-right" value="{{$item->onhand_quantity}}"></td>
                        <td class="text-center">{{$item->location}}</td>
                    </tr>
                @endforeach
                </tbody>                    
            </table>
        </div>
    </div>
    <div class="col-md-12 form-horizontal">
        <div class="ibox-tools pull-right">     
            {!! Form::submit('Save Changes', ['class' => 'btn btn-primary','id'=>'submit_form']) !!}  
        </div>
    </div>
</div>