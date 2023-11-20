@extends('layout.master')
@section('title', 'Promotion - ')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/select2/select2.min.js') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .sorting_disabled {
            background: none;
        }



        #overtime-checkbox::after {
            display: none;
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
                <h6>{{ __('Promotion') }}</h6>
            </div>
            <x-all-buttons>
                <x-slot name="before">
                    <x-add-button :href="route('hrm.promotions.create')" id="create-modal" :can="'hrm_promotion_create'" :is_modal="false" />
                </x-slot>
            </x-all-buttons>
        </div>

        <form id="bulk_action_form" action="{{ route('hrm.promotion.bulk-action') }}" method="POST">
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
                                                    <select name="employee_id" class="form-control submitable"
                                                        id="employee_id">
                                                        <option value="">{{ __('All') }}</option>
                                                        @foreach ($employees as $key => $employee)
                                                            <option value="{{ $employee->id }}">
                                                                {{ $employee->employee_id }}-{{ $employee->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-xl-2 col-md-4">
                                                    <label><strong>{{ __('Department') }} </strong></label>
                                                    <select name="hrm_department_id" class="form-control submitable"
                                                        id="hrm_department_id">
                                                        <option value="">{{ __('All') }}</option>
                                                        @foreach ($departments as $key => $department)
                                                            <option value="{{ $department->id }}">
                                                                {{ $department->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-xl-2 col-md-4">
                                                    <label><strong>{{ __('Department') }} </strong></label>
                                                    <select name="hrm_department_id" class="form-control submitable"
                                                        id="hrm_department_id">
                                                        <option value="">{{ __('All') }}</option>
                                                        @foreach ($departments as $key => $department)
                                                            <option value="{{ $department->id }}">
                                                                {{ $department->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-xl-2 col-md-4">
                                                    <label><strong>{{ __('Sections') }} </strong></label>
                                                    <select name="section_id" class="form-control submitable"
                                                        id="section_id">
                                                        <option value="">{{ __('All') }}</option>
                                                        @foreach ($sections as $key => $section)
                                                            <option value="{{ $section->id }}">
                                                                {{ $section->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-xl-2 col-md-4">
                                                    <label><strong>{{ __('Designations') }} </strong></label>
                                                    <select name="designation_id" class="form-control submitable"
                                                        id="designation_id">
                                                        <option value="">{{ __('All') }}</option>
                                                        @foreach ($designations as $key => $designation)
                                                            <option value="{{ $designation->id }}">
                                                                {{ $designation->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-xl-2 col-md-4">
                                                    <label><strong>{{ __('Promoted By') }} </strong></label>
                                                    <select name="promoted_by" class="form-control submitable"
                                                        id="promoted_by">
                                                        <option value="">{{ __('All') }}</option>
                                                        @foreach ($admin_users as $key => $user)
                                                            <option value="{{ $user->id }}">
                                                                {{ $user->employee_id }}-{{ $user->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-xl-2 col-md-4">
                                                    <label><strong>{{ __('Date Range') }} </strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-calendar-week input_i"></i></span>
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
                                        <table class="display data_tbl data__table promotion_table">
                                            <thead>
                                                <tr>
                                                    <th class="text-start">
                                                        <div>
                                                            <input type="checkbox" id="is_check_all">
                                                        </div>
                                                    </th>
                                                    <th class="text-start">{{ __('ID') }}</th>
                                                    <th class="text-start">{{ __('Name') }}</th>
                                                    <th class="text-start">{{ __('Deparment') }}</th>
                                                    <th class="text-start">{{ __('Section') }}</th>
                                                    <th class="text-start">{{ __('Designation') }}</th>
                                                    <th class="text-start">{{ __('Promoted By') }}</th>
                                                    <th class="text-start">{{ __('Promoted Date') }}</th>
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
            </div>
        </form>
        <!-- Add application Modal -->
        <div id="add_modal" class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-dark">
                        <h5 class="modal-title"><i class="icon-newspaper mr-2"></i> &nbsp; New Promotion</h5>
                        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                                class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body crate-modal-body">

                    </div>
                </div>
            </div>
        </div>
        <!-- Edit Modal -->
        <div id="editModal" class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-dark">
                        <h5 class="modal-title"><i class="icon-newspaper mr-2"></i> &nbsp; Edit Promotion</h5>
                        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                                class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body edit_modal_body">

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
        $('#section_id').select2();
        $('#designation_id').select2();
        $('#promoted_by').select2();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            var allRow = '';
            var trashedRow = '';
            var table = $('.promotion_table').DataTable({
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
                    "url": "{{ route('hrm.promotions.index') }}",
                    "data": function(data) {
                        //filter options
                        data.employee_id = $('#employee_id').val();
                        data.hrm_department_id = $('#hrm_department_id').val();
                        // data.date_range = $('.submitable_input').val();
                        data.employment_status = $('#employment_status').val();
                        data.showTrashed = $('#trashed_item').attr('showtrash');
                        data.section_id = $('#section_id').val();
                        data.designation_id = $('#designation_id').val();
                        data.promoted_by = $('#promoted_by').val();
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
                            <div class="input-group">
                                <button type="submit" id="filter_button" class="btn btn-sm btn-info">Apply</button>
                            </div>
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
                        name: 'employee_id',
                        data: 'employee_id'
                    },
                    {
                        name: 'employee_name',
                        data: 'employee_name'
                    },
                    {
                        name: 'department_name',
                        data: 'department_name'
                    },
                    {
                        name: 'section_name',
                        data: 'section_name'
                    },
                    {
                        name: 'designation_name',
                        data: 'designation_name'
                    },
                    {
                        name: 'promoted_by',
                        data: 'promoted_by'
                    },
                    {
                        name: 'promoted_date',
                        data: 'promoted_date'
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
            $(document).on('click', '#restore', function(e) {
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
                                        $('.department-table').DataTable().draw(
                                            false);
                                        $('.data_preloader').hide();
                                        toastr.error(data);
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
                            'action': function() {}
                        }
                    }
                });
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
            // Add Modal
            $(document).on('click', '#create-modal', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('#add_modal').modal('show');
                        $('.crate-modal-body').html(data);
                    }
                });
            });

            $(document).on('submit', '#add_form', function(e) {
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
                        $('#add_modal').modal('hide');
                        table.ajax.reload();
                    },
                    error: function(error) {
                        $('.loading_button').hide();
                        toastr.error(error.responseJSON.message);
                    }
                });
            });
            $(document).on('submit', '#update_form', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                $('.error').html('');

                $.ajax({
                    url: url,
                    type: 'PATCH',
                    data: request,
                    success: function(data) {
                        toastr.success(data);
                        $('#update_form')[0].reset();
                        $('.loading_button').hide();
                        $('.leave-type-table').DataTable().draw(false);
                        $('#editModal').modal('hide');
                        table.ajax.reload();
                    },
                    error: function(error) {
                        $('.loading_button').hide();
                        toastr.error(error.responseJSON.message);
                    }
                });
            });
            // });

            // $.ajax({
            //     url: "{{ route('hrm.employees.index') }}",
            //     type: 'get',
            //     dataType: 'json',
            //     success: function(data) {
            //         $.each(data.data, function(key, val) {
            //             $('#employee_id').append('<option value="' + val.id + '">' + val.name + '</option>');
            //         });
            //     }
            // });
            // $.ajax({
            //     url: "{{ route('hrm.departments.index') }}",
            //     type: 'get',
            //     dataType: 'json',
            //     success: function(data) {
            //         $.each(data.data, function(key, val) {
            //             $('#hrm_department_id').append('<option value="' + val.id + '">' + val.name + '</option>');
            //         });
            //     }
            // });
            // $.ajax({
            //     url: "{{ route('hrm.departments.index') }}",
            //     type: 'get',
            //     dataType: 'json',
            //     success: function(data) {
            //         $.each(data.data, function(key, val) {
            //             $('#date_range').append('<option value="' + val.id + '">' + val.name + '</option>');
            //         });
            //     }
            // });
            $(document).on('change', '#employee_id', function(e) {
                e.preventDefault();
                var id = $(this).val();
                $.ajax({
                    url: "{{ route('hrm.promotions.index') }}",
                    data: {
                        "employee_id": id,
                    },
                    contentType: false,
                    processData: false,
                    success: function({
                        data
                    }) {


                    }
                });
            });
            $(document).on('change', '#hrm_department_id', function(e) {
                e.preventDefault();
                var id = $(this).val();
                $.ajax({
                    url: "{{ route('hrm.promotions.index') }}",
                    data: {
                        "hrm_department_id": id,
                    },
                    contentType: false,
                    processData: false,
                    success: function({
                        data
                    }) {


                    }
                });
            });
            $(document).on('change', '#date_range', function(e) {
                e.preventDefault();
                var id = $(this).val();
                $.ajax({
                    url: "{{ route('hrm.promotions.index') }}",
                    data: {
                        "date_range": id,
                    },
                    contentType: false,
                    processData: false,
                    success: function({
                        data
                    }) {


                    }
                });
            });
            //Submit filter form by select input changing
            $(document).on('change', '.submitable', function() {

                table.ajax.reload();

            });
            $('.submitable_input').on('hide.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format(
                    'MM/DD/YYYY'));
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

        // Edit Modal
        $(document).on('click', '#edit', function(e) {
            e.preventDefault();
            // $('.data_preloader').show();
            var url = $(this).attr('href');
            $.ajax({
                url: "{{ route('hrm.promotions.index') }}",
                data: {
                    "employee_id": id,
                },
                contentType: false,
                processData: false,
                success: function({
                    data
                }) {

                }
            });
        });
        $(document).on('click', '#delete', function(e) {
            e.preventDefault();
            var id = $(this).val();
            $.ajax({
                url: "{{ route('hrm.promotions.index') }}",
                data: {
                    "hrm_department_id": id,
                },
                contentType: false,
                processData: false,
                success: function({
                    data
                }) {

                }
            });
        });
        $(document).on('change', '#date_range', function(e) {
            e.preventDefault();
            var id = $(this).val();
            $.ajax({
                url: "{{ route('hrm.promotions.index') }}",
                data: {
                    "date_range": id,
                },
                contentType: false,
                processData: false,
                success: function({
                    data
                }) {

                }
            });
        });
        //Submit filter form by select input changing
        $(document).on('change', '.submitable', function() {

            table.ajax.reload();

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
                                    $('.promotion_table').DataTable().draw(false);
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

        $(document).on('change', '#select_department', function(e) {
            e.preventDefault();
            var id = $(this).val();
            $.ajax({
                type: 'POST',
                url: "{{ route('hrm.promotion.department') }}",
                data: {
                    id: id
                },
                success: function(data) {
                    $('#select_section').html(data);
                }
            });
        });
        $(document).on('change', '#select_section', function(e) {
            e.preventDefault();
            var id = $(this).val();
            $.ajax({
                type: 'POST',
                url: "{{ route('hrm.promotion.section') }}",
                data: {
                    id: id
                },
                success: function(data) {
                    $('#select_subsection').html(data);
                }
            });
        });

        //trashed item
        $(document).on('click', '#trashed_item', function(e) {
            e.preventDefault();
            $(this).attr("showtrash", true);
            $('.check1').prop('checked', false)
            $(this).addClass("font-weight-bold");
            $('.promotion_table').DataTable().draw(false);
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
            $('.promotion_table').DataTable().draw(false);
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

        // $(document.body).on('click', '.check1', function(event) {

        //             var allItem = $('.check1');

        //             var array = $.map(allItem, function(el, index) {
        //                 return [el]
        //             })

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
                        $("#move_to_trash").css('display', 'block');
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
    </script>
@endpush
