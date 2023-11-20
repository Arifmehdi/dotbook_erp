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

    * { box-sizing: border-box;}
    .row { margin-left:-5px; margin-right:-5px;}
    .column {float: left; width: 100%; padding: 0px;}
    /* Clearfix (clear floats) */
    .row::after {content: "";clear: both;display: table;}

    table { border-collapse: collapse;border-spacing: 0; width: 100%;border: 1px solid #ddd;}
    th, td { text-align: left;}
    table {border: none!important;}

    .net_total_balance_footer tr {border-top: 1px solid; border-bottom: 1px solid;line-height: 16px;}
    /* td.trial_balance_area {font-family: Arial, Helvetica, sans-serif!important;} */
    td.trial_balance_area {line-height: 17px!important;}
    .trial_balance_area table{font-family: Arial, Helvetica, sans-serif!important;}
    .header_text {letter-spacing: 3px;border-bottom: 1px solid; background-color: #fff!important; color: #000!important}
    .trial_balance_area tbody tr td {line-height: 16px!important;}
</style>
@php
    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
<div class="row" style="border-bottom: 1px solid black;">
    <div class="col-4">
        @if (json_decode($generalSettings->business, true)['business_logo'] != null)
            <img style="height: auto; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
        @else
            <span style="font-family: 'Anton', sans-serif;font-size:17px;color:gray;font-weight: 550; letter-spacing:1px;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
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
        <h6 class="text-uppercase"><strong>@lang('menu.trial_balance') </strong></h6>
    </div>
</div>

<div class="row">
    <div class="col-12 text-center">
        @if ($fromDate && $toDate)

            <p style="margin-top: 10px;"><strong>@lang('menu.from') :</strong>
                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($fromDate)) }}
                <strong>To</strong> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($toDate)) }}
            </p>
        @endif
    </div>
</div>

<div class="row">
    <p><strong>@lang('menu.type_of_grouping') : </strong><b>@lang('menu.ledger_wise')</b></p>
</div>

