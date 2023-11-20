@extends('layout.master')
@section('title', 'Grades - ')


@section('content')
    <div class="body-wraper">
        <div class="sec-name">
            <div class="section-header">
                <h6>{{ __('Grade') }}</h6>
            </div>
            <x-all-buttons>
                <x-slot name="before">
                    <x-add-button :can="'hrm_grades_create'" />
                </x-slot>
            </x-all-buttons>
        </div>

        <form id="bulk_action_form" action="{{ route('hrm.grades.bulk-action') }}" method="POST">
            <div class="p-15">
                <div class="card">
                    <div class="card-body">
                        <div class="data_preloader">
                            <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                        </div>
                        <div class="table-responsive h-350" id="data-list">
                            <table class="display data_tbl data__table grade-table">
                                <thead>
                                    <tr>
                                        <th class="text-start">
                                            <div>
                                                <input type="checkbox" id="is_check_all">
                                            </div>
                                        </th>
                                        <th class="text-start">{{ __('Sl No.') }}</th>
                                        <th class="text-start">{{ __('Name') }}</th>
                                        <th class="text-start">{{ __('Basic') }}</th>
                                        <th class="text-start">{{ __('House Rent') }}</th>
                                        <th class="text-start">{{ __('Medical') }}</th>
                                        <th class="text-start">{{ __('Food') }}</th>
                                        <th class="text-start">{{ __('Transport') }}</th>
                                        <th class="text-start">{{ __('Other') }}</th>
                                        <th class="text-start">{{ __('Gross Salary') }}</th>
                                        <th class="text-start">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
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
                        {{ __('Add Grade') }}
                    </h6>
                    <div>
                        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                                class="fas fa-times"></span></a>
                    </div>

                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_form" action="{{ route('hrm.grades.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row mt-1">
                            <div class="col-xl-3 col-md-6">
                                <label><strong> {{ __('Grade Name') }}</strong> <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control"
                                    data-name="{{ __('Grade Name') }}" id="name" placeholder="{{ __('Grade Name') }}"
                                    required />
                                <span class="error error_name"></span>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <label><strong> {{ __('Basic Salary') }}</strong> <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="basic" class="form-control"
                                    data-name="{{ __('Basic Salary') }}" id="name"
                                    placeholder="{{ __('Basic Salary') }}" required />
                                <span class="error error_basic"></span>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong> {{ __('House Rent') }}</strong> <span class="text-danger">*</span></label>
                                <input type="number" name="house_rent" class="form-control"
                                    data-name="{{ __('House Rent') }}" id="name" placeholder="{{ __('House Rent') }}"
                                    required />
                                <span class="error error_house_rent"></span>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong> {{ __('Medical') }}</strong> <span class="text-danger">*</span></label>
                                <input type="number" name="medical" class="form-control" data-name="{{ __('Medical') }}"
                                    id="name" placeholder="{{ __('Medical') }}" required />
                                <span class="error error_medical"></span>
                            </div>
                        </div>
                        <div class="form-group row mt-1">
                            <div class="col-xl-3 col-md-6">
                                <label><strong> {{ __('Food') }}</strong> <span class="text-danger">*</span></label>
                                <input type="number" name="food" class="form-control" data-name="{{ __('Food') }}"
                                    id="name" placeholder="{{ __('Food') }}" required />
                                <span class="error error_food"></span>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong> {{ __('Transport') }}</strong> <span class="text-danger">*</span></label>
                                <input type="number" name="transport" class="form-control"
                                    data-name="{{ __('Transport') }}" id="name"
                                    placeholder="{{ __('Transport') }}" required />
                                <span class="error error_transport"></span>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <label><strong> {{ __('Other') }}</strong> <span class="text-danger">*</span></label>
                                <input type="number" name="other" class="form-control"
                                    data-name="{{ __('Other') }}" id="name" placeholder="{{ __('Other') }}"
                                    required />
                                <span class="error error_other"></span>
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
                            <div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <!-- Edit Modal -->
    <div id="editModal" class="modal fade" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="false" aria-labelledby="GradeEditModel" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">
                        {{ __('Edit Grade') }}
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

        $(document).ready(function() {
            var allRow = '';
            var trashedRow = '';
            var table = $('.grade-table').DataTable({
                dom: "lBfrtip",
                buttons: [{
                        extend: 'pdf',
                        text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                        className: 'pdf btn text-white btn-sm px-1',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                        className: 'pdf btn text-white btn-sm px-1',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                        className: 'pdf btn text-white btn-sm px-1',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
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
                    "url": "{{ route('hrm.grades.index') }}",
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
                        name: 'name',
                        data: 'name'
                    },
                    {
                        name: 'basic',
                        data: 'basic'
                    },
                    {
                        name: 'house_rent',
                        data: 'house_rent'
                    },
                    {
                        name: 'medical',
                        data: 'medical'
                    },
                    {
                        name: 'food',
                        data: 'food'
                    },
                    {
                        name: 'transport',
                        data: 'transport'
                    },
                    {
                        name: 'other',
                        data: 'other'
                    },
                    {
                        name: 'gross_salary',
                        data: 'gross_salary'
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
            $('.grade-table').DataTable().draw(false);
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
            $('.grade-table').DataTable().draw(false);
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
                                    $('.grade-table').DataTable().draw(false);
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
                                    $('.grade-table').DataTable().draw(false);
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
