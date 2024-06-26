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
</style>
<table class="table report-table table-sm table-bordered print_table">
    <tbody>
        <tr>
            <td class="aiability_area">
                <table class="table table-sm">
                    <tbody>
                        {{-- Cash Flow from operations --}}
                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.total_sale') :</em>
                            </td>

                            <td class="text-start">
                                <em>{{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_sale']) }}</em>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.purchase_return') :</em>
                            </td>

                            <td class="text-start">
                                <em>{{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_purchase_return']) }}</em>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.total_purchase') : </em>
                            </td>

                            <td class="text-start">
                                <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_purchase']) }})</em>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                            <em>@lang('menu.sale_return') : </em>
                            </td>

                            <td class="text-start">
                                <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_sale_return']) }})</em>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.direct_expense') :</em>
                            </td>

                            <td class="text-start">
                                <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_direct_expense']) }})</em>
                            </td>
                        </tr>

                        @if ($addons->manufacturing == 1)
                            <tr>
                                <td class="text-start">
                                    <em>@lang('menu.total_production_cost') :</em>
                                </td>

                                <td class="text-start">
                                    <em>(0.00)</em>
                                </td>
                            </tr>
                        @endif

                        {{-- <tr>
                            <td class="text-start">
                            <em> @lang('menu.opening_stock') :</em>
                            </td>

                            <td class="text-start">
                                <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['opening_stock']) }})</em>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                            <em>Closing Stock :</em>
                            </td>

                            <td class="text-start">
                                <em>{{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['closing_stock']) }}</em>
                            </td>
                        </tr> --}}

                        <tr>
                            <th class="text-endx">
                                <em>@lang('menu.gross_profit') :</em>
                            </th>

                            <td class="text-start">
                                <b><em>{{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['gross_profit']) }}</em></b>
                            </td>
                        </tr>

                        {{-- Cash Flow from investing --}}
                        <tr>
                            <th class="text-startx" colspan="2">
                                <strong>@lang('menu.net_profit_loss_information') :</strong>
                            </th>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.gross_profit') :</em>
                            </td>
                            <td class="text-start"><em>{{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['gross_profit']) }}</em> </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.total_stock_adjustment') :</em>
                            </td>

                            <td class="text-start">
                                <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_adjusted']) }})</em>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.total_adjustment_recovered') :</em>
                            </td>

                            <td class="text-start">
                                <em>{{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_adjusted_recovered']) }}</em>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.total_sale_order_tax') :</em>
                            </td>

                            <td class="text-start">
                                <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_sale_order_tax']) }})</em>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                                <em>@lang('menu.item_sold_individual_tax') :</em>
                            </td>

                            <td class="text-start">
                                <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['individual_product_sale_tax']) }})</em>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start">
                               <em>@lang('menu.indirect_expense') :</em>
                            </td>

                            <td class="text-start">
                                <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_indirect_expense']) }})</em>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-endx">
                                <em>@lang('menu.net_profit') :</em>
                            </th>

                            <td class="text-start">
                                <b><em>{{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['net_profit']) }}</em> </b>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
