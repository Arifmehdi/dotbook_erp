<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 35px; margin-left: 15px;margin-right: 15px;}
    div#footer {position:fixed;bottom:24px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
</style>

@php
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
    $allTotalPurchase = 0;
    $allTotalPaid = 0;
    $allTotalOpDue = 0;
    $allTotalDue = 0;
    $allTotalReturn = 0;
@endphp

<div class="row">
    <div class="col-md-12 text-center">
        <div class="heading_area" style="border-bottom: 1px solid black;">
            <img style="height: auto; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
            <p style="text-transform: uppercase;"><strong>{{ json_decode($generalSettings->business, true)['shop_name'] }}</strong></p>
            <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
            <p style="width: 60%; margin:0 auto;"><strong>@lang('menu.phone') : </strong> {{ json_decode($generalSettings->business, true)['phone'] }}</p>
        </div>

        <h6 style="margin-top: 10px;"><strong>@lang('menu.supplier_report') </strong></h6>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <table class="table report-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <th class="text-start">@lang('menu.supplier')</th>
                    <th class="text-end">@lang('menu.opening_balance')</th>
                    <th class="text-end">@lang('menu.total_purchase')</th>
                    <th class="text-end">@lang('menu.total_paid')</th>
                    <th class="text-end">@lang('menu.total_return')</th>
                    <th class="text-end">Current Balance</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @foreach ($supplierReports as $report)
                    @php
                        $amounts = $supplierUtil->supplierAmountSummery($report->id, $report);
                        $openingBalance = $amounts['opening_balance_type'] == 'credit'
                            ? $amounts['opening_balance']
                            : ($amounts['opening_balance'] > 0 ? -$amounts['opening_balance'] : $amounts['opening_balance']);

                        $allTotalPurchase += $amounts['total_purchase'];
                        $allTotalPaid += $amounts['total_paid'];
                        $allTotalOpDue += $openingBalance;
                        $allTotalDue += $amounts['total_due'];
                        $allTotalReturn += $amounts['total_return'];
                    @endphp
                    <tr>
                        <td class="text-start">{{ $report->name.'(ID: '.$report->contact_id.')' }}</td>
                        <td class="text-end">
                            @php
                                $formattedOpeningBalance = App\Utils\Converter::format_in_bdt($openingBalance);
                                $showOpeningBalance = $formattedOpeningBalance < 0 ? Str::of($formattedOpeningBalance)->replace('-', '')->wrap('(', ')') : $formattedOpeningBalance;
                            @endphp
                            {{ $showOpeningBalance }}
                        </td>

                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($amounts['total_purchase']) }}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($amounts['total_paid']) }}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($amounts['total_return']) }}</td>
                        <td class="text-end">
                            @php
                                $total_due = $amounts['total_due'];
                                $formattedTotalDue = App\Utils\Converter::format_in_bdt($total_due);
                                $showTotalDue = $formattedTotalDue < 0 ? Str::of($formattedTotalDue)->replace('-', '')->wrap('(', ')') : $formattedTotalDue;
                            @endphp
                            {{ $showTotalDue }}
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
            <tbody>
                <tr>
                    <th class="text-end">@lang('menu.opening_balance') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($allTotalOpDue) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_purchase') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($allTotalPurchase) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_paid') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($allTotalPaid) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_return') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($allTotalReturn) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total') @lang('menu.closing_balance') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($allTotalDue) }}</td>
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
                <small>@lang('menu.powered_by') <b>@lang('menu.speedDigit_software_solution').</b></small>
            @endif
        </div>

        <div class="col-4 text-end">
            <small>@lang('menu.print_time') :{{ date($timeFormat) }}</small>
        </div>
    </div>
</div>
