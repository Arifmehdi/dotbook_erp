@extends('layout.master')
@section('title', 'Event Calendar - ')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
@endpush

@push('js')
    <<<<<<< HEAD======={{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" /> --}} {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" /> --}}>>>>>>> 17f8ca8f560b23655999b8ecb109972c8a680237
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endpush
    @section('content')
        <div class="modal fade" id="holidayModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create Holidays</h5>
                        <button type="button" class="btn btn-sm btn-danger px-2" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-regular fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body border-0">
                        <input type="text" class="form-control" id="title" placeholder="Enter your holiday name">
                        <span id="titleError" class="text-danger"></span>
                    </div>
                    <div class="modal-footer p-2">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" id="saveBtn" class="btn btn-sm btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-15 event-calendar">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center">Event Calendar </h4>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="mb-5">
                                <table class="table table-hover event-table">
                                    <h3 class="event-table-title">Passed Events</h3>
                                    <thead>
                                        <tr class="table-dark">
                                            <th>SL</th>
                                            <th>Title</th>
                                            <th>Date</th>
                                            <th>Day</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($asc_holidays as $key => $asc_holiday)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>{{ $asc_holiday->name }}</td>
                                                <td>{{ date('d F Y', strtotime($asc_holiday->from)) }}</td>
                                                <td>{{ $asc_holiday->num_of_days }}</td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <div>
                                <table class="table table-hover event-table">
                                    <h3 class="event-table-title">Upcoming events</h3>
                                    <thead>
                                        <tr class="table-dark">
                                            <th>SL</th>
                                            <th>Title</th>
                                            <th>Date</th>
                                            <th>Day</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dsc_holidays as $key => $dsc_holiday)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>{{ $dsc_holiday->name }}</td>
                                                <td>{{ date('d F Y', strtotime($dsc_holiday->from)) }}</td>
                                                <td>{{ $dsc_holiday->num_of_days }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @push('js')
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).ready(function() {
                var events = @json($events);
                $('#calendar').fullCalendar({
                    header: {
                        left: 'prev, next today', //
                        center: 'title',
                        right: 'month, agendaWeek agendaDay'
                    },
                    events: events,
                    selectable: true,
                    selectHelper: true,
                    select: function(start, end, allDays) {

                        $('#holidayModel').modal('show');
                        $('#saveBtn').click(function() {
                            var title = $('#title').val();
                            var start_date = moment(start).format('YYYY-MM-DD');
                            var end_date = moment(end).format('YYYY-MM-DD');
                            // alert(title+ start_date + end_date)
                            $.ajax({
                                url: "{{ route('hrm.calendar.store') }}",
                                type: "POST",
                                dataType: "json",
                                data: {
                                    title,
                                    start_date,
                                    end_date
                                },
                                success: function(response) {
                                    $('#holidayModel').modal('hide');
                                    $('#calendar').fullCalendar('renderEvent', {
                                        'title': response.name,
                                        'start': response.from,
                                        'end': response.to,
                                    });
                                    location.reload();
                                },
                                error: function(error) {
                                    if (error.responseJSON.errors) {
                                        $('#titleError').html(error.responseJSON.errors
                                            .title);
                                    }
                                },
                            });
                        });
                    },
                    editable: true,
                    eventDrop: function(event) {
                        var id = event.id;
                        var start_date = moment(event.start).format('YYYY-MM-DD');
                        var end_date = moment(event.end).format('YYYY-MM-DD');
                        $.ajax({
                            url: "{{ route('hrm.calendar.update', '') }}" + '/' + id,
                            type: "PATCH",
                            dataType: "json",
                            data: {
                                start_date,
                                end_date
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Good job!',
                                    'Holiday Updated Successfully!',
                                    'success'
                                )
                            },
                            error: function(error) {},
                        });
                    },
                    eventClick: function(event) {
                        var id = event.id;
                        const swalWithBootstrapButtons = Swal.mixin({
                            customClass: {
                                confirmButton: 'btn btn-success',
                                cancelButton: 'btn btn-danger'
                            },
                            buttonsStyling: false
                        })

                        swalWithBootstrapButtons.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, delete it!',
                            cancelButtonText: 'No, cancel!',
                            // customClass: {
                            //     confirmButton: 'swal-button-spacing',
                            //     cancelButton: 'swal-button-spacing'
                            // },
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: "{{ route('hrm.calendar.destroy', '') }}" + '/' +
                                        id,
                                    type: "DELETE",
                                    dataType: "json",
                                    success: function(response) {
                                        var id = response;
                                        $('#calendar').fullCalendar('removeEvents',
                                            response);
                                        Swal.fire(
                                            'Good job!',
                                            'Holiday Deleted Successfully',
                                            'success'
                                        )
                                    },
                                    error: function(error) {},
                                });
                            } else if (
                                /* Read more about handling dismissals below */
                                result.dismiss === Swal.DismissReason.cancel
                            ) {
                                // swalWithBootstrapButtons.fire(
                                // 'Cancelled',
                                // 'Your imaginary file is safe :)',
                                // 'error'
                                // )
                            }
                        })
                        // if(confirm('Are you sure you want to remove it ?')){

                        // }
                    }
                });
                $('.fc-button').addClass('btn btn-primary').removeClass('fc-state-default');
                $('.fc-button').on('click', function() {
                    if ($(this).hasClass('fc-state-active')) {
                        $(this).addClass('active').siblings().removeClass('active');
                    }
                });
                $('.fc-state-active').addClass('active');
            });
        </script>
    @endpush
