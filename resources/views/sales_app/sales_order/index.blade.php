@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

@endpush
@section('title', 'Sales Orders - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.manage_order')</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="before">
                        @if (auth()->user()->can('sale_order_add'))
                            <a href="{{ route('sales.order.create') }}" id="add_btn" class="btn text-white btn-sm"><span><i class="fa-thin fa-circle-plus fa-2x"></i><br>@lang('menu.new_order')</span></a>
                        @endif
                    </x-slot>
                    <x-slot name="after">
                        <button id="print_statement_report" class="pdf btn text-white btn-sm"><span><i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')</span></button>
                        <button id="print_summary" class="pdf btn text-white btn-sm px-1"><span><i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print_summary')</span></button>
                        <a href="#" class="btn text-white btn-sm d-lg-block d-none"><span><span class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</span></a>
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            <div class="form_element rounded mt-0 mb-1">
                <div class="card-body">
                    <form id="filter_form">
                        <div class="form-group row g-3 g-xl-3 g-xxl-3 align-items-end">
                            <div class="col-xl-2 col-md-6">
                                <label><strong>@lang('menu.customer') </strong></label>
                                <select name="customer_account_id" class="form-control select2 form-select" id="customer_account_id" autofocus>
                                    <option data-customer_name="All" value="">@lang('menu.all')</option>
                                    @foreach ($customerAccounts as $customer)
                                        <option data-customer_name="{{ $customer->name . ' (' . $customer->phone . ')' }}" value="{{ $customer->id }}">
                                            {{ $customer->name . ' (' . $customer->phone . ')' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @if (!auth()->user()->can('view_own_sale'))
                                <div class="col-xl-2 col-md-6">
                                    <label><strong>@lang('menu.sr') </strong></label>
                                    <select name="user_id" class="form-control select2 form-select" id="user_id" autofocus>
                                        <option data-user_name="@lang('menu.all')" value="">@lang('menu.all')
                                        </option>
                                        @foreach ($users as $user)
                                            <option data-user_name="{{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}" value="{{ $user->id }}">
                                                {{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="col-xl-2 col-md-4">
                                <label><strong>@lang('menu.sales_ledger_ac') </strong></label>
                                <select name="sale_account_id" class="form-control select2 form-select" id="sale_account_id" autofocus>
                                    <option data-sale_account_name="All" value="">@lang('menu.all')</option>
                                    @foreach ($saleAccounts as $saleAccount)
                                        <option data-sale_account_name="{{ $saleAccount->name }}" value="{{ $saleAccount->id }}">{{ $saleAccount->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xl-2 col-md-6">
                                <label><strong>@lang('menu.from_date') </strong></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                    </div>
                                    <input type="text" name="from_date" id="from_date" class="form-control" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-xl-2 col-md-6">
                                <label><strong>@lang('menu.to_date') </strong></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">
                                            <i class="fas fa-calendar-week input_f"></i>
                                        </span>
                                    </div>

                                    <input type="text" name="to_date" id="to_date" class="form-control" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-xl-1 col-md-6">
                                <button type="submit" class="btn btn-sm  btn-info"><i class="fa-solid fa-filter-list"></i>
                                    Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-0">
                <div class="card-body">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                    </div>
                    <div class="table-responsive h-350" id="data-list">
                        <table class="display data_tbl table-sm sale_or_order_table">
                            <thead>
                                <tr>
                                    <th>@lang('menu.actions')</th>
                                    <th>@lang('menu.date')</th>
                                    <th>@lang('menu.order_id')</th>
                                    <th>@lang('menu.customer')</th>
                                    <th>@lang('menu.sr')</th>
                                    <th>@lang('menu.curr_status')</th>
                                    <th>@lang('menu.delivery_status')</th>
                                    <th>@lang('menu.do_approval')</th>
                                    <th>@lang('menu.ordered_qty') (@lang('menu.as_base_unit'))</th>
                                    <th>@lang('menu.delivered_qty') (@lang('menu.as_base_unit'))</th>
                                    <th>@lang('menu.left_qty') (@lang('menu.as_base_unit'))</th>
                                    <th>@lang('menu.total_amount')</th>
                                    <th>@lang('menu.received_amount')</th>
                                    <th>@lang('menu.receipt_details')</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr class="bg-secondary">
                                    <th colspan="8" class="text-white">@lang('menu.total') :
                                        ({{ json_decode($generalSettings->business, true)['currency'] }})
                                    </th>
                                    <th id="total_ordered_qty" class="text-white"></th>
                                    <th id="total_delivered_qty" class="text-white"></th>
                                    <th id="do_total_left_qty" class="text-white"></th>
                                    <th class="text-white" style="line-height:14px!important;">
                                        <span id="total_payable_amount"></span><br>
                                        <span id="average_unit_price"></span>
                                    </th>
                                    <th id="paid" class="text-white"></th>
                                    <th class="text-white">---</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <form id="deleted_form" action="" method="post">
                    @method('DELETE')
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <div id="details"></div>
    <div id="extra_details"></div>

    <!-- Do approval modal -->
    <div class="modal fade" id="doApprovalModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content" id="do_approval_modal_content">

            </div>
        </div>
    </div>

    <!-- Change order status modal -->
    <div class="modal fade" id="changeOrderStatusModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content" id="change_order_status_modal_content"></div>
        </div>
    </div>
    <!-- Change order status modal end-->

    @if (auth()->user()->can('receipts_add'))
        <!--Add receipt modal-->
        <div class="modal fade" id="saleReceiptModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
        <!--Add receipt modal end-->
    @endif
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        // Show session message by toster alert.
        @if (Session::has('successMsg'))
            toastr.success('{{ session('successMsg') }}');
        @endif

        $('.select2').select2();

        var orders_table = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            dom: "lBfrtip",
            buttons: [{
                    extend: 'pdf',
                    text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
                    }
                },
                {
                    extend: 'excel',
                    text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
                    }
                },
                // {extend: 'print',text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',className: 'pdf btn text-white btn-sm px-1',exportOptions: {columns: [1,2,3,4,5,6,7,8,9,10,11,12,13]}},
            ],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('sales.order.index') }}",
                "data": function(d) {
                    d.customer_account_id = $('#customer_account_id').val();
                    d.sale_account_id = $('#sale_account_id').val();
                    d.user_id = $('#user_id').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },

            columns: [{
                    data: 'action'
                },
                {
                    data: 'order_date',
                    name: 'order_date'
                },
                {
                    data: 'order_id',
                    name: 'sales.order_id',
                    className: 'fw-bold'
                },
                {
                    data: 'customer',
                    name: 'customers.name'
                },
                {
                    data: 'sr',
                    name: 'sr.name'
                },
                {
                    data: 'current_status',
                    name: 'sales.order_id'
                },
                {
                    data: 'delivery_status',
                    name: 'sales.order_id'
                },
                {
                    data: 'do_approval',
                    name: 'do_approval'
                },
                {
                    data: 'total_ordered_qty',
                    name: 'total_ordered_qty',
                    className: 'fw-bold'
                },
                {
                    data: 'total_delivered_qty',
                    name: 'sr.prefix',
                    className: 'fw-bold'
                },
                {
                    data: 'do_total_left_qty',
                    name: 'sr.last_name',
                    className: 'fw-bold'
                },
                {
                    data: 'total_payable_amount',
                    name: 'sales.quotation_id',
                    className: 'fw-bold'
                },
                {
                    data: 'paid',
                    name: 'sales.order_id',
                    className: 'fw-bold'
                },
                {
                    data: 'receipt_details',
                    name: 'sales.payment_note'
                },
            ],
            fnDrawCallback: function() {

                var total_ordered_qty = sum_table_col($('.data_tbl'), 'total_ordered_qty');
                $('#total_ordered_qty').text(bdFormat(total_ordered_qty));

                var total_delivered_qty = sum_table_col($('.data_tbl'), 'total_delivered_qty');
                $('#total_delivered_qty').text(bdFormat(total_delivered_qty));

                var do_total_left_qty = sum_table_col($('.data_tbl'), 'do_total_left_qty');
                $('#do_total_left_qty').text(bdFormat(do_total_left_qty));

                var total_payable_amount = sum_table_col($('.data_tbl'), 'total_payable_amount');
                $('#total_payable_amount').text(bdFormat(total_payable_amount));

                var averageUnitPrice = total_payable_amount / total_ordered_qty;
                var totalPayableAmountAmtWithAvgUnitPrice = '(Avg.U/p:' + bdFormat(averageUnitPrice) + ')';
                $('#average_unit_price').text(totalPayableAmountAmtWithAvgUnitPrice);

                var paid = sum_table_col($('.data_tbl'), 'paid');
                $('#paid').text(bdFormat(paid));
                $('.data_preloader').hide();
            }
        });

        orders_table.buttons().container().appendTo('#exportButtonsContainer');

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

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            orders_table.ajax.reload();
        });

        // Show details modal with data
        $(document).on('click', '#do_approval', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.get(url, function(data) {

                $('#do_approval_modal_content').html(data);
                $('.data_preloader').hide();
                $('#doApprovalModal').modal('show');
            });
        });

        // Show change status modal with data
        $(document).on('click', '#change_order_status', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.get(url, function(data) {

                $('#change_order_status_modal_content').html(data);
                $('.data_preloader').hide();
                $('#changeOrderStatusModal').modal('show');
            });
        });

        $(document).on('click', '#details_btn', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#details').html(data);
                    $('.data_preloader').hide();
                    $('#detailsModal').modal('show');
                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server error. Please contact to the support team.');
                        return;
                    }
                }
            });
        });

        // Make print
        $(document).on('click', '#print_modal_details_btn', function(e) {
            e.preventDefault();

            var body = $('.print_details').html();
            var header = $('.heading_area').html();

            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('css/print/sale.print.css') }}",
                removeInline: false,
                printDelay: 500,
                header: null,
                footer: null,
            });
        });

        $(document).on('click', '#add_sale_receipt', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#saleReceiptModal').html(data);
                    $('#saleReceiptModal').modal('show');
                    $('.data_preloader').hide();

                    setTimeout(function() {

                        $('input[name="received_amount"]').focus().select();
                    }, 500);
                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server error. Please contact to the support team.');
                        return;
                    }
                }
            });
        });

        $(document).on('click', '#delete', function(e) {
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
                        'action': function() {}
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
                data: request,
                success: function(data) {
                    orders_table.ajax.reload(null, false);
                    toastr.error(data);
                    countSalesOrdersQuotationDo();
                }
            });
        });

        $(document).on('click', '#print_statement_report', function(e) {
            e.preventDefault();

            var url = "{{ route('reports.sales.order.report.print') }}";

            var user_id = $('#user_id').val();
            var user_name = $('#user_id').find('option:selected').data('user_name');
            var customer_account_id = $('#customer_account_id').val();
            var customer_name = $('#customer_account_id').find('option:selected').data('customer_name');
            var sale_account_id = $('#sale_account_id').val();
            var sale_account_name = $('#sale_account_id').find('option:selected').data('sale_account_name');
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    user_id,
                    user_name,
                    customer_account_id,
                    customer_name,
                    sale_account_id,
                    sale_account_name,
                    from_date,
                    to_date
                },
                success: function(data) {

                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 1000,
                    });
                }
            });
        });

        $(document).on('click', '#print_summary', function(e) {
            e.preventDefault();

            var url = "{{ route('reports.sales.order.report.print.summary') }}";

            var customer_account_id = $('#customer_account_id').val();
            var customer_name = $('#customer_account_id').find('option:selected').data('customer_name');
            var sale_account_id = $('#sale_account_id').val();
            var sale_account_name = $('#sale_account_id').find('option:selected').data('sale_account_name');
            var user_id = $('#user_id').val();
            var user_name = $('#user_id').find('option:selected').data('user_name');
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    customer_account_id,
                    customer_name,
                    sale_account_id,
                    sale_account_name,
                    user_id,
                    user_name,
                    from_date,
                    to_date
                },
                success: function(data) {

                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 700,
                        header: "",
                        pageTitle: "",
                    });
                }
            });
        });
    </script>

    <script type="text/javascript">
        new Litepicker({
            singleMode: true,
            element: document.getElementById('from_date'),
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
            element: document.getElementById('to_date'),
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
            format: 'DD-MM-YYYY',
        });

        document.onkeyup = function() {
            var e = e || window.event; // for IE to cover IEs window event-object
            if (e.ctrlKey && e.which == 13) {

                // $('#add_btn').click();
                window.location = $('#add_btn').attr('href');
                return false;
            }
        }
    </script>
@endpush
