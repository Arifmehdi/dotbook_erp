<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto, font-size:9px!important; }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 5px;margin-right: 5px;}
    div#footer {position:fixed;bottom:24px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}

    .print_table th { font-size:15px!important; font-weight: 550!important;}
    .print_table tr td{font-size:15px!important;}

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

<div class="row mt-1">
    <div class="col-12 text-center">
        <h6 style="text-transform:uppercase;"><strong>@lang('menu.purchase_summary') </strong></h6>
    </div>
</div>

<div class="row mt-1">
    <div class="col-12 text-center">
        @if ($fromDate && $toDate)
            <p>
                <strong>@lang('menu.from') :</strong>
                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($fromDate)) }}
                <strong>@lang('menu.to') : </strong> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($toDate)) }}
            </p>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-4">
        <small><strong>@lang('menu.supplier') : </strong> {{ $supplierName }} </small>
    </div>

    <div class="col-4">
        <small><strong>@lang('menu.purchase_ledger_ac') :</strong>  {{ $purchaseAccountName }}</small>
    </div>

    <div class="col-4">
        <small><strong>@lang('menu.created_by') :</strong>  {{ $userName ? $userName : auth()->user()->prefix.' '.auth()->user()->name.' '.auth()->user()->last_name }}</small>
    </div>
</div>

@php
    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp

<div class="row mt-1">
    <div class="col-8 offset-2">
        <table class="display table table-sm print_table">
            <thead>

                <tr>
                    <th class="text-end"> @lang('menu.total_qty') : </th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($purchases->sum('total_qty')) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_net_amount') : {{json_decode($generalSettings->business, true)['currency']}}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($purchases->sum('total_net_amount')) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_purchase_discount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($purchases->sum('total_purchase_discount')) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_purchase_tax') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($purchases->sum('total_tax_amount')) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_additional_expense') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($purchases->sum('total_additional_expense')) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_purchased_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($purchases->sum('total_purchased_amount')) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.average_unit_cost') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        @php
                            $totalAverageUnitCost = $purchases->sum('total_purchased_amount') / $purchases->sum('total_qty');
                        @endphp
                        {{ App\Utils\Converter::format_in_bdt($totalAverageUnitCost) }}
                    </td>
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
