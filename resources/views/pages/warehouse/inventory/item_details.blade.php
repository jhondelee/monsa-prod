@extends('layouts.app')

@section('pageTitle','Happy Chicken')

@section('content')


        <div class="row wrapper border-bottom white-bg page-heading">

                <div class="col-lg-10">

                    <h2>Warehouse Inventory</h2>

                   <ol class="breadcrumb">
                        <li>
                            <a href="{{route('main')}}">Home</a>
                        </li>
                        <li class="active">
                            <strong>Item Details</strong>
                        </li>
                       
                    </ol>

                </div>

        </div>

       @include('layouts.alert')
       @include('layouts.deletemodal')
    
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                
                <div class="ibox-content">
                         <a class="btn btn-primary btn-sm pull-right" href="/inventory" role="button"><i class="fa fa-reply">&nbsp;</i>Back</a>
                    <div class="col-sm-4">
                        <div class="row">
                   
                        <br>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 text-center">
                                    @if ($showItems->picture!="")
                                        <img class="img-thumbnail img-responsive text-center" src="/item_image/{!! $showItems->picture !!}"/>
                                    @else
                                        <img class="img-thumbnail img-responsive text-center" alt="image" src="{!! asset('item_image/image_default.png') !!}">
                                    @endif
                                    <div class="m-t-xs font-bold">
                                        <a href="#" class="btn btn-warning btn-sm upload-item" >Upload Image</a>
        
                                    </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <h5>Inventory Item:</h5>

                        <p><h2><strong>{{$showItems->description}}</strong></h2></p>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><h5>SRP:</h5></label>
                                <label class="col-sm-4 control-label"><h4>{{$showItems->srp}}</h4></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><h5>Code:</h5></label>
                                <label class="col-sm-4 control-label"><h4>{{$showItems->code}}</h4></label>
                            </div>
                        </div>
                                
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><h5>Unit of Mueasure:</h5></label>
                                <label class="col-sm-4 control-label"><h4>{{$showItems->units}}</h4></label>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>Inventory Item</h5>

                            </div>
                            <div class="ibox-content">
                                
                                  <div class="table-responsive" >
                                    <table class="table table-striped table-hover dataTables-itemdetails" >
                                        <thead>
                                            <tr>
                                                <th>Location</th>
                                                <th>Code</th>
                                                <th>Units</th>
                                                <th>On Hand</th>
                                                <th>Received Date</th> 
                                            </tr>
                                        </thead>
                                        <tbody>
                                      
                                            @foreach($showItemsLocations as $showItemsLocation)
                                                <tr>           
                                                    <td>{{$showItemsLocation->location}}</td>
                                                    <td>{{$showItemsLocation->code}}</td>
                                                    <td>{{$showItemsLocation->units}}</td>
                                                    <td>{{$showItemsLocation->onhand_quantity}}</td>
                                                    <td>{{$showItemsLocation->received_date}}</td>
                                                </tr>
                                            @endforeach 
                                                                                             
                                        </tbody>
                                    </table>
                            
                                </div>
                                
                             </div>
                        </div>
                    </div>
                </div>

                

            </div>

@include('pages.warehouse.inventory.upload_image')

@endsection

@section('scripts')

<script type="text/javascript">
    


        $(document).ready(function(){
              $('.dataTables-itemdetails').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    //{ extend: 'copy'},
                    //{extend: 'csv'},
                    //{extend: 'excel', title: 'Farm List'},
                    {extend: 'pdf', title: 'Procurement'},

                    {extend: 'print',
                     customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                    }
                    }
                ]

            });

        });

        $(document).ready(function(){
              $('.dataTables-shelf-life').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    //{ extend: 'copy'},
                    //{extend: 'csv'},
                    //{extend: 'excel', title: 'Farm List'},
                    {extend: 'pdf', title: 'Procurement'},

                    {extend: 'print',
                     customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                    }
                    }
                ]

            });

        });

        $(document).ready(function(){
              $('.dataTables-expiration').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    //{ extend: 'copy'},
                    //{extend: 'csv'},
                    //{extend: 'excel', title: 'Farm List'},
                    {extend: 'pdf', title: 'Procurement'},

                    {extend: 'print',
                     customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                    }
                    }
                ]

            });

        });

        $(document).on('click', '.upload-item', function() {
           $('.modal-title').text('Upload Item Image');
           $('#uploadModal').modal('show');
        });

    
    
</script>

@endsection