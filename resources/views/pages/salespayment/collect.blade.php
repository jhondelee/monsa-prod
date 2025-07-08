    
@extends('layouts.app')

@section('pageTitle','Payment Terms')

@section('content')



      <div class="row wrapper border-bottom white-bg page-heading">

                <div class="col-lg-10">

                    <h2>Sales Payment</h2>

                   <ol class="breadcrumb">
                        <li>
                            <a href="{{route('main')}}">Home</a>
                        </li>
                        <li class="active">
                            <strong>Add Payment Terms</strong>
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

                            <h5><address>  </address> Payment Terms</h5>
                            <div class="ibox-tools"> 

                                    <a href="{{route('sales_payment.index')}}" class="btn btn-primary btn-sm">
                                        <i class="fa fa-reply">&nbsp;</i>Back
                                    </a> 

                            </div>
                        </div>

                        <div class="ibox-content">
                            
                            <a href="{{route('sales_payment.print',$salespayments->id)}}" class="btn btn-primary btn-print"><i class="fa fa-print">&nbsp;</i>Print</a> 

                            <div class="form-horizontal m-t-md">

                            {!! Form::model($salespayments, ['route' => ['sales_payment.storeitems', $salespayments->id],'id'=>'storeitems_form']) !!}

                                        
                            {!! Form::token(); !!}

                            {!! csrf_field() ; !!}

                            {!! Form::hidden('sales_payment_id',$salespayments->id, ['class'=>'form-control id','id'=>'salespayment_id']) !!}

                             <div class="form-group">  

                                 <div class="form-group">
                                    <label class="col-sm-2 control-label">SO Number</label>
                                    <div class="col-sm-3">
                                        {!! Form::text('so_number',null, ['class'=>'form-control so_number','readonly'=>true,'id'=>'so_number']) !!}
                                    </div>


                                    <label class="col-sm-2 control-label">Customer</label>
                                    <div class="col-sm-3">
                                        {!! Form::text('customer',$customer->name, ['class'=>'form-control' , 'readonly'=>'true']) !!}
                                    </div>

           

                                </div>
                                

                        
                                <div class="form-group">
                        
                                    <label class="col-sm-2 control-label">SO Date</label>
                                    <div  class="col-sm-3 ">
                                        <div class="input-group date">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            {!! Form::text('so_date',$salesorder->so_date, ['class'=>'form-control','readonly'=>true,'id'=>'so_date']) !!}
                                        </div>
                                    </div>

           
                                    <label class="col-sm-2 control-label">Prepared by </label>
                                    <div class="col-sm-3">
                                        {!! Form::text('created_by',$creator, ['class'=>'form-control', 'readonly']) !!}
                                    </div>


                                </div>

      

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Payment Status </label>

                                    <div class="col-sm-3">
                                        {!! Form::text('payment_status',null, ['class'=>'form-control payment_status', 'readonly','placeholder' => 'Auto-Generated']) !!}
                                    </div>


                                     <label class="col-sm-2 control-label">Payment Type</label>

                                    <div class="col-sm-3">

                                         {!! Form::text('payment_type',null, ['class'=>'form-control', 'readonly']) !!}
                                    </div>

                                </div>

                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <div class="col-sm-3">
                                @IF ($salespayments->payment_status == 'Existing Balance')
                                    &nbsp;&nbsp;
                                    <a class='btn btn-info btn-sm btn-term-item' id="btn-term-item"><i class='fa fa-plus'></i> Payment</a>
                                @ENDIF
                                </div>
                            </div>
                                                   
                            <div class="table-responsive">
                                                         
                                <table class="table table-bordered" id="dTable-terms-item-table">                  
                                    <thead> 
                                        <tr>
                                            <th>Id</th>
                                            <th>Date</th>
                                            <th>Transaction No.</th>
                                            <th>Payemnt Mode</th>
                                            <th>Amount Paid</th>
                                            <th>Collector</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        
                                    </thead>

                                    <tbody>                                                    
                                    </tbody>

                                </table>
                                
                                <hr>
                            </div>

                            <div class="row"> 
                                <div class="col-md-8 form-horizontal"></div>
                                 <div class="col-md-4 form-horizontal">
                                     
                                        <div class="form-group">
                                            <label class="col-md-6 control-label">Total Amount</label>
                                            <div class="col-md-6">
                                                {!! Form::text('total_sales',$salespayments->sales_total, array('class' => 'form-control text-right total_sales','readonly' => 'true')) !!}
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-6 control-label">Total Paid</label>
                                            <div class="col-md-6">
                                                {!! Form::text('total_paid',$total_paid->amount, array('placeholder' => '0.00','class' => 'form-control text-right total_paid', 'readonly' => 'true')) !!}
                                            </div>

                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-6 control-label">Total Balance</label>
                                            <div class="col-md-6">
                                                {!! Form::text('total_balance',null, array('placeholder' => '0.00','class' => 'form-control text-right total_balance','id'=>'total_balance', 'readonly' => 'true' )) !!}
                                            </div>
                                        </div>
                                  
                                </div> 
                            </div>

                            <div class="hr-line-dashed"></div>

                            <div class="row">
                                    <div class="col-md-12 form-horizontal">
                                        

                                        <div class="ibox-tools pull-right">
                                                 
                                                       
                                                        
                                        <a class="btn btn-primary btn-warning" href="{{ route('sales_payment.index') }}">Cancel</a> 

                            

                                        </div>

                                    </div>

                                </div>

                        
                            {!! Form::close() !!}
                            
                            </div>
                                                                     
                        </div>

                    </div>

                </div>

            </div>


