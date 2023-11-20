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
    .group_tr {line-height: 17px;}
    .account_tr {line-height: 17px;}
    table {border: none!important;}
    td.group_cash_flow_area {border-left: 1px solid #000;}
    .net_total_balance_footer tr {border-top: 1px solid; border-bottom: 1px solid;line-height: 16px;}
    .net_credit_total {border-left: 1px solid #000;}
    td.group_cash_flow_area {line-height: 17px;}
    /* font-family: sans-serif; */
    .header_text {letter-spacing: 3px;border-bottom: 1px solid; border-top: 1px solid; background-color: #fff!important; color: #000!important}
</style>
@php
    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp


<div class="row">
    <div class="col-md-12 text-center">
        <div class="heading_area" style="border-bottom: 1px solid black;">
            <div class="row">
                <div class="col-4 text-start">
                    <img style="height: auto; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                </div>

                <div class="col-8 text-end">
                    <p style="text-transform: uppercase;"><strong>{{ json_decode($generalSettings->business, true)['shop_name'] }}</strong></p>
                    <p>{{ json_decode($generalSettings->business, true)['address'] }}</p>
                    <p>
                        <strong>@lang('menu.email') : </strong> {{ json_decode($generalSettings->business, true)['email'] }},
                        <strong>@lang('menu.phone') : </strong> {{ json_decode($generalSettings->business, true)['phone'] }}
                    </p>
                </div>
            </div>
        </div>

        <h6 style="margin-top: 10px; text-transform:uppercase;"><strong>@lang('menu.group_cash_flow') </strong></h6>
        <h6 style="margin-top: 10px; text-transform:uppercase;"><strong>@lang('menu.group') : {{ $groupCashflow->name }} </strong></h6>

        @if ($fromDate && $toDate)

            <p style="margin-top: 10px;"><strong>@lang('menu.from') :</strong>
                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($fromDate)) }}
                <strong>{{ __("To") }}</strong> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($toDate)) }}
            </p>
        @endif
    </div>
</div>

<div class="row" style="margin-top: 15px;">
    <div class="col-12">
        <table class="w-100">
            <thead>
                <tr>
                    <th class="header_text ps-1 text-start" style="border-left: 1px solid black;">@lang('menu.particulars')</th>
                    <th class="header_text ps-1 text-end">@lang('menu.inflow')</th>
                    <th class="header_text ps-1 text-end" style="border-left: 1px solid black; border-right: 1px solid black;">@lang('menu.outflow')</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $totalInflow = 0;
                    $totalOutflow = 0;
                @endphp
                @if (count($groupCashflow->subgroupsAccountsForOthers) > 0)

                    @foreach ($groupCashflow->subgroupsAccountsForOthers as $group)

                        @if (($cashFlowSide == 'in' && $group->cash_in > 0) || ($cashFlowSide == 'out' && $group->cash_out > 0))

                            @if ($group->sub_sub_group_number != 11 && $group->sub_sub_group_number != 1 && $group->sub_sub_group_number != 2)

                                <tr class="group_tr">
                                    <td class="account_group ps-1 fw-bold" style="border-left: 1px solid black;">{{ $group->name }} (@lang('menu.group'))</td>
                                    <td class="text-end fw-bold" style="border-right: 1px solid black;">
                                        @if ($cashFlowSide == 'in')
                                            @php
                                                $totalInflow += $group->cash_in;
                                            @endphp
                                            {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                        @endif
                                    </td>

                                    <td class="text-end fw-bold" style="border-right: 1px solid black;">
                                        @if ($cashFlowSide == 'out')
                                            @php
                                                $totalOutflow += $group->cash_out;
                                            @endphp
                                            {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endif
                    @endforeach
                @endif

                @if (count($groupCashflow->accounts) > 0)

                    @foreach ($groupCashflow->accounts as $account)

                        @if (($cashFlowSide == 'in' && $account->cash_in > 0) || ($cashFlowSide == 'out' && $account->cash_out > 0))
                            <tr class="account_tr">
                                <td class="ledger ps-1" style="border-left: 1px solid black;"><b>{{ $account->name }}</b> (@lang('menu.ledger'))</td>
                                <td class="text-end fw-bold" style="border-right: 1px solid black;">
                                    @if ($cashFlowSide == 'in')
                                        @php
                                            $totalInflow += $account->cash_in;
                                        @endphp
                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                                    @endif
                                </td>
                                <td class="text-end fw-bold" style="border-right: 1px solid black;">
                                    @if ($cashFlowSide == 'out')
                                        @php
                                            $totalOutflow += $account->cash_out;
                                        @endphp
                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @endif
            </tbody>

            <tfoot class="net_total_balance_footer">
                <tr>
                    <td class="text-start fw-bold ps-1" style="border-left: 1px solid black;">@lang('menu.grand_total') :</td>
                    <td class="text-end fw-bold net_debit_total">
                        @if ($cashFlowSide == 'in')
                            {{ \App\Utils\Converter::format_in_bdt($totalInflow) }}
                        @endif
                    </td>

                    <td class="text-end fw-bold net_credit_total" style="border-right: 1px solid black;">
                        @if ($cashFlowSide == 'out')
                            {{ \App\Utils\Converter::format_in_bdt($totalOutflow) }}
                        @endif
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
