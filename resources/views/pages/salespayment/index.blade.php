@extends('layouts.app')

@section('pageTitle','Sales Payment')

@section('content')


        <div class="row wrapper border-bottom white-bg page-heading">

                <div class="col-lg-10">

                    <h2></h2>

                   <ol class="breadcrumb">
                        <li>
                            <a href="{{route('main')}}">Home</a>
                        </li>
                        <li class="active">
                            <strong>Sales Payment</strong>
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
                                <h5>Sales Collection</h5>
          
                                @if (!can('sales_payment.create'))

                                <div class="ibox-tools"> 

                                    <a href="{{route('sales_payment.create')}}" class="btn btn-primary btn-sm add-modal">
                                        <i class="fa fa-plus">&nbsp;</i>Add Payment
                                    </a> 
                                </div>
                                
                                @endif  

                            </div>

                            <div class="ibox-content">
                                    
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover dataTables-payments"data-toggle="dataTable" data-form="deleteForm" id="dTable-ItemList-table">
                                        <thead>
       
                                        </thead>
                                        <tbody>

                                                                
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

<script src="/js/plugins/footable/footable.all.min.js"></script>
<script src="/js/plugins/toastr/toastr.min.js"></script>
<script type="text/javascript">


    $(document).ready(function(){
        var _id = 0;
        $.ajax({
            url:  '{{ url('sales-payment/datatable') }}',
            type: 'POST',
            dataType: 'json',
            data: { _token: "{{ csrf_token() }}",
            id: _id},  
            success:function(results){

                $('#dTable-ItemList-table').DataTable({
                    destroy: true,
                    pageLength: 100,
                    responsive: true,
                    autoWidth: true,
                    fixedColumns: true,
                    data: results,
                    dom: '<"html5buttons"B>lTfgitp',
                    buttons: [],
                    columns: [
                        {data: 'id', title: 'Id'}, 
                        {data: 'so_number', title: 'SO Number'},  
                        {data: 'customer', title: 'Customer'},    
                        {data: 'payment_type', title: 'Payment Type'},
                        {data: 'sales_total', title: 'Total Amount'},
                        {data: 'payment_status', title: 'Status',
                            render: function(data, type, row){
                                if(row.payment_status=='Completed'){
                                    return '<label class="label label-success" >Completed</label>  '
                                }else{
                                    return '<label class="label label-danger" >Existing Balance</label>';
                                }   
                            }
                        },
                        {data: null, title: 'Action',
                            render: function(data, type, row){
                                if(row.payment_status=='Completed'){
                                    return '@if (!can('sales_payment.update'))<a href="/sales-payment/update/'+ row.id +'" class="btn-warning btn btn-xs"><i class="fa fa-money"></i></a>@endif';
                                }else{
                                    return '@if (!can('sales_payment.update'))<a href="/sales-payment/update/'+ row.id +'" class="btn-warning btn btn-xs"><i class="fa fa-money"></i></a>@endif&nbsp;@if (!can('sales_payment.delete'))\
                                    <a class="btn-primary btn btn-xs delete" id="delete-btn "onclick="confirmDelete('+ row.id +'); return false;"><i class="fa fa-trash"></i></a>@endif';
                                }

                            }
                        },
                         
                    ],
                })
            }
        }); 

    });


    function confirmDelete(data,model) {   
        $('#confirmDelete').modal({ backdrop: 'static', keyboard: false })
            .on('click', '#delete-btn', function(){
            $(this).attr("disabled","disabled");
            document.location.href="/sales-payment/delete/"+data;
        });
    }


        $(document).on('click', '.add-modal', function() {
            $('.modal-title').text('Add Area');
            $('#myModal').modal('show');
        });


        // Edit a post
        $(document).on('click', '.edit-modal', function() {
            $('.modal-title').text('Update Cost/Srp');
            $('#id_edit').val($(this).data('id'));
            $('#descript_edit').val($(this).data('descript'));
            $('#srp_edit').val($(this).data('srp'));
            $('#unit_cost_edit').val($(this).data('unit_cost'));
            $('#editModal').modal('show');
        });

    
    
</script>

@endsection