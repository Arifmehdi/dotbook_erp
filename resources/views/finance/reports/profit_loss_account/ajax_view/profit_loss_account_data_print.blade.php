<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto, font-size:10px; }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 5px;margin-right: 5px;}
    div#footer {position:fixed;bottom:20px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}

    h6 { font-size: 16px; }
    p {  font-size: 14px; }
    td {  color: black; }

    th { font-size:11px!important; font-weight: 550!important; }
    td { font-size:10px; }

        * { box-sizing: border-box;}
        .row { margin-left:-5px; margin-right:-5px;}
        .column {float: left; width: 100%; padding: 0px;}
        /* Clearfix (clear floats) */
        .row::after {content: "";clear: both;display: table;}
        table { border-collapse: collapse;border-spacing: 0; width: 100%;border: 1px solid #ddd;}
        th, td { text-align: left; vertical-align: baseline;}
        table.group_account_table tr {line-height: 16px;}
        table {border: none!important;}
        td.credit_area {border-left: 1px solid #000;}
        table.gross_total_balance tr {border-top: 1px solid; border-bottom: 1px solid;line-height: 16px;}
        .net_total_balance_footer tr {border-top: 1px solid; border-bottom: 1px solid;line-height: 16px;}
        .net_credit_total {border-left: 1px solid #000;}
        td.debit_area {line-height: 17px;}
        td.credit_area {line-height: 17px;}
        /* font-family: sans-serif; */
        td.first_td {width: 72%;}
        .profit_loss_account_area table{font-family: Arial, Helvetica, sans-serif;}
        .print_table td { font-size: 12px!important;}
</style>
@php
    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
<div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
    <div class="col-4">
        @if (json_decode($generalSettings->business, true)['business_logo'] != null)

            <img style="height: auto; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
        @else

            <p style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</p>
        @endif
    </div>

    <div class="col-8 text-end">
        <p style="text-transform: uppercase;"><strong>{{ json_decode($generalSettings->business, true)['shop_name'] }}</strong></p>
        <p><b>{{ json_decode($generalSettings->business, true)['address'] }}</b></p>
        <p>
            <strong>@lang('menu.email') : </strong> <b>{{ json_decode($generalSettings->business, true)['email'] }}</b>,
            <strong>@lang('menu.phone') : </strong> <b>{{ json_decode($generalSettings->business, true)['phone'] }}</b>
        </p>
    </div>
</div>

<div class="row mt-2">
    <div class="col-12 text-center">
        <h6 style="text-transform:uppercase;"><strong>@lang('menu.profit_loss_account') </strong></h6>
    </div>
</div>

<div class="row mt-1">
    <div class="col-12 text-center">
        @if ($fromDate && $toDate)
            <p>
                <strong>@lang('menu.from') :</strong>
                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($fromDate)) }}
                <strong>@lang('menu.to')</strong> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($toDate)) }}
            </p>
        @endif
    </div>
</div>

