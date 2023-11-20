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
        <h6 style="text-transform:uppercase;"><strong>@lang('menu.stock_issue_report') </strong></h6>
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
    <div class="col-6">
        <small><strong>@lang('menu.department') :</strong> {{ $departmentName }} </small>
    </div>

    <div class="col-6">
        <small><strong>@lang('menu.event') :</strong> {{ $eventName }} </small>
    </div>
</div>

@php
    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
    $totalItem = 0;
    $totalQty = 0;
    $netTotalValue = 0;
@endphp

<div class="row mt-1">
    <div class="col-12">
        <table class="table report-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <tr>
                        <th>@lang('menu.voucher_no')</th>
                        <th>@lang('menu.receiver_dept')</th>
                        <th>@lang('menu.event')</th>
                        <th class="text-end">@lang('menu.total_item')</th>
                        <th class="text-end">@lang('menu.total_qty')</th>
                        <th class="text-end">@lang('menu.net_total_value')</th>
                    </tr>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @php
                    $previousDate = '';
                @endphp
                @foreach ($stockIssues as $stockIssue)

                    @if ($previousDate != $stockIssue->date)

                        @php
                            $previousDate = $stockIssue->date;
                        @endphp

                        <tr>
                            <th colspan="7">{{ date($__date_format, strtotime($stockIssue->date)) }}</th>
                        </tr>
                    @endif

                    <tr>
                        <td class="text-start">{{ $stockIssue->voucher_no }}</td>

                        <td class="text-start">{{ $stockIssue->dep_name }}</td>

                        <td class="text-start">{{ $stockIssue->event_name }}</td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($stockIssue->total_item) }}
                            @php
                                $totalItem += $stockIssue->total_item;
                            @endphp
                        </td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($stockIssue->total_qty) }}
                            @php
                                $totalQty += $stockIssue->total_qty;
                            @endphp
                        </td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($stockIssue->net_total_value) }}
                            @php
                                $netTotalValue += $stockIssue->net_total_value;
                            @endphp
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
                    <th class="text-end">@lang('menu.total_item') : </th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalItem) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_quantity') : </th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalQty) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.net_total_value') : {{json_decode($generalSettings->business, true)['currency']}}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($netTotalValue) }}</td>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div style="position:fixed;bottom:0px;left:0px;width:100%;color: #000; padding-top:0px;" class="footer text-end">

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
