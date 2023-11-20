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
    .row::after {content: "";clear: both;display: table;}
    table { border-collapse: collapse;border-spacing: 0; width: 100%;border: 1px solid #ddd;}
    th, td { text-align: left; vertical-align: baseline; }
    .group_tr {line-height: 17px;}
    .account_tr {line-height: 17px;}
    table {border: none!important;}
    td.ledger_cash_flow_area {border-left: 1px solid #000;}
    .net_total_balance_footer tr {border-top: 1px solid; border-bottom: 1px solid;line-height: 16px;}
    td.inflow_area {line-height: 17px;}
    td.ledger_cash_flow_area {line-height: 17px;}
    /* font-family: sans-serif; */
    .header_text {letter-spacing: 3px;border-bottom: 1px solid; background-color: #fff!important; color: #000!important}
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
                    <p><b>{{ json_decode($generalSettings->business, true)['address'] }}</b></p>
                    <p>
                        <strong>@lang('menu.email') : </strong> <b>{{ json_decode($generalSettings->business, true)['email'] }}</b>,
                        <strong>@lang('menu.phone') : </strong> <b>{{ json_decode($generalSettings->business, true)['phone'] }}</b>
                    </p>
                </div>
            </div>
        </div>

        <h6 style="margin-top: 10px; text-transform:uppercase;"><strong>@lang('menu.ledger_cash_flow') </strong></h6>
        <h6 style="margin-top: 10px; text-transform:uppercase;"><strong>
            @lang('menu.ledger') :
            {{ $account->name }}
            {{ $account->phone ? ' / ' .$account->phone : '' }}
            {{ $account->account_number ? ' / ' .$account->account_number : '' }}
        </strong></h6>

        @if ($fromDate && $toDate)

            <p style="margin-top: 10px;"><strong>@lang('menu.from') :</strong>
                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($fromDate)) }}
                <strong>To</strong> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($toDate)) }}
            </p>
        @endif
    </div>
</div>

<div class="row" style="margin-top: 15px;">
    <div class="col-12">
        <table class="w-100 selectable">
            <thead>
                <tr>
                    <th class="header_text ps-1 text-start">@lang('menu.date')</th>
                    <th class="header_text ps-1 text-start">@lang('menu.particulars')</th>
                    <th class="header_text ps-1 text-start">@lang('menu.voucher_type')</th>
                    <th class="header_text ps-1 text-start">@lang('menu.voucher_no')</th>
                    <th class="header_text ps-1 text-end">@lang('menu.debit')</th>
                    <th class="header_text ps-1 text-end">@lang('menu.credit')</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $totalDebit = 0;
                    $totalCredit = 0;
                @endphp
                @foreach ($ledgerCashflow as $row)
                    @php
                        $totalDebit += $row->debit;
                        $totalCredit += $row->credit;
                    @endphp
                    <tr class="group_tr" style="border-bottom:1px solid rgb(218, 210, 210);">
                        <td class="fw-bold">
                            @php
                                $dateFormat = json_decode($generalSettings->business, true)['date_format'];
                                $__date_format = str_replace('-', '/', $dateFormat);
                            @endphp

                            {{ $row->date ? date($__date_format, strtotime($row->date)) : '' }}
                        </td>
                        <td class="text-start">
                            @php
                                $voucherType = $row->voucher_type;
                                $ledgerParticularsForPrintUtil = new \App\Utils\LedgerParticularsForPrintUtil();
                            @endphp
                            {!! $ledgerParticularsForPrintUtil->particulars($request, $row->voucher_type, $row, $by) !!}
                        </td>
                        <td class="text-start fw-bold">
                            @php
                                $accountLedgerUtil = new \App\Utils\AccountLedgerUtil();
                                $type = $accountLedgerUtil->voucherType($row->voucher_type);
                            @endphp
                            {!! $row->voucher_type != 0 ? $type['name'] : '' !!}
                        </td>
                        <td class="text-start">{!! $row->{$type['voucher_no']} !!}</td>
                        <td class="text-end fw-bold">{{ $row->debit > 0 ? \App\Utils\Converter::format_in_bdt($row->debit) : '' }}</td>
                        <td class="text-end fw-bold">{{ $row->credit > 0 ? \App\Utils\Converter::format_in_bdt($row->credit) : '' }}</td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot class="net_total_balance_footer">
                <tr>
                    <td colspan="4" class="text-end fw-bold">@lang('menu.current_total') :</td>
                    <td class="text-end fw-bold net_debit_total">{{ $totalDebit > 0 ? \App\Utils\Converter::format_in_bdt($totalDebit) : '' }}</td>
                    <td class="text-end fw-bold net_credit_total">{{ $totalCredit > 0 ? \App\Utils\Converter::format_in_bdt($totalCredit) : '' }}</td>
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
