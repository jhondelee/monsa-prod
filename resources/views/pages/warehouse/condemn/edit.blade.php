    
@extends('layouts.app')

@section('pageTitle','Condemnt')

@section('content')



      <div class="row wrapper border-bottom white-bg page-heading">

                <div class="col-lg-10">

                    <h2>Condemnations</h2>

                   <ol class="breadcrumb">
                        <li>
                            <a href="{{route('main')}}">Home</a>
                        </li>
                        <li class="active">
                            <strong>Condemn Item</strong>
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

                            <h5>Condemn Products</h5>
                            <div class="ibox-tools"> 
                                    <a href="{{route('condemn.index')}}" class="btn btn-primary btn-sm">
                                        <i class="fa fa-reply">&nbsp;</i>Back
                                    </a> 
                                </div>
                        </div>

                        <div class="ibox-content">
                             @if ($condemn->status == 'Pending')
                                           
                                <div class="btn-group">
                                     <button type="button" class="btn btn-success" onclick="confirmPost('{{$condemn->id}}'); return false;" id="post-btn"><i class="fa fa-check">&nbsp;</i>Post&nbsp; </button>
                                </div>

                            @endif


                                 <a href="{{route('condemn.print',$condemn->id)}}" class="btn btn-primary btn-print"><i class="fa fa-print">&nbsp;</i>Print</a> 

                            <div class="form-horizontal m-t-md">

                            {!! Form::model($condemn, ['route' => ['condemn.update', $condemn->id],'id'=>'condemn_form']) !!}
                                           
                            {!! Form::token(); !!}

                            {!! csrf_field() ; !!}

                        

                             <div class="form-group">
                                <input type="hidden" name="condemn_id" id="condemn_id" value="{{$condemn->id}}" >
                                <label class="col-sm-2 control-label">Reference No. <span class="text-danger">*</span></label>
                                <div class="col-sm-3">
                                    {!! Form::text('reference_no',$condemn->reference_no, ['class'=>'form-control reference_no', 'required'=> true ,'id'=>'reference_no']) !!}
                                </div>

                                <label class="col-sm-2 control-label">Condemn Date <span class="text-danger">*</span></label>
                                <div  class="col-sm-3 ">
                                    <div class="input-group date">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        {!! Form::text('condemn_date',$condemn->condemn_date, ['class'=>'form-control', 'required'=>true]) !!}
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">


                    
                                <label class="col-sm-2 control-label">Prepared by </label>
                                <div class="col-sm-3">
                                    {!! Form::text('created_by',$creator, ['class'=>'form-control', 'readonly']) !!}
                                </div>


                                <label class="col-sm-2 control-label">Approved by <span class="text-danger">*</span></label>
                                <div class="col-sm-3">
                                    {!! Form::select ('approved_by',$approver, null,['placeholder' => 'Select Approver...','class'=>'chosen-select','required'=>true])!!}
                                </div>

                            </div>

                            <div class="form-group">
                                 <label class="col-sm-2 control-label">Source Location <span class="text-danger">*</span></label>

                                <div class="col-sm-3">
                                    {!! Form::select ('source',$location, $condemn->location,['placeholder' => 'Choose Source Location...','class'=>'chosen-select required source', 'required'=>true])!!}
                                </div>

                                <label class="col-sm-2 control-label">Reason</label>
                                <div class="col-sm-3">
                                     {!! Form::textarea('reason',$condemn->reason, array('class' => 'form-control', 'rows' => 3,'id'=>'reason')) !!}
                                </div>

                            </div>

                             

                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <div class="col-sm-4">
                                    <a class='btn btn-info btn-xs btn-add-item' id="btn-add-item"><i class='fa fa-plus'></i> Inventory</a>
                                    &nbsp;
                                    <a class='btn btn-info btn-xs btn-consume' id="btn-consume-item"><i class='fa fa-plus'></i> Consumables</a>
                                    &nbsp;
                                    <a class='btn btn-info btn-xs btn-return' id="btn-return-item"><i class='fa fa-plus'></i> Returns</a>
                                </div>
                            </div>
                                                        
                            <div class="table-responsive">
                                                         
                                <table class="table table-bordered" id="dTable-condemnt-item-table">                  

                                    <thead> 
                                        
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Source</th>
                                            <th class="text-center">Item Description</th>
                                            <th>Units</th>
                                            <th>Quantity</th>
                                            <th class="text-center">Remove </th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        
                                    </tbody>

                                </table>
                                
                                <hr>
                            </div>
                              <div class="hr-line-dashed"></div>
                                <div class="row">
                                    <div class="col-md-12 form-horizontal">
                             
                                        <div class="ibox-tools pull-right">
                                             
                                                   
                                        <a class="btn btn-primary btn-danger" href="{{ route('condemn.index') }}">Close</a> 

                                                      
                                        @if ($condemn->status == 'Pending')

                                        {!! Form::submit('Save Changes', ['class' => 'btn btn-primary btn-save']) !!}  

                                        @endif

                                         </div>

                                    </div>
                                </div>
                                  
                            {!! Form::close() !!}

                            </div>
                                                                     
                        </div>

                    </div>

                </div>

            </div>

        </div>


   
  @include('pages.warehouse.condemn.additem')
  @endsection

@section('scripts')

<script src="/js/plugins/toastr/toastr.min.js"></script>

<script type="text/javascript">

var get_source = "";



