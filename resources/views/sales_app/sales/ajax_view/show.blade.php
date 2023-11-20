@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp

<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-display">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    @if ($sale->status == 1)
                        @lang('menu.sale_details') (@lang('menu.invoice_id') : <strong><span>{{ $sale->invoice_id }}</span></strong>)
                    @elseif($sale->status == 4 || $sale->status == 7)
                    @lang('menu.order_details') (@lang('menu.order_id') : <strong><span>{{ $sale->invoice_id }}</span></strong>)
                    @endif
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
                                <strong>@lang('menu.name') : </strong> {{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}
                            </li>

                            <li style="font-size:11px!important;">
                                <strong>@lang('menu.address') : </strong> {{ $sale->customer ? $sale->customer->address : '' }}
                            </li style="font-size:11px!important;">

                            <li style="font-size:11px!important;">
                                <strong>@lang('menu.phone') : </strong>{{ $sale->customer ? $sale->customer->phone : '' }}
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-start">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;">
                                <strong>@lang('menu.do_id') : </strong> {{ $sale?->do?->do_id }}
                            </li>

                            <li style="font-size:11px!important;">
                                <strong>@lang('menu.do_date') : </strong>
                                {{ $sale->do ? date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($sale->do->do_date)) : '' }}
                            </li>

                            <li style="font-size:11px!important;">
                                <strong>@lang('menu.invoice_id')  : </strong> {{ $sale->invoice_id }}
                            </li>

                            <li style="font-size:11px!important;">
                                <strong>@lang('menu.invoice_date') : </strong>
                                {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($sale->date)) }}
                            </li>

                            {{-- <li style="font-size:11px!important;"><strong>@lang('menu.sale_status') : </strong>
                                <span class="badge bg-success">@lang('menu.final')</span>
                            </li> --}}

                            <li style="font-size:11px!important;"><strong>@lang('menu.sales_ledger_ac') : </strong>
                                {{ $sale?->salesAccount?->name }}
                            </li>

                            <li style="font-size:11px!important;"><strong>@lang('menu.sr') : </strong>{{ $sale?->sr?->prefix . ' ' . $sale?->sr?->name . ' ' . $sale?->sr?->last_name }}
                            </li>

                            <li style="font-size:11px!important;"><strong>@lang('menu.created_by') : </strong>{{ $sale?->saleBy?->prefix . ' ' . $sale?->saleBy?->name . ' ' . $sale?->saleBy?->last_name }}
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-start">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>@lang('menu.sale_from') : </strong></li>
                            <li style="font-size:11px!important;"><strong>@lang('menu.stock_location') : </strong>
                                {{ json_decode($generalSettings->business, true)['shop_name'] }}</b>
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
                                    <th class="text-start" style="font-size:11px!important;">@lang('menu.serial')</th>
                                    <th class="text-start" style="font-size:11px!important;">@lang('menu.item')</th>
                                    <th class="text-start" style="font-size:11px!important;">@lang('menu.stock_location')</th>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.quantity')</th>
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
                                    $totalPrAmount = 0;
                                    $isMultiplierUnitExists = 0;
                                @endphp
                                @foreach ($sale->saleProducts as $saleProduct)
                                    <tr>
                                        <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}</td>
                                        @php
                                            $variant = $saleProduct->variant ? ' -' . $saleProduct->variant->variant_name : '';
                                        @endphp

                                        <td class="text-start" style="font-size:11px!important;">{{ $saleProduct->product->name . $variant }}</td>

                                        <td class="text-start" style="font-size:11px!important;">
                                            @if ($saleProduct->stock_warehouse_id)

                                                {{ $saleProduct->warehouse->warehouse_name.'/'.$saleProduct->warehouse->warehouse_code }}
                                            @else

                                                {{ json_decode($generalSettings->business, true)['shop_name'] }}
                                            @endif
                                        </td>

                                        <td class="text-end" style="font-size:11px!important;">
                                            @php
                                                $baseUnitMultiplier = $saleProduct?->saleUnit?->base_unit_multiplier ? $saleProduct?->saleUnit?->base_unit_multiplier : 1;
                                                $soldQty = $saleProduct->quantity / $baseUnitMultiplier;
                                            @endphp

                                            @if ($saleProduct?->saleUnit?->baseUnit)
                                                @php
                                                    $isMultiplierUnitExists = 1;
                                                @endphp

                                                (<strong>{{ App\Utils\Converter::format_in_bdt($saleProduct->quantity) }}/{{ $saleProduct?->saleUnit?->baseUnit->code_name }}</strong>)={{ App\Utils\Converter::format_in_bdt($soldQty) }}/{{ $saleProduct?->saleUnit?->code_name }}
                                            @else

                                                {{ App\Utils\Converter::format_in_bdt($soldQty) }}/{{ $saleProduct?->saleUnit?->code_name }}
                                            @endif
                                        </td>

                                        <td class="text-end" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_exc_tax * $baseUnitMultiplier) }}</td>

                                        @php
                                            $showPrAmount = $saleProduct->price_type == 'PR' ? '('.$saleProduct->pr_amount.')' : '';
                                            $totalPrAmount += $soldQty * $saleProduct->pr_amount;
                                        @endphp

                                        <td class="text-end" style="font-size:11px!important;">{{ $saleProduct->price_type.$showPrAmount }} </td>

                                        @php
                                            $DiscountType = $saleProduct->unit_discount_type == 1 ? '(Fixed) ' : ' (' . $saleProduct->unit_discount . '%)';
                                        @endphp

                                        <td class="text-end" style="font-size:11px!important;">
                                            {{ $DiscountType . App\Utils\Converter::format_in_bdt($saleProduct->unit_discount_amount * $baseUnitMultiplier) }}
                                        </td>

                                        <td class="text-end" style="font-size:11px!important;">
                                            {{ '(' . $saleProduct->unit_tax_percent . '%) ' . App\Utils\Converter::format_in_bdt($saleProduct->unit_tax_amount * $baseUnitMultiplier) }}
                                        </td>

                                        <td class="text-end" style="font-size:11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($saleProduct->unit_price_inc_tax * $baseUnitMultiplier) }}
                                        </td>

                                        <td class="text-end" style="font-size:11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($saleProduct->subtotal) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        @if (auth()->user()->can('receipts_index'))
                            <p class="fw-bold">@lang('menu.receipts_against_reference')</p>
                            @include('sales_app.sales.ajax_view.partials.modal_receipt_list')
                        @endif
                    </div>
                    <div class="col-md-4">
                        <div class="table-responsive">
                            <table class="table modal-table table-sm">
                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.rate_type') : </th>
                                    <td class="text-end" style="font-size:11px!important;">{{ $sale->all_price_type }}{{ $sale->all_price_type == 'PR' ? '('.App\Utils\Converter::format_in_bdt($totalPrAmount).')' : '' }}</td>
                                </tr>

                                @if ($sale->delivery_order_id)
                                    <tr>
                                        <th class="text-end" style="font-size:11px!important;">@lang('menu.total') @lang('menu.qty_weight') : </th>
                                        <td class="text-end" style="font-size:11px!important;">
                                            <span class="net_total">
                                                {{ App\Utils\Converter::format_in_bdt($sale->total_sold_qty) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endif

                                <tr>
                                    <th class="text-end" style="font-size:11px!important;">@lang('menu.net_total_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end" style="font-size:11px!important;">
                                        <span class="net_total">
                                            {{ App\Utils\Converter::format_in_bdt($sale->net_total_amount) }}
                                        </span>
                                    </td>
                                </tr>

                                @if (!$sale->delivery_order_id)
                                    <tr>
                                        <th class="text-end" style="font-size:11px!important;"> @lang('menu.sale_discount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                        <td class="text-end" style="font-size:11px!important;">
                                            @if ($sale->order_discount_type == 1)

                                                (@lang('menu.fixed')) {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }}
                                            @else

                                                {{ App\Utils\Converter::format_in_bdt($sale->order_discount_amount) }} ({{ $sale->order_discount }}%)
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="text-end" style="font-size:11px!important;">@lang('menu.sale_tax') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                        <td class="text-end" style="font-size:11px!important;">
                                            {{ '(' . $sale->order_tax_percent . '%) ' . App\Utils\Converter::format_in_bdt($sale->order_tax_amount) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="text-end" style="font-size:11px!important;">@lang('menu.shipment_charge')  : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                        <td class="text-end" style="font-size:11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($sale->shipment_charge) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="text-end" style="font-size:11px!important;">@lang('menu.total_invoice_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                        <td class="text-end" style="font-size:11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($sale->total_payable_amount) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="text-end" style="font-size:11px!important;">@lang('menu.sale_return') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                        <td class="text-end" style="font-size:11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($sale->sale_return_amount) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="text-end" style="font-size:11px!important;">@lang('menu.received_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                        <td class="text-end" style="font-size:11px!important;">
                                            {{ App\Utils\Converter::format_in_bdt($sale->paid) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="text-end" style="font-size:11px!important;">@lang('menu.due') @lang('menu.on_invoice') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                        <td class="text-end" style="font-size:11px!important;">
                                            @if ($sale->due < 0)

                                                ({{ App\Utils\Converter::format_in_bdt(abs($sale->due)) }})
                                            @else

                                                {{ App\Utils\Converter::format_in_bdt(abs($sale->due)) }}
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="text-end" style="font-size:11px!important;">@lang('menu.current_balance') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                        <td class="text-end fw-bold" style="font-size:11px!important;">
                                            @php
                                             $accountUtil = new App\Utils\AccountUtil();
                                             $amounts = $accountUtil->accountClosingBalance($sale->customer_account_id, $sale->sr_user_id);
                                            @endphp
                                            {{ $amounts['closing_balance_string'] }}
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="details_area">
                            <p style="font-size:11px!important;"><strong>@lang('menu.shipping_address')</strong> : </p>
                            <p style="font-size:11px!important;">{{ $sale->shipping_address ? $sale->shipping_address : 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="details_area">
                            <p style="font-size:11px!important;"><strong>@lang('menu.sale_note')</strong> : </p>
                            <p style="font-size:11px!important;">{{ $sale->sale_note ? $sale->sale_note : 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="details_area">
                            <p style="font-size:11px!important;"><strong>@lang('menu.payment_note')</strong> : </p>
                            <p style="font-size:11px!important;">{{ $sale->payment_note ? $sale->payment_note : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                @if ($sale->status == 3)

                    @if (auth()->user()->can('sale_order_do_approval'))

                        <a href="{{ route('sales.order.do.approval', $sale->id) }}" class="btn btn-sm btn-success action_hideable m-0 me-2" id="do_approval">@lang('menu.do_approval')</a>
                    @endif
                @endif

                @if ($sale->created_by == 1)

                    @if (auth()->user()->can('edit_sale'))

                        <a class="btn btn-sm btn-secondary m-0 me-2" href="{{ route('sales.edit', $sale->id) }}">@lang('menu.edit')</a>
                    @endif
                @else

                    @if (auth()->user()->can('pos_edit'))

                        <a class="footer_btn btn btn-sm btn-secondary m-0 me-2" href="{{ route('sales.pos.edit', $sale->id) }}">@lang('menu.edit')</a>
                    @endif
                @endif

                @if ($sale->status == 1)

                    <a href="{{ route('sales.print.sales.gate.pass', $sale->id) }}" class="footer_btn btn btn-sm btn-primary action_hideable m-0 me-2" id="print_gate_pass">@lang('menu.print_gate_pass')</a>

                    @if ($sale->delivery_order_id)

                        <a href="{{ route('sales.print.sales.weight', $sale->id) }}" class="footer_btn btn btn-sm btn-info action_hideable m-0 me-2" id="print_weight">@lang('menu.weight')</a>
                    @endif

                    <button type="button" class="footer_btn btn btn-sm btn-primary action_hideable m-0 me-2" id="print_challan_btn">@lang('menu.print_challan')</button>
                @endif

                <button type="button" class="footer_btn btn btn-sm btn-primary m-0 me-2" id="print_modal_details_btn">@lang('menu.print_invoice')</button>

                <button type="button" class="btn btn-sm btn-danger m-0" data-bs-dismiss="modal">@lang('menu.close')</button>
            </div>
        </div>
    </div>
</div>
<!-- Details Modal End-->

<!-- Sale print templete-->
@include('sales_app.sales.ajax_view.partials.add_sale_default_lnvoice_layout')
<!-- Sale print templete end-->

<!-- Challan print templete-->
@include('sales_app.sales.ajax_view.partials.add_sale_default_challan_layout')
<!-- Challan print templete end-->

