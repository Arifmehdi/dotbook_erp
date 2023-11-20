@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        * {
            box-sizing: border-box;
        }

        .column {
            float: left;
            width: 100%;
            padding: 0px;
        }

        /* Clearfix (clear floats) */
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

        table.group_account_table tr {
            line-height: 16px;
        }

        table {
            border: none !important;
        }

        table.gross_total_balance tr {
            border-top: 1px solid;
            border-bottom: 1px solid;
            line-height: 16px;
        }

        .net_total_balance_footer tr {
            border-top: 1px solid;
            border-bottom: 1px solid;
            line-height: 16px;
        }

        .net_debit_total {
            border-left: 1px solid #000;
        }

        td.debit_area {
            line-height: 17px;
            border-left: 1px solid #000;
        }

        td.credit_area {
            line-height: 17px;
        }

        /* font-family: sans-serif; */
        td.first_td {
            width: 72%;
        }

        .header_text {
            letter-spacing: 3px;
            border-bottom: 1px solid;
            background-color: #fff !important;
            color: #000 !important
        }
    </style>
@endpush
@section('title', 'Fund Flow - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.fund_flow')</h6>
                </div>
                <x-all-buttons>
                    <button id="print_report_btn" class="btn text-white btn-sm px-2"><span><i
                                class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')</span></button>
                    <x-help-button />
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            <div class="row g-1">
                <div class="col-md-12">
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <form id="filter_fund_flow">
                                <div class="form-group row align-items-end g-2">
                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.from_date') </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i
                                                        class="fas fa-calendar-week input_f"></i></span>
                                            </div>
                                            <input type="text" name="from_date" id="from_date"
                                                class="form-control from_date date" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
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

                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.format_of_report') </strong></label>
                                        <div class="input-group">
                                            <select name="format_of_report" class="form-control form-select"
                                                id="format_of_report">
                                                <option value="condensed">Condensed</option>
                                                <option value="detailed">Detailed</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <button type="submit" class="btn btn-sm btn-info"><i
                                                class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
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
                        <div class="card-body p-0">
                            <div class="p-2">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                                </div>
                                <div class="fund_flow_area">
                                    <div class="table-responsive h-350" id="data-list">
                                        <table class="w-100">
                                            <thead>
                                                <tr>
                                                    <th class="header_text ps-1">@lang('menu.sources')</th>
                                                    <th class="header_text ps-1" style="border-left: 1px solid black;">
                                                        @lang('menu.applications')</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td class="credit_area">
                                                        <table class="capital_account_group_table w-100 mt-1">
                                                            <tr>
                                                                <td class="first_td">
                                                                    <strong class="ps-2">Capital Account</strong>
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1"><a
                                                                                    href="#">Capital A/c</a></td>
                                                                            <td class="group_account_balance text-end">
                                                                                1,200.00</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td class="text-end"><strong>1,200.00</strong></td>
                                                            </tr>
                                                        </table>

                                                        <table class="loan_liabilities_account_group_table w-100 mt-1">
                                                            <tr>
                                                                <td class="first_td">
                                                                    <strong class="ps-2">Loan Liabilities</strong>
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1"><a
                                                                                    href="#">City Bank</a></td>
                                                                            <td class="group_account_balance text-end">
                                                                                1,200.00</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td class="text-end"><strong>12,400.00</strong></td>
                                                            </tr>
                                                        </table>

                                                        <table class="branch_and_divisions_account_group_table w-100 mt-1">
                                                            <tr>
                                                                <td class="first_td">
                                                                    <strong class="ps-2">Branch/Divisions</strong>
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1"><a
                                                                                    href="#">City Bank</a></td>
                                                                            <td class="group_account_balance text-end">
                                                                                1,200.00</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td class="text-end"><strong>12,400.00</strong></td>
                                                            </tr>
                                                        </table>

                                                        <table class="suspense_account_group_table w-100 mt-1">
                                                            <tr>
                                                                <td class="first_td">
                                                                    <strong class="ps-2">Suspense A/c</strong>
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1"><a
                                                                                    href="#">City Bank</a></td>
                                                                            <td class="group_account_balance text-end">
                                                                                1,200.00</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td class="text-end"><strong>12,400.00</strong></td>
                                                            </tr>
                                                        </table>
                                                    </td>

                                                    <td class="debit_area">
                                                        <table class="capital_account_group_table w-100 mt-1">
                                                            <tr>
                                                                <td class="first_td">
                                                                    <strong class="ps-2">Capital Account</strong>
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1"><a
                                                                                    href="#">Capital A/c</a></td>
                                                                            <td class="group_account_balance text-end">
                                                                                1,200.00</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td class="text-end"><strong>1,200.00</strong></td>
                                                            </tr>
                                                        </table>

                                                        <table class="loan_liabilities_account_group_table w-100 mt-1">
                                                            <tr>
                                                                <td class="first_td">
                                                                    <strong class="ps-2">Loan Liabilities</strong>
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1"><a
                                                                                    href="#">City Bank</a></td>
                                                                            <td class="group_account_balance text-end">
                                                                                1,200.00</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td class="text-end"><strong>12,400.00</strong></td>
                                                            </tr>
                                                        </table>

                                                        <table class="branch_and_divisions_account_group_table w-100 mt-1">
                                                            <tr>
                                                                <td class="first_td">
                                                                    <strong class="ps-2">Branch/Divisions</strong>
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1"><a
                                                                                    href="#">City Bank</a></td>
                                                                            <td class="group_account_balance text-end">
                                                                                1,200.00</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td class="text-end"><strong>12,400.00</strong></td>
                                                            </tr>
                                                        </table>

                                                        <table class="suspense_account_group_table w-100 mt-1">
                                                            <tr>
                                                                <td class="first_td">
                                                                    <strong class="ps-2">Suspense A/c</strong>
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1"><a
                                                                                    href="#">City Bank</a></td>
                                                                            <td class="group_account_balance text-end">
                                                                                1,200.00</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td class="text-end"><strong>12,400.00</strong></td>
                                                            </tr>
                                                        </table>

                                                        <table class="profit_loss_account_group_table w-100 mt-1">
                                                            <tr>
                                                                <td class="first_td">
                                                                    <strong class="ps-2">Net Profit</strong>
                                                                </td>

                                                                <td class="text-end"><strong>12,400.00</strong></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>

                                            <tfoot class="net_total_balance_footer">
                                                <tr>
                                                    <td class="text-end fw-bold net_debit_total">Total : 20,000.00</td>
                                                    <td class="text-end fw-bold net_credit_total">Total : 20,000.00</td>
                                                </tr>

                                                <tr>
                                                    <td colspan="2">
                                                        <table class="w-100">
                                                            <thead>
                                                                <tr>
                                                                    <th class="header_text ps-1 text-start w-50">
                                                                        @lang('menu.particulars')</th>
                                                                    <th class="header_text ps-1 text-end">
                                                                        @lang('menu.opening_balance')</th>
                                                                    <th class="header_text ps-1 text-end">
                                                                        @lang('menu.closing_balance')</th>
                                                                    <th class="header_text ps-1 text-end">
                                                                        @lang('menu.wkg_cap_increase')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td class="fw-bold text-start w-50">Current Assets</td>
                                                                    <td class="fw-bold text-end">100,0000.0.00</td>
                                                                    <td class="fw-bold text-end">200,0000.0.00</td>
                                                                    <td class="fw-bold text-end">200,0000.0.00</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="fw-bold text-start w-50">Current Liabilities
                                                                    </td>
                                                                    <td class="fw-bold text-end">100,0000.0.00</td>
                                                                    <td class="fw-bold text-end">100,0000.0.00</td>
                                                                    <td class="fw-bold text-end">100,0000.0.00</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="fw-bold text-start w-50">Working Capital
                                                                    </td>
                                                                    <td class="fw-bold text-end">100,0000.0.00</td>
                                                                    <td class="fw-bold text-end">100,0000.0.00</td>
                                                                    <td class="fw-bold text-end">100,0000.0.00</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
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
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function getFundFlow() {

            $('.data_preloader').show();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var format_of_report = $('#format_of_report').val();

            $.ajax({
                url: "{{ route('reports.fund.flow.data.view') }}",
                type: 'GET',
                data: {
                    from_date,
                    to_date,
                    format_of_report
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
        getFundFlow();

        $(document).on('submit', '#filter_fund_flow', function(e) {

            e.preventDefault();
            getFundFlow();
        });

        // Print single payment details
        $(document).on('click', '#print_report_btn', function(e) {
            e.preventDefault();

            var url = "{{ route('reports.fund.flow.data.print') }}";

            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var format_of_report = $('#format_of_report').val();

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    from_date,
                    to_date,
                    format_of_report
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
