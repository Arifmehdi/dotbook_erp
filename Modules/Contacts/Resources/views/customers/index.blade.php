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
@section('title', 'Customer List - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.customers')</h6>
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
                    ]" />
                    <x-all-buttons>
                        <x-slot name="before">
                            @if (auth()->user()->can('customer_add'))
                                <a href="#" class="btn text-white btn-sm" id="add_customer">
                                    <span><i class="fa-thin fa-circle-plus fa-2x"></i><br>@lang('menu.new_customer')</span>
                                </a>
                            @endif

                            @if (auth()->user()->can('customer_import'))
                                <a href="{{ route('contacts.customers.import.create') }}" class="btn text-white btn-sm">
                                    <span><i class="fa-thin fa-file-arrow-down fa-2x"></i><br>@lang('menu.import_customers')</span>
                                </a>
                            @endif
                        </x-slot>
                        <x-slot name="after">
                            @if (auth()->user()->can('customer_report'))
                                <button class="btn text-white btn-sm px-1 print_report" id="print_report">
                                    <span><i class="fa-thin fa-print fa-2x"></i><br> @lang('menu.print')</span>
                                </button>
                            @endif
                            <a href="#" class="btn text-white btn-sm d-lg-block d-none"><span><span
                                        class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</span></a>
                        </x-slot>
                    </x-all-buttons>
                </div>
            </div>
        </div>

        <div class="p-15">
            <div class="card">
                @if (auth()->user()->is_marketing_user == 0)
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="filter_form" class="px-2">
                                    <div class="row align-items-end g-2">

                                        <div class="col-xl-4 col-md-4">
                                            <div class="input-group align-items-center">
                                                <label class="col-1"><b>{{ __("Sr.") }}</b></label>
                                                <div class="col-11">
                                                    <select name="user_id" class="form-control select2" id="user_id"
                                                        autofocus>
                                                        <option data-user_name="All" value="">@lang('menu.all')
                                                        </option>
                                                        @foreach ($users as $user)
                                                            <option
                                                                data-user_name="{{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}"
                                                                value="{{ $user->id }}">
                                                                {{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-2">
                                            <button type="submit" class="btn btn-sm btn-info"><i class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="card-body">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner"></i> @lang('menu.processing')</h6>
                    </div>
                    <div class="table-responsive h-350" id="data-list">
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr>
                                    <th>@lang('menu.action')</th>
                                    <th>@lang('menu.customer_id')</th>
                                    <th>@lang('menu.name')</th>
                                    <th>@lang('menu.phone')</th>
                                    <th>@lang('menu.group')</th>
                                    <th>@lang('menu.customer_type')</th>
                                    <th>@lang('menu.credit_limit')</th>
                                    <th>@lang('menu.opening_balance')</th>
                                    <th>@lang('menu.debit')</th>
                                    <th>@lang('menu.credit')</th>
                                    <th>@lang('menu.closing_balance')</th>
                                    <th>@lang('menu.status')</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <form id="deleted_form" action="" method="POST">
                    @method('DELETE')
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    </div>

    <!-- Money Receipt list Modal-->
    <div class="modal fade" id="moneyReceiptListModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog col-60-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.payment_receipt_voucher')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="receipt_voucher_list_modal_body"></div>
            </div>
        </div>
    </div>
    <!-- Money Receipt list Modal End-->

    <!--add money receipt Modal-->
    <div class="modal fade" id="MoneyReciptModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog col-60-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.generate_money_receipt')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="money_receipt_modal"></div>
            </div>
        </div>
    </div>
    <!--add money receipt Modal End-->

    <!--add money receipt Modal-->
    <div class="modal fade" id="changeReciptStatusModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
    </div>
    <!--add money receipt Modal End-->

    <div class="modal fade" id="add_customer_basic_modal" data-bs-backdrop="static" data-bs-keyboard="true"
        aria-labelledby="staticBackdrop"></div>
    <div class="modal fade" id="add_customer_detailed_modal" data-bs-backdrop="static" data-bs-keyboard="true"
        aria-labelledby="staticBackdrop"></div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $('.select2').select2();

        var table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [{
                extend: 'pdf',
                text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                className: 'btn text-white btn-sm',
                exportOptions: {
                    columns: [1, 2, 3, 4, 6, 7, 8, 9, 10]
                }
            }, {
                extend: 'excel',
                text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                className: 'btn text-white btn-sm',
                exportOptions: {
                    columns: [1, 2, 3, 4, 6, 7, 8, 9, 10]
                }
            }, ],
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('contacts.customers.index') }}",
                "data": function(d) {
                    d.user_id = $('#user_id').val();
                }
            },
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],

            columns: [{
                data: 'action',
                name: 'action'
            }, {
                data: 'contact_id',
                name: 'contact_id',
                orderable: true,
            }, {
                data: 'name',
                name: 'name'
            }, {
                data: 'phone',
                name: 'phone'
            }, {
                data: 'group_name',
                name: 'customer_groups.group_name'
            }, {
                data: 'customer_type',
                name: 'customers.customer_type'
            }, {
                data: 'credit_limit',
                name: 'customers.credit_limit'
            }, {
                data: 'opening_balance',
                name: 'contact_id',
                className: 'fw-bold'
            }, {
                data: 'debit',
                name: 'contact_id',
                className: 'fw-bold'
            }, {
                data: 'credit',
                name: 'contact_id',
                className: 'fw-bold'
            }, {
                data: 'closing_balance',
                name: 'contact_id',
                className: 'fw-bold'
            }, {
                data: 'status',
                name: 'status'
            }, ],
            fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
        });

        table.buttons().container().appendTo('#exportButtonsContainer');

        function sum_table_col(table, class_name) {
            var sum = 0;

            table.find('tbody').find('tr').each(function() {

                if (parseFloat($(this).find('.' + class_name).data('value'))) {

                    sum += parseFloat(
                        $(this).find('.' + class_name).data('value')
                    );
                }
            });
            return sum;
        }

        function refresh() {

            $.get("{{ route('customers.change.status') }}", function(data) {
                $('#total_customer').text(data.customer);
                $('#active_customer').text(data.active_customer);
                $('#inactive_customer').text(data.inactive_customer);
            });
        }
        refresh();

        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            table.ajax.reload();
        });

        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // call jquery method
        $(document).ready(function() {

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
                    type: 'POST',
                    async: false,
                    data: request,
                    success: function(data) {

                        if (!$.isEmptyObject(data.errorMsg)) {

                            toastr.error(data.errorMsg, 'Attention');
                            return;
                        }

                        table.ajax.reload(null, false);
                        refresh();
                        toastr.error(data);
                        $('#deleted_form')[0].reset();
                    }
                });
            });

            // Show sweet alert for delete
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
                                    type: 'GET',
                                    success: function(data) {

                                        if (!$.isEmptyObject(data.errorMsg)) {

                                            toastr.error(data.errorMsg);
                                            return;
                                        }

                                        toastr.success(data);
                                        table.ajax.reload(null, false);
                                        refresh();
                                    },
                                    error: function(err) {}
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

            $(document).on('click', '#generate_receipt', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {

                        $('#money_receipt_modal').html(data);
                        $('#MoneyReciptModal').modal('show');
                    }
                });
            });

            $(document).on('click', '#money_receipt_list', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $('.data_preloader').show();
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {

                        $('#receipt_voucher_list_modal_body').html(data);
                        $('#moneyReceiptListModal').modal('show');
                        $('.data_preloader').hide();
                    }
                });
            });

            $(document).on('submit', '#money_receipt_form', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                var request = $(this).serialize();

                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {

                        toastr.success('Successfully money receipt voucher is generated.');
                        $('#MoneyReciptModal').modal('hide');
                        $('#moneyReceiptListModal').modal('hide');
                        $('.loading_button').hide();

                        $(data).printThis({
                            debug: false,
                            importCSS: true,
                            importStyle: true,
                            loadCSS: "{{ asset('css/print/sale.print.css') }}",
                            removeInline: false,
                            printDelay: 1000,
                            header: null,
                        });
                    }
                });
            });

            // Pass editable data to edit modal fields
            $(document).on('click', '#edit_receipt', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $.get(url, function(data) {

                    $('#money_receipt_modal').html(data);
                    $('#MoneyReciptModal').modal('show');
                });
            });

            $(document).on('click', '#print_receipt', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'get',
                    dataType: 'html',
                    success: function(data) {

                        $(data).printThis({
                            debug: false,
                            importCSS: true,
                            importStyle: true,
                            loadCSS: "{{ asset('css/print/sale.print.css') }}",
                            removeInline: false,
                            printDelay: 1000,
                            header: null,
                        });
                        $('.print_area').remove();
                        return;
                    }
                });
            });

            // Show sweet alert for delete
            $(document).on('click', '#change_receipt_status', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $('.receipt_preloader').show();

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {

                        $('#changeReciptStatusModal').html(data);
                        $('#changeReciptStatusModal').modal('show');
                        $('.receipt_preloader').hide();
                    }
                });
            });

            $(document).on('submit', '#change_voucher_status_form', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                var inputs = $('.vcs_input');
                $('.error').html('');

                var countErrorField = 0;

                $.each(inputs, function(key, val) {

                    var inputId = $(val).attr('id');
                    var idValue = $('#' + inputId).val();

                    if (idValue == '') {

                        countErrorField += 1;
                        var fieldName = $('#' + inputId).data('name');
                        $('.error_vcs_' + inputId).html(fieldName + ' is required.');
                    }
                });

                if (countErrorField > 0) {

                    $('.loading_button').hide();
                    return;
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: request,
                    success: function(data) {

                        toastr.success(data);
                        $('#changeReciptStatusModal').modal('hide');
                        $('#moneyReceiptListModal').modal('hide');
                        table.ajax.reload(null, false);
                        refresh();
                    }
                });
            });

            $(document).on('click', '#delete_receipt', function(e) {
                e.preventDefault();

                var url = $(this).attr('href');
                var tr = $(this).closest('tr');

                $('#receipt_deleted_form').attr('action', url);

                $.confirm({
                    'title': 'Delete Confirmation',
                    'content': 'Are you sure?',
                    'buttons': {

                        'Yes': {
                            'class': 'yes btn-danger',
                            'action': function() {
                                $('#receipt_deleted_form').submit();
                                tr.remove();
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
            $(document).on('submit', '#receipt_deleted_form', function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var request = $(this).serialize();

                $.ajax({
                    url: url,
                    type: 'POST',
                    async: false,
                    data: request,
                    success: function(data) {

                        toastr.error(data);
                        $('#receipt_deleted_form')[0].reset();
                    }
                });
            });

            $(document).on('change', '#is_header_less', function() {

                if ($(this).is(':CHECKED', true)) {

                    $('.gap-from-top-add').show();
                } else {

                    $('.gap-from-top-add').hide();
                }
            });
        });

        //Print supplier report
        $(document).on('click', '.print_report', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = "{{ route('reports.customer.print') }}";
            var customer_id = $('#customer_id').val();

            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    customer_id
                },
                success: function(data) {

                    $('.data_preloader').hide();
                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 1000,
                        header: null,
                    });
                }
            });
        });

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

        $('#add_customer').on('click', function(e) {
            e.preventDefault();
            $.get("{{ route('contacts.customers.create.basic.modal') }}", function(data) {
                $('#add_customer_basic_modal').html(data);
                $('#add_customer_basic_modal').modal('show');


                $('#editModal').empty();
            });
        });
    </script>
@endpush
