@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
@endphp
<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 33px; margin-left: 20px;margin-right: 20px;}
    .header, .header-space,
    .footer, .footer-space {height: 20px;}
    .header {position: fixed;top: 0;}
    .footer {position: fixed;bottom: 0;}
    .noBorder {border: 0px !important;}
    tr.noBorder td {border: 0px !important;}
    tr.noBorder {border: 0px !important;border-left: 1px solid transparent;border-bottom: 1px solid transparent;}
</style>
 <!-- Purchase print templete-->
    <div class="production_print_template">
        <div class="details_area">
            <div class="heading_area">
                <div class="row">
                    <div class="col-12 text-center">
                        <h6>
                            {{ json_decode($generalSettings->business, true)['shop_name'] }}
                        </h6>
                        <p style="width: 60%; margin:0 auto;">
                            {{ json_decode($generalSettings->business, true)['address'] }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="heading_area">
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-lg-4">
                        @if (json_decode($generalSettings->business, true)['business_logo'] != null)
                            <img src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
                        @endif
                    </div>
                    <div class="col-md-4 col-sm-4 col-lg-4">
                        <div class="heading text-center">
                            <p style="margin-top: 10px;" class="bill_name"><strong>@lang('menu.manufacturing_bill')</strong></p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-lg-4">

                    </div>
                </div>
            </div>

            <div class="purchase_and_deal_info pt-3">
                <div class="row">
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.stored_location') : </strong>
                                @if ($production->warehouse_id)
                                    {{ $production->warehouse->warehouse_name.'/'.$production->warehouse->warehouse_code }}<b>(WH)</b>
                                @else
                                    {{ json_decode($generalSettings->business, true)['shop_name'] }}
                                @endif
                            </li>
                            <li><strong>@lang('menu.ingredients_stock_location') : </strong>
                                @if ($production->stock_warehouse_id)
                                    {{ $production->stock_warehouse->warehouse_name.'/'.$production->stock_warehouse->warehouse_code }}<b>(WH)</b>
                                @else
                                    {{ json_decode($generalSettings->business, true)['shop_name'] }}
                                @endif
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li>
                                <strong>@lang('menu.production_item') : </strong>
                                {{ $production->product->name }} {{ $production->variant_id ? $production->variant->variant_name : '' }} {{ $production->variant_id ? $production->variant->variant_code : $production->product->product_code }}
                            </li>
                            <li>
                                <strong>@lang('menu.production_status'): </strong>
                                @if ($production->is_final == 1)
                                    <span class="text-success">@lang('menu.final')</span>
                                @else
                                    <span class="text-hold">@lang('menu.hold')</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <ul class="list-unstyled">
                            <li><strong> @lang('menu.voucher_no') : </strong> {{ $production->reference_no }}</li>
                            <li><strong>@lang('menu.date') : </strong>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($production->date)) . ' ' . date($timeFormat, strtotime($production->time)) }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="purchase_product_table pt-3 pb-3">
                <p><strong>@lang('menu.ingredients') @lang('menu.list')</strong></p>
                <table class="table report-table table-sm table-bordered print_table">
                    <thead>
                        <tr>
                            <th scope="col">@lang('menu.ingredient_name')</th>
                            <th scope="col">@lang('menu.input_qty')</th>
                            <th scope="col">@lang('menu.unit_cost_inc_tax')</th>
                            <th scope="col">@lang('menu.sub_total')</th>
                        </tr>
                    </thead>
                    <tbody class="purchase_print_product_list">
                        @foreach ($production->ingredients as $ingredient)
                            <tr>
                                @php
                                    $variant = $ingredient->variant_id ? ' ('.$ingredient->variant->variant_name.')' : '';
                                @endphp

                                <td>{{ $ingredient->product->name.' '.$variant }}</td>
                                <td>{{ $ingredient->input_qty }}</td>
                                <td>
                                    {{ App\Utils\Converter::format_in_bdt($ingredient->unit_cost_inc_tax) }}
                                </td>
                                <td>{{ App\Utils\Converter::format_in_bdt($ingredient->subtotal) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <br>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>@lang('menu.production_quantity_and_total_cost')</strong></p>
                    <table class="table report-table table-sm table-bordered print_table">
                        <tbody>
                            <tr>
                                <th class="text-endx">@lang('menu.output_quantity') : </th>
                                <td class="text-endx">
                                    {{ $production->quantity.'/'.$production->unit->code_name }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-endx">@lang('menu.wasted_quantity') : </th>
                                <td class="text-endx">
                                    {{ $production->wasted_quantity.'/'.$production->unit->code_name }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-endx">@lang('menu.final_quantity') : </th>
                                <td class="text-endx">
                                    {{ $production->total_final_quantity.'/'.$production->unit->code_name }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-endx">@lang('menu.additional_cost') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <td class="text-endx">
                                    {{ App\Utils\Converter::format_in_bdt($production->production_cost) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-endx">@lang('menu.total_cost') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <td class="text-endx">
                                    {{ App\Utils\Converter::format_in_bdt($production->total_cost) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-md-6 text-end">
                    <p><strong>@lang('menu.production_items_costing_and_pricing')</strong></p>
                    <table class="table report-table table-sm table-bordered print_table">
                        <tbody>
                            <tr>
                                <th class="text-endx">@lang('menu.tax') : </th>
                                <td class="text-endx">
                                    {{ $production->tax ? $production->tax->tax_percent : 0 }}%
                                </td>
                            </tr>

                            <tr>
                                <th class="text-endx">@lang('menu.per_unit_cost_exc_tax') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <td class="text-endx">
                                    {{ App\Utils\Converter::format_in_bdt($production->unit_cost_exc_tax) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-endx">@lang('menu.per_unit_cost_inc_tax') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <td class="text-endx">
                                    {{ App\Utils\Converter::format_in_bdt($production->unit_cost_inc_tax) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="text-endx">@lang('short.x_margin')(%) : </th>
                                <td class="text-endx">
                                    {{ $production->x_margin }}%
                                </td>
                            </tr>

                            <tr>
                                <th class="text-endx">@lang('menu.selling_price_exc_tax') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                <td class="text-endx">
                                    {{ App\Utils\Converter::format_in_bdt($production->price_exc_tax) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <br>
            <div class="row">
                <div class="col-md-6">
                    <h6>@lang('menu.checked_by') : </h6>
                </div>

                <div class="col-md-6 text-end">
                    <h6>@lang('menu.approved_by') : </h6>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    <img style="width: 170px; height:25px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($production->reference_no, $generator::TYPE_CODE_128)) }}">
                    <p>{{$production->reference_no}}</p>
                </div>
            </div>

            @if (config('company.print_on_purchase'))
                <div class="row">
                    <div class="col-md-12 text-center">
                        <small>@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd') .</b></small>
                    </div>
                </div>
            @endif

            <div style="position:fixed;bottom:0px;left:0px;width:100%;color: #000;" class="footer">
                <small style="font-size: 5px; float: right;" class="text-end">
                    @lang('menu.print_date'): {{ date('d-m-Y , h:iA') }}
                </small>
            </div>
        </div>
    </div>
 <!-- production print templete end-->
