@extends('layouts.app')

@section('pageTitle','Routes')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Route Management</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{route('main')}}">Home</a>
            </li>
            <li class="active">
                <strong>Route List</strong>
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

                    <h5>Route List</h5>

                        <div class="ibox-tools {{can('permission.create')}}" > 

                            <a class="btn btn-w-m btn-primary btn-sm" href="{{ route('permission.create') }}">

                                <i class="fa fa-plus">&nbsp;</i>Add Route

                            </a> 
                                        
                        </div>    

                </div>
                
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover dataTables-permission" data-toggle="dataTable" data-form="deleteForm" >
                            <thead>
                                        <tr>

                                            <th>ID</th>
                                            <th>Display Name</th>
                                            <th>Route Name</th>
                                            <th>Icon</th>
                                            <th>Group</th>
                                            <th>Display</th>
                                            <th>Order</th>
                                            <th>Action</th>
                                           
                                        </tr>
                                        </thead>
                                        <tbody>

                                            @foreach($permissions as $permission)

                                                <tr>

                                                    <td>{{$permission->id}}</td>
                                                    <td>{{$permission->display_name}}</td>
                                                    <td>{{$permission->route_name}}</td>
                                                    <td>{{$permission->icon_class}}</td>
                                                    <td>{{$permission->groupname}}</td>
                                                    <td>{{$permission->isdisplay}}</td>
                                                    <td>{{$permission->sort}}</td>

                                                 
                                                   
                                                    <td>

                                                        <div class="btn-group {{can('permission.edit')}}">
                                                            
                                                            <a class="btn-info btn btn-xs " href="{{ route('permission.edit',$permission->id)}}">Edit</a>
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


@section('scripts')

<script type="text/javascript">
    $(document).ready(function(){
            $('.dataTables-permission').DataTable({
                pageLength: 15,
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
