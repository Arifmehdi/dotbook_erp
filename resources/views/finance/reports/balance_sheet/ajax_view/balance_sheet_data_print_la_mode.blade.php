<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto, font-size:10px; }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 0px;margin-right: 0px;}
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
    td.debit_area {line-height: 15px;font-size:9px!important;}
    td.credit_area {line-height: 15px;font-size:9px!important;}
    /* font-family: sans-serif; */
    td.first_td {width: 72%;}
    .print_table td {font-size:11px!important;line-height: 17px!important;}
    .print_table th {font-size:11px!important;line-height: 17px!important;}
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
            <strong>@lang('menu.email') : </strong> <b>{{ json_decode($generalSettings->business, true)['email'] }}</b>, <strong>@lang('menu.phone') : </strong> <b>{{ json_decode($generalSettings->business, true)['phone'] }}</b>
        </p>
    </div>
</div>

<div class="row mt-2">
    <div class="col-12 text-center">
        <h6 style="text-transform:uppercase;"><strong>@lang('menu.balance_sheet') </strong></h6>
    </div>
</div>

<div class="row mt-1">
    <div class="col-12 text-center">
        @if ($fromDate && $toDate)
            <p>
                <strong>@lang('menu.from') : </strong>
                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($fromDate)) }}
                <strong>@lang('menu.to') : </strong> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($toDate)) }}
            </p>
        @endif
    </div>
</div>

