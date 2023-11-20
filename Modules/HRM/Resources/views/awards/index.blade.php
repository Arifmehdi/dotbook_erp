@extends('layout.master')
@section('title', 'Award/Prize - ')
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
                <h6>{{ __('Award') }}</h6>
            </div>
            <x-all-buttons>
                <x-slot name="before">
                    <x-add-button :can="'hrm_awards_create'" />
                </x-slot>
            </x-all-buttons>
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
                                            <div class="col-xl-2 col-md-4">
                                                <label><strong>{{ __('Employee') }} </strong></label>
                                                <select name="employee_id" class="form-control submitable form-select"
                                                    id="employee_id">
                                                    <option value="">@lang('menu.all')</option>
                                                </select>
                                            </div>
                                            <div class="col-xl-2 col-md-4">
                                                <label><strong>@lang('menu.department') </strong></label>
                                                <select name="department_id" class="form-control submitable form-select"
                                                    id="department_id">
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
                                                <label><strong>{{ __('Awarded Date') }}</strong></label>
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
            <form id="bulk_action_form" action="{{ route('hrm.awards.bulk-action') }}" method="POST">
                <div class="row mt-1">
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
                                                    <th class="text-start">
                                                        <div>
                                                            <input type="checkbox" id="is_check_all">
                                                        </div>
                                                    </th>
                                                    <th class="text-start">{{ __('Sl') }}</th>
                                                    <th class="text-start">{{ __('Employee ID') }}</th>
                                                    <th class="text-start">{{ __('Employee') }}</th>
                                                    <th class="text-start">{{ __('Award Name') }}</th>
                                                    <th class="text-start">{{ __('Gift Item') }}</th>
                                                    <th class="text-start">{{ __('Award By') }}</th>
                                                    <th class="text-start">{{ __('Date') }}</th>
                                                    <th class="text-start">{{ __('Month') }}</th>
                                                    <th class="text-start">{{ __('Year') }}</th>
                                                    <th class="text-start">{{ __('Action') }}</th>
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
            </form>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">
                        {{ __('Add Award') }}
                    </h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_form" action="{{ route('hrm.awards.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-xl-12 col-md-12">
                                <label><b> {{ __('Employee') }}</b> <span class="text-danger">*</span></label><br>
                                <select name="employee_id" id="employee_id" class="form-control select2 form-select">
                                    <option value="" selected disabled>--{{ __('Choose Employee') }}--</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }} -
                                            {{ $employee->employee_id }}</option>
                                    @endforeach
                                </select>
                                <span class="error error_employee_id"></span>
                            </div>
                            <div class="form-group col-xl-6 col-md-6">
                                <label><b> {{ __('Award Name') }}</b> <span class="text-danger">*</span></label>
                                <input type="text" name="award_name" class="form-control form-control-sm add_input"
                                    data-name="{{ __('Award Name') }}" id="award_name"
                                    placeholder="{{ __('Award Name') }}" />
                                <span class="error error_award_name"></span>
                            </div>
                            <div class="form-group col-xl-6 col-md-6">
                                <label><b> {{ __('Award By') }}</b> <span class="text-danger">*</span></label>
                                <input type="text" name="award_by"
                                    class="form-control form-control-sm add_input startdate"
                                    data-name="{{ __('Award By') }}" id="award_by"
                                    placeholder="{{ __('Who given Award') }}" />
                                <span class="error error_award_by"></span>
                            </div>
                        </div>
                        <div class="row">

                            <div class="form-group col-xl-6 col-md-6">
                                <label><b> {{ __('Gift Name') }}</b> <span class="text-danger">*</span></label>
                                <input type="text" name="gift_item"
                                    class="form-control form-control-sm add_input enddate"
                                    data-name="{{ __('Gift Name') }}" id="gift_item"
                                    placeholder="{{ __('Gift Item Name') }}" />
                                <span class="error error_gift_item"></span>
                            </div>
                            <div class="form-group col-xl-6 col-md-6">
                                <label><b> {{ __('Award Date') }}</b> <span class="text-danger">*</span></label>
                                <input type="date" name="date" class="form-control form-control-sm add_input"
                                    data-name="{{ __('Num of Days') }}" id="date"
                                    placeholder="{{ __('Award Date') }}" required value="<?php echo date('Y-m-d'); ?>" />
                                <span class="error error_date"></span>
                            </div>
                        </div>

                        <div class="col-xl-12 col-md-12">
                            <label> {{ __('Award Details') }} <span class="text-danger"></span></label>
                            <textarea name="award_description" id="award_description" cols="30" rows="3"
                                class="form-control form-control-sm add_input" placeholder="Award Description"></textarea>
                            <span class="error error_award_description"></span>
                        </div>
                        <div class="form-group row mt-3">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="loading-btn-box">
                                    <button type="button" class="btn btn-sm loading_button display-none"><i
                                            class="fas fa-spinner"></i></button>
                                    <button type="submit"
                                        class="btn btn-sm btn-success float-end submit_button">@lang('menu.save')</button>
                                    <button type="reset" data-bs-dismiss="modal"
                                        class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                                </div>
                            </div>
                        </div>
                    </form>
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
                        {{ __('Edit Award') }}
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
        $('#hrm_department_id').select2();
        $('#designation_id').select2();
        $('#shift_id').select2();
        $('#grade_id').select2();
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
                    text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('
                                                            menu.pdf ')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }
                }, {
                    extend: 'excel',
                    text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('
                                                            menu.excel ')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }
                }, {
                    extend: 'print',
                    text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('
                                                            menu.print ')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
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
                    "url": "{{ route('hrm.awards.index') }}",
                    "data": function(data) {

                        //send types of request for colums
                        data.showTrashed = $('#trashed_item').attr('showtrash');
                        //filter options
                        data.hrm_department_id = $('#hrm_department_id').val();
                        data.shift_id = $('#shift_id').val();
                        data.grade_id = $('#grade_id').val();
                        data.designation_id = $('#designation_id').val();
                        data.date_range = $('.submitable_input').val();
                        data.employee_id = $('#employee_id').val();

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
                }, {
                    name: 'DT_RowIndex',
                    data: 'DT_RowIndex',
                    sWidth: '3%'
                }, {
                    name: 'employee_id',
                    data: 'employee_id'
                }, {
                    name: 'employee',
                    data: 'employee'
                }, {
                    name: 'award_name',
                    data: 'award_name'
                }, {
                    name: 'gift_item',
                    data: 'gift_item'
                }, {
                    name: 'award_by',
                    data: 'award_by'
                }, {
                    name: 'date',
                    data: 'date'
                }, {
                    name: 'month',
                    data: 'month'
                }, {
                    name: 'year',
                    data: 'year'
                }, {
                    name: 'action',
                    data: 'action'
                }, ],
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
            $('.award_table').DataTable().draw(false);
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
            $('.award_table').DataTable().draw(false);
            $('#trashed_item').removeClass("font-weight-bold")
            $("#delete_option").css('display', 'none');
            $("#restore_option").css('display', 'none');
            $("#move_to_trash").css('display', 'block');
        })

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
                                    $('.award_table').DataTable().draw(false);
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
                'title': '{{ __('Delete Confirmation') }}',
                'message': '{{ __('Are you sure?') }}',
                'buttons': {
                    '{{ __('Yes') }}': {
                        'class': 'yes btn-danger',
                        'action': function() {
                            // $('#delete_form').submit();
                            $.ajax({
                                url: url,
                                type: 'DELETE',
                                success: function(data) {
                                    if ($.isEmptyObject(data.errorMsg)) {
                                        toastr.error(data);
                                        $('.award_table').DataTable().draw(false);
                                    } else {
                                        toastr.error(data.errorMsg);
                                    }
                                },
                                error: function(error) {
                                    $('.loading_button').hide();
                                    toastr.error(error.responseJSON.message);
                                }
                            });
                        }
                    },
                    '{{ __('No') }}': {
                        'class': 'no btn-primary',
                        'action': function() {}
                    }
                }
            });
        });

        $.ajax({
            url: "{{ route('hrm.employees.index') }}",
            type: 'get',
            dataType: 'json',
            success: function(data) {
                $.each(data.data, function(key, val) {
                    $('#employee_id').append('<option value="' + val.id + '">' + val.name +
                        '</option>');
                })
            }

        });

        $.ajax({
            url: "{{ route('hrm.v1.departments.index') }}",
            type: 'get',
            dataType: 'json',
            success: function(data) {
                $.each(data.data, function(key, val) {
                    $('#hrm_department_id').append('<option value="' + val.id + '">' + val.name +
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
                    $('#designation_id').append('<option value="' + val.id + '">' + val.name +
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
    </script>
@endpush
