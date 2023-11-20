@extends('layout.master')
@section('title', 'Appointment Letter - ')
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
                <h6>{{ __('Employees appointment letter test') }}</h6>
            </div>
            <x-all-buttons>
                <x-slot name="before">
                    <span class="btn-sm loading_button display-none"><b>Print Generating...</b></span>
                    <a class="btn text-white btn-sm px-2" id="appoinmentLetter"><span><i class="fa-thin fa-print fa-2x"
                                target="_blank"></i><br> Print Appointment</span></a>
                </x-slot>
            </x-all-buttons>
        </div>
        <div class="row g-0">
            <div class="col-md-12 p-15 pb-0">
                <div class="form_element m-0 rounded">
                    <div class="element-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="filter_form">
                                    <div class="form-group row">
                                        {{-- filter goes here..  @include('hrm::employees.employee-filter-partial.filter'); --}}
                                        @include('hrm::employees.employee-filter-partial.filter')

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form id="bulk_action_form" action="{{ route('hrm.print.appointment.Letter-2') }}" method="POST" target="_blank">
            @csrf
            <div class="p-15">
                <div class="row mt-1">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                                </div>
                                <form id="bulk_action">
                                    <div class="table-responsive h-350" id="data-list">
                                        <table class="display data_tbl data__table employee-table">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>
                                                        <div>
                                                            <input type="checkbox" id="is_check_all"
                                                                style="margin-left: 5px">
                                                        </div>
                                                    </th>
                                                    <th class="text-start all">{{ __('SL.') }}</th>
                                                    <th>{{ __('Employee ID') }}</th>
                                                    <th>{{ __('Photo') }}</th>
                                                    <th>{{ __('Employee Name') }}</th>
                                                    <th>{{ __('Department') }}</th>
                                                    <th>{{ __('Section') }}</th>
                                                    <th>{{ __('Designation') }}</th>
                                                    <th>{{ __('Phone') }}</th>
                                                    <th>{{ __('Present Address') }}</th>
                                                    <th>{{ __('Joining') }}</th>
                                                    <th>{{ __('Status') }}</th>
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
    <script src="{{ asset('plugins/print_this/printThis.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#employee_id').select2();
        $('#grade_id').select2();
        $('#section_id').select2();
        $('#designation_id').select2();
        $('#hrm_department_id').select2();
        $('#employment_status').select2();
        $(document).ready(function() {
            var allRow = '';
            var table = $('.employee-table').DataTable({
                dom: "lBfrtip",
                buttons: [{
                    extend: 'pdf',
                    text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [0, 1, 2, 4, 5, 6, 7, 8, 9, 10]
                    }
                }, {
                    extend: 'excel',
                    text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [0, 1, 2, 4, 5, 6, 7, 8, 9, 10]
                    }
                }, {
                    extend: 'print',
                    text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [0, 1, 2, 4, 5, 6, 7, 8, 9, 10]
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
                    "url": "{{ route('hrm.appointmentLetter-2') }}",
                    "data": function(data) {
                        //filter options
                        //ajax filter goes here... @include('hrm::employees.employee-filter-partial.ajax-data-filter')
                        @include('hrm::employees.employee-filter-partial.ajax-data-filter');

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
                    $("div.dataTables_filter #bulk_action_field").parent().css('width', '160px');
                    $("#restore_option").css('display', 'none');
                    $("#delete_option").css('display', 'none');
                    $("#move_to_trash").css('display', 'block');
                    $('#all_item').text('All (' + allRow + ')');
                    $('#is_check_all').prop('checked', false);
                    $('#trashed_item').text('');
                    $('#trash_separator').text('');
                    $("#bulk_action_field option:selected").prop("selected", false);
                },

                columns: [{
                        name: 'check',
                        data: 'check',
                        sWidth: '3%',
                        orderable: false,
                        targets: 0
                    },
                    {
                        name: 'DT_RowIndex',
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        name: 'employee_id',
                        data: 'employee_id'
                    },
                    {
                        name: 'photo',
                        data: 'photo'
                    },
                    {
                        name: 'employee_name',
                        data: 'employee_name'
                    },
                    {
                        name: 'departmentID',
                        data: 'departmentID'
                    },
                    {
                        name: 'section_id',
                        data: 'section_id'
                    },
                    {
                        name: 'designationID',
                        data: 'designationID'
                    },
                    {
                        name: 'phone',
                        data: 'phone'
                    },
                    {
                        name: 'address',
                        data: 'address'
                    },
                    {
                        name: 'joining',
                        data: 'joining'
                    },
                    {
                        name: 'status',
                        data: 'status'
                    },

                ],
                "lengthMenu": [
                    [10, 25, 50, 100, 500, 1000, -1],
                    [10, 25, 50, 100, 500, 1000, "All"]
                ],
            });

            table.buttons().container().appendTo('#exportButtonsContainer');

            // Active status
            $(document).on('click', '#activeEmp', function(e) {
                e.preventDefault();
                $('.data_preloader').show();
                var url = $(this).attr('href');
                $.confirm({
                    'title': 'Active Confirmation',
                    'message': 'Are you sure?',
                    'buttons': {
                        'Yes': {
                            'class': 'yes btn-danger',
                            'action': function() {
                                $('.data_preloader').show();
                                $.ajax({
                                    url: url,
                                    type: 'get',
                                    success: function(data) {
                                        toastr.success(data);
                                        $('.data_preloader').hide();
                                        table.ajax.reload();
                                    },
                                    error: function(err) {
                                        $('.data_preloader').hide();
                                        if (err.status == 0) {
                                            toastr.error(
                                                'Net Connetion Error. Reload This Page.'
                                                );
                                        } else {
                                            toastr.error(
                                                'Server Error. Please contact to the support team.'
                                                );
                                        }
                                    }
                                });
                            }
                        },
                        'No': {
                            'class': 'no btn-primary',
                            'action': function() {
                                $('.data_preloader').hide();
                            }
                        }
                    }
                });
            });

            // all ajax to get data goes here ..
            @include('hrm::employees.employee-filter-partial.ajax')
        })

        $(document.body).on('click', '#is_check_all', function(event) {
            //
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


        $(document).ready(function() {
            $(document).on('click', '#appoinmentLetter', function(e) {
                e.preventDefault();
                const form = document.getElementById('bulk_action_form');
                form.submit();
                // const formData = $('#bulk_action_form').serialize();
                // var route = "{{ route('hrm.printAppointmentLetter') }}"
                // $.ajax({
                //     url: route,
                //     type: 'POST',
                //     data: formData,
                //     success: function(data) {
                //         var link = document.createElement('a');
                //         link.href = window.URL.createObjectURL(data);
                //         link.download = `SalarySheet.pdf`;
                //         link.click();
                //     },
                //     error: function(error) {
                //     }
                // });
            });
        });
    </script>
@endpush
