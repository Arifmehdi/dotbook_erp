<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto, font-size:10px; }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }
    div#footer {position:fixed;bottom:20px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 10px;margin-right: 10px;}
    .print_table th { font-size:10px!important; font-weight: 550!important;}
    .print_table td { font-size:10px!important;}

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
        <p><strong>@lang('menu.email') : </strong> <b>{{ json_decode($generalSettings->business, true)['email'] }}</b>,
            <strong>@lang('menu.phone') : </strong> <b>{{ json_decode($generalSettings->business, true)['phone'] }}</b>
        </p>
    </div>
</div>

<div class="row mt-1">
    <div class="col-12 text-center">
        <h6 style="text-transform:uppercase;"><strong>@lang('menu.purchase_return_report') </strong></h6>
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
    <div class="col-6">
        <small><strong>@lang('menu.supplier') : </strong> {{ $supplierName }} </small>
    </div>

    <div class="col-6">
        <small><strong>@lang('menu.created_by') :</strong>  {{ $userName ? $userName : auth()->user()->prefix.' '.auth()->user()->name.' '.auth()->user()->last_name }}</small>
    </div>
</div>

@php
    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
    $totalItems = 0;
    $totalIQty = 0;
    $TotalNetTotal = 0;
    $TotalReturnDiscount = 0;
    $TotalReturnTax = 0;
    $TotalReturnAmount = 0;
    $TotalRefundedAmount = 0;
@endphp
<div class="row mt-1">
    <div class="col-12">
        <table class="table report-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <th class="text-start">@lang('menu.voucher_no')</th>
                    <th class="text-start">@lang('menu.parent_purchase')</th>
                    <th class="text-start">@lang('menu.supplier')</th>
                    <th class="text-end">@lang('menu.total_item')</th>
                    <th class="text-end">@lang('menu.total_qty')</th>
                    <th class="text-end">@lang('menu.net_total_amt')</th>
                    <th class="text-end">@lang('menu.return_discount')</th>
                    <th class="text-end">@lang('menu.return_tax')</th>
                    <th class="text-end">@lang('short.total_return_amt')</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">

                @php $previousDate = '';@endphp

                @foreach ($returns as $return)

                    @if ($previousDate != $return->date)

                        @php
                            $previousDate = $return->date;
                        @endphp

                        <tr>
                            <th colspan="9" style="font-size: 11px!important;; font-weight:600;">{{ date($__date_format, strtotime($return->date)) }}</th>
                        </tr>
                    @endif

                    <tr>
                        <td class="text-start">{{ $return->voucher_no }}</td>
                        <td class="text-start">{{ $return->purchase_invoice_id }}</td>
                        <td class="text-start">
                            {{ $return->supplier_name }}
                        </td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($return->total_item) }}
                            @php
                                $totalItems += $return->total_item;
                            @endphp
                        </td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($return->total_qty) }}
                            @php
                                $totalIQty += $return->total_qty;
                            @endphp
                        </td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($return->net_total_amount) }}
                            @php
                                $TotalNetTotal += $return->net_total_amount;
                            @endphp
                        </td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($return->return_discount_amount) }}
                            @php
                                $TotalReturnDiscount += $return->return_discount_amount;
                            @endphp
                        </td>

                        <td class="text-end fw-bold">
                            {{ '(' . $return->return_tax_percent . '%)=' . App\Utils\Converter::format_in_bdt($return->return_tax_amount) }}
                            @php
                                $TotalReturnTax += $return->return_tax_amount;
                            @endphp
                        </td>

                        <td class="text-end fw-bold">
                            {{ App\Utils\Converter::format_in_bdt($return->total_return_amount) }}
                            @php
                                $TotalReturnAmount += $return->total_return_amount;
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
                    <th class="text-end">@lang('menu.total_returned_item') : </th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalItems) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_return_qty') :</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($totalIQty) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_net_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalNetTotal) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_return_discount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalReturnDiscount) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_return_tax') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalReturnTax) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_return_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        {{ App\Utils\Converter::format_in_bdt($TotalReturnAmount) }}
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

