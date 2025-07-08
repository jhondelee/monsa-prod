@extends('layouts.app')

@section('pageTitle','Incoming')

@section('content')


	    <div class="row wrapper border-bottom white-bg page-heading">

                <div class="col-lg-10">

                    <h2>Incoming Item</h2>

                   <ol class="breadcrumb">
                        <li>
                            <a href="{{route('main')}}">Home</a>
                        </li>
                        <li class="active">
                            <strong>Incoming</strong>
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
                                <h5>Incoming List</h5>
                                 @if (!can('incoming.create'))
                                <div class="ibox-tools">

                                    <a href="{{route('incoming.create')}}" class="btn btn-primary btn-sm">
                                        <i class="fa fa-search">&nbsp;</i>Searc Purchase Order</a>
                                     
                                </div>
                                @endif

                            </div>

                            <div class="ibox-content">
                              
                                <div class="table-responsive">
                                    
                                    <table class="table table-bordered table-hover dataTables-incoming" id="dataTables-incoming">
                                        <thead>
                                        <tr>

                                            
                                            <th>PO Number</th>
                                            <th>DR Number</th>
                                            <th>DR Date</th>
                                            <th>Received By</th>
                                            <th>Status</th>
                                            <th class="text-center">Action</th>
                                           
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($incomings as $incoming)
                                                <tr>
                                                    <td>{{$incoming->po_number}}</td>
                                                    <td>{{$incoming->dr_number}}</td>
                                                    <td>{{date('m-d-Y', strtotime($incoming->dr_date))}}</td>
                                                 
                                                    
                                                    <td>{{$incoming->received_by}}</td>
                                                    <td>
                                                        @IF($incoming->status == 'RECEIVING')
                                                            <label class="label label-success">RECEIVING</label>
                                                        @Else
                                                            <label class="label label-danger">CLOSED</label>
                                                        @Endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (!can('incoming.edit'))
                                                        <div class="btn-group">
                                                            <a href="{{route('incoming.edit',$incoming->id)}}" class="btn-primary btn btn-xs"><i class="fa fa-pencil"></i></a>
                                                        </div>
                                                        @endif
                                                        @if (!can('incoming.delete'))
                                                         @IF($incoming->status == 'RECEIVING')
                                                            <div class="btn-group">
                                                              <a class="btn-primary btn btn-xs delete" onclick="confirmDelete('{{$incoming->id}}'); return false;"><i class="fa fa-trash"></i></a>
                                                            </div>
                                                         @Endif
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

<script type="text/javascript">
    


        $(document).ready(function(){
              $('.dataTables-incoming').DataTable({
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


        function confirmDelete(data,model) {   
         $('#confirmDelete').modal({ backdrop: 'static', keyboard: false })
            .on('click', '#delete-btn', function(){
                $(this).attr("disabled","disabled");
                document.location.href="/incoming/delete/"+data;
            });
        }

    
    
</script>

@endsection