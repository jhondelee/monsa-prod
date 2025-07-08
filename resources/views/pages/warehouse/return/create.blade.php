    
@extends('layouts.app')

@section('pageTitle','Return')

@section('content')


  <div class="row wrapper border-bottom white-bg page-heading">

            <div class="col-lg-10">

            <h2>Return Item</h2>

                <ol class="breadcrumb">
                    <li>

                        Home

                    </li>

                    <li class="active">

                        <strong>Return</strong>

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
                                     
                            <h4>Search SO</h4> 
                            
                        </div>
                        
                        <div class="ibox-content">
                            
                            <div class="form-horizontal m-t-md">

                            {!! Form::open(array('route' => array('returns.store','method'=>'POST'))) !!}

                                @include('pages.warehouse.return._form_add')
                                  
                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
            </div>
        </div>     


@endsection



@section('scripts')

<script src="/js/plugins/typehead/bootstrap3-typeahead.min.js"></script>

<script src="/js/plugins/toastr/toastr.min.js"></script>

<script type="text/javascript">


   
        $('.date').datepicker({
            autoclose: true,
            format:'yyyy-mm-dd'
         });

        $('.chosen-select').chosen({width: "100%"});

        $(document).ready(function(){
            $('#btn-close').on('click', function(){
                document.location.href="/returns"; 
            });
        });

    
 
      $(document).ready(function(){
            $('#btn-search').on('click',function(){
                var so_num = $('#search').val();
             

                if(!so_num){

                    toastr.error('Please select SO Number','Invalid')
                    return false;

                } else {

      
                    $.ajax({
                    url:  '{{ url("returns/show-so") }}',
                    type: 'POST',
                    dataType: 'json',
                    data: { _token: "{{ csrf_token() }}",
                    so_num: so_num}, 
                    success:function(results){
                       
                        $('#dTable-return-item-table tbody').empty();
                        toastr.warning('SO# '+ results.so.so_number,'Shown')
                        $('#lbl_so_number').val(  results.so.so_number  );
                        $('#lbl_so_date').val(  results.so.so_date  );
                        $('#lbl_customer').val( results.customer.name );
                        $('#location').val( results.so.location ).trigger("chosen:updated");

                            for(var i=0 ;i<=results.soitems.length;i++) {
                                $('#dTable-return-item-table tbody').append("<tr><td><input type='text' name='return_id[]' class='form-control input-sm text-center return_id' size='3'  value="+ results.soitems[i].id +" readonly/></td>\
                                    <td>"+ results.soitems[i].description +"</td><td>"+ results.soitems[i].unit +"</td><td><input type='text' name='order_quantity[]' class='form-control input-sm text-center item_quantity' size='4' value="+ results.soitems[i].order_quantity +" id ='item_quantity' readonly /></td><td><input type='text' name='set_srp[]' class='form-control input-sm text-center set_srp' size='4' value="+ results.soitems[i].set_srp +"  readonly id ='set_srp'/></td><td><input type='text' name='return_qty[]' class='form-control input-sm text-center return_qty' size='4' id ='return_qty' placeholder='0' /></td><td><b><input type='text' name='gAmount[]' class='form-control input-sm text-right gAmount' size='6'  id='gAmount' placeholder='0.00' readonly></b></td></tr>");

                            }



                        
                        }
                    });

                }

            });
        });

                // compare input quanity
        $('#dTable-return-item-table').on('keyup','.return_qty',function(e){
        var _returnQty = parseFloat($(this).closest( 'tr ').find( '#return_qty' ).val());
        var _itemQty = parseFloat($(this).closest( 'tr' ).find( '#item_quantity' ).val());
        var _setSRP = parseFloat($(this).closest( 'tr' ).find( '#set_srp' ).val());
        var _total_amount = 0;

             if ( _returnQty > _itemQty ){

                    toastr.warning('the return quantity is too much','Warning');
                    $(this).closest('tr').find( '#return_qty' ).val( '0' )
                    return false;
             } else {

                    var _Qty = $(this).val();
                    var _gAmount = (_setSRP * _Qty);
                     _gAmount = _gAmount.toFixed(2);
                    $(this).closest('tr').find( '#gAmount' ).val( _gAmount );
             }

             $( "#dTable-return-item-table tbody > tr" ).each( function() {
                var $row = $( this );
                var _subtotal = $row.find( ".gAmount" ).val();
                _total_amount += parseFloat( ('0' + _subtotal).replace(/[^0-9-\.]/g, ''), 10 );
             });
             _total_amount = _total_amount.toFixed(2);
            $('#total_amount').val( _total_amount );
        
        }); 



        // allow only numeric with decimal
        $("#return_qty").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
         $(this).val($(this).val().replace(/[^0-9\.]/g,''));
                if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }
        });

        

</script>

@endsection
