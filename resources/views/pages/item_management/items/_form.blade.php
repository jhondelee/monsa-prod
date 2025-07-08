
{!! Form::token(); !!}

{!! csrf_field() ; !!}


<div class="form-group">
    <label class="col-sm-2 control-label">Item Code<span class="text-danger">*</span><br><small class="text-navy">Unique item_code</small></label>
        <div class="col-sm-2">
            {!! Form::text('code',null, ['class'=>'form-control','readonly','placeholder' =>'Auto Generated' ]) !!}
        </div>
</div>


<div class="hr-line-dashed"></div>
 
<div class="form-group">
    <label class="col-sm-2 control-label">Item Name <span class="text-danger">*</span><br><small class="text-navy">Unique item_name </small></label>
    <div class="col-sm-4">
        {!! Form::text('name', null, ['class'=>'form-control', 'required'=>'true']) !!}
    </div>
</div>

<div class="hr-line-dashed"></div>

<div class="form-group">
    <label class="col-sm-2 control-label">Description <span class="text-danger">*</span></label>
    <div class="col-sm-5">
         {!! Form::textarea('description',null, array('class' => 'form-control', 'rows' => 3, 'required'=>true)) !!}
    </div>
</div>

<div class="hr-line-dashed"></div>

    <div class="form-group">
    <label class="col-sm-2 control-label">Unit of Measure <span class="text-danger">*</span></label>
    <div class="col-sm-3">
        {!! Form::select ('unit_id',$units, null,['placeholder' => 'Select Unit...','class'=>'chosen-select','required'=>true])!!}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Unit Quantity<br></label>
    <div class="col-sm-2">
        {!! Form::number('unit_quantity', null, ['class'=>'form-control', 'placeholder'=>'0']) !!}
    </div>
</div>



<div class="hr-line-dashed"></div>
    <div class="form-group">
        <div class="col-sm-2"></div>
        <div class="col-sm-8 col-sm-4">
            @if (isset($item->picture))
                <img alt="" class="img-responsive" src="{{asset('/item_image/').'/'.$item->picture}}" />
            @else
                <img alt="" class="img-responsive" src="{{asset('/item_image/image_default.png')}}" /> 
            @endif
        </div>
    </div>

        <div class="form-group">
            <label class="col-sm-2 control-label"></label>
            <div class="col-sm-4">
                <div class="fileinput fileinput-new" data-provides="fileinput">

                    <span class="btn btn-default btn-file">

                        <span class="fileinput-new">Upload Item Image</span>

                        <span class="fileinput-exists">Change Item Image</span>

                        {!! Form::file('item_picture') !!}

                    </span>

                    <span class="fileinput-filename"></span>
                    <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">×</a>
                </div>  
            </div>
        </div>


<div class="hr-line-dashed"></div>
 
<div class="form-group">
    <label class="col-sm-2 control-label">Safety Stock Level</label>
    <div class="col-sm-2">
        {!! Form::number('safety_stock_level', null, ['class'=>'form-control', 'placeholder'=>'0', 'id'=>'safety_stock']) !!}
    </div>
</div>

<div class="hr-line-dashed"></div>
 
<div class="form-group">
    <label class="col-sm-2 control-label">Critical Stocl Level</label>
    <div class="col-sm-2">
        {!! Form::number('criticl_stock_level', null, ['class'=>'form-control', 'placeholder'=>'0', 'id'=>'criticl_stock_level']) !!}
    </div>
</div>

<div class="hr-line-dashed"></div>
 
<div class="form-group">
    <label class="col-sm-2 control-label">SRP</label>
    <div class="col-sm-2">
        {!! Form::text('srp', null, ['class'=>'form-control', 'placeholder'=>'0.00','onchange'=>'validateFloatKeyPress(this);']) !!}
    </div>
</div>

<div class="hr-line-dashed"></div>
 
<div class="form-group">
    <label class="col-sm-2 control-label">Unit Cost</label>
    <div class="col-sm-2">
        {!! Form::text('unit_cost', null, ['class'=>'form-control', 'placeholder'=>'0.00','onchange'=>'validateFloatKeyPress(this);'])  !!}
    </div>
</div>
    


<div class="hr-line-dashed"></div>

<div class="form-group">

    <div class="col-sm-4 col-sm-offset-2">

        <a class="btn btn-warning" href="{{ route('item.index') }}">Cancel</a> 

        &nbsp;
                                                           
        {!! Form::submit(' Save Changes ', ['class' => 'btn btn-primary']) !!}
                                            
    </div>  

</div>

@section('styles')
<link rel="stylesheet" href="{!! asset('/css/plugins/jasny/jasny-bootstrap.min.css') !!}" />
@endsection
@section('scripts')
<script src="{!! asset('/js/plugins/jasny/jasny-bootstrap.min.js') !!}" type="text/javascript"></script>

 <script type="text/javascript">

        function validateFloatKeyPress(el) {
            var v = parseFloat(el.value);
            el.value = (isNaN(v)) ? '' : v.toFixed(2);
        }
        
 </script>

@endsection