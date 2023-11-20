<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    div#footer {position:fixed;bottom:20px;left:0px;width:100%;height:0%;color:#2d2d2d;background:#333; padding: 0; margin: 0;}
    @page {size:A4; margin-top: 0.8cm; margin-bottom: 35px; margin-left: 5px;margin-right: 5px;}
    .print_table th { font-size:10px!important; font-weight: 550!important;}
    .print_table tr td{font-size:10px!important;}

    h6 { font-size: 16px; }
    p {  font-size: 14px; }
    td {  color: black; }
</style>
@php
    $totalWeightByScale = 0;
    $totalWastage = 0;
    $totalNetWeight = 0;
    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:sA';
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
        <h6 style="text-transform:uppercase;"><strong>@lang('menu.weighted_item_report') </strong></h6>
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

<div class="row mt-2">
    <div class="col-4">
        <small><strong>@lang('menu.item') :</strong> {{ $search_product ? $search_product : 'All' }} </small>
    </div>

    <div class="col-4">
        <small><strong>@lang('menu.supplier') : </strong> {{ $supplier_name }} </small>
    </div>

    <div class="col-4">
        <small><strong>@lang('menu.scale_operator') :</strong> {{ $user_name }}</small>
    </div>
</div>

<div class="row mt-1">
    <div class="col-12">
        <table class="table report-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <th>{{ __("Weight Time") }}</th>
                    <th>@lang('menu.item')</th>
                    <th>@lang('menu.voucher_no')</th>
                    <th>@lang('menu.vehicle_no')</th>
                    <th>@lang('menu.item_weight_by_scale')</th>
                    <th>@lang('menu.wastage')</th>
                    <th>@lang('menu.net_weight')</th>
                    <th>@lang('menu.remark')</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @php
                    $previousDate = '';
                @endphp
                @foreach ($weights as $weight)

                    @php
                       $date = date($__date_format, strtotime($weight->created_at))
                    @endphp

                    @if ($previousDate != $date)

                        @php
                            $previousDate = $date;
                        @endphp

                        <tr>
                            <th colspan="8">{{ $date }}</th>
                        </tr>
                    @endif

                    <tr>
                        <td>{{ date($timeFormat, strtotime($weight->created_at)) }}</td>
                        <td class="text-start">
                            @php
                                $variant = $weight->variant_name ? ' - ' . $weight->variant_name : '';
                                $totalWeightByScale += $weight->differ_weight;
                                $totalWastage += $weight->wast;
                                $totalNetWeight += $weight->net_weight;
                            @endphp
                           {{ ($weight->product_name ? $weight->product_name : 'Not yet to be available.') . $variant }}
                        </td>

                        <td class="text-start">{{ substr($weight->voucher_no, -9) }}</td>
                        <td class="text-start">{{ $weight->vehicle_number }}</td>
                        <td class="text-start fw-bold">{!! App\Utils\Converter::format_in_bdt($weight->differ_weight) . '/' . $weight->unit_code !!}</td>
                        <td class="text-start fw-bold">{!! App\Utils\Converter::format_in_bdt($weight->wast) . '/' . $weight->unit_code !!}</td>
                        <td class="text-start fw-bold">{!! App\Utils\Converter::format_in_bdt($weight->net_weight) . '/' . $weight->unit_code !!}</td>
                        <td>{{ $weight->remarks }}</td>
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
                    <th class="text-end">@lang('menu.total') @lang('menu.item_weight_by_scale') :</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalWeightByScale) }}</td>
                </tr>

                <tr>
                    <th class="text-end">Total Wastage : </th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalWastage) }}</td>
                </tr>

                <tr>
                    <th class="text-end">Total Net Weight : </th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalNetWeight) }}</td>
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
            <small>@lang('menu.print_time') :{{ date($timeFormat) }}</small>
        </div>
    </div>
</div>
