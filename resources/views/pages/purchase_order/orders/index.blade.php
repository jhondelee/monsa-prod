@extends('layouts.app')

@section('pageTitle','Orders')

@section('content')



         <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Purchase Order</h2>
                    <ol class="breadcrumb">
                        <li class="active">
                             <a href="{{ route('main') }}">Home</a>
                        </li>
                        <li>
                            <strong><a href="#">Orders</a></strong>
                        </li>
                    
                    </ol>
                </div>
        </div>

        @include('layouts.alert')
        @include('layouts.deletemodal')    

        <div class="wrapper wrapper-content animated fadeInRight ecommerce">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox float-e-margins">
                            <div class="ibox-content" >
                                 @if (!can('order.create'))
                                   <a href="{{route('order.create')}}" class="btn btn-warning btn-sm">
                                        <i class="fa fa-plus">&nbsp;</i>Create Order
                                    </a>
                                 @endif
                                <div class="row m-t-lg">
                                    <div class="col-lg-12">
                                        <div class="tabs-container">
                                            <ul class="nav nav-tabs">
                                                <li class="active">
                                                    <a data-toggle="tab" href="#tab-1">
                                                    <i class="fa fa-ticket">&nbsp;</i>Processing
                                                    <span class="label label-warning"></span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a data-toggle="tab" href="#tab-2">
                                                    <i class="fa fa-exclamation-circle">&nbsp;</i>Posted
                                                    <span class="add-modal label label-warning"></span>
                                                    
                                                    </a>
                                                </li>
                                                <li>
                                                    <a data-toggle="tab" href="#tab-3">
                                                    <i class="fa fa-ban">&nbsp;</i>Canceled
                                                     
                                                    <span class="add-modal label label-warning"></span>
                                                    
                                                    </a>
                                                </li>
                                                <li>
                                                    <a data-toggle="tab" href="#tab-4">
                                                    <i class="fa fa-suitcase">&nbsp;</i>Closed
                                                     
                                                    <span class="add-modal label label-warning"></span>
                                                    
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div id="tab-1" class="tab-pane active">
                                                    <div class="panel-body">
                                                       <div class="table-responsive" >
                                    
                                                        @include('pages.purchase_order.orders.po_list') 

                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="tab-2" class="tab-pane">
                                                    <div class="panel-body">
                                                        <div class="table-responsive" >
                                                        
                                                        @include('pages.purchase_order.orders.posted_list')
                                                                                                           
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="tab-3" class="tab-pane">
                                                    <div class="panel-body">
                                                        <div class="table-responsive" >
                                                        
                                                        @include('pages.purchase_order.orders.cancel_list')
                                                        

                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="tab-4" class="tab-pane">
                                                    <div class="panel-body">
                                                        <div class="table-responsive" >
                                                        
                                                        @include('pages.purchase_order.orders.closed_list')
                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



@endsection

@section('styles')
<link href="/css/plugins/footable/footable.core.css" rel="stylesheet">
@endsection

@section('scripts')

<script src="/js/plugins/footable/footable.all.min.js"></script>

<script type="text/javascript">

    $(document).ready(function(){
              $('.dataTables-po').DataTable({
                pageLength: 10,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    //{ extend: 'copy'},
                    //{extend: 'csv'},
                    {extend: 'excel', title: 'ExampleFile'},
                    {extend: 'pdf', title: 'Inventory List'},

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

    

    
     function confirmCancel(data,model) {   
         $('#confirmCancel').modal({ backdrop: 'static', keyboard: false })
            .on('click', '#cancel-btn', function(){
                $(this).attr("disabled","disabled");
                document.location.href="/order/cancel/"+data;
            });
        }


    function confirmDelete(data,model) {   
         $('#confirmDelete').modal({ backdrop: 'static', keyboard: false })
            .on('click', '#delete-btn', function(){
                $(this).attr("disabled","disabled");
                document.location.href="/order/delete/"+data;
            });
    }
        
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

        $(document).on('click', '.add-modal', function() {
            $('.modal-title').text('Receive Item');
            $('#myModal').modal('show');
        });


</script>

@endsection