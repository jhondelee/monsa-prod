    
@extends('layouts.app')

@section('pageTitle','Groups')

@section('content')





      <div class="row wrapper border-bottom white-bg page-heading">

            <div class="col-lg-10">

            <h2>Permission Group</h2>

                <ol class="breadcrumb">

                    <li class="active">

                        <strong>Group List</strong>

                    </li>
                    
                </ol>

            </div>

        </div>

        <div class="wrapper wrapper-content animated fadeInRight">

            <div class="row">

                <div class="col-lg-12">

                    <div class="ibox float-e-margins">

                        <div class="ibox-title">

                            <h5>Edit Group</h5>
                            
                        </div>

                        <div class="ibox-content">

                            <div class="form-horizontal m-t-md">



                                     {!! Form::model($pgroup, ['route' => ['pgroup.update', $pgroup->id]]) !!}

                                        @include('pages.route_management.permission_group._form')
                                  
                                    {!! Form::close() !!}

                            </div>
                                                                     
                        </div>

                    </div>

                </div>

            </div>

        </div>




  @endsection

















