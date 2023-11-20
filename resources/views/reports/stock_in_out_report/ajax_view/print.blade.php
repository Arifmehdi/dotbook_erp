<style>
    @media print {
        table {
            page-break-after: auto
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto
        }

        td {
            page-break-inside: avoid;
            page-break-after: auto
        }

        thead {
            display: table-header-group
        }

        tfoot {
            display: table-footer-group
        }
    }

    @page {
        size: a4;
        margin-top: 0.8cm;
        margin-bottom: 35px;
        margin-left: 10px;
        margin-right: 10px;
    }

    h6 { font-size: 16px; }
    p {  font-size: 14px; }
    td {  color: black; }
</style>
@php
    $totalStockInQty = 0;
    $totalStockOutQty = 0;
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp

<div class="row" style="border-bottom: 1px solid black;">
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


<div class="row">
    <div class="col-md-12 text-center">
        <h6 style="margin-top: 10px; text-transform:uppercase;"><strong>{{ __("Stock In-Out Report") }}</strong></h6>
    </div>

    @if ($fromDate && $toDate)
        <div class="col-md-12 text-center">
            <p style="margin-top: 5px;"><strong>@lang('menu.from') :</strong>
                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($fromDate)) }}
                <strong>{{ __("To") }} : </strong> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($toDate)) }}
            </p>
        </div>
    @endif
</div>

<div class="row mt-2">
    <div class="col-12">
        <table class="table report-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <th class="text-startx">@lang('menu.item')</th>
                    <th class="text-startx">Sale</th>
                    <th class="text-startx">Sale Date</th>
                    <th class="text-endx">Sold/Out Qty</th>
                    <th class="text-endx">Sold Price({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                    <th class="text-startx">@lang('menu.customer')</th>
                    <th class="text-startx">Stock In By</th>
                    <th class="text-startx">Stock In Date</th>
                    <th class="text-endx">@lang('menu.unit_cost')({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @foreach ($stockInOuts as $row)
                    @php
                        $totalStockInQty += $row->stock_in_qty;
                        $totalStockOutQty += $row->sold_qty;
                    @endphp
                    <tr>
                        <td class="text-start">
                            @php
                                $variant = $row->variant_name ? '/' . $row->variant_name : '';
                            @endphp
                            {{ $row->name . $variant }}
                        </td>
                        <td class="text-start">{{ $row->invoice_id }}</td>
                        <td class="text-start">
                            {{ date($__date_format, strtotime($row->date)) }}
                        </td>

                        <td class="text-endx">{{ $row->sold_qty }}</td>

                        <td class="text-endx">
                            {{ \App\Utils\Converter::format_in_bdt($row->unit_price_inc_tax) }}
                        </td>

                        <td class="text-start">{{ $row->customer_name ? $row->customer_name : 'Walk-In-Customer' }}</td>

                        <td class="text-start">
                            @if ($row->purchase_inv)
                                {{ 'Purchase:' . $row->purchase_inv }}
                            @elseif ($row->production_voucher_no)
                                {{ 'Production:' . $row->production_voucher_no }}
                            @elseif ($row->pos_id)
                                {{ 'Opening Stock' }}
                            @endif
                        </td>

                        <td class="text-start">
                            {{ date($__date_format, strtotime($row->stock_in_date)) }}
                        </td>

                        <td class="text-endx">
                            {{ \App\Utils\Converter::format_in_bdt($row->net_unit_cost) }}
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
                    <th class="text-endx">Total Stock In Qty : </th>
                    <td class="text-endx">
                        {{ \App\Utils\Converter::format_in_bdt($totalStockInQty) }}
                    </td>
                </tr>

                <tr>
                    <th class="text-endx">Total Stock Out Qty : </th>
                    <td class="text-endx">
                        {{ \App\Utils\Converter::format_in_bdt($totalStockOutQty) }}
                    </td>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div style="position:fixed;bottom:0px;left:0px;width:100%;color: #000;" class="footer text-end">

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
