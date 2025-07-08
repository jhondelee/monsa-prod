@extends('layouts.app')

@section('pageTitle','Unit of Measure')

@section('content')


	    <div class="row wrapper border-bottom white-bg page-heading">

                <div class="col-lg-10">

                    <h2>References</h2>

                   <ol class="breadcrumb">
                        <li>
                            <a href="{{route('main')}}">Home</a>
                        </li>
                        <li class="active">
                            <strong>Unit of Measure List</strong>
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
                                <h5>Unit of Measure List</h5>
                                 @if (!can('unit_of_measure.create'))
                                <div class="ibox-tools"> 
                                    <a class="btn btn-primary btn-sm add-modal">
                                        <i class="fa fa-plus">&nbsp;</i> Unit of Measure
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
                                            <th>Code</th>
                                            <th>Unit of Measure</th>
                                            <th>Created by</th>
                                            <th class="text-center">Action</th>
                                           
                                        </tr>
                                        </thead>
                                        <tbody>

                                            @foreach($unit_of_measures as $unit_of_measure)

                                                <tr>

                                                    <td>{{$unit_of_measure->id}}</td>
                                                    <td>{{$unit_of_measure->code}}</td>
                                                    <td>{{$unit_of_measure->name}}</td>
                                                    <td>{{$unit_of_measure->created_by}}</td>
                                                    <td class="text-center">
                                                        @if (!can('unit_of_measure.edit'))
                                                        <div class="btn-group">
                                                            <a class="btn-info btn btn-xs edit-modal" 
                                                            data-id="{{$unit_of_measure->id}}"
                                                            data-code="{{$unit_of_measure->code}}"
                                                            data-name="{{$unit_of_measure->name}}"><i class="fa fa-pencil"></i></a>
                                                        </div>
                                                        @endif
                                                        @if (!can('unit_of_measure.delete'))
                                                        <div class="btn-group">
                                                                   <a class="btn-danger btn btn-xs delete"onclick="confirmDelete('{{$unit_of_measure->id}}'); return false;"><i class="fa fa-trash"></i></a>
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

            @include('pages.references.unit_of_measure.create')
            @include('pages.references.unit_of_measure.edit')

            
          
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
                    {extend: 'excel', title: 'Unit Of Measure'},
                    {extend: 'pdf', title: 'Unit Of Measure'},

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
                document.location.href="/unit_of_measures/delete/"+data;
            });
        }

        $(document).on('click', '.add-modal', function() {
            $('.modal-title').text('Add Unit of Measure');
            $('#myModal').modal('show');
        });

        // Edit a post
        $(document).on('click', '.edit-modal', function() {
            $('.modal-title').text('Edit  Unit of Measure');
            $('#id_edit').val($(this).data('id'));
            $('#code_edit').val($(this).data('code'));
            $('#name_edit').val($(this).data('name'));
            $('#editModal').modal('show');
        });


</script>

@endsection