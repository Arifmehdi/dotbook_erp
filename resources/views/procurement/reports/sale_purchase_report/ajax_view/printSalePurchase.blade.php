<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 33px; margin-left: 15px;margin-right: 15px;}
    .header, .header-space,
    .footer, .footer-space {height: 20px;}
    .header {position: fixed; top: 0;}
    .footer {position: fixed;bottom: 0;}
    .noBorder {border: 0px !important;}
    tr.noBorder td {border: 0px !important;}
    tr.noBorder {border: 0px !important;border-left: 1px solid transparent;border-bottom: 1px solid transparent;}

    h6 { font-size: 16px; }
    p {  font-size: 14px; }
    td {  color: black; }
</style>
@php
    $total_purchase = 0;
    $total_purchase_inc_tax = 0;
    $total_purchase_due = 0;
    $total_purchase_return = 0;

    $total_sale = 0;
    $total_sale_inc_tax = 0;
    $total_sale_due = 0;
    $total_sale_return = 0;

    foreach ($purchases as $purchase) {
        $total_purchase += $purchase->total_purchase_amount - $purchase->purchase_tax_amount;
        $total_purchase_inc_tax += $purchase->total_purchase_amount;
        $total_purchase_due += $purchase->due;
        $total_purchase_return += $purchase->purchase_return_amount;
    }

    foreach ($sales as $sale) {
        $total_sale += $sale->total_payable_amount - $sale->order_tax_amount;
        $total_sale_inc_tax += $sale->total_payable_amount;
        $total_sale_due += $sale->due > 0 ? $sale->due : 0;
        $total_sale_return += $sale->sale_return_amount > 0 ? $sale->sale_return_amount : 0;
    }

    $saleMinusPurchase = $total_sale_inc_tax - $total_sale_return - $total_purchase_inc_tax - $total_purchase_return;
@endphp
<div class="row">
    <div class="col-md-12 text-center">
        <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }} </h5>
        <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>

        @if ($fromDate && $toDate)
            <p><strong>@lang('menu.date') :</strong> {{date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($fromDate)) }} <strong>To</strong> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($toDate)) }} </p>
        @endif
        <h6 style="margin-top: 10px;"><strong>Sale / Purcahse Compare Report </strong></h6>
    </div>
</div>
<br>
<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <div class="heading">
                    <h6 class="text-primary"><b>@lang('menu.purchases')</b></h6>
                </div>

                <table class="table modal-table table-sm">
                    <tbody>
                        <tr>
                            <th class="text-startx">@lang('menu.total_purchase') :</th>
                            <td class="text-endx">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ App\Utils\Converter::format_in_bdt($total_purchase) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-startx">@lang('menu.purchase_including_tax') :</th>
                            <td class="text-endx">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ App\Utils\Converter::format_in_bdt($total_purchase_inc_tax) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-startx">@lang('menu.purchase_return_including_tax') :</th>
                            <td class="text-endx">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ App\Utils\Converter::format_in_bdt($total_purchase_return) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <div class="heading">
                    <h6 class="text-primary"><b>@lang('menu.sale')</b></h6>
                </div>

                <table class="table modal-table table-sm">
                    <tbody>
                        <tr>
                            <th class="text-startx">@lang('menu.total_sale') :</th>
                            <td class="text-endx">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ App\Utils\Converter::format_in_bdt($total_sale) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-startx">@lang('menu.sale_including_tax') :</th>
                            <td class="text-endx">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ App\Utils\Converter::format_in_bdt($total_sale_inc_tax) }}
                            </td>
                        </tr>

                        <tr>
                            <th class="text-startx">@lang('menu.sale_return_including_tax') :</th>
                            <td class="text-endx">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ App\Utils\Converter::format_in_bdt($total_sale_return) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-1">
    <div class="sale_purchase_due_compare_area">
        <div class="col-md-12">
            <div class="card-body card-custom">
                <div class="heading">
                    <h6 class="text-navy-blue">@lang('menu.overall_purchase_return')</h6>
                </div>

                <div class="compare_area mt-3">
                    <h5 class="text-muted">Sale - Purchase :
                        <span class="{{ $saleMinusPurchase < 0 ? 'text-danger' : '' }}">
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ App\Utils\Converter::format_in_bdt($saleMinusPurchase) }}
                        </span>
                    </h5>
                </div>
            </div>
        </div>
    </div>
</div>
