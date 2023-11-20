<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    div#footer {position:fixed;bottom:24px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}

    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 5px;margin-right: 5px;}
</style>
@php
    $totalIncome = 0;
    $totalReceived = 0;
    $totalDue = 0;
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp

<div class="row">
    <div class="col-md-12 text-center">
        <div class="heading_area" style="border-bottom: 1px solid black;">
            <img style="height: auto; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
            <p style="text-transform: uppercase;"><strong>{{ json_decode($generalSettings->business, true)['shop_name'] }}</strong></p>
            <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
            <p style="width: 60%; margin:0 auto;"><strong>@lang('menu.phone') :</strong> {{ json_decode($generalSettings->business, true)['phone'] }}</p>
        </div>

        <h6 style="margin-top: 10px;"><strong>@lang('menu.income_report')</strong></h6>

        @if ($fromDate && $toDate)
            <p style="margin-top: 10px;"><strong>From :</strong>
                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($fromDate)) }}
                <strong>To</strong> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($toDate)) }}
            </p>
        @endif
    </div>
</div>

<div class="row" style="margin-top: 15px;">
    <div class="col-12">
        <table class="table report-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <th class="text-start">@lang('menu.voucher_no')</th>
                    <th class="text-start">@lang('menu.description')</th>
                    <th class="text-start">@lang('menu.created_by')</th>
                    <th class="text-end">@lang('menu.total_amount')</th>
                    <th class="text-end">@lang('menu.received')</th>
                    <th class="text-end">@lang('menu.due')</th>
                </tr>
            </thead>
            @php
                $previousDate = '';
            @endphp
            <tbody class="sale_print_product_list">
                @foreach ($incomes as $income)
                    @php
                        $totalIncome += $income->total_amount;
                        $totalReceived += $income->received;
                        $totalDue += $income->due;

                        $incomeDate = date($__date_format, strtotime($income->report_date))
                    @endphp

                    @if ($previousDate != $incomeDate)

                        @php $previousDate = $incomeDate; @endphp

                        <tr>
                            <th colspan="6">{{ $incomeDate }}</th>
                        </tr>
                    @endif

                    <tr>
                        <td class="text-start">{{ $income->voucher_no }}</td>
                        <td class="text-start">
                            @foreach ($income->incomeDescriptions as $description)

                                @php
                                    $accountType = '';
                                @endphp

                                @if ($description->account->account_type == 24)

                                    @php $accountType = 'Direct Income : '; @endphp
                                @elseif ($description->account->account_type == 25)

                                    @php $accountType = 'Indirect Income : '; @endphp
                                @else

                                    @php $accountType = 'Misc. Income A/c : '; @endphp
                                @endif

                                <p class="m-0 p-0">- {{ $accountType . $description->account->name }} : <strong> {{ App\Utils\Converter::format_in_bdt($description->amount) }} </strong></p>
                            @endforeach
                        </td>

                        <td>{{ $income->cr_prefix . ' ' . $income->cr_name . ' ' . $income->cr_last_name }}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($income->total_amount) }}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($income->received) }}</td>
                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($income->due) }}</td>
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
                    <th class="text-end">Total Income : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalIncome) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_paid') : {{json_decode($generalSettings->business, true)['currency']}}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalReceived) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_due') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalDue) }}
                    </td>
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
                <small>Powered By <b>SpeedDigit Software Solution.</b></small>
            @endif
        </div>

        <div class="col-4 text-center">
            <small>@lang('menu.print_time') : {{ date($timeFormat) }}</small>
        </div>
    </div>
</div>
