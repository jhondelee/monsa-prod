@extends('layouts.app')

@section('pageTitle','Sales Commission')

@section('content')


        <div class="row wrapper border-bottom white-bg page-heading">

                <div class="col-lg-10">

                    <h2>Assign Area</h2>

                   <ol class="breadcrumb">
                        <li>
                            <a href="{{route('main')}}">Home</a>
                        </li>
                        <li class="active">
                            <strong>Assign Area List</strong>
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
                                <h5>Rate List</h5>
                                 @if (!can('assign_area.create'))
                                <div class="ibox-tools"> 
                                    <a class="btn btn-primary btn-sm add-modal">
                                        <i class="fa fa-plus">&nbsp;</i> Rate
                                    </a> 
                                </div>
                                @endif

                            </div>

                            <div class="ibox-content">
                              
                                <div class="table-responsive">
                                    
                                    <table class="table table-striped table-hover dataTables-area"data-toggle="dataTable" data-form="deleteForm" >
                                        <thead>
                                        <tr>

                                            <th>ID</th>
                                            <th>Agent</th>
                                            <th>Rate</th>
                                            <th>Assign Area</th>
                                            <th>Created At</th>
                                            <th class="text-center">Action</th>
                                           
                                        </tr>
                                        </thead>
                                        <tbody>

                                            @foreach($assigned_areas as $assigned_area)

                                                <tr>

                                                    <td>{{$assigned_area->id}}</td>
                                                    <td>{{$assigned_area->emp_name}}</td>
                                                    <td>{{$assigned_area->rate}}</td>
                                                    <td>{{$assigned_area->area_assigned}}</td>
                                                    <td>{{$assigned_area->created_at}}</td>
                                                    <td class="text-center">
                                                        @if (!can('assign_area.edit'))
                                                        <div class="btn-group">
                                                            <a class="btn-info btn btn-xs edit-modal" 
                                                            data-id="{{$assigned_area->id}}"
                                                            data-name="{{$assigned_area->employee_id}}"
                                                            data-rate="{{$assigned_area->rate_id}}"
                                                            data-area="{{$assigned_area->area_id}}">
                                                            <i class="fa fa-pencil"></i></a>
                                                        </div>
                                                        @endif
                                                        @if (!can('assign_area.delete'))
                                                        <div class="btn-group">
                                                                   <a class="btn-danger btn btn-xs delete"onclick="confirmDelete('{{$assigned_area->id}}'); return false;"><i class="fa fa-trash"></i></a>
                                                        </div>
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

            @include('pages.sales_commission.assign_area.create')
            @include('pages.sales_commission.assign_area.edit')

            
          
@endsection


@section('scripts')

<script type="text/javascript">
        $(document).ready(function(){
              $('.dataTables-area').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    //{ extend: 'copy'},
                    //{extend: 'csv'},
                    {extend: 'excel', title: 'Area'},
                    {extend: 'pdf', title: 'Area'},

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
                document.location.href="/assign-area/delete/"+data;
            });
        }

        $(document).on('click', '.add-modal', function() {
            $('.modal-title').text('Add Assign Area');
            $('#myModal').modal('show');
        });

        // Edit a post
        $(document).on('click', '.edit-modal', function() {
            $('.modal-title').text('Edit Assign Area');
            $('#id_edit').val($(this).data('id'));
            $('#emp_id_edit').val($(this).data('name')).trigger("chosen:updated");
            $('#rates_edit').val($(this).data('rate')).trigger("chosen:updated");
            $('#area_edit').val($(this).data('area')).trigger("chosen:updated");

            $('#editModal').modal('show');
 
        });

            /* if ($(this).data('status')==1)
                $('#alive_status').attr("checked","checked"); 
            else
            $('#alive_status').attr("checked",false);
            */
</script>

@endsection