<div class="row mt-1">
    <div class="col-12">
        <table class="w-100 print_table">
            <thead>
                <tr>
                    <th class="header_text ps-1" style="border: 1px solid black;">@lang('menu.particulars')</th>
                    <th class="header_text ps-1" style="border: 1px solid black;">@lang('menu.particulars')</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td class="debit_area" style="border-left:1px solid black;width: 50%;">
                        <table class="opening_stock_account_group_table w-100 mt-1">
                            <tr>
                                <td><strong class="ps-2">@lang('menu.opening_stock')</strong></td>
                                <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($openingStock) }}</strong></td>
                            </tr>
                        </table>

                        @if ($purchaseAccountBalance['groupClosingBalanceSide'] == 'dr' && $purchaseAccountBalance['groupClosingBalance'] > 0)
                            <table class="purchase_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $purchaseAccountBalance['groupName'] }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($purchaseAccountBalance['results'] as $res)
                                                    @if ($res->account_id && $res->closing_balance > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $res->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ $res->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                {{ \App\Utils\Converter::format_in_bdt($res->closing_balance) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($purchaseAccountBalance['groupClosingBalance']) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($salesAccountBalance['groupClosingBalanceSide'] == 'dr' && $salesAccountBalance['groupClosingBalance'] > 0)
                            <table class="sales_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $salesAccountBalance['groupName'] }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($salesAccountBalance['results'] as $res)
                                                    @if ($res->account_id && $res->closing_balance > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $res->account_name }}</b></td>{{ $res->group_name }}</span>)</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ $res->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                                {{ \App\Utils\Converter::format_in_bdt($res->closing_balance) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($salesAccountBalance['groupClosingBalance']) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($directExpenseAccountBalance['groupClosingBalanceSide'] == 'dr' && $directExpenseAccountBalance['groupClosingBalance'] > 0)
                            <table class="direct_expense_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $directExpenseAccountBalance['groupName'] }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($directExpenseAccountBalance['results'] as $res)
                                                    @if ($res->account_id && $res->closing_balance > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $res->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ $res->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                {{ \App\Utils\Converter::format_in_bdt($res->closing_balance) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($directExpenseAccountBalance['groupClosingBalance']) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($directIncomesAccountBalance['groupClosingBalanceSide'] == 'dr' && $directIncomesAccountBalance['groupClosingBalance'] > 0)
                            <table class="direct_income_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $directIncomesAccountBalance['groupName'] }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($directIncomesAccountBalance['results'] as $res)
                                                    @if ($res->account_id && $res->closing_balance > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $res->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ $res->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                                {{ \App\Utils\Converter::format_in_bdt($res->closing_balance) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($directIncomesAccountBalance['groupClosingBalance']) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($grossAmountOfDebit > $grossAmountOfCredit)
                            <table class="gross_total_balance w-100 mt-1">
                                <tr>
                                    <td><strong class="ps-2"></strong></td>
                                    <td class="text-end"><strong>@lang('total') : {{ \App\Utils\Converter::format_in_bdt($grossAmountOfDebit) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($grossProfitOrLossSide == 'cr')
                            <table class="gross_profit_account_group_table w-100 mt-1">
                                <tr>
                                    <td><strong class="ps-2">@lang('menu.gross_profit_co')</strong></td>
                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($grossProfitOrLoss) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($grossProfitOrLossSide == 'dr')
                            <table class="gross_loss_table w-100 mt-1">
                                <tr>
                                    <td><strong class="ps-2">@lang('menu.gross_profit_bf')</strong></td>
                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($grossProfitOrLoss) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($grossAmountOfCredit > $grossAmountOfDebit)
                            <table class="gross_total_balance w-100 mt-1">
                                <tr>
                                    <td><strong class="ps-2"></strong></td>
                                    <td class="text-end"><strong>@lang('menu.total') : {{ \App\Utils\Converter::format_in_bdt($grossAmountOfCredit) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($indirectExpenseAccountBalance['groupClosingBalanceSide'] == 'dr' && $indirectExpenseAccountBalance['groupClosingBalance'] > 0)
                            <table class="indirect_expense_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $indirectExpenseAccountBalance['groupName'] }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($indirectExpenseAccountBalance['results'] as $res)
                                                    @if ($res->account_id && $res->closing_balance > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $res->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ $res->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                {{ \App\Utils\Converter::format_in_bdt($res->closing_balance) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($indirectExpenseAccountBalance['groupClosingBalance']) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($indirectIncomesAccountBalance['groupClosingBalanceSide'] == 'dr' && $indirectIncomesAccountBalance['groupClosingBalance'] > 0)
                            <table class="indirect_income_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $indirectIncomesAccountBalance['groupName'] }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($indirectIncomesAccountBalance['results'] as $res)
                                                    @if ($res->account_id && $res->closing_balance > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $res->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ $res->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                                {{ \App\Utils\Converter::format_in_bdt($res->closing_balance) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($indirectIncomesAccountBalance['groupClosingBalance']) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($netProfitLossSide == 'cr')
                            <table class="net_loss_account_group_table w-100">
                                <tr>
                                    <td><strong class="ps-2">@lang('menu.net_profit')</strong></td>
                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($netProfit) }}</strong></td>
                                </tr>
                            </table>
                        @endif
                    </td>

                    <td class="credit_area" style="border-right:1px solid black;">
                        @if ($purchaseAccountBalance['groupClosingBalanceSide'] == 'cr' && $purchaseAccountBalance['groupClosingBalance'] > 0)
                            <table class="purchase_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $purchaseAccountBalance['groupName'] }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($purchaseAccountBalance['results'] as $res)
                                                    @if ($res->account_id && $res->closing_balance > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $res->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ $res->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                {{ \App\Utils\Converter::format_in_bdt($res->closing_balance) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($purchaseAccountBalance['groupClosingBalance']) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($salesAccountBalance['groupClosingBalanceSide'] == 'cr' && $salesAccountBalance['groupClosingBalance'] > 0)
                            <table class="sales_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $salesAccountBalance['groupName'] }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($salesAccountBalance['results'] as $res)
                                                    @if ($res->account_id && $res->closing_balance > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $res->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ $res->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                                {{ \App\Utils\Converter::format_in_bdt($res->closing_balance) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($salesAccountBalance['groupClosingBalance']) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($directIncomesAccountBalance['groupClosingBalanceSide'] == 'cr' && $directIncomesAccountBalance['groupClosingBalance'] > 0)
                            <table class="direct_income_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $directIncomesAccountBalance['groupName'] }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($directIncomesAccountBalance['results'] as $res)
                                                    @if ($res->account_id && $res->closing_balance > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $res->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ $res->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                                {{ \App\Utils\Converter::format_in_bdt($res->closing_balance) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($directIncomesAccountBalance['groupClosingBalance']) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($directExpenseAccountBalance['groupClosingBalanceSide'] == 'cr' && $directExpenseAccountBalance['groupClosingBalance'] > 0)
                            <table class="direct_expense_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $directExpenseAccountBalance['groupName'] }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($directExpenseAccountBalance['results'] as $res)
                                                    @if ($res->account_id && $res->closing_balance > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $res->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ $res->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                {{ \App\Utils\Converter::format_in_bdt($res->closing_balance) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($directExpenseAccountBalance['groupClosingBalance']) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        <table class="closing_stock_account_group_table w-100 mt-1">
                            <tr>
                                <td><strong class="ps-2">@lang('menu.closing_stock')</strong></td>
                                <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($closingStock) }}</strong></td>
                            </tr>
                        </table>

                        @if ($grossAmountOfCredit > $grossAmountOfDebit)
                            <table class="gross_total_balance w-100 mt-1">
                                <tr>
                                    <td><strong class="ps-2"></strong></td>
                                    <td class="text-end"><strong>@lang('menu.total') : {{ \App\Utils\Converter::format_in_bdt($grossAmountOfCredit) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($grossProfitOrLossSide == 'dr')
                            <table class="gross_loss_table w-100 mt-1">
                                <tr>
                                    <td><strong class="ps-2">@lang('menu.gross_profit_co')</strong></td>
                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($grossProfitOrLoss) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($grossProfitOrLossSide == 'cr')
                            <table class="gross_profit_account_group_table w-100 mt-1">
                                <tr>
                                    <td><strong class="ps-2">@lang('menu.gross_profit_bf')</strong></td>
                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($grossProfitOrLoss) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($grossAmountOfDebit > $grossAmountOfCredit)
                            <table class="gross_total_balance w-100 mt-1">
                                <tr>
                                    <td><strong class="ps-2"></strong></td>
                                    <td class="text-end"><strong>@lang('menu.total') : {{ \App\Utils\Converter::format_in_bdt($grossAmountOfDebit) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($indirectExpenseAccountBalance['groupClosingBalanceSide'] == 'cr' && $indirectExpenseAccountBalance['groupClosingBalance'] > 0)
                            <table class="indirect_expense_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $indirectExpenseAccountBalance['groupName'] }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($indirectExpenseAccountBalance['results'] as $res)
                                                    @if ($res->account_id && $res->closing_balance > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $res->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ $res->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                {{ \App\Utils\Converter::format_in_bdt($res->closing_balance) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($indirectExpenseAccountBalance['groupClosingBalance']) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($indirectIncomesAccountBalance['groupClosingBalanceSide'] == 'cr' && $indirectIncomesAccountBalance['groupClosingBalance'] > 0)
                            <table class="indirect_income_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $indirectIncomesAccountBalance['groupName'] }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($indirectIncomesAccountBalance['results'] as $res)
                                                    @if ($res->account_id && $res->closing_balance > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $res->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ $res->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                                {{ \App\Utils\Converter::format_in_bdt($res->closing_balance) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($indirectIncomesAccountBalance['groupClosingBalance']) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($netProfitLossSide == 'dr')
                            <table class="net_loss_account_group_table w-100">
                                <tr>
                                    <td><strong class="ps-2">@lang('menu.net_loss')</strong></td>
                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($netLoss) }}</strong></td>
                                </tr>
                            </table>
                        @endif
                    </td>
                </tr>
            </tbody>
            <tfoot class="net_total_balance_footer">
                <tr>
                    <td class="text-end fw-bold net_debit_total" style="border-left:1px solid black;">Total : {{ \App\Utils\Converter::format_in_bdt($totalNetAmountBothSide) }}</td>
                    <td class="text-end fw-bold net_credit_total" style="border-right:1px solid black;">Total : {{ \App\Utils\Converter::format_in_bdt($totalNetAmountBothSide) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div id="footer">
    <div class="row">
        <div class="col-4 text-start">
            <small>@lang('menu.print_date') : {{ date(json_decode($generalSettings->business, true)['date_format']) }}</small>
        </div>

        <div class="col-4 text-center">
            @if (config('company.print_on_sale'))
                <small>@lang('menu.powered_by') <strong>@lang('menu.speedDigit_software_solution').</strong></small>
            @endif
        </div>

        <div class="col-4 text-end">
            <small>@lang('menu.print_time') : {{ date($timeFormat) }}</small>
        </div>
    </div>
</div>
