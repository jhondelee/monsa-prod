    
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
                                     
                            <h4>Search PO</h4> 
                            
                        </div>
                        
                        <div class="ibox-content">
                            
                            <div class="form-horizontal m-t-md">

                            {!! Form::open(array('route' => array('incoming.store','method'=>'POST'))) !!}

                                @include('pages.purchase_order.incoming._form_add')
                                  
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

    $('#search').val($(this).data('')).trigger("chosen:updated");
    $('#received_by').val($(this).data('')).trigger("chosen:updated");
    $('#dr_date').val('');
    $('#dr_number').val('');
    $('#received_date').val('');
    $('#po_number').val('');
    $('#po_date').val('');
    $('#pr_number').val('');
    $('#supplier').val('');
    $('#prepared_by').val('');
    $('#approved_by').val('');
    $('#notes').val('');
       
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

        
      $(document).ready(function(){
            $('#btn-search').on('click',function(){
                var id = $('#search').val();

                if(id > 0){

                    $.ajax({
                    url:  '{{ url('incoming/receiving') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: { _token: "{{ csrf_token() }}",
                    id:id}, 
                    success:function(results){
                        
                        $('#dTable-receive-item-table tbody').empty();

                         $('#search').val($(this).data('')).trigger("chosen:updated");
                         $('#order_id').val(  id  );
                         $('#po_number_input').val(  results.po_details.po_number  );
                         $('#po_number').text(  results.po_details.po_number  );
                         $('#po_date').text(  results.po_details.po_date  );
                         $('#supplier').text(  results.supplier.name  );
                         $('#prepared_by').text(  results.created_by  );
                         $('#approved_by').text(  results.approved_by  );
                         $('#discount').text( parseFloat( results.po_details.discount).toFixed(2)  );
                         $('#discount_input').val(  results.po_details.discount  );
                         $('#total_amount').text(  parseFloat(results.po_details.grand_total).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')  );
                         $('#total_amount_input').val(  results.po_details.grand_total  );

                        for(var i=0;i<=results.po_items.length;i++) {

                               $('#dTable-receive-item-table tbody').append("<tr><td><input type='input' name='item_id[]' class='form-control input-sm text-center item_id' size='2' value="+ results.po_items[i]
                               .id +" readonly></td>\
                                    <td>"+ results.po_items[i].name +"</td>\
                                    <td>"+ results.po_items[i].description +"</td>\
                                    <td>"+ results.po_items[i].units +"</td>\
                                    <input type='text' name='item_unit_cost[]' class='form-control input-sm text-center item_unit_cost' size='4'  value ="+ results.po_items[i].unit_cost + " id ='item_unit_cost'></td>\
                                    <td>\
                                    <td class='text-center'>\
                                    <input type='text' name='item_quantity[]' class='form-control input-sm text-center item_quantity' size='4'  value ="+ results.po_items[i].quantity + " id ='item_quantity' readonly='true'></td>\
                                    <td>\
                                    <input type='text' name='received_qty[]' class='form-control input-sm text-center received_qty' size='4'  placeholder='0.00'  id ='received_qty'>\
                                    </td>\
                                </tr>");             
                        }
             

                        toastr.warning('PO# '+ results.po_details.po_number,'Shown')
                           
                        }
                    })

                } else {

                    toastr.error('Please select PO Number','Invalid')

                }

            })
        });


        $(document).on("keyup", ".received_qty", function () {
            $( "#dTable-receive-item-table tbody > tr" ).each( function() {
                var $row = $( this );        
                var _qty = $row.find( ".item_quantity" ).val();
                var _recd_qty = $row.find( ".received_qty" ).val();

                _qty =parseFloat( ('0' + _qty).replace(/[^0-9-\.]/g, ''), 10 );
                _recd_qty =parseFloat( ('0' + _recd_qty).replace(/[^0-9-\.]/g, ''), 10 );

                /*if( _recd_qty > _qty) {
                    toastr.options ={ "closeButton": false,"positionClass": "toast-top-center","preventDuplicates": true}
                    toastr.error('Over Quantity to be received!','Invalid')
                    $row.find( ".received_qty" ).val('');
                }*/
            });
            
        });

         $('#dTable-receive-item-table').on('keyup','.item_quantity',function(e){
        //compute price
        var _price = parseFloat($(this).closest( 'tr ').find( '#item_unit_cost' ).val());
        var _quantity = parseFloat($(this).closest( 'tr' ).find( '#item_quantity' ).val());
        var _sub_amount = 0.00;

           if (isNaN(_price)){
                var _sub_amount =0.00;
            }else{
                var _sub_amount = ( _price * _quantity );
            }

            _sub_amount = _sub_amount.toFixed(2);
            $(this).closest('tr').find('#item_unit_total_cost').val( _sub_amount );

                // sum of price
                var _total_amount = 0.00;
                $( "#dTable-receive-item-table tbody > tr" ).each( function() {
                        var $row = $( this );        
                        var _subtotal = $row.find( ".item_unit_total_cost" ).val();
    
                        total_amount += parseFloat( ('0' + _subtotal).replace(/[^0-9-\.]/g, ''), 10 );
                       
                });

                 total_amount = total_amount.toFixed(2);
                $('input[name="grand_total"]').val(  total_amount  );
  
        }); 

        // allow only numeric with decimal
        $(".recvd_qty").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
         $(this).val($(this).val().replace(/[^0-9\.]/g,''));
                if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }
        });


  

</script>

@endsection
