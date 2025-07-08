

{!! Form::token(); !!}

{!! csrf_field() ; !!}

<div class="form-group">
    <label class="col-sm-2 control-label">Route Name <span class="text-danger">*</span><br><small class="text-navy">Defined in routes (web.php)</small></label>
    <div class="col-sm-3">
        @if(isset($permission->route_name))
            {!! Form::text('route_name', null, ['class'=>'form-control']) !!}
        @else
            {!! Form::select('route_name', $routeList, null, ['placeholder' => '--', 'class' => 'form-control m-b chosen-select', 'required'=>'']) !!}
        @endif
    </div>
</div> 

<div class="hr-line-dashed"></div>

<div class="form-group">
    <label class="col-sm-2 control-label">Display Name</label>
    <div class="col-sm-3">
        {!! Form::text('display_name', null, ['class'=>'form-control']) !!}
        <span class="help-block m-b-none">Optional</span>
    </div>
</div>

<div class="hr-line-dashed"></div>

<div class="form-group">
    <label class="col-sm-2 control-label">Icon <br><small class="text-navy">HTML Class Attribute</small></label>
    <div class="col-sm-3">
        {!! Form::text('icon_class', null, ['class'=>'form-control']) !!}
        <span class="help-block m-b-none">Optional</span>
    </div>
</div>

<div class="hr-line-dashed"></div>

<div class="form-group">
    <label class="col-sm-2 control-label">Navigation Group</label>
    <div class="col-sm-3">
        {!! Form::select('group_id', $groups, null, ['placeholder' => '--', 'class' => 'form-control m-b chosen-select']) !!}
        <span class="help-block m-b-none">Optional</span>
    </div>
</div>

<div class="hr-line-dashed"></div>

<div class="form-group">
    <label class="col-sm-2 control-label"></label>
    <div class="col-sm-3">
        <div class="checkbox checkbox-success">
            {!! Form::checkbox('display_status', '1', null, ['id'=>'display_status']) !!}
            <label for="display_status">
                Display
            </label>
        </div>
    </div>
</div>

<div class="hr-line-dashed"></div>

<div class="form-group">
    <label class="col-sm-2 control-label">Order </label>
    <div class="col-sm-3">
        {!! Form::text('sort', null, ['class'=>'form-control', 'required'=>'']) !!}
    </div>
</div>


<div class="hr-line-dashed"></div>

<div class="form-group">

    <div class="col-sm-4 col-sm-offset-2">

        <a class="btn btn-warning" href="{{ route('permission.index') }}">Cancel</a> 

        &nbsp;
                                                           
        {!! Form::submit(' Save Changes ', ['class' => 'btn btn-primary']) !!}
                                            
    </div>  

</div>



