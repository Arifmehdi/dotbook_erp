@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        * {
            box-sizing: border-box;
        }

        .row {
            margin-left: -5px;
            margin-right: -5px;
        }

        .column {
            float: left;
            width: 100%;
            padding: 0px;
        }

        .row::after {
            content: "";
            clear: both;
            display: table;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
            border: 1px solid #ddd;
        }

        th,
        td {
            text-align: left;
            vertical-align: baseline;
        }

        .group_tr {
            line-height: 17px;
        }

        .account_tr {
            line-height: 17px;
        }

        table {
            border: none !important;
        }

        td.ledger_cash_flow_area {
            border-left: 1px solid #000;
        }

        .net_total_balance_footer tr {
            border-top: 1px solid;
            border-bottom: 1px solid;
            line-height: 16px;
        }

        td.inflow_area {
            line-height: 17px;
        }

        td.ledger_cash_flow_area {
            line-height: 17px;
        }

        .header_text {
            letter-spacing: 3px;
            border-bottom: 1px solid;
            background-color: #fff !important;
            color: #000 !important
        }
    </style>
@endpush
@section('title', $account->name . ' - Ledger Cash Flow - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.ledger_cash_flow') -
                        <span>{{ $account->name }}
                            {{ $account->phone ? ' / ' . $account->phone : '' }}
                            {{ $account->account_number ? ' / ' . $account->account_number : '' }}</span>
                    </h6>
                </div>
                <x-all-buttons>
                    <button class="btn text-white btn-sm px-2" id="print_report"><span><i class="fa-thin fa-print fa-2x"></i><br> @lang('menu.print')</span></button>
                    <x-help-button />
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            <div class="row g-1">
                <div class="col-md-12">
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <form id="filter_group_cash_flow">
                                <div class="form-group row align-items-end">
                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.from_date') </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                            </div>
                                            <input type="text" name="from_date" id="from_date" class="form-control" value="{{ $fromDate }}" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.to_date') </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                            </div>
                                            <input type="text" name="to_date" id="to_date" class="form-control" value="{{ $toDate }}" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>@lang('menu.note/remarks') :</strong></label>
                                        <select name="note" class="form-control form-select" id="note">
                                            <option value="0">{{ __("No") }}</option>
                                            <option selected value="1">{{ __("Yes") }}</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>@lang('menu.voucher_details') :</strong></label>
                                        <select name="voucher_details" class="form-control form-select" id="voucher_details">
                                            <option value="0">{{ __("No") }}</option>
                                            <option value="1">{{ __("Yes") }}</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>@lang('menu.transaction_details') :</strong></label>
                                        <select name="transaction_details" class="form-control form-select" id="transaction_details">
                                            <option value="0">{{ __("No") }}</option>
                                            <option value="1">{{ __("Yes") }}</option>
                                        </select>
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

            <div class="row g-1">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="px-2">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                                </div>
                                <div class="ledger_cash_flow_area">
                                    <div class="table-responsive h-350" id="data-list">
                                        <table class="w-100">
                                            <thead>
                                                <tr>
                                                    <th class="header_text ps-1 text-start">@lang('menu.date')</th>
                                                    <th class="header_text ps-1 text-start">@lang('menu.particulars')</th>
                                                    <th class="header_text ps-1 text-start">@lang('menu.voucher_type')</th>
                                                    <th class="header_text ps-1 text-start">@lang('menu.voucher_no')</th>
                                                    <th class="header_text ps-1 text-end">@lang('menu.debit')</th>
                                                    <th class="header_text ps-1 text-end">@lang('menu.credit')</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr class="group_tr">
                                                    <td class="fw-bold">10-04-2023</td>
                                                    <td class="text-start fw-bold">Customer A</td>
                                                    <td class="text-start fw-bold">Sales</td>
                                                    <td class="text-start">1001-2304-120</td>
                                                    <td class="text-end fw-bold"></td>
                                                    <td class="text-end fw-bold">1,000.00</td>
                                                </tr>
                                            </tbody>

                                            <tfoot class="net_total_balance_footer">
                                                <tr>
                                                    <td colspan="4" class="text-end fw-bold">@lang('menu.current_total') :</td>
                                                    <td class="text-end fw-bold net_debit_total"></td>
                                                    <td class="text-end fw-bold net_credit_total"></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
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
        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function getLedgerCashflow() {

            $('.data_preloader').show();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var note = $('#note').val();
            var transaction_details = $('#transaction_details').val();
            var voucher_details = $('#voucher_details').val();
            var inventory_list = $('#inventory_list').val();
            $.ajax({
                url: "{{ route('reports.ledger.cash.flow.blade.view', [$account->id, $cashFlowSide]) }}",
                type: 'GET',
                data: {
                    from_date,
                    to_date,
                    note,
                    transaction_details,
                    voucher_details,
                    inventory_list
                },
                success: function(data) {

                    $('.data_preloader').hide();
                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    $('#data-list').html(data);
                }
            });
        }
        getLedgerCashflow();

        $(document).on('submit', '#filter_group_cash_flow', function(e) {

            e.preventDefault();
            getLedgerCashflow();
        });

        // Print single payment details
        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();

            var url = "{{ route('reports.ledger.cash.flow.print', [$account->id, $cashFlowSide]) }}";

            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var note = $('#note').val();
            var transaction_details = $('#transaction_details').val();
            var voucher_details = $('#voucher_details').val();
            var inventory_list = $('#inventory_list').val();

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    from_date,
                    to_date,
                    note,
                    transaction_details,
                    voucher_details,
                    inventory_list
                },
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

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

        // Show details modal with data
        $(document).on('click', '#details_btn', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.get(url, function(data) {

                $('#details').html(data);
                $('.data_preloader').hide();
                $('#detailsModal').modal('show');
                $('.action_hideable').hide();
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
