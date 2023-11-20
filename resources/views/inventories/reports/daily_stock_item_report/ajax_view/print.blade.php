<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 10px;margin-right: 10px;}
    div#footer {position:fixed;bottom:24px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
    .print_table th { font-size:10px!important; font-weight: 550!important;}
    .print_table td { font-size:10px!important;}

    h6 { font-size: 16px; }
    p {  font-size: 14px; }
    td {  color: black; }
</style>
@php
    $totalQty = 0;
    $totalSubtotal = 0;
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


<div class="row">
    <div class="col-md-12 text-center">
        <h6 style="margin-top: 10px; text-transform:uppercase;"><strong>@lang('menu.daily_stock_item_report')</strong></h6>
    </div>

    @if ($fromDate && $toDate)
        <div class="col-md-12 text-center">
            <p style="margin-top: 5px;"><strong>@lang('menu.from') :</strong>
                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($fromDate)) }}
                <strong>{{ __("To") }} : </strong> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($toDate)) }}
            </p>
        </div>
    @endif
</div>

<div class="row" style="margin-top: 15px;">
    <div class="col-12">
        <table class="table report-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <th class="text-start">@lang('menu.item_code')</th>
                    <th class="text-start">@lang('menu.item_name')</th>
                    <th class="text-start">@lang('menu.stored_location')</th>
                    <th class="text-start">@lang('menu.voucher_no')</th>
                    <th class="text-start">@lang('menu.qty')</th>
                    <th class="text-end">@lang('menu.unit_cost_inc_tax')</th>
                    <th class="text-end">@lang('menu.sub_total')</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @php
                    $previousDate = '';
                @endphp
                @foreach ($dailyStockProducts as $dailyStockProduct)
                    @php
                       $date = date($__date_format, strtotime($dailyStockProduct->date))
                    @endphp
                    @if ($previousDate != $date)

                        @php
                            $previousDate = $date;
                        @endphp

                        <tr><th colspan="7">{{ $date }}</th></tr>
                    @endif

                    <tr>
                        <td class="text-start">{{ $dailyStockProduct->variant_code ? $dailyStockProduct->variant_code : $dailyStockProduct->product_code}}</td>
                        <td class="text-start">
                            @php
                                $variant = $dailyStockProduct->variant_name ? ' - ' . $dailyStockProduct->variant_name : '';
                            @endphp
                           {{ $dailyStockProduct->name . $variant }}
                        </td>

                        <td class="text-start">
                            @if ($dailyStockProduct->w_name)

                                {!! $dailyStockProduct->w_name.'/'.$dailyStockProduct->w_code. '<strong>(WH)</strong>' !!}
                            @elseif ($dailyStockProduct->b_name)

                                {!! $row->b_name . '<strong>(BL)</strong>' !!}
                            @else

                                {{ json_decode($generalSettings->business, true)['shop_name'] }}
                            @endif
                        </td>

                        <td class="text-start">{{ $dailyStockProduct->voucher_no }}</td>
                        @php
                            $baseUnitMultiplier = $dailyStockProduct->base_unit_multiplier ? $dailyStockProduct->base_unit_multiplier : 1;
                            $dailyStockQty = $dailyStockProduct->quantity / $baseUnitMultiplier;
                        @endphp
                        <td class="text-start">{{ \App\Utils\Converter::format_in_bdt($dailyStockQty) . '/' . $dailyStockProduct?->unit_code }}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($dailyStockProduct->unit_cost_inc_tax * $baseUnitMultiplier) }}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($dailyStockProduct->subtotal) }}</td>

                        @php
                            $totalQty += $dailyStockProduct->quantity;
                            $totalSubtotal += $dailyStockProduct->subtotal;
                        @endphp
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-6"></div>
    <div class="col-6">
        <table class="table report-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <th class="text-end">@lang('menu.total_quantity') :</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalQty) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_stock_value') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalSubtotal) }}</td>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div id="footer">
    <div class="row mt-1">
        <div class="col-4 text-start">
            <small>@lang('menu.print_date') : {{ date(json_decode($generalSettings->business, true)['date_format']) }}</small>
        </div>

        <div class="col-4 text-center">
            @if (config('company.print_on_sale'))
                <small>@lang('menu.powered_by') <strong>@lang('menu.speedDigit_software_solution')</strong></small>
            @endif
        </div>

        <div class="col-4 text-end">
            <small>@lang('menu.print_time') : {{ date($timeFormat) }}</small>
        </div>
    </div>
</div>
