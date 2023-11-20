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
        th, td { text-align: left; vertical-align: baseline; }
        table.group_account_table tr {line-height: 16px;}
        table {border: none!important;}
        table.gross_total_balance tr {border-top: 1px solid; border-bottom: 1px solid;line-height: 16px;}
        .net_total_balance_footer tr {border-top: 1px solid; border-bottom: 1px solid;line-height: 16px;}
        .net_credit_total {border-left: 1px solid #000;}
        td.debit_area {line-height: 17px;}
        td.credit_area {line-height: 17px;}
        /* font-family: sans-serif; */
        td.first_td {width: 72%;}
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
        <h6 style="text-transform:uppercase;"><strong>@lang('menu.fund_flow') </strong></h6>
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
        <table class="w-100">
            <thead>
                <tr>
                    <th class="header_text ps-1" style="border: 1px solid black;">@lang('menu.sources')</th>
                    <th class="header_text ps-1" style="border: 1px solid black;">@lang('menu.applications')</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td class="credit_area" style="border: 1px solid black;width: 50%;">
                        @if ($capitalAccount->closing_balance_side == 'cr' && $capitalAccount->closing_balance > 0)
                            <table class="capital_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $capitalAccount->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($capitalAccount->groups as $group)
                                                    @if ($group->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1">{{ $group->group_name }}</td>
                                                            <td class="group_account_balance text-end">
                                                                {{ $group->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                                {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($capitalAccount->accounts as $account)
                                                    <tr>
                                                        <td class="group_account_name ps-1">{{ $account->account_name }}</td>
                                                        <td class="group_account_balance text-end">
                                                            {{ $group->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                            {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($capitalAccount->closing_balance) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($loanLiabilitiesAccount->closing_balance_side == 'cr' && $loanLiabilitiesAccount->closing_balance > 0)
                            <table class="loan_liabilities_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $loanLiabilitiesAccount->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($loanLiabilitiesAccount->groups as $group)
                                                    @if ($group->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1">{{ $group->group_name }}</td>
                                                            <td class="group_account_balance text-end">
                                                                {{ $group->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                                {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($loanLiabilitiesAccount->accounts as $account)
                                                    @if ($account->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1">{{ $account->account_name }}</td>
                                                            <td class="group_account_balance text-end">
                                                                {{ $account->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                                {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($loanLiabilitiesAccount->closing_balance) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($branchAndDivisionsAccount->closing_balance_side == 'cr' && $branchAndDivisionsAccount->closing_balance > 0)
                            <table class="branch_and_divisions_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $branchAndDivisionsAccount->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($branchAndDivisionsAccount->groups as $group)
                                                    @if ($group->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1">{{ $group->group_name }}</td>
                                                            <td class="group_account_balance text-end">
                                                                {{ $group->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                                {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($branchAndDivisionsAccount->accounts as $account)
                                                    @if ($account->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1">{{ $account->account_name }}</td>
                                                            <td class="group_account_balance text-end">
                                                                {{ $account->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                                {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($branchAndDivisionsAccount->closing_balance) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($suspenseAccount->closing_balance_side == 'cr' && $suspenseAccount->closing_balance > 0)
                            <table class="suspense_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $suspenseAccount->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($suspenseAccount->groups as $group)
                                                    @if ($group->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1">{{ $group->group_name }}</td>
                                                            <td class="group_account_balance text-end">
                                                                {{ $group->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                                {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($suspenseAccount->accounts as $account)
                                                    @if ($account->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1">{{ $account->account_name }}</td>
                                                            <td class="group_account_balance text-end">
                                                                {{ $account->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                                {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($suspenseAccount->closing_balance) }}</strong></td>
                                </tr>
                            </table>
                        @endif
                    </td>

                    <td class="debit_area" style="border: 1px solid black;">
                        @if ($capitalAccount->closing_balance_side == 'dr' && $capitalAccount->closing_balance > 0)
                            <table class="capital_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $capitalAccount->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($capitalAccount->groups as $group)
                                                    @if ($group->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1">{{ $group->group_name }}</td>
                                                            <td class="group_account_balance text-end">
                                                                {{ $group->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($capitalAccount->accounts as $account)
                                                    @if ($account->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1">{{ $account->account_name }}</td>
                                                            <td class="group_account_balance text-end">
                                                                {{ $group->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($capitalAccount->closing_balance) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($loanLiabilitiesAccount->closing_balance_side == 'dr' && $loanLiabilitiesAccount->closing_balance > 0)
                            <table class="loan_liabilities_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $loanLiabilitiesAccount->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($loanLiabilitiesAccount->groups as $group)
                                                    @if ($group->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1">{{ $group->group_name }}</td>
                                                            <td class="group_account_balance text-end">
                                                                {{ $group->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($loanLiabilitiesAccount->accounts as $account)
                                                    @if ($account->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1">{{ $account->account_name }}</td>
                                                            <td class="group_account_balance text-end">
                                                                {{ $account->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($loanLiabilitiesAccount->closing_balance) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($branchAndDivisionsAccount->closing_balance_side == 'dr' && $branchAndDivisionsAccount->closing_balance > 0)
                            <table class="branch_and_divisions_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $branchAndDivisionsAccount->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($branchAndDivisionsAccount->groups as $group)
                                                    @if ($group->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1">{{ $group->group_name }}</td>
                                                            <td class="group_account_balance text-end">
                                                                {{ $group->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($branchAndDivisionsAccount->accounts as $account)
                                                    @if ($account->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1">{{ $account->account_name }}</td>
                                                            <td class="group_account_balance text-end">
                                                                {{ $account->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($branchAndDivisionsAccount->closing_balance) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($suspenseAccount->closing_balance_side == 'dr' && $suspenseAccount->closing_balance > 0)
                            <table class="suspense_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $suspenseAccount->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($suspenseAccount->groups as $group)
                                                    @if ($group->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1">{{ $group->group_name }}</td>
                                                            <td class="group_account_balance text-end">
                                                                {{ $group->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($suspenseAccount->accounts as $account)
                                                    @if ($account->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1">{{ $account->account_name }}</td>
                                                            <td class="group_account_balance text-end">
                                                                {{ $account->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($suspenseAccount->closing_balance) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        <table class="profit_loss_account_group_table w-100 mt-1">
                            <tr>
                                <td class="first_td">
                                    <strong class="ps-2">
                                        @if ($netProfitLoss['netProfitLossSide'] == 'cr')
                                            @lang('menu.net_profit')
                                        @elseif($netProfitLoss['netProfitLossSide'] == 'dr')
                                            @lang('menu.net_loss')
                                        @endif
                                    </strong>
                                </td>

                                <td class="text-end">
                                    <strong>
                                        @if ($netProfitLoss['netProfitLossSide'] == 'cr')
                                            {{ \App\Utils\Converter::format_in_bdt($netProfitLoss['netProfit']) }}
                                        @elseif($netProfitLoss['netProfitLossSide'] == 'dr')
                                            {{ \App\Utils\Converter::format_in_bdt($netProfitLoss['netLoss']) }}
                                        @endif
                                    </strong>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>

            <tfoot class="net_total_balance_footer">
                <tr>
                    <td class="text-end fw-bold net_credit_total" style="border: 1px solid black;">@lang('menu.total') : {{ \App\Utils\Converter::format_in_bdt($totalCredit) }}</td>
                    <td class="text-end fw-bold net_debit_total" style="border: 1px solid black;">@lang('menu.total') : {{ \App\Utils\Converter::format_in_bdt($totalDebit) }}</td>
                </tr>

                <tr>
                    <td colspan="2">
                        <table class="w-100">
                            <thead>
                                <tr>
                                    <th class="header_text ps-1 text-start w-50">@lang('menu.particulars')</th>
                                    <th class="header_text ps-1 text-end">@lang('menu.closing_balance')</th>
                                    <th class="header_text ps-1 text-end">@lang('menu.wkg_cap_increase')</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-bold text-start w-50 ps-1" style="border-left: 1px solid black;">{{ $currentAssetAccount->main_group_name }}</td>
                                    <td class="fw-bold text-end">{{ \App\Utils\Converter::format_in_bdt($currentAssetAccount->closing_balance) }}
                                        {{ ucfirst($currentAssetAccount->closing_balance_side) }}.</td>
                                    <td class="fw-bold text-end" style="border-right: 1px solid black;">
                                        {{ $currentAssetAccount->closing_balance_side == 'cr' ? '(-)' : '' }}
                                        {{ \App\Utils\Converter::format_in_bdt($currentAssetAccount->closing_balance) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="fw-bold text-start w-50 ps-1" style="border-left: 1px solid black;">{{ $currentLiabilitiesAccount->main_group_name }}</td>
                                    <td class="fw-bold text-end">{{ \App\Utils\Converter::format_in_bdt($currentLiabilitiesAccount->closing_balance) }}
                                        {{ ucfirst($currentLiabilitiesAccount->closing_balance_side) }}.</td>
                                    <td class="fw-bold text-end" style="border-right: 1px solid black;">
                                        {{ $currentLiabilitiesAccount->closing_balance_side == 'cr' ? '(-)' : '' }}
                                        {{ \App\Utils\Converter::format_in_bdt(abs($currentLiabilitiesAccount->closing_balance)) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="fw-bold text-start w-50 ps-1" style="border-left: 1px solid black;">@lang('menu.working_capital')</td>
                                    @php
                                        $debitWkgCap = 0;
                                        $creditWkgCap = 0;
                                        $debitWkgCap += $currentAssetAccount->closing_balance_side == 'dr' ? $currentAssetAccount->closing_balance : 0;
                                        $debitWkgCap += $currentLiabilitiesAccount->closing_balance_side == 'dr' ? $currentLiabilitiesAccount->closing_balance : 0;
                                        $creditWkgCap += $currentAssetAccount->closing_balance_side == 'cr' ? $currentAssetAccount->closing_balance : 0;
                                        $creditWkgCap += $currentLiabilitiesAccount->closing_balance_side == 'cr' ? $currentLiabilitiesAccount->closing_balance : 0;

                                        $wkgBalance = 0;
                                        $wkgBalanceSide = 'dr';
                                        if ($debitWkgCap > $creditWkgCap) {

                                            $wkgBalance = $debitWkgCap - $creditWkgCap;
                                            $wkgBalanceSide = 'dr';
                                        }elseif ($creditWkgCap > $debitWkgCap) {

                                            $wkgBalance = $creditWkgCap - $debitWkgCap;
                                            $wkgBalanceSide = 'cr';
                                        }
                                    @endphp
                                    <td class="fw-bold text-end">{{ \App\Utils\Converter::format_in_bdt($wkgBalance) }} {{ ucfirst($wkgBalanceSide) }}.</td>
                                    <td class="fw-bold text-end" style="border-right: 1px solid black;">{{ $wkgBalanceSide == 'cr' ? '(-)' : '' }} {{ \App\Utils\Converter::format_in_bdt($wkgBalance) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
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
                <small>@lang('menu.powered_by') <b>@lang('menu.speedDigit_software_solution').</b></small>
            @endif
        </div>

        <div class="col-4 text-end">
            <small>@lang('menu.print_time') :{{ date($timeFormat) }}</small>
        </div>
    </div>
</div>
