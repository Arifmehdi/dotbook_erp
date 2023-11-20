@extends('layout.master')
<x-lightpicker />
@push('css')
    <style>
        .sorting_disabled {
            background: none;
        }



        .dataTables_filter {
            width: calc(100% - 150px) !important;
        }
    </style>
@endpush

@section('content')
    <div class="body-wraper">
        <div class="sec-name">
            <div class="section-header">
                <h6>{{ __('Leads To Followups List') }}</h6>
            </div>
            <x-all-buttons>
                <x-add-button :can="'crm_business_leads_create'" />
                <a href="{{ route('crm.individual-leads.import') }}" class="btn text-white btn-sm">
                    <span><i class="fa-thin fa-file-arrow-down fa-2x"></i><br>Import Leads</span>
                </a>
            </x-all-buttons>
        </div>

        <form id="bulk_action_form" action="{{ route('crm.followup.bulk-action') }}" method="POST">
            <div class="p-15">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                                </div>
                                <form id="bulk_action">
                                    <div class="table-responsive h-350" id="data-list">
                                        <table class="display data_tbl data__table followup_table">
                                            <thead>
                                                <tr>
                                                    <th class="text-start">
                                                        <div>
                                                            <input type="checkbox" id="is_check_all">
                                                        </div>
                                                    </th>
                                                    <th class="text-start">@lang('menu.action')</th>
                                                    <th class="text-start">@lang('menu.name')</th>
                                                    <th class="text-start">Type</th>
                                                    <th class="text-start">Title</th>
                                                    <th class="text-start">Status</th>
                                                    <th class="text-start">Date</th>
                                                    <th class="text-start">Category</th>
                                                    <th class="text-start">Connect By</th>
                                                    <th class="text-start">Description</th>
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

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-50-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Edit Followups Info</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="edit_modal_body"></div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
        {{-- <div class="modal-dialog two-col-modal" role="document"> --}}
        <div class="modal-dialog col-50-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Followup Leads</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="followup_modal_body">
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
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            var allRow = '';
            var trashedRow = '';
            var groupByRow = '';
            var table = $('.followup_table').DataTable({
                dom: "lBfrtip",
                buttons: [{
                    extend: 'pdf',
                    text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang(' menu.pdf')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [0, 1]
                    }
                }, {
                    extend: 'excel',
                    text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [0, 1]
                    }
                }, {
                    extend: 'print',
                    text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [0, 1]
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
                    "url": "{{ route('crm.followup.index') }}",
                    "data": function(data) {

                        //send types of request for colums
                        data.showTrashed = $('#trashed_item').attr('showtrash');
                    }
                },
                "drawCallback": function(data) {
                    allRow = data.json.allRow;
                    groupByRow = data.json.groupByRow;
                    trashedRow = data.json.trashedRow;
                    $('#all_item').text('All (' + allRow + ')');
                    $('#group_by_item').text('Group By (' + groupByRow + ')');
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
                        `
                        <div class="me-3">
                                <a href="#" style="color:#2688cd" class="font-weight-bold" id="all_item">All</a>
                                <a href="#" style="color:#2688cd" class="font-weight-bold" id="group_by_item">Group By</a>
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

                        <div class="form-group row align-items-end g-2">
                            <div class="col-8" >
                                <select name="filter_action_type" id="filter_action_field" class="form-control submit_able form-select">
                                    <option value="" selected disabled>Filter</option>
                                    <option value="Interested" id="interested_data_filter">Interested</option>
                                    <option value="Pending" id="pending_data_filter">Pending</option>
                                    <option value="Not Connect" id="not_nonnect_data_filter">Not Connect</option>
                                    <option value="Not Interested" id="not_interested_data_filter">Not Interested</option>
                                </select>
                            </div>

                            <div class="col-4">
                                <button type="button" id="status_wise_filter_button" class="btn btn-sm btn-info">Filter</button>
                            </div>
                        </div>
                        `;

                    $("div.dataTables_filter").prepend(toolbar).addClass('d-flex');
                    // $("div.dataTables_filter").addClass('d-flex');
                    $("div.dataTables_filter #bulk_action_field").parent();
                    $("#restore_option").css('display', 'none');
                    $("#delete_option").css('display', 'none');
                    $("#move_to_trash").css('display', 'block');
                    $('#all_item').text('All (' + allRow + ')');
                    $('#group_by_item').text('Group By (' + groupByRow + ')');
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
                        name: 'action',
                        data: 'action'
                    }, {
                        name: 'user_name',
                        data: 'user_name'
                    }, {
                        name: 'leads_type',
                        data: 'leads_individual_or_business'
                    }, {
                        name: 'title',
                        data: 'title'
                    }, {
                        name: 'status_color',
                        data: 'status_color'
                    }, {
                        name: 'date',
                        data: 'date'
                    }, {
                        name: 'followup_category',
                        data: 'categories.name'
                    }, {
                        name: 'followup_type',
                        data: 'followup_type'
                    }

                    , {
                        name: 'description',
                        data: 'description'
                    }

                    ,
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

            $(document).on('click', '#status_wise_filter_button', function(e) {
                e.preventDefault();
                var filter_type = $('#filter_action_field').val();
                var url = "{{ route('crm.followup.filter-action', ['filter_type' => ':filter_type']) }}";
                url = url.replace(':filter_type', filter_type);

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        toastr.success(data);

                    },
                    error: function(error) {
                        toastr.error(error.responseJSON.message);
                    }
                });
            });

        });

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

        //trashed item
        $(document).on('click', '#trashed_item', function(e) {
            e.preventDefault();
            $(this).attr("showtrash", true);
            $('.check1').prop('checked', false)
            $(this).addClass("font-weight-bold");
            $('.followup_table').DataTable().draw(false);
            $('#is_check_all').prop('checked', false);
            $('#all_item').removeClass("font-weight-bold");
            $("#delete_option").css('display', 'block');
            $("#restore_option").css('display', 'block');
            $("#move_to_trash").css('display', 'none');
            $('#group_by_item').addClass('d-none');
        })

        //all item
        $(document).on('click', '#all_item', function(e) {
            e.preventDefault();
            trashed_item = $('#trashed_item');
            $('#is_check_all').prop('checked', false);
            $('.check1').prop('checked', false);
            trashed_item.attr("showtrash", false);
            $(this).addClass("font-weight-bold");
            $('.followup_table').DataTable().draw(false);
            $('#trashed_item').removeClass("font-weight-bold")
            $("#delete_option").css('display', 'none');
            $("#restore_option").css('display', 'none');
            $("#move_to_trash").css('display', 'block');
            $('#group_by_item').removeClass('d-none');
        })

        $(document).on('click', '#group_by_item', function(e) {
            e.preventDefault();
            // alert('ok');
        })


        $('.addModal').on('click', function() {
            $.get("{{ route('crm.followup.create') }}", function(data) {
                $('#followup_modal_body').html(data);
                $('#addModal').modal('show');
            });
        });


        // $('#add_followup').on('click', function() {
        //     var url = $(this).attr('href');
        //     $.get("", function(data) {
        //         $('#followup_modal_body').html(data);
        //         $('#addModal').modal('show');
        //     });
        // });

        $(document).on('click', '#add_followup', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    $('#followup_modal_body').html(data);
                    $('#addModal').modal('show');
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

        // restore Modal
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
                                    $('.followup_table').DataTable().draw(false);
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
                                    $('.followup_table').DataTable().draw(false);
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


        // new Litepicker({
        //     singleMode: true,
        //     element: document.getElementById('followup_date'),
        //     dropdowns: {
        //         minYear: new Date().getFullYear() - 2,
        //         maxYear: new Date().getFullYear() + 10,
        //         months: true,
        //         years: true
        //     },
        //     tooltipText: {
        //         one: 'night',
        //         other: 'nights'
        //     },
        //     tooltipNumber: (totalDays) => {
        //         return totalDays - 1;
        //     },
        //     format: 'YYYY-MM-DD'
        // });

    </script>
@endpush
