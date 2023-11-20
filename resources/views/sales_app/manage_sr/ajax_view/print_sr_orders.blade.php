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
    th { font-size:11px!important; font-weight: 550!important;}
    td { font-size:10px;}
</style>
@php
    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
@endphp
<div class="row">
    <div class="col-md-12 text-center">
        <div class="heading_area" style="border-bottom: 1px solid black;">
            <img style="height: auto; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
            <p style="text-transform: uppercase;"><strong>{{ json_decode($generalSettings->business, true)['shop_name'] }}</strong></p>
            <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
            <p style="width: 60%; margin:0 auto;"><strong>@lang('menu.phone') : </strong> {{ json_decode($generalSettings->business, true)['phone'] }}</p>
        </div>

        <h6 style="margin-top: 10px;"><strong>@lang('menu.sales_order_statements') </strong></h6>

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
                <li><strong>{{ __("Sr.") }} : </strong> {{ $user->prefix.' '.$user->name.' '.$user->last_name }} </li>
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
    $totalItems = 0;
    $totalQty = 0;
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
                    <th class="text-start">@lang('menu.order_id')</th>
                    <th class="text-start">@lang('menu.customer')</th>
                    <th class="text-end">@lang('menu.total_qty')</th>
                    <th class="text-end">@lang('short.net_total_amt').</th>
                    <th class="text-end">@lang('menu.order_discount')</th>
                    <th class="text-end">@lang('menu.rate_type')</th>
                    <th class="text-end">@lang('menu.total_ordered_amount').</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">

                @php $previousDate = '';@endphp

                @foreach ($orders as $sale)

                    @php
                        $date = date($__date_format, strtotime($sale->order_date))
                    @endphp

                    @if ($previousDate != $date)

                        @php
                            $previousDate = $date;
                        @endphp

                        <thead>
                            <tr>
                                <th colspan="11" style="font-size: 12px!important; font-weight:600;">{{ $date }}</th>
                            </tr>
                        </thead>
                    @endif

                    <tr>
                        <td class="text-start">{{ $sale->order_id }}</td>

                        <td class="text-start">
                            {{ $sale->customer_name ? $sale->customer_name : 'Walk-In-Customer' }}
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($sale->total_ordered_qty) }}
                            @php
                                $totalQty += $sale->total_ordered_qty;
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

                        <td class="text-end">{{ $sale->all_price_type }}</td>

                        <td class="text-end">
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
                    <th class="text-end">@lang('menu.total') @lang('menu.ordered_qty')  : </th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalQty) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_ordered_value') : {{json_decode($generalSettings->business, true)['currency']}}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalNetTotal) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">Total Order Discount : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalOrderDiscount) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">Total Order Tax : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalOrderTax) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_ordered_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalSaleAmount) }}
                    </td>
                </tr>
            </thead>
        </table>
    </div>
</div>


<div style="position:fixed;bottom:0px;left:0px;width:100%;color: #000;" class="footer text-end">

    <div class="row">

        <div class="col-4 text-start">
            <small>@lang('menu.print_date') : {{ date(json_decode($generalSettings->business, true)['date_format']) }}</small>
        </div>

        <div class="col-4 text-center">
            <small>@lang('menu.powered_by') <b>@lang('menu.speedDigit_software_solution').</b></small>
        </div>

        <div class="col-4 text-end">
            <small>@lang('menu.print_time') :{{ date($timeFormat) }}</small>
        </div>
    </div>
</div>
