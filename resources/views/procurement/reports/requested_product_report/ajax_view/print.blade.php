<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    div#footer {position:fixed;bottom:20px;left:0px;right: 0;width:100%!important;height:0%!important;color:#2e2c2c; padding: 0; margin: 0;}
    @page {size:A4; margin-top: 0.8cm; margin-bottom: 35px; margin-left: 5px;margin-right: 5px;}
    .print_table th { font-size:9px!important; font-weight: 550!important;}
    .print_table tr td{font-size:9px!important;}

    h6 { font-size: 16px; }
    p {  font-size: 14px; }
    td {  color: black; }
</style>

@php
    $totalQty = 0;
    $totalPurchaseQty = 0;
    $totalReceivedQty = 0;
    $totalLeftQty = 0;
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
        <h6 style="text-transform:uppercase;"><strong>@lang('menu.requested_item_report') </strong></h6>
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
        <small><strong>@lang('menu.item') :</strong> {{ $search_product ? $search_product : 'All' }} </small>
    </div>

    <div class="col-4">
        <small><strong>@lang('menu.department') :</strong> {{ $department_name }} </small>
    </div>

    <div class="col-4">
        <small><strong>@lang('menu.requested_by') :</strong> {{ $requested_by_name }}</small>
    </div>
</div>

<div class="row mt-1">
    <div class="col-12">
        <table class="table report-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <th class="text-startx">@lang('menu.item')</th>
                    <th class="text-startx">@lang('menu.requisition_no')</th>
                    <th class="text-startx">@lang('menu.departments')</th>
                    <th class="text-startx">@lang('menu.requested_by')</th>
                    <th class="text-startx">@lang('menu.unit')</th>
                    <th class="text-end">@lang('menu.requisition_qty')</th>
                    <th class="text-end">@lang('menu.received_qty')</th>
                    <th class="text-end">@lang('menu.purchased_qty')</th>
                    <th class="text-end">@lang('short.left_qty')</th>
                </tr>
            </thead>
            @php $previousDate = ''; @endphp
            <tbody class="sale_print_product_list">
                @foreach ($requestedProducts as $requestedProduct)
                    @php
                        $totalQty += $requestedProduct->quantity;
                        $totalReceivedQty += $requestedProduct->received_qty;
                        $totalPurchaseQty += $requestedProduct->purchase_qty;
                        $totalLeftQty += $requestedProduct->left_qty;
                    @endphp

                    @if ($previousDate != $requestedProduct->date)

                        @php $previousDate = $requestedProduct->date; @endphp

                        <tr>
                            <th colspan="9">{{ date($__date_format, strtotime($requestedProduct->date)) }}</th>
                        </tr>
                    @endif

                    <tr>
                        <td>
                            @php
                                $variant = $requestedProduct->variant_name ? ' - ' . $requestedProduct->variant_name : '';
                            @endphp

                            {{ $requestedProduct->product_name . $variant }}
                        </td>

                        <td class="text-start">{{ $requestedProduct->requisition_no }}</td>
                        <td class="text-start">{{ $requestedProduct->department }}</td>
                        <td class="text-start">{{ Str::limit($requestedProduct->requester, 15, '..') }}</td>
                        <td><strong>{{ $requestedProduct->unit_code }}</strong></td>
                        @php
                            $baseUnitMultiplier = $requestedProduct->base_unit_multiplier ? $requestedProduct->base_unit_multiplier : 1;
                        @endphp
                        <td class="text-end"><strong>{{ App\Utils\Converter::format_in_bdt($requestedProduct->quantity / $baseUnitMultiplier) }}</strong></td>
                        <td class="text-end"><strong>{{ App\Utils\Converter::format_in_bdt($requestedProduct->received_qty / $baseUnitMultiplier) }}</strong></td>
                        <td class="text-end"><strong>{{ App\Utils\Converter::format_in_bdt($requestedProduct->purchase_qty / $baseUnitMultiplier) }}</strong></td>
                        <td class="text-end"><strong>{{ App\Utils\Converter::format_in_bdt($requestedProduct->left_qty / $baseUnitMultiplier) }}</strong></td>
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
                    <th class="text-end">@lang('menu.total') @lang('menu.requisition_quantity') : </th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalQty) }}</td>
                </tr>

                <tr>
                    <th class="text-end">Total Received Quantity : </th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalReceivedQty) }}</td>
                </tr>

                <tr>
                    <th class="text-end">Total Purchased Quantity : </th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalPurchaseQty) }}</td>
                </tr>

                <tr>
                    <th class="text-end">Total Left Quantity : </th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalLeftQty) }}</td>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div id="footer" style="height: 0px!important;">
    <div class="row" style="height: 0px!important;">
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
