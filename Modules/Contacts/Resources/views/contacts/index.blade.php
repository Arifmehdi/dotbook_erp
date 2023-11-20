@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .common-btn {
            color: #e7e8f7 !important;
            border: 1px solid #06f526;
            border-radius: 10px;
        }

        .common-btn:hover,
        .common-btn.active {
            background: #ffffff !important;
            color: #0f0f0f !important;
            border: 1px solid #0c0c0c;
            border-radius: 15px;
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
@section('title', ' Contacts List - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('Contacts List') }}</h6>
                </div>
                <div class="d-flex gap-2">
                    <x-table-stat :items="[
                        ['id' => 'active_contact', 'name' => __('Active Contact'), 'value' => $totalActive],
                        ['id' => 'inactive_contact', 'name' => __('In-Active Contact'), 'value' => $totalInActive],
                        ['id' => 'total_contact', 'name' => __('Total Contact'), 'value' => $totalContacts],
                        ['id' => 'total_customer', 'name' => __('Total Customer'), 'value' => $totalCustomers],
                        ['id' => 'total_supplier', 'name' => __('Total Supplier'), 'value' => $totalSuppliers],
                        ['id' => 'total_lead', 'name' => __('Total Leads'), 'value' => $totalLeads],
                    ]" />
                    <x-all-buttons>
                        <x-slot name="before">
                            <x-add-button id="add_contact" :text="'Add Contact'" />
                            <a href="{{ route('contacts.import') }}" class="btn text-white btn-sm">
                                <span><i class="fa-thin fa-file-arrow-down fa-2x"></i><br>Import Contact</span>
                            </a>
                        </x-slot>
                        <x-slot name="after">
                            <x-help-button />
                        </x-slot>
                    </x-all-buttons>
                </div>
            </div>
        </div>

        <div class="p-15">
            <div class="card">
                <div class="card-body">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                    </div>
                    <form id="bulk_action_form" action="{{ route('contacts.bulk-action') }}" method="POST">
                        <div class="table-responsive h-350" id="data-list">
                            <table class="display data_tbl data__table contacts_table">
                                <thead>
                                    <tr>
                                        <th class="text-start">
                                            <div>
                                                <input type="checkbox" id="is_check_all">
                                            </div>
                                        </th>
                                        <th class="text-start">@lang('menu.action')</th>
                                        <th class="text-start">@lang('menu.name')</th>
                                        <th>{{ __('phone') }}</th>
                                        <th>{{ __('email') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        <th>{{ __('Contact Type') }}</th>
                                        <th>{{ __('Status') }}</th>
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

    <!-- Edit Modal -->
    <div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true"></div>

    <div class="modal fade" id="view_modal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true"></div>

    <!-- Add Modal -->
    <div class="modal fade" id="add_contact_basic_modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="true"
        aria-labelledby="staticBackdrop"></div>
    <div class="modal fade" id="add_contact_detailed_modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="true"
        aria-labelledby="staticBackdrop"></div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        document.onkeyup = function() {
            var e = e || window.event; // for IE to cover IEs window event-object
            if (e.ctrlKey && e.which == 13) {
                $('#addModal').modal('show');
                setTimeout(function() {

                    $('#name').focus();
                }, 500);
                //return false;
            }
        }

        function refresh() {
            $.get("{{ route('contacts.total_status') }}", function(data) {
                $('#active_contact').text(totalActive);
                $('#inactive_contact').text(totalInActive);
                $('#total_customer').text(totalCustomers);
                $('#total_lead').text(totalLeads);
                $('#total_supplier').text(totalSuppliers);
                $('#total_contact').text(totalContacts);

            });
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#add_contact').on('click', function(e) {
            e.preventDefault();
            $.get("{{ route('contacts.create.basic.modal') }}", function(data) {
                $('#add_contact_basic_modal').html(data);
                $('#add_contact_basic_modal').modal('show');

            });
        });

        $(document).ready(function() {
            var allRow = '';
            var trashedRow = '';
            var table = $('.contacts_table').DataTable({
                dom: "lBfrtip",
                buttons: [{
                    extend: 'pdf',
                    text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
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
                    "url": "{{ route('contacts.index') }}",
                    "data": function(data) {
                        data.showTrashed = $('#trashed_item').attr('showtrash');
                        data.filter_action_field = $('#filter_action_field').val();
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
                        ` <div class="me-3">
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
                                <button type="submit" id="filter_button" class="btn btn-sm btn-info"> Apply </button>
                            </div>
                        </div>

                        <div class="form-group row g-2">
                            <div class="col-xl-12 col-md-12" >
                                <select name="filter_action_type" id="filter_action_field" class="form-control submit_able form-select">
                                    <option value="" selected disabled>Filter</option>
                                    <option value="">{{ __('All') }}</option>
                                    <option value="Contacts">{{ __('Contacts') }}</option>
                                    <option value="Leads">{{ __('Leads') }}</option>
                                    <option value="Customers">{{ __('Customers') }}</option>
                                    <option value="Suppliers">{{ __('Suppliers') }}</option
                                </select>
                            </div>
                        </div>
                    `;

                    $("div.dataTables_filter").prepend(toolbar).addClass('d-flex');
                    $("div.dataTables_filter #bulk_action_field").parent();
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
                        name: 'action',
                        data: 'action'
                    }

                    , {
                        name: 'name',
                        data: 'name'
                    }, {
                        name: 'phone',
                        data: 'phone'
                    }, {
                        name: 'email',
                        data: 'email'
                    }, {
                        name: 'Type',
                        data: 'contact_type'
                    }, {
                        name: 'related',
                        data: 'contact_related'
                    }, {
                        name: 'status',
                        data: 'status'
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
                        refresh();
                    },
                    error: function(error) {
                        toastr.error(error.responseJSON.message);
                    }
                });
            });


            $(document).on('change', '#filter_action_field', function(e) {
                e.preventDefault();
                table.ajax.reload();
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
            refresh();
            $(this).attr("showtrash", true);
            $('.check1').prop('checked', false)
            $(this).addClass("font-weight-bold");
            $('.contacts_table').DataTable().draw(false);
            $('#is_check_all').prop('checked', false);
            $('#all_item').removeClass("font-weight-bold");
            $("#delete_option").css('display', 'block');
            $("#restore_option").css('display', 'block');
            $("#move_to_trash").css('display', 'none');
        })

        //all item
        $(document).on('click', '#all_item', function(e) {
            e.preventDefault();
            refresh();
            trashed_item = $('#trashed_item');
            $('#is_check_all').prop('checked', false);
            $('.check1').prop('checked', false);
            trashed_item.attr("showtrash", false);
            $(this).addClass("font-weight-bold");
            $('.contacts_table').DataTable().draw(false);
            $('#trashed_item').removeClass("font-weight-bold")
            $("#delete_option").css('display', 'none');
            $("#restore_option").css('display', 'none');
            $("#move_to_trash").css('display', 'block');
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
                    $('#edit_modal').html(data);
                    $('#edit_modal').modal('show');
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


        $(document).on('click', '.view', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('#view_modal').html(data);
                    $('#view_modal').modal('show');
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
                                    $('.contacts_table').DataTable().draw(false);
                                    $('.data_preloader').hide();
                                    refresh();
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
                                    refresh();
                                    $('.loading_button').hide();
                                    $('.contacts_table').DataTable().draw(false);
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

        $(document).on('click', '.change_status', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            $.confirm({
                'title': 'Confirmation',
                'message': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-danger',
                        'action': function() {
                            $.ajax({
                                url: url,
                                type: 'get',
                                success: function(data) {
                                    toastr.success(data);
                                    $('.contacts_table').DataTable().ajax.reload();
                                    refresh();
                                }
                            });
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
    </script>
@endpush
