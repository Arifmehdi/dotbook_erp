@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
<div class="sale_print_template">
    <style>
        @media print
        {
            table { page-break-after:auto }
            tr    { page-break-inside:avoid; page-break-after:auto }
            td    { page-break-inside:avoid; page-break-after:auto }
            thead { display:table-header-group }
            tfoot { display:table-footer-group }
        }

        @page {size:a4; margin-top: 0.8cm; margin-bottom: 35px; margin-left: 10px;margin-right: 10px;}
        div#footer {position:fixed;bottom:24px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}

        h6 { font-size: 16px; }
        p {  font-size: 14px; }
        td {  color: black; }
    </style>
    <div class="details_area">
        <div class="row" style="border-bottom: 1px solid black; padding-botton: 1px;">
            <div class="col-4">
                @if (json_decode($generalSettings->business, true)['business_logo'] != null)

                    <img style="height: auto; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                @else

                    <span style="font-family: 'Anton', sans-serif;font-size:17px;color:gray;font-weight: 550; letter-spacing:1px;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
                @endif
            </div>

            <div class="col-8 text-end">
                <h6 class="company_name" style="text-transform: uppercase;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</h6>
                <p class="company_address"><b>{{ json_decode($generalSettings->business, true)['address'] }}</b></p>
                <p>
                    <strong>@lang('menu.email')</strong> : <b>{{ json_decode($generalSettings->business, true)['email'] }}</b>,
                    <strong>@lang('menu.phone')</strong> : <b>{{ json_decode($generalSettings->business, true)['phone'] }}</b>
                </p>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12">
                <div class="middle_header_text text-center">
                    <h6 style="text-transform: uppercase;">@lang('menu.daily_stock_voucher')</h6>
                </div>
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-4">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;">
                        <strong> @lang('menu.date') : </strong> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($dailyStock->date)) . ' ' . $dailyStock->time }}
                    </li>

                    <li style="font-size:11px!important;">
                        <strong> @lang('menu.voucher_no') : </strong> {{ $dailyStock->voucher_no }}
                    </li>
                </ul>
            </div>

            <div class="col-4 text-center">
                <img style="width: 170px; height:25px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($dailyStock->voucher_no, $generator::TYPE_CODE_128)) }}">
                <p>{{ $dailyStock->voucher_no }}</p>
            </div>

            <div class="col-4">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>@lang('menu.reported_by') : </strong> {{ $dailyStock->reported_by }}</li>

                    <li style="font-size:11px!important;">
                        <strong>@lang('menu.created_by') : </strong> {{ $dailyStock->createdBy ? $dailyStock->createdBy->prefix . ' ' . $dailyStock->createdBy->name . ' ' . $dailyStock->createdBy->last_name : 'N/A' }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="sale_product_table pt-3 pb-3">
            <table class="table report-table table-sm table-bordered print_table">
                <thead>
                    <tr>
                        <th class="text-start" style="font-size:11px!important;">@lang('menu.sl')</th>
                        <th class="text-start" style="font-size:11px!important;">@lang('menu.item_name')</th>
                        <th class="text-end" style="font-size:11px!important;">@lang('menu.stock_quantity')</th>
                        <th class="text-end" style="font-size:11px!important;">@lang('menu.unit_cost_exc_tax')</th>
                        <th class="text-end" style="font-size:11px!important;">@lang('menu.tax')</th>
                        <th class="text-end" style="font-size:11px!important;">@lang('menu.unit_cost_inc_tax')</th>
                        <th class="text-end" style="font-size:11px!important;">@lang('menu.sub_total')</th>
                    </tr>
                </thead>
                <tbody class="sale_print_product_list">
                    @foreach ($dailyStock->dailyStockProducts as $dsProduct)
                        <tr>
                            <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}</td>
                            <td class="text-start" style="font-size:11px!important;">
                                {{ $dsProduct->product->name }}

                                @if ($dsProduct->variant_id)

                                    -{{ $dsProduct?->variant?->variant_name }}
                                @endif
                            </td>

                            @php
                                $baseUnitMultiplier = $dsProduct?->dailyStockUnit?->base_unit_multiplier ? $dsProduct?->dailyStockUnit?->base_unit_multiplier : 1;
                                $dailyStockQty = $dsProduct->quantity / $baseUnitMultiplier;
                            @endphp

                            <td class="text-end" style="font-size:11px!important;">
                                @if ($dsProduct?->dailyStockUnit?->baseUnit)
                                    @php
                                        $isMultiplierUnitExists = 1;
                                    @endphp

                                    (<strong>{{ App\Utils\Converter::format_in_bdt($dsProduct->quantity) }}/{{ $dsProduct?->dailyStockUnit?->baseUnit->code_name }}</strong>)={{ App\Utils\Converter::format_in_bdt($dailyStockQty) }}/{{ $dsProduct?->dailyStockUnit?->code_name }}
                                @else

                                    {{ App\Utils\Converter::format_in_bdt($dailyStockQty) }}/{{ $dsProduct?->dailyStockUnit?->code_name }}
                                @endif
                            </td>
                            <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($dsProduct->unit_cost_exc_tax * $baseUnitMultiplier) }}</td>
                            <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($dsProduct->tax_percent) }}%</td>
                            <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($dsProduct->unit_cost_inc_tax * $baseUnitMultiplier) }}</td>
                            <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($dsProduct->subtotal) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-6">
                <p style="font-size:11px!important;"><strong>@lang('menu.production_details') : </strong>{{ $dailyStock->production_details }}</p>
                <p style="font-size:11px!important;"><strong>@lang('menu.special_note') : </strong>{{ $dailyStock->note }}</p>
            </div>

            <div class="col-6">
                <table class="table modal-table table-sm">
                    <tbody>
                        <tr>
                            <td class="text-end" style="font-size:11px!important;"><strong>@lang('menu.total_item') :</strong></td>
                            <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($dailyStock->total_item) }}</td>
                        </tr>

                        <tr>
                            <td class="text-end" style="font-size:11px!important;"><strong>@lang('menu.total_qty') : </strong></td>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($dailyStock->total_qty) }}
                            </td>
                        </tr>

                        <tr>
                            <td class="text-end" style="font-size:11px!important;"><strong> @lang('menu.total_stock_value') : {{ json_decode($generalSettings->business, true)['currency'] }} </strong></td>
                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($dailyStock->total_stock_value) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div><br><br>

        <div class="row">
            <div class="col-4">
                <div class="details_area text-start">
                    <p class="borderTop"><strong>@lang('menu.checked_by')</strong></p>
                </div>
            </div>
            <div class="col-4">
                <div class="details_area text-center">
                    <p class="borderTop"><strong>@lang('menu.approved_by')</strong></p>
                </div>
            </div>
            <div class="col-4">
                <div class="details_area text-end">
                    <p class="borderTop"><strong>@lang('menu.signature_of_authority')</strong></p>
                </div>
            </div>
        </div>

        <div id="footer">
            <div class="row mt-1">
                <div class="col-4 text-start">
                    <small>@lang('menu.print_date') : {{ date(json_decode($generalSettings->business, true)['date_format']) }}</small>
                </div>

                <div class="col-4 text-center">
                    @if (config('company.print_on_sale'))
                        <small class="d-block">@lang('menu.software_by') <strong>@lang('menu.speedDigit_pvt_ltd') .</strong></small>
                    @endif
                </div>

                <div class="col-4 text-end">
                    <small>@lang('menu.print_time') : {{ date($timeFormat) }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
