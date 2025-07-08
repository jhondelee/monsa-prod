@extends('layouts.app')

@section('pageTitle','Area')

@section('content')


	    <div class="row wrapper border-bottom white-bg page-heading">

                <div class="col-lg-10">

                    <h2>References</h2>

                   <ol class="breadcrumb">
                        <li>
                            <a href="{{route('main')}}">Home</a>
                        </li>
                        <li class="active">
                            <strong>Area List</strong>
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
                                <h5>Area List</h5>
                                 @if (!can('area.create'))
                                <div class="ibox-tools"> 
                                    <a class="btn btn-primary btn-sm add-modal">
                                        <i class="fa fa-plus">&nbsp;</i> Area
                                    </a> 
                                </div>
                                @endif

                            </div>

                            <div class="ibox-content">
                              
                                <div class="table-responsive">
                                    
                                    <table class="table table-striped table-hover dataTables-farms"data-toggle="dataTable" data-form="deleteForm" >
                                        <thead>
                                        <tr>

                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Addition Cost</th>
                                            <th>Addition Percentage</th>
                                            <th>Created by</th>
                                            <th class="text-center">Action</th>
                                           
                                        </tr>
                                        </thead>
                                        <tbody>

                                            @foreach($areas as $area)

                                                <tr>

                                                    <td>{{$area->id}}</td>
                                                    <td>{{$area->name}}</td>
                                                    <td>{{$area->add_cost}}</td>
                                                    <td>{{$area->add_percentage}}</td>
                                                    <td>{{$area->created_by}}</td>
                                                    <td class="text-center">
                                                        @if (!can('area.edit'))
                                                        <div class="btn-group">
                                                            <a class="btn-info btn btn-xs edit-modal" 
                                                            data-id="{{$area->id}}"
                                                            data-name="{{$area->name}}"
                                                            data-add_cost="{{$area->add_cost}}"
                                                            data-add_percentage="{{$area->add_percentage}}">
                                                            <i class="fa fa-pencil"></i></a>
                                                        </div>
                                                        @endif
                                                        @if (!can('area.delete'))
                                                        <div class="btn-group">
                                                                   <a class="btn-danger btn btn-xs delete"onclick="confirmDelete('{{$area->id}}'); return false;"><i class="fa fa-trash"></i></a>
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

            @include('pages.references.area.create')
            @include('pages.references.area.edit')

            
          
@endsection


@section('scripts')

<script type="text/javascript">
        $(document).ready(function(){
              $('.dataTables-farms').DataTable({
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
                document.location.href="/area/delete/"+data;
            });
        }

        $(document).on('click', '.add-modal', function() {
            $('.modal-title').text('Add Area');
            $('#myModal').modal('show');
        });

        // Edit a post
        $(document).on('click', '.edit-modal', function() {
            $('.modal-title').text('Edit Area');
            $('#id_edit').val($(this).data('id'));
            $('#name_edit').val($(this).data('name'));
            $('#add_cost_edit').val($(this).data('add_cost'));
            $('#add_percentage_edit').val($(this).data('add_percentage'));
            $('#editModal').modal('show');
        });


</script>

@endsection