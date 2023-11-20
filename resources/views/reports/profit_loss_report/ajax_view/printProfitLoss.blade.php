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
</style>
<div class="sale_and_purchase_amount_area">
    <div class="row">
        <div class="col-md-12 text-center">
            <h6>{{ json_decode($generalSettings->business, true)['shop_name'] }}</h6>
            <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
            
            @if ($fromDate && $toDate)
                <p><strong>@lang('menu.date') :</strong> {{date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($fromDate)) }} <strong>To</strong> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($toDate)) }} </p>
            @endif
            <h6 style="margin-top: 10px;"><strong>@lang('menu.profit') / @lang('menu.loss_report') </strong></h6>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <table class="table modal-table table-sm">
                        <tbody>
                            <tr>
                                <th class="text-startx">
                                    @lang('menu.sold_item_total_unit_cost') :
                                    <br>
                                    <small>(Inc.Tax)</small>
                                </th>

                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ App\Utils\Converter::format_in_bdt($totalTotalUnitCost) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-startx">Total Order Tax : </th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ App\Utils\Converter::format_in_bdt($totalOrderTax) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-startx">@lang('menu.total_stock_adjustment') : </th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ App\Utils\Converter::format_in_bdt($totalStockAdjustmentAmount) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-startx">@lang('menu.total_expense') : </th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ App\Utils\Converter::format_in_bdt($totalExpense) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-startx">@lang('menu.total_transfer_shipping_charge') : </th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ App\Utils\Converter::format_in_bdt($totalTransferShipmentCost) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-startx">@lang('menu.total_sell_return') : </th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ App\Utils\Converter::format_in_bdt($totalReturn) }}
                                </td>
                            </tr>

                            {{-- <tr>
                                <th class="text-startx">Total Payroll :</th>
                                <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} {{ App\Utils\Converter::format_in_bdt($totalPayroll) }}</td>
                            </tr> --}}

                            <tr>
                                <th class="text-startx">@lang('menu.total_production_cost') :</th>
                                <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} 0.00 (P)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <table class="table modal-table table-sm">
                        <tbody>
                            <tr>
                                <th class="text-startx">
                                    @lang('menu.total_sale') : <br>
                                    <small>(Inc.Tax)</small>
                                </th>

                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ App\Utils\Converter::format_in_bdt($totalSale) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-startx">@lang('menu.total_stock_adjustment') @lang('menu.recovered') : </th>
                                <td class="text-start">
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    {{ App\Utils\Converter::format_in_bdt($totalStockAdjustmentRecovered) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    @php
                        $grossProfit = ($totalSale + $totalStockAdjustmentRecovered)
                                    - $totalStockAdjustmentAmount
                                    - $totalExpense
                                    - $totalReturn
                                    - $totalOrderTax
                                    // - $totalPayroll
                                    - $totalTotalUnitCost
                                    - $totalTransferShipmentCost;
                    @endphp

                    <div class="gross_profit_area">
                        <h6 class="text-muted m-0">@lang('menu.total') @lang('menu.daily_profit') :
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            <span class="{{ $grossProfit < 0 ? 'text-danger' : '' }}">{{ App\Utils\Converter::format_in_bdt($grossProfit) }}</span></h6>
                        <p class="text-muted m-0"><b>Calculate Gross Profit :</b> (Total Sale + Total Stock Adjustment Recovered)
                            <b>-</b> ( Sold Item Total Unit Cost + Total Sale Return + Total Sale Order Tax + Total Stock Adjustment + Total Expense + Total transfer shipping charge + Total Production Cost )</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@if (config('company.print_on_others'))
    <div class="row">
        <div class="col-md-12 text-center">
            <small>@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd') .</b></small>
        </div>
    </div>
@endif
