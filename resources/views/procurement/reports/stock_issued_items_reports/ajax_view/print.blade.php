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
    @page {size:A4; margin-top: 0.8cm; margin-bottom: 35px; margin-left: 5px;margin-right: 5px;}
    .print_table th { font-size:10px!important; font-weight: 550!important;}
    .print_table tr td{font-size:10px!important;}

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
        <h6 style="text-transform:uppercase;"><strong>@lang('menu.stock_issued_items_report') </strong></h6>
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
        <small><strong>@lang('menu.send_from') :</strong>  {{ $warehouseName }}</small>
    </div>

    <div class="col-4">
        <small><strong>@lang('menu.department') :</strong> {{ $departmentName }} </small>
    </div>

    <div class="col-4">
        <small><strong>@lang('menu.event')</strong> {{ $eventName }} </small>
    </div>
</div>

@php
    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';

    $totalQty = 0;
    $totalStockValue = 0;
@endphp
<div class="row mt-1">
    <div class="col-12">
        <table class="table report-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <tr>
                        <th>@lang('menu.date')</th>
                        <th>@lang('menu.item_name')</th>
                        <th>@lang('menu.voucher')</th>
                        <th>@lang('menu.issue_note')</th>
                        <th>@lang('menu.send_from')</th>
                        <th>@lang('menu.quantity')</th>
                        <th class="text-end">@lang('menu.unit_cost')</th>
                        <th class="text-end">@lang('menu.stock_value')</th>
                    </tr>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @php
                    $previousDepartmentId = '';
                @endphp

                @foreach ($stockIssueItems as $stockIssueItem)

                    @if ($previousDepartmentId != $stockIssueItem->id)

                        @php
                            $previousDepartmentId = $stockIssueItem->id;
                        @endphp

                        <tr>
                            <th colspan="8">{{ $stockIssueItem->dep_name }}</th>
                        </tr>
                    @endif
                    <tr>
                        <td class="text-start">{{ date($__date_format, strtotime($stockIssueItem->date))}}</td>
                        <td class="text-start">
                            @if ($stockIssueItem->v_name)

                                {{ $stockIssueItem->p_name . '-' . $stockIssueItem->v_name }}
                            @else

                                {{ $stockIssueItem->p_name }}
                            @endif
                        </td>

                        <td class="text-start">{{ $stockIssueItem->voucher_no }}</td>
                        <td class="text-start">{{ $stockIssueItem->note }}</td>
                        <td class="text-start">
                            @if ($stockIssueItem->w_name)

                                {!! $stockIssueItem->w_name .'/'. $stockIssueItem->w_code !!}
                            @else

                                 {{ json_decode($generalSettings->business, true)['shop_name'] . '' }}
                            @endif
                        </td>

                        <td class="fw-bold">
                            @php
                                $baseUnitMultiplier = $stockIssueItem->base_unit_multiplier ? $stockIssueItem->base_unit_multiplier : 1;
                                $issuedQty = $stockIssueItem->quantity / $baseUnitMultiplier;
                            @endphp
                                {{ App\Utils\Converter::format_in_bdt($issuedQty) }}/{{ $stockIssueItem->unit_code }}
                            @php
                                $totalQty += $issuedQty;
                            @endphp
                        </td>

                        <td class="text-end fw-bold">{{ App\Utils\Converter::format_in_bdt($stockIssueItem->unit_cost_inc_tax) }}</td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($stockIssueItem->subtotal) }}
                            @php
                                $totalStockValue += $stockIssueItem->subtotal;
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
                    <th class="text-end">@lang('menu.total_quantity') : </th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalQty) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_stock_value') : {{json_decode($generalSettings->business, true)['currency']}}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalStockValue) }}</td>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div id="footer">
    <div class="row mt-1">
        <div class="col-4 text-center">
            <small>@lang('menu.print_date') : {{ date(json_decode($generalSettings->business, true)['date_format']) }}</small>
        </div>

        <div class="col-4 text-center">
            @if (config('company.print_on_sale'))
                <small>@lang('menu.powered_by') <b>@lang('menu.speedDigit_software_solution').</b></small>
            @endif
        </div>

        <div class="col-4 text-center">
            <small>@lang('menu.print_time') :{{ date($timeFormat) }}</small>
        </div>
    </div>
</div>
