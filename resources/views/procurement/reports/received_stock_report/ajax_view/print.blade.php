<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto;}
        td    { page-break-inside:avoid; page-break-after:auto;}
        th    { page-break-inside:avoid; page-break-after:auto; font-size: 1px;}
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }
    div#footer {position:fixed;bottom:20px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}

    @page {size:A4; margin-top: 0.8cm; margin-bottom: 35px; margin-left: 5px;margin-right: 5px;}
    .print_table th { font-size:9px!important; font-weight: 550!important;}
    .print_table tr td{font-size: 9px!important;}

    h6 { font-size: 16px; }
    p {  font-size: 14px; }
    td {  color: black; }
</style>
@php
    $totalQty = 0;
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
        <h6 style="text-transform:uppercase;"><strong>@lang('menu.received_stocks_report') </strong></h6>
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
        <small><strong>@lang('menu.item') :</strong> {{ $searchProduct ? $searchProduct : 'All' }} </small>
    </div>

    <div class="col-4">
        <small><strong>@lang('menu.supplier') : </strong> {{ $supplierName }} </small>
    </div>

    <div class="col-4">
        <small><strong>@lang('menu.created_by') : </strong> {{ $userName }} </small>
    </div>
</div>

<div class="row mt-1">
    <div class="col-12">
        <table class="table report-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <tr>
                        <th>@lang('menu.item_name')</th>
                        <th>@lang('menu.stored_location')</th>
                        <th>@lang('menu.rs_voucher')</th>
                        <th>@lang('menu.requisition_no')</th>
                        <th>@lang('menu.department')</th>
                        <th>@lang('menu.supplier')</th>
                        {{-- <th>@lang('menu.created_by')</th> --}}
                        <th>@lang('menu.received_qty')</th>
                        <th>@lang('menu.lot_number')</th>
                        <th>@lang('menu.short_description')</th>
                    </tr>
                </tr>
            </thead>

            <tbody>
                @php
                    $previousDate = '';
                    $sum = 0;
                    $isSameGroup = true;
                    $lastDate = null;
                    $lastSum = 0;
                @endphp
                @foreach ($receiveStockProducts as $rsProduct)
                    @php
                        $baseUnitMultiplier = $rsProduct->base_unit_multiplier ? $rsProduct->base_unit_multiplier : 1;
                        $totalQty += $rsProduct->quantity / $baseUnitMultiplier;
                        $date = date($__date_format, strtotime($rsProduct->date_ts));
                        $isSameGroup = (null != $lastDate && $lastDate == $date) ? true : false;
                        $lastDate = $date;
                    @endphp

                    @if ($isSameGroup == true)

                        @php
                            $sum += $rsProduct->quantity / $baseUnitMultiplier;
                        @endphp
                    @else

                        @if($sum != 0)

                            <tr>
                                <td colspan="6" class="fw-bold text-end">@lang('menu.total') : </td>
                                <td class="fw-bold">{{ App\Utils\Converter::format_in_bdt($sum) }}</td>
                                <td class="fw-bold">---</td>
                                <td class="fw-bold">---</td>
                            </tr>
                        @endif

                        @php $sum = 0; @endphp
                    @endif

                    @if ($previousDate != $date)
                        @php
                            $previousDate = $date;
                            $sum += $rsProduct->quantity / $baseUnitMultiplier;
                        @endphp

                        <tr>
                            <th colspan="9">{{ $date }} </th>
                        </tr>
                    @endif

                    <tr>
                        <td class="text-start">
                            @php
                                $variant = $rsProduct->variant_name ? ' - ' . $rsProduct->variant_name : '';
                            @endphp
                            {{ $rsProduct->name . $variant }}
                        </td>

                        <td class="text-start">
                            @if ($rsProduct->w_name)

                                {!! $rsProduct->w_name .'/'. $rsProduct->w_code . '<b>(WH)</b>' !!}
                            @else

                                {{ json_decode($generalSettings->business, true)['shop_name'] }}
                            @endif
                        </td>
                        <td class="text-start">{{ $rsProduct->voucher_no }}</td>
                        <td class="text-start">{{ $rsProduct->requisition_no }}</td>
                        <td class="text-start">{{ $rsProduct->department_name }}</td>
                        <td class="text-start">{{ Str::limit($rsProduct->supplier_name, 25) }}</td>
                        {{-- <td class="text-start">{{ $rsProduct->u_prefix . ' ' . $rsProduct->u_name . ' ' . $rsProduct->u_last_name }}</td> --}}
                        <td class="text-start"><strong>{{ App\Utils\Converter::format_in_bdt($rsProduct->quantity / $baseUnitMultiplier) . '/' . $rsProduct->unit_code }}</strong></td>
                        <td class="text-end">{{ $rsProduct->lot_number }}</td>
                        <td class="text-end">{{ $rsProduct->short_description }}</td>
                    </tr>

                    @php
                        $__veryLastDate = date($__date_format, strtotime($veryLastDate));
                        $currentDate = $date;
                        if ($currentDate == $__veryLastDate) {

                            $lastSum += $rsProduct->quantity / $baseUnitMultiplier;
                        }
                    @endphp

                    @if($loop->index == $lastRow)

                        <tr>
                            <td colspan="6" class="fw-bold text-end">@lang('menu.total') : </td>
                            <td class="fw-bold">{{ App\Utils\Converter::format_in_bdt($lastSum) }}</td>
                            <td class="fw-bold">---</td>
                            <td class="fw-bold">---</td>
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
                    <th class="text-end">@lang('menu.total_received_quantity') :</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalQty) }}</td>
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
