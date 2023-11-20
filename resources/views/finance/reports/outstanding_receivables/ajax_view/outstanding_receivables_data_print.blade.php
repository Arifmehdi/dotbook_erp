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
    td.outstanding_receivable_area {line-height: 17px!important;}
    .header_text {letter-spacing: 3px;border-bottom: 1px solid; background-color: #fff!important; color: #000!important}
    .outstanding_receivable_area tbody tr td {line-height: 16px;}
    tr.account_list td {border-bottom: 1px solid rgb(84, 82, 82);}
    .print_table td {font-size:11px!important;line-height: 17px!important;}
    .print_table th {font-size:11px!important;line-height: 17px!important;}
</style>
@php
    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
<div class="row" style="border-bottom: 1px solid black;">
    <div class="col-4">
        <img style="height: auto; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
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
        <h6 class="text-uppercase"><strong>@lang('menu.outstanding_receivables') </strong></h6>

        @if ($fromDate && $toDate)

            <p><strong>@lang('menu.from') :</strong>
                <b>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($fromDate)) }}</b>
                <strong>To</strong> <b>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($toDate)) }}</b>
            </p>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-6">
        <small><strong>@lang('menu.accounts_group_head') :</strong> {{ $accountGroupHeadName }}</small>
    </div>
</div>

<div class="row mt-2">
    <div class="col-12">
        <div class="outstanding_receivable_area">
            <table class="w-100 print_table">
                <thead>
                    <tr class="bg-primary">
                        <th class="header_text text-start ps-1">@lang('menu.period')</th>
                        <th class="header_text ps-1">@lang('menu.account_name')</th>
                        <th class="header_text ps-1">@lang('menu.group')</th>
                        <th class="header_text pe-1 text-end">@lang('menu.pending_amount')</th>
                    </tr>
                </thead>

                <tbody>
                    @php
                        $totalPendingAmount = 0;
                    @endphp
                    @foreach ($receivables as $receivable)
                        @php
                            $openingBalanceDebit = isset($receivable->opening_total_debit) ? (float)$receivable->opening_total_debit : 0;
                            $openingBalanceCredit = isset($receivable->opening_total_credit) ? (float)$receivable->opening_total_credit : 0;

                            $CurrTotalDebit = (float)$receivable->curr_total_debit;
                            $CurrTotalCredit = (float)$receivable->curr_total_credit;

                            $currOpeningBalance = 0;
                            $currOpeningBalanceSide = $receivable->sub_sub_group_number == 6 ? 'dr' : 'cr';

                            if ($openingBalanceDebit > $openingBalanceCredit) {

                                $currOpeningBalance = $openingBalanceDebit - $openingBalanceCredit;
                                $currOpeningBalanceSide = 'dr';
                            } elseif ($openingBalanceCredit > $openingBalanceDebit) {

                                $currOpeningBalance = $openingBalanceCredit - $openingBalanceDebit;
                                $currOpeningBalanceSide = 'cr';
                            }

                            $CurrTotalDebit += $currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0;
                            $CurrTotalCredit += $currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0;

                            $closingBalance = 0;
                            $closingBalanceSide = $receivable->sub_sub_group_number == 6 ? 'dr' : 'cr';
                            if ($CurrTotalDebit > $CurrTotalCredit) {

                                $closingBalance = $CurrTotalDebit - $CurrTotalCredit;
                                $closingBalanceSide = 'dr';
                            } elseif ($CurrTotalCredit > $CurrTotalDebit) {

                                $closingBalance = $CurrTotalCredit - $CurrTotalDebit;
                                $closingBalanceSide = 'cr';
                            }
                        @endphp

                        @if ($closingBalanceSide == 'dr' && $closingBalance > 0)
                            @php
                                $totalPendingAmount += $closingBalance;
                            @endphp
                            <tr class="account_list">
                                <td class="text-start ps-1">
                                    @if ($fromDate && $toDate)
                                        <b>{!! date($__date_format, strtotime($fromDate)) . ' <strong>'.__('menu.to').'</strong> ' . date($__date_format, strtotime($toDate)) !!}</b>
                                    @else
                                        {!! date($__date_format, strtotime($accountStartDate)) . ' <strong>'.__('menu.to').'</strong> ' . date($__date_format) !!}
                                    @endif
                                </td>
                                <td class="text-start ps-1">
                                    {{ $receivable->account_name }}
                                    {!! $receivable?->u_name ? ' - <strong>Sr.</strong> ' . $receivable?->u_prefix .' '. $receivable?->u_name .' '. $receivable?->u_last_name : '' !!}</td>
                                <td class="text-start ps-1">{{ $receivable->group_name }}</td>
                                <td class="fw-bold pe-1 text-end">{{ \App\Utils\Converter::format_in_bdt($closingBalance) }}</td>
                            </tr>
                        @endif
                    @endforeach

                    <tr>
                        <td colspan="3" class="text-end ps-1 fw-bold" style="border-bottom: 1px solid #000;font-size:12px!important;">
                            @lang('menu.total') :
                        </td>
                        <td class="fw-bold pe-1 text-end" style="border-bottom: 1px solid #000;font-size:12px!important;">
                            {{ \App\Utils\Converter::format_in_bdt($totalPendingAmount) }}
                        </td>
                    </tr>
                </tbody>
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
                <small>@lang('menu.powered_by') <b>@lang('menu.speedDigit_software_solution').</b></small>
            @endif
        </div>

        <div class="col-4 text-end">
            <small>@lang('menu.print_time') :{{ date($timeFormat) }}</small>
        </div>
    </div>
</div>
