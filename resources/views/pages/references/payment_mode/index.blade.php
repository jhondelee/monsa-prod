@extends('layouts.app')

@section('pageTitle','Mode of Payment')

@section('content')


	    <div class="row wrapper border-bottom white-bg page-heading">

                <div class="col-lg-10">

                    <h2>References</h2>

                   <ol class="breadcrumb">
                        <li>
                            <a href="{{route('main')}}">Home</a>
                        </li>
                        <li class="active">
                            <strong>Mode of Payments</strong>
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
                                <h5>Mode of Payment</h5>
                                 @if (!can('payment_mode.create'))
                                <div class="ibox-tools"> 
                                    <a class="btn btn-primary btn-sm add-modal">
                                        <i class="fa fa-plus">&nbsp;</i> Payment Mode
                                    </a> 
                                </div>
                                @endif

                            </div>

                            <div class="ibox-content">
                              
                                <div class="table-responsive">
                                    
                                    <table class="table table-striped table-hover dataTables-farms"data-toggle="dataTable" data-form="deleteForm" >
                                        <thead>
                                        <tr>

                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Created by</th>
                                            <th>Created At</th>
                                            <th class="text-center">Action</th>
                                           
                                        </tr>
                                        </thead>
                                        <tbody>

                                            @foreach($paymentmodes as $paymentmode)

                                                <tr>

                                                    <td>{{$paymentmode->id}}</td>
                                                    <td>{{$paymentmode->name}}</td>
                                                    <td>{{$paymentmode->created_by}}</td>
                                                    <td>{{$paymentmode->created_at}}</td>
                                                    <td class="text-center">
                                                        @if (!can('payment_mode.edit'))
                                                        <div class="btn-group">
                                                            <a class="btn-info btn btn-xs edit-modal" 
                                                            data-id="{{$paymentmode->id}}"
                                                            data-name="{{$paymentmode->name}}"><i class="fa fa-pencil"></i></a>
                                                        </div>
                                                        @endif
                                                        @if (!can('payment_mode.delete'))
                                                        <div class="btn-group">
                                                                   <a class="btn-danger btn btn-xs delete"onclick="confirmDelete('{{$paymentmode->id}}'); return false;"><i class="fa fa-trash"></i></a>
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

            @include('pages.references.payment_mode.create')
            @include('pages.references.payment_mode.edit')

            
          
@endsection


@section('scripts')

<script type="text/javascript">
        $(document).ready(function(){
              $('.dataTables-farms').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    //{ extend: 'copy'},
                    //{extend: 'csv'},
                    {extend: 'excel', title: 'Mode Of Payment'},
                    {extend: 'pdf', title: 'Mode Of Payment'},

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
                document.location.href="/mode-of-payment/delete/"+data;
            });
        }

        $(document).on('click', '.add-modal', function() {
            $('.modal-title').text('Add Mode Of Payment');
            $('#myModal').modal('show');
        });

        // Edit a post
        $(document).on('click', '.edit-modal', function() {
            $('.modal-title').text('Edit  Mode Of Payment');
            $('#id_edit').val($(this).data('id'));
            $('#name_edit').val($(this).data('name'));
            $('#editModal').modal('show');
        });


</script>

@endsection