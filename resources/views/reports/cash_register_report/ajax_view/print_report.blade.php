@php
   use Carbon\Carbon;
@endphp
<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto, }
        td    { font-size: 10px!important; }
        th    { font-size: 10px!important; }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 4%;margin-right: 4%;}
</style>
@php
    $totalExpense = 0;
    $totalPaid = 0;
    $totalDue = 0;
@endphp

<div class="row">
    <div class="col-md-12 text-center">
        <div class="heading_area" style="border-bottom: 1px solid black;">
            <img style="height: auto; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
            <p style="text-transform: uppercase;"><strong>{{ json_decode($generalSettings->business, true)['shop_name'] }}</strong></p>
            <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
            <p style="width: 60%; margin:0 auto;"><strong>@lang('menu.phone') : </strong> {{ json_decode($generalSettings->business, true)['phone'] }}</p>
        </div>

        <h6 style="margin-top: 10px;"><strong>@lang('menu.cash_register_reports') </strong></h6>

        @if ($fromDate && $toDate)
            <p style="margin-top: 10px;"><strong>@lang('menu.from') </strong>
                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($fromDate)) }}
                <strong>To</strong> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($toDate)) }}
            </p>
        @endif
    </div>
</div>

@php
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
    $totalSaleAmount = 0;
    $totalReceivedAmount = 0;
    $totalDueAmount = 0;
    $totalClosedAmount = 0;
@endphp
<div class="row" style="margin-top: 15px;">
    <div class="col-12">
        <table class="table report-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <th class="text-startx">@lang('menu.open_time')</th>
                    <th class="text-startx">@lang('menu.closed_time')</th>
                    <th class="text-startx">@lang('menu.user')</th>
                    <th class="text-startx">@lang('menu.status')</th>
                    <th class="text-endx">@lang('menu.total_sale')</th>
                    <th class="text-endx">@lang('menu.total_paid')</th>
                    <th class="text-endx">@lang('menu.total_due')</th>
                    <th class="text-endx">@lang('menu.closed_time')</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @foreach ($cashRegisters as $row)
                    <tr>
                        <td class="text-start">
                            {{ Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->format('jS M, Y h:i A') }}
                        </td>

                        <td class="text-start">
                            @if ($row->closed_at)
                                 {{ Carbon::createFromFormat('Y-m-d H:i:s', $row->closed_at)->format('jS M, Y h:i A') }}
                            @endif
                        </td>

                        <td class="text-start">
                            {{ $row->u_prefix . ' ' . $row->u_first_name . ' ' . $row->u_last_name }}
                        </td>

                        <td class="text-start">
                            @if ($row->status == 1 )
                                Open
                            @else
                                @lang('menu.closed')
                            @endif
                        </td>

                        <td class="text-endx">
                            @php
                                $__totalSale = $row->total_sale ? $row->total_sale : 0;
                                $totalSaleAmount += $__totalSale;
                            @endphp
                            {{ App\Utils\Converter::format_in_bdt($__totalSale) }}
                        </td>

                        <td class="text-endx">
                            @php
                                $__totalPaid = $row->total_paid ? $row->total_paid : 0;
                                $totalReceivedAmount += $__totalPaid;
                            @endphp
                            {{ App\Utils\Converter::format_in_bdt($__totalPaid) }}
                        </td>

                        <td class="text-endx">
                            @php
                                $__totalDue= $row->total_due ? $row->total_due : 0;
                                $totalDueAmount += $__totalDue;
                            @endphp
                            {{ App\Utils\Converter::format_in_bdt($__totalDue) }}
                        </td>

                        <td class="text-endx">
                            {{ App\Utils\Converter::format_in_bdt($row->closed_amount) }}
                            @php
                                $totalClosedAmount += $row->closed_amount;
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
                    <th class="text-endx">@lang('menu.all_total_sale') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-endx">
                        {{ App\Utils\Converter::format_in_bdt($totalSaleAmount) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-endx">@lang('menu.all_total_paid') : {{json_decode($generalSettings->business, true)['currency']}}</th>
                    <td class="text-endx">
                        {{ App\Utils\Converter::format_in_bdt($totalReceivedAmount) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-endx">@lang('menu.all_total_due') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-endx">
                        {{ App\Utils\Converter::format_in_bdt($totalDueAmount) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-endx">@lang('menu.all_total_closing_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-endx">
                        {{ App\Utils\Converter::format_in_bdt($totalClosedAmount) }}
                    </td>
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
