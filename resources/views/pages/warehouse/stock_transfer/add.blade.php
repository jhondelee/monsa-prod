    
@extends('layouts.app')

@section('pageTitle','Stock Transfer')

@section('content')





      <div class="row wrapper border-bottom white-bg page-heading">

            <div class="col-lg-10">

            <h2>Inventory</h2>

                    <ol class="breadcrumb">
                        <li class="active">
                             <a href="{{ route('main') }}">Home</a>
                        </li>
                        <li>
                            <strong><a href="#">Stock Transfer</a></strong>
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

                            <h5>Create Stock Transfer</h5>
                            
                        </div>

                        <div class="ibox-content">

                            <div class="form-horizontal m-t-md">

                                {!! Form::open(array('route' => array('transfer.store','method'=>'POST'))) !!}
                                   
                                    @include('pages.warehouse.stock_transfer._form')
                                  
                                {!! Form::close() !!}

                            </div>
                                                                     
                        </div>

                    </div>

                </div>

            </div>

        </div>

        @include('pages.warehouse.stock_transfer.AddItem')

  @endsection



@section('scripts')
<script src="/js/plugins/toastr/toastr.min.js"></script>
<script type="text/javascript">
        $(document).ready(function(){
            $('#btn-cancel').on('click', function(){
                document.location.href="/inventory"; 
            });
        });

        $(document).on('click', '#add-item-modal', function() {
            var _source = $( '.source' ).val();
            var _destination = $( '.destination' ).val();
            var _warehouse = $( '.source :selected' ).text();

            if ( !_source ) {

                toastr.warning('Please select Source Location','Warning')
                 return false;
            }
            if ( !_destination ) {

                toastr.warning('Please select Destination Location','Warning')
                 return false;
            }
            if ( _source == _destination ){
                  
                toastr.warning('Destination should be different from Source Location ','Warning')
                return false;
            }
            if ( _source !=  _destination ){
  
                $('.modal-title').text('Add Item - FROM: ' + _warehouse);
                $('#AddItemModal').modal('show');

                $(function() {
                    var id = _source;
                    $.ajax({
                    url:  '{{ url('transfer/source') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: { _token: "{{ csrf_token() }}",
                    id: id}, 
                    success:function(results){
                        
                        $('.dTable-ItemList-table').DataTable({
                            destroy: true,
                            pageLength: 10,
                            responsive: true,
                            fixedColumns: true,
                            data: results,
                            dom: '<"html5buttons"B>lTfgitp',
                            buttons: [],
                            columns: [
                                    {data: 'inventory_id', name: 'inventory_id'},
                                    {data: 'name', name: 'name'},
                                    {data: 'units', name: 'units'},
                                    {data: 'onhand_quantity', name: 'onhand_quantity'},
                                    {data: undefined, defaultContent: '{!! Form::text('req_quantity',null, array('id'=> 'req_quantity','placeholder' => '0', 'size' => '4','class'=>'text-center')) !!}'},
                                    {data: 'action', orderable: false, searchable: true},
                                ],
                            columnDefs: [{
                                        targets: -1,
                                        render: function (data) {return btnAction(data);},
                                    }]
                            });        
                        }
                    });
                }); 
                                       
           
            }               

        });  
        //get functionbutton
        btnAction = function(id) {
            return '<div class="text-center">\
            <a class="btn btn-xs btn-info add-item" id="add_button" ><i class="fa fa-plus"></i></button></div>';
        } 

        $('#dTable-ItemList-table tbody').on( 'click', '.add-item', function (event) {
            event.preventDefault();
            var cellIndexMapping = { 0: true, 1:true, 2: true, 3: true, 4: true, 5: true};
            var data = [];
            var qty_value = parseFloat($(this).closest('tr').find('#req_quantity').val());
            var tableData = $(this).parents('tr').map(function () 
                { 
                    $(this).find("td").each(function(cellIndex) 
                    { 
                        if (cellIndexMapping[cellIndex])
                            data.push($(this).text());
                    });
                    
                }).get();

            item_id = data[0];  
            item_name = data[1]; 
            units = data[2]; 
            onhand = data[3];
            var int_quantity = parseFloat(qty_value);
            var int_onhand = parseFloat(onhand);
           
            if (int_quantity > int_onhand) {
                toastr.warning('Not enough stocks','Warning');
                return false;
            }

            var destination = $( '.destination :selected' ).text();
            
             //   table_data = createTableData (item_id,item_name,destination,qty_value)

            if(isNaN(qty_value)){
                toastr.warning('Item quantity is 0','Warning');
            } else {    
                $('#create_transfer_order tbody > tr').each(function () {
                   var input_id = $(this).closest('tr').find('#item_id').val();
                    if(input_id == item_id){
                        $(this).closest('tr').remove();
                    }
                });

                $('#create_transfer_order tbody').append("<tr>\
                    <td>"+ item_id +"<input type='hidden'  name='item_id[]' id='item_id' value="+ item_id +" readonly></td><td>"+ destination +"</td><td>"+ item_name +"</td><td class='text-center'>"+ units +"</td>\
                    <td class='text-center'><input type='text'  name='qty_value[]' class='text-center' size='8' value="+ qty_value.toFixed(2) +" readonly></td>\
                    <td class='text-center'><a class='btn btn-xs btn-danger' id='delete_line'><i class='fa fa-minus'></i></td></tr>");
                    toastr.warning(item_name + ' Added ' + qty_value,'Warning');
                    var qty_value = $(this).closest('tr').find('#qty_value').val('0');

                    $("#dTable-ItemList-table").dataTable();
                    

            }
            
        }); 


        $('.source').on('change', function (e) {
            var valueSelected = this.value;
        });

       
        $("#btn-remove").click(function(){
            $("table tbody").find('input[name="remove"]').each(function(){
                if($(this).is(":checked")){
                    $(this).parents("tr").remove();
                }
            });
        });

        $('#create_transfer_order ').on('click', '#delete_line', function(){
            $(this).closest('tr').remove();
        });


            
</script>

@endsection














