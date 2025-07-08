    
@extends('layouts.app')

@section('pageTitle','Area Prices')

@section('content')



      <div class="row wrapper border-bottom white-bg page-heading">

                <div class="col-lg-10">

                    <h2>Customer Mgnt.</h2>

                   <ol class="breadcrumb">
                        <li>
                            <a href="{{route('main')}}">Home</a>
                        </li>
                        <li class="active">
                            <strong>Area Item Prices</strong>
                        </li>
                    </ol>

                </div>

        </div>
@include('layouts.alert')
@include('layouts.deletemodal')
        <div class="wrapper wrapper-content animated fadeInRight" >
 
                
                                 
            <div class="row">

                <div class="col-lg-12">

                    <div class="ibox float-e-margins">

                        <div class="ibox-title">

                            <h5>Area Prices</h5>
                            <div class="ibox-tools"> 
                                    <a href="{{route('area_prices.index')}}" class="btn btn-primary btn-sm">
                                        <i class="fa fa-reply">&nbsp;</i>Back
                                    </a> 
                                </div>
              
                                               
                            <div class="form-horizontal m-t-md"  id="ibox1">

                            <div class="ibox-content">

                                <div class="sk-spinner sk-spinner-wave">
                                        <label>Loading...</label>
                                        <div class="sk-rect1"></div>
                                        <div class="sk-rect2"></div>
                                        <div class="sk-rect3"></div>
                                        <div class="sk-rect4"></div>
                                        <div class="sk-rect5"></div>
                                    </div>

                              
                            {!! Form::model($areas, ['route' => ['area_prices.update', $areas->id],'id'=>'customer_form']) !!}

                                        
                            {!! Form::token(); !!}

                            {!! csrf_field() ; !!}

                            
                             <div class="form-group">
                                <input type="hidden" name="area_id" id="area_id" value="{{$areas->id}}">
                                <label class="col-sm-2 control-label">Name </label>
                                <div class="col-sm-3">
                                    {!! Form::text('name',null, ['class'=>'form-control area_name', 'required'=> true ,'readonly','id'=>'area_name']) !!}
                                </div>
                                

                            </div>

                         
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <div class="col-sm-2">
                                    <a class='btn btn-info btn-sm btn-add-item' id="btn-add-item"><i class='fa fa-plus'></i> Item</a>
                                </div>

                                <div class="col-sm-1 pull-right">
                                    <a class='btn btn-danger btn-sm btn-remove-item' id="btn-remove-item"><i class='fa fa-check'></i> Remove</a>
                                </div>
                            </div>

                            </div>                     
                            <div class="table-responsive" id="ibox2">
                                <div class="ibox-content">
                                    <div class="sk-spinner sk-spinner-wave">
                                        <label>Loading...</label>
                                        <div class="sk-rect1"></div>
                                        <div class="sk-rect2"></div>
                                        <div class="sk-rect3"></div>
                                        <div class="sk-rect4"></div>
                                        <div class="sk-rect5"></div>
                                    </div> 
                                <table class="table table-bordered " id="dTable-area-price-item-table">                  
                                 
                          
                                    <thead> 
                                        
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Item Description</th>
                                            <th>Units</th>
                                            <th>SRP</th>
                                            <th>Added ₱</th>
                                            <th>Added %</th>
                                            <th>Active &nbsp; <input type="checkbox" class="largerCheckbox" id="ChkAllSetSRP" /></th>
                                            <th>Set SRP</th>
                                            <th class="text-center">Remove</th>
                                            <th class="text-center">
                                                <input type="checkbox" class="largerCheckbox" id="ChkAllRemove" />
                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody>
                                   

                                    </tbody>
                                   
                                 
                                </table>
                                </div>
  
                                <hr>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-2 pull-right">
              
                                    <a class="btn btn-primary btn-primary btn-sm" href="{{ route('area_prices.index') }}">Close</a>
                                    <a class='btn btn-primary btn-danger btn-sm btn-remove-item' id="btn-remove-item"><i class='fa fa-check'></i> Remove</a>
                                </div>

                            </div>
                            <div class="hr-line-dashed"></div>


                            {!! Form::close() !!}

                            </div>
                                                                
                        </div>

                    </div>

                </div>

            </div>
          
        </div>


   
  @include('pages.customer_management.area_prices.additem')

  @endsection

