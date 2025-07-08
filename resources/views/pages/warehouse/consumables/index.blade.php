@extends('layouts.app')

@section('pageTitle','Consumable')

@section('content')


        <div class="row wrapper border-bottom white-bg page-heading">

                <div class="col-lg-10">

                    <h2>Consumable Inventory</h2>

                   <ol class="breadcrumb">
                        <li>
                            <a href="{{route('main')}}">Home</a>
                        </li>
                        <li class="active">
                            <strong>Consumable Inventory</strong>
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
                                <h5>Consumable Inventory List</h5>
          
                                                                   
                                    @if (!can('consumables.create'))
                                     <div class="ibox-tools">
                                        <a href="#" class="btn btn-primary btn-sm add-consumable-item"  id="add-product"><i class="fa fa-plus">&nbsp;</i>Create Consumable Inventory</a> 
                                         <a href="{{route('consumables.print')}}" class="btn btn-info btn-sm print-inventory-item"><i class="fa fa-print">&nbsp;</i>Print</a>
                                    </div>
                                    @endif
                                       

                            </div>

                            <div class="ibox-content">
                              
                                <div class="table-responsive">
                                    
                                    <table class="table table-striped table-hover dataTables-items"data-toggle="dataTable" data-form="deleteForm" >
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Item Name</th>
                                                <th>Unit</th>
                                                <th>Quantity</th>
                                                <th>Location</th>
                                                <th>Status</th>
                                                <th>Action</th>  
                                            </tr>
                                        </thead>
                                        <tbody>

                                             @foreach($consumables as $consumable)
                                            <tr>
                                                <td>{{$consumable->id}}</td>
                                                <td>
                                                    @if ($consumable->picture!="")
                                                        <img class="img-thumbnail img-responsive text-center"  width="56" height="56" src="/item_image/{!! $consumable->picture !!}"/>
                                                    @else
                                                        <img class="img-thumbnail img-responsive text-center"  width="56" height="56" alt="image" src="{!! asset('item_image/image_default.png') !!}">
                                                    @endif
                                                    {{$consumable->name}}
                                                </td>
                                                               
                                                <td>{{$consumable->units}}</td>
                                                <td>{{$consumable->onhand_quantity}}</td>   
                                                <td>{{$consumable->location}}</td>
                                                <td class="text-center"><label class="label label-warning " >{{$consumable->status}}</label></td>
                                                <td class="text-center">
                                                        <div class="btn-group">
                                                            <a href="{{route('consumables.show',$consumable->item_id)}}" class="btn-primary btn btn-xs"><i class="fa fa-eye"></i></a>
                                                        </div>
                                                        <div class="btn-group">
                                                            <a href="{{route('consumables.edit',$consumable->item_id)}}" class="btn-primary btn btn-xs"><i class="fa fa-pencil"></i></a>
                                                        </div>
                                                        <div class="btn-group">
                                                            <a href="#" class="btn-primary btn btn-xs"><i class="fa fa-ticket"></i></a>
                                                        </div>
                                                        
                                                </td>
                                                 <td class="text-center">
                                                    
                                                </td>
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

            
@include('pages.warehouse.consumables.add_item')
@endsection


@section('scripts')

<script src="/js/plugins/footable/footable.all.min.js"></script>

<script type="text/javascript">
    


        $(document).ready(function(){
              $('.dataTables-items').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    //{ extend: 'copy'},
                    //{extend: 'csv'},
                    //{extend: 'excel', title: 'Suppier List'},
                    {extend: 'pdf', title: 'Suppliers'},

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

        $(document).on('click', '.add-consumable-item', function() {
           $('.modal-title').text('Add Consumable Item');
           $('#myModal').modal('show');
        });  
    

           
    
    
</script>

@endsection