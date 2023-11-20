<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto, font-size:10px; }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 10px;margin-right: 10px;}
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
        <h6 style="text-transform:uppercase;"><strong>@lang('menu.sr_wise_sales_order_report') </strong></h6>
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
        <small><strong>{{ __('S/r') }} :</strong>  {{ $userName ? $userName : auth()->user()->prefix.' '.auth()->user()->name.' '.auth()->user()->last_name }}</small>
    </div>

    <div class="col-4">
        <small><strong>{{ __('Customer') }} :</strong> {{ $customerName }} </small>
    </div>

    <div class="col-4">
        <small><strong>{{ __('Seles Ledger A/c') }} :</strong> {{ $saleAccountName }} </small>
    </div>
</div>

@php
    $totalItems = 0;
    $totalQty = 0;
    $TotalNetTotal = 0;
    $TotalOrderDiscount = 0;
    $TotalOrderTax = 0;
    $TotalShipmentCharge = 0;
    $totalOrderedAmount = 0;
    $TotalPaymentReceived = 0;
    $TotalSaleDue = 0;
@endphp

<div class="row mt-1">
    <div class="col-12">
        <table class="table report-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <th class="text-start">@lang('menu.date')</th>
                    <th class="text-start">@lang('menu.order_id')</th>
                    <th class="text-start">@lang('menu.customer')</th>
                    <th class="text-end">@lang('menu.total_qty') (@lang('menu.as_base_unit'))</th>
                    <th class="text-end">@lang('short.net_total_amt').</th>
                    <th class="text-end">@lang('menu.order_discount')</th>
                    <th class="text-end">@lang('menu.order_tax')</th>
                    <th class="text-end">@lang('menu.rate_type')</th>
                    <th class="text-end">@lang('menu.total_ordered_amount').</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">

                @php
                    $previousUserId = '';
                    $userTotalQty = 0;
                    $userTotalNetAmount = 0;
                    $userTotalDiscountAmount = 0;
                    $userTotalTaxAmount = 0;
                    $userTotalOrderedAmount = 0;
                    $isSameUser = true;
                    $lastUserId = null;
                    $lastUserTotalQty = 0;
                    $lastUserTotalNetAmount = 0;
                    $lastUserTotalDiscountAmount = 0;
                    $lastUserTotalTaxAmount = 0;
                    $lastUserTotalOrderedAmount = 0;
                @endphp

                @foreach ($sales as $sale)
                    @php
                        $isSameUser = (null != $lastUserId && $lastUserId == $sale->sr_user_id) ? true : false;
                        $lastUserId = $sale->sr_user_id;
                    @endphp

                    @if ($isSameUser == true)

                        @php
                            $userTotalQty += $sale->total_ordered_qty;
                            $userTotalNetAmount += $sale->net_total_amount;
                            $userTotalDiscountAmount += $sale->order_discount_amount;
                            $userTotalTaxAmount += $sale->order_tax_amount;
                            $userTotalOrderedAmount += $sale->total_payable_amount;
                        @endphp
                    @else

                        @if ($userTotalQty != 0 || $userTotalNetAmount != 0 || $userTotalDiscountAmount != 0 || $userTotalTaxAmount != 0 || $userTotalOrderedAmount != 0)
                            <tr>
                                <td colspan="3" class="fw-bold text-end">@lang('menu.total') : </td>
                                <td class="fw-bold text-end">{{ App\Utils\Converter::format_in_bdt($userTotalQty) }}</td>
                                <td class="fw-bold text-end">{{ App\Utils\Converter::format_in_bdt($userTotalNetAmount) }}</td>
                                <td class="fw-bold text-end">{{ App\Utils\Converter::format_in_bdt($userTotalDiscountAmount) }}</td>
                                <td class="fw-bold text-end">{{ App\Utils\Converter::format_in_bdt($userTotalTaxAmount) }}</td>
                                <td class="fw-bold text-end">---</td>
                                <td class="fw-bold text-end">{{ App\Utils\Converter::format_in_bdt($userTotalOrderedAmount) }}</td>
                            </tr>
                        @endif

                        @php
                            $userTotalQty = 0;
                            $userTotalNetAmount = 0;
                            $userTotalDiscountAmount = 0;
                            $userTotalTaxAmount = 0;
                            $userTotalOrderedAmount = 0;
                        @endphp
                    @endif

                    @if ($previousUserId != $sale->sr_user_id)

                        @php
                            $previousUserId = $sale->sr_user_id;
                            $userTotalQty += $sale->total_ordered_qty;
                            $userTotalNetAmount += $sale->net_total_amount;
                            $userTotalDiscountAmount += $sale->order_discount_amount;
                            $userTotalTaxAmount += $sale->order_tax_amount;
                            $userTotalOrderedAmount += $sale->total_payable_amount;
                        @endphp

                        <tr>
                            <td colspan="12"><strong>@lang('menu.sr') {{ $sale->u_prefix .' '. $sale->u_name .' '. $sale->u_last_name }}</strong>{{ '-'.$sale->u_phone  }}</td>
                        </tr>
                    @endif

                    <tr>
                        <td class="text-start">{{ date($__date_format, strtotime($sale->order_date)) }}</td>
                        <td class="text-start fw-bold">{{ $sale->order_id }}</td>

                        <td class="text-start">
                            {{ $sale->customer_name ? $sale->customer_name : 'Walk-In-Customer' }}
                        </td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($sale->total_ordered_qty) }}
                            @php
                                $totalQty += $sale->total_ordered_qty;
                            @endphp
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

                        <td class="text-end fw-bold">{{ $sale->all_price_type }}</td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($sale->total_payable_amount) }}
                            @php
                                $totalOrderedAmount += $sale->total_payable_amount;
                            @endphp
                        </td>
                    </tr>

                    @php
                        $__veryLastUserId = $veryLastUserId;
                        $currentUserId = $sale->sr_user_id;
                        if ($currentUserId == $__veryLastUserId) {

                            $lastUserTotalQty += $sale->total_ordered_qty;
                            $lastUserTotalNetAmount += $sale->net_total_amount;
                            $lastUserTotalDiscountAmount += $sale->order_discount_amount;
                            $lastUserTotalTaxAmount += $sale->order_tax_amount;
                            $lastUserTotalOrderedAmount += $sale->total_payable_amount;
                        }
                    @endphp

                    @if($loop->index == $lastRow)

                        <tr>
                            <td colspan="3" class="fw-bold text-end">@lang('menu.total') : </td>
                            <td class="fw-bold text-end">{{ App\Utils\Converter::format_in_bdt($lastUserTotalQty) }}</td>
                            <td class="fw-bold text-end">{{ App\Utils\Converter::format_in_bdt($lastUserTotalNetAmount) }}</td>
                            <td class="fw-bold text-end">{{ App\Utils\Converter::format_in_bdt($lastUserTotalDiscountAmount) }}</td>
                            <td class="fw-bold text-end">{{ App\Utils\Converter::format_in_bdt($lastUserTotalTaxAmount) }}</td>
                            <td class="fw-bold text-end">---</td>
                            <td class="fw-bold text-end">{{ App\Utils\Converter::format_in_bdt($lastUserTotalOrderedAmount) }}</td>
                        </tr>
                    @endif
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
                    <th class="text-end">{{ __("Total Ordered Qty") }} : </th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalQty) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">{{ __("Total Net Amount") }} : {{json_decode($generalSettings->business, true)['currency']}}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalNetTotal) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">{{ __("Total Order Discount Value") }} : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalOrderDiscount) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">{{ __("Total Order Tax") }} : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalOrderTax) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_ordered_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalOrderedAmount) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.average_unit_price') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        @php
                            $__totalQty = $totalQty ? $totalQty : 1;
                            $averageUnitPrice = $totalOrderedAmount / $__totalQty;
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
