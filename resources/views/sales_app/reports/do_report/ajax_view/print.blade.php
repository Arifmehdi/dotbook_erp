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
    .print_table td { font-size:10px!important;}

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
    </div>
</div>

<div class="row mt-1">
    <div class="col-12 text-center">
        <h6 style="text-transform:uppercase;"><strong>@lang('menu.do_report') </strong></h6>
    </div>
</div>

<div class="row">
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

<div class="row">
    <div class="col-6">
        <small><strong>@lang('menu.sr') :</strong> {{ $userName ? $userName : auth()->user()->prefix.' '.auth()->user()->name.' '.auth()->user()->last_name }}</small>
    </div>

    <div class="col-6">
        <small><strong>@lang('menu.customer') :</strong> {{ $customerName }} </small>
    </div>
</div>

@php
    $totalItems = 0;
    $totalDoQty = 0;
    $totalDeliveredQty = 0;
    $totalLeftQty = 0;
    $TotalNetTotal = 0;
    $TotalOrderDiscount = 0;
    $TotalOrderTax = 0;
    $TotalShipmentCharge = 0;
    $TotalSaleAmount = 0;
    $TotalPaymentReceived = 0;
    $TotalSaleDue = 0;
@endphp

<div class="row mt-1">
    <div class="col-12">
        <table class="table report-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <th class="text-start">@lang('menu.do_id')</th>
                    <th class="text-start">@lang('menu.sr')</th>
                    <th class="text-start">@lang('menu.customer')</th>
                    <th class="text-end">@lang('menu.do_qty') (@lang('menu.as_base_unit'))</th>
                    <th class="text-end">@lang('short.delivered_qty') (@lang('menu.as_base_unit'))</th>
                    <th class="text-end">@lang('short.left_qty') (@lang('menu.as_base_unit'))</th>
                    <th class="text-end">@lang('short.net_total_amt').</th>
                    <th class="text-end">@lang('menu.shipment_charge')</th>
                    <th class="text-end">@lang('menu.order_discount')</th>
                    <th class="text-end">@lang('menu.rate_type')</th>
                    <th class="text-end">@lang('menu.total_ordered_amount').</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">

                @php $previousDate = '';@endphp

                @foreach ($dos as $do)

                    @php
                        $date = date($__date_format, strtotime($do->do_date))
                    @endphp

                    @if ($previousDate != $date)

                        @php
                            $previousDate = $date;
                        @endphp

                        <tr>
                            <th colspan="13" style="font-size: 11px!important; font-weight:600;">{{ $date }}</th>
                        </tr>
                    @endif

                    <tr>
                        <td class="text-start fw-bold">{{ $do->do_id }}</td>

                        <td class="text-start">{{ $do->u_prefix .' '.$do->u_name.' '.$do->u_last_name }}</td>

                        <td class="text-start">{{ $do->customer_name ? $do->customer_name : 'Walk-In-Customer' }}</td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($do->total_do_qty) }}
                            @php $totalDoQty += $do->total_do_qty; @endphp
                        </td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($do->total_delivered_qty) }}
                            @php $totalDeliveredQty += $do->total_delivered_qty; @endphp
                        </td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($do->do_total_left_qty) }}
                            @php $totalLeftQty += $do->do_total_left_qty; @endphp
                        </td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($do->net_total_amount) }}
                            @php $TotalNetTotal += $do->net_total_amount; @endphp
                        </td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($do->shipment_charge) }}
                            @php $TotalShipmentCharge += $do->shipment_charge; @endphp
                        </td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($do->order_discount_amount) }}
                            @php $TotalOrderDiscount += $do->order_discount_amount; @endphp
                        </td>

                        <td class="text-end fw-bold">{{ $do->all_price_type }}</td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($do->total_payable_amount) }}
                            @php $TotalSaleAmount += $do->total_payable_amount; @endphp
                        </td>
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
                    <th class="text-end">@lang('short.total_do_qty') : </th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalDoQty) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_delivered_qty') : </th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalDeliveredQty) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_left_qty') : </th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalLeftQty) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_ordered_value') : {{json_decode($generalSettings->business, true)['currency']}}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($TotalNetTotal) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total') @lang('menu.shipment_charge'): {{json_decode($generalSettings->business, true)['currency']}}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($TotalShipmentCharge) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total') @lang('menu.order_discount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($TotalOrderDiscount) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_ordered_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($TotalSaleAmount) }}</td>
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
