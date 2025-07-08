@extends('layouts.app')

@section('pageTitle','Users')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>{{$title}}</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('main')}}">Home</a>
            </li>
            <li class="active">
                <strong>User List</strong>
            </li>
        </ol>
    </div>
</div>

@include('layouts.alert')
@include('layouts.deletemodal')

<div class="wrapper wrapper-content animated fadeInRight">       
    <div class="row">
        <div class="col-lg-12">
        
            <div class="ibox float-e-margins">
                
                <div class="ibox-title">
                    <h5>User List</h5>
                    <div class="ibox-tools {{can('user.create')}}">
                        
                        <a class="btn btn-w-m btn-primary btn-sm" href="{{ route('user.create') }}">

                            <i class="fa fa-plus">&nbsp;</i>Add User

                        </a> 
                                  
                    </div>                    
                </div>
                
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover dataTables-example" data-toggle="dataTable" data-form="deleteForm" >
                             <thead>
                                    <tr>

                                    <th>ID</th>
                                    <th>Employee Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                           
                                    </tr>
                            </thead>
                            <tbody>

                                    @foreach($users as $user)

                                        <tr>

                                            <td>{{$user->id}}</td>
                                            <td>{{$user->name}}</td>
                                            <td>{{$user->username}}</td>
                                            <td>{{$user->email}}</td>
                                            <td>{{$user->role}}</td>

                                            <td>

                                                 <div class="btn-group">        
                                                    @if ($user->activated_status === 1)
                                                        <span class="label label-success">Active</span>
                                                    @else
                                                        <span class="label label-warning">Inactive</span>
                                                    @endif
                                                </div>
                                                
                                            </td>
                                            
                                            <td>


                                                <div class="btn-group">
                                                    @if (!can('user.edit'))   
                                                    <a class="btn-info btn btn-xs " href="{{ route('user.edit',$user->id)}}">Edit</a>
                                                    @endif
                                                </div>

                                                <div class="btn-group ">
                                                    @if (!can('user.delete')) 
                                                    {!! Form::model($user, [ 'route' => ['user.delete', $user->id], 'class' =>'btn-group form-delete']) !!}
                                                    {{ method_field('DELETE') }}                                                                                                        
                                                    {!! Form::hidden('id', $user->id) !!}
                                                    {!! Form::submit(trans('Delete'), ['class' => 'btn btn-xs btn-danger', 'name' => 'delete_modal']) !!}
                                                    {!! Form::close() !!}
                                                    @endif                                            
                                                          
                                                </div>
                                                           
                                                       
                                            </td>

                                        </tr>

                                    @endforeach
                                                                               
                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
        </div>
    </div>        
</div>

@endsection


@section('styles')
<link rel="stylesheet" href="{{ asset('/css/plugins/chosen/bootstrap-chosen.css') }}" />
@endsection


@section('scripts')
<script src="{{ asset('/js/plugins/chosen/chosen.jquery.js') }}" type="text/javascript"></script>

<script type="text/javascript">
            
$(document).ready(function(){
    $('table[data-form="deleteForm"]').on('click', '.form-delete', function(e){
        e.preventDefault();
        var $form=$(this);
        $('#confirmDelete').modal({ backdrop: 'static', keyboard: false })
            .on('click', '#delete-btn', function(){
                $form.submit();
            });
        });

  });

      
</script>

@endsection
