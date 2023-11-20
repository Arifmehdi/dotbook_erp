@extends('layout.master')
@section('title', 'Bulk ID Card - ')
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
                <h6>{{ __('Employees Bulk ID Card') }}</h6>
            </div>
            <x-all-buttons>
                <x-slot name="before">
                    <span class="btn-sm loading_button display-none"><b>Print Generating...</b></span>
                    <button class="btn text-white btn-sm px-2" id="idcard"><span><i
                                class="fa-thin fa-print fa-2x"></i><br> Print Bulk ID</span></button>
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
                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>Department </strong></label>
                                            <select name="hrm_department_id" class="form-control submitable form-select"
                                                id="hrm_department_id">
                                                <option value="">All</option>
                                                @foreach ($departments as $key => $department)
                                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>Section </strong></label>
                                            <select name="section_id" class="form-control submitable form-select"
                                                id="section_id">
                                                <option value="" selected>All</option>
                                                @foreach ($sections as $key => $section)
                                                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>Designation </strong></label>
                                            <select name="designation_id" class="form-control submitable"
                                                id="designation_id">
                                                <option value="">All</option>
                                                @foreach ($designations as $key => $designation)
                                                    <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>Grade </strong></label>
                                            <select name="grade_id" class="form-control submitable form-select"
                                                id="grade_id">
                                                <option value selected>All</option>
                                                @foreach ($grades as $key => $grade)
                                                    <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>Type Status </strong></label>
                                            <select name="employment_status" class="form-control submitable"
                                                id="employment_status">
                                                <option value="">All</option>
                                                @foreach (Modules\HRM\Enums\EmploymentStatus::cases() as $key => $employeestatus)
                                                    <option value="{{ $employeestatus->value }}">
                                                        {{ $employeestatus->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>Joining Date </strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i
                                                            class="fas fa-calendar-week input_i"></i></span>
                                                </div>
                                                <input type="search" name="date_range" id="date_range"
                                                    class="form-control reportrange submitable_input date_range"
                                                    autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>Employee </strong></label>
                                            <select name="employee_id" class="form-control submitable form-select"
                                                id="employee_id">
                                                <option value selected>All</option>
                                                @foreach ($employees as $key => $employee)
                                                    <option value="{{ $employee->id }}">{{ $employee->employee_id }} -
                                                        {{ $employee->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form id="bulk_action_form" action="{{ route('hrm.print.employee.card') }}" method="GET">
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
                                                    <th class="text-start">
                                                        <div>
                                                            <input type="checkbox" id="is_check_all">
                                                        </div>
                                                    </th>
                                                    <th>{{ __('SL .') }}</th>
                                                    <th>{{ __('Employee ID') }}</th>
                                                    <th>{{ __('Photo') }}</th>
                                                    <th>{{ __('Employee Name') }}</th>
                                                    <th>{{ __('Department') }}</th>
                                                    <th>{{ __('Section') }}</th>
                                                    <th>{{ __('Designation') }}</th>
                                                    <th>{{ __('Phone') }}</th>
                                                    <th>{{ __('Present Address') }}</th>
                                                    <th>{{ __('Joining') }}</th>
                                                    <th>{{ __('Print Count') }}</th>
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

    <!-- Leave Modal -->
    <div class="modal fade" id="leftModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">
                        {{ __('Left Employee') }}
                    </h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="left_modal_body"></div>
            </div>
        </div>
    </div>

    <!-- Resign Modal -->
    <div class="modal fade" id="resignModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">
                        {{ __('Resign Employee') }}
                    </h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="resign_modal_body"></div>
            </div>
        </div>
    </div>
    <!-- View Modal -->
    <div id="viewModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog four-col-modal ui-draggable ui-draggable-handle">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title"><i class="icon-plus-circle2 mr-2"></i> &nbsp;Employee Details</h5>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="view_modal_body">

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
                    "url": "{{ route('hrm.employee.card') }}",
                    "data": function(data) {
                        //filter options
                        data.hrm_department_id = $('#hrm_department_id').val();
                        data.section_id = $('#section_id').val();
                        data.grade_id = $('#grade_id').val();
                        data.designation_id = $('#designation_id').val();
                        data.date_range = $('.submitable_input').val();
                        data.employment_status = $('#employment_status').val();
                        data.employee_id = $('#employee_id').val();
                    }
                },

                initComplete: function() {

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
                        data: 'DT_RowIndex',
                        searchable: false,
                        orderable: false
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
                        name: 'print_count',
                        data: 'print_count'
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




            $(document).on('change', '#employment_status', function(e) {
                e.preventDefault();
                var selected = $(this).val();
                $.ajax({
                    url: "{{ route('hrm.employees.index') }}",
                    data: selected,
                    contentType: false,
                    processData: false,
                    success: function(data) {

                    }
                });
            });


            // $.ajax({
            //     url: "{{ route('hrm.v1.departments.index') }}"
            //     , type: 'get'
            //     , dataType: 'json'
            //     , success: function(data) {
            //         $.each(data.data, function(key, val) {
            //             $('#hrm_department_id').append('<option value="' + val.id + '">' + val.name +
            //                 '</option>');
            //         });
            //     }
            // });

            // $.ajax({
            //     url: "{{ route('hrm.v1.designations.index') }}"
            //     , type: 'get'
            //     , dataType: 'json'
            //     , success: function(data) {
            //         $.each(data.data, function(key, val) {
            //             $('#designation_id').append('<option value="' + val.id + '">' + val.name +
            //                 '</option>');
            //         });
            //     }
            // });

            // $.ajax({
            //     url: "{{ route('hrm.v1.sections.index') }}"
            //     , type: 'get'
            //     , dataType: 'json'
            //     , success: function(data) {
            //         $.each(data.data, function(key, val) {
            //             $('#section_id').append('<option value="' + val.id + '">' + val.name +
            //                 '</option>');
            //         });
            //     }
            // });

            // $.ajax({
            //     url: "{{ route('hrm.v1.grades.index') }}"
            //     , type: 'get'
            //     , dataType: 'json'
            //     , success: function(data) {
            //         $.each(data.data, function(key, val) {
            //             $('#grade_id').append('<option value="' + val.id + '">' + val.name +
            //                 '</option>');
            //         });
            //     }
            // });

            // $.ajax({
            //     url: "{{ route('hrm.arrivals.index') }}"
            //     , type: 'get'
            //     , dataType: 'json'
            //     , success: function(data) {
            //         $.each(data.data, function(key, val) {
            //             $('#employee_id').append('<option value="' + val.id + '">' + val.name +
            //                 '</option>');
            //         });
            //     }
            // });


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
            })
        })


        $(document).ready(function() {
            $(document).on('click', '#idcard', function(e) {
                e.preventDefault();
                // $('#bulk_action_form').submit();

                $('#bulk_action_form').submit(function(event) {
                    event.preventDefault(); // Prevent the form from submitting normally

                    // Perform any additional validations or manipulations here if needed

                    // Submit the form using AJAX
                    $.ajax({
                        url: $(this).attr('action'), // Target URL for form submission
                        method: $(this).attr('method'), // HTTP method (e.g., "post", "get")
                        data: $(this).serialize(), // Serialize form data
                        success: function(response) {
                            // Handle the successful submission response here

                        },
                        error: function(xhr, status, error) {
                            // Handle the error case here

                        }
                    });
                });

                $('.loading_button').show();
                var departmentID = $('#hrm_department_id').val();
                var sectionID = $('#section_id').val();
                var designationID = $('#designation_id').val();
                var gradeID = $('#grade_id').val();
                var employeeStatus = $('#employment_status').val();
                var joiningDate = $('#date_range').val();
                var employeeID = $('#employee_id').val();
                var employeeIds = $('#bulk_action').serializeArray();
                $.ajax({
                    url: "{{ route('hrm.print.employee.card') }}",
                    type: "get",
                    data: {
                        departmentID: departmentID,
                        sectionID: sectionID,
                        designationID: designationID,
                        employeeStatus: employeeStatus,
                        joiningDate: joiningDate,
                        gradeID: gradeID,
                        employeeID: employeeID
                    },
                    success: function(data) {
                        $('.loading_button').hide();
                        $(data).printThis({
                            debug: false,
                            importCSS: true,
                            importStyle: true,
                            removeInline: false,
                            printDelay: 7000,
                            header: null,
                            footer: null,
                        });
                    }

                })
            });
        });
    </script>
@endpush
