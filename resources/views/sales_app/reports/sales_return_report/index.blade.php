@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    
@endpush
@section('title', 'Sales Return Report - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.sales_return_report')</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="after">
                        <button id="print_report" class="pdf btn text-white btn-sm px-1"><span><i
                                    class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')</span></button>
                        <a href="#" class="btn text-white btn-sm d-lg-block d-none"><span><span
                                    class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</span></a>
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            <div class="row">
                <div class="col-md-12">
                    <div class="form_element rounded">
                        <div class="element-body">
                            <form id="filter_form">
                                <div class="form-group row align-items-end g-2">
                                    <div class="col-xl-2 col-md-3">
                                        <label><strong>@lang('menu.customer') </strong></label>
                                        <select name="customer_account_id" class="form-control select2 form-select"
                                            id="customer_account_id" autofocus>
                                            <option value="">@lang('menu.all')</option>
                                            @foreach ($customerAccounts as $customerAccount)
                                                <option value="{{ $customerAccount->id }}">
                                                    {{ $customerAccount->name . '/' . $customerAccount->phone }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-2 col-md-3">
                                        <label><strong>@lang('menu.from_date') </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i
                                                        class="fas fa-calendar-week input_f"></i></span>
                                            </div>
                                            <input type="text" name="from_date" id="from_date" class="form-control"
                                                autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-3">
                                        <label><strong>@lang('menu.to_date') </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i
                                                        class="fas fa-calendar-week input_f"></i></span>
                                            </div>
                                            <input type="text" name="to_date" id="to_date" class="form-control"
                                                autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-3">
                                        <button type="submit" class="btn btn-sm btn-info"><i
                                                class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-1">
                <div class="card-body">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                    </div>
                    <div class="table-responsive h-350" id="data-list">
                        {{-- <table class="display data_tbl data__table table-hover"> --}}
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr>
                                    <th>@lang('menu.date')</th>
                                    <th>@lang('menu.voucher_no')</th>
                                    <th>@lang('menu.parent_sale')</th>
                                    <th>@lang('menu.customer')</th>
                                    <th>@lang('menu.user')</th>
                                    <th>@lang('menu.total_item')</th>
                                    <th>@lang('menu.total_qty') (@lang('menu.as_base_unit'))</th>
                                    <th>@lang('short.net_total_amt').</th>
                                    <th>@lang('menu.return_discount')</th>
                                    <th>@lang('menu.return_tax')</th>
                                    <th>@lang('short.total_return_amt').</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr class="bg-secondary">
                                    <th colspan="5" class="text-white">
                                        @lang('menu.total') :
                                        ({{ json_decode($generalSettings->business, true)['currency'] }})
                                    </th>
                                    <th id="total_item" class="text-white"></th>
                                    <th id="total_qty" class="text-white"></th>
                                    <th id="net_total_amount" class="text-white"></th>
                                    <th id="return_discount_amount" class="text-white"></th>
                                    <th id="return_tax_amount" class="text-white"></th>
                                    <th id="total_return_amount" class="text-white"></th>
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

@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $('.select2').select2();

        var return_statements_table = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            dom: "lBfrtip",
            buttons: [{
                    extend: 'pdf',
                    text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                    className: 'pdf btn text-white btn-sm px-1'
                },
                {
                    extend: 'excel',
                    text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                    className: 'pdf btn text-white btn-sm px-1'
                },
            ],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('reports.sale.return.report.index') }}",
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
                    data: 'voucher_no',
                    name: 'sale_returns.voucher_no',
                    className: 'fw-bold'
                },
                {
                    data: 'parent_sale',
                    name: 'sales.invoice_id',
                    className: 'fw-bold'
                },
                {
                    data: 'customer',
                    name: 'customers.name'
                },
                {
                    data: 'createdBy',
                    name: 'createdBy.name'
                },
                {
                    data: 'total_item',
                    name: 'total_item',
                    className: 'fw-bold'
                },
                {
                    data: 'total_qty',
                    name: 'total_qty',
                    className: 'fw-bold'
                },
                {
                    data: 'net_total_amount',
                    name: 'net_total_amount',
                    className: 'fw-bold'
                },
                {
                    data: 'return_discount_amount',
                    name: 'return_discount_amount',
                    className: 'fw-bold'
                },
                {
                    data: 'return_tax_amount',
                    name: 'return_tax_amount',
                    className: 'fw-bold'
                },
                {
                    data: 'total_return_amount',
                    name: 'total_return_amount',
                    className: 'fw-bold'
                },

            ],
            fnDrawCallback: function() {

                var total_item = sum_table_col($('.data_tbl'), 'total_item');
                $('#total_item').text(bdFormat(total_item));

                var total_qty = sum_table_col($('.data_tbl'), 'total_qty');
                $('#total_qty').text(bdFormat(total_qty));

                var net_total_amount = sum_table_col($('.data_tbl'), 'net_total_amount');
                $('#net_total_amount').text(bdFormat(net_total_amount));

                var return_discount_amount = sum_table_col($('.data_tbl'), 'return_discount_amount');
                $('#return_discount_amount').text(bdFormat(return_discount_amount));

                var return_tax_amount = sum_table_col($('.data_tbl'), 'return_tax_amount');
                $('#return_tax_amount').text(bdFormat(return_tax_amount));

                var total_return_amount = sum_table_col($('.data_tbl'), 'total_return_amount');
                $('#total_return_amount').text(bdFormat(total_return_amount));

                $('.data_preloader').hide();
            }
        });

        return_statements_table.buttons().container().appendTo('#exportButtonsContainer');

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
            return_statements_table.ajax.reload();
        });

        //Print purchase Payment report
        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();

            var url = "{{ route('reports.sale.return.report.print') }}";

            var customer_account_id = $('#customer_account_id').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    customer_account_id,
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
