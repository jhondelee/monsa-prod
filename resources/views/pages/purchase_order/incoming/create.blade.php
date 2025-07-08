    
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

  @include('pages.purchase_order.incoming.add_item')
@endsection



@section('scripts')

<script src="/js/plugins/typehead/bootstrap3-typeahead.min.js"></script>

<script src="/js/plugins/toastr/toastr.min.js"></script>

<script type="text/javascript">


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

    
    // search the po_number list value
      $(document).ready(function(){
            $('#btn-search').on('click',function(){
                var id = $('#search').val();

                if(id > 0){

                    $.ajax({
                    url:  '{{ url("incoming/receiving") }}',
                    type: 'POST',
                    dataType: 'json',
                    data: { _token: "{{ csrf_token() }}",
                    id:id}, 
                    success:function(results){
                        
                         // display the details
                        $('#dTable-receive-item-table tbody').empty();

                         $('#search').val($(this).data('')).trigger("chosen:updated");
                         $('#order_id').val(  id  );
                         $('#po_number_input').val(  results.po_details.po_number  );
                         $('#po_number').text(  results.po_details.po_number  );
                         $('#po_date').text(  results.po_details.po_date  );
                         $('#supplier').text(  results.supplier.name  );
                         $('#supplier_id').val(  results.supplier.id  );
                         $('#prepared_by').text(  results.created_by  );
                         $('#approved_by').text(  results.approved_by  );
                         $('#discount').text( parseFloat( results.po_details.discount).toFixed(2)  );
                         $('#discount_input').val(  results.po_details.discount  );
                         $('#total_amount').text(  parseFloat(results.po_details.grand_total).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')  );
                         $('#total_amount_input').val(  results.po_details.grand_total  );
                        
                        for(var i=0 ;i<=results.po_items.length;i++) {

                               $('#dTable-receive-item-table tbody').append("<tr><td><input type='input' name='item_id[]' class='form-control input-sm text-center item_id' size='2' value="+ results.po_items[i]
                               .id +" readonly></td>\
                                    <td>"+ results.po_items[i].description +"</td>\
                                    <td class='text-center'>"+ results.po_items[i].units +"</td>\
                                    <td class='text-center'>\
                                    <input type='text' name='item_quantity[]' class='form-control input-sm text-center item_quantity' size='4'  value ="+ results.po_items[i].quantity + " id ='item_quantity' readonly='true'></td>\
                                    <td>\
                                    <input type='text' name='received_qty[]' class='form-control input-sm text-center received_qty' size='4'   id ='received_qty'></td>\
                                    @if (!can('item.unit_cost'))\
                                    <td>\
                                    <input type='text' name='item_unit_cost[]' class='form-control input-sm text-right item_unit_cost' size='4'  placeholder='0.00'  id ='item_unit_cost' value ="+ results.po_items[i].unit_cost + ">\
                                    </td>\
                                    @endif\
                                    <td>\
                                    <input type='text' name='total_amount[]' class='form-control input-sm text-right total_amount' size='4' readonly='true'  placeholder='0.00'  id ='total_amount'>\
                                    </td>\
                                </tr>");             
                        }
             

                        toastr.warning('PO# '+ results.po_details.po_number,'Shown')
                           
                        }
                    });

                } else {

                    toastr.error('Please select PO Number','Invalid')

                }

            });
        });



        // compute total by input in received quanity
        $('#dTable-receive-item-table').on('keyup','.received_qty',function(e){
        //compute price
        var _price = parseFloat($(this).closest( 'tr ').find( '#item_unit_cost' ).val());
        var _quantity = parseFloat($(this).closest( 'tr' ).find( '#received_qty' ).val());
        var _sub_amount = 0.00;

           if (isNaN(_price)){
                var _sub_amount =0.00;
            }else{
                var _sub_amount = ( _price * _quantity );
            }

            _sub_amount = _sub_amount.toFixed(2);
            $(this).closest('tr').find('#total_amount').val( _sub_amount );

                // sum of price
                var _total_amount = 0.00;
                $( "#dTable-receive-item-table tbody > tr" ).each( function() {
                        var $row = $( this );        
                        var _subtotal = $row.find( ".total_amount" ).val();
    
                        _total_amount += parseFloat( ('0' + _subtotal).replace(/[^0-9-\.]/g, ''), 10 );
                       
                });

                 _total_amount = _total_amount.toFixed(2);
                $('input[name="grand_total_amount"]').val(  _total_amount  );
  
        }); 

        // compute total by input in unitcost
        $('#dTable-receive-item-table').on('keyup','.item_unit_cost',function(e){
        //compute price
        var _price = parseFloat($(this).closest( 'tr ').find( '#item_unit_cost' ).val());
        var _quantity = parseFloat($(this).closest( 'tr' ).find( '#received_qty' ).val());
        var _sub_amount = 0.00;

           if (isNaN(_price)){
                var _sub_amount =0.00;
            }else{
                var _sub_amount = ( _price * _quantity );
            }

            _sub_amount = _sub_amount.toFixed(2);
            $(this).closest('tr').find('#total_amount').val( _sub_amount );

                // sum of price
                var _total_amount = 0.00;
                $( "#dTable-receive-item-table tbody > tr" ).each( function() {
                        var $row = $( this );        
                        var _subtotal = $row.find( ".total_amount" ).val();
    
                        _total_amount += parseFloat( ('0' + _subtotal).replace(/[^0-9-\.]/g, ''), 10 );
                       
                });

                 _total_amount = _total_amount.toFixed(2);
                $('input[name="grand_total_amount"]').val(  _total_amount  );
  
        }); 

        // allow only numeric with decimal
        $(".received_qty").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
         $(this).val($(this).val().replace(/[^0-9\.]/g,''));
                if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }
        });

        
         $(document).on("keyup", ".discount_input", function () {
      
                var _discount = $( ".discount_input" ).val();
                var _g_total = $( ".grand_total_amount" ).val();

                _discount =parseFloat( ('0' + _discount).replace(/[^0-9-\.]/g, ''), 10 );
                _g_total =parseFloat( ('0' + _g_total).replace(/[^0-9-\.]/g, ''), 10 );

                var _Gtotal_amount = 0.00;
                    if(isNaN(_discount)  = 0) {
                         $( "#dTable-receive-item-table tbody > tr" ).each( function() {
                            var $row = $( this );        
                            var _subtotal = $row.find( ".total_amount" ).val();
        
                            _Gtotal_amount += parseFloat( ('0' + _subtotal).replace(/[^0-9-\.]/g, ''), 10 );
                           
                        });
                    }

                    _Gtotal_amount = _g_total - _discount;

                    _Gtotal_amount = _Gtotal_amount.toFixed(2);
                $('input[name="grand_total_amount"]').val(  _Gtotal_amount  );
            
            });

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
                        <td>"+ results.description +"  "+ _free + "</td>\
                        <td class='text-center'>"+ results.units +"</td>\
                        <td>\
                        <input type='text' name='quantity[]' class='form-control input-sm text-center quantity' required=true size='4'  value='0'   id ='quantity'>\
                        </td>\
                         <td>\
                        <input type='text' name='received_qty[]' class='form-control input-sm text-center received_qty' required=true size='4'  placeholder='0.00'  id ='received_qty'>\
                        </td>\
                         <td>\
                        <input type='text' name='item_unit_cost[]' class='form-control input-sm text-right item_unit_cost' size='4'  placeholder='0.00'  id ='item_unit_cost' value ="+ _unitCost + ">\
                        </td>\
                        <td>\
                        <input type='text' name='total_amount[]' class='form-control input-sm text-right total_amount' required=true size='4'  placeholder='0.00'  id ='total_amount'>\
                        </td>\
                    </tr>"); 

                    toastr.success(results.description +' has been added','Success!')
                }
            })
        }

</script>

@endsection
