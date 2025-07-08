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
                            <strong>Ending Inventory</strong>
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
                                <h5>Ending Inventory List</h5>
                                 @if (!can('ending.create'))
                                <div class="ibox-tools"> 
                                    <a href="{{route('ending.create')}}" class="btn btn-primary btn-sm" id="add-product"><i class="fa fa-plus">&nbsp;</i>Create Ending Inventory</a> 
                                </div>
                                @endif

                            </div>

                            <div class="ibox-content">
                              
                                <div class="table-responsive">
                                
                                    <table class="table table-striped table-hover dataTables-ending-inventory">
                                        <thead>
                                            <tr>

                                                <th>ID</th>
                                                <th>Ending Date</th>
                                                <th>Created by</th>
                                                <th>Status</th>
                                                <th class="text-center">Action</th>  

                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($endings as $ending)
                                            <tr>
                                                <td>{{$ending->id}}</td>
                                                <td>{{date("M-d-Y",strtotime($ending->ending_date))}}</td>
                                                <td>{{$ending->prepared_by}}</td>
                                                <td>
                                                    @IF($ending->status == 'POSTED')
                                                        <label class="label label-xs label-success">Posted</label>
                                                    @ELSE
                                                        <label class="label label-xs label-warning">Unpposted</label>
                                                    @ENDIF                                                
                                                </td>
                                                 <td class="text-center">
                                                    <a href="{{route('ending.edit',$ending->id)}}" class="btn btn-xs btn-white">
                                                    <i class="fa fa-pencil"></i></a>
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
                    
@endsection


@section('scripts')

<script src="/js/plugins/footable/footable.all.min.js"></script>

<script type="text/javascript">
    
        $(document).ready(function(){
              $('.dataTables-ending-inventory').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                order: [ [0, 'desc'] ],
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

          
     
                   
    
    
</script>

@endsection