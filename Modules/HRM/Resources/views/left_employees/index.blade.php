@extends('layout.master')
@section('title', 'Left Employees - ')
@push('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .sorting_disabled {
            background: none;
        }



        .designation-table img {
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
            <div class="designation-header">
                <h6>{{ __('Left Employees') }}</h6>
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
                                            <div class="col-xl-2 col-md-4">
                                                <label><strong>Department :</strong></label>
                                                <select name="hrm_department_id" class="form-control submitable form-select"
                                                    id="hrm_department_id">
                                                    <option value="">All</option>
                                                </select>
                                            </div>
                                            <div class="col-xl-2 col-md-4">
                                                <label><strong>Designation :</strong></label>
                                                <select name="designation_id" class="form-control submitable form-select"
                                                    id="designation_id">
                                                    <option value="">All</option>
                                                </select>
                                            </div>
                                            <div class="col-xl-2 col-md-4">
                                                <label><strong>Left Date :</strong></label>
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
            <form id="bulk_action_form" action="{{ route('hrm.employees.bulk-action') }}" method="POST">
                <div class="row mt-1">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                                </div>
                                <form id="bulk_action">
                                    <div class="table-responsive h-350" id="data-list">
                                        <table class="display data_tbl data__table designation-table">
                                            <thead>
                                                <tr>
                                                    <th class="text-start">
                                                        <div>
                                                            <input type="checkbox" id="is_check_all">
                                                        </div>
                                                    </th>
                                                    <th class="text-start">{{ __('Employee ID') }}</th>
                                                    <th class="text-start">{{ __('Name') }}</th>
                                                    <th class="text-start">{{ __('Present Address') }}</th>
                                                    <th class="text-start">{{ __('Section') }}</th>
                                                    <th class="text-start">{{ __('Designation') }}</th>
                                                    <th class="text-start">{{ __('Join Date') }}</th>
                                                    <th class="text-start">{{ __('Left Date') }}</th>
                                                    <th class="text-start">{{ __('Phone') }}</th>
                                                    <th class="text-start">{{ __('Action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody id="left-reload"></tbody>
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
    <!-- First Modal -->
    <!-- First Modal -->
    <div class="modal fade" id="firstModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">
                        {{ __('First Letter Employee') }}
                    </h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="first_letter_modal_body">

                </div>
            </div>
        </div>
    </div>
    <!-- secondModal Modal -->
    <div class="modal fade" id="second_letter_modal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">
                        {{ __('Second Letter Employee') }}
                    </h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="second_letter_modal_body">

                </div>
            </div>
        </div>
    </div>
    <!-- Third Modal -->
    <div class="modal fade" id="third_letter_modal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">
                        {{ __('Third Letter Employee') }}
                    </h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="third_letter_modal_body">

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
            var trashedRow = '';
            var table = $('.designation-table').DataTable({
                dom: "lBfrtip",
                buttons: [{
                        extend: 'pdf',
                        text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                        className: 'pdf btn text-white btn-sm px-1',
                        exportOptions: {
                            columns: [2, 4, 7, 8, 9, 10, 11, 12, 13, 14]
                        }
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                        className: 'pdf btn text-white btn-sm px-1',
                        exportOptions: {
                            columns: [2, 4, 7, 8, 9, 10, 11, 12, 13, 14]
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                        className: 'pdf btn text-white btn-sm px-1',
                        exportOptions: {
                            columns: [2, 4, 7, 8, 9, 10, 11, 12, 13, 14]
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
                    "url": "{{ route('hrm.left-employees.index') }}",
                    "data": function(data) {

                        //send types of request for colums

                        //filter options
                        data.hrm_department_id = $('#hrm_department_id').val();
                        data.shift_id = $('#shift_id').val();
                        data.grade_id = $('#grade_id').val();
                        data.designation_id = $('#designation_id').val();
                        data.date_range = $('#date_range').val();
                        // data.date_range = $('.submitable_input').val();
                        data.employment_status = $('#employment_status').val();

                    }
                },
                "drawCallback": function(data) {
                    allRow = data.json.allRow;

                    $('#all_item').text('All (' + allRow + ')');
                    $('#is_check_all').prop('checked', false);

                    $("#bulk_action_field option:selected").prop("selected", false);
                    $('#all_item').addClass("font-weight-bold");


                },

                initComplete: function() {

                    var toolbar = `<div class="d-flex">
                                    <div class="me-3">
                                            <a href="#" style="color:#2688cd" class="font-weight-bold" id="all_item">All</a>
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
                    $("#bulk_action_field option:selected").prop("selected", false);
                    $('#all_item').addClass("font-weight-bold");

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
                        name: 'name',
                        data: 'name'
                    },
                    {
                        name: 'permanent_address',
                        data: 'permanent_address'
                    },
                    {
                        name: 'section',
                        data: 'section'
                    },
                    {
                        name: 'designation',
                        data: 'designation'
                    },
                    {
                        name: 'joining_date',
                        data: 'joining_date'
                    },
                    {
                        name: 'left_date',
                        data: 'left_date'
                    },
                    {
                        name: 'phone',
                        data: 'phone'
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

            // Edit Modal
            $(document).on('click', '.left', function(e) {
                e.preventDefault();
                $('.data_preloader').show();
                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('#left_modal_body').html(data);
                        $('#leftModal').modal('show');
                        $('.data_preloader').hide();

                        table.ajax.reload();
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
            $(document).on('click', '.resign', function(e) {
                e.preventDefault();
                $('.data_preloader').show();
                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('#resign_modal_body').html(data);
                        $('#resignModal').modal('show');
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
            // First Modal
            $(document).on('click', '.openModal', function(e) {
                e.preventDefault();
                $('.data_preloader').show();
                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('#firstModal').modal('show');
                        $('#first_letter_modal_body').html(data);
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
            // second Modal
            $(document).on('click', '.secondModal', function(e) {
                e.preventDefault();
                $('.data_preloader').show();
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('#second_letter_modal').modal('show');
                        $('#second_letter_modal_body').html(data);
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
            // third Modal
            $(document).on('click', '.thirdModal', function(e) {
                e.preventDefault();
                $('.data_preloader').show();
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('#third_letter_modal').modal('show');
                        $('#third_letter_modal_body').html(data);
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
                url: "{{ route('hrm.designations.index') }}",
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
                url: "{{ route('hrm.left-employees.index') }}",
                type: 'get',
                dataType: 'json',
                success: function(data) {
                    $.each(data.data, function(key, val) {
                        $('#date_range').append('<option value="' + val.id + '">' + val.name +
                            '</option>');
                    });
                }
            });

            // $(document).on('submit', '.filter_field', function(e) {
            //     e.preventDefault();
            //     $('.data_preloader').show();
            //     table.ajax.reload();
            // });

            $(document).on('change', '#designation_id', function(e) {
                e.preventDefault();
                var selected = $(this).val();
                $.ajax({
                    url: "{{ route('hrm.left-employees.index') }}",
                    data: {
                        "designation_id": selected,
                    },
                    contentType: false,
                    processData: false,
                    success: function({
                        data
                    }) {}
                });
            });

            $(document).on('change', '#date_range', function(e) {
                e.preventDefault();
                var selected = $(this).val();
                $.ajax({
                    url: "{{ route('hrm.resign-employees.index') }}",
                    data: {
                        "date_range": selected,
                    },
                    contentType: false,
                    processData: false,
                    success: function({
                        data
                    }) {

                    }
                });
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
        // Active status
        $(document).on('click', '#activeEmp', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');

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
                        toastr.error('Net Connetion Error. Reload This Page.');
                    } else {
                        toastr.error('Server Error. Please contact to the support team.');
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
                                    $('.designation-table').DataTable().draw(false);
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
                                    $('.designation-table').DataTable().draw(false);
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

        $('body').on('click', '#print_one_id_get', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            $("#user_id").val($(this).data('id')); //set userid on uerid field
        });
    </script>
@endpush
