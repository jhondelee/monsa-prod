
{!! Form::token(); !!}

{!! csrf_field() ; !!}



<div class="hr-line-dashed"></div>
 
<div class="form-group">
    <label class="col-sm-2 control-label">Name <span class="text-danger">*</span><br><small class="text-navy">Unique name</small></label>
    <div class="col-sm-5">
        {!! Form::text('name', null, ['class'=>'form-control', 'required'=>'']) !!}
    </div>
</div>

<div class="hr-line-dashed"></div>

<div class="form-group">
    <label class="col-sm-2 control-label">Address <span class="text-danger">*</span></label>
    <div class="col-sm-5">
         {!! Form::textarea('address',null, array('class' => 'form-control', 'rows' => 2, 'required'=>true)) !!}
    </div>
</div>

<div class="hr-line-dashed"></div>
    

<div class="hr-line-dashed"></div>

<div class="form-group">
    <label class="col-sm-2 control-label">Contact Person <span class="text-danger">*</span></label>
    <div class="col-sm-3">
        {!! Form::text('contact_person', null, ['class'=>'form-control','placeholder'=>'','required'=>'']) !!}
    </div>
</div>

 <div class="hr-line-dashed"></div>

<div class="form-group">
    
    <label class="col-sm-2 control-label">Contact Nunber <span class="text-danger">*</span></label>
    <div class="col-sm-3">
        {!! Form::text('contact_number', null, ['class'=>'form-control','placeholder'=>'+63','required'=>'']) !!}
    </div>
</div>

<div class="hr-line-dashed"></div>

<div class="form-group">

    <div class="col-sm-4 col-sm-offset-2">

        <a class="btn btn-warning" href="{{ route('supplier.index') }}">Cancel</a> 

        &nbsp;
                                                           
        {!! Form::submit(' Save Changes ', ['class' => 'btn btn-primary']) !!}
                                            
    </div>  

</div>

@section('styles')
<link rel="stylesheet" href="{!! asset('/css/plugins/jasny/jasny-bootstrap.min.css') !!}" />
@endsection
@section('scripts')
<script src="{!! asset('/js/plugins/jasny/jasny-bootstrap.min.js') !!}" type="text/javascript"></script>
@endsection