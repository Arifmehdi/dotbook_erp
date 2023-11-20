<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }
    div#footer {position:fixed;bottom:20px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 5px;margin-right: 5px;}
    .print_table th { font-size:10px!important; font-weight: 550!important;}
    .print_table td { font-size:10px!important;}

    h6 { font-size: 16px; }
    p {  font-size: 14px; }
    td {  color: black; }
</style>
@php
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
    $totalQty = 0;
    $totalUnitPrice = 0;
    $totalSubTotal = 0;
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
        <p>{{ json_decode($generalSettings->business, true)['address'] }}</p>
        <p>
            <strong>@lang('menu.email') : </strong> {{ json_decode($generalSettings->business, true)['email'] }},
            <strong>@lang('menu.phone') : </strong> {{ json_decode($generalSettings->business, true)['phone'] }}
        </p>
    </div>
</div>

<div class="row mt-2">
    <div class="col-12 text-center">
        <h6 style="text-transform:uppercase;"><strong>@lang('menu.sold_items_report') </strong></h6>
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
    <div class="col-4">
        <small><strong>@lang('menu.item') :</strong> {{ $itemName ? $itemName : 'All' }} </small>
    </div>

    <div class="col-4">
        <small><strong>@lang('menu.customer') :</strong> {{ $customerName }} </small>
    </div>

    <div class="col-4">
        <small><strong>@lang('menu.sr') :</strong> {{ $userName }} </small>
    </div>
</div>

<div class="row mt-1">
    <div class="col-12">
        <table class="table report-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <th class="text-start">@lang('menu.vehicle_no').</th>
                    <th class="text-start">@lang('menu.item_name')</th>
                    <th class="text-start">@lang('menu.customer')</th>
                    <th class="text-start">@lang('menu.invoice_id')</th>
                    <th class="text-end">@lang('menu.qty')</th>
                    <th class="text-end">@lang('menu.price')</th>
                    <th class="text-end">@lang('menu.sub_total')</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @php
                    $previousDate = '';
                    $isSameGroup = true;
                    $lastDate = null;
                    $dateTotalQty = 0;
                    $dateTotalSubTotal = 0;
                    $lastDateTotalQty = 0;
                    $lastDateTotalSubTotal = 0;
                @endphp
                @foreach ($saleProducts as $sProduct)
                    @php
                        $baseUnitMultiplier = $sProduct->base_unit_multiplier ? $sProduct->base_unit_multiplier : 1;
                        $date = date($__date_format, strtotime($sProduct->report_date));
                        $isSameGroup = (null != $lastDate && $lastDate == $date) ? true : false;
                        $lastDate = $date;
                    @endphp

                    @if ($isSameGroup == true)

                        @php
                            $dateTotalQty += $sProduct->quantity / $baseUnitMultiplier;
                            $dateTotalSubTotal += $sProduct->subtotal;
                        @endphp
                    @else

                        @if($dateTotalQty != 0 || $dateTotalSubTotal != 0)

                            <tr>
                                <td colspan="4" class="fw-bold text-end">@lang('menu.total') : </td>
                                <td class="text-end fw-bold">{{ App\Utils\Converter::format_in_bdt($dateTotalQty) }}</td>
                                <td class="text-end fw-bold">{{ App\Utils\Converter::format_in_bdt($dateTotalSubTotal / $dateTotalQty) }}</td>
                                <td class="text-end fw-bold">{{ App\Utils\Converter::format_in_bdt($dateTotalSubTotal) }}</td>
                            </tr>
                        @endif

                        @php
                            $dateTotalQty = 0;
                            $dateTotalSubTotal = 0;
                        @endphp
                    @endif

                    @if ($previousDate != $date)

                        @php
                            $previousDate = $date;
                            $dateTotalQty += $sProduct->quantity / $baseUnitMultiplier;
                            $dateTotalSubTotal += $sProduct->subtotal;
                        @endphp

                        <tr>
                            <th colspan="8">{{ $date }}</th>
                        </tr>
                    @endif

                    <tr>
                        <td class="text-start">{{ $sProduct->do_car_number }}</td>
                        <td class="text-start">
                            @php

                                $variant = $sProduct->variant_name ? ' - ' . $sProduct->variant_name : '';
                            @endphp
                            {{ $sProduct->name . $variant }}
                        </td>

                        <td class="text-start">{{ $sProduct->customer_name }}</td>
                        <td class="text-start fw-bold">{{ $sProduct->invoice_id }}</td>
                        @php
                            $soldQty = $sProduct->quantity / $baseUnitMultiplier;
                            $totalQty += $soldQty;
                            $totalSubTotal += $sProduct->subtotal;
                        @endphp
                        <td class="text-end fw-bold">{!! App\Utils\Converter::format_in_bdt($soldQty) . '/' . $sProduct->unit_code !!}</td>
                        <td class="text-end fw-bold">{{ App\Utils\Converter::format_in_bdt($sProduct->unit_price_inc_tax * $baseUnitMultiplier) }}</td>
                        <td class="text-end fw-bold">{{ App\Utils\Converter::format_in_bdt($sProduct->subtotal) }}</td>
                    </tr>

                    @php
                        $__veryLastDate = date($__date_format, strtotime($veryLastDate));
                        $currentDate = $date;
                        if ($currentDate == $__veryLastDate) {

                            $lastDateTotalQty += $sProduct->quantity / $baseUnitMultiplier;
                            $lastDateTotalSubTotal += $sProduct->subtotal;
                        }
                    @endphp

                    @if($loop->index == $lastRow)

                        <tr>
                            <td colspan="4" class="fw-bold text-end">@lang('menu.total') : </td>
                            <td class="text-end fw-bold">{{ App\Utils\Converter::format_in_bdt($lastDateTotalQty) }}</td>
                            <td class="text-end fw-bold">{{ App\Utils\Converter::format_in_bdt($lastDateTotalSubTotal / $lastDateTotalQty) }}</td>
                            <td class="text-end fw-bold">{{ App\Utils\Converter::format_in_bdt($lastDateTotalSubTotal) }}</td>
                        </tr>
                    @endif
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
                    <th class="text-end">@lang('menu.total_net_amount') : {{json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalSubTotal) }}</td>
                </tr>

                <tr>
                    @php
                        $__totalQty = $totalQty ? $totalQty : 1;
                        $averageUnitPrice = $totalSubTotal / $__totalQty;
                    @endphp
                    <th class="text-end">@lang('menu.average_unit_price') : {{json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($averageUnitPrice) }}</td>
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