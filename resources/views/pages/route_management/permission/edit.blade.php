    
@extends('layouts.app')

@section('pageTitle','Routes')

@section('content')





      <div class="row wrapper border-bottom white-bg page-heading">

            <div class="col-lg-10">

            <h2>Route Management</h2>

                <ol class="breadcrumb">

                    <li class="active">

                        <strong>Route List</strong>

                    </li>
                    
                </ol>

            </div>

        </div>

        <div class="wrapper wrapper-content animated fadeInRight">

            <div class="row">

                <div class="col-lg-12">

                    <div class="ibox float-e-margins">

                        <div class="ibox-title">

                            <h5>Edit Route</h5>
                            
                        </div>

                        <div class="ibox-content">

                            <div class="form-horizontal m-t-md">


                                     {!! Form::model($permission, ['route' => ['permission.update', $permission->id]]) !!}

                                        @include('pages.route_management.permission._form')
                                  
                                    {!! Form::close() !!}

                            </div>
                                                                     
                        </div>

                    </div>

                </div>

            </div>

        </div>




  @endsection

















