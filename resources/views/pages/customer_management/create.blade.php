    
@extends('layouts.app')

@section('pageTitle','Customer')

@section('content')



      <div class="row wrapper border-bottom white-bg page-heading">

                <div class="col-lg-10">

                    <h2>Condemnations</h2>

                   <ol class="breadcrumb">
                        <li>
                            <a href="{{route('main')}}">Home</a>
                        </li>
                        <li class="active">
                            <strong>Customer</strong>
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

                            <h5>Customer</h5>
                            <div class="ibox-tools"> 
                                    <a href="{{route('customer.index')}}" class="btn btn-primary btn-sm">
                                        <i class="fa fa-reply">&nbsp;</i>Back
                                    </a> 
                                </div>
                        </div>

                        <div class="ibox-content">

                            <div class="form-horizontal m-t-md">

                                
                            {!! Form::open(array('route' => array('customer.store'),'class'=>'form-horizontal','role'=>'form','id'=>'customer_form')) !!} 

                                        
                            {!! Form::token(); !!}

                            {!! csrf_field() ; !!}

                        

                             <div class="form-group">

                                <label class="col-sm-2 control-label">Name <span class="text-danger">*</span></label>
                                <div class="col-sm-3">
                                    {!! Form::text('name',null, ['class'=>'form-control customer_name', 'required'=> true ,'id'=>'customer_name']) !!}
                                </div>
                                
                                <label class="col-sm-2 control-label">Contact Person</label>
                                <div class="col-sm-3">
                                
                                       {!! Form::text('contact_person',null, ['class'=>'form-control contact_person','id'=>'contact_person']) !!}
                                </div>


                            </div>


                            <div class="form-group">
                                 <label class="col-sm-2 control-label">Area <span class="text-danger">*</span></label>

                                <div class="col-sm-3">
                                    {!! Form::select ('area',$areas, null,['placeholder' => 'Choose Source Location...','class'=>'chosen-select required area', 'required'=>true])!!}
                                </div>
                                
                    
                            
                            <label class="col-sm-2 control-label">Contact Number 1</label>
                                <div class="col-sm-3">
                                     {!! Form::text('contact_number2',null, ['class'=>'form-control contact_no2' ,'id'=>'contact_no2']) !!}
                                </div>

                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="checkbox checkbox-success">
                                        {!! Form::checkbox('activated_area_amount', '1', null, ['id'=>'activated_amount']) !!}
                                        <label for="activated_amount">
                                            Activate Amount (Area)
                                        </label>
                                    </div>
                                </div>

                                <label class="col-sm-2 control-label">Contact Number 2</label>
                                <div class="col-sm-3">
                                     {!! Form::text('contact_number1',null, ['class'=>'form-control contact_no1','id'=>'contact_no1']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="checkbox checkbox-success">
                                        {!! Form::checkbox('activated_area_percentage', '1', null, ['id'=>'activated_precent']) !!}
                                        <label for="activated_precent">
                                            Activate Percentage (Area)
                                        </label>
                                    </div>
                                </div>


                                <label class="col-sm-2 control-label">Prepared by </label>
                                <div class="col-sm-3">
                                    {!! Form::text('created_by',$creator, ['class'=>'form-control', 'readonly']) !!}
                                </div>
                                
                            </div>
                                   
                            <div class="form-group">

                                <label class="col-sm-2 control-label">Address <span class="text-danger">*</span></label>
                                <div class="col-sm-3">
                                       {!! Form::textarea('address',null, array('class' => 'form-control', 'rows' => 3,'id'=>'address','required'=>true)) !!}
                                </div>  
                                
                                <label class="col-sm-2 control-label">E-mail</label>
                                <div class="col-sm-3">
                                     {!! Form::text('email',null, ['class'=>'form-control email','id'=>'email']) !!}
                                </div>

                            </div>

                             
                            <div class="form-group">

                            </div>

                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <div class="col-sm-8">
                                    <a class='btn btn-info btn-sm btn-add-item' id="btn-add-item"><i class='fa fa-plus'></i> Item</a>
                                </div>
                                  <div class="ibox-tools pull-right">
                                     {!! Form::submit('Save Changes', ['class' => 'btn btn-primary btn-save']) !!} 
                                </div>
                            </div>
                                                        
                            <div class="table-responsive">
                                                    
                                <table class="table table-bordered" id="dTable-price-item-table">                  

                                    <thead> 
                                        
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Item Description</th>
                                            <th>Units</th>
                                            <th>SRP</th>
                                            <th>Discount $ </th>
                                            <th>Discount %</th>
                                            <th>Active &nbsp; <input type="checkbox" class="largerCheckbox" id="ChkAllSetSRP" /></th>
                                            <th>Set SRP</th>
                                            <th class="text-center">Remove</th>

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
                                             
                                                   
                                        <a class="btn btn-primary btn-danger" href="{{ route('customer.index') }}">Close</a> 

                                                      
                                                                                    
                                        {!! Form::submit('Save Changes', ['class' => 'btn btn-primary btn-save']) !!}  


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


   
  @include('pages.customer_management.additem')
  @endsection

@section('scripts')

<script src="/js/plugins/toastr/toastr.min.js"></script>

<script type="text/javascript">

    $(document).ready(function(){
              $('.dTable-ItemList-table').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: []

            });

    });

   $(function() {
        
        $('#ChkAll').click(function() {
            if ($(this).prop('checked')) {
                $('.tblChk').prop('checked', true);
            } else {
                $('.tblChk').prop('checked', false);
            }
        });
        
    });

    $(function() {

        $('#ChkAllSetSRP').click(function() {

            if ($(this).prop('checked')) {

                 $('.chk_active').prop('checked', true);

                   $( "#dTable-price-item-table tbody > tr" ).each( function() {

                        var $row = $( this );  

                        if ($row.find('.chk_active').is(':checked')) {

                            var _srp =  $row.closest('tr').find('#item_srp').val();

                            var _srpD = $row.closest('tr').find('#amountD').val();

                            var _perD = $row.closest('tr').find('#perD').val();
                        
                         
                            if ( _perD == 0  && _srpD == 0 ){
                            
                                $row.closest('tr').find('#setSRP').val('0.00');
                              
                            }

                            if ( _srpD != 0 && _perD == 0 ){
                                  
                                var _amoundD=0.00;  

                                    if (isNaN( _srpD )){
                                        _srpD = 0.00;
                                    }else{
                                        _amoundD = ( _srp - _srpD );
                                    }
                                                                                                

                                  $row.closest('tr').find('#setSRP').val( _amoundD.toFixed(2) );
                                                                
                            }

                            if ( _perD != 0 && _srpD == 0 ){
                        
                                var _perAmount = 0.00;
                                var _SetSRP = 0.00;

                                    if (isNaN(_perD)){
                                        _SetSRP = 0.00;
                                    }else{

                                        _perAmount = ( _srp * _perD ) / 100;

                                        _SetSRP = ( _srp - _perAmount);

                                    }
                                      
                                $row.closest('tr').find('#setSRP').val( _SetSRP.toFixed(2) );
                                
                            }
                        }

                   });

            } else {

                $('.chk_active').prop('checked', false);

                $( "#dTable-price-item-table tbody > tr" ).each( function() {
                    var $row = $( this ); 
                    $row.closest('tr').find('#setSRP').val( '0.00');
                
                });

                    
            }
                            
        });
     });
  
    //show modal
    $(document).on('click', '.btn-add-item', function() {

        var valueSelected = 0;
        //
        $.ajax({
        url:  '{{ url('customer/all-items') }}',
        type: 'POST',
        dataType: 'json',
        data: { _token: "{{ csrf_token() }}",
        value: valueSelected},  
        success:function(results){                
        //
           $('#dTable-ItemList-table').DataTable({
                                destroy: true,
                                pageLength: 100,
                                responsive: true, 
                                data: results,
                                dom: '<"html5buttons"B>lTfgitp',
                                buttons: [],
                                fixedColumns: true,
                                columns: [
                                     {data: null,
                                        render: function(data, type, row){
                                                return '<input type="checkbox" value="' + row.id + '" class="largerCheckbox tblChk" id="tblChk"/></td>';
                                        }
                                    },
                                    {data: 'id',name: 'id'}, 
                                    {data: 'description', name: 'description'},
                                    {data: 'unit_code', name: 'unit_code'},
                                    {data: 'srp', name: 'srp'},  
                                
                              
                                ],
                            })

                        }
             });
            
        $('.modal-title').text('Add Item');
        $('#myModal').modal('show'); 
    });



     $('.item_name').on('change', function (e) {
        var itemSelected = this.value;
        $('#ChkAll').prop('checked', false);
        

        toastr.success(itemSelected,'Selected!')
        //
        $.ajax({
        url:  '{{ url('customer/selected-items') }}',
        type: 'POST',
        dataType: 'json',
        data: { _token: "{{ csrf_token() }}",
        value: itemSelected},  
        success:function(results){                
        //
           $('#dTable-ItemList-table').DataTable({
                                destroy: true,
                                pageLength: 100,
                                responsive: true, 
                                data: results,
                                dom: '<"html5buttons"B>lTfgitp',
                                buttons: [],
                                fixedColumns: true,
                                columns: [
                                    {data: null,
                                        render: function(data, type, row){
                                                return '<input type="checkbox" value="' + row.id + '" class="largerCheckbox tblChk" id="tblChk"/></td>';
                                        }
                                    },
                                    {data: 'id',name: 'id'}, 
                                    {data: 'description', name: 'description'},
                                    {data: 'unit_code', name: 'unit_code'},
                                    {data: 'srp', name: 'srp'},  
                              
                                ],
                            })

                        }
             });

     });

    $(document).on('click', '#add-selected', function() {
        var _ctr = 0;
        var _unit_cost = 0;

        $( ".dTable-ItemList-table tbody > tr" ).each( function() {

                var $row = $( this );   
                    
                    if($row.find('#tblChk').is(':checked')){                      

                        _ctr = _ctr + 1;

                        var _id = $row.find( 'td:eq(1)').text();

                        var _description = $row.find( 'td:eq(2)').text();
                        var _unit_code = $row.find( 'td:eq(3)').text();
                        var _srp = $row.find( 'td:eq(4)').text(); 

                        $.ajax({
                            url:  '{{ url('customer/cost-items') }}',
                            type: 'POST',
                            dataType: 'json',
                            data: { _token: "{{ csrf_token() }}",
                            value: _id},  
                            success:function(results){
                                
                            _unit_cost = results.unit_cost ;

                            if (!_unit_cost){
                                _unit_cost = 0.00;
                            }


                            $('#dTable-price-item-table tbody').append("<tr><td>"+_id+"<input type='hidden' name='item_id[]' id='item_id' value="+_id+"></td><td>"+_description+"</td><td>"+_unit_code+"</td><td>"+_srp+"<input type='hidden' name='item_srp[]' id='item_srp' value="+_srp+"><input type='hidden' name='item_cost[]' value="+_unit_cost+"></td>\
                                <td><input type='input' size='4' name='amountD[]' class='form-control input-sm text-right' placeholder='0.00' id='amountD'> </td>\
                                <td><input type='input' size='4' name='perD[]'  class='form-control input-sm text-right ' placeholder='0.00' id='perD'></td>\
                                <td class='text-center'><input type='checkbox' name='disc_active[]' class='chk_active' value='"+_id+"'/></td>\
                                <td><input type='input' size='4' name='setSRP[]'  class='form-control input-sm text-right setSRP' placeholder='0.00' id='setSRP' readonly></td>\
                                <td class='text-center'><a class='btn btn-xs btn-danger' id='delete_line'><i class='fa fa-minus'></i>\
                            </td></tr>");


                                }
                            });    
                    }

            });
        if (_ctr > 0)
        {
            toastr.info('Item has been added','Success!')
           }

    });

     // remove item 
    $('#dTable-price-item-table').on('click', '#delete_line', function(){
        $(this).closest('tr').remove();
    });
    
     $('#dTable-price-item-table').on('click','#disc_active',function(e){
 
        var _chckbox_per = $(this).closest('tr').find('#disc_active').val();

        if($(this).closest('tr').find('#disc_active').is(':checked')){

            var _srp = parseFloat($(this).closest( 'tr ').find( '#item_srp' ).val());

            var _srpD = parseFloat($(this).closest( 'tr ').find( '#amountD' ).val());

            var _perD = parseFloat($(this).closest( 'tr' ).find( '#perD' ).val());


                if ( !_perD == false && !_srpD == false ){

                    $(this).closest( 'tr').find( '#setSRP' ).val('0');

                }

                if ( !_srpD == false && !_perD == true){

                    var _amoundD=0.00;

                        if (isNaN( _srpD )){
                            _srpD = 0.00;
                        }else{
                            _amoundD = ( _srp - _srpD );
                        }

                    $(this).closest( 'tr').find( '#setSRP' ).val( _amoundD.toFixed(2));
                    
                }

                if ( !_perD == false && !_srpD == true ){

                    var _perAmount = 0.00;
                    var _SetSRP = 0.00;

                        if (isNaN(_perD)){
                            _SetSRP = 0.00;
                        }else{

                            _perAmount = ( _srp * _perD ) / 100;

                            _SetSRP = ( _srp - _perAmount);

                        }

                    $(this).closest( 'tr').find( '#setSRP' ).val( _SetSRP.toFixed(2))   ;
                    
                }

                

            
        } else {

            $(this).closest( 'tr').find( '#setSRP' ).val('0.00');
        }    
             
     });

  
     /*
      //activated_amount
    $('#activated_amount').click(function(){

        if($(this).is(':checked')){

            var _addAmount = 0.00;
            var _resultSRP = 0.00;
            var __subSRP = 0.00;

            if (!$('.area').val()){

                toastr.warning('Invalid selected Area','Warning!');

            } else {
                    
                var _id = $('.area').val();
                
                    $.ajax({
                    url:  '{{ url('customer/area') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: { _token: "{{ csrf_token() }}",
                    id: _id}, 
                    success:function(results){
                                
                        var _addAmount = parseFloat(results.add_cost).toFixed(2);
              
                            $("#dTable-price-item-table tbody > tr" ).each( function() {

                                var $row = $( this );    

                                var _subSRP = $row.find( ".setSRP" ).val();
   
                                    _resultSRP = parseFloat( _subSRP  ) + parseFloat( _addAmount  );
                                    
                                $(this).closest( 'tr').find( '#setSRP' ).val( _resultSRP.toFixed(2));

                            });
                        
                        }
                    });
                }

           } else {

                if (!$('.area').val()){

                    toastr.warning('Invalid selected Area','Warning!');

                } else {
                        
                    var _id = $('.area').val();
                    
                        $.ajax({
                        url:  '{{ url('customer/area') }}',
                        type: 'POST',
                        dataType: 'json',
                        data: { _token: "{{ csrf_token() }}",
                        id: _id}, 
                        success:function(results){
                                    
                            var _addAmount = parseFloat(results.add_cost).toFixed(2);
                  
                                $("#dTable-price-item-table tbody > tr" ).each( function() {

                                    var $row = $( this );    

                                    var _subSRP = $row.find( ".setSRP" ).val();
       
                                        _resultSRP = parseFloat( _subSRP  ) - parseFloat( _addAmount  );
                                        
                                    $(this).closest( 'tr').find( '#setSRP' ).val( _resultSRP.toFixed(2));

                                });
                            
                            }
                        });
                    }   

            }
    });

    //activated_precent
    $('#activated_precent').click(function(){

        if($(this).is(':checked')){

            if (!$('.area').val()){

                toastr.warning('Invalid selected Area','Warning!');

            } else {
                
                var _id = $('.area').val();
                var AddPercent = 0.00;
                    $.ajax({
                    url:  '{{ url('customer/area') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: { _token: "{{ csrf_token() }}",
                    id: _id}, 
                    success:function(results){
                            
                            var AddPercent = results.add_percentage;
                            toastr.info('Addition Percentage','Activated!');

                        }
                    });
            }

        } else {
         

        }
    });
         */
</script>

@endsection














