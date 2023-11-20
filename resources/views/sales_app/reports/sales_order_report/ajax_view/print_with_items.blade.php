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
        <h6 style="text-transform:uppercase;"><strong>@lang('menu.sales_order_report_with_Items') </strong></h6>
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
    <p><strong>@lang('menu.filtered_by')</strong></p>

    <div class="col-6">
        <small><strong>@lang('menu.sr') :</strong>  {{ $userName ? $userName : auth()->user()->prefix.' '.auth()->user()->name.' '.auth()->user()->last_name }}</small>
    </div>

    <div class="col-6">
        <small><strong>@lang('menu.customer') :</strong> {{ $customerName }} </small>
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

<div class="row">
    <div class="col-12">
        <table class="table report-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <th class="text-start">
                        <p class="p-0 m-0" style="font-size:11px;!important">@lang('menu.date') * @lang('menu.order_id') * @lang('menu.sr') * (Customer * @lang('menu.customer_type') * @lang('menu.credit_limit') * @lang('menu.current_balance'))</p>
                        <p class="p-0 m-0" style="font-size:11px;!important">@lang('menu.ordered_qty') * @lang('menu.total_ordered_amount') * @lang('menu.rate_type') * @lang('menu.received_amount') * @lang('menu.order_note') * @lang('menu.comment') * @lang('menu.payment_note')</p>
                    </th>
                    <th class="text-start">@lang('menu.ordered_qty')</th>
                    <th class="text-end">@lang('menu.price')</th>
                    <th class="text-end">@lang('menu.type')</th>
                    <th class="text-end">@lang('menu.sub_total')</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">

                @php
                    $previousOrderId = '';
                    $previousSrId = '';
                @endphp
                @php $accountUtil = new App\Utils\AccountUtil(); @endphp
                @foreach ($sales as $sale)

                    @if ($previousSrId != $sale->u_id)
                        @php
                            $previousSrId = $sale->u_id;
                        @endphp
                        <tr>
                            <td colspan="5" style="font-size: 12px!important;"><strong>@lang('menu.sr') : {{ $sale->u_prefix.' '.$sale->u_name.' '.$sale->u_last_name }}</strong></td>
                        </tr>
                    @endif

                    @if ($previousOrderId != $sale->id)

                        @php
                            $previousOrderId = $sale->id;
                        @endphp

                        @php
                            $amounts = $accountUtil->accountClosingBalance($sale->cus_id, $sale->u_id);
                            $totalQty += $sale->total_ordered_qty;
                            $TotalNetTotal += $sale->net_total_amount;
                            $TotalOrderDiscount += $sale->order_discount_amount;
                            $TotalSaleAmount += $sale->total_payable_amount;
                        @endphp

                        <tr>
                            <td colspan="5" style="font-size: 10px!important;">

                                <p class="p-0 m-0" style="font-size:11px;!important"><span style="font-weight:600;"><span>&#9658;</span> </span>{{ date($__date_format, strtotime($sale->order_date)) }} *
                                    <span style="font-weight:600;"></span>{{ $sale->order_id }} *
                                    <span style="font-weight:600;"></span>{{ $sale->u_prefix.' '.$sale->u_name.' '.$sale->u_last_name }} *
                                    (<span style="font-weight:600;"></span>{{ $sale->customer_name }} *
                                    <span style="font-weight:600;"></span>
                                    {{ $sale->customer_type == 1 ? 'Non-Credit'  : 'Credit' }} *

                                    @if ($sale->customer_type == 2)

                                        <span style="font-weight:600;"></span>{{ App\Utils\Converter::format_in_bdt($sale->credit_limit) }} *
                                    @else

                                        N/A *
                                    @endif
                                    <span style="font-weight:600;" class="fw-bold"></span>{{ $amounts['closing_balance_string'] }})</p>
                                    <span style="font-weight:600;"></span>@lang('menu.qty') - {{ $sale->total_ordered_qty }} *
                                    <span style="font-weight:600;"></span>{{ $sale->total_payable_amount }} *
                                    <span style="font-weight:600;"></span>MR *
                                    <span style="font-weight:600;"></span>{{ $sale->paid }} *
                                    <span style="font-weight:600;"></span>{{ $sale->sale_note ? $sale->sale_note : 'N/A' }} *
                                    <span style="font-weight:600;"></span>{{ $sale->comment ? $sale->comment : 'N/A' }} *
                                    <span style="font-weight:600;"></span>{{ $sale->payment_note ? $sale->payment_note : 'N/A' }}
                                </p>
                            </td>
                        </tr>
                    @endif

                    <tr>
                        <td class="text-start" style="font-size: 10px!important; padding-left:10px!important;"> <strong>-</strong>  {{ $sale->p_name }}</td>

                        <td class="text-start" style="font-size: 10px!important;">
                            {{ $sale->ordered_quantity }}
                        </td>

                        <td class="text-end" style="font-size: 10px!important;">
                            {{ App\Utils\Converter::format_in_bdt($sale->item_price) }}
                        </td>

                        <td class="text-end" style="font-size: 10px!important;">
                            {{ $sale->item_price_type }}
                        </td>

                        <td class="text-end" style="font-size: 10px!important;">
                            {{ App\Utils\Converter::format_in_bdt($sale->item_subtotal) }}
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
                    <th class="text-end">@lang('menu.total') @lang('menu.ordered_qty')  : </th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalQty) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_net_amount') : {{json_decode($generalSettings->business, true)['currency']}}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalNetTotal) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_order_discount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalOrderDiscount) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_order_tax') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
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
