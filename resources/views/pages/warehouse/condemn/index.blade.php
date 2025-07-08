@extends('layouts.app')

@section('pageTitle','Condemn')

@section('content')


	    <div class="row wrapper border-bottom white-bg page-heading">

                <div class="col-lg-10">

                    <h2>Condemnation</h2>

                   <ol class="breadcrumb">
                        <li>
                            <a href="{{route('main')}}">Home</a>
                        </li>
                        <li class="active">
                            <strong>Condemn Item</strong>
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
                                <h5>Condemn List</h5>
                                 @if (!can('condemn.create'))
                                <div class="ibox-tools"> 
                                    <a href="{{route('condemn.create')}}" class="btn btn-primary btn-sm add-modal">
                                        <i class="fa fa-plus">&nbsp;</i>Condemn
                                    </a> 
                                </div>
                                @endif

                            </div>

                            <div class="ibox-content">
                              
                                <div class="table-responsive">
                                    
                                    <table class="table table-striped table-hover dataTables-items"data-toggle="dataTable" data-form="deleteForm" >
                                        <thead>
                                        <tr>

                                            <th>ID</th>
                                            <th>Reference No.</th>
                                            <th>Date Condemn</th>
                                            <th>Reason</th>
                                            <th>Status</th>
                                            <th>Created by</th>
                                            <th class="text-center">Action</th>
                                          
                                           
                                        </tr>
                                        </thead>
                                        <tbody>

                                            @Foreach($condemns as $condemn)
                                                <tr>

                                                    <td>{{$condemn->id}}</td>
                                                    <td>{{$condemn->reference_no}}</td>
                                                    <td>{{ date('m-d-Y', strtotime($condemn->condemn_date))}}</td>
                                                    <td>{{$condemn->reason}}</td>
                                                    <td>
                                                        @if($condemn->status == 0)
                                                            <label class="label label-info" >Pending</label> 
                                                        @else
                                                            <label class="label label-danger" >Posted</label> 
                                                        @endif
                                                    </td>
                                                    <td>{{$condemn->created_by}}</td>


                                                    <td class="text-center">

                                                        @if (!can('condemn.edit'))
                                                        <div class="btn-group tooltip-demo">
                                                            <a href="{{route('condemn.edit',$condemn->id)}}" class="btn-primary btn btn-xs"><i class="fa fa-pencil"></i></a>
                                                        </div>
                                                        @endif
                                                        @if (!can('condemn.delete'))
                                                            @if($condemn->status == 0)
                                                            <div class="btn-group">
                                                              <a class="btn-primary btn btn-xs delete"onclick="confirmDelete('{{$condemn->id}}'); return false;"><i class="fa fa-trash"></i></a>
                                                            </div>
                                                            @endif
                                                        @endif
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

<script src="/js/plugins/pace/pace.min.js"></script>
<script type="text/javascript">
        $(document).ready(function(){
              $('.dataTables-items').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                order: [ [0, 'desc'] ],
                buttons: [
                    //{ extend: 'copy'},
                    //{extend: 'csv'},
                    {extend: 'excel', title: 'Farm List'},
                    {extend: 'pdf', title: 'Farm List'},

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

        function confirmDelete(data,model) {   
         $('#confirmDelete').modal({ backdrop: 'static', keyboard: false })
            .on('click', '#delete-btn', function(){
                $(this).attr("disabled","disabled");
                document.location.href="/condemn/delete/"+data;
            });
        }

</script>

@endsection