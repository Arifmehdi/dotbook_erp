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
    th, td { text-align: left;}
    table {border: none!important;}

    .net_total_balance_footer tr {border-top: 1px solid; border-bottom: 1px solid;}
    /* td.trial_balance_area {font-family: Arial, Helvetica, sans-serif!important;} */
    /* td.trial_balance_area {line-height: 17px!important;} */
    .trial_balance_area table{font-family: Arial, Helvetica, sans-serif!important;}
    .header_text {letter-spacing: 3px;border-bottom: 1px solid; background-color: #fff!important; color: #000!important}
    /* .trial_balance_area tbody tr td {line-height: 16px;} */
    .footer_total {font-size: 14px!important;}
</style>
@php
    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
<div class="row align-items-center" style="border-bottom: 1px solid black;">
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
        <h4 class="text-uppercase"><strong>@lang('menu.trial_balance') </strong></h4>
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
    <p><strong>@lang('menu.type_of_grouping') : </strong><b>@lang('menu.group_wise')</b></p>
</div>

<div class="row" style="margin-top: 15px;">
    <div class="col-12">
        <div class="trial_balance_area">
            <table class="w-100">
                <thead>
                    <tr>
                        <th rowspan="2" class="header_text text-center" style="border-left:1px solid black;border-top:1px solid black;">@lang('menu.particulars')</th>
                        <th colspan="2" class="header_text text-center" style="border-left:1px solid black;border:1px solid black;">@lang('menu.opening_balance')</th>
                        <th colspan="2" class="header_text text-center" style="border-left:1px solid black;border:1px solid black;">@lang('menu.closing_balance')</th>
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

                    @foreach ($accountGroups as $key => $mainGroup)

                        @if ($key != 0)

                            @if ($mainGroup['debit_closing_balance'] > 0 || $mainGroup['credit_closing_balance'] > 0)

                                @php
                                    $totalDebitOpeningBalance +=  $mainGroup['debit_opening_balance'];
                                    $totalCreditOpeningBalance += $mainGroup['credit_opening_balance'];
                                    $totalDebitClosingBalance += $mainGroup['debit_closing_balance'];
                                    $totalCreditClosingBalance += $mainGroup['credit_closing_balance'];
                                @endphp

                                <tr class="account_group_list">
                                    {{-- <td class="text-start fw-bold">{{ $mainGroup['main_group_name'] }}</td> --}}
                                    <td class="text-start fw-bold">{{ $mainGroup['main_group_name'] }}</td>

                                    <td class="text-end fw-bold" style="{{ ($mainGroup['debit_opening_balance'] > 0 ? 'border-bottom:1px solid gray;' : '' ) }}">
                                        {{ ($mainGroup['debit_opening_balance'] > 0 ? \App\Utils\Converter::format_in_bdt($mainGroup['debit_opening_balance']) : '' ) }}
                                    </td>

                                    <td class="text-end fw-bold" style="{{ ($mainGroup['credit_opening_balance'] > 0 ? 'border-bottom:1px solid gray;' : '' ) }}">
                                        {{ ($mainGroup['credit_opening_balance'] > 0 ? \App\Utils\Converter::format_in_bdt($mainGroup['credit_opening_balance']) : '' ) }}
                                    </td>

                                    <td class="text-end fw-bold" style="{{ ($mainGroup['debit_closing_balance'] > 0 ? 'border-bottom:1px solid gray;' : '' ) }}">{{ $mainGroup['debit_closing_balance'] > 0 ? App\Utils\Converter::format_in_bdt($mainGroup['debit_closing_balance']) : '' }}</td>

                                    <td class="text-end fw-bold" style="{{ ($mainGroup['credit_closing_balance'] > 0 ? 'border-bottom:1px solid gray;' : '' ) }}">{{ $mainGroup['credit_closing_balance'] > 0 ? App\Utils\Converter::format_in_bdt($mainGroup['credit_closing_balance']) : '' }}</td>
                                </tr>

                                @if ($formatOfReport == 'detailed')

                                    @if (count($mainGroup['groups']) > 0)

                                        @foreach ($mainGroup['groups'] as $group)

                                            @if ($group['debit_closing_balance'] > 0 || $group['credit_closing_balance'] > 0)

                                                <tr class="account_group_list">
                                                    <td class="text-start ps-1"><b>{{ $group['group_name'] }}</b></td>

                                                    <td class="text-end">
                                                        <b>{{ ($group['debit_opening_balance'] > 0 ? \App\Utils\Converter::format_in_bdt($group['debit_opening_balance']) : '' ) }}</b>
                                                    </td>

                                                    <td class="text-end">
                                                        <b>{{ ($group['credit_opening_balance'] > 0 ? \App\Utils\Converter::format_in_bdt($group['credit_opening_balance']) : '' ) }}</b>
                                                    </td>

                                                    <td class="text-end">
                                                        <b>{{ $group['debit_closing_balance'] > 0 ? App\Utils\Converter::format_in_bdt($group['debit_closing_balance']) : '' }}</b>
                                                    </td>

                                                    <td class="text-end">
                                                        <b>{{ $group['credit_closing_balance'] > 0 ? App\Utils\Converter::format_in_bdt($group['credit_closing_balance']) : '' }}</b>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                @endif

                                 @if ($formatOfReport == 'detailed')

                                    @if (count($mainGroup['accounts']) > 0)

                                        @foreach ($mainGroup['accounts'] as $account)

                                            @if ($account['debit_closing_balance'] > 0 || $account['credit_closing_balance'] > 0)
                                                <tr class="account_group_list">
                                                    <td class="text-start ps-1"><b>{{ $account['account_name'] }}</b></td>

                                                    <td class="text-end">
                                                        <b>{{ ($account['debit_opening_balance'] > 0 ? \App\Utils\Converter::format_in_bdt($account['debit_opening_balance']) : '' ) }}</b>
                                                    </td>

                                                    <td class="text-end">
                                                        <b>{{ ($account['credit_opening_balance'] > 0 ? \App\Utils\Converter::format_in_bdt($account['credit_opening_balance']) : '' ) }}</b>
                                                    </td>

                                                    <td class="text-end">
                                                        <b>{{ $account['debit_closing_balance'] > 0 ? App\Utils\Converter::format_in_bdt($account['debit_closing_balance']) : '' }}</b>
                                                    </td>

                                                    <td class="text-end">
                                                        <b>{{ $account['credit_closing_balance'] > 0 ? App\Utils\Converter::format_in_bdt($account['credit_closing_balance']) : '' }}</b>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                @endif
                            @endif
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
                        <td class="text-end footer_total footer_total_debit fw-bold">{{ \App\Utils\Converter::format_in_bdt($totalDebitOpeningBalance) }}</td>
                        <td class="text-end footer_total footer_total_credit fw-bold">{{ \App\Utils\Converter::format_in_bdt($totalCreditOpeningBalance) }}</td>
                        <td class="text-end footer_total footer_total_debit fw-bold">{{ \App\Utils\Converter::format_in_bdt($totalDebitClosingBalance) }}</td>
                        <td class="text-end footer_total footer_total_credit fw-bold">{{ \App\Utils\Converter::format_in_bdt($totalCreditClosingBalance) }}</td>
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
