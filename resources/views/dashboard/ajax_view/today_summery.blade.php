<!--begin::Form-->
@php
    $currency = json_decode($generalSettings->business, true)['currency'];
@endphp
<div class="form-group row">
    <div class="col-md-6">
        <div class="loader d-none">
            <i class="fas fa-sync fa-spin ts_preloader text-primary"></i> <b>@lang('menu.processing')</b>
        </div>
    </div>
</div>

<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 4%;margin-right: 4%;}
    .header, .header-space,
    .footer, .footer-space {height: 20px;}
    .header {position: fixed;top: 0;}
    .footer {position: fixed;bottom: 0;}
</style>

<div class="print_body">
    <div class="today_summery_area mt-2">
        <div class="print_today_summery_header d-none">
            <div class="row text-center">
                <p><strong>Today Summary</strong></p>
                <p><strong>@lang('menu.date') : </strong> {{ date(date(json_decode($generalSettings->business, true)['date_format'])) }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <table class="table modal-table table-sm">
                    <tbody>
                        <tr>
                            <th class="text-startx">@lang('menu.total_purchase') :</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalPurchase) }}</td>
                        </tr>

                        <tr>
                            <th class="text-startx">Total Payment :</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalPayment) }}</td>
                        </tr>

                        <tr>
                            <th class="text-startx">@lang('menu.total_purchase_due') :</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalPurchaseDue) }}</td>
                        </tr>

                        <tr>
                            <th class="text-startx">@lang('menu.total_adjustment') :</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($total_adjustment) }}</td>
                        </tr>

                        <tr>
                            <th class="text-startx">@lang('menu.total_expense') :</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalExpense) }}</td>
                        </tr>

                        <tr>
                            <th class="text-startx">Total Sale Discount :</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalSaleDiscount) }}</td>
                        </tr>

                        <tr>
                            <th class="text-startx">Transfer Shiping Charge :</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalTransferShippingCost) }}</td>
                        </tr>

                        <tr>
                            <th class="text-startx">Purchanse Shiping Charge :</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($purchaseTotalShipmentCost) }}</td>
                        </tr>

                        <tr>
                            <th class="text-startx">@lang('menu.total_customer_reward') :</th>
                            <td class="text-start">{{ $currency }} 0.00 (P)</td>
                        </tr>

                        <tr>
                            <th class="text-startx">@lang('menu.total_sale_return') :</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalSalesReturn) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <table class="table modal-table table-sm">
                    <tbody>
                        <tr>
                            <th class="text-startx">@lang('menu.current_stock') :</th>
                            <td class="text-start">{{ $currency }} 0.00</td>
                        </tr>

                        <tr>
                            <th class="text-startx">Total sale :</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalSales) }}</td>
                        </tr>

                        <tr>
                            <th class="text-startx">@lang('menu.total_received') :</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalReceive) }}</td>
                        </tr>

                        <tr>
                            <th class="text-startx">@lang('menu.total_sale_due') :</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalSaleDue) }}</td>
                        </tr>

                        <tr>
                            <th class="text-startx">@lang('menu.total_stock_recovered') :</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($total_recovered) }}</td>
                        </tr>

                        <tr>
                            <th class="text-startx">@lang('menu.total_purchase_return') :</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalPurchaseReturn) }}</td>
                        </tr>

                        <tr>
                            <th class="text-startx">Total Sale Shipping Charge :</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($totalSalesShipmentCost) }}</td>
                        </tr>

                        <tr>
                            <th class="text-startx">Total Round Off :</th>
                            <td class="text-start">{{ $currency }} 0.00 (P)</td>
                        </tr>
                    </tbody>
                </table>

                <table class="table modal-table table-sm">
                    <tbody>
                        <tr>
                            <th class="text-startx">Today Daily Profit :</th>
                            <td class="text-start">{{ $currency }} {{ App\Utils\Converter::format_in_bdt($todayProfit) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="print_today_summery_footer d-none">
            <br><br>
            <div class="row">
                <div class="col-6">
                    <p><strong>@lang('menu.checked_by') :</strong></p>
                </div>
                <div class="col-6 text-end">
                    <p><strong>@lang('menu.approved_by') :</strong></p>
                </div>
            </div>
        </div>
    </div>
</div>
