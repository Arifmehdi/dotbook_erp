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
    @page {size:A4; margin-top: 0.8cm; margin-bottom: 35px; margin-left: 10px;margin-right: 10px;}

    h6 { font-size: 16px; }
    p {  font-size: 14px; }
    td {  color: black; }
</style>
@php
    $totalOrderedQty = 0;
    $totalDeliveredQty = 0;
    $totalLeftQty = 0;
    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
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
        <h6 style="text-transform:uppercase;"><strong>@lang('menu.ordered_item_quantities_report') </strong></h6>
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

<div class="row">
    <div class="col-4">
        <small><strong>@lang('menu.item') :</strong> {{ $search_product ? $search_product : 'All' }} </small>
    </div>

    <div class="col-4">
        <small><strong>@lang('menu.customer') :</strong> {{ $customer_name }} </small>
    </div>

    <div class="col-4">
        <small><strong>@lang('menu.sr') :</strong> {{ $user_name ? $user_name : auth()->user()->prefix.' '.auth()->user()->name.' '.auth()->user()->last_name }}</small>
    </div>
</div>

<div class="row" style="margin-top: 15px;">
    <div class="col-12">
        <table class="table report-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <th style="font-size: 11px!important;">@lang('menu.item')</th>
                    <th style="font-size: 11px!important;">@lang('menu.ordered_quantity') (@lang('menu.as_base_unit'))</th>
                    <th style="font-size: 11px!important;">@lang('menu.delivered_quantity') (@lang('menu.as_base_unit'))</th>
                    <th style="font-size: 11px!important;">@lang('menu.left_quantity') (@lang('menu.as_base_unit'))</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @foreach ($orderedProductQty as $row)
                    @php
                        $variant = $row->variant_name ? ' - ' . $row->variant_name : '';
                    @endphp
                    <tr>
                        <td style="font-size: 10px!important;">
                            {{ $row->product_name . $variant }}
                        </td>

                        <td class="fw-bold" style="font-size: 10px!important;">
                            {{ App\Utils\Converter::format_in_bdt($row->ordered_qty) }}/{{$row->unit}}
                            @php $totalOrderedQty += $row->ordered_qty; @endphp
                        </td>

                        <td class="fw-bold" style="font-size: 10px!important;">
                            {{ App\Utils\Converter::format_in_bdt($row->delivered_qty) }}/{{$row->unit}}
                            @php $totalDeliveredQty += $row->delivered_qty; @endphp
                        </td>

                        <td class="fw-bold" style="font-size: 10px!important;">
                            {{ App\Utils\Converter::format_in_bdt(($row->left_qty > 0 ? $row->left_qty : $row->ordered_qty)) }}/{{$row->unit}}
                            @php $totalLeftQty += ($row->left_qty > 0 ? $row->left_qty : $row->ordered_qty); @endphp
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
                    <th class="text-end">Total Ordered Quantity :</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalOrderedQty) }}</td>
                </tr>

                <tr>
                    <th class="text-end">Total Delivered Quantity :</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalDeliveredQty) }}</td>
                </tr>

                <tr>
                    <th class="text-end">Total Left Quantity :</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalLeftQty) }}</td>
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
                <small>@lang('menu.powered_by') <b>@lang('menu.speedDigit_software_solution').</b></small>
            @endif
        </div>

        <div class="col-4 text-end">
            <small>@lang('menu.print_time') :{{ date($timeFormat) }}</small>
        </div>
    </div>
</div>