@include('pages.salespayment.edit')
@include('pages.salespayment.details')

@endsection

@section('scripts')

<script src="/js/plugins/toastr/toastr.min.js"></script>

<script type="text/javascript">

    $(document).ready(function(){
        var _status = $('.payment_status').val();
        var _SpID  = $('#salespayment_id').val();

            $.ajax({
            url:  '{{ url('sales-payment/showpayments') }}',
            type: 'POST',
            dataType: 'json',
            data: { _token: "{{ csrf_token() }}",
            id: _SpID},  
            success:function(results){

                for(var i=0;i<=results.length;i++) {

                    $('#dTable-terms-item-table tbody').append("<tr><td><input type='text' name='id[]' class='form-control input-sm text-center id' required=true size='4'  value="+ results[i].id +" readonly></td><td>"+ results[i].date_payment +"</td><td>"+ results[i].trasanction_no +"</td><td>"+ results[i].modes +"</td><td class='text-right'>"+ results[i].amount_collected +"</td><td>"+ results[i].collected_by +"</td><td style='text-align:center;'><a class='btn-primary btn btn-xs details' onclick='showdetails("+ results[i].id +"); return false;'><i class='fa fa-eye'></i></a>&nbsp;@IF($salespayments->payment_status == 'Existing Balance')<a class='btn-danger btn btn-xs' onclick='confirmDelete("+ results[i].id +"); return false;'><i class='fa fa-trash'></i></a>@ENDIF</td></tr>"); 
                }
                    
            }

        })
    });


    $(document).ready(function(){

        var _total_sales = $('.total_sales').val();
        var _total_paid = $('.total_paid').val();
        var _total_balance = _total_sales - _total_paid ;

        $('#total_balance').val( _total_balance.toFixed(2) );

    });

    $(document).on('click','#btn-term-item',function(){
        $('.modal-title').text('Add Payment');
        $('#AddPayemntModal').modal('show');
    });


    function showdetails(data,model){   
        var _id = data;
        var _salespaymentID = $('#salespayment_id').val();
         
        $.ajax({
            url:  '{{ url('sales-payment/details') }}',
            type: 'POST',
            dataType: 'json',
            data: { _token: "{{ csrf_token() }}",
            id: _id, spID : _salespaymentID },  
            success:function(results){

                $('#_date_payment').val( results.date_payment );
                $('._payment_mode_id').val( results.modes );
                $('._trasanction_no').val( results.trasanction_no );
                $('._bank_name').val( results.bank_name );
                $('._bank_account_no').val( results.bank_account_no );
                $('._bank_account_name').val( results.bank_account_name );
                $('._amount_collected').val( results.amount_collected );
                $('._collected_by').val( results.collected_by );
                $('.modal-title').text('Show Details');
                $('#ShowPayemntModal').modal('show');
            }
        })
    }


    function confirmDelete(data,model) {   
            var _id = data;
            document.location.href="/sales-payment/remove/"+_id;
    }

</script>

@endsection














