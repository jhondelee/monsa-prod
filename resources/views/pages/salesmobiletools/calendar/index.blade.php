@extends('layouts.app')

@section('pageTitle','Event Schedule')

@section('content')


    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Calendar</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="index.html">Home</a>
                </li>
                <li>
                    Extra pages
                </li>
                <li class="active">
                    <strong>Calendar </strong>
                </li>
            </ol>
        </div>
    </div>
       
        @include('layouts.alert')
        @include('layouts.deletemodal') 

<div class="wrapper wrapper-content">
    <div class="row animated fadeInDown">
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Events for This Month</h5>

                </div>
                <div class="ibox-content">
                    <div id='external-events'>
                        <p>List of Events.</p>
                            @foreach($scheds as $sched)
                              <div class='external-event navy-bg'>
                                <a href="#" class="navy-bg btn-block edit-title" data-id="{{$sched->id}}" data-title="{{$sched->title}}"><i class="fa fa-pencil">&nbsp;</i>{{$sched->title}}
                                </a>
                           </div>
                            @endforeach

                        <p class="m-t">
                           <!-- @if (!can('calendar.create'))

                                <a href="#" class="btn btn-primary">
                                    <i class="fa fa-pencil">&nbsp;</i>Create Event
                                </a>
                         
                            @endif -->
                        </p>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-9">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Calendar - Schedule of Events</h5>
                </div>
                <div class="ibox-content">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

  <!-- Modal -->
  <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Booking title</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="text" class="form-control" id="title">
          <span id="titleError" class="text-danger"></span>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" id="saveBtn" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>

@include('pages.salesmobiletools.calendar.create')
@include('pages.salesmobiletools.calendar.edit')
@endsection


@section('scripts')

<link href="/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
<script src="/js/plugins/toastr/toastr.min.js"></script>
 <script src="js/plugins/sweetalert/sweetalert.min.js"></script>

<!-- Mainly scripts -->
<script src="js/plugins/fullcalendar/moment.min.js"></script>

<!-- jQuery UI  -->
<script src="js/plugins/jquery-ui/jquery-ui.min.js"></script>

<!-- iCheck -->
<script src="js/plugins/iCheck/icheck.min.js"></script>

<!-- Full Calendar -->
<script src="js/plugins/fullcalendar/fullcalendar.min.js"></script>

<script>

    $(document).ready(function() {
        $(function () {
              $("#start_date").datepicker();
        });
    });

    $(document).on('click', '.add-event', function() {
        $('.modal-title').text('Event Schedule');
        $('#eventModal').modal('show');
    });

    $(document).ready(function() {


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


        /* initialize the external events
         -----------------------------------------------------------------*/

        $('#external-events div.external-event').each(function() {

            // store data so the calendar knows to render an event upon drop
            $(this).data('event', {
                title: $.trim($(this).text()), // use the element's text as the event title
                stick: true // maintain when user navigates (see docs on the renderEvent method)
            });

            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 1111999,
                revert: true,      // will cause the event to go back to its
                revertDuration: 0  //  original position after the drag
            });

        });


        /* initialize the calendar
                $.ajax({
            url:  '{{ url('calendar-schedule/events') }}',
            type: 'POST',
            dataType: 'json',
            data: { _token: "{{ csrf_token() }}",
            month: _month}, 
            success:function(results){

            });
         -----------------------------------------------------------------*/
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
        var _month = date.getMonth();

        
        var booking = @json($events);

                        
                $('#calendar').fullCalendar({
                    header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                events: booking,
                selectable: true,
                selectHelper: true,
                select: function(start,end, allDays){
                    $('#eventModal').modal('toggle');
                        var start_date =moment(start).format('YYYY-MM-DD');
                        var end_date =moment(end).format('YYYY-MM-DD');
      
                        $('#start_date').val(start_date);
                        $('#end_date').val(end_date);
                },
                editable: true,
                eventDrop: function(event) {
                    var id = event.id;
                    var start_date = moment(event.start).format('YYYY-MM-DD');
                    var end_date = moment(event.end).format('YYYY-MM-DD');

                    $.ajax({
                            url:  '{{ url('calendar-schedule/update') }}',
                            type: 'POST',
                            dataType: 'json',
                            data: { _token: "{{ csrf_token() }}",
                            id: id, start_date:start_date, end_date: end_date}, 
                            success:function(response)
                            {
                                toastr.info('Event has been updated','Success!')
                            },
                            error:function(error)
                            {
                                console.log(error)
                            },
                        });
                },
                 eventClick: function(event){
                    var data = event.id;
                    $('#confirmDelete').modal({ backdrop: 'static', keyboard: false })
                    .on('click', '#delete-btn', function(){
                        $(this).attr("disabled","disabled");
                        document.location.href="/calendar-schedule/delete/"+data;
                    });

                },
                selectAllow: function(event)
                {
                    return moment(event.start).utcOffset(false).isSame(moment(event.end).subtract(1, 'second').utcOffset(false), 'day');
                },

            }); 

        // Edit a post
        $(document).on('click', '.edit-title', function() {
            $('#id_edit').val($(this).data('id'));
            $('#title_edit').val($(this).data('title'));
            $('#EditeventModal').modal('show');
        }); 

    });


</script>
@endsection
