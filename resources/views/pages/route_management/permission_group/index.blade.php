@extends('layouts.app')

@section('pageTitle','Groups')

@section('content')



         <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Route Management</h2>
                    <ol class="breadcrumb">
                        <li class="active">
                             <a href="{{route('main')}}">Home</a>
                        </li>
                         <li>
                            <strong> Group List</strong>
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

                                <h5>Group List</h5>

                                <div class="ibox-tools {{can('pgroup.create')}}" > 

                                        <a class="btn btn-w-m btn-primary btn-sm" href="{{ route('pgroup.create') }}">
                                            <i class="fa fa-plus">&nbsp;</i>Add Group
                                        </a> 
                                        
                                </div>
                                 

                            </div>
   

                            <div class="ibox-content" >
                              
                                <div class="table-responsive" >
                                    
                                    <table class="table table-striped table-hover dataTables-example" data-toggle="dataTable" data-form="deleteForm" >
                                        <thead>
                                        <tr>

                                            <th>ID</th>
                                            <th>Display Name</th>
                                            <th>Icon</th>
                                            <th>Order</th>
                                            <th>Action</th>
                                           
                                        </tr>
                                        </thead>
                                        <tbody>

                                            @foreach($pgroups as $pgroup)

                                                <tr>

                                                    <td>{{$pgroup->id}}</td>
                                                    <td>{{$pgroup->name}}</td>
                                                    <td>{{$pgroup->icon_class}}</td>
                                                    <td>{{$pgroup->sort}}</td>
                                                 
                                                   
                                                    <td>

                                                        <div class="btn-group {{can('pgroup.edit')}}">
                                                            
                                                            <a class="btn-info btn btn-xs " href="{{ route('pgroup.edit',$pgroup->id)}}">Edit</a>
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