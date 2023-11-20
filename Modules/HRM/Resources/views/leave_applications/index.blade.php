@extends('layout.master')
@section('title', 'Leave Applications - ')
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
            <div class="section-header">
                <h6>{{ __('Leave Application') }}</h6>
            </div>
            <x-all-buttons>
                <x-slot name="before">
                    <x-add-button :can="'hrm_leave_applications_create'" />
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
                                        <div class="col-xl-3 col-md-4">
                                            <label><strong>{{ __('Leave Type') }} </strong></label>
                                            <select name="leave_type_id" required
                                                class="form-control submit_able submitable" id="leave_type_id">
                                                <option value="">@lang('menu.all')</option>
                                                @foreach ($leaveTypes as $leaveType)
                                                    <option value="{{ $leaveType->id }}">{{ $leaveType->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @include('hrm::leave_applications.adjustment-filter-partial.filter')
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form id="bulk_action_form" action="{{ route('hrm.leave-applications.bulk-action') }}" method="POST">
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
                                        <table class="display data_tbl data__table leave_application_table">
                                            <thead>
                                                <tr>
                                                    <th class="text-start all">
                                                        <div>
                                                            <input type="checkbox" id="is_check_all">
                                                        </div>
                                                    </th>
                                                    <th class="text-start all">{{ __('Employee ID') }} </th>
                                                    <th class="text-start all">{{ __('Employee Name') }} </th>
                                                    <th class="text-start all">{{ __('Leave Type') }}</th>
                                                    <th class="text-start all">{{ __('From Date') }}</th>
                                                    <th class="text-start all">{{ __('To Date') }}</th>
                                                    <th class="text-start all">{{ __('Approved Days') }}</th>
                                                    <th class="text-start all">{{ __('Status') }}</th>
                                                    <th class="text-start all">{{ __('Paid / Unpaid') }}</th>
                                                    <th class="text-start all">{{ __('Attachment') }}</th>
                                                    <th class="text-start all">{{ __('Action') }}</th>
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
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">
                        {{ __('Add Leave Application') }}
                    </h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_form" action="{{ route('hrm.leave-applications.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-xl-12 col-md-12">
                                <label><b> {{ __('Employee Name') }}</b> <span class="text-danger">*</span></label>
                                <select name="employee_id" class="form-control submit_able form-select" id="employee_id"
                                    autofocus="">
                                    <option value="">Select</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->employee_id }} -
                                            {{ $employee->name }}</option>
                                    @endforeach
                                </select>
                                <span class="error error_employee_id"></span>
                            </div>

                        </div>
                        <div class="row">
                            <div class="form-group col-xl-6 col-md-6">
                                <label><b> {{ __('Start From') }}</b> <span class="text-danger">*</span></label>
                                <input type="date" name="from_date"
                                    class="form-control form-control-sm add_input startdate"
                                    data-name="{{ __('Start From') }}" id="startdate" placeholder="{{ __('Start From') }}"
                                    required />
                                <span class="error error_from"></span>
                            </div>
                            <div class="form-group col-xl-6 col-md-6">
                                <label><b> {{ __('End To') }}</b> <span class="text-danger">*</span></label>
                                <input type="date" name="to_date"
                                    class="form-control form-control-sm add_input enddate"
                                    data-name="{{ __('End To') }}" id="enddate" placeholder="{{ __('End To') }}"
                                    required />
                                <span class="error error_to"></span>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-xl-6 col-md-6">
                                <label> {{ __('Leave Type') }} <span class="text-danger">*</span></label>
                                <select name="leave_type_id" required class="form-control submit_able" id="leave_type_id"
                                    autofocus="">
                                    <option value="">Select</option>
                                    @foreach ($leaveTypes as $leaveType)
                                        <option value="{{ $leaveType->id }}">{{ $leaveType->name }}</option>
                                    @endforeach
                                </select>

                                <span class="error error_leave_type_id"></span>
                            </div>
                            <div class="form-group col-xl-6 col-md-6">
                                <label><b> {{ __('Paid Type') }}</b> <span class="text-danger">*</span></label>
                                <select name="is_paid" class="form-control submit_able form-select" id="is_paid"
                                    autofocus="">
                                    <option value="1">{{ __('Paid') }}</option>
                                    <option value="0"selected>{{ __('Unpaid') }}</option>
                                </select>
                                <span class="error error_is_paid"></span>
                            </div>
                        </div>

                        <div class="form-group col-xl-12 col-md-12">
                            <label><b> {{ __('Reason') }}</b> <span class="text-danger">*</span></label>
                            <textarea name="reason" id="reason" cols="30" rows="3"
                                class="form-control form-control-sm add_input " placeholder="{{ __('Enter leave Reason') }}"></textarea>
                            <span class="error error_reason"></span>
                        </div>

                        <div class="row">
                            <div class="form-group col-xl-4 col-md-4">
                                <label><b> {{ __('Num of Days') }}</b> <span class="text-danger">*</span></label>
                                <input type="number" name="approve_day"
                                    class="form-control form-control-sm add_input num_of_days"
                                    data-name="{{ __('Num of Days') }}" id="approve_day"
                                    placeholder="{{ __('Num of Days') }}" required readonly />
                                <span class="error error_approve_day"></span>
                            </div>
                            <div class="form-group col-xl-6 col-md-6">
                                <label><strong>@lang('menu.add_file') </strong></label>
                                <input type="file" name="attachment" class="form-control" id="attachment">
                                <span class="error error_attachment"></span>
                            </div>
                            <div class="col-md-2">
                                <img src="{{ asset('images/profile-picture.jpg') }}"
                                    style="height:70px; width:60px; margin-top: 13px; margin-right:10px;" id="p_avatar"
                                    class="d-none" alt="No image">
                            </div>
                        </div>

                        <input type="hidden" value="1" name="status">

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
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">
                        {{ __('Edit Leave Application') }}
                    </h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="edit_modal_body"></div>
            </div>
        </div>
    </div>


        <!-- show  Modal -->
        <div class="modal fade" id="showModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">
                        {{ __('Show Leave Application Attachment') }}
                    </h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="show_modal_body"></div>
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
        $('#employee_id').select2();
        $('.employee2').select2();
        $('#leave_type').select2();
        $('#type').select2();
        //Date Difference
        function dateDiffInDays(date1, date2) {
            // round to the nearest whole number
            return Math.round((date2 - date1) / (1000 * 60 * 60 * 24));
        }
        var table
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
                    "url": "{{ route('hrm.leave-applications.index') }}",
                    "data": function(data) {
                        //send types of request for colums
                        @include('hrm::leave_applications.adjustment-filter-partial.ajax-data-filter');
                        data.leave_type_id = $('#leave_type_id').val();
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
                    },
                    {
                        name: 'employee_id',
                        data: 'employee_id'
                    },
                    {
                        name: 'employeeName',
                        data: 'employeeName'
                    },
                    {
                        name: 'leave_type_name',
                        data: 'leave_type_name'
                    },
                    {
                        name: 'from_date',
                        data: 'from_date'
                    },
                    {
                        name: 'to_date',
                        data: 'to_date'
                    },
                    {
                        name: 'approve_day',
                        data: 'approve_day'
                    },
                    {name: 'status', data: 'status'},
                    {name: 'isPaid', data: 'isPaid'},
                    {name: 'attachment', data: 'attachment'},
                    {
                        name: 'action',
                        data: 'action'
                    }
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
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        toastr.success(data);
                        $('#add_form')[0].reset();
                        $('.loading_button').hide();
                        table.ajax.reload();
                        $('#addModal').modal('hide');
                        $("#p_avatar").removeClass("d-block");
                        $("#p_avatar").addClass("d-none");
                    },
                    error: function(error) {
                        $('.loading_button').hide();
                        toastr.error(error.responseJSON.message);
                    }
                });
            });
        });
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
            $('.leave_application_table').DataTable().draw(false);
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
            $('.leave_application_table').DataTable().draw(false);
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
        $(document).on('click', '.showModalbtn', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('#show_modal_body').html(data);
                    $('#showModal').modal('show');
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


        // restore
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
                                    $('.leave_application_table').DataTable().draw(false);
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
                                    $('.leave_application_table').DataTable().draw(false);
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
        // coder start here

        //show image on add from with jquery
        $("#attachment").change(function() {
            var file = $("#attachment").get(0).files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function() {
                    var extension = file.name.split(".").pop();
                    if (extension.toLowerCase() == 'pdf') {
                        $("#p_avatar").attr("src", "{{ asset('uploads/application/pdf.jpg') }}");
                        $("#p_avatar").attr("alt", extension);
                        $("#p_avatar").removeClass("d-none");
                        $("#p_avatar").addClass("d-block");
                    } else if (extension.toLowerCase() == 'jpg' || 'jpeg' || 'png' || 'gif') {
                        $("#p_avatar").attr("src", reader.result);
                        $("#p_avatar").attr("alt", extension);
                        $("#p_avatar").removeClass("d-none");
                        $("#p_avatar").addClass("d-block");
                    }
                }
                reader.readAsDataURL(file);
            }
        });
        // all fliter data append here
        @include('hrm::leave_applications.adjustment-filter-partial.ajax');
    </script>
@endpush
