<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 15px;margin-right: 15px;}
    div#footer {position:fixed;bottom:24px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
    .print_table th { font-size:10px!important; font-weight: 550!important;}
    .print_table td { font-size:10px!important;}

    h6 { font-size: 16px; }
    p {  font-size: 14px; }
    td {  color: black; }
</style>
@php
    $totalNormal = 0;
    $totalAbnormal = 0;
    $totalAdjustment = 0;
    $totalRecovered = 0;
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
            <p>
                <strong>@lang('menu.email') : </strong> <b>{{ json_decode($generalSettings->business, true)['email'] }}</b>,
                <strong>@lang('menu.phone') : </strong> <b>{{ json_decode($generalSettings->business, true)['phone'] }}</b>
            </p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 text-center">
        <h6 style="margin-top: 10px; text-transform:uppercase;"><strong>@lang('menu.stock_adjustment_report')</strong></h6>
    </div>

    @if ($fromDate && $toDate)
        <div class="col-md-12 text-center">
            <p style="margin-top: 5px;"><strong>@lang('menu.from') :</strong>
                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($fromDate)) }}
                <strong>To</strong> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($toDate)) }}
            </p>
        </div>
    @endif
</div>

<div class="row" style="margin-top: 15px;">
    <div class="col-12">
        <table class="table report-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <th class="text-start">@lang('menu.voucher_no')</th>
                    <th class="text-start">@lang('menu.created_by')</th>
                    <th class="text-start">@lang('menu.type')</th>
                    <th class="text-end">@lang('menu.total_item')</th>
                    <th class="text-end">@lang('menu.total_qty')</th>
                    <th class="text-end">@lang('menu.total_amount')</th>
                    <th class="text-end">@lang('menu.total_recovered_amount')</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @php $previousDate = ''; @endphp
                @foreach ($adjustments as $ad)
                    @php
                        if($ad->type == 1) {

                            $totalNormal += $ad->net_total_amount;
                        } else {

                            $totalAbnormal += $ad->net_total_amount;
                        }

                        $totalAdjustment += $ad->net_total_amount;
                        $totalRecovered += $ad->recovered_amount;
                    @endphp

                    @if ($previousDate != $ad->date)

                        @php $previousDate = $ad->date; @endphp

                        <tr>
                            <th colspan="7">{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($ad->date)) }}</th>
                        </tr>
                    @endif

                    <tr>
                        <td class="text-start">{{ $ad->voucher_no }}</td>
                        <td class="text-start">{{ $ad->prefix . ' ' . $ad->name . ' ' . $ad->last_name }}</td>
                        <td class="text-start">{{ $ad->type == 1 ? 'Normal' : 'Abnormal' }}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($ad->total_item) }}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($ad->total_qty) }}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($ad->net_total_amount) }}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($ad->recovered_amount) }}</td>
                    </tr>
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
                    <th class="text-end">@lang('menu.total_normal') {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalNormal)  }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_abnormal') {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalAbnormal)  }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_adjusted_amount') {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalAdjustment)  }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_recovered_amount') {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalRecovered)  }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div id="footer">
    <div class="row mt-1">
        <div class="col-4 text-start">
            <small>@lang('menu.print_date')  {{ date(json_decode($generalSettings->business, true)['date_format']) }}</small>
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
