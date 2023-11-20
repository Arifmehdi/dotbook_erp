<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 10px;margin-right: 10px;}
    div#footer {position:fixed;bottom:24px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
    .print_table tr td{font-size: 10px!important;}
    .print_table tr th{font-size: 10px!important;}

    h6 { font-size: 16px; }
    p {  font-size: 14px; }
    td {  color: black; }
</style>

@php
    $totalExpense = 0;
    $totalPaid = 0;
    $totalDue = 0;

    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp

<div class="heading_area" style="border-bottom: 1px solid black;">
    <div class="row">
        <div class="col-4">
            <img style="height: auto; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
        </div>

        <div class="col-8 text-end">
            <p style="text-transform: uppercase;"><strong>{{ json_decode($generalSettings->business, true)['shop_name'] }}</strong></p>
            <p><b>{{ json_decode($generalSettings->business, true)['address'] }}</b></p>
            <p><strong>@lang('menu.email') : </strong> <b>{{ json_decode($generalSettings->business, true)['email'] }}</b>,
            <strong>@lang('menu.phone') : </strong> <b>{{ json_decode($generalSettings->business, true)['phone'] }}</b></p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 text-center">
        <h6 style="margin-top: 10px; text-transform:uppercase;"><strong>@lang('menu.warehouse_stock_report')</strong></h6>
    </div>
</div>

<div class="row">
    <p><strong>@lang('menu.filtered_by')</strong></p>
    <div class="col-3">
        <small><strong>@lang('menu.warehouse') :</strong> {{ $warehouseName }} </small>
    </div>

    <div class="col-3">
        <small><strong>@lang('menu.category') :</strong> {{ $categoryName }} </small>
    </div>

    <div class="col-3">
        <small><strong>@lang('menu.subcategory') :</strong> {{ $subcategoryName }}</small>
    </div>

    <div class="col-2">
        <small><strong>@lang('menu.brand') :</strong> {{ $brandName }}</small>
    </div>

    <div class="col-1">
        <small><strong>@lang('menu.unit') :</strong> {{ $unitName }}</small>
    </div>
</div>

<div class="row" style="margin-top: 15px;">
    <div class="col-12">
        <table class="table report-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <th class="text-start">@lang('menu.item_code')</th>
                    <th class="text-start">@lang('menu.item')</th>
                    <th class="text-end">@lang('menu.current_stock')</th>
                    <th class="text-end">@lang('menu.per_unit_cost') (@lang('menu.by_wt_avg'))</th>
                    <th class="text-end">@lang('menu.stock_value') (@lang('menu.by_wt_avg'))</th>
                </tr>
            </thead>
            @php
                $proviousWarehouseId = '';
                $totalQty = 0;
                $totalStockValue = 0;
            @endphp
            <tbody class="sale_print_product_list">
                @foreach ($warehouse_stock as $row)

                    @if ($proviousWarehouseId != $row->w_id)
                        @php
                            $proviousWarehouseId = $row->w_id;
                        @endphp
                        <tr>
                            <td colspan="6"><strong>{{ $row->w_name.'/'.$row->w_code }}</strong></td>
                        </tr>
                    @endif

                    @if ($row->variant_name)

                        <tr>
                            <td class="text-start">{{ $row->variant_code }}</td>
                            <td class="text-start">{{ $row->name.'-'.$row->variant_name }}</td>

                            <td class="text-end fw-bold">
                                @php
                                    $totalQty += $row->variant_quantity;
                                @endphp
                                {{ App\Utils\Converter::format_in_bdt($row->variant_quantity) }}/{{ $row->unit_code_name }}
                            </td>

                            <td class="text-end fw-bold">
                                @php
                                    $totalInStock = $row->variant_purchased_qty;
                                    $totalPrice = $row->all_variant_purchased_cost;
                                    $wtAvgPrice = $totalInStock > 0 ? $totalPrice / $totalInStock : 0;
                                @endphp
                                {{ App\Utils\Converter::format_in_bdt($wtAvgPrice) }}
                            </td>

                            <td class="text-end fw-bold">
                                @php

                                    $currentStock = $row->variant_quantity;
                                    $totalInStock = $row->variant_purchased_qty;
                                    $totalPrice = $row->all_variant_purchased_cost;

                                    $wtAvgPrice = $totalInStock > 0 ? $totalPrice / $totalInStock : 0;
                                    $currentStockValue = $wtAvgPrice * $currentStock;

                                    $totalStockValue += $currentStockValue;
                                @endphp
                                {{ App\Utils\Converter::format_in_bdt($currentStockValue) }}
                            </td>
                        </tr>
                    @else

                        <tr>
                            <td class="text-start">{{ $row->product_code }}</td>
                            <td class="text-start">{{ $row->name }}</td>

                            <td class="text-end fw-bold">
                                {{ App\Utils\Converter::format_in_bdt($row->product_quantity) }}/{{ $row->unit_code_name }}
                                @php
                                    $totalQty += $row->product_quantity;
                                @endphp
                            </td>

                            <td class="text-end fw-bold">
                                @php
                                    $totalInStock = $row->product_purchased_qty;
                                    $totalPrice = $row->all_product_purchased_cost;

                                    $wtAvgPrice = $totalInStock > 0 ? $totalPrice / $totalInStock : 0;
                                @endphp
                                {{ App\Utils\Converter::format_in_bdt($wtAvgPrice) }}
                            </td>

                            <td class="text-end fw-bold">
                                @php
                                    $currentStock = $row->product_quantity;
                                    $totalInStock = $row->product_purchased_qty;
                                    $totalPrice = $row->all_product_purchased_cost;

                                    $wtAvgPrice = $totalInStock > 0 ? $totalPrice / $totalInStock : 0;
                                    $currentStockValue = $wtAvgPrice * $currentStock;
                                    $totalStockValue += $currentStockValue;
                                @endphp
                                {{ App\Utils\Converter::format_in_bdt($currentStockValue) }}
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-6">

    </div>

    <div class="col-6">
        <table class="table report-table table-sm table-bordered print_table">
            <tbody>
                <tr>
                    <th class="text-end">@lang('menu.total_quantity')</th>
                    <td class="text-end fw-bold">
                        {{ App\Utils\Converter::format_in_bdt($totalQty)  }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_stock_value') {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end fw-bold">
                        {{ App\Utils\Converter::format_in_bdt($totalStockValue)  }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div id="footer">
    <div class="row mt-1">
        <div class="col-4 text-start">
            <small>@lang('menu.print_date') : {{ date(json_decode($generalSettings->business, true)['date_format']) }}</small>
        </div>

        <div class="col-4 text-center">
            @if (config('company.print_on_sale'))
                <small>@lang('menu.powered_by') <strong>@lang('menu.speedDigit_software_solution')</strong></small>
            @endif
        </div>

        <div class="col-4 text-end">
            <small>@lang('menu.print_time') : {{ date($timeFormat) }}</small>
        </div>
    </div>
</div>
