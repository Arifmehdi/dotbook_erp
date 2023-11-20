<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
    <div class="modal-dialog col-65-modal">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    @lang('menu.sales_return') | (@lang('menu.voucher_no') : <strong>{{ $saleReturn->voucher_no }}</strong>)
                </h5>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                class="fas fa-times"></span></a>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>@lang('menu.return_date') : </strong> {{ $saleReturn->date }}</li>
                            <li style="font-size:11px!important;"><strong>@lang('menu.voucher_no') : </strong> {{ $saleReturn->voucher_no }}</li>
                            <li style="font-size:11px!important;"><strong>@lang('menu.customer') : </strong> {{ $saleReturn?->customer?->name }}</li>
                            <li style="font-size:11px!important;">
                                <strong> @lang('menu.sr') : </strong> {{ $saleReturn?->sr?->prefix . ' ' . $saleReturn?->sr?->name . ' ' . $saleReturn?->sr?->last_name }}
                            </li>
                            <li style="font-size:11px!important;"><strong>@lang('menu.stored_location') : </strong>
                                @if ($saleReturn->warehouse)

                                    {{ $saleReturn->warehouse->warehouse_name . '/' .$saleReturn->warehouse->warehouse_code }}
                                @else

                                    {{ json_decode($generalSettings->business, true)['shop_name'] }}
                                @endif
                            </li>
                        </ul>
                    </div>

                    <div class="col-6 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>@lang('menu.sale_details') </strong></li>
                            <li style="font-size:11px!important;"><strong>@lang('menu.invoice_no') : </strong> {{ $saleReturn->sale ? $saleReturn->sale->invoice_id : '' }}</li>
                            <li style="font-size:11px!important;"><strong>@lang('menu.date') : </strong> {{ $saleReturn->sale ? $saleReturn->sale->date : '' }}</li>
                        </ul>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="" class="table modal-table table-sm table-striped">
                                <thead>
                                    <tr class="bg-primary text-white">
                                        <th class="text-start" style="font-size:11px!important;">@lang('menu.sl')</th>
                                        <th class="text-start" style="font-size:11px!important;">@lang('menu.item')</th>
                                        <th class="text-end" style="font-size:11px!important;">@lang('menu.return_quantity')</th>
                                        <th class="text-end" style="font-size:11px!important;">@lang('menu.unit_price_inc_tax')</th>
                                        <th class="text-end" style="font-size:11px!important;">@lang('menu.sub_total')</th>
                                    </tr>
                                </thead>
                                <tbody class="sale_return_product_list">
                                    @foreach ($saleReturn->returnProducts as $returnProduct)
                                        <tr>
                                            <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}</td>
                                            <td class="text-start" style="font-size:11px!important;">
                                                {{ $returnProduct->product->name }}

                                                @if ($returnProduct->variant)

                                                    -{{ $returnProduct->variant->variant_name }}
                                                @endif

                                                @if ($returnProduct->variant)

                                                    ({{ $returnProduct->variant->variant_code }})
                                                @else

                                                    ({{ $returnProduct->product->product_code }})
                                                @endif
                                            </td>

                                            @php
                                                $baseUnitMultiplier = $returnProduct?->returnUnit?->base_unit_multiplier ? $returnProduct?->returnUnit?->base_unit_multiplier : 1;
                                                $returnedQty = $returnProduct->return_qty / $baseUnitMultiplier;
                                            @endphp

                                            <td class="text-end" style="font-size:11px!important;">
                                                @if ($returnProduct?->returnUnit?->baseUnit)
                                                    @php
                                                        $isMultiplierUnitExists = 1;
                                                    @endphp

                                                    (<strong>{{ App\Utils\Converter::format_in_bdt($returnProduct->return_qty) }}/{{ $returnProduct?->returnUnit?->baseUnit->code_name }}</strong>)={{ App\Utils\Converter::format_in_bdt($returnedQty) }}/{{ $returnProduct?->returnUnit?->code_name }}
                                                @else

                                                    {{ App\Utils\Converter::format_in_bdt($returnedQty) }}/{{ $returnProduct?->returnUnit?->code_name }}
                                                @endif
                                            </td>

                                            <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($returnProduct->unit_price_inc_tax * $baseUnitMultiplier) }}</td>

                                            <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($returnProduct->return_subtotal) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 offset-md-6">
                        <div class="table-responsive">
                            <table class="table modal-table tabl-sm">
                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.net_total_amount') : {{ json_decode($generalSettings->business, true)['currency'] }} </th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($saleReturn->net_total_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.return_discount') : {{ json_decode($generalSettings->business, true)['currency'] }} </th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($saleReturn->return_discount_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.return_tax') : {{ json_decode($generalSettings->business, true)['currency'] }} </th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        ({{ App\Utils\Converter::format_in_bdt($saleReturn->return_tax_percent) }}%) {{ App\Utils\Converter::format_in_bdt($saleReturn->return_tax_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.total_return') @lang('menu.amount') : {{ json_decode($generalSettings->business, true)['currency'] }} </th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($saleReturn->total_return_amount) }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-sm btn-success" id="print_modal_details_btn">@lang('menu.print')</button>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
            </div>

        </div>
    </div>
