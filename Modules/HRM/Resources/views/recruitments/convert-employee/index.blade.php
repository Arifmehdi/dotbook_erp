@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        button.btn.btn-danger.deletewarrantyButton {
            border-radius: 0px !important;
            padding: 0.7px 10px !important;
        }

        .form-title {
            background: transparent;
            color: #0c0c0c;
            text-shadow: 0 0;
            height: 50px;
            line-height: 50px;
            margin: 0px;
        }
    </style>
@endpush
@section('title', 'Recruitment - ')
@section('content')
    <div class="body-wraper">

        @canany(['web_job_applied_download', 'web_job_applied_delete'])
            <div class="main__content">
                <div class="sec-name">
                    <div class="name-head">
                        <h6>@lang(__('Convert Employee List'))</h6>
                    </div>
                    <x-all-buttons>
                        <x-slot name="after">
                            <x-help-button />
                        </x-slot>
                    </x-all-buttons>
                </div>
            </div>
            {{-- <form id="bulk_action_form" action="{{ route('hrm.applicant_offer_letter_bulk-action') }}" method="POST"> --}}
            @csrf

            <div class="p-15">
                <div class="form_element m-0 rounded">
                    <div class="element-body">
                        <form id="filter_form">
                            <div class="form-group row">
                                <div class="col-xl-2 col-md-4">
                                    <label><strong>Job Category </strong></label>
                                    <select name="job_category_id" class="form-control submitable form-select"
                                        id="job_category_id">
                                        <option value="">@lang('menu.all')</option>
                                        <option value="">{{ __('Select Category') }}</option>
                                        @foreach ($jobCategories as $jobCategory)
                                            <option value="{{ $jobCategory->id }}">{{ $jobCategory->jobCategory_id }}
                                                -{{ $jobCategory->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-2 col-md-4">
                                    <label><strong>Job Title </strong></label>
                                    <select name="job_title" class="form-control submitable form-select" id="job_title">
                                        <option value="">@lang('menu.all')</option>
                                        <option value="">{{ __('Select Title') }}</option>
                                        @foreach ($jobTitles as $jobTitle)
                                            <option value="{{ $jobTitle->job_title }}">{{ $jobTitle->job_title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-2 col-md-4">
                                    <label><strong>Apply Date</strong></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i
                                                    class="fas fa-calendar-week input_i"></i></span>
                                        </div>
                                        <input type="search" name="date_range" id="date_range"
                                            class="form-control reportrange submitable_input date_range" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card mt-1">
                    <div class="card-body">
                        <div class="data_preloader">
                            <h6><i class="fas fa-spinner"></i> @lang('menu.processing')</h6>
                        </div>
                        <form id="bulk_action">
                            <div class="table-responsive h-350" id="data-list">
                                <table class="display data_tbl data__table selectedForInterviewTable">
                                    <thead>
                                        <tr>
                                            <th class="text-start">
                                                <div>
                                                    <input type="checkbox" id="is_check_all">
                                                </div>
                                            </th>
                                            <th class="text-start">@lang('menu.action')</th>
                                            <th class="text-start">@lang('menu.job_title')</th>
                                            <th class="text-start">@lang('menu.name')</th>
                                            <th class="text-start">@lang('menu.email')</th>
                                            <th class="text-start">@lang('menu.mobile')</th>
                                            {{-- <th class="text-start">@lang('menu.resume')</th> --}}
                                            <th class="text-start">{{ __('Apply Date') }}</th>
                                            <th class="text-start">@lang('menu.status')</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <div class="p-15">
                <div class="bd-callout bd-callout-info">
                    <code>Warning!!</code> You do not have permission to access please contact with administrator.
                </div>
            </div>
            @endif
            {{-- </form> --}}
        </div>
        <!-- View Modal -->
        <div id="viewModal" class="modal fade" tabindex="-1">
            <div class="modal-dialog four-col-modal ui-draggable ui-draggable-handle">
                <div class="modal-content">
                    <div class="modal-header bg-dark">
                        <h5 class="modal-title"><i class="icon-plus-circle2 mr-2"></i> &nbsp;Employee's Details</h5>
                        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                                class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body" id="view_modal_body">
                    </div>
                </div>
            </div>
        </div>

        <form id="deleted_form" action="" method="POST">
            @csrf
        </form>
    @endsection

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
            integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#job_category_id').select2();
            $('#job_title').select2();
            $('#apply_date').select2();
            var table;
            $(document).ready(function() {
                var allRow = '';
                var selectedForInterviewRow = '';
                table = $('.selectedForInterviewTable').DataTable({
                    processing: true,
                    serverSide: true,
                    searchable: true,
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
                    }],
                    serverSide: true,
                    "pageLength": parseInt(
                        "{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
                    "lengthMenu": [
                        [10, 25, 50, 100, 500, 1000, -1],
                        [10, 25, 50, 100, 500, 1000, "All"]
                    ],
                    "ajax": {
                        "url": "{{ route('hrm.convert_employee_list') }}",
                        "data": function(data) {
                            //filter options
                            data.job_category_id = $('#job_category_id').val();
                            data.job_title = $('#job_title').val();
                            data.date_range = $('#date_range').val();
                            data.date_range = $('.submitable_input').val();
                        }
                    },
                    "drawCallback": function(data) {
                        allRow = data.json.allRow;
                        selectedForInterviewRow = data.json.selectedForInterviewRow;
                        $('#all_item').text('All (' + allRow + ')');
                        $('#is_check_all').prop('checked', false);
                        $('#selected_for_interview_item').text('');
                        $('#selected_for_interview_item_separator').text('');
                        $("#bulk_action_field option:selected").prop("selected", false);
                        if (selectedForInterviewRow > 0) {
                            $('#selected_for_interview_item').text('For Interviw Applicant (' +
                                selectedForInterviewRow + ')');
                        }

                    },
                    initComplete: function() {
                        var toolbar =
                            `<div class="me-3">
                    <a href="#" style="color:#2688cd" class="font-weight-bold" id="all_item">All</a>
                </div>
                <div class="d-flex">
                    <div class="me-3">
                            <span style="color:#2688cd; margin-right:3px;" id="selected_for_interview_item_separator"></span><a style="color:#2688cd" href="#" id="selected_for_interview_item"></a>
                    </div>
                    <div class="form-group row align-items-end g-2">
                        <div class="col-8" >
                            <select name="action_type" id="bulk_action_field" class="form-control submit_able form-select" required>
                                <option value="" selected>Bulk Actions</option>
                                <option value="bulk_select_for_interview" id="bulk_select_for_interview">Bulk Select For Reject</option>
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
                        $("#bulk_select_for_interview").css('display', 'block');
                        $('#all_item').text('All (' + allRow + ')');
                        $('#is_check_all').prop('checked', false);
                        $('#selected_for_interview_item').text('');
                        $('#selected_for_interview_item_separator').text('');
                        $("#bulk_action_field option:selected").prop("selected", false);
                        if (selectedForInterviewRow > 0) {
                            $('#selected_for_interview_item').text('For Interviw Applicant (' +
                                selectedForInterviewRow + ')');
                        }
                        $("#selected_for_interview_item").addClass('font-weight-bold');
                    },
                    columns: [{
                        name: 'check',
                        data: 'check',
                        sWidth: '3%',
                        orderable: false,
                        targets: 0
                    }, {
                        data: 'action',
                        name: 'action'
                    }, {
                        data: 'job_title',
                        name: 'job_title'
                    }, {
                        data: 'name',
                        name: 'name'
                    }, {
                        data: 'email',
                        name: 'email'
                    }, {
                        data: 'mobile',
                        name: 'mobile'
                    }, {
                        data: 'apply_date',
                        name: 'Apply Date'
                    }, {
                        data: 'status',
                        name: 'status'
                    }]
                });
                table.buttons().container().appendTo('#exportButtonsContainer');
                $(document).on('click', '#delete', function(e) {
                    e.preventDefault();
                    var url = $(this).attr('href');
                    $('#deleted_form').attr('action', url);
                    $.confirm({
                        'title': 'Delete Confirmation',
                        'message': 'Are you sure?',
                        'buttons': {
                            'Yes': {
                                'class': 'yes btn-danger',
                                'action': function() {
                                    $('#deleted_form').submit();
                                }
                            },
                            'No': {
                                'class': 'no btn-primary',
                                'action': function() {

                                }
                            }
                        }
                    });
                });
                //Submit filter form by select input changing
                $(document).on('change', '.submitable', function() {
                    table.ajax.reload();
                });
                //data delete by ajax
                $(document).on('submit', '#deleted_form', function(e) {
                    e.preventDefault();
                    var url = $(this).attr('action');
                    var request = $(this).serialize();
                    $.ajax({
                        url: url,
                        type: 'post',
                        async: false,
                        data: request,
                        success: function(data) {
                            toastr.success(data);
                            if (!$.isEmptyObject(data.errorMsg)) {

                                toastr.error(data.errorMsg, 'Attention');
                                return;
                            }
                            table.ajax.reload();
                            refresh();
                            toastr.error(data);
                            $('#deleted_form')[0].reset();
                        }
                    });
                });

                // View Modal
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

                // Check
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

                //Seleted for Interview item
                $(document).on('click', '#selected_for_interview_item', function(e) {
                    e.preventDefault();
                    $(this).attr("showtrash", true);
                    $('.check1').prop('checked', false)
                    $(this).addClass("font-weight-bold");
                    $('.trash_table').DataTable().draw(false);
                    $('#is_check_all').prop('checked', false);
                    $('#all_item').removeClass("font-weight-bold");
                    $("#move_to_trash").css('display', 'none');
                    $("#bulk_select_for_interview").css('display', 'none');
                })

                //all item
                $(document).on('click', '#all_item', function(e) {
                    e.preventDefault();
                    selected_for_interview_item = $('#selected_for_interview_item');
                    $('#is_check_all').prop('checked', false);
                    $('.check1').prop('checked', false);
                    selected_for_interview_item.attr("showtrash", false);
                    $(this).addClass("font-weight-bold");
                    $('.trash_table').DataTable().draw(false);
                    $('#selected_for_interview_item').removeClass("font-weight-bold")
                    $("#move_to_trash").css('display', 'block');
                    $("#bulk_select_for_interview").css('display', 'block');
                })

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
            });
        </script>
        <script>
            $('.submitable_input').on('hide.daterangepicker', function(ev, picker) {
                // $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                table.ajax.reload();
            });

            $('#date_range').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
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
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                            'month').endOf('month')],
                        'This Year': [moment().startOf('year'), moment().endOf('year')],
                        'Last Year': [moment().startOf('year').subtract(1, 'year'), moment().endOf('year')
                            .subtract(1, 'year')
                        ],
                    }

                });
            });
        </script>
    @endpush
