@extends('layout.master', ['custom_modal' => false])
@section('title', 'Notice - ')

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
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('Notice') }}</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="before">
                        <x-add-button :can="'hrm_awards_create'" />
                    </x-slot>
                    <x-slot name="after">
                        <a href="#" class="btn btn-sm d-lg-block d-none"><span><span
                                    class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</span></a>
                    </x-slot>
                </x-all-buttons>
            </div>

        </div>

        <div class="p-15">
            <div class="row g-0">
                <div class="col-md-12 ">
                    <div class="form_element m-0 rounded">
                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form id="filter_form">
                                        <div class="form-group row">
                                            <div class="col-xl-2 col-md-4">
                                                <label><strong>{{ __('Status') }}</strong></label>
                                                <select name="status_id" class="form-control submitable form-select"
                                                    id="status_id">
                                                    <option value selected>@lang('menu.all')</option>
                                                    <option value="1">Active</option>
                                                    <option value="2">Inactive</option>
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
            <form id="bulk_action_form" action="{{ route('hrm.notices.bulk-action') }}" method="POST">
                <div class="row">
                    <div class="col-12">
                        <div class="card mt-1">
                            <div class="card-body">
                                <div class="table-responsive h-350" id="data-list">

                                    <table class="display data_tbl data__table notice_table">
                                        <thead>
                                            <tr>
                                                <th class="text-start">
                                                    <div>
                                                        <input type="checkbox" id="is_check_all">
                                                    </div>
                                                </th>
                                                <th class="text-start">{{ __('Sl') }}</th>
                                                <th class="text-start">@lang('menu.action')</th>
                                                <th class="text-start">@lang('menu.title')</th>
                                                <th class="text-start">@lang('menu.date')</th>
                                                <th class="text-start">{{ __('Status') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Edit Modal -->
        <div id="editModal" class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="icon-newspaper mr-2"></i> &nbsp; Edit Notice</h5>
                        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                                class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body edit_modal_body">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content" style="margin-left: 2%; margin-top: 6%;">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_notice')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <form id="add_notice_form" action="{{ route('hrm.notices.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row mt-1">
                            <div class="col-md-12">
                                <label><strong>@lang('menu.title') </strong> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title">
                                <span class="error error_title"></span>
                            </div>
                            <div class="col-md-6">
                                <label><strong>{{ __('Notice By') }} </strong></label>
                                <input type="text" name="notice_by" class="form-control">
                                <span class="error error_notice_by"></span>
                            </div>
                            <div class="col-md-4">
                                <label><strong>@lang('menu.add_file') </strong></label>
                                <input type="file" name="attachment" class="form-control" id="attachment">
                                <span class="error error_attachment"></span>
                            </div>
                            <div class="col-md-2">
                                <img src="{{ asset('images/profile-picture.jpg') }}"
                                    style="height:70px; width:70px; margin-top: 13px;" id="p_avatar" class="d-none"
                                    alt="No image">
                            </div>
                            <div class="col-md-12">
                                <label><strong>@lang('menu.description')</strong></label>
                                <textarea name="description" rows="10" class="form-control ckEditor" contenteditable="true" id="description"
                                    placeholder="Description"></textarea>
                                <span class="error error_description"></span>
                            </div>
                            <div class="mt-3">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <div class="loading-btn-box">
                                        <button type="button" class="btn btn-sm loading_button display-none float-end"><i
                                                class="fas fa-spinner"></i></button>
                                        <button type="submit"
                                            class="btn btn-sm btn-success float-start float-end">@lang('menu.save')</button>
                                        <button type="reset" data-bs-dismiss="modal"
                                            class="btn btn-sm btn-danger float-start float-end me-2">@lang('menu.close')</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="requisition_details">
        <div class="modal-dialog four-col-modal" role="document" id="edit-content">

        </div>
    </div>

    <form id="deleted_form" action="" method="post">
        @csrf
        @method('DELETE')
    </form>

@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


    <script>
        var table;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#status_id').select2();
        $(document).ready(function() {
            var allRow = '';
            var trashedRow = '';
            // var table = $('.holiday-table').DataTable({
            table = $('.notice_table').DataTable({
                dom: "lBfrtip",
                buttons: [{
                    extend: 'pdf',
                    text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [1, 3, 4]
                    }
                }, {
                    extend: 'excel',
                    text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [1, 3, 4]
                    }
                }, {
                    extend: 'print',
                    text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [1, 3, 4]
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
                    "url": "{{ route('hrm.notices.index') }}",
                    "data": function(data) {

                        //send types of request for colums
                        data.showTrashed = $('#trashed_item').attr('showtrash');
                        //filter options
                        data.status_id = $('#status_id').val();
                        data.date_range = $('.submitable_input').val();

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
                    name: 'action',
                    data: 'action'
                }, {
                    name: 'title',
                    data: 'title'
                }, {
                    name: 'created_at',
                    data: 'created_at'
                }, {
                    name: 'status',
                    data: 'status'
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

            // Add category by ajax
            $(document).on('submit', '#add_notice_form', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                $.ajax({
                    url: url,
                    type: 'post',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        toastr.success(data);
                        $('#add_notice_form')[0].reset();
                        $('#addModal').hide();
                        $('.loading_button').hide();
                        table.ajax.reload();
                    },
                    error: function(err) {
                        $('.loading_button').hide();
                        if (err.status == 0) {
                            toastr.error('Net Connetion Error. Reload This Page.');
                            return;
                        }
                        $.each(err.responseJSON.errors, function(key, error) {
                            $('.error_' + key + '').html(error[0]);
                        });
                    }
                });
            });
            // Edit category by ajax
            $(document).on('submit', '#edit_notice_form', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                $.ajax({
                    url: url,
                    type: 'post',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        toastr.success(data);
                        $('#edit_notice_form')[0].reset();
                        $('#editModal .close-modal').click();
                        $('.notice_table').DataTable().draw(false);
                        $('.loading_button').hide();
                        table.ajax.reload();
                    },
                    error: function(err) {
                        $('.loading_button').hide();
                        if (err.status == 0) {
                            toastr.error('Net Connetion Error. Reload This Page.');
                            return;
                        }
                        $.each(err.responseJSON.errors, function(key, error) {
                            $('.error_' + key + '').html(error[0]);
                        });
                    }
                });
            });
            // Data delete by ajax
            $(document).on('click', '.delete', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $('#deleted_form').attr('action', url);
                $.confirm({
                    'title': 'Delete Confirmation',
                    'content': 'Are you sure?',
                    'buttons': {
                        'Yes': {
                            'class': 'yes btn-primary',
                            'action': function() {
                                $('#deleted_form').submit();
                            }
                        },
                        'No': {
                            'class': 'no btn-danger',
                            'action': function() {

                            }
                        }
                    }
                });
            });

            // Data delete by ajax
            $(document).on('submit', '#deleted_form', function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {
                        if ($.isEmptyObject(data.errorMsg)) {
                            toastr.error(data);
                            table.ajax.reload();
                        } else {
                            toastr.error(data.errorMsg);
                        }
                    },
                    error: function(err) {
                        if (err.status == 0) {
                            toastr.error('Net Connetion Error. Please check the connection.');
                        } else if (err.status == 500) {
                            toastr.error('Server Error. Please contact to the support team.');
                        }
                    }
                });
            });
            // Edit Modal

            $(document).on('click', '.edit', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('#editModal').modal('show');
                        $('.edit_modal_body').html(data);
                        $('.notice_table').DataTable().draw(false);
                        $('.data_preloader').hide();
                    }
                });
            });
        });

        $(document).on('click', '#view', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.get(url, function(data) {
                $('#requisition_details').html(data);
                $('#detailsModal').modal('show');
            })
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

        //trashed item
        $(document).on('click', '#trashed_item', function(e) {
            e.preventDefault();
            $(this).attr("showtrash", true);
            $('.check1').prop('checked', false)
            $(this).addClass("font-weight-bold");
            $('.notice_table').DataTable().draw(false);
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
            $('.notice_table').DataTable().draw(false);
            $('#trashed_item').removeClass("font-weight-bold")
            $("#delete_option").css('display', 'none');
            $("#restore_option").css('display', 'none');
            $("#move_to_trash").css('display', 'block');
        })

        $(document).on('change', '#status_id', function(e) {
            var status = $(this).val();
            $.ajax({
                url: "{{ route('hrm.notices.index') }}",
                type: 'get',
                data: {
                    status: status
                },
            });
        })

        //show image on add from with jquery
        $("#attachment").change(function() {
            var file = $("#attachment").get(0).files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function() {
                    var extension = file.name.split(".").pop();
                    $("#p_avatar").attr("src", reader.result);
                    $("#p_avatar").attr("alt", extension);
                    $("#p_avatar").removeClass("d-none");
                    $("#p_avatar").addClass("d-block");
                }
                reader.readAsDataURL(file);
            }
        });



        $(document).on('click', '.change_status', function(e) {
            e.preventDefault();
            var status = $(this).val();
            var url = $(this).attr('data-url');
            $.confirm({
                'title': '{{ __('Status Activation or Deactivation') }}',
                'content': '{{ __('You want to Change?') }}',
                'buttons': {
                    'Yes': {

                        'class': 'yes btn-primary',
                        'action': function() {
                            $.ajax({
                                url: url,
                                type: 'get',
                                data: {
                                    status: status
                                },
                                success: function(data) {
                                    if ($.isEmptyObject(data.errorMsg)) {
                                        toastr.success(data);
                                        table.ajax.reload();
                                    } else {
                                        toastr.success(data.errorMsg);
                                    }
                                },
                                error: function(err) {
                                    if (err.status == 0) {
                                        toastr.error(
                                            'Net Connetion Error. Please check the connection.'
                                        );
                                    } else if (err.status == 500) {
                                        toastr.error(
                                            'Server Error. Please contact to the support team.'
                                        );
                                    }
                                }
                            });
                        }
                    },
                    'No': {
                        'class': 'no btn-danger',
                        'action': function() {

                        }
                    }
                }
            });
        });

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
                                    table.ajax.reload();
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
    </script>
@endpush
