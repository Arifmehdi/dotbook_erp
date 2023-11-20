@extends('layout.master')
@php
    $months_array = array_reduce(range(1, 12), function ($result, $month) {
        $result[$month] = date('F', mktime(0, 0, 0, $month, 10));
        return $result;
    });
@endphp
@section('title', 'Salary Generator - ')
@push('css')

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
            <div class="section-header">
                <h6>{{ __('Salary Generator') }}</h6>
            </div>
            <x-all-buttons>
                <x-slot name="before">
                    <span class="loading_button fs-5 display-none me-4"><b>Generating...</b></span>
                    @if (auth()->user()->can('hrm_awards_create'))
                        <a href="#" data-bs-toggle="modal" id="print_list" class="btn text-white btn-sm">
                            <span>
                                <i class="fa-regular fa-list"></i>
                                <br> {{ __('Generate List') }}
                            </span>
                        </a>
                    @endif

                    @if (auth()->user()->can('hrm_payroll_payslip_generate'))
                        <a href="#" data-bs-toggle="modal" id="print_payslip" class="btn text-white btn-sm">
                            <span>
                                <i class="fa-regular fa-circle-dollar"></i>
                                <br> {{ __('Generate Payslip') }}
                            </span>
                        </a>
                    @endif

                    @if (auth()->user()->can('hrm_payroll_custom_excel'))
                        <a href="#" data-bs-toggle="modal" id="export_excel_button" class="btn text-white btn-sm">
                            <span>
                                <i class="fa-regular fa-file-excel"></i>
                                <br> {{ __('Generate Excel') }}
                            </span>
                        </a>
                    @endif
                </x-slot>
            </x-all-buttons>
        </div>

        <form id="bulk_action_form" action="{{ route('hrm.awards.bulk-action') }}" method="POST">
            <div class="p-15">
                <div class="row g-0">
                    <div class="col-md-12">
                        <div class="form_element m-0 rounded">
                            <div class="element-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form id="filter_form">
                                            <div class="form-group row">
                                                <div class="col-xl-2 col-md-4">
                                                    <label><strong>{{ __('Employee') }} </strong></label>
                                                    <select name="employee_id" id="employee_id"
                                                        class="form-control submitable">
                                                        <option value="">@lang('menu.all')</option>
                                                        @foreach ($employees as $employee)
                                                            <option value="{{ $employee->id }}">{{ $employee->employee_id }}
                                                                - {{ $employee->name }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-xl-2 col-md-4">
                                                    <label><strong>{{ __('Section') }} </strong></label>
                                                    <select name="section_id" class="form-control submitable"
                                                        id="section_id">
                                                        <option value="">{{ __('All') }}</option>
                                                        @foreach ($departments as $department)
                                                            <option value="{{ $department->id }}" disabled
                                                                style="color:blue"><strong>
                                                                    {{ $department->name }}</strong>
                                                            </option>
                                                            @foreach ($department->sections as $section)
                                                                <option value="{{ $section->id }}">&nbsp;&nbsp;&nbsp; --
                                                                    {{ $section->name }}</option>
                                                            @endforeach
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-xl-2 col-md-4">
                                                    <label><strong>{{ __('Designation') }}</strong></label>
                                                    <select name="designation_id" id="designation_id"
                                                        class="form-control submitable">
                                                        <option value="">@lang('menu.all')</option>
                                                        @foreach ($designations as $designation)
                                                            <option value="{{ $designation->id }}">
                                                                {{ $designation->name }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-xl-2 col-md-4">
                                                    <label><strong>@lang('menu.shift') </strong></label>
                                                    <select name="shift_id" id="shift_id"
                                                        class="form-control submitable form-select">
                                                        <option value="">@lang('menu.all')</option>
                                                        @foreach ($shifts as $shift)
                                                            <option value="{{ $shift->id }}">{{ $shift->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-xl-2 col-md-4">
                                                    <label><strong>{{ __('Month') }}</strong></label>
                                                    <select name="month" id="month"
                                                        class="form-control submitable form-select">
                                                        <option value="">@lang('menu.all')</option>
                                                        @foreach ($months_array as $key => $month_name)
                                                            <option value="{{ $key }}"
                                                                @if (date('n') == $key) Selected @endif>
                                                                {{ $month_name }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-xl-2 col-md-4">
                                                    <label><strong>{{ __('Year') }}</strong></label>
                                                    <select class="form-control selectpicker form-control-sm submitable"
                                                        data-live-search="true" name="year" id="year">
                                                        @php
                                                            $years = \Modules\Core\Utils\DateTimeUtils::years_array();
                                                        @endphp
                                                        @foreach ($years as $year)
                                                            <option value="{{ $year }}"
                                                                @selected($year === intval(date('Y')))>
                                                                {{ $year }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class='form-group row'>
                                                <div class="col-xl-2 col-md-4">
                                                    <label><strong
                                                            class="text-warning">{{ __('Resign/Left Month :') }}</strong></label>
                                                    {{-- <label><b class="text-warning"> Resign/Left Month :</b></label> --}}

                                                    <select class="form-control form-control-sm submitable"
                                                        name="resign_month" id="resign_month">
                                                        <option value=""> None </option>
                                                        @foreach ($months_array as $key => $month_name)
                                                            <option value="{{ $key }}"
                                                                @if (date('n') == $key) selected @endif>
                                                                {{ $month_name }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-xl-2 col-md-4">
                                                    <label><strong
                                                            class="text-warning">{{ __('Employee Status') }}</strong></label>
                                                    <select name="type_status" class="form-control submitable"
                                                        id="type_status">
                                                        <option value="">@lang('menu.all')</option>
                                                        <option value="1" selected>{{ __('Active') }}</option>
                                                        <option value="2">{{ __('Resign') }}</option>
                                                        <option value="3">{{ __('Left') }}</option>
                                                    </select>
                                                </div>
                                                <div class="col-xl-2 col-md-4">
                                                    <label><strong>{{ __('Print Date') }}</strong></label>
                                                    <input type="date" class="form-control" value=""
                                                        id="printDate">
                                                </div>
                                                <div class="col-xl-2 col-md-4">
                                                    <label><strong>{{ __('P.Start Date') }}</strong></label>
                                                    <input type="date" class="form-control" value=""
                                                        id="startDate">
                                                </div>
                                                <div class="col-xl-2 col-md-4">
                                                    <label><strong>{{ __('P.End Date') }}</strong></label>
                                                    <input type="date" class="form-control" value=""
                                                        id="endDate">
                                                </div>

                                                <div class="col-xl-2 col-md-4">
                                                    <label><strong>{{ __('Joining Date') }}</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-calendar-week input_i"></i></span>
                                                        </div>
                                                        <input type="text" name="date_range" id="date_range"
                                                            class="form-control reportrange submitable_input date_range"
                                                            autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-0 mt-1">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                                </div>
                                <form id="bulk_action">
                                    <div class="table-responsive h-350" id="data-list">
                                        <table class="display data_tbl data__table award_table">
                                            <thead>
                                                <tr>
                                                    <th class="text-start">{{ __('Sl') }}</th>
                                                    <th class="text-start">{{ __('Employee ID') }}</th>
                                                    <th class="text-start">{{ __('Employee Name') }}</th>
                                                    <th class="text-start">{{ __('Phone') }}</th>
                                                    <th class="text-start">{{ __('Salary') }}</th>
                                                    <th class="text-start">{{ __('Mobile Banking') }}</th>
                                                    <th class="text-start">{{ __('Section') }}</th>
                                                    <th class="text-start">{{ __('Designation') }}</th>
                                                    <th class="text-start">{{ __('Shift') }}</th>
                                                    <th class="text-start">{{ __('Joining Date') }}</th>
                                                    <th class="text-start">{{ __('Type Status') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>


@endsection

@push('js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $('#employee_id').select2();
        // $('#hrm_department_id').select2();
        $('#section_id').select2();
        $('#designation_id').select2();
        $('#shift_id').select2();
        $('#grade_id').select2();
        $('#year').select2();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //Date Difference
        function dateDiffInDays(date1, date2) {
            // round to the nearest whole number
            return Math.round((date2 - date1) / (1000 * 60 * 60 * 24));
        }

        $(document).ready(function() {
            $('#enddate').on('change', function(e) {
                var date1 = $('#startdate').val();
                var date2 = $('#enddate').val();
                var daysDiff = dateDiffInDays(new Date(date1), new Date(date2));
                var totaldays = daysDiff + 1;
                if (isNaN(totaldays)) {
                    $('.num_of_days').val("");
                } else {
                    $('.num_of_days').val(totaldays);
                }
            });
            $('#startdate').on('change', function(e) {
                var date1 = $('#startdate').val();
                var date2 = $('#enddate').val();
                var daysDiff = dateDiffInDays(new Date(date1), new Date(date2));
                var totaldays = daysDiff + 1;
                if (isNaN(totaldays)) {
                    $('.num_of_days').val("");
                } else {
                    $('.num_of_days').val(totaldays);
                }

            });
        });

        $(document).ready(function() {
            var allRow = '';
            var trashedRow = '';
            // var table = $('.holiday-table').DataTable({
            var table = $('.award_table').DataTable({
                dom: "lBfrtip",
                buttons: [{
                    extend: 'pdf',
                    text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                    }
                }, {
                    extend: 'excel',
                    text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                    }
                }, {
                    extend: 'print',
                    text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                    }
                }, ],

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
                    "url": "{{ route('hrm.payrolls.sallary.list') }}",
                    "data": function(data) {
                        // employee_id section_id designation_id shift_id grade_id date_range
                        //send types of request for colums
                        data.showTrashed = $('#trashed_item').attr('showtrash');
                        //filter options
                        data.section_id = $('#section_id').val();
                        data.shift_id = $('#shift_id').val();
                        data.employment_status = $('#employment_status').val();
                        data.designation_id = $('#designation_id').val();
                        data.date_range = $('.submitable_input').val();
                        data.employee_id = $('#employee_id').val();
                        data.type_status = $('#type_status').val();

                    }
                },
                "drawCallback": function(data) {
                    allRow = data.json.allRow;
                    trashedRow = data.json.trashedRow;
                    $('#all_item').text('All (' + allRow + ')');
                    $('#is_check_all').prop('checked', false);
                    $('#trashed_item').text('');
                    $('#trash_separator').text('');
                    $("#bulk_action_field option:selected").prop("selected", false);
                    if (trashedRow > 0) {
                        $('#trash_separator').text('|');
                        $('#trashed_item').text('Trash (' + trashedRow + ')');
                    }
                    if (trashedRow < 1) {
                        $('#all_item').addClass("font-weight-bold");
                    }

                },

                initComplete: function() {

                    var toolbar = `<div class="d-flex">
                                    <div class="me-3">
                                            <a href="#" style="color:#2688cd" class="font-weight-bold" id="all_item">All</a>
                                            <span style="color:#2688cd; margin-right:3px;" id="trash_separator"></span><a style="color:#2688cd" href="#" id="trashed_item"></a>
                                    </div>
                                </div>`;

                    $("div.dataTables_filter").prepend(toolbar);
                    $("div.dataTables_filter").addClass('d-flex');
                    $("#restore_option").css('display', 'none');
                    $("#delete_option").css('display', 'none');
                    $("#move_to_trash").css('display', 'block');
                    $('#all_item').text('All (' + allRow + ')');
                    $('#is_check_all').prop('checked', false);
                    $('#trashed_item').text('');
                    $('#trash_separator').text('');
                    $("#bulk_action_field option:selected").prop("selected", false);
                    if (trashedRow > 0) {
                        $('#trash_separator').text('|');
                        $('#trashed_item').text('Trash (' + trashedRow + ')');
                    }
                },

                columns: [{
                        name: 'DT_RowIndex',
                        data: 'DT_RowIndex',
                        sWidth: '3%'
                    }, {
                        name: 'employee_id',
                        data: 'employee_id'
                    }, {
                        name: 'name',
                        data: 'name'
                    }, {
                        name: 'phone',
                        data: 'phone'
                    },
                    // {name: 'salary', data: 'salary'},
                    {
                        name: 'salary_format',
                        data: 'salary_format'
                    }, {
                        name: 'payment_&_number',
                        data: 'payment_&_number'
                    }, {
                        name: 'section_name',
                        data: 'section_name'
                    }, {
                        name: 'designation_name',
                        data: 'designation_name'
                    }, {
                        name: 'shift_name',
                        data: 'shift_name'
                    }, {
                        name: 'joining_date_format',
                        data: 'joining_date_format'
                    }, {
                        name: 'employment_status',
                        data: 'employment_status'
                    },
                ],
                "lengthMenu": [
                    [10, 25, 50, 100, 500, 1000, -1],
                    [10, 25, 50, 100, 500, 1000, "All"]
                ],
            });

            table.buttons().container().appendTo('#exportButtonsContainer');

            // //Bulk Action
            // $('#bulk_action_form').on('submit', function(e) {
            //     e.preventDefault();
            //     var url = $(this).attr('action');
            //     var request = $(this).serialize();


            //     $.ajax({
            //         url: url
            //         , type: 'POST'
            //         , data: request
            //         , success: function(data) {
            //             toastr.success(data);
            //             table.ajax.reload();
            //         },
            //         error: function(error) {
            //             toastr.error(error.responseJSON.message);
            //         }
            //     });
            // });

            //Add new data
            $('#add_form').on('submit', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                $('.error').html('');

                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {
                        toastr.success(data);
                        $('#add_form')[0].reset();
                        $('.loading_button').hide();
                        table.ajax.reload();
                        $('#addModal').modal('hide');
                    },

                    error: function(error) {
                        $('.loading_button').hide();
                        toastr.error(error.responseJSON.message);
                        ///field error.
                        $.each(error.responseJSON.errors, function(key, error) {
                            $('.error_' + key + '').html(error[0].replace(' id ', ' '));
                        });
                    }
                });
            });
            //Submit filter form by select input changing
            $(document).on('change', '.submitable', function() {

                table.ajax.reload();

            });

            $('.submitable_input').on('hide.daterangepicker', function(ev, picker) {
                // $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                table.ajax.reload();
            });

            $('.submitable_input').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format(
                    'MM/DD/YYYY'));
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
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment()
                            .subtract(1, 'month').endOf('month')
                        ],
                        'This Year': [moment().startOf('year'), moment().endOf('year')],
                        'Last Year': [moment().startOf('year').subtract(1, 'year'), moment().endOf(
                            'year').subtract(1, 'year')],
                    }

                });
            });
        });



        $('#print_list').on('click', function(e) {
            e.preventDefault();
            $('.loading_button').attr("style", "display: block;");
            //     $('#employee_id').val() + ' <= employee Id'+ $('#section_id').val() + ' <= section Id '+$('#resign_month').val()+ ' <= resign month ' + $('#shift_id').val() + ' <= shift ID' +$('#month').val() + ' <= month' +$('#year').val() + ' <= year' +$('#type_status').val() + ' <= type_status' +$('#printDate').val() + ' <= tprint date' +$('#startDate').val() + ' <= start date' +$('#endDate').val() + ' <= endDate' );
            $.ajax({
                url: "{{ route('hrm.payrolls.salary.list.print') }}",
                type: 'get',
                data: {
                    section_id: $('#section_id').val(),
                    employee_id: $('#employee_id').val(),
                    resign_month: $('#resign_month').val(),
                    shift_id: $('#shift_id').val(),
                    month: $('#month').val(),
                    year: $('#year').val(),
                    type_status: $('#type_status').val(),
                    printDate: $('#printDate').val(),
                    startDate: $('#startDate').val(),
                    endDate: $('#endDate').val()

                },
                success: function(data) {
                    $('.loading_button').hide();
                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('css/print/attendance.print.css') }}",
                        removeInline: false,
                        printDelay: 12000,
                        header: null,
                        footer: null,
                    });
                }
            });
        });

        $('#print_payslip').on('click', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            $.ajax({
                url: "{{ route('hrm.payrolls.payslip.print') }}",
                type: 'get',
                data: {
                    section_id: $('#section_id').val(),
                    employee_id: $('#employee_id').val(),
                    resign_month: $('#resign_month').val(),
                    shift_id: $('#shift_id').val(),
                    month: $('#month').val(),
                    year: $('#year').val(),
                    type_status: $('#type_status').val(),
                    printDate: $('#printDate').val(),
                    startDate: $('#startDate').val(),
                    endDate: $('#endDate').val()
                },
                success: function(data) {
                    $('.loading_button').hide();
                    $(data).printThis({
                        debug: false,
                        importCSS: false // set true if not work on production
                            ,
                        importStyle: false // set true if not work on production
                            ,
                        loadCSS: "{{ asset('css/print/attendance.print.css') }}",
                        removeInline: false,
                        printDelay: 7000,
                        header: null,
                        footer: null,
                    });
                },
                error: function(err) {
                    $('.loading_button').hide();
                }
            });
        });

        $('#export_excel_button').on('click', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            $.ajax({
                url: "{{ route('hrm.payrolls.salary.list.excel_export') }}",
                type: 'get',
                cache: false,
                xhrFields: {
                    responseType: 'blob'
                },
                data: {
                    section_id: $('#section_id').val(),
                    employee_id: $('#employee_id').val(),
                    resign_month: $('#resign_month').val(),
                    shift_id: $('#shift_id').val(),
                    month: $('#month').val(),
                    year: $('#year').val(),
                    type_status: $('#type_status').val(),
                    printDate: $('#printDate').val(),
                    startDate: $('#startDate').val(),
                    endDate: $('#endDate').val()
                },
                success: function(data) {
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(data);
                    link.download = `SalarySheet.xlsx`;
                    link.click();
                    $('.loading_button').hide();
                },
                error: function(err) {
                    $('.loading_button').hide();
                }
            });
        });
    </script>
@endpush
