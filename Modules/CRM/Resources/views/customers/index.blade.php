@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

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
@section('title', 'CRM - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>Customers</h6>
                </div>
                <div class="d-flex gap-2">
                    <x-table-stat :items="[
                        ['id' => 'total_customer', 'name' => __('Total Customers'), 'value' => $total['customer']],
                        [
                            'id' => 'active_customer',
                            'name' => __('Active Customer'),
                            'value' => $total['active_customer'],
                        ],
                        [
                            'id' => 'inactive_customer',
                            'name' => __('Inactive Customer'),
                            'value' => $total['inactive_customer'],
                        ],
                        ['id' => '', 'name' => __('Debit'), 'value' => '---'],
                        ['id' => '', 'name' => __('Credit'), 'value' => '---'],
                        ['id' => '', 'name' => __('Logged In Today'), 'value' => '---'],
                    ]" />
                    <x-all-buttons>
                        <x-add-button id="add_customer" :text="'New Customer'" />
                        <x-slot name="after">
                            <x-help-button />
                        </x-slot>
                    </x-all-buttons>
                </div>
            </div>

        </div>

        <div class="p-15">
            <div class="card m-0">
                <div class="card-body">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner"></i> @lang('menu.processing')</h6>
                    </div>
                    <div class="table-responsive h-350" id="data-list">
                        <table class="display data_tbl data__table customerTable">
                            <thead>
                                <tr>
                                    <th class="text-start">@lang('menu.action')</th>
                                    <th class="text-start">@lang('menu.customer_id')</th>
                                    <th class="text-start">@lang('menu.name')</th>
                                    <th class="text-start">@lang('menu.phone')</th>
                                    <th class="text-start">@lang('menu.group')</th>
                                    <th class="text-start">@lang('menu.customer_type')</th>
                                    <th class="text-start">@lang('menu.credit_limit')</th>
                                    <th class="text-start">@lang('menu.opening_balance')</th>
                                    <th class="text-start">@lang('menu.debit')</th>
                                    <th class="text-start">@lang('menu.credit')</th>
                                    <th class="text-start">@lang('menu.closing_balance')</th>
                                    <th class="text-start">@lang('menu.status')</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="add_customer_basic_modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="true"
        aria-labelledby="staticBackdrop"></div>
    <div class="modal fade" id="add_customer_detailed_modal" tabindex="-1" data-bs-backdrop="static"
        data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>

    <!-- Edit Modal -->
    {{-- <div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document" id="edit-content"></div>
    </div> --}}

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true"></div>

    <form id="deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function() {

            // Data Table

            var table = $('.customerTable').DataTable({
                processing: true,
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
                }, ],
                serverSide: true,
                "pageLength": parseInt(
                    "{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
                "lengthMenu": [
                    [10, 25, 50, 100, 500, 1000, -1],
                    [10, 25, 50, 100, 500, 1000, "All"]
                ],
                ajax: "{{ route('crm.customers.index') }}",
                columns: [{
                    data: 'action',
                    name: 'action'
                }, {
                    data: 'contact_id',
                    name: 'contact_id'
                }, {
                    data: 'name',
                    name: 'name'
                }, {
                    data: 'phone',
                    name: 'phone'
                }, {
                    data: 'customer_group_id',
                    name: 'customer_group_id'
                }, {
                    data: 'customer_type',
                    name: 'customer_type'
                }, {
                    data: 'credit_limit',
                    name: 'credit_limit'
                }, {
                    data: 'opening_balance',
                    name: 'opening_balance'
                }, {
                    data: 'debit',
                    name: 'debit'
                }, {
                    data: 'credit',
                    name: 'credit'
                }, {
                    data: 'closing_balance',
                    name: 'closing_balance'
                }, {
                    data: 'status',
                    name: 'status'
                }, ],
            });

            table.buttons().container().appendTo('#exportButtonsContainer');

            // Show and hide input field
            $(document).on('change', '#customer_type', function() {
                if ($(this).val() == 2) {

                    $('.hidable').slideToggle("slow").removeClass('d-none');
                    $('#credit_limit').addClass('add_input');
                } else {

                    $('.hidable').slideToggle("slow").addClass('d-none');
                    $('#credit_limit').removeClass('add_input');
                }
            });

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

            $('#add_customer').on('click', function(e) {
                e.preventDefault();
                $.get("{{ route('crm.customer.create.basic.modal') }}", function(data) {

                    $('#add_customer_basic_modal').html(data);
                    $('#add_customer_basic_modal').modal('show');


                    $('#editModal').empty();
                });
            });


            $(document).on('click', '.change_status', function(e) {
                e.preventDefault();
                var url = $(this).data('url');
                $.confirm({
                    'title': 'Changes Status Confirmation',
                    'message': 'Are you sure?',
                    'buttons': {
                        'Yes': {
                            'class': 'yes btn-danger',
                            'action': function() {
                                $.ajax({
                                    url: url,
                                    type: 'get',
                                    success: function(data) {

                                        if (!$.isEmptyObject(data.errorMsg)) {

                                            toastr.error(data.errorMsg);
                                            return;
                                        }

                                        toastr.success(data);
                                        table.ajax.reload();
                                        refresh();
                                    },
                                    error: function(err) {

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

            // Pass editable data to edit modal fields
            $(document).on('click', '#edit', function(e) {
                e.preventDefault();
                $('.data_preloader').show();
                var url = $(this).attr('href');

                $.get(url, function(data) {

                    $('#editModal').html(data);
                    $('#editModal').modal('show');
                    $('.data_preloader').hide();

                });
            });

        });
    </script>
@endpush