// display the for condemn items

    $(document).ready(function(){
    
        var id = $('#condemn_id').val();

        $('#dTable-condemnt-item-table tbody').empty();

        $.ajax({
            url:  '{{ url('condemn/items') }}',
            type: 'POST',
            dataType: 'json',
            data: { _token: "{{ csrf_token() }}",
            id: id}, 
            success:function(results){

                for( var i = 0 ; i <= results.length ; i++ ) {

                    $('#dTable-condemnt-item-table tbody').append("<tr><td>"+ results[i].id +"<input type='hidden' name='item_id[]' id='item_id' value="+ results[i].id +" readonly></td>\
                            <td>"+ results[i].source +"<input type='hidden' name='get_source[]' id='get_source' value="+ results[i].source +" readonly></td>\
                            <td>"+ results[i].name +"</td>\
                            <td>"+ results[i].units +"</td>\
                            <td><input type='text' name='qty_value[]' class='form-control input-sm text-center item_quantity' readonly size='8' value="+ results[i].unit_quantity +" id ='qty_value'></td>\
                            <td class='text-center'><a class='btn btn-xs btn-danger' id='delete_line'><i class='fa fa-minus'></i></td>\
                        </tr>"); 
                                
                        var qty_value = $(this).closest('tr').find('#qty_value').val('0');
                }
            }          
                            
        });
    });

 
// Add item from Inventory

$(document).on('click', '#btn-add-item', function() {
            var _source = $( '.source' ).val();
                get_source ="Inventory";
            var _warehouse = $( '.source :selected' ).text();

            if ( !_source ) {

                toastr.warning('Please select Source Location','Warning')
                 return false;
            }
            
                $('.modal-title').text('Add Inventory Item - FROM: ' + _warehouse);
                $('#AddItemModal').modal('show');

                $(function() {
                    var id = _source;
                    $.ajax({
                    url:  '{{ url('condemn/inventory-source') }}',
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
                                    {data: undefined, defaultContent: '{!! Form::text('req_quantity',null, array('id'=> 'req_quantity','placeholder' => '0', 'size' => '8','class'=>'form-control input-sm text-center')) !!}'},
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

        });  



//  add item from consumables

$(document).on('click', '#btn-consume-item', function() {
            var _source = $( '.source' ).val();
            get_source = "Consumable";
            var _warehouse = $( '.source :selected' ).text();

            if ( !_source ) {

                toastr.warning('Please select Source Location','Warning')
                 return false;
            }
            
                $('.modal-title').text('Add Consumable Item - FROM: ' + _warehouse);
                $('#AddItemModal').modal('show');

                $(function() {
                    var id = _source;
                    $.ajax({
                    url:  '{{ url('condemn/consumable-source') }}',
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
                                    {data: undefined, defaultContent: '{!! Form::text('req_quantity',null, array('id'=> 'req_quantity','placeholder' => '0', 'size' => '8','class'=>'form-control input-sm text-center')) !!}'},
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

        });  


//  add item from return

$(document).on('click', '#btn-return-item', function() {
            var _source = $( '.source' ).val();
            get_source = "Return";
            var _warehouse = $( '.source :selected' ).text();

            if ( !_source ) {

                toastr.warning('Please select Source Location','Warning')
                 return false;
            }
            
                $('.modal-title').text('Add Consumable Item - FROM: ' + _warehouse);
                $('#AddItemModal').modal('show');

                $(function() {
                    var id = _source;
                    $.ajax({
                    url:  '{{ url('condemn/return-source') }}',
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
                                    {data: undefined, defaultContent: '{!! Form::text('req_quantity',null, array('id'=> 'req_quantity','placeholder' => '0', 'size' => '8','class'=>'form-control input-sm text-center')) !!}'},
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

            
             //   table_data = createTableData (item_id,source,item_name,units,qty_value)

            if(isNaN(qty_value)){
                toastr.warning('Item quantity is 0','Warning');
            } else {    
                $('#dTable-ItemList-table tbody > tr').each(function () {
                   var input_id = $(this).closest('tr').find('#item_id').val();
                    if(input_id == item_id){
                        $(this).closest('tr').remove();
                    }
                });

                $('#dTable-condemnt-item-table tbody').append("<tr>\
                    <td>"+ item_id +"<input type='hidden'  name='item_id[]' id='item_id' value="+ item_id +" readonly></td><td>"+ get_source +"<input type='hidden'  name='get_source[]' id='get_source' value="+ get_source +" readonly></td><td>"+ item_name +"</td><td class='text-center'>"+ units +"</td>\
                    <td class='text-center'><input type='text'  name='qty_value[]' class='form-control input-sm text-center' size='8' value="+ qty_value.toFixed(2) +" readonly></td>\
                    <td class='text-center'><a class='btn btn-xs btn-danger' id='delete_line'><i class='fa fa-minus'></i></td></tr>");
                    toastr.info(item_name + ' Added ' + qty_value,'Condemn Item');
                    var qty_value = $(this).closest('tr').find('#qty_value').val('0');

                    $("#dTable-ItemList-table").dataTable();
                    

            }
            
        }); 


    $('#dTable-condemnt-item-table ').on('click', '#delete_line', function(){
            $(this).closest('tr').remove();
    });


    function confirmPost(data,model) {   
         $('#confirmPost').modal({ backdrop: 'static', keyboard: false })
            .on('click', '#post-btn', function(){
                $(this).attr("disabled","disabled");
                document.location.href="/condemn/post/"+data;
            });
        }


    
</script>

@endsection














