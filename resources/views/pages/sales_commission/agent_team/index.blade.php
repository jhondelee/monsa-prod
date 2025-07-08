@extends('layouts.app')

@section('pageTitle','Sales Commission')

@section('content')


        <div class="row wrapper border-bottom white-bg page-heading">

                <div class="col-lg-10">

                    <h2>Agent Team</h2>

                   <ol class="breadcrumb">
                        <li>
                            <a href="{{route('main')}}">Home</a>
                        </li>
                        <li class="active">
                            <strong>Team</strong>
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
                                <h5>Main Agent & Team List</h5>

                                 @if (!can('team.create'))
                                
                                    <div class="ibox-tools"> 

                                        <a href="{{route('team.create')}}" class="btn btn-primary btn-sm">
                                            <i class="fa fa-plus">&nbsp;</i>Add Agent Team
                                        </a> 
                                    
                                    </div>

                                @endif

                            </div>

                            <div class="ibox-content">
                              
                                <div class="table-responsive">
                                    
                                    <table class="table table-striped table-hover dataTables-area"data-toggle="dataTable" data-form="deleteForm" >
                                        <thead>
                                        <tr>

                                            <th>ID</th>
                                            <th>Main Agent</th>
                                            <th>Created At</th>
                                            <th class="text-center">Action</th>
                                           
                                        </tr>
                                        </thead>
                                        <tbody>

                                            @foreach($agents as $agent)

                                                <tr>

                                                    <td>{{$agent->id}}</td>
                                                    <td>{{$agent->main_agent}}</td>
                                                    <td>{{$agent->created_at}}</td>
                                                    <td class="text-center">
                                                        @if (!can('team.edit'))
                                                        <div class="btn-group">
                                                            <a class="btn-info btn btn-xs edit-modal" 
                                                            href="{{route ('team.edit', $agent->id) }}">
                                                            <i class="fa fa-pencil"></i></a>
                                                        </div>
                                                        @endif
                                                        @if (!can('team.delete'))
                                                        <div class="btn-group">
                                                                   <a class="btn-danger btn btn-xs delete"onclick="confirmDelete('{{$agent->id}}'); return false;"><i class="fa fa-trash"></i></a>
                                                        </div>
                                                        @endif
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
              $('.dataTables-area').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    //{ extend: 'copy'},
                    //{extend: 'csv'},
                    {extend: 'excel', title: 'Area'},
                    {extend: 'pdf', title: 'Area'},

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

        function confirmDelete(data,model) {   
         $('#confirmDelete').modal({ backdrop: 'static', keyboard: false })
            .on('click', '#delete-btn', function(){
                $(this).attr("disabled","disabled");
                document.location.href="/agent-team/delete/"+data;
            });
        }

</script>

@endsection