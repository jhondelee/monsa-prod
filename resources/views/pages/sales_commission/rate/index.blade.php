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
                            <strong>Rate List</strong>
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
                                <h5>Rate List</h5>
                                 @if (!can('commission_rate.create'))
                                <div class="ibox-tools"> 
                                    <a class="btn btn-primary btn-sm add-modal">
                                        <i class="fa fa-plus">&nbsp;</i> Rate
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
                                            <th>Rate</th>
                                            <th>Created At</th>
                                            <th class="text-center">Action</th>
                                           
                                        </tr>
                                        </thead>
                                        <tbody>

                                            @foreach($rates as $rate)

                                                <tr>

                                                    <td>{{$rate->id}}</td>
                                                    <td>{{$rate->rate}}</td>
                                                    <td>{{$rate->created_at}}</td>
                                                    <td class="text-center">
                                                        @if (!can('commission_rate.edit'))
                                                        <div class="btn-group">
                                                            <a class="btn-info btn btn-xs edit-modal" 
                                                            data-id="{{$rate->id}}"
                                                            data-rate="{{$rate->rate}}">
                                                            <i class="fa fa-pencil"></i></a>
                                                        </div>
                                                        @endif
                                                        @if (!can('commission_rate.delete'))
                                                        <div class="btn-group">
                                                                   <a class="btn-danger btn btn-xs delete"onclick="confirmDelete('{{$rate->id}}'); return false;"><i class="fa fa-trash"></i></a>
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

            @include('pages.sales_commission.rate.create')
            @include('pages.sales_commission.rate.edit')

            
          
@endsection


@section('scripts')

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
                document.location.href="/commission-rate/delete/"+data;
            });
        }

        $(document).on('click', '.add-modal', function() {
            $('.modal-title').text('Add Rate');
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