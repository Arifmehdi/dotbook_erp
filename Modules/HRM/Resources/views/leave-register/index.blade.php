@extends('layout.master')
@section('title', 'Leavel Register - ')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .sorting_disabled {
            background: none;
        }



        .daterangepicker .calendar-table tr th {
            padding-top: 2px !important;
            padding-bottom: 2px !important;
            background-color: #e7e7e7 !important;
            color: black !important;
            border-radius: unset;
            line-height: unset;
        }
    </style>
@endpush

@section('content')
    <div class="body-wraper">
        <div class="sec-name">
            <div class="employee-header">
                <h6>{{ __('Leave Register') }}</h6>
            </div>
            <div class="d-flex">
                <div id="exportButtonsContainer"></div>
            </div>
            <div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm  float-end back-button">
                    <i class="fa-thin fa-left-to-line fa-2x"></i>
                    <br>
                    @lang('menu.back')
                </a>
            </div>
        </div>
        <div class="row g-0">
            <div class="col-md-12 p-15 pb-0">
                <div class="form_element m-0 rounded">
                    <div class="element-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form action="{{ route('hrm.leave_report_print') }}" method="get" id="filter_form"
                                    target="_blank">
                                    @csrf
                                    <div class="form-group row align-items-end g-2">
                                        <div class="col-xl-4 col-md-4">
                                            <label><strong>{{ __('Employee') }} </strong></label>
                                            <select name="employee_id" class="form-control submitable form-select"
                                                id="employee_id">
                                                <option value="">{{ __('Select an Employee') }}</option>
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->id }}">{{ $employee->employee_id }}
                                                        -{{ $employee->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-xl-4 col-md-4">
                                            <label><strong>{{ __('Select Year') }} </strong></label>
                                            <select class="form-control form-control-sm form-select" name="year">
                                                @foreach ($years as $year)
                                                    <option value="{{ $year }}"
                                                        @if (date('Y') == $year) selected @endif>
                                                        {{ $year }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-xl-4 col-md-4">
                                            <button href="{{ route('hrm.leave_report_print') }}" target="_blank"
                                                class="btn btn-sm btn-info">Get Leave
                                                Report</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Form -->
    <form id="delete_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>

@endsection

@push('js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $('#employee_id').select2();
        $('#hrm_department_id').select2();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table;
        $(document).ready(function() {
            var allRow = '';
            var trashedRow = '';
            table = $('.attendance_table').DataTable({
                dom: "lBfrtip",
                buttons: [{
                        extend: 'pdf',
                        text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                        className: 'pdf btn text-white btn-sm px-1',
                        exportOptions: {
                            columns: 'th:not(:last-child)'
                        }
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                        className: 'pdf btn text-white btn-sm px-1',
                        exportOptions: {
                            columns: 'th:not(:last-child)'
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                        className: 'pdf btn text-white btn-sm px-1',
                        exportOptions: {
                            columns: 'th:not(:last-child)'
                        }
                    },
                ],

                "pageLength": parseInt(
                    "{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
                "lengthMenu": [
                    [10, 25, 50, 100, 500, 1000, -1],
                    [10, 25, 50, 100, 500, 1000, "All"]
                ],
                processing: true,
                serverSide: true,
                searchable: true,
                "ajax": {
                    // "url": "{{ route('hrm.persons.index') }}",
                    "url": "{{ route('hrm.attendance_log.index') }}",
                    "data": function(data) {
                        //filter options
                        data.employee_id = $('#employee_id').val();
                        data.section_id = $('#section_id').val();
                        data.hrm_department_id = $('#hrm_department_id').val();
                        data.sub_section_id = $('#sub_section_id').val();
                        data.designation_id = $('#designation_id').val();
                        data.shift_id = $('#shift_id').val();
                        data.date_range = $('.submitable_input').val();
                        data.employment_status = $('#employment_status').val();
                        data.showTrashed = $('#trashed_item').attr('showtrash');
                        // data.date_range = $('#date_range').val();
                    }
                },
                "drawCallback": function(data) {
                    allRow = data.json.allRow;
                    $('#all_item').text('All (' + allRow + ')');
                },
                initComplete: function() {

                    var toolbar =
                        `<div class="d-flex">
                    <div style="width: 120px;">
                            <a href="#" style="color:#2688cd" class="font-weight-bold" id="all_item">All</a>
                    </div>
                </div>`;
                    $("div.dataTables_filter").prepend(toolbar);
                    $("div.dataTables_filter").addClass('d-flex');
                    $('#all_item').text('All (' + allRow + ')');
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        orderable: false
                    },
                    {
                        name: 'at_date_format',
                        data: 'at_date_format'
                    },
                    {
                        name: 'employee',
                        data: 'employee'
                    },
                    {
                        name: 'employee_name',
                        data: 'employee_name'
                    },
                    {
                        name: 'section',
                        data: 'section'
                    },
                    {
                        name: 'clock_in',
                        data: 'clock_in'
                    },
                    {
                        name: 'clock_out',
                        data: 'clock_out'
                    },
                    {
                        name: 'shift',
                        data: 'shift'
                    },
                    {
                        name: 'status',
                        data: 'status'
                    },
                    {
                        name: 'action',
                        data: 'action'
                    },
                ],
                "lengthMenu": [
                    [10, 25, 50, 100, 500, 1000, -1],
                    [10, 25, 50, 100, 500, 1000, "All"]
                ],

            });


            table.buttons().container().appendTo('#exportButtonsContainer');
            $.ajax({
                url: "{{ route('hrm.v1.departments.index') }}",
                type: 'get',
                dataType: 'json',
                success: function(data) {
                    $.each(data.data, function(key, val) {
                        $('#hrm_department_id').append('<option value="' + val.id + '">' + val
                            .name +
                            '</option>');
                    });
                }
            });
            $.ajax({
                "url": "{{ route('hrm.sections.index') }}",
                type: 'get',
                dataType: 'json',
                success: function(data) {
                    $.each(data.data, function(key, val) {
                        $('#section_id').append('<option value="' + val.id + '">' + val.name +
                            '</option>');
                    });
                }
            });
            $.ajax({
                "url": "{{ route('hrm.subsections.index') }}",
                type: 'get',
                dataType: 'json',
                success: function(data) {
                    $.each(data.data, function(key, val) {
                        $('#sub_section_id').append('<option value="' + val.id + '">' + val
                            .name +
                            '</option>');
                    });
                }
            });
            $.ajax({
                url: "{{ route('hrm.v1.designations.index') }}",
                type: 'get',
                dataType: 'json',
                success: function(data) {
                    $.each(data.data, function(key, val) {
                        $('#designation_id').append('<option value="' + val.id + '">' + val
                            .name +
                            '</option>');
                    });
                }
            });
            $.ajax({
                url: "{{ route('hrm.v1.shifts.index') }}",
                type: 'get',
                dataType: 'json',
                success: function(data) {
                    $.each(data.data, function(key, val) {
                        $('#shift_id').append('<option value="' + val.id + '">' + val.name +
                            '</option>');
                    });
                }
            });
            $(document).on('click', '.btn_remove', function() {
                $(this).closest('tr').remove();
            });

            // Employee on change
            $(document).on('change', '#employee_id', function() {
                var employee_id = $(this).val();
                var count = 0;
                $('.create_attendance_table table').find('tr').each(function() {
                    if ($(this).data('employee_id') == employee_id) {
                        count++;
                    }
                });

                if (employee_id && count == 0) {
                    $('.data_preloader').show();
                    $.ajax({
                        url: "{{ url('hrm/attendances/persons/') }}" + "/" + employee_id,
                        type: 'get',
                        success: function(data) {
                            $('#attendance_row').append(data);
                            $('.data_preloader').hide();
                        }
                    });
                }
            });
            //Submit filter form by select input changing
            $(document).on('change', '.submitable', function() {

                table.ajax.reload();
            });

            // update new data
            $(document).on('submit', '#edit_attendance_form', function(e) {
                e.preventDefault();
                $('.loading_button').hide();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                $('.error').html('');

                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {
                        toastr.success(data);
                        $('#edit_attendance_form')[0].reset();
                        $('.loading_button').hide();
                        table.ajax.reload();
                        $('#editModal').modal('hide');
                    },
                    error: function(error) {
                        $('.loading_button').hide();
                        toastr.error('Something went wrong');
                        $('#editModal').modal('hide');
                        // toastr.error(error.responseJSON.message);
                    }
                });
            });
        });

        $(document.body).on('click', '.check1', function(event) {
            var allItem = $('.check1');
            var array = $.map(allItem, function(el, index) {
                return [el]
            })
            var allChecked = array.every(isSameAnswer);

            function isSameAnswer(el, index, arr) {
                if (index === 0) {
                    return true;
                } else {
                    return (el.checked === arr[index - 1].checked);
                }
            }
            if (allChecked && array[0].checked) {
                $('#is_check_all').prop('checked', true);
            } else {
                $('#is_check_all').prop('checked', false);
            }
        });
        //trashed item
        $(document).on('click', '#trashed_item', function(e) {
            e.preventDefault();
            $(this).attr("showtrash", true);
            $('.check1').prop('checked', false)
            $(this).addClass("font-weight-bold");
            $('.attendance_table').DataTable().draw(false);
            $('#is_check_all').prop('checked', false);
            $('#all_item').removeClass("font-weight-bold");
            $("#delete_option").css('display', 'block');
            $("#restore_option").css('display', 'block');
            $("#move_to_trash").css('display', 'none');
        })
        //all item
        $(document).on('click', '#all_item', function(e) {
            e.preventDefault();
            trashed_item = $('#trashed_item');
            $('#is_check_all').prop('checked', false);
            $('.check1').prop('checked', false);
            trashed_item.attr("showtrash", false);
            $(this).addClass("font-weight-bold");
            $('.attendance_table').DataTable().draw(false);
            $('#trashed_item').removeClass("font-weight-bold")
            $("#delete_option").css('display', 'none');
            $("#restore_option").css('display', 'none');
            $("#move_to_trash").css('display', 'block');
        })
        $(document.body).on('click', '#is_check_all', function(event) {
            var checked = event.target.checked;
            if (true == checked) {
                $('.check1').prop('checked', true);
            }
            if (false == checked) {
                $('.check1').prop('checked', false);
            }
        });
        $('#is_check_all').parent().addClass('text-center');
        $(document.body).on('click', '.check1', function(event) {
            var allItem = $('.check1');
            var array = $.map(allItem, function(el, index) {
                return [el]
            })
            var allChecked = array.every(isSameAnswer);

            function isSameAnswer(el, index, arr) {
                if (index === 0) {
                    return true;
                } else {
                    return (el.checked === arr[index - 1].checked);
                }
            }
            if (allChecked && array[0].checked) {
                $('#is_check_all').prop('checked', true);
            } else {
                $('#is_check_all').prop('checked', false);
            }
        });
        $(document).on('click', '#edit', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    $('#edit_modal_body').html(data);
                    $('#editModal').modal('show');
                },
                error: function(error) {
                    $('.loading_button').hide();
                    toastr.error(error.responseJSON.message);
                }
            });
        });
        // Show
        $(document).on('click', '#show', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    $('#show_modal_body').html(data);
                    $('#showModal').modal('show');
                },
                error: function(error) {
                    $('.loading_button').hide();
                    toastr.error(error.responseJSON.message);
                }
            });
        });

        $('.submitable_input').on('hide.daterangepicker', function(ev, picker) {
            // $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            table.ajax.reload();
        });

        $('.submitable_input').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            table.ajax.reload();
        });
        //date range picker
        $(function() {
            $('.reportrange').daterangepicker({
                autoUpdateInput: false,
                applyClass: 'btn-primary',
                cancelClass: 'btn-secondary',
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'Last Year': [moment().startOf('year').subtract(1, 'year'), moment().endOf('year')
                        .subtract(1, 'year')
                    ],
                }

            });
        });
    </script>
    <script>
        //for month and year count
        var minOffset = 0,
            maxOffset = 10; // Change to whatever you want // minOffset = 0 for current year
        var thisYear = (new Date()).getFullYear();
        var m_names = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October',
            'November', 'December'
        ];
        var month = 0 // month = (new Date()).getMonth(); // for cuurent month
        for (var j = month; j <= 11; j++) {
            var months = m_names[0 + j].slice(0, 9);
            $('<option>', {
                value: j + 1,
                text: months
            }).appendTo("#month");
        }
        for (var i = minOffset; i <= maxOffset; i++) {
            var year = (thisYear + i) - 2;
            $('<option>', {
                value: year,
                text: year
            }).appendTo("#year");
        }
    </script>
@endpush
