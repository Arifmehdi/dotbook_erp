@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
@endphp

<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-display">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    @lang('menu.order_details') (@lang('menu.order_id') : <strong><span>{{ $order->order_id }}</span></strong>)
                </h5>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                    <span class="fas fa-times"></span>
                </a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;">
                                <strong>@lang('menu.customer') :- </strong>
                            </li>

                            <li style="font-size:11px!important;">
                                <strong>@lang('menu.name') : </strong> {{ $order->customer ? $order->customer->name : 'Walk-In-Customer' }}
                            </li>

                            <li style="font-size:11px!important;">
                                <strong>@lang('menu.address') : </strong> {{ $order->customer ? $order->customer->address : '' }}
                            </li>

                            <li style="font-size:11px!important;">
                                <strong>@lang('menu.tax_number') : </strong> {{ $order->customer ? $order->customer->tax_number : '' }}
                            </li>

                            <li style="font-size:11px!important;">
                                <strong>@lang('menu.phone') : </strong>{{ $order->customer ? $order->customer->phone : '' }}
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-start">
                        <ul class="list-unstyled">
                            @if ($order->quotation_id)
                                <li style="font-size:11px!important;">
                                    <strong>@lang('menu.quotation_id')  : </strong> {{ $order->quotation_id }}
                                </li>
                            @endif

                            <li style="font-size:11px!important;">
                                <strong> @lang('menu.order_id')  : </strong> {{ $order->order_id }}
                            </li>

                            <li style="font-size:11px!important;">
                                <strong>@lang('menu.order_date') : </strong>
                                {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($order->order_date))}}
                            </li>

                            <li style="font-size:11px!important;"><strong> @lang('menu.sr') : </strong> {{ $order?->sr?->prefix . ' ' . $order?->sr?->name . ' ' . $order?->sr?->last_name }} </li>
                            <li style="font-size:11px!important;"><strong> @lang('menu.sales_ledger_ac') : </strong> {{ $order?->salesAccount?->name }}</li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-start">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;">
                                <strong>{{ __("D/o Approval") }} : </strong>
                                @if ($order->do_approval == 0)

                                    <span class="badge bg-danger">@lang('menu.pending')</</span>
                                @elseif($order->do_approval == 1)

                                    <span class="badge bg-success">@lang('menu.approved')</span>
                                @endif
                            </li>

                            <li style="font-size:11px!important;">
                                <strong>@lang('menu.expire_date') : </strong>
                                {{ $order->expire_date ? date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($order->expire_date)) : '' }}
                            </li>

                            <li style="font-size:11px!important;"><strong> @lang('menu.created_by') : </strong> {{ $order?->orderBy?->prefix . ' ' . $order?->orderBy?->name . ' ' . $order?->orderBy?->last_name }} </li>
                        </ul>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="table-responsive">
                        <table id="" class="table modal-table table-sm table-striped">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th class="text-start" style="font-size:11px!important;">@lang('menu.serial')</th>
                                    <th class="text-start" style="font-size:11px!important;">@lang('menu.item')</th>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.ordered_qty')</th>
                                    <th class="text-end" style="font-size:11px!important;">@lang('short.delivered_qty')</th>
                                    <th class="text-end" style="font-size:11px!important;">@lang('short.left_qty')</th>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.unit_price_exc_tax')</th>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.rate_type')</th>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.unit_discount')</th>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.unit_tax')</th>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.unit_price_inc_tax')</th>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.sub_total')</th>
                                </tr>
                            </thead>
                            <tbody class="sale_product_list">
                                @php
                                    $isMultiplierUnitExists = 0;
                                    $totalPrAmount = 0;
                                    $totalOrderedQtyAsMultiplier = 0;
                                    $totalDeliveredQtyAsMultiplier = 0;
                                    $totalLeftQtyAsMultiplier = 0;
                                    $totalOrderedQtyAsBaseUnit = 0;
                                    $totalDeliveredQtyAsBaseUnit = 0;
                                    $totalLeftQtyAsBaseUnit = 0;
                                @endphp

                                @foreach ($order->saleProducts as $saleProduct)
                                    <tr>
                                        <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}</td>
                                        @php
                                            $variant = $saleProduct->variant ? ' -' . $saleProduct->variant->variant_name : '';
                                        @endphp
                                        <td class="text-start" style="font-size:11px!important;">{{ $saleProduct->product->name . $variant }}</td>

                                        @php
                                            $baseUnitMultiplier = $saleProduct?->saleUnit?->base_unit_multiplier ? $saleProduct?->saleUnit?->base_unit_multiplier : 1;
                                            $orderedQty = $saleProduct->ordered_quantity / $baseUnitMultiplier;
                                            $doDeliveredQty = $saleProduct->do_delivered_qty / $baseUnitMultiplier;
                                            $doLeftQty = $saleProduct->do_left_qty / $baseUnitMultiplier;

                                            $totalOrderedQtyAsMultiplier += $orderedQty;
                                            $totalDeliveredQtyAsMultiplier += $doDeliveredQty;
                                            $totalLeftQtyAsMultiplier += $doLeftQty;

                                            $totalOrderedQtyAsBaseUnit += $saleProduct->ordered_quantity;
                                            $totalDeliveredQtyAsBaseUnit += $saleProduct->do_delivered_qty;
                                            $totalLeftQtyAsBaseUnit += $saleProduct->do_left_qty;
                                        @endphp

                                        <td class="text-end" style="font-size:11px!important;">
                                            @if ($saleProduct?->saleUnit?->baseUnit)
                                                @php
                                                    $isMultiplierUnitExists = 1;
                                                @endphp

                                                (<strong>{{ App\Utils\Converter::format_in_bdt($saleProduct->ordered_quantity) }}/{{ $saleProduct?->saleUnit?->baseUnit->code_name }}</strong>)={{ App\Utils\Converter::format_in_bdt($orderedQty) }}/{{ $saleProduct?->saleUnit?->code_name }}
                                            @else

                                                {{ App\Utils\Converter::format_in_bdt($orderedQty) }}/{{ $saleProduct?->saleUnit?->code_name }}
                                            @endif
                                        </td>

                                        <td class="text-end" style="font-size:11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($saleProduct->do_delivered_qty) }}/{{ $saleProduct?->saleUnit?->baseUnit ? $saleProduct?->saleUnit?->baseUnit->code_name : $saleProduct?->saleUnit?->code_name }}
                                        </td>

                                        <td class="text-end" style="font-size:11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($saleProduct->do_left_qty) }}/{{ $saleProduct?->saleUnit?->baseUnit ? $saleProduct?->saleUnit?->baseUnit->code_name : $saleProduct?->saleUnit?->code_name }}
                                        </td>

                                        <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_exc_tax * $baseUnitMultiplier) }}</td>

                                        @php
                                            $showPrAmount = $saleProduct->price_type == 'PR' ? '('.$saleProduct->pr_amount.')' : '';
                                            $totalPrAmount += $orderedQty * $saleProduct->pr_amount
                                        @endphp

                                        <td class="text-end" style="font-size:11px!important;">{{ $saleProduct->price_type.$showPrAmount }} </td>

                                        @php
                                            $DiscountType = $saleProduct->unit_discount_type == 1 ? '('.__('menu.fixed').') ' : '(' . $saleProduct->unit_discount . '%) ';
                                        @endphp

                                        <td class="text-end" style="font-size:11px!important;">{{ $DiscountType . App\Utils\Converter::format_in_bdt($saleProduct->unit_discount_amount * $baseUnitMultiplier) }}</td>

                                        <td class="text-end" style="font-size:11px!important;">
                                            {{ '(' . $saleProduct->unit_tax_percent . '%) ' . App\Utils\Converter::format_in_bdt($saleProduct->unit_tax_amount * $baseUnitMultiplier) }}
                                        </td>

                                        <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_inc_tax * $baseUnitMultiplier) }}</td>
                                        <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($saleProduct->subtotal) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-end" style="font-size:11px!important;">@lang('menu.total') :</th>
                                    <th class="text-end" style="font-size:11px!important;">
                                        @if ($isMultiplierUnitExists == 1)
                                            ({{ App\Utils\Converter::format_in_bdt($totalOrderedQtyAsBaseUnit) }}) =
                                        @endif

                                        {{ App\Utils\Converter::format_in_bdt($totalOrderedQtyAsMultiplier) }}
                                    </th>
                                    <th class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($totalDeliveredQtyAsBaseUnit) }}</th>
                                    <th class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($totalLeftQtyAsBaseUnit) }}</th>
                                    <th class="text-end" style="font-size:11px!important;">---</th>
                                    <th class="text-end" style="font-size:11px!important;">---</th>
                                    <th class="text-end" style="font-size:11px!important;">---</th>
                                    <th class="text-end" style="font-size:11px!important;">---</th>
                                    <th class="text-end" style="font-size:11px!important;">---</th>
                                    <th class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($order->net_total_amount) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        @if (auth()->user()->can('receipts_index'))
                            <p class="fw-bold">@lang('menu.receipts_against_reference')</p>
                            @include('sales_app.sales_order.ajax_view.partials.modal_receipt_list')
                        @endif
                    </div>

                    <div class="col-md-4">
                        <div class="table-responsive">
                            <table class="table modal-table table-sm">
                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.rate_type') : </th>
                                    <td class="text-end" style="font-size:11px!important;">{{ $order->all_price_type }}{{ $order->all_price_type == 'PR' ? '('.App\Utils\Converter::format_in_bdt($totalPrAmount).')' : '' }}</td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.total_ordered_qty') : </th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        <span class="float-start" style="font-size:11px!important;">(@lang('menu.as_base_unit')) = </span> {{ App\Utils\Converter::format_in_bdt($totalOrderedQtyAsBaseUnit) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.total_delivered_qty') : </th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        <span class="float-start" style="font-size:11px!important;">(@lang('menu.as_base_unit')) = </span>  {{ App\Utils\Converter::format_in_bdt($totalDeliveredQtyAsBaseUnit) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.net_total_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($order->net_total_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.order_discount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        @if ($order->order_discount_type == 1)
                                            {{ App\Utils\Converter::format_in_bdt($order->order_discount_amount) }} (Fixed)
                                        @else
                                            {{ App\Utils\Converter::format_in_bdt($order->order_discount_amount) }} ({{ $order->order_discount }}%)
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.order_tax') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($order->order_tax_amount) . ' (' . $order->order_tax_percent . '%)' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.shipment_cost') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($order->shipment_charge) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.total_ordered_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($order->total_payable_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.received_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        {{ App\Utils\Converter::format_in_bdt($order->paid) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.current_balance') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end fw-bold">
                                        @php
                                         $accountUtil = new App\Utils\AccountUtil();
                                         $amounts = $accountUtil->accountClosingBalance($order->customer_account_id, $order->sr_user_id);
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
                    <div class="col-md-3">
                        <p style="font-size:11px!important;"><strong>@lang('menu.shipping_address')</strong> : </p>
                        <p style="font-size:11px!important;">{{ $order->shipping_address }}</p>
                    </div>

                    <div class="col-md-3">
                        <p style="font-size:11px!important;"><strong>@lang('menu.comment')</strong> : </p>
                        <p style="font-size:11px!important;">{{ $order->comment}}</p>
                    </div>

                    <div class="col-md-3">
                        <p style="font-size:11px!important;"><strong>@lang('menu.sale_note')</strong> : </p>
                        <p style="font-size:11px!important;">{{ $order->sale_note }}</p>
                    </div>

                    <div class="col-md-3">
                        <p style="font-size:11px!important;"><strong>@lang('menu.payment_note')</strong> : </p>
                        <p style="font-size:11px!important;">{{ $order->payment_note }}</p>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <a href="{{ route('sales.order.do.approval', $order->id) }}" class="btn btn-sm btn-success action_hideable m-0 me-2" id="do_approval">@lang('menu.do_approval')</a>
                <a class="btn btn-sm btn-secondary m-0 me-2" href="{{ route('sales.order.edit', $order->id) }}"> Edit</a>
                <button type="button" class="footer_btn btn btn-sm btn-primary m-0 me-2" id="print_modal_details_btn">Print Order </button>
                <button type="button" class="btn btn-sm btn-danger m-0" data-bs-dismiss="modal">@lang('menu.close')</button>
            </div>
        </div>
    </div>
</div>
<!-- Details Modal End-->

<!-- Quotation print templete-->
@include('sales_app.sales_order.ajax_view.partials.order_print_default_layout')
<!-- Quotation print templete end-->
