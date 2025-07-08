    
@extends('layouts.app')

@section('pageTitle','Sales Order')

@section('content')


  <div class="row wrapper border-bottom white-bg page-heading">

            <div class="col-lg-10">

            <h2>Sales Order</h2>

                <ol class="breadcrumb">
                    <li>

                        Home

                    </li>

                    <li class="active">

                        <strong>Sales</strong>

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
                            <h5>Create Sales Order</h5>
           
                        </div>
                        
                        <div class="ibox-content">
                             
                            <div class="form-horizontal m-t-md">

                            {!! Form::open(array('route' => array('salesorder.store','method'=>'POST'),'id'=>'orders_form')) !!}
                                   
                                @include('pages.salesorder._form_add')
                                     
                            {!! Form::close() !!} 

                        </div>
                    </div>
                </div>
            </div>
        </div>     

        @include('pages.salesorder.add_item')


@endsection

@section('styles')
<link href="/css/plugins/footable/footable.core.css" rel="stylesheet">
<link href="/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
@endsection

@section('scripts')
<script src="/js/plugins/toastr/toastr.min.js"></script>

<script type="text/javascript">


        $('#customer_id').on('change', function (e) {

            var id = this.value;
         
            $('#dTable-selected-item-table').datatable().destroy()
           
        });

       
        $(document).on('click', '.btn-show-item', function() {
            var _id = $('#location').val();
            var _cs = $('#customer_id').val();

            if ( !_cs ){

                toastr.warning('Please select Customer','Warning')
                 return false;

            } 

            if ( !_id ){

                  toastr.warning('Please select Source Location','Warning')
                 return false;

            } else {

                    $('.modal-title').text('Add Item');
                    $('#myModal').modal('show'); 

                $(function() {
                    $.ajax({
                        url:  '{{ url("sales/additem") }}',
                        type: 'POST',
                        dataType: 'json',
                        data: { _token: "{{ csrf_token() }}",
                        id: _id}, 
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
                                    {data: 'id', title: 'id',
                                        render: function(data,type,row){
                                        return '<input type="text" name="item_id[]" class="form-control input-sm text-center item_id" size="3"  readonly="true" id ="item_id" value="'+ row.id +'">';
                                        }
                                    },    
                                    {data: 'description', title: 'Item Description'},                               
                                    {data: 'untis', title: 'Units'},
                                    {data: 'srp', title: 'SRP'},
                                    {data: 'unit_quantity', title: 'Qty',
                                        render: function(data, type, row){
                                            return '<input type="input" size="4" name="setQty[]"  class="form-control input-sm text-right setQty" placeholder="0.00" id="setQty"><input type="hidden"  name="unitQty[]" id="unitQty" value="'+ row.unit_quantity +'">';
                                        }
                                    },
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
                                             return '<a class="btn-primary btn btn-xs text-right btn-add-items" onclick="confirmAddItem('+ row.id +'); return false;"><i class="fa fa-plus"></i></a>';
                                        }
                                    }
                                    ]
                            });
                        }
                    });
                });
            }

        });

          
 
        $('.chosen-select').chosen({width: "100%"});


        $('#dTable-ItemList-table tbody').on('click','.btn-add-items',function(event){
            var _setQty =  parseFloat($(this).closest( 'tr' ).find( '#setQty' ).val());
            var _unitQty =  parseFloat($(this).closest( 'tr' ).find( '#unitQty' ).val());
            var _itemID =  parseFloat($(this).closest( 'tr' ).find( '#item_id' ).val());
            var _setSRP = 0.00;
            var _total_amount = 0.00;
            var _total_dis_amount = 0.00;
            var _total_dis_percent = 0.00;
            var _total_add_amount = 0.00;
            var _total_add_percent = 0.00;

            if ( isNaN( _setQty) || !_setQty ){

                 toastr.warning('Item quantity is 0','Warning');
                 return false;
            } 

            if ( _setQty > _unitQty ){

                toastr.warning('Not enough stocks','Warning');
                return false;

            } else {
                
                var _id = _itemID;
                var _cs = $('#customer_id').val();
            //
                $.ajax({
                url:  '{{ url("sales/getcustomeritems") }}',
                type: 'POST',
                dataType: 'json',
                data: { _token: "{{ csrf_token() }}",
                id: _id, cs: _cs}, 
                success:function(results){

                             if(results.noaddedPrice > 0){

                                 _newSRP = parseFloat(results.newSRP);

                                if (!results.csPrice.dis_amount == false && !results.csPrice.dis_percent == false){
      
                                    _setSRP = _newSRP;
                              
                                }

                                if (!results.csPrice.dis_amount == false  && results.csPrice.dis_percent > 0){

                                    var _per = ( parseFloat(results.csPrice.dis_percent) / 100 ) * _newSRP ;
                                    _setSRP = _newSRP - _per;

                                }

                                if (results.csPrice.dis_amount > 0 && !results.csPrice.dis_percent == false ){

                                    _setSRP = _newSRP + parseFloat(results.csPrice.dis_amount);
                                }
                                
                            } else {
                                
                                 _newSRP = parseFloat( results.csPrice.srp );
                                 _setSRP =  parseFloat( results.csPrice.set_srp );

                            }
             
                            var _Gamount = _setQty * _setSRP ;

                          $('#dTable-selected-item-table tbody').append("<tr><td><input type='text' name='invenId[]' class='form-control input-sm text-center invenId' size='3'  value="+ results.invenId.id +" readonly></td>\
                            <td>"+ results.csPrice.description +"</td>\
                            <td>"+'('+ results.invenId.unit_quantity +') '+results.csPrice.units+"</td><td><input type='text' name='setQty[]' class='form-control input-sm text-center setQty' size='3'  value="+ _setQty.toFixed(2) +" readonly></td><td><input type='text' name='setPrice[]' class='form-control input-sm text-center setPrice' size='6'  id='setPrice' value="+_newSRP+" readonly></td><td><input type='text' name='dis_amount[]' class='form-control input-sm text-center dis_amount' size='6'  id='dis_amount' value="+results.csPrice.dis_amount+" readonly></td><td><input type='text' name='dis_percent[]' class='form-control input-sm text-center dis_percent' size='3'  id='dis_percent' value="+results.csPrice.dis_percent+" readonly></td><td><input type='text' name='setSRP[]' class='form-control input-sm text-center setSRP' size='8'  id='setSRP' value="+_setSRP.toFixed(2) +" readonly></td><td><b><input type='text' name='gAmount[]' class='form-control input-sm text-right gAmount' size='6'  id='gAmount' value="+_Gamount.toFixed(2)+" readonly></b></td>\
                            <td class='text-center'><a class='btn btn-xs btn-danger' id='delete_line'><i class='fa fa-minus'></i></td>\
                              </tr>");

                        toastr.info(results.csPrice.description  +' has been added','Success!')


                        $( "#dTable-selected-item-table tbody > tr" ).each( function() {
                                var $row = $( this );        
                                var _subtotal = $row.find( ".gAmount" ).val();
                                var _dis_amount = $row.find( ".dis_amount" ).val();
                                var _dis_percent = $row.find( ".dis_percent" ).val();
                               // var _add_amount = $row.find( ".add_amount" ).val();
                                //var _add_percent = $row.find( ".add_percent" ).val();
                                 
            
                        _total_amount += parseFloat( ('0' + _subtotal).replace(/[^0-9-\.]/g, ''), 10 );
                        
                        _total_dis_amount += parseFloat( ('0' + _dis_amount).replace(/[^0-9-\.]/g, ''), 10 );
                           
                        _total_dis_percent += parseFloat( ('0' + _dis_percent).replace(/[^0-9-\.]/g, ''), 10 );

                        //_total_add_amount += parseFloat( ('0' + _add_amount).replace(/[^0-9-\.]/g, ''), 10 );

                       // _total_add_percent += parseFloat( ('0' + _add_percent).replace(/[^0-9-\.]/g, ''), 10 );


                        });

                         _total_amount = _total_amount.toFixed(2);
                        $('#total_sales').val(  _total_amount  );

                         _total_dis_amount = _total_dis_amount.toFixed(2);
                        $('#total_amount_discount').val(  _total_dis_amount  );

                         _total_dis_percent = _total_dis_percent.toFixed(2);
                        $('#total_percent_discount').val(  _total_dis_percent  );

                        // total_amount_added = _total_add_amount.toFixed(2);
                        //$('#total_amount_added').val(  _total_add_amount  );

                        // _total_add_percent = _total_add_percent.toFixed(2);
                       // $('#total_percent_added').val(  _total_add_percent  );
                    }
                });
            }   
         });


       $('#dTable-selected-item-table').on('click', '#delete_line', function(){

            $(this).closest('tr').remove();  
                
                var _total_amount = 0;
                var _total_dis_amount = 0;
                var _total_dis_percent = 0;
                    
                     $( "#dTable-selected-item-table tbody > tr" ).each( function() {
                            var $row = $( this );        
                            var _subtotal = $row.find( ".gAmount" ).val();
                            var _dis_amount = $row.find( ".dis_amount" ).val();
                            var _dis_percent = $row.find( ".dis_percent" ).val();
                    
                        _total_amount += parseFloat( ('0' + _subtotal).replace(/[^0-9-\.]/g, ''), 10 );
                            
                        _total_dis_amount += parseFloat( ('0' + _dis_amount).replace(/[^0-9-\.]/g, ''), 10 );
                           
                        _total_dis_percent += parseFloat( ('0' + _dis_percent).replace(/[^0-9-\.]/g, ''), 10 ); 
                    });

                _total_amount = _total_amount.toFixed(2);
                $('#total_sales').val(  _total_amount  );

                _total_dis_amount = _total_dis_amount.toFixed(2);
                $('#discount_amount').val(  _total_dis_amount  );

                _total_dis_percent = _total_dis_percent.toFixed(2);
                $('#discount_percentage').val(  _total_dis_percent  );

        });

        $(document).ready(function(){
            $('#btn-close').on('click', function(){
                document.location.href="/sales"; 
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
