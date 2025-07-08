    
@extends('layouts.app')

@section('pageTitle','Incoming')

@section('content')


  <div class="row wrapper border-bottom white-bg page-heading">

            <div class="col-lg-10">

            <h2>Incoming Item</h2>

                <ol class="breadcrumb">
                    <li>

                        Home

                    </li>

                    <li class="active">

                        <strong>Incoming</strong>

                    </li>
                                      
                </ol>

            </div>

        </div>
       @include('layouts.alert')
       @include('layouts.deletemodal')

        <div class="wrapper wrapper-content  animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                                     
                            <h4>Incoming Item</h4> 
                            
                        </div>
                        
                        <div class="ibox-content">

                             @if (!can('incoming.post'))

                                @if ($incomings->status == 'RECEIVING')

                                    <div class="btn-group">
                                         <button type="button" class="btn btn-success" onclick="confirmPost('{{$incomings->id}}'); return false;" id="post-btn"><i class="fa fa-check">&nbsp;</i>Post&nbsp; </button>
                                    </div>

                                @endif

                            @endif

                            <a href="{{route('incoming.print',$incomings->id)}}" class="btn btn-primary btn-print"><i class="fa fa-print">&nbsp;</i>Print</a> 

                     
                            
                            <div class="form-horizontal m-t-md">

                            {!! Form::model($incomings, ['route' => ['incoming.update', $incomings->id]]) !!}

                                @include('pages.purchase_order.incoming._form')
                                  
                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
            </div>
        </div>     

@include('pages.purchase_order.incoming.add_item')

@endsection



@section('scripts')

<script src="/js/plugins/typehead/bootstrap3-typeahead.min.js"></script>

<script src="/js/plugins/toastr/toastr.min.js"></script>

