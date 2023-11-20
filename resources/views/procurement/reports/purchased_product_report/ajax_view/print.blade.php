<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto, font-size:9px!important; }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 5px;margin-right: 5px;}
    div#footer {position:fixed;bottom:24px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}

    .print_table th { font-size:10px!important; font-weight: 550!important;}
    .print_table tr td{font-size:10px!important;}

    h6 { font-size: 16px; }
    p {  font-size: 14px; }
    td {  color: black; }
</style>
@php
    $totalQty = 0;
    $totalUnitCost = 0;
    $totalSubTotal = 0;
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
        <h6 style="text-transform:uppercase;"><strong>@lang('menu.purchased_items_report') </strong></h6>
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
    <div class="col-6">
        <small><strong>@lang('menu.item') :</strong> {{ $searchProduct ? $searchProduct : 'All' }} </small>
    </div>

    <div class="col-6">
        <small><strong>@lang('menu.supplier') : </strong> {{ $supplierName }} </small>
    </div>
</div>

<div class="row mt-1">
    <div class="col-12">
        <table class="table report-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <th class="text-start">@lang('menu.item_code')</th>
                    <th class="text-start">@lang('menu.item_name')</th>
                    <th class="text-start">@lang('menu.supplier')</th>
                    <th class="text-start">@lang('short.p_invoice_id')</th>
                    <th class="text-start">@lang('menu.qty')</th>
                    <th class="text-end">@lang('menu.unit_cost')</th>
                    <th class="text-end">@lang('menu.sub_total')</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @php
                    $previousDate = '';
                @endphp
                @foreach ($purchaseProducts as $pProduct)
                    @php
                       $date = date($__date_format, strtotime($pProduct->report_date))
                    @endphp
                    @if ($previousDate != $date)

                        @php
                            $previousDate = $date;
                        @endphp

                        <tr>
                            <th colspan="7">{{ $date }}</th>
                        </tr>
                    @endif

                    <tr>
                        <td class="text-start">{{ $pProduct->variant_code ? $pProduct->variant_code : $pProduct->product_code}}</td>
                        <td class="text-start">
                            @php
                                $variant = $pProduct->variant_name ? ' - ' . $pProduct->variant_name : '';
                                $totalQty += $pProduct->quantity;
                                $totalUnitCost += $pProduct->net_unit_cost;
                                $totalSubTotal += $pProduct->line_total;
                            @endphp
                           {{ $pProduct->name . $variant }}
                        </td>
                        <td class="text-start">{{ $pProduct->supplier_name }}</td>
                        <td class="text-start">{{ $pProduct->invoice_id }}</td>
                        @php
                             $baseUnitMultiplier = $pProduct->base_unit_multiplier ? $pProduct->base_unit_multiplier : 1;
                        @endphp
                        <td class="text-start fw-bold">{!! App\Utils\Converter::format_in_bdt($pProduct->quantity / $baseUnitMultiplier)  .'/'.$pProduct->unit_code !!}</td>
                        <td class="text-end fw-bold">{{ App\Utils\Converter::format_in_bdt($pProduct->net_unit_cost) }}</td>
                        <td class="text-end fw-bold">{{ App\Utils\Converter::format_in_bdt($pProduct->line_total) }}</td>
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
                    <th class="text-end">@lang('menu.total_cost') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalUnitCost) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.net_total_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalSubTotal) }}</td>
                </tr>
            </thead>
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
