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

        .net_credit_total {
            border-left: 1px solid #000;
        }

        td.debit_area {
            line-height: 17px;
        }

        td.credit_area {
            line-height: 17px;
        }

        /* font-family: sans-serif; */
        td.first_td {
            width: 80%;
        }

        .header_text {
            letter-spacing: 3px;
            border-bottom: 1px solid;
            background-color: #fff !important;
            color: #000 !important
        }
    </style>
@endpush
@section('title', 'Balance Sheet - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.balance_sheet')</h6>
                </div>
                <x-all-buttons>
                    <button id="print_report_btn" class="btn text-white btn-sm px-2" id="print_btn"><span><i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')</span></button>
                    <x-help-button />
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            <div class="row g-1">
                <div class="col-md-12">
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <form id="filter_balance_sheet">
                                <div class="form-group row align-items-end g-2">
                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.from_date') </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                            </div>
                                            <input type="text" name="from_date" id="from_date" class="form-control from_date date" autocomplete="off">
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
                                        <label><strong>@lang('menu.format_of_report') </strong></label>
                                        <div class="input-group">
                                            <select name="format_of_report" class="form-control form-select" id="format_of_report">
                                                <option value="condensed">@lang('menu.condensed')</option>
                                                <option value="detailed">@lang('menu.detailed')</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.showing_method') </strong></label>
                                        <div class="input-group">
                                            <select name="showing_method" class="form-control form-select" id="showing_method">
                                                <option value="liabilities-assets">@lang('menu.liabilities_assets')</option>
                                                <option value="assets-liabilities">@lang('menu.assets_liabilities')</option>
                                            </select>
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
            <div class="row g-1">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="p-2">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                                </div>
                                <div class="balance_sheet_area">
                                    <div class="table-responsive h-350" id="data-list">
                                        <table class="w-100">
                                            <thead>
                                                <tr>
                                                    <th class="header_text ps-1">Particulars</th>
                                                    <th class="header_text ps-1" style="border-left: 1px solid black;">
                                                        Particulars</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td class="credit_area">
                                                        <table class="capital_account_group_table w-100 mt-1">
                                                            <tr>
                                                                <td>
                                                                    <strong class="ps-2">Capital Account</strong>
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1"><a href="#">Capital A/c</a></td>
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
                                                                <td>
                                                                    <strong class="ps-2">Loan Liabilities</strong>
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1"><a href="#">City Bank</a></td>
                                                                            <td class="group_account_balance text-end">
                                                                                1,200.00</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td class="text-end"><strong>12,400.00</strong></td>
                                                            </tr>
                                                        </table>

                                                        <table class="current_assets_account_group_table w-100 mt-1">
                                                            <tr>
                                                                <td>
                                                                    <strong class="ps-2">Current Assets</strong>
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1"><a href="#">Closing Stocks</a></td>
                                                                            <td class="group_account_balance text-end">
                                                                                1,200.00</td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="group_account_name ps-1"><a href="#">Diposits (Asset)</a></td>
                                                                            <td class="group_account_balance text-end">
                                                                                1,200.00</td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="group_account_name ps-1"><a href="#">Account Receivable</a>
                                                                            </td>
                                                                            <td class="group_account_balance text-end">
                                                                                1,200.00</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td class="text-end"><strong>12,400.00</strong></td>
                                                            </tr>
                                                        </table>

                                                        <table class="investments_account_group_table w-100 mt-1">
                                                            <tr>
                                                                <td>
                                                                    <strong class="ps-2">Investments</strong>
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1"><a href="#">Advertisement</a></td>
                                                                            <td class="group_account_balance text-end">
                                                                                1,200.00</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td class="text-end"><strong>12,400.00</strong></td>
                                                            </tr>
                                                        </table>

                                                        <table class="fixed_assets_account_group_table w-100 mt-1">
                                                            <tr>
                                                                <td>
                                                                    <strong class="ps-2">Fixed Assets</strong>
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1"><a href="#">Furniture</a></td>
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
                                                                <td>
                                                                    <strong class="ps-2">Profit Loss A/c</strong>
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1">Opening
                                                                                Balance</td>
                                                                            <td class="group_account_balance text-end">
                                                                                1,000.00</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="group_account_name ps-1">Current
                                                                                Period</td>
                                                                            <td class="group_account_balance text-end">
                                                                                7,200.00</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="group_account_name ps-1">Less:
                                                                                Tranferred</td>
                                                                            <td class="group_account_balance text-end">
                                                                                4,200.00</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td class="text-end"><strong>12,400.00</strong></td>
                                                            </tr>
                                                        </table>

                                                        <table class="current_liabilities_account_group_table w-100 mt-1">
                                                            <tr>
                                                                <td>
                                                                    <strong class="ps-2">Current Liabilities</strong>
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1"><a href="#">Duties & Taxes</a></td>
                                                                            <td class="group_account_balance text-end">
                                                                                2,200.00</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                                <td class="text-end"><strong>2,200.00</strong></td>
                                                            </tr>
                                                        </table>
                                                    </td>

                                                    <td class="debit_area">
                                                        <table class="capital_account_group_table w-100 mt-1">
                                                            <tr>
                                                                <td>
                                                                    <strong class="ps-2">Capital Account</strong>
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1"><a href="#">Capital A/c</a></td>
                                                                            <td class="group_account_balance text-end">
                                                                                1,200.00</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td class="text-end"><strong>1,200.00</strong></td>
                                                            </tr>
                                                        </table>

                                                        <table class="current_liabilities_account_group_table w-100 mt-1">
                                                            <tr>
                                                                <td>
                                                                    <strong class="ps-2">Current Liabilities</strong>
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1"><a href="#">Duties & Taxes</a></td>
                                                                            <td class="group_account_balance text-end">
                                                                                2,200.00</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                                <td class="text-end"><strong>2,200.00</strong></td>
                                                            </tr>
                                                        </table>

                                                        <table class="loan_liabilities_account_group_table w-100 mt-1">
                                                            <tr>
                                                                <td>
                                                                    <strong class="ps-2">Loan Liabilities</strong>
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1"><a href="#">City Bank</a></td>
                                                                            <td class="group_account_balance text-end">
                                                                                1,200.00</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td class="text-end"><strong>12,400.00</strong></td>
                                                            </tr>
                                                        </table>

                                                        <table class="fixed_assets_account_group_table w-100 mt-1">
                                                            <tr>
                                                                <td>
                                                                    <strong class="ps-2">Fixed Assets</strong>
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1"><a href="#">Furniture</a></td>
                                                                            <td class="group_account_balance text-end">
                                                                                1,200.00</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td class="text-end"><strong>12,400.00</strong></td>
                                                            </tr>
                                                        </table>

                                                        <table class="investments_account_group_table w-100 mt-1">
                                                            <tr>
                                                                <td>
                                                                    <strong class="ps-2">Investments</strong>
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1"><a href="#">Advertisement</a></td>
                                                                            <td class="group_account_balance text-end">
                                                                                1,200.00</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td class="text-end"><strong>12,400.00</strong></td>
                                                            </tr>
                                                        </table>

                                                        <table class="current_assets_account_group_table w-100 mt-1">
                                                            <tr>
                                                                <td>
                                                                    <strong class="ps-2">Current Assets</strong>
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1"><a href="#">Closing Stocks</a></td>
                                                                            <td class="group_account_balance text-end">
                                                                                1,200.00</td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="group_account_name ps-1"><a href="#">Diposits (Asset)</a>
                                                                            </td>
                                                                            <td class="group_account_balance text-end">
                                                                                1,200.00</td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="group_account_name ps-1"><a href="#">Account Receivable</a>
                                                                            </td>
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
                                                                <td>
                                                                    <strong class="ps-2">Profit Loss A/c</strong>
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1">Opening
                                                                                Balance</td>
                                                                            <td class="group_account_balance text-end">
                                                                                1,000.00</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="group_account_name ps-1">Current
                                                                                Period</td>
                                                                            <td class="group_account_balance text-end">
                                                                                7,200.00</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="group_account_name ps-1">Less:
                                                                                Tranferred</td>
                                                                            <td class="group_account_balance text-end">
                                                                                4,200.00</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td class="text-end"><strong>12,400.00</strong></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>

                                            <tfoot class="net_total_balance_footer">
                                                <tr>
                                                    <td class="text-start fw-bold net_debit_total">Total : 20,000.00</td>
                                                    <td class="text-start fw-bold net_credit_total">Total : 20,000.00</td>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function getBalanceSheet() {

            $('.data_preloader').show();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var format_of_report = $('#format_of_report').val();
            var showing_method = $('#showing_method').val();

            $.ajax({
                url: "{{ route('reports.balance.sheet.data.view') }}",
                type: 'GET',
                data: {
                    from_date,
                    to_date,
                    format_of_report,
                    showing_method
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
        getBalanceSheet();

        $(document).on('submit', '#filter_balance_sheet', function(e) {

            e.preventDefault();
            getBalanceSheet();
        });

        // Print single payment details
        $(document).on('click', '#print_report_btn', function(e) {
            e.preventDefault();

            var url = "{{ route('reports.balance.sheet.data.print') }}";

            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var format_of_report = $('#format_of_report').val();
            var showing_method = $('#showing_method').val();

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    from_date,
                    to_date,
                    format_of_report,
                    showing_method
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
