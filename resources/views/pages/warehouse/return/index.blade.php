@extends('layouts.app')

@section('pageTitle','Return')

@section('content')


	  <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Return Item</h2>
                    <ol class="breadcrumb">
                        <li class="active">
                             <a href="{{ route('main') }}">Home</a>
                        </li>
                        <li>
                            <strong><a href="#">Return</a></strong>
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
                                 
                                <div class="row m-t-lg">
                                    <div class="col-lg-12">
                                        <div class="tabs-container">
                                            <ul class="nav nav-tabs">

                                                @if (!can('returns.index'))
                                                <li class="active">
                                                    <a data-toggle="tab" href="#tab-1">
                                                    <i class="fa fa-trash">&nbsp;</i>Returns Items
                                                    </a>
                                                </li>
                                                @endif
                                                @if (!can('returns.index'))
                                                <li>
                                                    <a data-toggle="tab" href="#tab-2">
                                                    <i class="fa fa-retweet">&nbsp;</i>Returned To Supplier
                                                    </a>
                                                </li>
                                                @endif
                                            </ul>
                                            <div class="tab-content">
                                                    <div id="tab-1" class="tab-pane active">
                                                        <div class="panel-body">
                                                           <div class="table-responsive" >
                                                            
                                                            @include('pages.warehouse.return.return_item') 
                                                            
                                                            </div>
                                                        </div>
                                                    </div>
                                                @if (!can('returns.index'))
                                                <div id="tab-2" class="tab-pane">
                                                    <div class="panel-body">
                                                        <div class="table-responsive" >

                                                            @include('pages.warehouse.return.return_supplier')
                                                       
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
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



@section('scripts')


<link href="/css/plugins/select2/select2.min.css" rel="stylesheet">
<script src="/js/plugins/toastr/toastr.min.js"></script>
<script type="text/javascript">

        $(document).ready(function(){
              $('.dataTables-items').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    //{ extend: 'copy'},
                    //{extend: 'csv'},
                    //{extend: 'excel', title: 'Farm List'},
                    {extend: 'pdf', title: 'Procurement'},

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

        $(document).ready(function(){
              $('.dataTables-trasfer').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    //{ extend: 'copy'},
                    //{extend: 'csv'},
                    //{extend: 'excel', title: 'Farm List'},
                    {extend: 'pdf', title: 'Procurement'},

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
                document.location.href="/warehouse/deduct/"+data;
            });
        }



        function confirmDeleteOrder(data,model) {  
         $('#confirmDelete').modal({ backdrop: 'static', keyboard: false })
            .on('click', '#delete-btn', function(){
                $(this).attr("disabled","disabled");
                document.location.href="/transfer/delete/"+data;
            });
        }

        
        $(document).on('click', '.add-inventory-item', function() {
           $('.modal-title').text('Add Inventory Item');
           $('#myModal').modal('show');
        });   
        
        //
        $(document).on('click', '#rtn-supplier', function() {
            var _id = $(this).data('rtn_id');
                
                //get-supplier
                $.ajax({
                    url:  '{{ url('inventory/get-item-to-supplier') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: { _token: "{{ csrf_token() }}",
                    id: _id}, 
                    success:function(results){

                        $('.supplier_id').val( results.id ).trigger("chosen:updated");

                    }
                });

            //suppliers
           $('.modal-title').text('Return to Supplier');
           $('#item_rtn_id').val( $(this).data('rtn_id') );
           $('#inven_id').val($(this).data('rtn_invenid') );
           $('.item_return_to_supplier').val( $(this).data('rtn_name') );
           $('.unti_qty').val( $(this).data('rtn_unit') );
           $('.return_date').val( $(this).data('rtn_date') );
           $('.location').prop('disabled', true);
           $('.location').val( $(this).data('rtn_loc') ).trigger("chosen:updated");
           $('.return_by').val( $(this).data('userid') ).trigger("chosen:updated");
           $('#myModalsupplier').modal('show');

        });  

        //
        $(document).on('click', 'rtn-inventory', function() {
           $('.modal-title').text('Return to Inventory');
           $('#myModalRtnInventory').modal('show');
        }); 

        $('#inventory_item_id').change(function(){
            var id = $(this).val();
            var _item_name = $('#inventory_item_id option:selected').text();
                
                $.ajax({
                    url:  '{{ url('inventory/iteminfo') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: { _token: "{{ csrf_token() }}",
                    id: id}, 
                    success:function(results){

                        $('#unit_price').val(results.unit_cost);

                            toastr.info(results.description ,'Unit Cost')
                    }
                });
        })
        
        //deduct to inventory
        $(document).on('click', '.deduct-inventory-item', function() {
           $('.modal-title').text('Deduct Inventory Item');
           $('.source_deduct').val($(this).data('')).trigger("chosen:updated");
           $('.items_deduct').val($(this).data('')).trigger("chosen:updated");
           $('#deduct-Modal').modal('show');
        });  




                $('.chosen-select').chosen({width: "100%"});
                $('#source_deduct').change(function(){ 
                  var _source_deduct = $(this).val();
                  
                    $.ajax({
                        url:  '{{ url('inventory-deduct/source') }}',
                        type: 'POST',
                        dataType: 'json',
                        data: { _token: "{{ csrf_token() }}",
                        loc: _source_deduct}, 
                        success:function(results){
                          
                            $('div #item-option').empty();
                            $('div #item-option').append('<select name="items_deduct" id="items_deduct" class="form-control chosen-select"></select>');

                            $('#items_deduct').empty();
                            $("#items_deduct").append('<option>Please choose items</option>');
                            for(var i = 0 ; i <= results.length ; i++ ) {
                                $("#items_deduct").append('<option value='+results[i].id+'>'+results[i].name+'</option>');
                            }
                            $('#items_deduct').chosen();
                            $('.chosen-select').chosen({width: "100%"});
                        }
                    });
                    
                });

        function confirmDelete(data,model) {   
         $('#confirmDelete').modal({ backdrop: 'static', keyboard: false })
            .on('click', '#delete-btn', function(){
                $(this).attr("disabled","disabled");
                document.location.href="/returns/delete/"+data;
            });
        }

               $(document).ready(function(){
              $('.dataTables-return').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                order: [ [0, 'desc'] ],
                buttons: [
                    //{ extend: 'copy'},
                    //{extend: 'csv'},
                    //{extend: 'excel', title: 'Farm List'},
                    {extend: 'pdf', title: 'Procurement'},

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