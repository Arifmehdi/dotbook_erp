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

    th { font-size:11px!important; font-weight: 550!important;}
    td { font-size:10px;}

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
    .trial_balance_area tbody tr td {line-height: 16px;}
    .footer_total {font-size: 14px!important;}
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
    <p><strong>@lang('menu.type_of_grouping') : </strong><b>@lang('menu.group_wise')</b></p>
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

                <tbody>
                    @php
                        $totalDebitOpeningBalance = 0;
                        $totalCreditOpeningBalance = 0;
                        $totalDebitTransaction = 0;
                        $totalCreditTransaction = 0;
                        $totalDebitClosingBalance = 0;
                        $totalCreditClosingBalance = 0;
                    @endphp
                    @foreach ($mainGroups as $mainGroup)

                        @if ($mainGroup->closing_balance > 0)

                            @php
                                $totalDebitOpeningBalance += $mainGroup->opening_balance_side == 'dr' ? $mainGroup->opening_balance : 0;
                                $totalCreditOpeningBalance += $mainGroup->opening_balance_side == 'cr' ? $mainGroup->opening_balance : 0;
                                $totalDebitTransaction += $mainGroup->curr_total_debit;
                                $totalCreditTransaction += $mainGroup->curr_total_credit;
                                $totalDebitClosingBalance += $mainGroup->closing_balance_side == 'dr' ? $mainGroup->closing_balance : 0;
                                $totalCreditClosingBalance += $mainGroup->closing_balance_side == 'cr' ? $mainGroup->closing_balance : 0;
                            @endphp

                            <tr class="group_tr">
                                <td class="fw-bold" style="font-size: 13px!important;">
                                    {{ $mainGroup->name }}
                                </td>

                                @if ($mainGroup->opening_balance > 0)

                                    <td class="text-end fw-bold" style="border-bottom: 1px solid black!important;">
                                        {{ \App\Utils\Converter::format_in_bdt($mainGroup->opening_balance) }}
                                        {{ $mainGroup->opening_balance_side == 'dr' ? 'Dr.' : 'Cr.' }}
                                    </td>
                                @else
                                    <td class="text-end fw-bold"></td>
                                @endif

                                @if ($mainGroup->curr_total_debit > 0)
                                    <td class="text-end fw-bold" style="border-bottom: 1px solid black!important;">
                                        {{ \App\Utils\Converter::format_in_bdt($mainGroup->curr_total_debit) }}
                                    </td>
                                @else
                                    <td class="text-end fw-bold"></td>
                                @endif

                                @if ($mainGroup->curr_total_credit > 0)

                                    <td class="text-end fw-bold" style="border-bottom: 1px solid black!important;">
                                        {{ \App\Utils\Converter::format_in_bdt($mainGroup->curr_total_credit) }}
                                    </td>
                                @else

                                    <td class="text-end fw-bold"></td>
                                @endif

                                <td class="text-end fw-bold" style="border-bottom: 1px solid black!important;">
                                    {{ \App\Utils\Converter::format_in_bdt($mainGroup->closing_balance) }}
                                    {{ $mainGroup->closing_balance_side == 'dr' ? 'Dr.' : 'Cr.' }}
                                </td>
                            </tr>

                            @foreach ($mainGroup->subgroupsAccountsForOthers as $group)

                                @if ($group->closing_balance > 0)

                                    <tr class="group_tr">
                                        <td>
                                            <b>{{ $group->name }} <span class="text-black">(@lang('menu.group'))</span></b>
                                        </td>

                                        @if ($group->opening_balance > 0)

                                            <td class="text-end">
                                                <b>
                                                    {{ \App\Utils\Converter::format_in_bdt($group->opening_balance) }}
                                                    {{ $group->opening_balance_side == 'dr' ? 'Dr.' : 'Cr.' }}
                                                </b>
                                            </td>
                                        @else

                                            <td class="text-end"></td>
                                        @endif

                                        @if ($group->curr_total_debit > 0)

                                            <td class="text-end"><b>{{ \App\Utils\Converter::format_in_bdt($group->curr_total_debit) }}</b></td>
                                        @else

                                            <td class="text-end"></td>
                                        @endif

                                        @if ($group->curr_total_credit > 0)

                                            <td class="text-end"><b>{{ \App\Utils\Converter::format_in_bdt($group->curr_total_credit) }}</b></td>
                                        @else

                                            <td class="text-end"></td>
                                        @endif

                                        <td class="text-end">
                                            {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                            {{ $group->closing_balance_side == 'dr' ? 'Dr.' : 'Cr.' }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                            @foreach ($mainGroup->accounts as $account)

                                @if ($account->closing_balance > 0)

                                    <tr class="account_tr">
                                        <td>
                                            <b>{{ $account->name }} (@lang('menu.ledger'))</b>
                                        </td>

                                        @if ($account->opening_balance > 0)

                                            <td class="text-end">
                                                <b>
                                                    {{ \App\Utils\Converter::format_in_bdt($account->opening_balance) }}
                                                    {{ $account->opening_balance_side == 'dr' ? 'Dr.' : 'Cr.' }}
                                                </b>
                                            </td>
                                        @else

                                            <td class="text-end"></td>
                                        @endif

                                        @if ($account->curr_total_debit > 0)

                                            <td class="text-end"><b>{{ \App\Utils\Converter::format_in_bdt($account->curr_total_debit) }}</b></td>
                                        @else

                                            <td class="text-end"></td>
                                        @endif

                                        @if ($account->curr_total_credit > 0)

                                            <td class="text-end"><b>{{ \App\Utils\Converter::format_in_bdt($account->curr_total_credit) }}</b></td>
                                        @else

                                            <td class="text-end"></td>
                                        @endif

                                        <td class="text-end">
                                            <b>
                                                {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                {{ $account->closing_balance_side == 'dr' ? 'Dr.' : 'Cr.' }}
                                            </b>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </tbody>

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

                <tfoot class="net_total_balance_footer">
                    <tr>
                        <td class="text-start fw-bold">@lang('menu.grand_total') :</td>
                        <td class="text-end fw-bold net_opening_total">
                            {{ \App\Utils\Converter::format_in_bdt($openingBalance) }}
                            {{ $openingBalanceSide == 'dr' ? 'Dr.' : 'Cr.' }}
                        </td>
                        <td class="text-end fw-bold net_debit_total">
                            {{ \App\Utils\Converter::format_in_bdt($totalDebitTransaction) }}
                        </td>
                        <td class="text-end fw-bold net_credit_total">
                            {{ \App\Utils\Converter::format_in_bdt($totalCreditTransaction) }}
                        </td>
                        <td class="text-end fw-bold net_closing_total">
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
