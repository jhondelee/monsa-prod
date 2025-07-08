    
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

        $(document).on("keyup", ".recvd_qty", function () {
            $( "#dTable-receive-item-table tbody > tr" ).each( function() {
                var $row = $( this );        
                var _qty = $row.find( ".quantity" ).val();
                var _recd_qty = $row.find( ".recvd_qty" ).val();
                var _total_recd = $row.find( ".total_received").val();

                _qty =parseFloat( ('0' + _qty).replace(/[^0-9-\.]/g, ''), 10 );
                _recd_qty =parseFloat( ('0' + _recd_qty).replace(/[^0-9-\.]/g, ''), 10 );
                _total_recd =parseFloat( ('0' + _total_recd).replace(/[^0-9-\.]/g, ''), 10 );

                _recd_qty = _recd_qty + _total_recd;

                   /* if( _recd_qty > _qty) {
                        toastr.options ={ "closeButton": false,"positionClass": "toast-top-center","preventDuplicates": true}
                        toastr.error('Over Quantity to be received!','Invalid')
                        $row.find( ".recvd_qty" ).val('');
                    }*/
            });
            
        });

          $('#dTable-receive-item-table').on('keyup','.item_quantity',function(e){
        //compute price
        var _price = parseFloat($(this).closest( 'tr ').find( '#unit_cost' ).val());
        var _quantity = parseFloat($(this).closest( 'tr' ).find( '#item_quantity' ).val());
        var _sub_amount = 0.00;

           if (isNaN(_price)){
                var _sub_amount =0.00;
            }else{
                var _sub_amount = ( _price * _quantity );
            }

            _sub_amount = _sub_amount.toFixed(2);
            $(this).closest('tr').find('#total_amount_input').val( _sub_amount );

                // sum of price
                var _total_amount = 0.00;
                $( "#dTable-receive-item-table tbody > tr" ).each( function() {
                        var $row = $( this );        
                        var _subtotal = $row.find( ".total_amount_input" ).val();
    
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


        function confirmPost(data,model) {   
         $('#confirmPost').modal({ backdrop: 'static', keyboard: false })
            .on('click', '#post-btn', function(){
                $(this).attr("disabled","disabled");
                document.location.href="/incoming/post/"+data;
            });
        }

        
  

</script>

@endsection
