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

        td.credit_area {
            border-left: 1px solid #000;
        }

        table.gross_total_balance tr {
            border-top: 1px solid;
            border-bottom: 1px solid;
            /* line-height: 16px; */
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
@section('title', 'Profit Loss A/c - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.profit_loss_account')</h6>
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
                            <form id="filter_profit_loss_account">
                                <div class="form-group row align-items-end g-2">
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

                                    <div class="col-xl-2 col-md-4">
                                        <label><strong>@lang('menu.format_of_report') </strong></label>
                                        <div class="input-group">
                                            <select name="format_of_report" class="form-control form-select" id="format_of_report">
                                                <option value="condensed">{{ __("Condensed") }}</option>
                                                <option value="detailed">{{ __("Detailed") }}</option>
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
                                <div class="profit_loss_account_area">
                                    <div class="table-responsive h-350" id="data-list">
                                        <table class="w-100">
                                            <thead>
                                                <tr>
                                                    <th class="header_text ps-1">{{ __("Particulars") }}</th>
                                                    <th class="header_text ps-1" style="border-left: 1px solid black;">Particulars</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <table class="opening_balance_account_group_table w-100">
                                                            <tr>
                                                                <td>Opening Stock</td>
                                                                <td class="text-end">1,0000.00</td>
                                                            </tr>
                                                        </table>

                                                        <table class="purchase_account_group_table w-100">
                                                            <tr>
                                                                <td>
                                                                    Purchase Account ledger
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1">Purchase 1
                                                                            </td>
                                                                            <td class="group_account_balance">60,200.00</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="group_account_name ps-1">Purchase 2
                                                                            </td>
                                                                            <td class="group_account_balance">60,200.00</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td class="text-end">1,0000.00</td>
                                                            </tr>
                                                        </table>

                                                        <table class="sales_account_group_table w-100">
                                                            <tr>
                                                                <td>
                                                                    Sales Account ledger
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1">Purchase 1
                                                                            </td>
                                                                            <td class="group_account_balance">60,200.00</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="group_account_name ps-1">Purchase 2
                                                                            </td>
                                                                            <td class="group_account_balance">60,200.00</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td class="text-end">1,0000.00</td>
                                                            </tr>
                                                        </table>

                                                        <table class="direct_expense_account_group_table w-100">
                                                            <tr>
                                                                <td>
                                                                    Direct Expense
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1">Purchase 1
                                                                            </td>
                                                                            <td class="group_account_balance">60,200.00</td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="group_account_name ps-1">Purchase 2
                                                                            </td>
                                                                            <td class="group_account_balance">60,200.00</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td class="text-end">1,0000.00</td>
                                                            </tr>
                                                        </table>

                                                        <table class="direct_expense_account_group_table w-100">
                                                            <tr>
                                                                <td>
                                                                    Gross Profit
                                                                </td>

                                                                <td class="text-end">1,0000.00</td>
                                                            </tr>
                                                        </table>

                                                        <table class="direct_expense_account_group_table w-100">
                                                            <tr>
                                                                <td>
                                                                    Gross Loss
                                                                </td>

                                                                <td class="text-end">1,0000.00</td>
                                                            </tr>
                                                        </table>

                                                        <table class="direct_expense_account_group_table w-100">
                                                            <tr>
                                                                <td>
                                                                    Indrect Expense
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1">Purchase 1
                                                                            </td>
                                                                            <td class="group_account_balance">60,200.00</td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="group_account_name ps-1">Purchase 2
                                                                            </td>
                                                                            <td class="group_account_balance">60,200.00
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td class="text-end">1,0000.00</td>
                                                            </tr>
                                                        </table>

                                                        <table class="direct_expense_account_group_table w-100">
                                                            <tr>
                                                                <td>
                                                                    Net Profit
                                                                </td>

                                                                <td class="text-end">1,0000.00</td>
                                                            </tr>
                                                        </table>
                                                    </td>

                                                    <td>
                                                        <table class="opening_balance_account_group_table w-100">
                                                            <tr>
                                                                <td>Opening Stock</td>
                                                                <td class="text-end">1,0000.00</td>
                                                            </tr>
                                                        </table>

                                                        <table class="purchase_account_group_table w-100">
                                                            <tr>
                                                                <td>
                                                                    Purchase Account ledger
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1">Purchase 1
                                                                            </td>
                                                                            <td class="group_account_balance">60,200.00
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="group_account_name ps-1">Purchase 2
                                                                            </td>
                                                                            <td class="group_account_balance">60,200.00
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td class="text-end">1,0000.00</td>
                                                            </tr>
                                                        </table>

                                                        <table class="sales_account_group_table w-100">
                                                            <tr>
                                                                <td>
                                                                    Sales Account ledger
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1">Purchase 1
                                                                            </td>
                                                                            <td class="group_account_balance">60,200.00
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="group_account_name ps-1">Purchase 2
                                                                            </td>
                                                                            <td class="group_account_balance">60,200.00
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td class="text-end">1,0000.00</td>
                                                            </tr>
                                                        </table>

                                                        <table class="direct_expense_account_group_table w-100">
                                                            <tr>
                                                                <td>
                                                                    Gross Loss
                                                                </td>

                                                                <td class="text-end">1,0000.00</td>
                                                            </tr>
                                                        </table>

                                                        <table class="direct_expense_account_group_table w-100">
                                                            <tr>
                                                                <td>
                                                                    Direct Expense
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1">Purchase 1
                                                                            </td>
                                                                            <td class="group_account_balance">60,200.00
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="group_account_name ps-1">Purchase 2
                                                                            </td>
                                                                            <td class="group_account_balance">60,200.00
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td class="text-end">1,0000.00</td>
                                                            </tr>
                                                        </table>

                                                        <table class="direct_expense_account_group_table w-100">
                                                            <tr>
                                                                <td>
                                                                    Gross Profit
                                                                </td>

                                                                <td class="text-end">1,0000.00</td>
                                                            </tr>
                                                        </table>

                                                        <table class="direct_expense_account_group_table w-100">
                                                            <tr>
                                                                <td>
                                                                    Indrect Expense
                                                                    <table class="group_account_table ms-2">
                                                                        <tr>
                                                                            <td class="group_account_name ps-1">Purchase 1
                                                                            </td>
                                                                            <td class="group_account_balance">60,200.00
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="group_account_name ps-1">Purchase 2
                                                                            </td>
                                                                            <td class="group_account_balance">60,200.00
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td class="text-end">1,0000.00</td>
                                                            </tr>
                                                        </table>

                                                        <table class="direct_expense_account_group_table w-100">
                                                            <tr>
                                                                <td>
                                                                    Net Loss
                                                                </td>
                                                                <td class="text-end">1,0000.00</td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
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

        function getProfitLossAccount() {

            $('.data_preloader').show();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var format_of_report = $('#format_of_report').val();

            $.ajax({
                url: "{{ route('reports.profit.loss.account.data.view') }}",
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
        getProfitLossAccount();

        //Print purchase Payment report
        $(document).on('submit', '#filter_profit_loss_account', function(e) {

            e.preventDefault();
            getProfitLossAccount();
        });

        // Print single payment details
        $(document).on('click', '#print_report_btn', function(e) {
            e.preventDefault();

            var url = "{{ route('reports.profit.loss.account.print') }}";

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
