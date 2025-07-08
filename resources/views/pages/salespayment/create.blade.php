    
@extends('layouts.app')

@section('pageTitle','Sales Payment')

@section('content')



      <div class="row wrapper border-bottom white-bg page-heading">

                <div class="col-lg-10">

                    <h2>Sales Payment</h2>

                   <ol class="breadcrumb">
                        <li>
                            <a href="{{route('main')}}">Home</a>
                        </li>
                        <li class="active">
                            <strong>Payment</strong>
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

                            <h5>Add Payment Terms</h5>
                            <div class="ibox-tools"> 
                                <a href="{{route('sales_payment.index')}}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-reply">&nbsp;</i>Back
                                </a> 
                            </div>
                        </div>

                        <div class="ibox-content">

                            <div class="form-horizontal m-t-md">

                            {!! Form::open(['route'=>'sales_payment.store','class'=>'form-horizontal']) !!} 
                                            
                                {!! Form::token(); !!}

                                {!! csrf_field() ; !!}

                                <div class="form-group">  
                                    <label class="col-sm-2 control-label"><button type="button" class="btn btn-xs btn-primary" id="btn-search">Search SO#</button></label>
                                    <div class="col-sm-3">
                                        {!! Form::select ('search',$so_number, null,['placeholder' => 'Select PO Number...','class'=>'chosen-select','id'=>'search'])!!}
                                    </div>
                                </div>

                                 <div class="hr-line-dashed"></div>

                                 <div class="form-group">

                                    <label class="col-sm-2 control-label">SO Number</label>
                                    <div class="col-sm-3">
                                        {!! Form::text('so_number',null, ['class'=>'form-control so_number','readonly'=>true,'id'=>'so_number']) !!}
                                    </div>

                                    <label class="col-sm-2 control-label">Payment Status </label>

                                    <div class="col-sm-3">
                                        {!! Form::text('payment_status',null, ['class'=>'form-control', 'readonly','placeholder' => 'Auto-Generated','id'=>'payment_status']) !!}
                                    </div>

           

                                </div>
                                
                                <div class="hr-line-dashed"></div>
                        
                                <div class="form-group">
                        
                                    <label class="col-sm-2 control-label">SO Date</label>
                                    <div  class="col-sm-3 ">
                                        <div class="input-group date">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            {!! Form::text('so_date',null, ['class'=>'form-control','readonly'=>true,'id'=>'so_date']) !!}
                                        </div>
                                    </div>
                                     
          
                                    <label class="col-sm-2 control-label">Prepared by </label>
                                    <div class="col-sm-3">
                                        {!! Form::text('created_by',$creator, ['class'=>'form-control', 'readonly']) !!}
                                    </div>

                                </div>

                                <div class="hr-line-dashed"></div>

                                <div class="form-group">

                                     <label class="col-sm-2 control-label">Payment Type <span class="text-danger">*</span></label>

                                    <div class="col-sm-3">
                                        {!! Form::select ('payment_type',['Fully Paid'=>'Fully Paid','Partial'=>'Partial','Credit'=>'Credit'], null,['placeholder' => 'Choose Source Location...','class'=>'chosen-select required payment_type', 'required'=>true])!!}
                                    </div>


                                </div>
                            
                                <div class="hr-line-dashed"></div>
                                    <div class="row">
                                        <div class="col-md-12 form-horizontal">
                                 
                                            <div class="ibox-tools pull-right">
                                                 
                                                       
                                                        
                                        <a class="btn btn-primary btn-danger" href="{{ route('sales_payment.index') }}">Close</a> 

                                                      
                                                                                    
                                        {!! Form::submit('Save Changes', ['class' => 'btn btn-primary btn-save']) !!}  


                                             </div>

                                        </div>

                                    </div>
                                            
                                </div> 
         
                            </div>  

                        </div>
                                
                    </div>

                {!! Form::close() !!}

            </div>
                                                                     
        </div>


  @endsection

@section('scripts')

<script src="/js/plugins/toastr/toastr.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
            $('#btn-search').on('click',function(){
                var _soNumberID  = $('#search').val();
                var _so = $('#search').val();
                $('#so_number').val( _so );
                $.ajax({
                    url:  '{{ url('sales-payment/getsoinifo') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: { _token: "{{ csrf_token() }}",
                    id: _soNumberID},  
                    success:function(results){
                         
                        $('#so_date').val( results.so_date );
                        toastr.info('Selected SO Payment Terms', _so )
                    }

                 })
            })
    });

    $(document).ready(function(){

        $('.payment_type').on('change',function(){

            var _tyep = $('.payment_type').val();

                if ( _tyep !='Fully Paid' ){

                    $('#payment_status').val('Existing Balance');

                }
                if ( _tyep =='Fully Paid' ){

                    $('#payment_status').val('Existing Balance');

                }
                if ( !_tyep ){

                    $('#payment_status').val('');

                }
        })
    });

</script>

@endsection