<script type="text/javascript">


 $(document).ready(function(){
    
        $('.dTable-receive-item-table').DataTable({
                pageLength: 10,
                responsive: true,
            
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    //{ extend: 'copy'},
                    //{extend: 'csv'},
                    //{extend: 'excel', title: 'ExampleFile'},
                    //{extend: 'pdf', title: 'Inventory List'},

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
 

        $('.date').datepicker({
            autoclose: true,
            format:'yyyy-mm-dd'
         });

        $('.chosen-select').chosen({width: "100%"});

        $(document).ready(function(){
            $('#btn-close').on('click', function(){
                document.location.href="/incoming"; 
            });
        });

       
        // compute total by input in received quanity
        $('#dTable-receive-item-table').on('keyup','._received_qty',function(e){
        //compute price
        var _price = parseFloat($(this).closest( 'tr ').find( '#_item_unit_cost' ).val());
        var _quantity = parseFloat($(this).closest( 'tr' ).find( '#_received_qty' ).val());
        var _sub_amount = 0.00;

           if (isNaN(_price)){
                var _sub_amount =0.00;
            }else{
                var _sub_amount = ( _price * _quantity );
            }

            _sub_amount = _sub_amount.toFixed(2);
            $(this).closest('tr').find('#_total_amount').val( _sub_amount );

                // sum of price
                var i_total_amount = 0.00;
                $( "#dTable-receive-item-table tbody > tr" ).each( function() {
                        var $row = $( this );        
                        var _subtotal = $row.find( "._total_amount" ).val();
    
                        i_total_amount += parseFloat( ('0' + _subtotal).replace(/[^0-9-\.]/g, ''), 10 );
                       
                });

                 i_total_amount = i_total_amount.toFixed(2);
                $('input[name="grand_total_amount"]').val(  i_total_amount  );
 
        }); 

        // compute total by input in unitcost
        $('#dTable-receive-item-table').on('keyup','._item_unit_cost',function(e){
        //compute price
        var _price = parseFloat($(this).closest( 'tr ').find( '#_item_unit_cost' ).val());
        var _quantity = parseFloat($(this).closest( 'tr' ).find( '#_received_qty' ).val());
        var _sub_amount = 0.00;

           if (isNaN(_price)){
                var _sub_amount =0.00;
            }else{
                var _sub_amount = ( _price * _quantity );
            }

            _sub_amount = _sub_amount.toFixed(2);
            $(this).closest('tr').find('#_total_amount').val( _sub_amount );

                // sum of price
                var i_total_amount = 0.00;
                $( "#dTable-receive-item-table tbody > tr" ).each( function() {
                        var $row = $( this );        
                        var _subtotal = $row.find( "._total_amount" ).val();
    
                        i_total_amount += parseFloat( ('0' + _subtotal).replace(/[^0-9-\.]/g, ''), 10 );
                       
                });

                 i_total_amount = i_total_amount.toFixed(2);
                $('input[name="grand_total_amount"]').val(  i_total_amount  );
  
        }); 

        // allow only numeric with decimal
        $("._received_qty").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
         $(this).val($(this).val().replace(/[^0-9\.]/g,''));
                if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }
        });

        
         $(document).on("keyup", "._discount_input", function () {

                        if (var_discount == 0 || var_discount == null){

                            var i_total_amount = 0;

                            $( "#dTable-receive-item-table tbody > tr" ).each( function() {
                                    var $row = $( this );        
                                    var _subtotal = $row.find( "._total_amount" ).val();
                
                                    i_total_amount += parseFloat( ('0' + _subtotal).replace(/[^0-9-\.]/g, ''), 10 );
                                   
                            });

                             i_total_amount = i_total_amount.toFixed(2);
                            $('input[name="grand_total_amount"]').val(  i_total_amount  );

                        }   
      
                var var_discount = $( "._discount_input" ).val();
                var var_gtotal = $( "._grand_total_amount" ).val();

                //var_discount =parseFloat( ('0' + var_discount).replace(/[^0-9-\.]/g, ''), 10 );
                //var_gtotal =parseFloat( ('0' + var_gtotal).replace(/[^0-9-\.]/g, ''), 10 );

                var var_Gtotal_amount = 0.00;
           
                    var_Gtotal_amount = var_gtotal - var_discount;

                    var_Gtotal_amount = var_Gtotal_amount.toFixed(2);
                    
                    $('input[name="grand_total_amount"]').val(  var_Gtotal_amount  );


              
            });


        function confirmPost(data,model) {   
         $('#confirmPost').modal({ backdrop: 'static', keyboard: false })
            .on('click', '#post-btn', function(){
                $(this).attr("disabled","disabled");
                document.location.href="/incoming/post/"+data;
            });
        }


    $(document).on('click', '.btn-show-item', function() {
            var id = $('#supplier_id').val();
            var sup_name = $('#supplier_id :selected').text();

            $('.modal-title').text('Add Item');
            $('#myModal').modal('show'); 

            $(function() {
            $.ajax({
                url:  '{{ url("order/orderToSupplier") }}',
                type: 'POST',
                dataType: 'json',
                data: { _token: "{{ csrf_token() }}",
                id: id}, 
                success:function(results){
                   
                    $('#dTable-ItemList-table').DataTable({
                        destroy: true,
                        pageLength: 100,
                        responsive: true,
                        fixedColumns: true,
                        autoWidth: true,
                        data: results,
                        dom: '<"html5buttons"B>lTfgitp',
                        buttons: [],
                        columns: [
                            {data: id ,title: 'Id', 
                                render: function(data,type,row){
                                return '<input type="text" name="item_id[]" class="form-control input-sm text-center item_id" size="4"  readonly="true" id ="item_id" value="'+ row.id +'">';
                                }
                            },  
                            {data: 'description', title: 'Description',
                                    render: function(data, type, row){
                                        if(row.free=='1'){
                                            return row.description +'  <label class="label label-danger" >Free</label> '
                                        }else{
                                            return row.description +'<label class="label label-warning" ></label>';
                                        }   
                                    }

                            },                               
                            {data: 'units', title: 'Units'},
                            {data: 'status', title: 'Status',
                                render: function(data, type, row){
                                    if(row.status=='In Stock'){
                                        return '<label class="label label-success" >In Stock</label>  '
                                    }else{
                                        if(row.status=='Reorder'){
                                            return '<label class="label label-warning" >Reorder</label>'
                                        }else{
                                            return '<label class="label label-danger" >Critical</label>';
                                        }
                                        
                                    }   
                                }
                            },
                            {data: 'id', title: 'Action',
                                render: function(data,type,row) {
                                     return '<a class="btn-primary btn btn-xs btn-add-items" onclick="confirmAddItem('+ row.id +'); return false;"><i class="fa fa-plus"></i></a>';
                                }
                            }
                            ]
                    });
                }
            });
        });
    });

  function confirmAddItem(data) {   
            var id = data;
            $.ajax({
            url:  '{{ url("order/getitems") }}',
            type: 'POST',
            dataType: 'json',
            data: { _token: "{{ csrf_token() }}",
            id: id}, 
            success:function(results){
                var _free="";
                var _unitCost= results.unit_cost;
                if(results.free =='1'){
                    _free = ' <label class="label label-danger " >FREE</label>';
                    _unitCost = 0;
                }

                $('#dTable-receive-item-table tbody').append("<tr><td><input type='text' name='item_id[]' class='form-control input-sm text-center item_id' required=true size='4'  value="+ results.id +" readonly></td>\
                        <td>"+ results.description +" "+ _free + "</td>\
                        <td class='text-left'>"+ results.units +"</td>\
                        <td>\
                        <input type='text' name='quantity[]' class='form-control input-sm text-center quantity' required=true size='4'   value='0'  id ='quantity'>\
                        </td>\
                         <td>\
                        <input type='text' name='received_qty[]' class='form-control input-sm text-center _received_qty' required=true size='4'  placeholder='0.00'  id ='_received_qty'>\
                        </td>\
                         <td>\
                        <input type='text' name='item_unit_cost[]' class='form-control input-sm text-right _item_unit_cost' size='4'  placeholder='0.00'  id ='_item_unit_cost' value ="+ _unitCost + ">\
                        </td>\
                        <td>\
                        <input type='text' name='total_amount[]' class='form-control input-sm text-right _total_amount' required=true size='4'  placeholder='0.00'  id ='_total_amount'>\
                        </td>\
                    </tr>"); 

                    toastr.success(results.description +' has been added','Success!')
                }
            })
        }

   
</script>

@endsection
