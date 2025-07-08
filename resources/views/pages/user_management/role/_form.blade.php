
<div class="form-group">
    <label class="col-sm-2 control-label">Role Name <span class="text-danger">*</span><br><small class="text-navy">Unique code_name</small></label>
    <div class="col-sm-4">
        {!! Form::text('name', null, ['class'=>'form-control', 'required'=>'']) !!}
    </div>
</div>

<div class="hr-line-dashed"></div>

<div class="form-group">
    <label class="col-sm-2 control-label">Display Name <span class="text-danger">*</span></label>
    <div class="col-sm-4">
        {!! Form::text('display_name', null, ['class'=>'form-control', 'required'=>'']) !!}
    </div>
</div>

<div class="hr-line-dashed"></div>

<div class="form-group">
    <label class="col-sm-2 control-label">Access Routes </label>
    <div class="col-sm-4">
        <div class="input-group m-b">
            <span class="input-group-addon"><i class="fa fa-search"></i></span>
            {!! Form::text('search_routes', null, ['class'=>'form-control', 'placeholder'=>'Search ...', 'id'=>'search_routes']) !!}       
        </div>
        <div class="checkbox checkbox-primary">
            <input type="checkbox" id="select-tree-checkbox" />
            <label for="select-tree-checkbox">
                <strong>Select All</strong>
            </label>
        </div>
    </div>   
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Routes </label>
        <div class="col-sm-6">
            <div class="feed-activity-list scroll_content">
                <tr id="myList">
                    <td>
                         @foreach($routes as $route)
                            <div class="checkbox checkbox-primary route-item">
                                {!! Form::checkbox('routes[]',$route->id, null, ['id'=>'routes']) !!}
                                    <label for="permissions">
                                        <p>{{$route->route_name}}</p>
                                    </label>
                            </div>
                        @endforeach
                    </td>
                </tr>
            </div>
        </div>   
    </div>



<div class="hr-line-dashed"></div>

<div class="form-group">

    <div class="col-sm-4 col-sm-offset-2">

        <a class="btn btn-warning" href="{{ route('role.index') }}">Cancel</a> 

        &nbsp;
                                                           
        {!! Form::submit(' Save Changes ', ['class' => 'btn btn-primary']) !!}
                                            
    </div>  

</div>
