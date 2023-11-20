@php
    $inWord = new NumberFormatter("en", NumberFormatter::SPELLOUT);
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
<style>
    @page {size:a4;margin-top: 0.8cm;/* margin-bottom: 35px;  */margin-left: 4%;margin-right: 4%;}
</style>
<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-full-display">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    @lang('menu.quotation_details') (@lang('menu.quotation_id') : <strong>{{ $quotation->quotation_id }}</strong>)
                </h5>

                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;">
                                <strong>@lang('menu.customer') : </strong>{{ $quotation->customer ? $quotation->customer->name : 'Walk-In-Customer' }}
                            </li>
                            <li style="font-size:11px!important;">
                                <strong>@lang('menu.address') : </strong>{{ $quotation->customer ? $quotation->customer->address : '' }}
                            </li>
                            <li style="font-size:11px!important;">
                                <strong>@lang('menu.tax_number') : </strong> {{ $quotation->customer ? $quotation->customer->tax_number : '' }}
                            </li>
                            <li style="font-size:11px!important;">
                                <strong>@lang('menu.phone') : </strong> {{ $quotation->customer ? $quotation->customer->phone : '' }}
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>@lang('menu.quotation_id') : </strong> {{ $quotation->quotation_id }}</li>

                            <li style="font-size:11px!important;"><strong>@lang('menu.current_status') : </strong>
                                <span class="sale_status">
                                    <span class="badge bg-info">@lang('menu.quotation')</span>
                                </span>
                            </li>

                            <li style="font-size:11px!important;">
                                <strong>@lang('menu.sr') : </strong>
                                {{ $quotation?->sr?->prefix . ' ' . $quotation?->sr?->name . ' ' . $quotation?->sr?->last_name }}
                            </li>

                            <li style="font-size:11px!important;">
                                <strong>@lang('menu.created_by') : </strong>
                                {{ $quotation?->quotationBy?->prefix . ' ' . $quotation?->quotationBy?->name . ' ' . $quotation?->quotationBy?->last_name }}
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li><strong>Entered From : </strong></li>
                            @if ($quotation->branch)
                                <li style="font-size:11px!important;">
                                    <strong>@lang('menu.business_location') : </strong> {{ $quotation->branch->name.'/'.$quotation->branch->branch_code }}
                                </li>
                                <li style="font-size:11px!important;"><strong>@lang('menu.phone') : </strong> {{ $quotation->branch->phone }}</li>
                                <li style="font-size:11px!important;"><strong>@lang('menu.address') : </strong>
                                    {{ $quotation->branch->name }}/{{ $quotation->branch->branch_code }},
                                    {{ $quotation->branch->city }}, {{ $quotation->branch->state }},
                                    {{ $quotation->branch->zip_code }}, {{ $quotation->branch->country }}
                                </li>
                            @else
                                <li style="font-size:11px!important;"><strong>@lang('menu.business_location') : </strong>
                                    {{ json_decode($generalSettings->business, true)['shop_name'] }} <b></b>
                                </li>
                                <li style="font-size:11px!important;"><strong>@lang('menu.phone') : </strong> <span>{{ json_decode($generalSettings->business, true)['phone'] }}</span></li>
                                <li style="font-size:11px!important;"><strong>@lang('menu.address') : </strong> <span>{{ json_decode($generalSettings->business, true)['address'] }}</span></li>
                            @endif
                        </ul>
                    </div>
                </div><br>

                <div class="row">
                    <div class="table-responsive">
                        <table id="" class="table modal-table table-sm">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th class="text-start" style="font-size:11px!important;">@lang('menu.item')</th>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.quantity')</th>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.unit_price_exc_tax')</th>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.rate_type')</th>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.unit_discount')</th>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.unit_tax')</th>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.unit_price_inc_tax')</th>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.sub_total')</th>
                                </tr>
                            </thead>
                            <tbody class="quotation_product_list">
                                @php
                                    $totalPrAmount = 0;
                                    $isMultiplierUnitExists = 0;
                                @endphp
                                @foreach ($quotation->saleProducts as $quotationProduct)
                                    <tr>
                                        @php
                                            $variant = $quotationProduct->variant ? ' -' . $quotationProduct->variant->variant_name : '';
                                        @endphp
                                        <td class="text-start" style="font-size:11px!important;">{{ $quotationProduct->product->name . $variant }}</td>
                                        @php
                                            $baseUnitMultiplier = $quotationProduct?->saleUnit?->base_unit_multiplier ? $quotationProduct?->saleUnit?->base_unit_multiplier : 1;
                                            $quotedQty = $quotationProduct->ordered_quantity / $baseUnitMultiplier;
                                        @endphp

                                        <td class="text-end" style="font-size:10px!important;">
                                            @if ($quotationProduct?->saleUnit->baseUnit)
                                                @php
                                                    $isMultiplierUnitExists = 1;
                                                @endphp

                                                (<strong>{{ App\Utils\Converter::format_in_bdt($quotationProduct->ordered_quantity) }}/{{ $quotationProduct?->saleUnit?->baseUnit?->code_name }}</strong>)={{ App\Utils\Converter::format_in_bdt($quotedQty) }}/{{ $quotationProduct?->saleUnit?->code_name }}
                                            @else

                                                {{ App\Utils\Converter::format_in_bdt($quotedQty) }}/{{ $quotationProduct?->saleUnit?->code_name }}
                                            @endif
                                        </td>

                                        <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($quotationProduct->unit_price_exc_tax * $baseUnitMultiplier) }} </td>

                                        @php
                                            $showPrAmount = $quotationProduct->price_type == 'PR' ? '('.App\Utils\Converter::format_in_bdt($quotationProduct->pr_amount).')' : '';
                                            $totalPrAmount += $quotedQty * $quotationProduct->pr_amount
                                        @endphp

                                        <td class="text-end" style="font-size:11px!important;">{{ $quotationProduct->price_type.$showPrAmount }} </td>
                                        @php
                                            $DiscountType = $quotationProduct->unit_discount_type == 1 ? '('.__('menu.fixed').') ' : '(' . $quotationProduct->unit_discount . '%) ';
                                        @endphp

                                        <td class="text-end" style="font-size:11px!important;">
                                            {{ $DiscountType . App\Utils\Converter::format_in_bdt($quotationProduct->unit_discount_amount * $baseUnitMultiplier) }}
                                        </td>

                                        <td class="text-end" style="font-size:11px!important;">
                                            {{ '(' . $quotationProduct->unit_tax_percent . '%) ' . App\Utils\Converter::format_in_bdt($quotationProduct->unit_tax_amount * $baseUnitMultiplier)  }}
                                        </td>

                                        <td class="text-end" style="font-size:11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($quotationProduct->unit_price_inc_tax * $baseUnitMultiplier) }}
                                        </td>

                                        <td class="text-end" style="font-size:11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($quotationProduct->subtotal) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 offset-md-6">
                        <div class="table-responsive">
                            <table class="table modal-table table-sm">
                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.rate_type') : </th>
                                    <td class="text-end" style="font-size:11px!important;">{{ $quotation->all_price_type }}{{ $quotation->all_price_type == 'PR' ? '('.App\Utils\Converter::format_in_bdt($totalPrAmount).')' : '' }}</td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.net_total_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($quotation->net_total_amount) }}</td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.order_discount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        @php
                                            $discount_type = $quotation->order_discount_type == 1 ? ' (Fixed)' : '%';
                                        @endphp
                                        {{ App\Utils\Converter::format_in_bdt($quotation->order_discount_amount) . $discount_type }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.order_tax') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($quotation->order_tax_amount) . ' (' . $quotation->order_tax_percent . '%)' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.shipment_charge') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($quotation->shipment_charge) }}</td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.total_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($quotation->total_payable_amount) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                <button type="submit" id="print_payment" class="btn btn-sm btn-success print_btn">@lang('menu.print')</button>
            </div>
        </div>
    </div>
</div>
<!-- Details Modal End-->

<!-- Quotation print templete-->
@include('sales_app.quotations.ajax_view.partials.quotation_default_print_layout')
<!-- Quotation print templete end-->
