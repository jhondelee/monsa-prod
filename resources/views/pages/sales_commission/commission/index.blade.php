@extends('layouts.app')

@section('pageTitle','Sales Commission')

@section('content')


        <div class="row wrapper border-bottom white-bg page-heading">

                <div class="col-lg-10">

                    <h2>Commission</h2>

                   <ol class="breadcrumb">
                        <li>
                            <a href="{{route('main')}}">Home</a>
                        </li>
                        <li class="active">
                            <strong>Commissions</strong>
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
                                <h5>Agent Commission List</h5>
                                 @if (!can('commission.create'))
                                <div class="ibox-tools"> 
                                    <a href="{{route('commission.create')}}" class="btn btn-primary btn-sm">
                                        <i class="fa fa-plus">&nbsp;</i>Agent Commission
                                    </a> 
                                </div>
                                             
                                @endif

                            </div>

                            <div class="ibox-content">
                              
                                <div class="table-responsive">
                                    
                                    <table class="table table-striped table-hover dataTables-rate"data-toggle="dataTable" data-form="deleteForm" >
                                        <thead>
                                        <tr>

                                            <th>ID</th>
                                            <th>Agent</th>
                                            <th>From Date</th>
                                            <th>To Date</th>
                                            <th>Total Sales</th>
                                            <th>Created At</th>
                                            <th class="text-center">Action</th>
                                           
                                        </tr>
                                        </thead>
                                        <tbody>

                                            @foreach($agentcommissions as $agentcommission)

                                                <tr>

                                                    <td>{{$agentcommission->id}}</td>
                                                    <td>{{$agentcommission->sub_agent}}</td>
                                                    <td>{{$agentcommission->from_date}}</td>
                                                    <td>{{$agentcommission->to_date}}</td>
                                                    <td>{{$agentcommission->total_sales}}</td>
                                                    <td>{{$agentcommission->created_at}}</td>
                                                    <td class="text-center">
                                                        @if (!can('commission.edit'))
                                                        <div class="btn-group">
                                                            <a class="btn-info btn btn-xs edit-modal" 
                                                            href="{{route ('commission.edit', $agentcommission->id) }}">
                                                            <i class="fa fa-pencil"></i></a>
                                                        </div>
                                                        @endif
                                                        @if (!can('commission.delete'))
                                                        <div class="btn-group">
                                                                   <a class="btn-danger btn btn-xs delete"onclick="confirmDelete('{{$agentcommission->id}}'); return false;"><i class="fa fa-trash"></i></a>
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
<script src="js/plugins/jquery-ui/jquery-ui.min.js"></script>

<script type="text/javascript">
        $(document).ready(function(){
              $('.dataTables-rate').DataTable({
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
                document.location.href="/agent-commission/delete/"+data;
            });
        }

        $(document).on('click', '.add-modal', function() {
            $('.modal-title').text('Generate Sales Commission');
            $('#myModal').modal('show');
        });

        // Edit a post
        $(document).on('click', '.edit-modal', function() {
            $('.modal-title').text('Edit Rate');
            $('#id_edit').val($(this).data('id'));
            $('#rate_edit').val($(this).data('rate'));
            $('#editModal').modal('show');
        });


</script>

@endsection