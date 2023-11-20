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
        td.credit_area {border-left: 1px solid #000;}
        table.gross_total_balance tr {border-top: 1px solid; border-bottom: 1px solid;line-height: 16px;}
        .net_total_balance_footer tr {border-top: 1px solid; border-bottom: 1px solid;line-height: 16px;}
        .net_credit_total {border-left: 1px solid #000;}
        td.debit_area {line-height: 17px;}
        td.credit_area {line-height: 17px;}
        /* font-family: sans-serif; */
        td.first_td {width: 72%;}
        .print_table td {font-size:12px!important;line-height: 13px!important;}
        .print_table th {font-size:12px!important;line-height: 13px!important;}
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
        <h6 style="text-transform:uppercase;"><strong>@lang('menu.cash_flow') </strong></h6>
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

<div class="row mt-2">
    <div class="col-12">
        <table class="w-100 print_table">
            <thead>
                <tr>
                    <th class="header_text ps-1 text-center" style="border: 1px solid black;">@lang('menu.INFLOW')</th>
                    <th class="header_text ps-1 text-center" style="border: 1px solid black;">@lang('menu.OUTFLOW')</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td class="inflow_area" style="border-left:1px solid black;width: 50%;">
                        @if ($capitalAccountCashFlows->cash_in > 0)
                            <table class="capital_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $capitalAccountCashFlows->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($capitalAccountCashFlows->groups as $group)
                                                    @if ($group->cash_in > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($capitalAccountCashFlows->accounts as $account)
                                                    @if ($account->cash_in > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($capitalAccountCashFlows->cash_in) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($branchAndDivisionCashFlows->cash_in > 0)
                            <table class="branch_and_division_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $branchAndDivisionCashFlows->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($branchAndDivisionCashFlows->groups as $group)
                                                    @if ($group->cash_in > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($branchAndDivisionCashFlows->accounts as $account)
                                                    @if ($account->cash_in > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($branchAndDivisionCashFlows->cash_in) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($suspenseAccountCashFlows->cash_in > 0)
                            <table class="suspense_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $suspenseAccountCashFlows->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($suspenseAccountCashFlows->groups as $group)
                                                    @if ($group->cash_in > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($suspenseAccountCashFlows->accounts as $account)
                                                    @if ($account->cash_in > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($suspenseAccountCashFlows->cash_in) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($currentLiabilitiesCashFlows->cash_in > 0)
                            <table class="current_liabilities_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $currentLiabilitiesCashFlows->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($currentLiabilitiesCashFlows->groups as $group)
                                                    @if ($group->cash_in > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($currentLiabilitiesCashFlows->accounts as $account)
                                                    @if ($account->cash_in > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>
                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($currentLiabilitiesCashFlows->cash_in) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($loanLiabilitiesCashFlows->cash_in > 0)
                            <table class="loan_liabilities_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $loanLiabilitiesCashFlows->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($loanLiabilitiesCashFlows->groups as $group)
                                                    @if ($group->cash_in > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($loanLiabilitiesCashFlows->accounts as $account)
                                                    @if ($account->cash_in > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>
                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($loanLiabilitiesCashFlows->cash_in) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($currentAssetsCashFlows->cash_in > 0)
                            <table class="current_assets_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $currentAssetsCashFlows->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($currentAssetsCashFlows->groups as $group)
                                                    @if ($group->cash_in > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($currentAssetsCashFlows->accounts as $account)
                                                    @if ($account->cash_in > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>
                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($currentAssetsCashFlows->cash_in) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($fixedAssetsCashFlows->cash_in > 0)
                            <table class="fixed_assets_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $fixedAssetsCashFlows->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($fixedAssetsCashFlows->groups as $group)
                                                    @if ($group->cash_in > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($fixedAssetsCashFlows->accounts as $account)
                                                    @if ($account->cash_in > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>
                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($fixedAssetsCashFlows->cash_in) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($investmentsCashFlows->cash_in > 0)
                            <table class="investments_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $investmentsCashFlows->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($investmentsCashFlows->groups as $group)
                                                    @if ($group->cash_in > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($investmentsCashFlows->accounts as $account)
                                                    @if ($account->cash_in > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>
                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($investmentsCashFlows->cash_in) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($directExpenseCashFlows->cash_in > 0)
                            <table class="direct_expense_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $directExpenseCashFlows->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($directExpenseCashFlows->groups as $group)
                                                    @if ($group->cash_in > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($directExpenseCashFlows->accounts as $account)
                                                    @if ($account->cash_in > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>
                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($directExpenseCashFlows->cash_in) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($indirectExpenseCashFlows->cash_in > 0)
                            <table class="indirect_expense_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $indirectExpenseCashFlows->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($indirectExpenseCashFlows->groups as $group)
                                                    @if ($group->cash_in > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($indirectExpenseCashFlows->accounts as $account)
                                                    @if ($account->cash_in > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>
                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($indirectExpenseCashFlows->cash_in) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($directIncomeCashFlows->cash_in > 0)
                            <table class="direct_income_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $directIncomeCashFlows->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($directIncomeCashFlows->groups as $group)
                                                    @if ($group->cash_in > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($directIncomeCashFlows->accounts as $account)
                                                    @if ($account->cash_in > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>
                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($directIncomeCashFlows->cash_in) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($indirectIncomeCashFlows->cash_in > 0)
                            <table class="indirect_income_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $indirectIncomeCashFlows->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($indirectIncomeCashFlows->groups as $group)
                                                    @if ($group->cash_in > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($indirectIncomeCashFlows->accounts as $account)
                                                    @if ($account->cash_in > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>
                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($indirectIncomeCashFlows->cash_in) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($salesAccountCashFlows->cash_in > 0)
                            <table class="sales_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $salesAccountCashFlows->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($salesAccountCashFlows->groups as $group)
                                                    @if ($group->cash_in > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($salesAccountCashFlows->accounts as $account)
                                                    @if ($account->cash_in > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1">{{ $account->account_name }}</td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>
                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($salesAccountCashFlows->cash_in) }}</strong></td>
                                </tr>
                            </table>
                        @endif
                    </td>

                    <td class="outflow_area" style="border-right:1px solid black;">
                        @if ($capitalAccountCashFlows->cash_out > 0)
                            <table class="capital_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $capitalAccountCashFlows->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($capitalAccountCashFlows->groups as $group)
                                                    @if ($group->cash_out > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($capitalAccountCashFlows->accounts as $account)
                                                    @if ($account->cash_out > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($capitalAccountCashFlows->cash_out) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($branchAndDivisionCashFlows->cash_out > 0)
                            <table class="branch_and_division_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $branchAndDivisionCashFlows->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($branchAndDivisionCashFlows->groups as $group)
                                                    @if ($group->cash_out > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($branchAndDivisionCashFlows->accounts as $account)
                                                    @if ($account->cash_out > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($branchAndDivisionCashFlows->cash_out) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($suspenseAccountCashFlows->cash_out > 0)
                            <table class="suspense_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $suspenseAccountCashFlows->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($suspenseAccountCashFlows->groups as $group)
                                                    @if ($group->cash_out > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($suspenseAccountCashFlows->accounts as $account)
                                                    @if ($account->cash_out > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>

                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($suspenseAccountCashFlows->cash_out) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($currentLiabilitiesCashFlows->cash_out > 0)
                            <table class="current_liabilities_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $currentLiabilitiesCashFlows->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($currentLiabilitiesCashFlows->groups as $group)
                                                    @if ($group->cash_out > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($currentLiabilitiesCashFlows->accounts as $account)
                                                    @if ($account->cash_out > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>
                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($currentLiabilitiesCashFlows->cash_out) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($loanLiabilitiesCashFlows->cash_out > 0)
                            <table class="loan_liabilities_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $loanLiabilitiesCashFlows->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($loanLiabilitiesCashFlows->groups as $group)
                                                    @if ($group->cash_out > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($loanLiabilitiesCashFlows->accounts as $account)
                                                    @if ($account->cash_out > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>
                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($loanLiabilitiesCashFlows->cash_out) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($currentAssetsCashFlows->cash_out > 0)
                            <table class="current_assets_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $currentAssetsCashFlows->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($currentAssetsCashFlows->groups as $group)
                                                    @if ($group->cash_out > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($currentAssetsCashFlows->accounts as $account)
                                                    @if ($account->cash_out > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>
                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($currentAssetsCashFlows->cash_out) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($fixedAssetsCashFlows->cash_out > 0)
                            <table class="fixed_assets_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $fixedAssetsCashFlows->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($fixedAssetsCashFlows->groups as $group)
                                                    @if ($group->cash_out > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($fixedAssetsCashFlows->accounts as $account)
                                                    @if ($account->cash_out > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>
                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($fixedAssetsCashFlows->cash_out) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($investmentsCashFlows->cash_out > 0)
                            <table class="investments_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $investmentsCashFlows->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($investmentsCashFlows->groups as $group)
                                                    @if ($group->cash_out > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($investmentsCashFlows->accounts as $account)
                                                    @if ($account->cash_out > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>
                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($investmentsCashFlows->cash_out) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($directExpenseCashFlows->cash_out > 0)
                            <table class="direct_expense_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $directExpenseCashFlows->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($directExpenseCashFlows->groups as $group)
                                                    @if ($group->cash_out > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($directExpenseCashFlows->accounts as $account)
                                                    @if ($account->cash_out > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>
                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($directExpenseCashFlows->cash_out) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($indirectExpenseCashFlows->cash_out > 0)
                            <table class="indirect_expense_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $indirectExpenseCashFlows->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($indirectExpenseCashFlows->groups as $group)
                                                    @if ($group->cash_out > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($indirectExpenseCashFlows->accounts as $account)
                                                    @if ($account->cash_out > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>
                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($indirectExpenseCashFlows->cash_out) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($directIncomeCashFlows->cash_out > 0)
                            <table class="direct_income_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $directIncomeCashFlows->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($directIncomeCashFlows->groups as $group)
                                                    @if ($group->cash_out > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($directIncomeCashFlows->accounts as $account)
                                                    @if ($account->cash_out > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>
                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($directIncomeCashFlows->cash_out) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($indirectIncomeCashFlows->cash_out > 0)
                            <table class="indirect_income_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $indirectIncomeCashFlows->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($indirectIncomeCashFlows->groups as $group)
                                                    @if ($group->cash_out > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($indirectIncomeCashFlows->accounts as $account)
                                                    @if ($account->cash_out > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>
                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($indirectIncomeCashFlows->cash_out) }}</strong></td>
                                </tr>
                            </table>
                        @endif

                        @if ($salesAccountCashFlows->cash_out > 0)
                            <table class="sales_account_group_table w-100 mt-1">
                                <tr>
                                    <td class="first_td">
                                        <strong class="ps-2">{{ $salesAccountCashFlows->main_group_name }}</strong>
                                        @if ($formatOfReport == 'detailed')
                                            <table class="group_account_table ms-2">
                                                @foreach ($salesAccountCashFlows->groups as $group)
                                                    @if ($group->cash_out > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $group->group_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                @foreach ($salesAccountCashFlows->accounts as $account)
                                                    @if ($account->cash_out > 0)
                                                        <tr>
                                                            <td class="group_account_name ps-1"><b>{{ $account->account_name }}</b></td>
                                                            <td class="group_account_balance text-end">
                                                                <b>{{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}</b>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>
                                    <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($salesAccountCashFlows->cash_out) }}</strong></td>
                                </tr>
                            </table>
                        @endif
                    </td>
                </tr>
            </tbody>

            <tfoot class="net_total_balance_footer">
                <tr>
                    <td class="text-end fw-bold net_debit_total" style="border:1px solid black;">@lang('menu.total') : {{ \App\Utils\Converter::format_in_bdt($totalIn) }}</td>
                    <td class="text-end fw-bold net_credit_total" style="border:1px solid black;">@lang('menu.total') : {{ \App\Utils\Converter::format_in_bdt($totalOut) }}</td>
                </tr>

                <tr>
                    <td class="text-end fw-bold net_debit_total" style="border:1px solid black;">@lang('menu.net_inflow') : </td>
                    <td class="text-start fw-bold net_credit_total ps-1" style="border:1px solid black;">
                        {{ $balanceSide == 'out' ? '(-)' : '' }}
                        {{ \App\Utils\Converter::format_in_bdt($balance) }}
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
