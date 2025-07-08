

{!! Form::token(); !!}

{!! csrf_field() ; !!}

<div class="form-group">  

    <label class="col-sm-2 control-label">Display Name <span class="text-danger">*</span></label> 

        <div class="col-sm-3">                  

           {!! Form::text('name', null, ['class'=>'form-control', 'required'=>'']) !!}

        </div>
        
</div>


<div class="form-group">

        <label class="col-sm-2 control-label">Icon <span class="text-danger">*</span><br><small class="text-navy">HTML Class Attribute</small></label>  

    <div class="col-sm-3">
                                        
             {!! Form::text('icon_class', null, ['class'=>'form-control', 'required'=>'']) !!}
                                    
    </div>

</div>


<div class="form-group">

    <label class="col-sm-2 control-label">Order </label>
                                    
    <div class="col-sm-3">
                                        
            {!! Form::text('sort', null, ['class'=>'form-control']) !!}
                                    
    </div>

</div>


<div class="hr-line-dashed"></div>

<div class="form-group">

    <div class="col-sm-4 col-sm-offset-2">

        <a class="btn btn-warning" href="{{ route('pgroup.index') }}">Cancel</a> 

        &nbsp;
                                                           
        {!! Form::submit(' Save Changes ', ['class' => 'btn btn-primary']) !!}
                                            
    </div>  

</div>

