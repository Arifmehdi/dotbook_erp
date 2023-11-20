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

    .print_table th { font-size:10px!important; font-weight: 550!important;}
    .print_table td { font-size:10px!important; color: #000;}
    tr.main_tr { border-bottom: 1pt solid black!important;}
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
        <p style="text-transform: uppercase; font-size: 14px;"><strong>{{ json_decode($generalSettings->business, true)['shop_name'] }}</strong></p>
        <p style="font-size: 14px;"><b>{{ json_decode($generalSettings->business, true)['address'] }}</b></p>
        <p style="font-size: 14px;">
            <strong>@lang('menu.email') : </strong> <b>{{ json_decode($generalSettings->business, true)['email'] }}</b>,
            <strong>@lang('menu.phone') : </strong> <b>{{ json_decode($generalSettings->business, true)['phone'] }}</b>
        </p>
    </div>
</div>

<div class="row mt-2">
    <div class="col-12 text-center">
        <h6 class="text-uppercase" style="font-size: 16px;"><strong>@lang('menu.day_book') </strong></h6>

        @if ($fromDate && $toDate)

            <p class="mt-1"><strong>@lang('menu.from') :</strong>
                <b>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($fromDate)) }}</b>
                <b><strong>{{ __("To") }} : </strong> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($toDate)) }}</b>
            </p>
        @endif
    </div>
</div>

<div class="row mt-2">
    <div class="col-12">
        <table class="table report-table table-sm print_table">
            <thead>
                <tr>
                    <th class="text-startx">@lang('menu.particulars')</th>
                    <th class="text-startx">@lang('menu.voucher_type')</th>
                    <th class="text-startx">@lang('menu.voucher_no')</th>
                    <th class="text-startx">
                        <p class="p-0 m-0" style="font-size:12px !important; border-bottom:1px solid #000;">@lang('menu.debit_amount')</p>
                        <p class="p-0 m-0" style="font-size:11px !important;">@lang('menu.inwardQuantity')</p>
                    </th>
                    <th class="text-startx">
                        <p class="p-0 m-0" style="font-size:12px !important; border-bottom:1px solid #000;">@lang('menu.credit_amount')</p>
                        <p class="p-0 m-0" style="font-size:11px !important;">@lang('menu.outwardQuantity')</p>
                    </th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">

                @php $previousDate = '';@endphp

                @foreach ($daybook as $row)

                    @php
                        $date = date($__date_format, strtotime($row->date_ts))
                    @endphp

                    @if ($previousDate != $date)

                        @php
                            $previousDate = $date;
                        @endphp

                        <tr class="main_tr">
                            <th colspan="6" style="font-size: 12px!important; font-weight:600;">{{ $date }}</th>
                        </tr>
                    @endif

                    <tr class="main_tr">
                        <td class="text-start">
                            @php
                                $voucherType = $row->voucher_type;
                                $dayBookPrintParticularUtil = new \App\Utils\DayBookPrintParticularUtil();
                            @endphp

                            {!! $dayBookPrintParticularUtil->particulars($request, $row->voucher_type, $row) !!}
                        </td>

                        <td class="text-start fw-bold">
                            @php
                                $dayBookPrintParticularUtil = new \App\Utils\DayBookPrintParticularUtil();
                                $type = $dayBookPrintParticularUtil->voucherType($row->voucher_type);
                            @endphp
                            {!! $type['name'] !!}
                        </td>

                        <td class="text-start fw-bold">

                            @php
                                $dayBookPrintParticularUtil = new \App\Utils\DayBookPrintParticularUtil();
                                $type = $dayBookPrintParticularUtil->voucherType($row->voucher_type);
                            @endphp

                            {!! $row->{$type['voucher_no']} !!}
                        </td>

                        <td class="text-end fw-bold">
                            {{ $row->amount_type == 'debit' ? \App\Utils\Converter::format_in_bdt($row->amount) : '' }}
                        </td>

                        <td class="text-end fw-bold">
                            {{ $row->amount_type == 'credit' ? \App\Utils\Converter::format_in_bdt($row->amount) : '' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
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
