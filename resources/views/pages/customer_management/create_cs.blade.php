    
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


  @endsection

@section('scripts')

<script src="/js/plugins/toastr/toastr.min.js"></script>

</script>

@endsection














