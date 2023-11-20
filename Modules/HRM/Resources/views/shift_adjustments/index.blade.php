@extends('layout.master')
@section('title', 'Shifts Adjustment - ')


@section('content')
    <div class="body-wraper">
        <div class="sec-name">
            <div class="section-header">
                <h6>{{ __('Shift Adjustment') }}</h6>
            </div>
            <x-all-buttons>
                <x-slot name="before">
                    <x-add-button :can="'hrm_shift_adjustments_create'" />
                </x-slot>
            </x-all-buttons>
        </div>

        <form id="bulk_action_form" action="{{ route('hrm.shift-adjustments.bulk-action') }}" method="POST">
            <div class="p-15">
                <div class="card">
                    <div class="card-body">
                        <div class="data_preloader">
                            <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                        </div>
                        <form id="bulk_action">
                            <div class="table-responsive h-350" id="data-list">
                                <table class="display data_tbl data__table shift-adjustment-table">
                                    <thead>
                                        <tr>
                                            <th class="text-start">
                                                <div>
                                                    <input type="checkbox" id="is_check_all">
                                                </div>
                                            </th>
                                            <th class="text-start">{{ __('Sl No.') }}</th>
                                            <th class="text-start">{{ __('Applied From ( d-m-Y )') }}</th>
                                            <th class="text-start">{{ __('Applied To ( d-m-Y )') }}</th>
                                            <th class="text-start">{{ __('Shift Name') }}</th>
                                            <th class="text-start">{{ __('Shift Start Time') }}</th>
                                            <th class="text-start">{{ __('Late Count') }}</th>
                                            <th class="text-start">{{ __('With Break') }}</th>
                                            <th class="text-start">{{ __('O.T. Break Start') }}</th>
                                            <th class="text-start">{{ __('O.T. Break End') }}</th>
                                            <th class="text-start">{{ __('Shift End Time') }}</th>
                                            <th class="text-start">@lang('menu.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">
                        {{ __('Add Shift Adjustment') }}
                    </h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_form" action="{{ route('hrm.shift-adjustments.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row mt-1">
                            <div class="col-xl-4 col-md-6">
                                <label><strong> {{ __('Shift Name') }}</strong><span class="text-danger">*</span></label>
                                <select name="shift_id" class="form-control submit_able form-select" id="section_id"
                                    autofocus="" required>
                                    <option value="" selected>Select</option>
                                    @foreach ($shifts as $shift)
                                        <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                                    @endforeach
                                </select>
                                <span class="error error_shift_id"></span>
                            </div>
                        </div>
                        <div class="row mt-1 form-group">
                            <div class="col-xl-4 col-md-6">
                                <label><strong> {{ __('Start Time') }}</strong> <span class="text-danger">*</span></label>
                                <input type="time" name="start_time" class="form-control add_input"
                                    data-name="Start Time" id="start_time" placeholder="Start Time" />
                                <span class="error error_start_time"></span>
                            </div>

                            <div class="col-xl-4 col-md-6">
                                <label><strong> {{ __('Late Count Time') }}</strong> <span
                                        class="text-danger">*</span></label>
                                <input type="time" name="late_count" class="form-control add_input"
                                    data-name="Late Count Time" id="start_time" placeholder="Late Count Time" />
                                <span class="error error_late_count"></span>
                            </div>

                            <div class="col-xl-4 col-md-6">
                                <label><strong> {{ __('End Time') }}</strong> <span class="text-danger">*</span></label>
                                <input type="time" name="end_time" class="form-control" data-name="{{ __('End Time') }}"
                                    id="end_time" placeholder="{{ __('End Time') }}" required />
                                <span class="error error_end_time"></span>
                            </div>
                        </div>
                        <div class="form-group row mt-1">
                            <div class="col-xl-4 col-md-6">
                                <label><strong> {{ __('Applied From Date (DD/MM/YYYY)') }}</strong> <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="applied_date_from" class="form-control"
                                    data-name="applied_date_from" id="applied_date_from"
                                    placeholder="{{ __('Applied From') }}" required />
                                <span class="error error_applied_date_to"></span>
                            </div>
                            <div class="col-xl-4 col-md-6">
                                <label><strong> {{ __('Applied To Date (DD/MM/YYYY)') }}</strong> <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="applied_date_to" class="form-control"
                                    data-name="applied_date_from" id="applied_date_to"
                                    placeholder="{{ __('Applied To') }}" required />
                                <span class="error error_applied_date_to"></span>
                            </div>
                        </div>
                        <div class="row mt-1">

                            <div class="col-xl-4 col-md-6">
                                <div class="row mt-4">
                                    <p class="checkbox_input_wrap">
                                        <input type="checkbox" name="with_break" id="with_break" value="0"> &nbsp;
                                        <label for="with_break">{{ __('Allow OverTime Break (Optional)') }}</label>
                                    </p>
                                </div>
                                <span class="error error_with_break"></span>
                            </div>

                            <div class="col-xl-4 col-md-6 break_start">
                                <label><strong> {{ __('Break Start (Optional)') }}</strong></label>
                                <input type="time" name="break_start" class="form-control"
                                    data-name="{{ __('Break Start') }}" placeholder="{{ __('Break Start') }}" />
                                <span class="error error_break_start"></span>
                            </div>

                            <div class="col-xl-4 col-md-6 break_end">
                                <label><strong> {{ __('Break End (Optional)') }}</strong></label>
                                <input type="time" name="break_end" class="form-control"
                                    data-name="{{ __('Break End') }}" placeholder="{{ __('Over Time Break End') }}" />
                                <span class="error error_break_end"></span>
                            </div>
                            <div>
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
    </div>
    </div>
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">
                        {{ __('Edit Shift Adjustment') }}
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
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //$('#body-wraper').attr("showtrash", true)

        $(document).ready(function() {
            var allRow = '';
            var trashedRow = '';
            var table = $('.shift-adjustment-table').DataTable({
                dom: "lBfrtip",
                buttons: [{
                        extend: 'pdf',
                        text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                        className: 'pdf btn text-white btn-sm px-1',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                        }
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                        className: 'pdf btn text-white btn-sm px-1',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                        className: 'pdf btn text-white btn-sm px-1',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
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
                    "url": "{{ route('hrm.shift-adjustments.index') }}",
                    "data": function(data) {

                        //send types of request for colums
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
                        // bodyWrapper = $('#body-wraper').attr('showtrash');
                        // if(bodyWrapper == 'true'){
                        //     $('#body-wraper').attr("showtrash", false)
                        //     $('.shift-adjustment-table').DataTable().draw(false);

                        // }
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
                        name: 'Sl No.',
                        data: 'DT_RowIndex'
                    },
                    {
                        name: 'applied_date_from',
                        data: 'applied_date_from'
                    },
                    {
                        name: 'applied_date_to',
                        data: 'applied_date_to'
                    },
                    {
                        name: 'shift_name',
                        data: 'shift_name'
                    },
                    {
                        name: 'start_time',
                        data: 'start_time'
                    },
                    {
                        name: 'late_count',
                        data: 'late_count'
                    },
                    {
                        name: 'with_break',
                        data: 'with_break'
                    },
                    {
                        name: 'break_start',
                        data: 'break_start'
                    },
                    {
                        name: 'break_end',
                        data: 'break_end'
                    },
                    {
                        name: 'end_time',
                        data: 'end_time'
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

            //Check Box Config   
            if ($('#with_break').is(':checked')) {
                $('.break_start').show();
                $('.break_end').show();
            } else {
                $('.break_start').hide();
                $('.break_end').hide();
            }

            $(document.body).on('click', '#with_break', function(event) {
                var checked = event.target.checked;
                if (true == checked) {
                    $('#with_break').attr('value', 1);
                    $('.break_start').show();
                    $('.break_end').show();
                }
                if (false == checked) {
                    $('#with_break').attr('value', 0);
                    $('.break_start').hide();
                    $('.break_end').hide();

                }
            });

            //Litepicker
            new Litepicker({
                singleMode: true,
                element: document.getElementById('applied_date_from'),
                dropdowns: {
                    minYear: new Date().getFullYear() - 50,
                    maxYear: new Date().getFullYear() + 100,
                    months: true,
                    years: true
                },
                tooltipText: {
                    one: 'night',
                    other: 'nights'
                },
                tooltipNumber: (totalDays) => {
                    return totalDays - 1;
                },
                format: 'DD-MM-YYYY'
            });


            new Litepicker({
                singleMode: true,
                element: document.getElementById('applied_date_to'),
                dropdowns: {
                    minYear: new Date().getFullYear() - 50,
                    maxYear: new Date().getFullYear() + 100,
                    months: true,
                    years: true
                },
                tooltipText: {
                    one: 'night',
                    other: 'nights'
                },
                tooltipNumber: (totalDays) => {
                    return totalDays - 1;
                },
                format: 'DD-MM-YYYY'
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
            //$('#body-wraper').attr("showtrash", true)
            $('.check1').prop('checked', false)
            $(this).addClass("font-weight-bold");
            $('.shift-adjustment-table').DataTable().draw(false);
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
            //$('#body-wraper').attr("showtrash", true)
            $('#is_check_all').prop('checked', false);
            $('.check1').prop('checked', false);
            trashed_item.attr("showtrash", false);
            $(this).addClass("font-weight-bold");
            $('.shift-adjustment-table').DataTable().draw(false);
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
                                    $('.shift-adjustment-table').DataTable().draw(false);
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
                                    $('.shift-adjustment-table').DataTable().draw(false);
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
    </script>
@endpush