</div>
<!-- Details Modal End-->

<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    div#footer {position:fixed;bottom:27px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px;  margin-left: 10px;margin-right: 10px;}

    h6 { font-size: 16px; }
    p {  font-size: 14px; }
    td {  color: black; }
</style>
@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
@endphp
 <!-- Sale print templete-->
 <div class="print_details d-none">
    <div class="details_area">
        <div class="row" style="border-bottom: 1px solid black;">
            <div class="col-4">
                <div class="heading text-start">
                    @if (json_decode($generalSettings->business, true)['business_logo'] != null)

                        <img style="height: auto; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                    @else

                        <span style="font-family: 'Anton', sans-serif;font-size:17px;color:gray;font-weight: 550; letter-spacing:1px;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
                    @endif
                </div>
            </div>

            <div class="col-8">
                <div class="heading text-end">
                    <p class="fw-bold" style="text-transform: uppercase;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</p>
                    <p><b>{{ json_decode($generalSettings->business, true)['address'] }}</b></p>
                    <p>
                        <strong>@lang('menu.email') : </strong> <b>{{ json_decode($generalSettings->business, true)['email'] }}</b>, <strong>@lang('menu.phone') : </strong> <b>{{ json_decode($generalSettings->business, true)['phone'] }}</b>
                    </p>
                </div>
            </div>
        </div>

        <div class="sale_and_deal_info pt-3">
            <div class="row">
                <div class="col-4">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;"><strong>@lang('menu.voucher_no') : </strong>{{ $saleReturn->voucher_no }}</li>
                        <li style="font-size:11px!important;"><strong>@lang('menu.return_date') : </strong>{{ $saleReturn->date }}</li>
                        <li style="font-size:11px!important;"><strong>@lang('menu.customer') : </strong>{{ $saleReturn?->customer?->name }}</li>
                        <li style="font-size:11px!important;">
                            <strong> @lang('menu.sr') : </strong> {{ $saleReturn?->sr?->prefix . ' ' . $saleReturn?->sr?->name . ' ' . $saleReturn?->sr?->last_name }}
                        </li>
                        <li style="font-size:11px!important;"><strong>@lang('menu.stored_location') : </strong>
                            @if ($saleReturn->warehouse)

                                {{ $saleReturn->warehouse->warehouse_name . '/' .$saleReturn->warehouse->warehouse_code }}
                            @else

                                {{ json_decode($generalSettings->business, true)['shop_name'] }}
                            @endif
                        </li>
                    </ul>
                </div>

                <div class="col-4 text-center">
                    <h5 style="text-transform: uppercase;">@lang('menu.sales_return_voucher')</h5>
                    <img style="width: 170px; height: 30px; margin-top: 15px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($saleReturn->voucher_no, $generator::TYPE_CODE_128)) }}">
                </div>

                <div class="col-4">
                    <ul class="list-unstyled float-right">
                        <li style="font-size:11px!important;"><strong>@lang('menu.sale_details')</strong> </li>
                        <li style="font-size:11px!important;">
                            <strong>@lang('menu.invoice_no') : </strong> {{ $saleReturn?->sale?->invoice_id }}
                        </li>

                        <li style="font-size:11px!important;"><strong>@lang('menu.date') : </strong>  {{ $saleReturn?->sale?->date }} </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="sale_product_table pt-3 pb-3">
            <table class="table report-table table-sm table-bordered print_table">
                <thead>
                    <tr>
                        <tr>
                            <th class="text-start" style="font-size:11px!important;">@lang('menu.sl')</th>
                            <th class="text-start" style="font-size:11px!important;">@lang('menu.item')</th>
                            <th class="text-end" style="font-size:11px!important;">@lang('menu.return_quantity')</th>
                            <th class="text-end" style="font-size:11px!important;">@lang('menu.unit_price_inc_tax')</th>
                            <th class="text-end" style="font-size:11px!important;">@lang('menu.sub_total')</th>
                        </tr>
                    </tr>
                </thead>
                <tbody class="sale_return_print_product_list">
                    @php
                        $isMultiplierUnitExists = 1;
                    @endphp
                    @foreach ($saleReturn->returnProducts as $returnProduct)

                        <tr>
                            <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}</td>
                            <td class="text-start" style="font-size:11px!important;">
                                {{ $returnProduct->product->name }}

                                @if ($returnProduct->variant)

                                    -{{ $returnProduct->variant->variant_name }}
                                @endif

                                @if ($returnProduct->variant)

                                    ({{ $returnProduct->variant->variant_code }})
                                @else

                                ({{ $returnProduct->product->product_code }})
                                @endif
                            </td>

                            @php
                                $baseUnitMultiplier = $returnProduct?->returnUnit?->base_unit_multiplier ? $returnProduct?->returnUnit?->base_unit_multiplier : 1;
                                $returnedQty = $returnProduct->return_qty / $baseUnitMultiplier;
                            @endphp

                            <td class="text-end" style="font-size:11px!important;">
                                @if ($returnProduct?->returnUnit?->baseUnit)
                                    @php
                                        $isMultiplierUnitExists = 1;
                                    @endphp

                                    (<strong>{{ App\Utils\Converter::format_in_bdt($returnProduct->return_qty) }}/{{ $returnProduct?->returnUnit?->baseUnit->code_name }}</strong>)={{ App\Utils\Converter::format_in_bdt($returnedQty) }}/{{ $returnProduct?->returnUnit?->code_name }}
                                @else

                                    {{ App\Utils\Converter::format_in_bdt($returnedQty) }}/{{ $returnProduct?->returnUnit?->code_name }}
                                @endif
                            </td>

                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($returnProduct->unit_price_inc_tax * $baseUnitMultiplier) }}
                            </td>

                            <td class="text-end" style="font-size:11px!important;">
                                {{ App\Utils\Converter::format_in_bdt($returnProduct->return_subtotal) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="border-bottom:1px solid white!important;">
                        <th class="text-end text-black" colspan="4" style="font-size:11px!important;">@lang('menu.net_total_amount') :</th>
                        <td class="text-end" colspan="2" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($saleReturn->net_total_amount) }}</td>
                    </tr>

                    <tr style="border-bottom:1px solid white!important;">
                        <th class="text-end text-black" colspan="4" style="font-size:11px!important;">@lang('menu.return_discount') :</th>
                        <td class="text-end" colspan="2" style="font-size:11px!important;">
                            @if ($saleReturn->return_discount_type == 1)
                                {{ App\Utils\Converter::format_in_bdt($saleReturn->return_discount_amount) }} (Fixed)
                            @else
                                {{ App\Utils\Converter::format_in_bdt($saleReturn->return_discount_amount) }} ({{ $saleReturn->return_discount}}%)
                            @endif
                        </td>
                    </tr>

                    <tr style="border-bottom:1px solid white!important;">
                        <th class="text-end text-black" colspan="4" style="font-size:11px!important;">@lang('menu.return_tax') :</th>
                        <td class="text-end" colspan="2" style="font-size:11px!important;">
                            ({{ App\Utils\Converter::format_in_bdt($saleReturn->return_tax_percent) }}%) {{ App\Utils\Converter::format_in_bdt($saleReturn->return_tax_amount) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end text-black" colspan="4" style="font-size:11px!important;">@lang('menu.total_return') @lang('menu.amount') :</th>
                        <td class="text-end" colspan="2" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($saleReturn->total_return_amount) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <br><br>
        <div class="row">
            <div class="col-4 text-start">
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('menu.prepared_by')</p>
            </div>

            <div class="col-4 text-center">
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('menu.checked_by')</p>
            </div>

            <div class="col-4 text-end">
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('menu.authorize_by')</p>
            </div>
        </div>

        <div id="footer">
            <div class="row mt-1">
                <div class="col-4 text-start">
                    <small>@lang('menu.print_date') :
                        {{ date(json_decode($generalSettings->business, true)['date_format']) }}
                    </small>
                </div>

                <div class="col-4 text-center">
                    @if (config('company.print_on_sale'))
                        <small class="d-block">@lang('menu.software_by') <strong>@lang('menu.speedDigit_pvt_ltd') .</strong></small>
                    @endif
                </div>

                <div class="col-4 text-end">
                    <small>@lang('menu.print_time') :{{ date($timeFormat) }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
