@extends('layout.master')
@section('title', 'Leave Application Report - ')
@push('css')

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .sorting_disabled {
            background: none;
        }


    </style>
@endpush

@section('content')
    <div class="body-wraper">
        <div class="sec-name">
            <div class="employee-header">
                <h6>{{ __('Leave Application Reports') }}</h6>
            </div>
            <x-all-buttons />
        </div>

        <div class="p-15">
            <div class="row g-0">
                <div class="col-md-12">
                    <div class="form_element m-0 rounded">
                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form id="filter_form">
                                        <div class="form-group row">
                                            <div class="col-xl-3 col-md-4">
                                                <label><strong>{{ __('Employee') }} </strong></label>
                                                <select name="employee_id" class="form-control submitable form-select"
                                                    id="employee_id">
                                                    <option value="">@lang('menu.all')</option>
                                                    @foreach ($employees as $employee)
                                                        <option value="{{ $employee->id }}">{{ $employee->employee_id }}
                                                            -{{ $employee->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @include('hrm::reports.adjustment-filter-partial.filter')
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                            </div>
                            <div class="table-responsive h-350" id="data-list">
                                <table class="display data_tbl data__table leave_application_table">
                                    <thead>
                                        <tr>
                                            <th class="text-start">@lang('menu.sl')</th>
                                            <th class="text-start all">{{__('Employee ID')}} </th>
                                            <th class="text-start all">{{__('Employee Name')}} </th>
                                            <th class="text-start all">{{__('Application Type')}}</th>
                                            <th class="text-start all">{{__('Start Date')}}</th>
                                            <th class="text-start all">{{__('End Date')}}</th>
                                            <th class="text-start all">{{__('Approved Day')}}</th>
                                            <th class="text-start all">{{__('Status')}}</th>
                                            <th class="text-start all">{{__('Paid / Unpaid')}}</th>
                                            <th class="text-start all">{{__('Attachment')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#employee_id').select2();
        $('.employee2').select2();
        $('#leave_type').select2();
        $('#type').select2();
        //Date Difference
        function dateDiffInDays(date1, date2) {
            // round to the nearest whole number
            return Math.round((date2 - date1) / (1000 * 60 * 60 * 24));
        }
        var table;
        $(document).ready(function() {
            var allRow = '';
            var trashedRow = '';
            table = $('.leave_application_table').DataTable({
                dom: "lBfrtip",
                buttons: [{
                    extend: 'pdf',
                    text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                }, {
                    extend: 'excel',
                    text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                }, {
                    extend: 'print',
                    text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
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
                    "url": "{{ route('hrm.leave_report') }}",
                    "data": function(data) {
                        //filter options
                        @include('hrm::reports.adjustment-filter-partial.ajax-data-filter');
                        data.leave_type = $('#leave_type').val();
                    }
                },
                "drawCallback": function(data) {
                    allRow = data.json.allRow;
                    trashedRow = data.json.trashedRow;
                    $('#all_item').text('All (' + allRow + ')');
                },

                initComplete: function() {

                    var toolbar = `<div class="d-flex">
                                    <div class="me-3">
                                            <a href="#" style="color:#2688cd" class="font-weight-bold" id="all_item">All</a>
                                            <span style="color:#2688cd; margin-right:3px;" id="trash_separator"></span><a style="color:#2688cd" href="#" id="trashed_item"></a>
                                    </div>
                                    <div class="form-group row g-2">
                                        <div class="col-xl-2 col-md-4">

                                        </div>
                                    </div>
                                </div>`;
                    $("div.dataTables_filter").prepend(toolbar);
                    $("div.dataTables_filter").addClass('d-flex');
                    $('#all_item').text('All (' + allRow + ')');
                },

            columns: [
                    {data: 'DT_RowIndex', searchable: false, orderable: false},
                    {name: 'employee_id', data: 'employee_id'},
                    {name: 'employeeName', data: 'employeeName'},
                    {name: 'leave_type_id', data: 'leave_type_name'},
                    {name: 'from_date', data: 'from_date'},
                    {name: 'to_date', data: 'to_date'},
                    {name: 'approve_day', data: 'approve_day'},
                    {name: 'status', data: 'status'},
                    {name: 'isPaid', data: 'isPaid'},
                    {name: 'attachment', data: 'attachment'},
                ],
                "lengthMenu": [
                    [10, 25, 50, 100, 500, 1000, -1],
                    [10, 25, 50, 100, 500, 1000, "All"]
                ],
            });

            table.buttons().container().appendTo('#exportButtonsContainer');

            // Leave Type
            $.ajax({
                url: "{{ route('hrm.leave-types.index') }}",
                type: 'get',
                dataType: 'json',
                success: function(data) {
                    $.each(data.data, function(key, val) {
                        $('#leave_type').append('<option value="' + val.id + '">' + val.name +
                            '</option>');
                    });
                }
            });

        });
        // all fliter data append here
        @include('hrm::reports.adjustment-filter-partial.ajax');
    </script>
@endpush
