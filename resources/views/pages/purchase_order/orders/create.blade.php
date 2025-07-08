    
@extends('layouts.app')

@section('pageTitle','Orders')

@section('content')


  <div class="row wrapper border-bottom white-bg page-heading">

            <div class="col-lg-10">

            <h2>Purchase Order</h2>

                <ol class="breadcrumb">
                    <li>

                        Home

                    </li>

                    <li class="active">

                        <strong>Orders</strong>

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
                            <h5>Create Order</h5>

                           
                        </div>
                        
                        <div class="ibox-content">
                            
                            <div class="form-horizontal m-t-md">

                            {!! Form::open(array('route' => array('order.store','method'=>'POST'),'id'=>'orders_form')) !!}
                                   
                                @include('pages.purchase_order.orders._form_add')
                                     
                            {!! Form::close() !!} 

                            

                        </div>
                    </div>
                </div>
            </div>
        </div>     

        @include('pages.purchase_order.orders.add_item')


@endsection



@section('scripts')

<script src="/js/plugins/toastr/toastr.min.js"></script>

<script type="text/javascript">


 $(document).ready(function(){
    
        $('.dataTables-add-items').DataTable({
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

    $('#discount').val('0.00');
    $('#total_amount').val('0.00');

    //Compute Price * Quanity = Total
     $('#dTable-selected-item-table').on('keyup','.quantity',function(e){
        //compute price
        var _price = parseFloat($(this).closest( 'tr ').find( '#price' ).val());
        var _quantity = parseFloat($(this).closest( 'tr' ).find( '#quantity' ).val());
        var _sub_amount = 0.00;

           if (isNaN(_price)){
                var _sub_amount =0.00;
            }else{
                var _sub_amount = ( _price * _quantity );
            }

            _sub_amount = _sub_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            $(this).closest('tr').find('#amount').val( _sub_amount );

                // sum of price
                var _total_amount = 0.00;
                $( "#dTable-selected-item-table tbody > tr" ).each( function() {
                        var $row = $( this );        
                        var _subtotal = $row.find( ".amount" ).val();
    
                        _total_amount += parseFloat( ('0' + _subtotal).replace(/[^0-9-\.]/g, ''), 10 );
                       
                });

                 _total_amount = _total_amount.toFixed(2);
                $('input[name="grand_total"]').val(  _total_amount  );
  
        }); 

        // Compute Discount
        $(document).on('keyup', '#discount', function() {
            var _total_amount = parseFloat($( '#grand_total' ).val());
            var _discount = parseFloat($( '#discount' ).val());
            var _total_discounted = 0.00;

            if (isNaN(_discount)){
                var _total_amount = 0.00;
                $( "#dTable-selected-item-table tbody > tr" ).each( function() {
                        var $row = $( this );        
                        var _subtotal = $row.find( ".amount" ).val();
    
                        _total_amount += parseFloat( ('0' + _subtotal).replace(/[^0-9-\.]/g, ''), 10 );
                       
                });

                 _total_amount = _total_amount.toFixed(2);
                $('input[name="grand_total"]').val(  _total_amount  );
            }else{
                var _total_discounted = ( _total_amount - _discount );

                _total_discounted = _total_discounted.toFixed(2);
                $('input[name="grand_total"]').val(  _total_discounted  );

            }


        });


       $(document).on('click', '.btn-show-item', function() {
            var id = $('#supplier_id').val();
                $('.modal-title').text('Add Item');
                $('#myModal').modal('show'); 
        });

        
        $("#remove-row").click(function(){
            $("table tbody").find('input[name="remove"]').each(function(){
                if($(this).is(":checked")){
                    $(this).parents("tr").remove();
                }
            });
        });

         $(".btn-remove").click(function(){
            $("table tbody").find('input[name="remove"]').each(function(){
                if($(this).is(":checked")){
                    $(this).parents("tr").remove();
                }
            });
        });


        $('.date').datepicker({
            autoclose: true,
            format:'yyyy-mm-dd'
         });

        $(".btn-remove").click(function(){

            $("table tbody").find('input[name="remove"]').each(function(){
                var _total_amount = 0.00;
                $( "#dTable-selected-item-table tbody > tr" ).each( function() {
                    var $row = $( this );        
                    var _subtotal = $row.find( ".amount" ).val();
    
                    _total_amount += parseFloat( ('0' + _subtotal).replace(/[^0-9-\.]/g, ''), 10 );     
                });
                    _total_amount = _total_amount.toFixed(2);
                    $('input[name="grand_total"]').val(  _total_amount  );
                });
            });

 
        $('.chosen-select').chosen({width: "100%"});


        function confirmAddItem(data) {   
            var id = data;
            $.ajax({
            url:  '{{ url('order/getitems') }}',
            type: 'POST',
            dataType: 'json',
            data: { _token: "{{ csrf_token() }}",
            id: id}, 
            success:function(results){
                                               
                $('#dTable-selected-item-table tbody').append("<tr><td><input type='text' name='item_id[]' class='form-control input-sm text-center item_id' required=true size='4'  value="+ results.id +" readonly></td>\
                        <td>"+ results.name +"</td>\
                        <td>"+ results.description +"</td>\
                        <td>"+ results.units +"</td>\
                        <td>\
                        <input type='text' name='quantity[]' class='form-control input-sm text-center quantity' required=true size='4'  placeholder='0.00'  id ='quantity'>\
                        </td>\
                         <td>\
                        <input type='text' name='unit_cost[]' class='form-control input-sm text-center price' required=true size='4'  value="+ results.unit_cost +"  id ='price'>\
                        </td>\
                        <td>\
                        <input type='text' name='total_amount[]' class='form-control input-sm text-right amount' required=true size='4'  placeholder='0.00'  id ='amount' readonly>\
                        </td>\
                        <td style='text-align:center;'>\
                            <div class='checkbox checkbox-success'>\
                                <input type='checkbox' name='remove'><label for='remove'></label>\
                            </div>\
                        </td>\
                    </tr>"); 

                    toastr.success(results.description +' has been added','Success!')
                }
            })
        }


        $(document).ready(function(){
            $('#btn-close').on('click', function(){
                document.location.href="/order"; 
            });
        });

        function submit_validate() {
            var ctr = $('#dTable-selected-item-table>tbody>tr').length;
            if (ctr > 0){
                $('#orders_form').submit();
            }else{
                toastr.warning('No Items to be save!','Invalid!')
            }
         }


</script>

@endsection
