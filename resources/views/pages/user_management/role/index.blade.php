@extends('layouts.app')

@section('pageTitle','Roles')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>{{$title}}</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('main')}}">Home</a>
            </li>
            <li class="active">
                <strong>Role List</strong>
            </li>
        </ol>
    </div>
</div>

        @include('layouts.alert')
        @include('layouts.alert2')
        @include('layouts.deletemodal')

<div class="wrapper wrapper-content animated fadeInRight">       
    <div class="row">
        <div class="col-lg-12">
        
            <div class="ibox float-e-margins">
                
                <div class="ibox-title">
                    <h5>Role List</h5>
                    <div class="ibox-tools {{can('role.create')}}">

                        <a class="btn btn-w-m btn-primary btn-sm" href="{{ route('role.create') }}">

                            <i class="fa fa-plus">&nbsp;</i>Add Role

                        </a> 

                    </div>                    
                </div>
                
                <div class="ibox-content">  
                    <div class="table-responsive">
                         <table class="table table-striped table-hover dataTables-role" data-toggle="dataTable" data-form="deleteForm" >
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Display Name</th>
                                    <th>Role Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                    <tr>
                                        <td>{{$role->id}}</td>
                                        <td>{{$role->display_name}}</td>
                                        <td>{{$role->name}}</td>
                                        <td>
                                            <div class="btn-group {{can('role.edit')}}">              
                                                <a class="btn-info btn btn-xs " href="{{ route('role.edit',$role->id)}}">Edit</a>
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
<link rel="stylesheet" href="{!! asset('css/plugins/gijgo.min.css') !!}" />
@endsection

@section('scripts')
<script src="{!! asset('js/plugins/gijgo.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/slimscroll/jquery.slimscroll.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/app.custom.js') !!}" type="text/javascript"></script>

<script "text/javascript">
$(document).ready(function(){
              $('.dataTables-role').DataTable({
                pageLength: 10,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    //{ extend: 'copy'},
                    //{extend: 'csv'},
                    //{extend: 'excel', title: 'ExampleFile'},
                    {extend: 'pdf', title: 'ExampleFile'},

                    {extend: 'print',
                     customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                    }
                    }
                ]

            });

        });

    

</script>
@endsection
