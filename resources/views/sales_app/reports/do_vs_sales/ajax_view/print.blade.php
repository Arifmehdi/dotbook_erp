<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto;}
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }
    div#footer {position:fixed;bottom:20px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 5px;margin-right: 5px;}
    .print_table tr td{font-size: 10px!important;}
    .print_table tr th{font-size: 10px!important;}

    h6 { font-size: 16px; }
    p {  font-size: 14px; }
    td {  color: black; }
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
        <p></p>
    </div>
</div>

<div class="row mt-2">
    <div class="col-12 text-center">
        <h6 style="text-transform:uppercase;"><strong>@lang('menu.do_vs_ales_report') </strong></h6>
    </div>
</div>

<div class="row mt-1">
    <div class="col-12 text-center">
        @if ($fromDate && $toDate)
            <p>
                <strong>@lang('menu.from') :</strong>
                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($fromDate)) }}
                <strong>@lang('menu.to') :</strong> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($toDate)) }}
            </p>
        @endif
    </div>
</div>

<div class="row mt-2">
    <div class="col-12">
        <table class="table report-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <th class="text-start">@lang('short.delivery_date')</th>
                    <th class="text-start">@lang('menu.do_id')</th>
                    <th class="text-start">@lang('short.delivery_order_left_qty') (@lang('menu.as_base_unit'))</th>
                    <th class="text-start">@lang('menu.invoice_date')</th>
                    <th class="text-start">@lang('menu.invoice_id')</th>
                    <th class="text-start">@lang('menu.vehicle_no')</th>
                    <th class="text-start">@lang('menu.sold_qty') (@lang('menu.as_base_unit'))</th>
                    <th class="text-start">@lang('menu.weight')</th>
                    <th class="text-start">@lang('menu.net_weight')</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @foreach ($sales as $sale)
                    <tr>
                        <td class="text-start">{{ date($__date_format, strtotime($sale->do->do_date)) }}</td>
                        <td class="text-start"><strong>{{ $sale->do->do_id }}</strong></td>
                        <td class="text-start">{{ App\Utils\Converter::format_in_bdt($sale->do_total_left_qty) }}</td>
                        <td class="text-start">{{ date($__date_format, strtotime($sale->report_date)) }}</td>
                        <td class="text-start"><strong>{{ $sale->invoice_id }}</strong></td>
                        <td class="text-start">{{ $sale?->weight?->do_car_number }}</td>
                        <td class="text-start">{{ App\Utils\Converter::format_in_bdt($sale->total_sold_qty) }}</td>

                        <td class="text-start">
                            <p class="p-0 m-0" style="font-size: 10px!important;"><strong>{{ __("1st") }}</strong> {{ __("W/t") }} : {{ App\Utils\Converter::format_in_bdt($sale?->weight?->first_weight) }}</p>
                            <p class="p-0 m-0" style="font-size: 10px!important;"><strong>{{ __("2nd") }}</strong> {{ __("W/t") }} : {{ App\Utils\Converter::format_in_bdt($sale?->weight?->second_weight) }}</p>
                        </td>
                        <td class="text-start">
                            @php
                                $calc1 = $sale?->weight?->second_weight - $sale?->weight?->first_weight;
                            @endphp

                            <strong>{{ App\Utils\Converter::format_in_bdt($calc1) }}</strong>
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