<div class="row mt-2">
    <div class="col-12">
        <table class="w-100 print_table">
            <thead>
                <tr>
                    <th class="header_text ps-1" style="border: 1px solid black;">@lang('menu.liabilities')</th>
                    <th class="header_text ps-1" style="border: 1px solid black;">@lang('menu.assets')</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td class="credit_area" style="border-left:1px solid black;width: 50%;">
                        @if ($capitalAccount->closing_balance_side == 'cr')
                            <table class="capital_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $capitalAccount->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($capitalAccount->groups as $group)
                                                    @if ($group->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b>(@lang('menu.group'))</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    {{ $group->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                                </b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($capitalAccount->accounts as $account)
                                                    <tr>
                                                        <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b>(@lang('menu.ledger'))</td>
                                                        <td class="group_account_balance text-end">
                                                            <b>
                                                                {{ $account->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                                {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                            </b>
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

                        @if ($currentLiabilitiesAccount->closing_balance_side == 'cr')
                            <table class="current_liabilities_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $currentLiabilitiesAccount->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($currentLiabilitiesAccount->groups as $group)
                                                    @if ($group->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b>(@lang('menu.group'))</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    {{ $group->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                                </b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($currentLiabilitiesAccount->accounts as $account)
                                                    @if ($account->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1">{{ $account->account_name }}(@lang('menu.ledger'))</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    {{ $account->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                                </b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>
                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($currentLiabilitiesAccount->closing_balance) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($loanLiabilitiesAccount->closing_balance_side == 'cr')
                            <table class="loan_liabilities_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $loanLiabilitiesAccount->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($loanLiabilitiesAccount->groups as $group)
                                                    @if ($group->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b>(@lang('menu.group'))</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    {{ $group->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                                </b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($loanLiabilitiesAccount->accounts as $account)
                                                    @if ($account->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b>(@lang('menu.ledger'))</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    {{ $account->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                                </b>
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

                        @if ($branchAndDivisionsAccountBalance->closing_balance_side == 'cr')
                            <table class="loan_liabilities_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $branchAndDivisionsAccountBalance->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($branchAndDivisionsAccountBalance->groups as $group)
                                                    @if ($group->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b>(@lang('menu.group'))</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    {{ $group->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                                </b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($branchAndDivisionsAccountBalance->accounts as $account)
                                                    @if ($account->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b>(@lang('menu.group'))</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    {{ $account->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                                </b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($branchAndDivisionsAccountBalance->closing_balance) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($suspenseAccountBalance->closing_balance_side == 'cr')
                            <table class="loan_liabilities_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $suspenseAccountBalance->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($suspenseAccountBalance->groups as $group)
                                                    @if ($group->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b>(@lang('menu.group'))</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    {{ $group->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                                </b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($suspenseAccountBalance->accounts as $account)
                                                    @if ($account->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b>(@lang('menu.ledger'))</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    {{ $account->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                                </b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($suspenseAccountBalance->closing_balance) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($currentAssetAccount['currentAssetsAccounts']->closing_balance_side == 'cr')
                            <table class="current_assets_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $currentAssetAccount['currentAssetsAccounts']->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                <tr>
                                                    <td class="group_account_name ps-1">@lang('menu.closing_stock')</td>
                                                    <td class="group_account_balance text-end">
                                                        (-) {{ \App\Utils\Converter::format_in_bdt($currentAssetAccount['closingStock']) }}
                                                    </td>
                                                </tr>
                                                @foreach ($currentAssetAccount['currentAssetsAccounts']->groups as $group)
                                                    @if ($group->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b>(@lang('menu.group'))</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    {{ $group->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                                </b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($currentAssetAccount['currentAssetsAccounts']->accounts as $account)
                                                    @if ($account->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1">{{ $account->account_name }}(@lang('menu.group'))</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    {{ $account->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                                </b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($currentAssetAccount['currentAssetsAccounts']->closing_balance) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($investmentsAccount->closing_balance_side == 'cr')
                            <table class="investments_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $investmentsAccount->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($investmentsAccount->groups as $group)
                                                    @if ($group->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b>(@lang('menu.group'))</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    {{ $group->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                                </b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($investmentsAccount->accounts as $account)
                                                    @if ($account->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b>(@lang('menu.ledger'))</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    {{ $account->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                                </b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($investmentsAccount->closing_balance) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($fixedAssetsAccount->closing_balance_side == 'cr')
                            <table class="fixed_assets_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $fixedAssetsAccount->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($fixedAssetsAccount->groups as $group)
                                                    @if ($group->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b>(@lang('menu.group'))</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    {{ $group->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                                </b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($fixedAssetsAccount->accounts as $account)
                                                    @if ($account->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b>(@lang('menu.ledger'))</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    {{ $account->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                                </b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($fixedAssetsAccount->closing_balance) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($profitLossAccount['net_profit_loss_side'] == 'cr')
                            <table class="profit_loss_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $profitLossAccount['account_name'] }}</strong>
                                        <table class="group_account_table ms-2">
                                            <tr>
                                                <td class="group_account_name ps-1">@lang('menu.opening_balance')</td>
                                                <td class="group_account_balance text-end">
                                                    @if ($profitLossAccount['ac_opening_balance'] > 0)
                                                        <b>
                                                            {{ $profitLossAccount['ac_opening_balance_side'] == 'dr' ? '(-)' : '' }}
                                                            {{ \App\Utils\Converter::format_in_bdt($profitLossAccount['ac_opening_balance']) }}
                                                        </b>
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="group_account_name ps-1">@lang('menu.current_period')</td>
                                                <td class="group_account_balance text-end">
                                                    @if ($profitLossAccount['previous_profit_loss'] > 0)
                                                        <b>
                                                            {{ $profitLossAccount['previous_profit_loss_side'] == 'dr' ? '(-)' : '' }}
                                                            {{ \App\Utils\Converter::format_in_bdt($profitLossAccount['previous_profit_loss']) }}
                                                        </b>
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="group_account_name ps-1">@lang('menu.less_transferred')</td>
                                                <td class="group_account_balance text-end">
                                                    @if ($profitLossAccount['ac_transferred_balance'] > 0)
                                                        <b>
                                                            {{ $profitLossAccount['ac_transferred_balance_side'] == 'dr' ? '(-)' : '' }}
                                                            {{ \App\Utils\Converter::format_in_bdt($profitLossAccount['ac_transferred_balance']) }}
                                                        </b>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($profitLossAccount['net_profit_loss']) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($differencesInOpeningBalanceSide == 'dr')
                            <table class="differences_in_opening_blance_table w-100 mt-1">
                                <tr>
                                    <td>
                                        <strong class="ps-2 text-end">@lang('menu.differenceInOpeningBalance') :</strong>
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($differenceInOpeningBalance) }}</strong></td>
                                </tr>
                            </table>
                        @endif
                    </td>

                    <td class="debit_area" style="border-left: 1px solid #000;border-right:1px solid #000">
                        @if ($capitalAccount->closing_balance_side == 'dr')
                            <table class="capital_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $capitalAccount->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($capitalAccount->groups as $group)
                                                    @if ($group->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b>(@lang('menu.group'))</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    {{ $group->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                                </b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($capitalAccount->accounts as $account)
                                                    @if ($account->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}(@lang('menu.ledger'))</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    {{ $account->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                                </b>
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

                        @if ($currentLiabilitiesAccount->closing_balance_side == 'dr')
                            <table class="current_liabilities_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $currentLiabilitiesAccount->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($currentLiabilitiesAccount->groups as $group)
                                                    @if ($group->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b>(@lang('menu.group'))</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    {{ $group->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                                </b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($currentLiabilitiesAccount->accounts as $account)
                                                    @if ($account->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b>(@lang('menu.ledger'))</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    {{ $account->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                                </b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>
                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($currentLiabilitiesAccount->closing_balance) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($loanLiabilitiesAccount->closing_balance_side == 'dr')
                            <table class="loan_liabilities_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $loanLiabilitiesAccount->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($loanLiabilitiesAccount->groups as $group)
                                                    @if ($group->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b>(@lang('menu.group'))</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    {{ $group->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                                </b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($loanLiabilitiesAccount->accounts as $account)
                                                    @if ($account->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b>(@lang('menu.ledger'))</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    {{ $account->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                                </b>
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

                        @if ($branchAndDivisionsAccountBalance->closing_balance_side == 'dr')
                            <table class="loan_liabilities_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $branchAndDivisionsAccountBalance->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($branchAndDivisionsAccountBalance->groups as $group)
                                                    @if ($group->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b>(@lang('menu.group'))</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    {{ $group->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                                </b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($branchAndDivisionsAccountBalance->accounts as $account)
                                                    @if ($account->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b>(@lang('menu.ledger'))</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    {{ $account->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                                </b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($branchAndDivisionsAccountBalance->closing_balance) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($suspenseAccountBalance->closing_balance_side == 'dr')
                            <table class="loan_liabilities_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $suspenseAccountBalance->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($suspenseAccountBalance->groups as $group)
                                                    @if ($group->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b>(@lang('menu.group'))</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    {{ $group->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                                </b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($suspenseAccountBalance->accounts as $account)
                                                    @if ($account->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b>(@lang('menu.ledger'))</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    {{ $account->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                                </b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($suspenseAccountBalance->closing_balance) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($fixedAssetsAccount->closing_balance_side == 'dr')
                            <table class="fixed_assets_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $fixedAssetsAccount->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($fixedAssetsAccount->groups as $group)
                                                    @if ($group->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b>(@lang('menu.group'))</td>
                                                            <td class="group_account_balance text-end">
                                                                {{ $group->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($fixedAssetsAccount->accounts as $account)
                                                    @if ($account->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b>(@lang('menu.ledger'))</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    {{ $account->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                                </b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($fixedAssetsAccount->closing_balance) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($investmentsAccount->closing_balance_side == 'dr')
                            <table class="investments_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $investmentsAccount->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($investmentsAccount->groups as $group)
                                                    @if ($group->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b>(@lang('menu.group'))</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    <b>
                                                                        {{ $account->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                        {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                                    </b>
                                                                </b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($investmentsAccount->accounts as $account)
                                                    @if ($account->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b>(@lang('menu.ledger'))</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    {{ $account->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                    {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                                </b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($investmentsAccount->closing_balance) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($currentAssetAccount['currentAssetsAccounts']->closing_balance_side == 'dr')
                            <table class="current_assets_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $currentAssetAccount['currentAssetsAccounts']->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                <tr>
                                                    <td class="group_account_name ps-1">@lang('menu.closing_stock') </td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($currentAssetAccount['closingStock']) }}
                                                    </td>
                                                </tr>
                                                @foreach ($currentAssetAccount['currentAssetsAccounts']->groups as $group)
                                                    @if ($group->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b>(@lang('menu.group'))</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    {{ $group->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                                </b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($currentAssetAccount['currentAssetsAccounts']->accounts as $account)
                                                    @if ($account->closing_balance)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b>(@lang('menu.ledger'))</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>
                                                                    {{ $account->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                                    {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                                </b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($currentAssetAccount['currentAssetsAccounts']->closing_balance) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($profitLossAccount['net_profit_loss_side'] == 'dr')
                            <table class="profit_loss_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $profitLossAccount['account_name'] }}</strong>
                                        <table class="group_account_table ms-2">
                                            <tr>
                                                <td class="group_account_name ps-1">@lang('menu.opening_balance')</td>
                                                <td class="group_account_balance text-end">
                                                    @if ($profitLossAccount['ac_opening_balance'] > 0)
                                                        <b>
                                                            {{ $profitLossAccount['ac_opening_balance_side'] == 'cr' ? '(-)' : '' }}
                                                            {{ \App\Utils\Converter::format_in_bdt($profitLossAccount['ac_opening_balance']) }}
                                                        </b>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="group_account_name ps-1">@lang('menu.current_period')</td>
                                                <td class="group_account_balance text-end">
                                                    @if ($profitLossAccount['previous_profit_loss'] > 0)
                                                        <b>
                                                            {{ $profitLossAccount['previous_profit_loss_side'] == 'cr' ? '(-)' : '' }}
                                                            {{ \App\Utils\Converter::format_in_bdt($profitLossAccount['previous_profit_loss']) }}
                                                        </b>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="group_account_name ps-1">@lang('menu.less_transferred')</td>
                                                <td class="group_account_balance text-end">
                                                    @if ($profitLossAccount['ac_transferred_balance'] > 0)
                                                        <b>
                                                            {{ $profitLossAccount['ac_transferred_balance_side'] == 'cr' ? '(-)' : '' }}
                                                            {{ \App\Utils\Converter::format_in_bdt($profitLossAccount['ac_transferred_balance']) }}
                                                        </b>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($profitLossAccount['net_profit_loss']) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($differencesInOpeningBalanceSide == 'cr')
                            <table class="differences_in_opening_blance_table w-100 mt-1">
                                <tr>
                                    <td>
                                        <strong class="ps-2 text-end">@lang('menu.differenceInOpeningBalance') :</strong>
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($differenceInOpeningBalance) }}</strong></td>
                                </tr>
                            </table>
                        @endif
                    </td>
                </tr>
            </tbody>

            <tfoot class="net_total_balance_footer">
                <tr>
                    <td class="text-end fw-bold net_debit_total" style="border-left:1px solid black;">@lang('menu.total') : {{ \App\Utils\Converter::format_in_bdt($totalDebit) }} </td>
                    <td class="text-end fw-bold net_credit_total" style="border-right:1px solid black;">@lang('menu.total') : {{ \App\Utils\Converter::format_in_bdt($totalCredit) }}</td>
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
