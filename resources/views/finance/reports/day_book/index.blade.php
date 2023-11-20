@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

@endpush
@section('title', 'Day Book')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name  d-flex justify-contents-between align-items-center">
                <div class="name-head">
                    <h6>@lang('menu.day_book')</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="after">
                        <a href="#" id="print_report" class="btn text-white btn-sm m-0"><span><i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')</span></a>
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            <div class="row g-1 mb-1">
                <div class="col-md-12">
                    <div class="form_element rounded m-0 h-100">
                        <div class="element-body">
                            <form id="filter_daybook" method="get">
                                <div class="form-group row align-items-end g-2">
                                    <div class="col-md-2">
                                        <label><strong>{{ __("Voucher Type") }}</strong></label>
                                        <select name="voucher_type" class="form-control select2" id="voucher_type">
                                            <option value="0">{{ __("All") }}</option>
                                            @foreach (\App\Utils\DayBookUtil::voucherTypes() as $key => $voucherType)
                                                <option value="{{ $key }}">{{ $voucherType }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>@lang('menu.from_date')</strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                            </div>
                                            <input type="text" name="from_date" id="from_date" class="form-control" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>@lang('menu.to_date')</strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                            </div>
                                            <input type="text" name="to_date" id="to_date" class="form-control" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>@lang('menu.note/remarks')</strong></label>
                                        <select name="note" class="form-control form-select" id="note">
                                            <option value="0">@lang('menu.no')</option>
                                            <option selected value="1">@lang('menu.yes')</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>@lang('menu.voucher_details')</strong></label>
                                        <select name="voucher_details" class="form-control form-select" id="voucher_details">
                                            <option value="0">@lang('menu.no')</option>
                                            <option value="1">@lang('menu.yes')</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>@lang('menu.transaction_details')</strong></label>
                                        <select name="transaction_details" class="form-control form-select" id="transaction_details">
                                            <option value="0">@lang('menu.no')</option>
                                            <option value="1">@lang('menu.yes')</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>@lang('menu.inventory_list')</strong></label>
                                        <select name="inventory_list" class="form-control form-select" id="inventory_list">
                                            <option value="0">@lang('menu.no')</option>
                                            <option value="1">@lang('menu.yes')</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-sm text-white btn-info"><i class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
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
                        <div class="card-body pb-2">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                            </div>
                            <div class="table-responsive h-350" id="data-list">
                                <table class="display data_tbl data__table">
                                    <thead>
                                        <tr>
                                            <th class="text-startx">@lang('menu.date')</th>
                                            <th class="text-startx">@lang('menu.particulars')</th>
                                            <th class="text-startx">@lang('menu.voucher_type')</th>
                                            <th class="text-startx">@lang('menu.voucher_no')</th>
                                            <th class="text-startx">
                                                <p class="p-0 m-0">@lang('menu.debit_amount')</p>
                                                <hr class="p-0 m-0">
                                                <p class="p-0 m-0" style="font-size:11px;">@lang('menu.inward_quantity')</p>
                                            </th>
                                            <th class="text-startx">
                                                <p class="p-0 m-0">@lang('menu.credit_amount')</p>
                                                <hr class="p-0 m-0">
                                                <p class="p-0 m-0" style="font-size:11px;">@lang('menu.outward_quantity')</p>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="details"></div>

@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $('.select2').select2();

        var daybook_table = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            // "searching" : false,
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
            "lengthMenu": [
                [50, 100, 500, 1000, -1],
                [50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('reports.daybook.index') }}",
                "data": function(d) {
                    d.voucher_type = $('#voucher_type').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    d.note = $('#note').val();
                    d.transaction_details = $('#transaction_details').val();
                    d.voucher_details = $('#voucher_details').val();
                    d.inventory_list = $('#inventory_list').val();
                }
            },
            columns: [{
                    data: 'date',
                    name: 'sales.invoice_id'
                },
                {
                    data: 'particulars',
                    name: 'sale_returns.voucher_no'
                },
                {
                    data: 'voucher_type',
                    name: 'purchases.invoice_id'
                },
                {
                    data: 'voucher_no',
                    name: 'journal.voucher_no'
                },
                {
                    data: 'debit',
                    name: 'expanses.voucher_no',
                    className: 'fw-bold'
                },
                {
                    data: 'credit',
                    name: 'payments.voucher_no',
                    className: 'fw-bold'
                },
                {
                    data: 'daily_stock_voucher',
                    name: 'dailyStock.voucher_no',
                    searchable: true,
                    visible: false
                },
                {
                    data: 'stock_issue_voucher',
                    name: 'StockIssue.voucher_no',
                    searchable: true,
                    visible: false
                },
                {
                    data: 'sales_order_voucher',
                    name: 'sales.order_id',
                    searchable: true,
                    visible: false
                },
                {
                    data: 'contra_voucher',
                    name: 'contra.voucher_no',
                    searchable: true,
                    visible: false
                },
                {
                    data: 'receive_stock_voucher',
                    name: 'receiveStock.voucher_no',
                    searchable: true,
                    visible: false
                },
            ],
            fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
        });

        daybook_table.buttons().container().appendTo('#exportButtonsContainer');

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_daybook', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            daybook_table.ajax.reload();
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
                    $('.action_hideable').hide();
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

        $(document).on('click', '#print_modal_details_btn', function(e) {
            e.preventDefault();

            var body = $('.print_details').html();

            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('css/print/sale.print.css') }}",
                removeInline: false,
                printDelay: 700,
            });
        });

        // //Print account ledger
        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();

            var url = "{{ route('reports.daybook.print') }}";

            var voucher_type = $('#voucher_type').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var note = $('#note').val();
            var voucher_details = $('#voucher_details').val();
            var transaction_details = $('#transaction_details').val();
            var inventory_list = $('#inventory_list').val();

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    voucher_type,
                    note,
                    voucher_details,
                    transaction_details,
                    inventory_list,
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
    </script>
@endpush
