<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto, font-size:10px; }
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
        <h6 style="text-transform:uppercase;"><strong>@lang('menu.sales_report') </strong></h6>
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

<div class="row">
    <div class="col-4">
        <small><strong>@lang('menu.customer') :</strong> {{ $customerName }} </small>
    </div>

    <div class="col-4">
        <small><strong>@lang('menu.sr') :</strong> {{ $userName ? $userName : auth()->user()->prefix.' '.auth()->user()->name.' '.auth()->user()->last_name }}</small>
    </div>

    <div class="col-4">
        <small><strong>@lang('menu.sales_ledger_ac') :</strong> {{ $saleAccountName }} </small>
    </div>
</div>

@php
    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s A';

    $totalQty = 0;
    $totalWeight = 0;
    $TotalNetTotal = 0;
    $TotalOrderDiscount = 0;
    $TotalOrderTax = 0;
    $TotalShipmentCharge = 0;
    $TotalSaleAmount = 0;
@endphp

<div class="row mt-1">
    <div class="col-12">
        <table class="table report-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <th class="text-start">@lang('menu.invoice_id')</th>
                    <th class="text-start">@lang('menu.do_id')</th>
                    <th class="text-start">@lang('menu.customer')</th>
                    <th class="text-start">@lang('menu.sr')</th>
                    <th class="text-end">@lang('menu.total_qty') (@lang('menu.as_base_unit'))</th>
                    <th class="text-end">@lang('menu.net_weight')</th>
                    <th class="text-end">@lang('short.net_total_amt').</th>
                    <th class="text-end">@lang('menu.sale_discount')</th>
                    <th class="text-end">@lang('menu.sale_tax')</th>
                    <th class="text-end">@lang('menu.shipment_charge')</th>
                    <th class="text-end">@lang('menu.total_amount')</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">

                @php $previousDate = '';@endphp

                @foreach ($sales as $sale)

                    @if ($previousDate != $sale->date)

                        @php $previousDate = $sale->date; @endphp

                        <tr>
                            <th colspan="11" style="font-size: 11px!important; font-weight:600;">{{ date($__date_format, strtotime($sale->date)) }}</th>
                        </tr>
                    @endif

                    <tr>
                        <td class="text-start">{{ $sale->invoice_id }}</td>
                        <td class="text-start">{{ $sale->do_id }}</td>
                        <td class="text-start">{{ $sale->customer_name ? $sale->customer_name : 'Walk-In-Customer' }}</td>

                        <td class="text-start">{{ $sale->sr_prefix . ' ' . $sale->sr_name . ' ' . $sale->sr_last_name }}</td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($sale->total_sold_qty) }}
                            @php
                                $totalQty += $sale->total_sold_qty;
                            @endphp
                        </td>

                        <td class="text-end fw-bold">
                            @if($sale->first_weight)
                                @php

                                    $netWeight = $sale->second_weight - $sale->first_weight;
                                    $totalWeight += $netWeight;
                                @endphp

                                {{ App\Utils\Converter::format_in_bdt($netWeight) }}
                            @endif
                        </td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}
                            @php
                                $TotalNetTotal += $sale->net_total_amount;
                            @endphp
                        </td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }}
                            @php
                                $TotalOrderDiscount += $sale->order_discount_amount;
                            @endphp
                        </td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($sale->order_tax_amount). '(' . $sale->order_tax_percent . '%)' }}
                            @php
                                $TotalOrderTax += $sale->order_tax_amount;
                            @endphp
                        </td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($sale->shipment_charge) }}
                            @php
                                $TotalShipmentCharge += $sale->shipment_charge;
                            @endphp
                        </td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($sale->total_payable_amount) }}
                            @php
                                $TotalSaleAmount += $sale->total_payable_amount;
                            @endphp
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- <div style="page-break-after: {{ count($sales) > 30 ? 'always' : '' }};"></div> --}}
<div class="row">
    <div class="col-6"></div>
    <div class="col-6">
        <table class="table report-table table-sm table-bordered print_table">
            <thead>

                <tr>
                    <th class="text-end">@lang('menu.total_sold_qty') : </th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalQty) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_net_weight') : </th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalWeight) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_net_amount') : {{json_decode($generalSettings->business, true)['currency']}}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalNetTotal) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_sale_discount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalOrderDiscount) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_sale_tax') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalOrderTax) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total') @lang('menu.shipment_charge') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalShipmentCharge) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_sold_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalSaleAmount) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.average_unit_price') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        @php
                            $averageUnitPrice = $TotalSaleAmount / $totalQty;
                        @endphp
                        {{ App\Utils\Converter::format_in_bdt($averageUnitPrice) }}
                    </td>
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
