@php
    $inWord = new NumberFormatter("en", NumberFormatter::SPELLOUT);
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-display">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    @lang('menu.delivery_order_details') | (@lang('menu.do_id') : <strong><span>{{ $do->do_id }}</span></strong>)
                </h5>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                    <span class="fas fa-times"></span>
                </a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.customer') :- </strong></li>
                            <li style="font-size:11px!important;"><strong>@lang('menu.name') : </strong> {{ $do?->customer?->name }}</li>
                            <li style="font-size:11px!important;"><strong>@lang('menu.phone') : </strong>{{ $do?->customer?->phone }}</li>
                            <li style="font-size:11px!important;"><strong>@lang('menu.address') : </strong> {{ $do?->customer?->address }}</li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-start">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong> @lang('menu.order_id') : </strong> {{ $do->order_id }}</li>

                            <li style="font-size:11px!important;">
                                <strong>@lang('menu.order_date') : </strong>
                                {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($do->order_date)) . ' ' . $do->time }}
                            </li>

                            <li style="font-size:11px!important;"><strong> @lang('menu.do_id') : </strong> {{ $do->do_id }}</li>

                            <li style="font-size:11px!important;">
                                <strong>@lang('menu.do_date') : </strong>
                                {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($do->do_date)) . ' ' . $do->time }}
                            </li>

                            <li style="font-size:11px!important;"><strong>@lang('menu.status') : </strong>
                                <span class="badge bg-primary">@lang('menu.delivery_order')</span>
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __("Sr.") }} : </strong> {{ $do?->sr?->prefix . ' ' . $do?->sr?->name . ' ' . $do?->sr?->last_name }} </li>
                            <li style="font-size:11px!important;"><strong> @lang('menu.created_by') : </strong> {{ $do?->doBy?->prefix . ' ' . $do?->doBy?->name . ' ' . $do?->doBy?->last_name }} </li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-start">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong> Delivery Order From: </strong></li>
                            <li style="font-size:11px!important;"><strong>@lang('menu.business_location') : </strong>
                                {{ json_decode($generalSettings->business, true)['shop_name'] }} <b></b>
                            </li>

                            <li style="font-size:11px!important;"><strong>@lang('menu.address') : </strong>{{ json_decode($generalSettings->business, true)['address'] }}</li>
                            <li style="font-size:11px!important;"><strong>@lang('menu.phone') : </strong>{{ json_decode($generalSettings->business, true)['phone'] }}</li>
                        </ul>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="table-responsive">
                        <table id="" class="table modal-table table-sm table-striped">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th class="text-start" style="font-size:11px!important;">@lang('menu.sl')</th>
                                    <th class="text-start" style="font-size:11px!important;">@lang('menu.item')</th>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.do_qty')</th>
                                    <th class="text-end" style="font-size:11px!important;">@lang('short.delivered_qty')</th>
                                    <th class="text-end" style="font-size:11px!important;">@lang('short.left_qty')</th>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.price_exc_tax')</th>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.unit_discount')</th>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.unit_tax')</th>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.price_inc_tax')</th>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.sub_total')</th>
                                </tr>
                            </thead>
                            <tbody class="sale_product_list">
                                @php
                                    $isMultiplierUnitExists = 0;
                                    $totalPrAmount = 0;
                                    $totalDoQtyAsMultiplier = 0;
                                    $totalDeliveredQtyAsMultiplier = 0;
                                    $totalLeftQtyAsMultiplier = 0;
                                    $totalDoQtyAsBaseUnit = 0;
                                    $totalDeliveredQtyAsBaseUnit = 0;
                                    $totalLeftQtyAsBaseUnit = 0;
                                @endphp

                                @foreach ($do->saleProducts as $doProduct)
                                    <tr>
                                        <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}</td>

                                        @php
                                            $variant = $doProduct->variant ? ' -' . $doProduct->variant->variant_name : '';
                                        @endphp

                                        <td class="text-start" style="font-size:11px!important;">{{ $doProduct->product->name . $variant }}</td>
                                        @php
                                            $baseUnitMultiplier = $doProduct?->saleUnit?->base_unit_multiplier ? $doProduct?->saleUnit?->base_unit_multiplier : 1;
                                            $doQty = $doProduct->do_qty / $baseUnitMultiplier;
                                            $doDeliveredQty = $doProduct->do_delivered_qty / $baseUnitMultiplier;
                                            $doLeftQty = $doProduct->do_left_qty / $baseUnitMultiplier;

                                            $totalDoQtyAsMultiplier += $doQty;
                                            $totalDeliveredQtyAsMultiplier += $doDeliveredQty;
                                            $totalLeftQtyAsMultiplier += $doLeftQty;

                                            $totalDoQtyAsBaseUnit += $doProduct->do_qty;
                                            $totalDeliveredQtyAsBaseUnit += $doProduct->do_delivered_qty;
                                            $totalLeftQtyAsBaseUnit += $doProduct->do_left_qty;
                                        @endphp

                                        <td class="text-end" style="font-size:11px!important;">
                                            @if ($doProduct?->saleUnit?->baseUnit)
                                                @php
                                                    $isMultiplierUnitExists = 1;
                                                @endphp

                                                (<strong>{{ App\Utils\Converter::format_in_bdt($doProduct->do_qty) }}/{{ $doProduct?->saleUnit?->baseUnit->code_name }}</strong>)={{ App\Utils\Converter::format_in_bdt($doQty) }}/{{ $doProduct?->saleUnit?->code_name }}
                                            @else

                                                {{ App\Utils\Converter::format_in_bdt($doQty) }}/{{ $doProduct?->saleUnit?->code_name }}
                                            @endif
                                        </td>

                                        <td class="text-end" style="font-size:11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($doProduct->do_delivered_qty) }}/{{ $doProduct?->saleUnit?->baseUnit ? $doProduct?->saleUnit?->baseUnit->code_name : $doProduct?->saleUnit?->code_name }}
                                        </td>

                                        <td class="text-end" style="font-size:11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($doProduct->do_left_qty) }}/{{ $doProduct?->saleUnit?->baseUnit ? $doProduct?->saleUnit?->baseUnit->code_name : $doProduct?->saleUnit?->code_name }}
                                        </td>

                                        <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($doProduct->unit_price_exc_tax * $baseUnitMultiplier) }}</td>

                                        @php
                                            $DiscountType = $doProduct->unit_discount_type == 1 ? '(Fixed) ' : '(' . $doProduct->unit_discount . '%) ';
                                        @endphp

                                        <td class="text-end" style="font-size:11px!important;">
                                            {{ $DiscountType . App\Utils\Converter::format_in_bdt($doProduct->unit_discount_amount * $baseUnitMultiplier) }}
                                        </td>

                                        <td class="text-end" style="font-size:11px!important;">
                                            {{ '(' . $doProduct->unit_tax_percent . '%) ' . App\Utils\Converter::format_in_bdt($doProduct->unit_tax_amount * $baseUnitMultiplier) }}
                                        </td>

                                        <td class="text-end" style="font-size:11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($doProduct->unit_price_inc_tax * $baseUnitMultiplier) }}
                                        </td>

                                        <td class="text-end" style="font-size:11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($doProduct->subtotal) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-end" style="font-size:11px!important;">@lang('menu.total') : </th>
                                    <th class="text-end">
                                        @if ($isMultiplierUnitExists == 1)
                                            ({{ App\Utils\Converter::format_in_bdt($totalDoQtyAsBaseUnit) }}) =
                                        @endif

                                        {{ App\Utils\Converter::format_in_bdt($totalDoQtyAsMultiplier) }}
                                    </th>
                                    <th class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($do->total_delivered_qty) }}</th>
                                    <th class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($do->do_total_left_qty) }}</th>
                                    <th class="text-end" style="font-size:11px!important;">---</th>
                                    <th class="text-end" style="font-size:11px!important;">---</th>
                                    <th class="text-end" style="font-size:11px!important;">---</th>
                                    <th class="text-end" style="font-size:11px!important;">---</th>
                                    <th class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($do->net_total_amount) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-8">
                        @if (auth()->user()->can('receive_payment_index'))
                            <p class="fw-bold">@lang('menu.receipts_against_reference')</p>
                            @include('sales_app.delivery_order.ajax_view.partials.modal_receipt_list')
                        @endif
                    </div>

                    <div class="col-4">
                        <div class="table-responsive">
                            <table class="table modal-table table-sm">
                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('short.total_do_qty') : </th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        <span class="float-start" style="font-size:11px!important;">(@lang('menu.as_base_unit')) = </span> {{ App\Utils\Converter::format_in_bdt($do->total_do_qty) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.total_delivered_qty') : </th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        <span class="float-start" style="font-size:11px!important;">(@lang('menu.as_base_unit')) = </span> {{ App\Utils\Converter::format_in_bdt($do->total_delivered_qty) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.total_left_qty') : </th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        <span class="float-start" style="font-size:11px!important;">(@lang('menu.as_base_unit')) = </span> {{ App\Utils\Converter::format_in_bdt($do->do_total_left_qty) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.net_total_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($do->net_total_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;"> @lang('menu.order_discount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        @if ($do->order_discount_type == 1)
                                            {{ App\Utils\Converter::format_in_bdt($do->order_discount_amount) }} (Fixed)
                                        @else
                                            {{ App\Utils\Converter::format_in_bdt($do->order_discount_amount) }} ({{ $do->order_discount }}%)
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.order_tax') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($do->order_tax_amount) . ' (' . $do->order_tax_percent . '%)' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.shipment_cost') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($do->shipment_charge) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.total_ordered_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($do->total_payable_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.received_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($do->paid) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.current_balance') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end fw-bold">
                                        @php
                                            $accountUtil = new App\Utils\AccountUtil();
                                            $amounts = $accountUtil->accountClosingBalance($do->customer_account_id, $do->sr_user_id);
                                        @endphp
                                        {{ $amounts['closing_balance_string'] }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="details_area">
                            <p style="font-size:11px!important;"><strong>@lang('menu.shipping_address')</strong> : </p>
                            <p class="shipping_address">{{ $do->shipping_address ? $do->shipping_address : 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="details_area">
                            <p style="font-size:11px!important;"><strong>@lang('menu.sale_note')</strong> : </p>
                            <p class="sale_note">{{ $do->sale_note ? $do->sale_note : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                @if (auth()->user()->can('edit_do'))

                    <a class="btn btn-sm btn-secondary" href="{{ route('sales.delivery.order.edit', $do->id) }}"> @lang('menu.edit')</a>
                @endif

                <button type="button" class="footer_btn btn btn-sm btn-primary print_btn">@lang('menu.print_do')</button>
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">@lang('menu.close')</button>
            </div>
        </div>
    </div>
</div>
<!-- Details Modal End-->

<!-- D/O print templete-->
@include('sales_app.delivery_order.ajax_view.partials.do_default_print_layout')
<!-- D/O print templete end-->
