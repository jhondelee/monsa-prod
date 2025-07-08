    
@extends('layouts.app')

@section('pageTitle','MONSA Trading')

@section('content')




<div class="row wrapper border-bottom white-bg page-heading">

                <div class="col-lg-10">

                    <h2>Suppliers</h2>

                   <ol class="breadcrumb">
                        <li>
                            <a href="{{route('main')}}">Home</a>
                        </li>
                        <li class="active">
                            <strong>Suppliers</strong>
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

                            <h5>Create Supplier</h5>
                            
                        </div>

                        <div class="ibox-content">

                            <div class="form-horizontal m-t-md">

                                {!! Form::open(['route'=>'supplier.store' ,'enctype'=>"multipart/form-data",  'class'=>'form-horizontal']) !!} 

                                    @include('pages.item_management.suppliers._form')
                                  
                                {!! Form::close() !!}

                            </div>
                                                                     
                        </div>

                    </div>

                </div>

            </div>

        </div>




  @endsection

