<div class="row">
    <div class="col-12">
        <div class="trial_balance_area">
            <table class="w-100">
                <thead>
                    <tr>
                        <th rowspan="2" class="header_text text-center" style="border-top:1px solid black;">@lang('menu.particulars')</th>
                        <th colspan="2" class="header_text text-center" style="border:1px solid black;">@lang('menu.opening_balance')</th>
                        <th colspan="2" class="header_text text-center" style="border:1px solid black;">@lang('menu.closing_balance')</th>
                    </tr>

                    <tr>
                        <th class="header_text text-end pe-1" style="border-left:1px solid black;border-right:1px solid black;">@lang('menu.debit')</th>
                        <th class="header_text text-end pe-1" style="border-right:1px solid black;">@lang('menu.credit')</th>
                        <th class="header_text text-end pe-1" style="border-right:1px solid black;">@lang('menu.debit')</th>
                        <th class="header_text text-end pe-1" style="border-right:1px solid black;">@lang('menu.credit')</th>
                    </tr>
                </thead>
                @php
                    $totalDebitOpeningBalance = $openingStock;
                    $totalCreditOpeningBalance = 0;
                    $totalDebitClosingBalance = $openingStock;
                    $totalCreditClosingBalance = 0;
                @endphp
                <tbody class="trial_balance_main_table_body">
                    @if ($openingStock > 0)
                        <tr class="opening_stock">
                            <td class="text-start fw-bold">@lang('menu.opening_stock')</td>
                            <td class="text-end debit_amount fw-bold">{{ \App\Utils\Converter::format_in_bdt($openingStock) }}</td>
                            <td class="text-end closing_balance fw-bold"></td>
                            <td class="text-end closing_balance fw-bold">{{ \App\Utils\Converter::format_in_bdt($openingStock) }}</td>
                            <td class="text-end closing_balance fw-bold"></td>
                        </tr>
                    @endif

                    @foreach ($accounts as $account)
                        @php
                            $debitOpeningBalance = isset($account->opening_total_debit) ? $account->opening_total_debit : 0;
                            $creditOpeningBalance = isset($account->opening_total_credit) ? $account->opening_total_credit : 0;

                            $currOpeningBalance = 0;
                            $currOpeningBalanceSide = 'dr';
                            if ($debitOpeningBalance > $creditOpeningBalance) {

                                $currOpeningBalance = $debitOpeningBalance - $creditOpeningBalance;
                                $currOpeningBalanceSide = 'dr';
                            }elseif ($creditOpeningBalance > $debitOpeningBalance) {

                                $currOpeningBalance = $creditOpeningBalance - $debitOpeningBalance;
                                $currOpeningBalanceSide = 'cr';
                            }

                            $currentDebit = $account->curr_total_debit + ($currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0);
                            $currentCredit = $account->curr_total_credit + ($currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0);

                            $closingBalance = 0;
                            $closingBalanceSide = 'dr';
                            if ($currentDebit > $currentCredit) {

                                $closingBalance = $currentDebit - $currentCredit;
                            } elseif ($currentCredit > $currentDebit) {

                                $closingBalance = $currentCredit - $currentDebit;
                                $closingBalanceSide = 'cr';
                            }
                        @endphp

                        @if ($closingBalance > 0)
                            @php
                                $totalDebitOpeningBalance += ($currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0);
                                $totalCreditOpeningBalance += ($currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0);
                                $totalDebitClosingBalance += $closingBalanceSide == 'dr' ? $closingBalance : 0;
                                $totalCreditClosingBalance += $closingBalanceSide == 'cr' ? $closingBalance : 0;
                            @endphp

                            <tr class="account_list">
                                <td class="text-start ps-1"><b>{{ $account->account_name }}</b></td>
                                <td class="text-end debit_opening_balance fw-bold">{{ $currOpeningBalanceSide == 'dr' && $currOpeningBalance > 0 ? \App\Utils\Converter::format_in_bdt($currOpeningBalance) : '' }}</td>
                                <td class="text-end credit_opening_balance fw-bold">{{ $currOpeningBalanceSide == 'cr' && $currOpeningBalance > 0 ? \App\Utils\Converter::format_in_bdt($currOpeningBalance) : '' }}</td>
                                <td class="text-end debit_closing_balance fw-bold">{{ $closingBalanceSide == 'dr' ? App\Utils\Converter::format_in_bdt($closingBalance) : '' }}</td>
                                <td class="text-end credit_closing_balance fw-bold">{{ $closingBalanceSide == 'cr' ? App\Utils\Converter::format_in_bdt($closingBalance) : '' }}</td>
                            </tr>
                        @endif

                    @endforeach
                    @php
                        $differenceInOpeningBalance = 0;
                        $differenceInOpeningBalanceSide = 'dr';
                        if ($totalDebitClosingBalance > $totalCreditClosingBalance) {

                            $differenceInOpeningBalance = $totalDebitClosingBalance - $totalCreditClosingBalance;
                            $differenceInOpeningBalanceSide = 'dr';
                        }elseif($totalCreditClosingBalance > $totalDebitClosingBalance) {

                            $differenceInOpeningBalance = $totalCreditClosingBalance - $totalDebitClosingBalance;
                            $differenceInOpeningBalanceSide = 'cr';
                        }

                        $totalDebitOpeningBalance += $differenceInOpeningBalanceSide == 'cr' ? $differenceInOpeningBalance : 0;
                        $totalCreditOpeningBalance += $differenceInOpeningBalanceSide == 'dr' ? $differenceInOpeningBalance : 0;
                        $totalDebitClosingBalance += $differenceInOpeningBalanceSide == 'cr' ? $differenceInOpeningBalance : 0;
                        $totalCreditClosingBalance += $differenceInOpeningBalanceSide == 'dr' ? $differenceInOpeningBalance : 0;
                    @endphp
                    <tr class="difference_in_opening_balance_area">
                        <td class="text-start fw-bold" style="text-align: right!important;">@lang('menu.differenceInOpeningBalance') :</td>
                        <td class="text-end debit_amount fw-bold">{{ $differenceInOpeningBalanceSide == 'cr' ? \App\Utils\Converter::format_in_bdt($differenceInOpeningBalance) : '' }}</td>
                        <td class="text-end credit_amount fw-bold">{{ $differenceInOpeningBalanceSide == 'dr' ? \App\Utils\Converter::format_in_bdt($differenceInOpeningBalance) : '' }}</td>
                        <td class="text-end debit_amount fw-bold">{{ $differenceInOpeningBalanceSide == 'cr' ? \App\Utils\Converter::format_in_bdt($differenceInOpeningBalance) : '' }}</td>
                        <td class="text-end credit_amount fw-bold">{{ $differenceInOpeningBalanceSide == 'dr' ? \App\Utils\Converter::format_in_bdt($differenceInOpeningBalance) : '' }}</td>
                    </tr>
                </tbody>

                <tfoot class="net_total_balance_footer">
                    <tr>
                        <td class="text-end footer_total fw-bold" >@lang('menu.total') :</td>
                        <td class="text-end footer_total_debit fw-bold">{{ \App\Utils\Converter::format_in_bdt($totalDebitOpeningBalance) }}</td>
                        <td class="text-end footer_total_credit fw-bold">{{ \App\Utils\Converter::format_in_bdt($totalCreditOpeningBalance) }}</td>
                        <td class="text-end footer_total_debit fw-bold">{{ \App\Utils\Converter::format_in_bdt($totalDebitClosingBalance) }}</td>
                        <td class="text-end footer_total_credit fw-bold">{{ \App\Utils\Converter::format_in_bdt($totalCreditClosingBalance) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
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
