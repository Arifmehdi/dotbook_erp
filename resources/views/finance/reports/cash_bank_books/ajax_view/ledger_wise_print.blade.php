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
    th, td { text-align: left; vertical-align: baseline; }
    table {border: none!important;}

    .net_total_balance_footer tr {border-top: 1px solid; border-bottom: 1px solid;line-height: 16px;}
    td.trial_balance_area {line-height: 17px!important;}
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

<div class="row mt-1">
    <div class="col-12 text-center">
        <h6 class="text-uppercase"><strong>@lang('menu.cash_bank_books') </strong></h6>
    </div>
</div>

<div class="row">
    <div class="col-12 text-center">
        @if ($fromDate && $toDate)
            <p><strong>@lang('menu.from') :</strong>
                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($fromDate)) }}
                <strong>@lang('menu.to') :</strong> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($toDate)) }}
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
                        <th class="header_text ps-1 text-start">@lang('menu.particulars')</th>
                        <th class="header_text ps-1 text-end">@lang('menu.opening_balance')</th>
                        <th class="header_text ps-1 text-end">@lang('menu.debit')</th>
                        <th class="header_text ps-1 text-end">@lang('menu.credit')</th>
                        <th class="header_text ps-1 text-end">@lang('menu.closing_balance')</th>
                    </tr>
                </thead>

                @php
                    $totalDebitOpeningBalance = 0;
                    $totalCreditOpeningBalance = 0;
                    $totalDebitTransaction = 0;
                    $totalCreditTransaction = 0;
                    $totalDebitClosingBalance = 0;
                    $totalCreditClosingBalance = 0;
                @endphp

                <tbody class="trial_balance_main_table_body">
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

                            $__currentDebit = $account->curr_total_debit;
                            $__currentCredit = $account->curr_total_credit;

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
                                $totalDebitOpeningBalance += $currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0;
                                $totalCreditOpeningBalance += $currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0;
                                $totalDebitTransaction += $__currentDebit;
                                $totalCreditTransaction +=  $__currentCredit;
                                $totalDebitClosingBalance += $closingBalanceSide == 'dr' ? $closingBalance : 0;
                                $totalCreditClosingBalance += $closingBalanceSide == 'cr' ? $closingBalance : 0;
                            @endphp

                            <tr class="account_list">
                                <td class="text-start ps-1">{{ $account->account_name }}</td>

                                @if ($currOpeningBalance > 0)

                                    <td class="text-end debit_opening_balance fw-bold">
                                        {{ \App\Utils\Converter::format_in_bdt($currOpeningBalance) }}
                                        {{ $currOpeningBalanceSide == 'dr' ? 'Dr.' : 'Cr.' }}
                                    </td>
                                @else

                                    <td class="text-end debit_opening_balance fw-bold"></td>
                                @endif

                                @if ($__currentDebit > 0)

                                    <td class="text-end credit_opening_balance fw-bold">{{ \App\Utils\Converter::format_in_bdt($__currentDebit) }}</td>
                                @else

                                    <td class="text-end debit_opening_balance fw-bold"></td>
                                @endif

                                @if ($__currentCredit > 0)

                                    <td class="text-end credit_opening_balance fw-bold">{{ \App\Utils\Converter::format_in_bdt($__currentCredit) }}</td>
                                @else

                                    <td class="text-end debit_opening_balance fw-bold"></td>
                                @endif

                                <td class="text-end debit_opening_balance fw-bold">
                                    {{ \App\Utils\Converter::format_in_bdt($closingBalance) }}
                                    {{ $closingBalanceSide == 'dr' ? 'Dr.' : 'Cr.' }}
                                </td>
                            </tr>
                        @endif
                    @endforeach

                    @php
                        $openingBalance = 0;
                        $openingBalanceSide = 'dr';

                        if ($totalDebitOpeningBalance > $totalCreditOpeningBalance) {

                            $openingBalance = $totalDebitOpeningBalance - $totalCreditOpeningBalance;
                            $openingBalanceSide = 'dr';
                        }elseif ($totalCreditOpeningBalance > $totalDebitOpeningBalance) {

                            $openingBalance = $totalCreditOpeningBalance - $totalDebitOpeningBalance;
                            $openingBalanceSide = 'cr';
                        }

                        $closingBalance = 0;
                        $closingBalanceSide = 'dr';

                        if ($totalDebitClosingBalance > $totalCreditClosingBalance) {

                            $closingBalance = $totalDebitClosingBalance - $totalCreditClosingBalance;
                            $closingBalanceSide = 'dr';
                        }elseif ($totalCreditClosingBalance > $totalDebitClosingBalance) {

                            $closingBalance = $totalCreditClosingBalance - $totalDebitClosingBalance;
                            $closingBalanceSide = 'cr';
                        }
                    @endphp
                </tbody>

                <tfoot class="net_total_balance_footer">
                    <tr>
                        <td class="text-end footer_total fw-bold" >@lang('menu.grand_total') :</td>
                        <td class="text-end footer_total_debit fw-bold">
                            {{ \App\Utils\Converter::format_in_bdt($openingBalance) }}
                            {{ $openingBalanceSide == 'dr' ? 'Dr.' : 'Cr.' }}
                        </td>
                        <td class="text-end footer_total_credit fw-bold">{{ \App\Utils\Converter::format_in_bdt($totalDebitTransaction) }}</td>
                        <td class="text-end footer_total_debit fw-bold">{{ \App\Utils\Converter::format_in_bdt($totalCreditTransaction) }}</td>
                        <td class="text-end footer_total_credit fw-bold">
                            {{ \App\Utils\Converter::format_in_bdt($closingBalance) }}
                            {{ $closingBalanceSide == 'dr' ? 'Dr.' : 'Cr.' }}
                        </td>
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
