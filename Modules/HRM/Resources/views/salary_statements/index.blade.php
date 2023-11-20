@extends('layout.master')
@push('css')

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .sorting_disabled {
            background: none;
        }



        .increment_table img {
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
                <h6>{{ __('Salary Settlement') }}</h6>
            </div>
            <x-all-buttons />
        </div>

        <div class="p-15">
            <div class="row g-1">
                <div class="col-md-12">
                    <div class="form_element m-0 rounded">
                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form id="filter_form">
                                        <div class="form-group row">
                                            <div class="col-xl-2 col-md-4">
                                                <label><strong>@lang('menu.department') </strong></label>
                                                <select name="hrm_department_id" class="form-control submitable form-select"
                                                    id="hrm_department_id">
                                                    <option value="">@lang('menu.all')</option>
                                                </select>
                                            </div>
                                            <div class="col-xl-2 col-md-4">
                                                <label><strong>{{ __('Designation') }}</strong></label>
                                                <select name="designation_id" class="form-control submitable form-select"
                                                    id="designation_id">
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
                                            <div class="col-xl-2 col-md-4">
                                                <label><strong>{{ __('Grade') }}</strong></label>
                                                <select name="grade_id" class="form-control submitable form-select"
                                                    id="grade_id">
                                                    <option value selected>@lang('menu.all')</option>
                                                </select>
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
                <div class="col-12">
                    <form id="bulk_action_form" action="{{ route('hrm.settlements.bulk-action') }}" method="POST">
                        <div class="card">
                            <div class="card-body">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                                </div>
                                <div class="table-responsive h-350" id="data-list">
                                    <table class="display data_tbl data__table increment_table">
                                        <thead>
                                            <tr class="text-center">
                                                <th class="text-center">
                                                    <div>
                                                        <input type="checkbox" id="is_check_all">
                                                    </div>
                                                </th>
                                                <th>{{ __('Action') }}</th>
                                                <th>{{ __('Employee ID') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Section') }}</th>
                                                <th>{{ __('Designation') }}</th>
                                                <th>{{ __('Current Salary') }}</th>
                                                <th>{{ __('Phone') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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

    <!-- Inrement Modal -->
    <div class="modal fade" id="incremetnModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">
                        {{ __('Salary Inrement') }}
                    </h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="increment_modal_body"></div>
            </div>
        </div>
    </div>
    <!-- department Modal -->
    <div class="modal fade" id="departmentModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">
                        {{ __('Department Wise Salary Settlement') }}
                    </h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="department_modal_body"></div>
            </div>
        </div>
    </div>
    <!-- View Modal -->
    <div id="viewModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog four-col-modal ui-draggable ui-draggable-handle">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="icon-plus-circle2 mr-2"></i>
                        &nbsp;{{ __('Salary Settlement Details') }}</h5>
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
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            var allRow = '';
            var table = $('.increment_table').DataTable({
                dom: "lBfrtip",
                buttons: [{
                    extend: 'pdf',
                    text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [2, 4, 5, 6, 7]
                    }
                }, {
                    extend: 'excel',
                    text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [2, 4, 5, 6, 7]
                    }
                }, {
                    extend: 'print',
                    text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [2, 4, 5, 6, 7]
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
                    "url": "{{ route('hrm.salary-settlements.index') }}",
                    "data": function(data) {
                        //filter options
                        data.hrm_department_id = $('#hrm_department_id').val();
                        data.shift_id = $('#shift_id').val();
                        data.grade_id = $('#grade_id').val();
                        data.designation_id = $('#designation_id').val();
                        data.date_range = $('.submitable_input').val();
                        data.employment_status = $('#employment_status').val();
                    }
                },
                "drawCallback": function(data) {
                    allRow = data.json.allRow;
                    $('#all_item').text('All (' + allRow + ')');
                    $('#is_check_all').prop('checked', false);
                    $('#trashed_item').text('');
                    $('#trash_separator').text('');
                    $("#bulk_action_field option:selected").prop("selected", false);

                },
                initComplete: function() {

                    var toolbar =
                        `<div class="d-flex">
                            <div style="width: 100px;">
                                    <a href="#" style="color:#2688cd" class="font-weight-bold" id="all_item">All</a>
                                    <span style="color:#2688cd; margin-right:3px;" id="trash_separator"></span><a style="color:#2688cd" href="#" id="trashed_item"></a>
                            </div>
                            <div class="form-group row align-items-end g-2">
                                <div class="col-8" >
                                    <select name="action_type" id="bulk_action_field" class="form-control submit_able form-select" required>
                                        <option value="" selected disabled>Bulk Actions</option>
                                        <option value="increment_or_decrement" class="department_wise" id="increment_or_decrement">Settlement</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <button type="submit" id="filter_button" class="btn btn-sm btn-info">Apply</button>
                                </div>
                            </div>
                        </div>`;

                    $("div.dataTables_filter").prepend(toolbar);
                    $("div.dataTables_filter").addClass('d-flex');
                    $("#restore_option").css('display', 'none');
                    $("#delete_option").css('display', 'none');
                    $("#increment_or_decrement").css('display', 'block');
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
                        name: 'action',
                        data: 'action'
                    },
                    {
                        name: 'employee_id',
                        data: 'employee_id'
                    },
                    {
                        name: 'name',
                        data: 'name'
                    },
                    {
                        name: 'section',
                        data: 'section'
                    },
                    {
                        name: 'designation_id',
                        data: 'designation_id'
                    },
                    {
                        name: 'salary',
                        data: 'salary'
                    },
                    {
                        name: 'phone',
                        data: 'phone'
                    },

                ],
                "lengthMenu": [
                    [10, 25, 50, 100, 500, 1000, -1],
                    [10, 25, 50, 100, 500, 1000, "All"]
                ],
            });

            table.buttons().container().appendTo('#exportButtonsContainer');

            //Bulk Action
            $('#bulk_action_form').on('submit', function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                // $('.data_preloader').show();
                var request = $(this).serialize();
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: request,
                    success: function(data) {
                        $('#departmentModal').modal('show');
                        $('#department_modal_body').html(data);
                        $('.data_preloader').hide();
                        // toastr.success(data);
                        // table.ajax.reload();
                    },
                    error: function(error) {
                        toastr.error(error.responseJSON.message);
                        $('.data_preloader').hide();
                    }
                });
            });
            // Add Award by ajax
            $(document).on('submit', '#add_form', function(e) {
                e.preventDefault();
                $('.loader').removeClass("d-none");
                var url = $(this).attr('action');
                var request = $(this).serialize();
                var employeeIds = [];
                $('input[name="employee_id[]"]:checked').each((d, v) => {
                    employeeIds.push(v.value)
                });
                var addFormData = new FormData(this);
                addFormData.append('employee_ids', JSON.stringify(employeeIds));
                $.ajax({
                    url: url,
                    type: 'post',
                    data: addFormData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        $('#departmentModal').modal('hide');
                        $('.loader').addClass("d-none");
                        table.ajax.reload();
                        // $('.datatable').DataTable().ajax.reload();
                        toastr.success(data);
                        $('#add_form')[0].reset();
                    }
                });
            });
            // Add Award by ajax
            $(document).on('submit', '#singleAddForm', function(e) {
                e.preventDefault();
                $('.loader').removeClass("d-none");
                var url = $(this).attr('action');
                var request = $(this).serialize();
                var addFormData = new FormData(this);
                $.ajax({
                    url: url,
                    type: 'post',
                    data: addFormData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        $('#incremetnModal').modal('hide');
                        $('.loader').addClass("d-none");
                        table.ajax.reload();
                        toastr.success(data);
                        $('#singleAddForm')[0].reset();
                    }
                });
            });

            // Add Modal
            $(document).on('click', '.increment', function(e) {
                e.preventDefault();
                $('.data_preloader').show();
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('#increment_modal_body').html(data);
                        $('#incremetnModal').modal('show');
                        $('.data_preloader').hide();
                    },
                    error: function(err) {
                        $('.data_preloader').hide();
                        if (err.status == 0) {
                            toastr.error('Net Connetion Error. Reload This Page.');
                        } else {
                            toastr.error('Server Error. Please contact to the support team.');
                        }
                    }
                });
            });
            // delete settlement
            $(document).on('click', '.delete', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $.confirm({
                    'title': 'Delete Confirmation',
                    'message': 'Are you sure?',
                    'buttons': {
                        'Yes': {
                            'class': 'yes btn-danger',
                            'action': function() {
                                $.ajax({
                                    url: url,
                                    type: 'get',
                                    success: function(data) {
                                        toastr.error(data);
                                        $('.loading_button').hide();
                                        $('#viewModal').modal('hide');
                                        table.ajax.reload();
                                        $('.increment_table').DataTable().draw(
                                            false);
                                    },
                                    error: function(error) {
                                        $('.loading_button').hide();
                                        toastr.error(error.responseJSON.message);
                                    }
                                });
                            }
                        },
                        'No': {
                            'class': 'no btn-primary',
                            'action': function() {}
                        }
                    }
                });
            });

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

            $.ajax({
                url: "{{ route('hrm.v1.grades.index') }}",
                type: 'get',
                dataType: 'json',
                success: function(data) {
                    $.each(data.data, function(key, val) {
                        $('#grade_id').append('<option value="' + val.id + '">' + val.name +
                            '</option>');
                    });
                }
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
            })
        });
        //all item
        $(document).on('click', '#all_item', function(e) {
            e.preventDefault();
            trashed_item = $('#trashed_item');
            $('#is_check_all').prop('checked', false);
            $('.check1').prop('checked', false);
            trashed_item.attr("showtrash", false);
            $(this).addClass("font-weight-bold");
            $('.promotion_table').DataTable().draw(false);
            $('#trashed_item').removeClass("font-weight-bold")
            $("#delete_option").css('display', 'none');
            $("#restore_option").css('display', 'none');
            $("#increment_or_decrement").css('display', 'block');
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
        $(document).on('click', '#view', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    $('#view_modal_body').html(data);
                    $('#viewModal').modal('show');
                },
                error: function(error) {
                    $('.loading_button').hide();
                    toastr.error(error.responseJSON.message);
                }
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
    </script>
@endpush
