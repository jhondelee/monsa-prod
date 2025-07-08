    
@extends('layouts.app')

@section('pageTitle','Users')

@section('content')





      <div class="row wrapper border-bottom white-bg page-heading">

            <div class="col-lg-10">

            <h2>User Management</h2>

                <ol class="breadcrumb">

                    <li class="active">

                        <strong>User List</strong>

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

                            <h5>Create User</h5>
                            
                        </div>

                        <div class="ibox-content">

                            <div class="form-horizontal m-t-md">


                                    {!! Form::open(array('route' => array('user.store','method'=>'POST'))) !!}

                                        @include('pages.user_management.user._form')
                                  
                                    {!! Form::close() !!}

                            </div>
                                                                     
                        </div>

                    </div>

                </div>

            </div>

        </div>




  @endsection

















