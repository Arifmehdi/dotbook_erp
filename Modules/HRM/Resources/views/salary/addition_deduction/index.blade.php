@extends('layout.master')
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
                <h6>{{ __('Salary Adjustment - Addition / Deduction') }}</h6>
            </div>
            <div class="d-flex">
                <div id="exportButtonsContainer">
                    @if (auth()->user()->can('hrm_employees_create'))
                        <a href="{{ route('hrm.salaryAdjustments.create') }}" class="btn text-white btn-sm add">
                            <span>
                                <i class="fa-thin fa-circle-plus fa-2x"></i>
                                <br> {{ __('Add New') }}
                            </span>
                        </a>
                    @endif
                </div>
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
                                <form id="filter_form">
                                    <div class="form-group row">
                                        <div class="col-xl-3 col-md-4">
                                            <label><strong>{{ __('Employee') }} </strong></label>
                                            <select name="employee_id" class="form-control submitable form-select"
                                                id="employee_id">
                                                <option value="">@lang('menu.all')</option>
                                            </select>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>{{ __('Month') }}</strong></label>
                                            <select name="month" class="form-control submitable form-select"
                                                id="month">
                                                <option value="">All</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>{{ __('Year') }}</strong></label>
                                            <select name="year" class="form-control submitable form-select"
                                                id="year">
                                                <option value="">@lang('menu.all')</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>{{ __('Adjustment Type') }}</strong></label>
                                            <select name="type" class="form-control submitable form-select"
                                                id="type">
                                                <option value="">@lang('menu.all')</option>
                                                <option value="1">{{ __('Addition') }}</option>
                                                <option value="2">{{ __('Deduction') }}</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>{{ __('Created Date') }}</strong></label>
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

        <form id="bulk_action_form" action="{{ route('hrm.salaryAdjustments.bulk-action') }}" method="POST">
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
                                                    <th>{{ __('Employee ID') }}</th>
                                                    <th>{{ __('Photo') }}</th>
                                                    <th>{{ __('Name') }}</th>
                                                    <th>{{ __('Amount') }}</th>
                                                    <th>{{ __('Month') }}</th>
                                                    <th>{{ __('Year') }}</th>
                                                    <th>{{ __('Adjustment Type') }}</th>
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
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">
                        {{ __('Create Salary Adjustment') }}
                    </h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="add_modal_body">
                    <!--begin::Form-->

                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">
                        {{ __('Edit Salary Adjustment') }}
                    </h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="edit_modal_body"></div>
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
                    "url": "{{ route('hrm.salaryAdjustments.index') }}",
                    "data": function(data) {
                        //filter options
                        data.employee_id = $('#employee_id').val();
                        data.year = $('#year').val();
                        data.month = $('#month').val();
                        data.designation_id = $('#designation_id').val();
                        data.date_range = $('.submitable_input').val();
                        data.employment_status = $('#employment_status').val();
                        data.type = $('#type').val();
                        data.showTrashed = $('#trashed_item').attr('showtrash');
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
                    <div class="form-group row align-items-end g-2">
                        <div class="col-8" >
                            <select name="action_type" id="bulk_action_field" class="form-control submit_able form-select" required>
                                <option value="" selected>Bulk Actions</option>
                                <option value="restore_from_trash" id="restore_option">Restore From Trash</option>
                                <option value="move_to_trash" id="move_to_trash">Move To Trash</option>
                                <option value="delete_permanently" id="delete_option">Delete Permanently</option>
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
                        name: 'check',
                        data: 'check',
                        sWidth: '3%',
                        orderable: false,
                        targets: 0
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
                        name: 'amount',
                        data: 'amount'
                    },
                    {
                        name: 'month',
                        data: 'month'
                    },
                    {
                        name: 'year',
                        data: 'year'
                    },
                    {
                        name: 'type',
                        data: 'type'
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

            $(document).on('submit', '#left_form', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                $('.error').html('');
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: request,
                    success: function(data) {
                        $('.loading_button').hide();
                        $('#leftModal').modal('hide');
                        toastr.success(data);
                        table.ajax.reload();
                    },
                    error: function(error) {
                        $('.loading_button').hide();
                        toastr.error(error.responseJSON.message);
                    }
                });
            });
            //Bulk Action
            $('#bulk_action_form').on('submit', function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: request,
                    success: function(data) {
                        toastr.success(data);
                        table.ajax.reload();
                    },
                    error: function(error) {
                        toastr.error(error.responseJSON.message);
                    }
                });
            });
            // Add Modal
            $(document).on('click', '.add', function(e) {
                e.preventDefault();

                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('#add_modal_body').html(data);
                        $('#addModal').modal('show');

                    },
                    error: function(err) {
                        if (err.status == 0) {
                            toastr.error('Net Connetion Error. Reload This Page.');
                        } else {
                            toastr.error('Server Error. Please contact to the support team.');
                        }
                    }
                });
            });

            // Edit Modal
            $(document).on('click', '.edit', function(e) {
                e.preventDefault();
                $('.data_preloader').show();
                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('#edit_modal_body').html(data);
                        $('#editModal').modal('show');
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

            $.ajax({
                url: "{{ route('hrm.arrivals.index') }}",
                type: 'get',
                dataType: 'json',
                success: function(data) {
                    $.each(data.data, function(key, val) {
                        $('#employee_id').append('<option value="' + val.id + '">' + val.name +
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
                    }
                });
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

        //trashed item
        $(document).on('click', '#trashed_item', function(e) {
            e.preventDefault();
            $(this).attr("showtrash", true);
            $('.check1').prop('checked', false)
            $(this).addClass("font-weight-bold");
            $('.employee-table').DataTable().draw(false);
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
            $('.employee-table').DataTable().draw(false);
            $('#trashed_item').removeClass("font-weight-bold")
            $("#delete_option").css('display', 'none');
            $("#restore_option").css('display', 'none');
            $("#move_to_trash").css('display', 'block');
        })


        // Edit Modal
        $(document).on('click', '.restore', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');

            $.confirm({
                'title': 'Restore Confirmation',
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
                                    $('.employee-table').DataTable().draw(false);
                                    $('.data_preloader').hide();
                                    toastr.success(data);
                                },
                                error: function(err) {
                                    $('.data_preloader').hide();
                                    if (err.status == 0) {
                                        toastr.error(
                                            'Net Connetion Error. Reload This Page.');
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
                        'action': function() {}
                    }
                }
            });
        });

        $(document).on('click', '.delete', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#delete_form').attr('action', url);

            $.confirm({
                'title': 'Delete Confirmation',
                'message': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-danger',
                        'action': function() {
                            // $('#delete_form').submit();
                            $.ajax({
                                url: url,
                                type: 'DELETE',
                                success: function(data) {
                                    toastr.success(data);
                                    $('.loading_button').hide();
                                    $('.employee-table').DataTable().draw(false);
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
