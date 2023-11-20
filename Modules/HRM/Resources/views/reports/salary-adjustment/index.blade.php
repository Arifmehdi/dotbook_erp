@extends('layout.master')
@section('title', 'Salary Adjustment Report - ')
@push('css')

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .sorting_disabled {
            background: none;
        }



        .employee-table img {
            width: 30px;
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
                <h6>{{ __('Salary Adjustment Reports') }}</h6>
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
                                            {{-- overtime-filter-partial.filter'); --}}
                                            @include('hrm::salary_adjustment.adjustment-filter-partial.filter')

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
                                <table class="display data_tbl data__table employee-table">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="text-start">@lang('menu.sl')</th>
                                            <th>{{ __('Employee ID') }}</th>
                                            <th>{{ __('Photo') }}</th>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Previous Salary') }}</th>
                                            <th>{{ __('Increment/Decrement') }}</th>
                                            <th>{{ __('Adjustment Type') }}</th>
                                            <th>{{ __('Total Salary') }}</th>
                                            <th>{{ __('Month') }}</th>
                                            <th>{{ __('Year') }}</th>
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
        $('#employee_id').select2();
        $('.employee2').select2();
        $('#month').select2();
        $('#year').select2();
        $('#type').select2();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            var allRow = '';
            var table = $('.employee-table').DataTable({
                dom: "lBfrtip",
                buttons: [{
                    extend: 'pdf',
                    text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7]
                    }
                }, {
                    extend: 'excel',
                    text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7]
                    }
                }, {
                    extend: 'print',
                    text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7]
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
                    "url": "{{ route('hrm.salary_adjustment_report') }}",
                    "data": function(data) {
                        //filter options
                        @include('hrm::salary_adjustment.adjustment-filter-partial.ajax-data-filter');
                        data.date_range = $('.submitable_input').val();

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

                    var toolbar =
                        `<div class="d-flex">
                    <div style="width: 120px;">
                            <a href="#" style="color:#2688cd" class="font-weight-bold" id="all_item">All</a>
                            <span style="color:#2688cd; margin-right:3px;" id="trash_separator"></span><a style="color:#2688cd" href="#" id="trashed_item"></a>
                    </div>

                </div>`;

                    $("div.dataTables_filter").prepend(toolbar);
                    $("div.dataTables_filter").addClass('d-flex');
                    $('#all_item').text('All (' + allRow + ')');
                },

                columns: [{
                        name: 'DT_RowIndex',
                        data: 'DT_RowIndex'
                    },
                    {
                        name: 'employeeId',
                        data: 'employeeId'
                    },
                    {
                        name: 'photo',
                        data: 'photo'
                    },
                    {
                        name: 'name',
                        data: 'name'
                    },
                    {
                        name: 'salary',
                        data: 'salary'
                    },
                    {
                        name: 'amount',
                        data: 'amount'
                    },
                    {
                        name: 'type',
                        data: 'type'
                    },
                    {
                        name: 'totalSalary',
                        data: 'totalSalary'
                    },
                    {
                        name: 'month',
                        data: 'month'
                    },
                    {
                        name: 'year',
                        data: 'year'
                    },

                    // {name: 'action' , data: 'action'  },

                ],
                "lengthMenu": [
                    [10, 25, 50, 100, 500, 1000, -1],
                    [10, 25, 50, 100, 500, 1000, "All"]
                ],
            });

            table.buttons().container().appendTo('#exportButtonsContainer');


            // all fliter data append here
            @include('hrm::salary_adjustment.adjustment-filter-partial.ajax');
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
