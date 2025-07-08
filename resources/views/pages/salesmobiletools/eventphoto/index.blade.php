@extends('layouts.app')

@section('pageTitle','Event Photo')

@section('content')

            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">

                    <h2>Event Photo</h2>

                   <ol class="breadcrumb">
                        <li>
                            <a href="{{route('main')}}">Home</a>
                        </li>
                        <li class="active">
                            <strong>Event Photo</strong>
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
                                <h5>Event Photo List</h5>
                                 @if (!can('event.create'))
                                <div class="ibox-tools"> 
                                    <a class="btn btn-primary btn-sm upload-item">
                                        <i class="fa fa-picture-o">&nbsp;</i> Upload Photo
                                    </a> 
                                </div>
                                @endif

                            </div>

                            <div class="ibox-content">

                                <div class="lightBoxGallery">
                                    <div id="blueimp-gallery" class="blueimp-gallery">
                                        <div class="slides"></div>
                                        <h3 class="title"></h3>
                                        <a class="prev">‹</a>
                                        <a class="next">›</a>
                                        <a class="close">×</a>
                                        <a class="play-pause"></a>
                                        <ol class="indicator"></ol>
                                    </div>
                                </div>

    
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

                                            @foreach($events as $event)

                                                <tr>

                                                    <td>{{$event->id}}</td>
                                                    <td>{{$event->name}}</td>
                                                    <td>{{$event->remarks}}</td>
                                                    <td>{{$event->created_at}}</td>
                                                    <td class="text-center">
                                                        @if (!can('event.edit'))
                                                        <div class="btn-group">
                                                             <a href="/item_image/{!! $event->picture!!}" class="btn-info btn btn-xs" title="{{$event->name}}" data-gallery="">
                                                            <i class="fa fa-eye"></i></a>
                                                        </div>
                                                        @endif
                                                        @if (!can('event.delete'))
                                                        <div class="btn-group">
                                                                   <a class="btn-danger btn btn-xs delete"onclick="confirmDelete('{{$event->id}}'); return false;"><i class="fa fa-trash"></i></a>
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

@include('pages.salesmobiletools.eventphoto.upload_image')
            
          
@endsection


@section('scripts')

    <script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <script src="js/plugins/blueimp/jquery.blueimp-gallery.min.js"></script>


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
           $('.modal-title').text('Upload Event Photo');
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
                document.location.href="/event-photo/delete/"+data;
            });
        }
</script>

@endsection