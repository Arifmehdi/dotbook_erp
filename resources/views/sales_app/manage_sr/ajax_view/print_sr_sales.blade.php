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
    th { font-size:9px!important;}
    td { font-size:9px;}

    h6 { font-size: 16px; }
    p {  font-size: 14px; }
    td {  color: black; }
</style>

<div class="row">
    <div class="col-md-12 text-center">
        <div class="heading_area" style="border-bottom: 1px solid black;">
            <img style="height: auto; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
            <p style="text-transform: uppercase;"><strong>{{ json_decode($generalSettings->business, true)['shop_name'] }}</strong></p>
            <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
            <p style="width: 60%; margin:0 auto;"><strong>@lang('menu.phone') : </strong> {{ json_decode($generalSettings->business, true)['phone'] }}</p>
        </div>

        <h6 style="margin-top: 10px;"><strong>@lang('menu.sale_statement') </strong></h6>

        @if ($fromDate && $toDate)
            <p style="margin-top: 10px;"><strong>@lang('menu.from') :</strong>
                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($fromDate)) }}
                <strong>To</strong> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($toDate)) }}
            </p>
        @endif
    </div>
</div>

<div class="sr_details_area">
    <div class="row">
        <div class="col-12">
            <ul class="list-unstyled">
                <li><strong>@lang('menu.sr'): </strong> {{ $user->prefix.' '.$user->name.' '.$user->last_name }} </li>
                <li><strong>@lang('menu.phone') : </strong> {{ $user->phone }}</li>
            </ul>
        </div>
    </div>
</div>

<div class="row mt-2">
    <p><strong>@lang('menu.filtered_by')</strong></p>
    <div class="col-12">
        <p><strong>@lang('menu.customer') :</strong> {{ $customerName }} </p>
    </div>
</div>

@php
    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';

    $totalItems = 0;
    $TotalNetTotal = 0;
    $TotalOrderDiscount = 0;
    $TotalOrderTax = 0;
    $TotalShipmentCharge = 0;
    $TotalSaleAmount = 0;
    $TotalPaymentReceived = 0;
    $TotalReturnedAmount = 0;
    $TotalSaleDue = 0;
@endphp

<div class="row mt-1">
    <div class="col-12">
        <table class="table report-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <th class="text-start">@lang('menu.invoice_id')</th>
                    <th class="text-end">@lang('menu.do_id')</th>
                    <th class="text-start">@lang('menu.customer')</th>
                    <th class="text-end">@lang('menu.total_item')</th>
                    <th class="text-end">@lang('short.net_total_amt').</th>
                    <th class="text-end">@lang('menu.sale_discount')</th>
                    <th class="text-end">@lang('menu.sale_tax')</th>
                    <th class="text-end">@lang('menu.shipment_charge')</th>
                    <th class="text-end">@lang('menu.total_invoice_amount')</th>

                    {{-- <th class="text-end">@lang('short.paid_amt').</th> --}}
                    <th class="text-end">@lang('menu.return_amount')</th>
                    {{-- <th class="text-end">@lang('short.due_amt').</th> --}}
                </tr>
            </thead>
            <tbody class="sale_print_product_list">

                @php $previousDate = '';@endphp

                @foreach ($sales as $sale)

                    @if ($previousDate != $sale->date)

                        @php $previousDate = $sale->date; @endphp

                        <thead>
                            <tr>
                                <th colspan="11" style="font-size: 12px!important; font-weight:600;">{{ date($__date_format, strtotime($sale->date)) }}</th>
                            </tr>
                        </thead>
                    @endif

                    <tr>
                        <td class="text-start">{{ $sale->invoice_id }}</td>
                        <td class="text-start">{{ $sale->do_id }}</td>

                        <td class="text-start">{{ $sale->customer_name ? $sale->customer_name : 'Walk-In-Customer' }}</td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($sale->total_item) }}
                            @php
                                $totalItems += $sale->total_item;
                            @endphp
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}
                            @php
                                $TotalNetTotal += $sale->net_total_amount;
                            @endphp
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }}
                            @php
                                $TotalOrderDiscount += $sale->order_discount_amount;
                            @endphp
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($sale->order_tax_amount). '(' . $sale->order_tax_percent . '%)' }}
                            @php
                                $TotalOrderTax += $sale->order_tax_amount;
                            @endphp
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($sale->shipment_charge) }}
                            @php
                                $TotalShipmentCharge += $sale->shipment_charge;
                            @endphp
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($sale->total_payable_amount) }}
                            @php
                                $TotalSaleAmount += $sale->total_payable_amount;
                            @endphp
                        </td>

                        {{-- <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($sale->paid) }}
                            @php
                                $TotalPaymentReceived += $sale->paid;
                            @endphp
                        </td> --}}

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($sale->sale_return_amount) }}
                            @php
                                $TotalReturnedAmount += $sale->sale_return_amount;
                            @endphp
                        </td>

                        {{-- <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($sale->due) }}
                            @php
                                $TotalSaleDue += $sale->due;
                            @endphp
                        </td> --}}
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
                    <th class="text-end">Total Sold Item : </th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalItems) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total') @lang('menu.net_total_amount') : {{json_decode($generalSettings->business, true)['currency']}}</th>
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
                    <th class="text-end">Total Sale Amount : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalSaleAmount) }}
                    </td>
                </tr>

                {{-- <tr>
                    <th class="text-end">Total Payment Received : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalPaymentReceived) }}
                    </td>
                </tr> --}}

                <tr>
                    <th class="text-end">@lang('menu.total_return') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalReturnedAmount) }}
                    </td>
                </tr>

                {{-- <tr>
                    <th class="text-end">@lang('menu.total_due') :{{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalSaleDue) }}
                    </td>
                </tr> --}}
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
                <small>@lang('menu.powered_by') <b>@lang('menu.speedDigit_software_solution').</b></small>
            @endif
        </div>

        <div class="col-4 text-end">
            <small>@lang('menu.print_time') :{{ date($timeFormat) }}</small>
        </div>
    </div>
</div>
