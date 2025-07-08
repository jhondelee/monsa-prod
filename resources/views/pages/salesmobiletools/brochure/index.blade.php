@extends('layouts.app')

@section('pageTitle','Brochure')

@section('content')

            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">

                    <h2>Brochure</h2>

                   <ol class="breadcrumb">
                        <li>
                            <a href="{{route('main')}}">Home</a>
                        </li>
                        <li class="active">
                            <strong>Brochure</strong>
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
                                <h5>Brochure List</h5>
                                 @if (!can('brochure.create'))
                                <div class="ibox-tools"> 
                                    <a class="btn btn-primary btn-sm upload-item">
                                        <i class="fa fa-folder">&nbsp;</i> Upload File
                                    </a> 
                                </div>
                                @endif

                            </div>

                            <div class="ibox-content">
                                
                                <div class="table-responsive">
                                   
                                    <table class="table table-striped table-hover dataTables-area" data-toggle="dataTable" data-form="deleteForm" >

            

                                        <thead>
                                        <tr>

                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Remarks</th>
                                            <th>Created At</th>
                                            <th class="text-center">Action</th>
                                           
                                        </tr>
                                        </thead>
                                        <tbody>

                                            @foreach($brochures as $brochure)

                                                <tr>

                                                    <td>{{$brochure->id}}</td>
                                                    <td>{{$brochure->name}}</td>
                                                    <td>{{$brochure->remarks}}</td>
                                                    <td>{{$brochure->created_at}}</td>
                                                    <td class="text-center">
                                                        <div class="btn-group">
                                                            <a href="{{route('brochure.download_file',$brochure->id)}}" class="btn-warning btn btn-xs btn-download" title="{{$brochure->name}}" data-id="{{$brochure->id}}">
                                                            <i class="fa fa-download"></i></a>
                                                        </div>
                                                        <div class="btn-group">
                                                            <a href="/uploaded_file/{{$brochure->docs}}" class="btn-info btn btn-xs" title="{{$brochure->name}}" data-gallery="">
                                                            <i class="fa fa-eye"></i></a>
                                                        </div>
                                                        @if (!can('brochure.delete'))
                                                        <div class="btn-group">
                                                            <a class="btn-danger btn btn-xs delete"onclick="confirmDelete('{{$brochure->id}}'); return false;"><i class="fa fa-trash"></i></a>
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

@include('pages.salesmobiletools.brochure.upload_file')
            
          
@endsection


@section('scripts')

    <script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

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


        $(document).on('click', '.upload-item', function() {
           $('.modal-title').text('Upload File');
           $('#uploadModal').modal('show');
        });


        $(document).ready(function(){
            $('.file-box').each(function() {
                animationHover(this, 'pulse');
            });
        });


        function confirmDelete(data,model) {   
         $('#confirmDelete').modal({ backdrop: 'static', keyboard: false })
            .on('click', '#delete-btn', function(){
                $(this).attr("disabled","disabled");
                document.location.href="/brochure/delete/"+data;
            });
        }


</script>

@endsection