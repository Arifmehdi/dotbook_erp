@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

@endpush
@section('title', 'D/o Report - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <h6>@lang('menu.do_report')</h6>
                <x-all-buttons>
                    <x-slot name="after">
                        <button id="print_report" class="pdf btn text-white btn-sm"><span><i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')</span></button>
                        <a href="#" class="btn text-white btn-sm d-lg-block d-none"><span><span class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</span></a>
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            <div class="row">
                <div class="col-md-12">
                    <div class="form_element rounded m-0 mb-1">
                        <div class="element-body">
                            <form id="filter_form">
                                <div class="form-group row align-items-end g-2">

                                    @if (!auth()->user()->can('view_own_sale'))
                                        <div class="col-xl-2 col-md-4">
                                            <label><strong>@lang('menu.sr') </strong></label>
                                            <select name="user_id" class="form-control select2 form-select" id="user_id" autofocus>
                                                <option data-user_name="All" value="">@lang('menu.all')</option>
                                                @foreach ($users as $user)
                                                    <option data-user_name="{{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}" value="{{ $user->id }}">
                                                        {{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.customer') </strong></label>
                                        <select name="customer_account_id" class="form-control select2 form-select" id="customer_account_id" autofocus>
                                            <option data-customer_name="All" value="">@lang('menu.all')</option>
                                            @foreach ($customerAccounts as $customerAccount)
                                                <option data-customer_name="{{ $customerAccount->name . '/' . $customerAccount->phone }}" value="{{ $customerAccount->id }}">
                                                    {{ $customerAccount->name . '/' . $customerAccount->phone }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.from_date') </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                            </div>
                                            <input type="text" name="from_date" id="from_date" class="form-control" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.to_date') </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                            </div>
                                            <input type="text" name="to_date" id="to_date" class="form-control" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <button type="submit" class="btn btn-sm btn-info"><i class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                            </div>
                            <div class="table-responsive h-350" id="data-list">
                                <table class="display data_tbl table-sm sale_or_order_table">
                                    <thead>
                                        <tr>
                                            <th>@lang('menu.date')</th>
                                            <th>@lang('menu.do_id')</th>
                                            <th>@lang('menu.customer')</th>
                                            <th>@lang('menu.sr')</th>
                                            <th>@lang('menu.do_qty') (@lang('menu.as_base_unit'))</th>
                                            <th>@lang('short.delivered_qty') (@lang('menu.as_base_unit'))</th>
                                            <th>@lang('short.left_qty') (@lang('menu.as_base_unit'))</th>
                                            <th>@lang('short.net_total_amt').</th>
                                            <th>@lang('menu.shipment_charge')</th>
                                            <th>@lang('menu.discount')</th>
                                            <th>@lang('menu.rate_type')</th>
                                            <th>@lang('menu.total_ordered_amount')</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr class="bg-secondary">
                                            <th colspan="4" class="text-white text-end">@lang('menu.total') :
                                                ({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                            <th id="total_do_qty" class="text-white"></th>
                                            <th id="total_delivered_qty" class="text-white"></th>
                                            <th id="do_total_left_qty" class="text-white"></th>
                                            <th id="net_total_amount" class="text-white"></th>
                                            <th id="order_discount_amount" class="text-white"></th>
                                            <th id="order_tax_amount" class="text-white"></th>
                                            <th id="shipment_charge" class="text-white"></th>
                                            <th id="total_payable_amount" class="text-white"></th>
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
        </div>
    </div>

@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $('.select2').select2();

        var do_table = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            dom: "lBfrtip",
            buttons: [{
                    extend: 'pdf',
                    text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                    }
                },
                {
                    extend: 'excel',
                    text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                    }
                },
            ],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('reports.do.report.index') }}",
                "data": function(d) {
                    d.customer_account_id = $('#customer_account_id').val();
                    d.user_id = $('#user_id').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },

            columns: [{
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'do_id',
                    name: 'do_id',
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
                    data: 'total_do_qty',
                    name: 'sr.prefix',
                    className: 'fw-bold'
                },
                {
                    data: 'total_delivered_qty',
                    name: 'sr.last_name',
                    className: 'fw-bold'
                },
                {
                    data: 'do_total_left_qty',
                    name: 'do_total_left_qty',
                    className: 'fw-bold'
                },
                {
                    data: 'net_total_amount',
                    name: 'net_total_amount',
                    className: 'fw-bold'
                },
                {
                    data: 'shipment_charge',
                    name: 'shipment_charge',
                    className: 'fw-bold'
                },
                {
                    data: 'order_discount_amount',
                    name: 'order_discount_amount',
                    className: 'fw-bold'
                },
                {
                    data: 'all_price_type',
                    name: 'sales.all_price_type',
                    className: 'fw-bold'
                },
                {
                    data: 'total_payable_amount',
                    name: 'total_payable_amount',
                    className: 'fw-bold'
                },
            ],
            fnDrawCallback: function() {

                var total_do_qty = sum_table_col($('.data_tbl'), 'total_do_qty');
                $('#total_do_qty').text(bdFormat(total_do_qty));

                var total_delivered_qty = sum_table_col($('.data_tbl'), 'total_delivered_qty');
                $('#total_delivered_qty').text(bdFormat(total_delivered_qty));

                var do_total_left_qty = sum_table_col($('.data_tbl'), 'do_total_left_qty');
                $('#do_total_left_qty').text(bdFormat(do_total_left_qty));

                var net_total_amount = sum_table_col($('.data_tbl'), 'net_total_amount');
                $('#net_total_amount').text(bdFormat(net_total_amount));

                var shipment_charge = sum_table_col($('.data_tbl'), 'shipment_charge');
                $('#shipment_charge').text(bdFormat(shipment_charge));

                var order_discount_amount = sum_table_col($('.data_tbl'), 'order_discount_amount');
                $('#order_discount_amount').text(bdFormat(order_discount_amount));

                var order_tax_amount = sum_table_col($('.data_tbl'), 'order_tax_amount');
                $('#order_tax_amount').text(bdFormat(order_tax_amount));

                var shipment_charge = sum_table_col($('.data_tbl'), 'shipment_charge');
                $('#shipment_charge').text(bdFormat(shipment_charge));

                var total_payable_amount = sum_table_col($('.data_tbl'), 'total_payable_amount');
                $('#total_payable_amount').text(bdFormat(total_payable_amount));

                $('.data_preloader').hide();
            }
        });

        do_table.buttons().container().appendTo('#exportButtonsContainer');

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
            do_table.ajax.reload();
        });

        //Print purchase Payment report
        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();

            var url = "{{ route('reports.reports.do.report.print') }}";

            var user_id = $('#user_id').val();
            var user_name = $('#user_id').find('option:selected').data('user_name');
            var customer_account_id = $('#customer_account_id').val();
            var customer_name = $('#customer_account_id').find('option:selected').data('customer_name');
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
    </script>
@endpush
