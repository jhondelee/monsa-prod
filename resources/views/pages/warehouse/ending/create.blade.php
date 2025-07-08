    
@extends('layouts.app')

@section('pageTitle','Ending')

@section('content')





      <div class="row wrapper border-bottom white-bg page-heading">

            <div class="col-lg-10">

            <h2>Ending Inventory</h2>

                <ol class="breadcrumb">
                    <li>
                        <a href="{{route('main')}}">Home</a>
                    </li>
                    <li class="active">
                        <strong>Ending Inventory List</strong>
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

                            <h5>Create Ending Inventory</h5>
                            <div class="ibox-tools"> 
                                  <a href="{{route('ending.index')}}"class="btn btn-sm btn-primary" id='btn-cancel'><i class="fa fa-reply">&nbsp;</i>Back</a> 
                                </div>
                            

                        </div>

                        <div class="ibox-content">

                            <div class="form-horizontal m-t-md">

                                {!! Form::open(['route'=>'ending.store','class'=>'form-horizontal']) !!} 
                                              
                                        @include('pages.warehouse.ending._form')
                                  
                                {!! Form::close() !!}

                            </div>
                                                                     
                        </div>

                    </div>

                </div>

            </div>

        </div>




@endsection















