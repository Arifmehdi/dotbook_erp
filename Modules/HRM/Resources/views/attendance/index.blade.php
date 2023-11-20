@extends('layout.master')
@section('title', 'Attendance List- ')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/select2/select2.min.js') }}">
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
                <h6>{{ __('Attendance List') }}</h6>
            </div>
            <x-all-buttons />
        </div>
        <form id="bulk_action_form" action="{{ route('hrm.person.bulk-action') }}" method="POST">
            @csrf
            <div class="p-15">
                <div class="row g-0 mb-1">
                    <div class="col-md-12">
                        <div class="form_element m-0 rounded">
                            <div class="element-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form id="filter_form">
                                            <div class="form-group row">
                                                <div class="col-xl-2 col-md-4">
                                                    <label><strong>{{ __('Employee') }} </strong></label>
                                                    <select name="employee_id" class="form-control submitable form-select"
                                                        id="employee_id">
                                                        {{-- <option value="">{{ __('Select Employee') }}</option> --}}
                                                        <option value="">@lang('menu.all')</option>
                                                        @foreach ($employees as $employee)
                                                            <option value="{{ $employee->id }}">{{ $employee->employee_id }}
                                                                -{{ $employee->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-xl-2 col-md-4">
                                                    <label><strong>{{ __('Department') }} </strong></label>
                                                    <select name="hrm_department_id" class="form-control submitable form-select"
                                                        id="hrm_department_id">
                                                        <option value="">@lang('menu.all')</option>
                                                    </select>
                                                </div>
                                                <div class="col-xl-2 col-md-4">
                                                    <label><strong>{{ __('Section') }} </strong></label>
                                                    <select name="section_id" class="form-control submitable form-select"
                                                        id="section_id">
                                                        <option value="">@lang('menu.all')</option>
                                                    </select>
                                                </div>
                                                <div class="col-xl-2 col-md-4">
                                                    <label><strong>{{ __('Sub Section') }}</strong></label>
                                                    <select name="sub_section_id" class="form-control submitable form-select"
                                                        id="sub_section_id">
                                                        <option value="">@lang('menu.all')</option>
                                                    </select>
                                                </div>
                                                <div class="col-xl-2 col-md-4">
                                                    <label><strong>{{ __('Designation') }}</strong></label>
                                                    <select name="designation_id"
                                                        class="form-control submitable form-select" id="designation_id">
                                                        <option value="">@lang('menu.all')</option>
                                                    </select>
                                                </div>
                                                <div class="col-xl-2 col-md-4">
                                                    <label><strong>@lang('menu.shift') </strong></label>
                                                    <select name="shift_id" class="form-control submitable form-select"
                                                        id="shift_id">
                                                        <option value="" selected>@lang('menu.all')</option>
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
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                                </div>
                                <form id="bulk_action">
                                    <div class="table-responsive create_attendance_table" id="data-list">
                                        <table class="display data_tbl data__table attendance_table">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>{{ __('S/L') }}</th>
                                                    <th>{{ __('Employee ID') }}</th>
                                                    <th>{{ __('Employee Name') }}</th>
                                                    <th>{{ __('Department') }}</th>
                                                    <th>{{ __('Section') }}</th>
                                                    <th>{{ __('Clock In') }}</th>
                                                    <th>{{ __('Clock Out') }}</th>
                                                    <th>{{ __('Date') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                    <th>{{ __('Action') }}</th>
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
        <!-- edit Modal -->
        <div id="editModal" class="modal fade" tabindex="-1">
            <div class="modal-dialog four-col-modal ui-draggable ui-draggable-handle">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="icon-plus-circle2 mr-2"></i> &nbsp;{{ __('Edit Attendance') }}
                        </h5>
                        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                                class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body" id="edit_modal_body">

                    </div>
                </div>
            </div>
        </div>
        <!-- Show Modal -->
        <div id="showModal" class="modal fade" tabindex="-1">
            <div class="modal-dialog four-col-modal ui-draggable ui-draggable-handle">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="icon-plus-circle2 mr-2"></i> &nbsp;{{ __('Show Attendance') }}
                        </h5>
                        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                                class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body" id="show_modal_body">

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
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

        <script>
            $('#employee_id').select2();
            $('#hrm_department_id').select2();
            $('#sub_section_id').select2();
            $('#designation_id').select2();
            $('#shift_id').select2();

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
                    }, {
                        extend: 'excel',
                        text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                        className: 'pdf btn text-white btn-sm px-1',
                        exportOptions: {
                            columns: 'th:not(:last-child)'
                        }
                    }, {
                        extend: 'print',
                        text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                        className: 'pdf btn text-white btn-sm px-1',
                        exportOptions: {
                            columns: 'th:not(:last-child)'
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
                    searchable: false,
                    "ajax": {
                        "url": "{{ route('hrm.persons.index') }}",
                        "data": function(data) {
                            //filter options
                            data.employee_id = $('#employee_id').val();
                            data.section_id = $('#section_id').val();
                            data.hrm_department_id = $('#hrm_department_id').val();
                            data.sub_section_id = $('#sub_section_id').val();
                            data.designation_id = $('#designation_id').val();
                            data.shift_id = $('#shift_id').val();
                            // data.date_range = $('.submitable_input').val();
                            data.employment_status = $('#employment_status').val();
                            data.showTrashed = $('#trashed_item').attr('showtrash');
                            data.date_range = $('#date_range').val();
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
                    }, {
                        name: 'employee_id',
                        data: 'employeeId'
                    }, {
                        name: 'employees.name',
                        data: 'employee_name'
                    }, {
                        name: 'departments.name',
                        data: 'department_name'
                    }, {
                        name: 'sections.name',
                        data: 'section_name'
                    }, {
                        name: 'clock_in',
                        data: 'clock_in'
                    }, {
                        name: 'clock_out',
                        data: 'clock_out'
                    }, {
                        name: 'at_date_format',
                        data: 'at_date_format'
                    }, {
                        name: 'status',
                        data: 'status'
                    }, {
                        name: 'action',
                        data: 'action'
                    }],
                    "lengthMenu": [
                        [10, 25, 50, 100, 500, 1000, -1],
                        [10, 25, 50, 100, 500, 1000, "All"]
                    ]
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
                                alert(data);
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
        </script>
    @endpush
