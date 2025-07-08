    
@extends('layouts.app')

@section('pageTitle','Supplier')

@section('content')



      <div class="row wrapper border-bottom white-bg page-heading">

                <div class="col-lg-10">

                    <h2>Supplier</h2>

                   <ol class="breadcrumb">
                        <li>
                            <a href="{{route('main')}}">Home</a>
                        </li>
                        <li class="active">
                            <strong>Supplied Items</strong>
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

                            <h5>Supplied Items</h5>
                            <div class="ibox-tools"> 
                                    <a href="{{route('supplier.index')}}" class="btn btn-primary btn-sm">
                                        <i class="fa fa-reply">&nbsp;</i>Back
                                    </a> 
                                </div>
                        </div>

                        <div class="ibox-content">
                            
                            <a href="{{route('supplier.print',$suppliers->id)}}" class="btn btn-primary btn-print"><i class="fa fa-print">&nbsp;</i>Print</a> 

                            <div class="form-horizontal m-t-md">

                            {!! Form::model($suppliers, ['route' => ['supplier.storeitems', $suppliers->id],'id'=>'supplieditem_form']) !!}

                                        
                            {!! Form::token(); !!}

                            {!! csrf_field() ; !!}

                            {!! Form::hidden('id',$suppliers->id, ['class'=>'form-control id','id'=>'supplier_id']) !!}
 
                             <div class="form-group">
                                <label class="col-sm-2 control-label">Supplier Name </label>
                                    <div class="col-sm-3">
                                      
                                        {!! Form::text('name',null, ['class'=>'form-control', 'readonly']) !!}
                                    </div>

                                <label class="col-sm-2 control-label">Cotact Person </label>
                                <div class="col-sm-3">
                                    {!! Form::text('contact_person', null, ['class'=>'form-control', 'readonly' ]) !!}
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-2 control-label">Address</label>
                                <div class="col-sm-3">
                                     {!! Form::textarea('address',null, array('class' => 'form-control', 'rows' => 3, 'readonly')) !!}
                                </div>
                                <label class="col-sm-2 control-label">Cotact Number </label>
                                <div class="col-sm-3">
                                    {!! Form::text('contact_number', null, ['class'=>'form-control', 'readonly' ]) !!}
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <div class="col-sm-3">
                                    <a class='btn btn-info btn-xs btn-show-item' id="btn-show-item"><i class='fa fa-plus'></i> Item</a>
                                </div>
                            </div>
                                                        
                            <div class="table-responsive">
                                                         
                                <table class="table table-bordered" id="dTable-components-item-table">                  

                                    <thead> 
                                        
                                        <tr>
                                            
                                            <th class="text-center">Id</th>
                                            <th class="text-center">Name</th>
                                            <th>Description </th>
                                            <th>Units </th>
                                            <th class="text-center">Remove <a class='btn btn-danger btn-xs btn-remove pull-right'><i class='fa fa-minus'></i></a>
                                        </tr>
                                    </thead>

                                    <tbody>                                                    
                                    </tbody>

                                </table>
                                
                                <hr>
                            </div>

                            <div class="form-group">

                                <div class="col-sm-4 col-sm-offset-9">

                                    <a class="btn btn-warning" href="{{ route('supplier.index') }}">Cancel</a> 

                                    &nbsp;
                                    
                                    <button type="button" class="btn btn-primary" onclick="submit_validate()"> Save Changes</button>
                                    <button type="submit" id="btn-submit" style="display:none;"></button>                              
                                                                        
                                </div>  

                            </div>

                        
                            {!! Form::close() !!}
                            
                            </div>
                                                                     
                        </div>

                    </div>

                </div>

            </div>

        </div>


    @include('pages.item_management.suppliers.additem')

@endsection

@section('scripts')

<script src="/js/plugins/toastr/toastr.min.js"></script>

<script type="text/javascript">


    
            // allow only numeric with decimal            
    $(".quantity").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
            $(this).val($(this).val().replace(/[^0-9\.]/g,''));
                if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                    event.preventDefault();
            }
        });



</script>

@endsection