@section('scripts')
<script src="js/jquery-3.1.1.min.js"></script>
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
                $('.tblChk  ').prop('checked', false);
            }
        });
        
    });
    $('#ChkAllRemove').prop('checked', false);
    $(function() {
            
        $('#ChkAllRemove').click(function() {
            if ($(this).prop('checked')) {
                $('.chk_remove').prop('checked', true);
            } else {
                $('.chk_remove').prop('checked', false);
            }
        });
        
    });


    $(function() {

        $('#ChkAllSetSRP').click(function() {

        var _rowCtr = $('#dTable-area-price-item-table').find('tr').length;

        $('#ibox1').children('.ibox-content').toggleClass('sk-loading');
        $('#ibox2').children('.ibox-content').toggleClass('sk-loading');

            if ($(this).prop('checked')) {

                 $('.chk_active').prop('checked', true);

                   $( "#dTable-area-price-item-table tbody > tr" ).each( function() {
                              
                        var $row = $( this );  

                        if ($row.find('.chk_active').is(':checked')) {

                            var _srp =  parseInt($row.closest('tr').find('#item_srp').val());

                            var _srpD = parseInt($row.closest('tr').find('#amountD').val());

                            var _perD = parseInt($row.closest('tr').find('#perD').val());
                                
                                if (isNaN( _srp )){
                                    _srp = 0.00;
                                }
                                if (isNaN( _srpD )){
                                    _srpD = 0.00;
                                }
                                if (isNaN( _perD )){
                                    _perD = 0.00;
                                }

                           
                            var _results = 0;

                             if ( !_perD == false && !_srpD == false ){
                            
                                $row.closest('tr').find('#setSRP').val('0.00');

                                _results = 0.00;
                              
                            }


                            if ( !_srpD == false && !_perD == true){

                               var _amoundD=0.00;
     
                      

                                    if (isNaN( _srpD )){
                                        _srpD = 0.00;
                                    }else{
                                        _amoundD = ( _srp + _srpD );
                                    }
                
                                $row.closest('tr').find('#setSRP').val( Math.round(_amoundD.toFixed(2)));
    

                                 _results = Math.round(_amoundD.toFixed(2));
                                
                            }

                            
                             if ( !_perD == false && !_srpD == true ){

                        
                                var _perAmount = 0.00;
                                var _SetSRP = 0.00;

                                    if (isNaN(_perD)){
                                        _SetSRP = 0.00;
                                    }else{

                                        _perAmount = ( _srp * _perD ) / 100;

                                        _SetSRP = ( _srp + _perAmount);

                                    }
                                      
                                $row.closest('tr').find('#setSRP').val( Math.round(_SetSRP.toFixed(2)));

                                 _results = Math.round(_SetSRP.toFixed(2));
                                
                            }


                          var  _id = $row.closest( 'tr').find( '#id' ).val();
                          var  _areaid = $('#area_id').val();
                          var  _item_id = $row.closest( 'tr' ).find( '#item_id' ).val();
                          var  _item_cost = $row.closest( 'tr' ).find( '#item_cost' ).val();
                          var  _chk_active = $row.closest('tr').find('#chk_active').val();
                       
                            


                  
                            $.ajax({

                                url:  '{{ url("area-prices/doUpdate") }}',
                                type: 'POST',
                                dataType: 'json',
                                data: { _token: "{{ csrf_token() }}",
                                id:_id, areaid: _areaid, item_id: _item_id, item_cost: _item_cost, chk_active:_chk_active,srp:_srp, srpD:_srpD, perD: _perD, set_srp :_results}, 

                                success:function(results){
                           
                                    //toastr.success(results +' - Set SRP saved!','Activate!')

                                }, 

                                complete: function(){

                                    _rowCtr--;
 
                                    if ( _rowCtr ==  1){
                                            $('#ibox1').children('.ibox-content').toggleClass('sk-loading');
                                            $('#ibox2').children('.ibox-content').toggleClass('sk-loading');
                                            toastr.success('All Set SRP has been activated!','Activate!')
                                    }
                                    
                                },
                               
                                


                            }); 
     
                        }


                   });               

                        
                     

                        
            } else {

                $('.chk_active').prop('checked', false);

                var _rowCtr = $('#dTable-area-price-item-table').find('tr').length;

                $( "#dTable-area-price-item-table tbody > tr" ).each( function() {
                    var $row = $( this ); 

                    $row.closest('tr').find('#setSRP').val( '0.00');

                    var _id = $row.closest( 'tr').find( '#id' ).val();
                    $.ajax({

                        url:  '{{ url("area-prices/doDeactive") }}',
                        type: 'POST',
                        dataType: 'json',
                        data: { _token: "{{ csrf_token() }}",
                        id: _id}, 
                        success:function(results){

                           // toastr.info('Selected item deactivate','Deactivate!')

                        },   


                        complete: function(){

                            _rowCtr--;
 
                            if ( _rowCtr ==  1){
                                    $('#ibox1').children('.ibox-content').toggleClass('sk-loading');
                                    $('#ibox2').children('.ibox-content').toggleClass('sk-loading');
                                    toastr.warning('All Set SRP has been deactivated!','Deactivate!')
                            }
                                    
                        },
                               
                    }); 
                
                });
            
                    
            }
                            
        });
     });

  
    //show modal
    $(document).on('click', '.btn-add-item', function() {

        var valueSelected = 0;
        //
        $.ajax({
        url:  '{{ url('area-prices/all-items') }}',
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
                                                return '<input type="checkbox" value="' + row.id + '" class="largerCheckbox tblChk" id="tblChk" name="tblChk" /></td>';
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
        toastr.success(itemSelected,'Selected!')
        $('#ChkAll').prop('checked', false);
        //
        $.ajax({
        url:  '{{ url('area-prices/selected-items') }}',
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
                                                return '<input type="checkbox" value="' + row.id + '" class="largerCheckbox tblChk" id="tblChk" name="tblChk" /></td>';
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
        
        var _unit_cost = 0;

        $('#ibox3').children('.ibox-content').toggleClass('sk-loading');
   
        var $checkboxes = $('.dTable-ItemList-table td input[type="checkbox"]');

        var ctrchk = $checkboxes.filter(':checked').length;

        var ctrchk = ctrchk - 1;
        
        $( ".dTable-ItemList-table tbody > tr" ).each( function() {

                var $row = $( this );   
                    
                    if($row.find('#tblChk').is(':checked')){         

                        var _id = $row.find( 'td:eq(1)').text();

                        var _description = $row.find( 'td:eq(2)').text();
                        var _unit_code = $row.find( 'td:eq(3)').text();
                        var _srp = $row.find( 'td:eq(4)').text(); 
                        var _areaid = $('#area_id').val();
                  
                        $.ajax({
                            url:  '{{ url("area-prices/cost-items") }}',
                            type: 'POST',
                            dataType: 'json',
                            data: { _token: "{{ csrf_token() }}",
                            id: _id, area_id:_areaid},  
                            success:function(results){

                            _unit_cost = results.UnitCost.unit_cost;
                      
                            _csprice_id = results.cspriceID;
                           
                            if (!_unit_cost){
                                _unit_cost = 0.00;
                            }

                            $('#dTable-area-price-item-table tbody').append("<tr><td>"+_id+"<input type='hidden' name='item_id[]' id='item_id' value="+_id+"></td><td>"+_description+"</td><td>"+_unit_code+"</td><td>"+_srp+"<input type='hidden' name='item_srp[]' id='item_srp' value="+_srp+"><input type='hidden' name='item_cost[]' id='item_cost' value="+_unit_cost+"></td>\
                                <td><input type='input' size='4' name='amountD[]' class='form-control input-sm text-right' placeholder='0.00' id='amountD'> </td>\
                                <td><input type='input' size='4' name='perD[]'  class='form-control input-sm text-right ' placeholder='0.00' id='perD'></td>\
                                <td class='text-center'><input type='checkbox' name='disc_active[]' id='chk_active' class='chk_active' value='"+_id+"'/></td>\
                                <td><input type='input' size='4' name='setSRP[]'  class='form-control input-sm text-right setSRP' placeholder='0.00' id='setSRP' readonly></td>\
                                <td class='text-center'><a class='btn btn-xs btn-danger' id='delete_line'><input type='hidden' name='id[]' id='id' value="+_csprice_id+"><i class='fa fa-minus'></i></td>\
                                <td class='text-center'><div class='btn-group'><input class='checkbox text-center chk_remove' id='chk_remove' type='checkbox' name='chk_remove'></div>\
                            </td></tr>");


                            },

                            complete: function(){
                       
                                if ( ctrchk ==  0){
                                        $('#ibox3').children('.ibox-content').toggleClass('sk-loading');
                                        toastr.info('Item has been added','Success!')
                                }
                                 
                                ctrchk--;    
                            },
                            
                            });    
                    }

            });

    });


     // remove item 
    $('#dTable-area-price-item-table').on('click', '#delete_line', function(){

        var _id = $(this).closest( 'tr').find( '#id' ).val();

            $.ajax({

                    url:  '{{ url("area-prices/doDelete") }}',
                    type: 'POST',
                    dataType: 'json',
                    data: { _token: "{{ csrf_token() }}",
                    id: _id}, 
                    success:function(results){

                            toastr.info(results +'','Successfully Removed')

                    }   


                }); 
    
        $(this).closest('tr').remove();

    });

     $(document).on('click', '#btn-remove-item', function() {
        
        $('#ibox1').children('.ibox-content').toggleClass('sk-loading');
   
        $('#ibox2').children('.ibox-content').toggleClass('sk-loading');

        var $checkboxes = $('#dTable-area-price-item-table td input[name="chk_remove"]');

        var $i = $checkboxes.filter(':checked').length;
        var $i = $i - 1;
        $( "#dTable-area-price-item-table tbody > tr" ).each( function() {

            var $row = $( this );  

            if ($row.find('.chk_remove').is(':checked')) {

                var _id = $row.closest('tr').find('#id').val();

                $.ajax({

                        url:  '{{ url("area-prices/doDelete") }}',
                        type: 'POST',
                        dataType: 'json',
                        data: { _token: "{{ csrf_token() }}",
                        id: _id}, 
                        success:function(results){
                             
                             $row.closest('tr').remove();
                             $('#ChkAllRemove').prop('checked', false);
                             
                        },   
                         complete: function(){
                                     
                                if ( $i ==  0){
                                    $('#ibox1').children('.ibox-content').toggleClass('sk-loading');
                                    $('#ibox2').children('.ibox-content').toggleClass('sk-loading');
                                        toastr.info('Selected item removed!','Success!')
                                }

                                $i--;     
                            }
                    });
            }

         });  

     });

     $('#dTable-area-price-item-table').on('click','#chk_active',function(e){
 
        var _chckbox_per = $(this).closest('tr').find('#chk_active').val();

        if($(this).closest('tr').find('#chk_active').is(':checked')){

            var _srp = parseFloat($(this).closest( 'tr ').find( '#item_srp' ).val());

            var _srpD = parseFloat($(this).closest( 'tr ').find( '#amountD' ).val());

            var _perD = parseFloat($(this).closest( 'tr' ).find( '#perD' ).val());

            var _results ;

                if (isNaN( _srp )){
                    _srp = 0.00;
                }
                if (isNaN( _srpD )){
                    _srpD = 0.00;
                }
                if (isNaN( _perD )){
                    _perD = 0.00;
                }


                if ( !_perD == false && !_srpD == false ){

                    $(this).closest( 'tr').find( '#setSRP' ).val('0');

                
                        _results = 0.00;
           

                }

                if ( !_srpD == false && !_perD == true){

                    var _amoundD=0.00;

                        if (isNaN( _srpD )){
                            _srpD = 0.00;
                        }else{
                            _amoundD = ( _srp + _srpD );
                        }


                    $(this).closest( 'tr').find( '#setSRP' ).val( Math.round(_amoundD.toFixed(2)));

                    _results = Math.round(_amoundD.toFixed(2));
                    
                }

                if ( !_perD == false && !_srpD == true ){

                    var _perAmount = 0.00;
                    var _SetSRP = 0.00;

                        if (isNaN(_perD) ){
                            _SetSRP = 0.00;
                        }else{

                            _perAmount = ( _srp * _perD ) / 100;

                            _SetSRP = ( _srp + _perAmount);

                        }

                    $(this).closest( 'tr').find( '#setSRP' ).val( Math.round(_SetSRP.toFixed(2)))   ;

                    _results = Math.round(_SetSRP.toFixed(2));
                }

                var _id = $(this).closest( 'tr').find( '#id' ).val();
                var _areaid = $('#area_id').val();
                var _item_id = $(this).closest( 'tr' ).find( '#item_id' ).val();
                var _item_cost = $(this).closest( 'tr' ).find( '#item_cost' ).val();
                var _chk_active = $(this).closest('tr').find('#chk_active').val();

 
                $.ajax({

                    url:  '{{ url("area-prices/doUpdate") }}',
                    type: 'POST',
                    dataType: 'json',
                    data: { _token: "{{ csrf_token() }}",
                    id:_id,areaid: _areaid, item_id: _item_id, item_cost: _item_cost, chk_active:_chk_active,srp:_srp, srpD:_srpD, perD: _perD, set_srp :_results}, 

                    success:function(results){

                        toastr.success(results +' - Set SRP saved!','Activate!')

                    }   
                });   
               
        } else {
            
            

            $(this).closest( 'tr').find( '#setSRP' ).val('0.00');
            var _id = $(this).closest( 'tr').find( '#id' ).val();
                $.ajax({

                    url:  '{{ url("area-prices/doDeactive") }}',
                    type: 'POST',
                    dataType: 'json',
                    data: { _token: "{{ csrf_token() }}",
                    id: _id}, 
                    success:function(results){

                        toastr.info('Selected item deactivate','Deactivate!')

                    }   
                }); 


        }    
             
     });


    $(document).ready(function(){
    
        var _id = $('#area_id').val();
        $('#ChkAllSetSRP').prop('checked', false);
        $.ajax({
            url:  '{{ url("area-prices/price") }}',
            type: 'POST',
            dataType: 'json',
            data: { _token: "{{ csrf_token() }}",
            id: _id}, 
            success:function(results){
                
                for( var i = 0 ; i <= results.length ; i++ ) {
                   //append to table
                    $('#dTable-area-price-item-table tbody').append("<tr><td>"+results[i].item_id+"<input type='hidden' name='item_id[]' id='item_id' value="+results[i].item_id+"></td><td>"+results[i].item_descript+"</td><td>"+results[i].item_units+"</td><td>"+results[i].item_srp+"<input type='hidden' name='item_srp[]' id='item_srp' value="+results[i].item_srp+"><input type='hidden' name='item_cost[]' id='item_cost' value="+results[i].item_cost+"></td>\
                        <td><input type='input' size='4' name='amountD[]' class='form-control input-sm text-right' placeholder='0.00' id='amountD' value="+results[i].amountD+"> </td>\
                        <td><input type='input' size='4' name='perD[]'  class='form-control input-sm text-right ' placeholder='0.00' id='perD' value="+results[i].perD+"></td>\
                        <td class='text-center chkbx'><input type='checkbox' name='disc_active[]' class='chk_active' id='chk_active' value="+results[i].item_id+"></td>\
                        <td><input type='input' size='4' name='setSRP[]'  class='form-control input-sm text-right setSRP' placeholder='0.00' id='setSRP' value="+results[i].setSRP+" readonly></td>\
                        <td class='text-center'><input type='hidden' name='id[]' id='id' value="+results[i].id+"><div class='btn-group'><a class='btn btn-xs btn-danger' id='delete_line'><i class='fa fa-minus'></i></td>\
                        <td class='text-center'><div class='btn-group'><input class='checkbox text-center chk_remove' id='chk_remove' type='checkbox' name='chk_remove'></div>\
                    </td></tr>");
                    
                    if(results[i].disc_active == 1){
                        
                         $(".chkbx input[value='"+results[i].item_id+"']").attr('checked','checked');

                    }
                }                                
            }                
        });
    });
      
  

 
</script>

@endsection














