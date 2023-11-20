@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style>
        .input-group-text {
            font-size: 12px !important;
        }

        .select_area {
            position: relative;
            background: #ffffff;
            box-sizing: border-box;
            position: absolute;
            width: 100%;
            z-index: 9999999;
            padding: 0;
            display: none;
            border: 1px solid var(--main-color);
            margin-top: 1px;
            border-radius: 0px;
        }

        .select_area ul {
            list-style: none;
            margin-bottom: 0;
            padding: 4px 4px;
        }

        .select_area ul li a {
            color: #000000;
            text-decoration: none;
            font-size: 11px;
            padding: 4px 3px;
            display: block;
        }

        .select_area ul li a:hover {
            background-color: #999396;
            color: #fff;
        }

        .selectProduct {
            background-color: #746e70;
            color: #fff !important;
        }

        b {
            font-weight: 500;
            font-family: Arial, Helvetica, sans-serif;
        }

        h6.collapse_table:hover {
            background: lightgray;
            padding: 3px;
            cursor: pointer;
        }

        .c-delete:focus {
            border: 1px solid gray;
            padding: 2px;
        }

        .selected_requisition {
            background-color: #645f61;
            color: #fff !important;
        }

        .invoice_search_result {
            position: absolute;
            width: 100%;
            border: 1px solid #E4E6EF;
            background: white;
            z-index: 1;
            padding: 3px;
            margin-top: 1px;
        }

        .invoice_search_result ul li {
            width: 100%;
            border: 1px solid lightgray;
            margin-top: 2px;
        }

        .invoice_search_result ul li a {
            color: #6b6262;
            font-size: 10px;
            display: block;
            padding: 0px 3px;
        }

        .invoice_search_result ul li a:hover {
            color: var(--white-color);
            background-color: #ada9a9;
        }

        .element-body {
            overflow: initial !important;
        }

        .sale-item-sec {
            min-height: 240px !important;
        }
    </style>
@endpush
@section('title', 'Edit Purchase Order - ')
@section('content')
    <div class="body-wraper">
        <div class="container-fluid p-0">
            <div class="sec-name">
                <h6>@lang('menu.edit') @lang('menu.purchase_order')</h6>
                <x-back-button />
            </div>

            <div class="p-15">
                <form id="edit_order_form" action="{{ route('purchases.order.update', $order->id) }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <input type="hidden" name="purchase_status" value="3">
                    <section>
                        <div class="form_element rounded mt-0 mb-1">
                            <div class="element-body">
                                <div class="row">
                                    <div class="col-xl-4 col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>@lang('menu.supplier')</b> <span class="text-danger">*</span></label>
                                            <div class="col-8">
                                                <div class="input-group select-customer-input-group">
                                                    <select required name="supplier_account_id" class="form-control select2 form-select" id="supplier_account_id" data-next="requisition_no">
                                                        <option value="">@lang('menu.select_supplier')</option>
                                                        @foreach ($supplierAccounts as $supplierAccount)
                                                            <option {{ $supplierAccount->id == $order->supplier_account_id ? 'SELECTED' : '' }} value="{{ $supplierAccount->id }}">
                                                                {{ $supplierAccount->name . '/' . $supplierAccount->phone }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    <div style="display:inline-block;margin-top:0px;" class="style-btn">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text add_button mr-1 {{ !auth()->user()->can('supplier_add')? 'disabled_element': '' }}" id="addSupplier"><i class="fas fa-plus-square text-dark"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="error error_supplier_account_id"></span>
                                            </div>
                                        </div>

                                        <div class="input-group mt-1">
                                            @php
                                                $accountUtil = new App\Utils\AccountUtil();
                                                $amounts = $accountUtil->accountClosingBalance($order->supplier_account_id);
                                            @endphp
                                            <label class="col-4 text-danger"><b>@lang('menu.closing_bal') </b></label>
                                            <div class="col-8">
                                                <input readonly type="text" class="form-control fw-bold" id="closing_balance" value="{{ $amounts['closing_balance_string'] }}" placeholder="0.00" tabindex="-1">
                                                <input readonly type="hidden" id="debit_amount" value="{{ $amounts['curr_total_debit'] }}">
                                                <input readonly type="hidden" id="credit_amount" value="{{ $amounts['curr_total_credit'] }}">
                                                <input readonly type="hidden" id="current_supplier_account_id" value="{{ $order->supplier_account_id }}">
                                                <input readonly type="hidden" id="current_invoice_amount" value="{{ $order->total_purchase_amount }}">
                                            </div>
                                        </div>

                                        <div class="input-group mt-1">
                                            <label class="col-4"><b>@lang('menu.requisition_no')</b> </label>
                                            <div class="col-8">
                                                <input type="text" name="requisition_no" id="requisition_no" class="form-control fw-bold" data-next="warehouse_id" value="{{ $order->requisition ? $order->requisition->requisition_no : '' }}" placeholder="Search Purchase Requisitons" autocomplete="off">
                                                <div class="invoice_search_result display-none">
                                                    <ul id="requisition_list" class="list-unstyled">

                                                    </ul>
                                                </div>
                                                <input type="hidden" name="requisition_id" id="requisition_id" value="{{ $order->requistion_id }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>@lang('menu.po_id') </b> </label>
                                            <div class="col-8">
                                                <input readonly type="text" name="invoice_id" id="invoice_id" class="form-control fw-bold" value="{{ $order->invoice_id }}" placeholder="Purchase Order ID" autocomplete="off">
                                                <span class="error error_invoice_id"></span>
                                            </div>
                                        </div>

                                        @if (count($warehouses) > 0)

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('menu.warehouse') </b></label>
                                                <div class="col-8">
                                                    <select class="form-control changeable form-select" name="warehouse_id" id="warehouse_id" data-next="purchase_account_id">
                                                        <option value="">@lang('menu.select_warehouse')</option>
                                                        @foreach ($warehouses as $w)
                                                            <option {{ $order->warehouse_id == $w->id ? 'SELECTED' : '' }} value="{{ $w->id }}">
                                                                {{ $w->warehouse_name . '/' . $w->warehouse_code }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_warehouse_id"></span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('menu.store_location') </b> </label>
                                                <div class="col-8">
                                                    <input readonly type="text" class="form-control changeable" value="{{ json_decode($generalSettings->business, true)['shop_name'] }}" />
                                                </div>
                                            </div>
                                        @endif

                                        <div class="input-group mt-1">
                                            <label class="col-4"><b>@lang('short.purchase_a_c')<span class="text-danger">*</span></b></label>
                                            <div class="col-8">
                                                <select name="purchase_account_id" class="form-control select2 form-select" id="purchase_account_id" data-next="date">
                                                    @foreach ($purchaseAccounts as $purchaseAccount)
                                                        <option {{ $purchaseAccount->id == $order->purchase_account_id ? 'SELECTED' : '' }} value="{{ $purchaseAccount->id }}">
                                                            {{ $purchaseAccount->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="error error_purchase_account_id"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>@lang('menu.date')</b></label>
                                            <div class="col-8">
                                                <input type="text" name="date" class="form-control changeable" id="date" value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($order->date)) }}" data-next="delivery_date" placeholder="dd-mm-yyyy" autocomplete="off">
                                                <span class="error error_date"></span>
                                            </div>
                                        </div>

                                        <div class="input-group mt-1">
                                            <label class="col-4"><b>@lang('menu.delivery_date') </b></label>
                                            <div class="col-8">
                                                <input type="text" name="delivery_date" class="form-control changeable" id="delivery_date" data-next="search_product" placeholder="DD-MM-YYYY" value="{{ $order->delivery_date ? date(json_decode($generalSettings->business, true)['date_format'], strtotime($order->delivery_date)) : '' }}" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section>
                        <div class="item-details-sec rounded mb-1">
                            <div class="content-inner">
                                <div class="row align-items-end">
                                    <div class="col-xl-4">
                                        <div class="searching_area" style="position: relative;">
                                            <label class="fw-bold">@lang('menu.search_item')</label>
                                            <div class="input-group ">
                                                <input type="text" name="search_product" class="form-control fw-bold" autocomplete="off" id="search_product" onkeyup="event.preventDefault();" placeholder="@lang('menu.search_item_item_code_scan_bar_code')">
                                                @if (auth()->user()->can('product_add'))
                                                    <div class="input-group-prepend">
                                                        <span id="add_product" class="input-group-text add_button"><i class="fas fa-plus-square text-dark input_f"></i></span>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="select_area">
                                                <ul id="list" class="variant_list_area"></ul>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" id="e_unique_id">
                                    <input type="hidden" id="e_item_name">
                                    <input type="hidden" id="e_product_id">
                                    <input type="hidden" id="e_variant_id">
                                    <input type="hidden" id="e_tax_amount">
                                    <input type="hidden" id="e_showing_tax_amount">
                                    <input type="hidden" id="e_unit_cost_with_discount">
                                    <input type="hidden" id="e_showing_unit_cost_with_discount">
                                    <input type="hidden" id="e_subtotal">
                                    <input type="hidden" id="e_unit_cost_inc_tax">
                                    <input type="hidden" id="e_showing_unit_cost_inc_tax">
                                    <input type="hidden" id="e_base_unit_cost_exc_tax">

                                    <div class="col-xl-2 col-md-4 mt-1">
                                        <label class="fw-bold">@lang('menu.quantity')</label>
                                        <div class="input-group">
                                            <input type="number" step="any" class="form-control w-60 fw-bold" id="e_showing_quantity" value="0.00" placeholder="0.00" autocomplete="off">
                                            <input type="hidden" step="any" id="e_quantity" value="0.00">
                                            <select id="e_unit_id" class="form-control w-40 form-select">
                                                <option value="">@lang('menu.unit')</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4 mt-1">
                                        <label class="fw-bold">@lang('menu.unit_cost_exc_tax')</label>
                                        <input type="number" step="any" class="form-control fw-bold" id="e_showing_unit_cost_exc_tax" value="0.00" placeholder="0.00" autocomplete="off">
                                        <input type="hidden" id="e_unit_cost_exc_tax" value="0.00">
                                    </div>

                                    <div class="col-xl-2 col-md-4 mt-1">
                                        <label class="fw-bold">@lang('menu.discount')</label>
                                        <div class="input-group">
                                            <input type="number" step="any" class="form-control w-60 fw-bold" id="e_showing_discount" value="0.00" placeholder="0.00" autocomplete="off">
                                            <input type="hidden" id="e_discount" value="0.00">

                                            <select id="e_discount_type" class="form-control w-40 form-select">
                                                <option value="1">@lang('menu.fixed')(0.00)</option>
                                                <option value="2">@lang('menu.percentage')(%)</option>
                                            </select>
                                            <input type="hidden" id="e_showing_discount_amount">
                                            <input type="hidden" id="e_discount_amount">
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4 mt-1">
                                        <label class="fw-bold">@lang('menu.tax')</label>
                                        <div class="input-group">
                                            <select id="e_tax_ac_id" class="form-control w-50 form-select">
                                                <option data-product_tax_percent="0.00" value="">@lang('menu.no_tax')
                                                </option>
                                                @foreach ($taxAccounts as $taxAccount)
                                                    <option data-product_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                        {{ $taxAccount->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <select id="e_tax_type" class="form-control w-50 form-select">
                                                <option value="1">@lang('menu.exclusive')</option>
                                                <option value="2">@lang('menu.inclusive')</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4 mt-1">
                                        <label class="fw-bold">@lang('menu.line_total')</label>
                                        <input readonly type="number" step="any" class="form-control fw-bold" id="e_linetotal" value="0.00" placeholder="0.00" tabindex="-1">
                                    </div>

                                    <div class="col-xl-2 col-md-4 mt-1">
                                        <label class="fw-bold">@lang('menu.lot_number')</label>
                                        <input type="text" step="any" class="form-control fw-bold" id="e_lot_number" placeholder="@lang('menu.lot_number')" autocomplete="off">
                                    </div>

                                    <div class="col-xl-2 col-md-4 mt-1">
                                        <label class="fw-bold">@lang('menu.short_description')</label>
                                        <input type="text" step="any" class="form-control fw-bold" id="e_description" placeholder="@lang('menu.short_description')" autocomplete="off">
                                    </div>

                                    @if (json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1')
                                        <div class="col-xl-2 col-md-4 mt-1">
                                            <label class="fw-bold">@lang('menu.profit_margin')</label>
                                            <input type="number" step="any" class="form-control fw-bold" id="e_profit_margin" value="0.00" placeholder="0.00" autocomplete="off">
                                        </div>

                                        <div class="col-xl-2 col-md-4 mt-1">
                                            <label class="fw-bold">@lang('menu.selling_price_exc_tax')</label>
                                            <input type="number" step="any" class="form-control fw-bold" id="e_showing_selling_price" value="0.00" placeholder="0.00" autocomplete="off">
                                            <input type="hidden" id="e_selling_price" value="0.00">
                                        </div>
                                    @endif

                                    <div class="col-xl-2 col-md-4">
                                        <a href="#" class="btn btn-sm btn-success" id="add_item">@lang('menu.add')</a>
                                        <input type="reset" class="btn btn-sm btn-danger" value="@lang('menu.reset')">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="sale-item-sec">
                                        <div class="sale-item-inner mt-2">
                                            <div class="table-responsive">
                                                <table class="display data__table table-striped">
                                                    <thead class="staky">
                                                        <tr>
                                                            <th>@lang('menu.item')</th>
                                                            <th>@lang('menu.quantity')</th>
                                                            <th>@lang('menu.unit_cost')</th>
                                                            <th>@lang('menu.discount')</th>
                                                            <th>@lang('menu.unit_tax')</th>
                                                            <th>@lang('menu.net') @lang('menu.unit_cost')</th>
                                                            <th>@lang('menu.line_total')</th>
                                                            @if (json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1')
                                                                <th>@lang('short.x_margin')(%)</th>
                                                                <th>@lang('menu.selling_price_exc_tax')</th>
                                                            @endif
                                                            <th><i class="fas fa-trash-alt"></i></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="purchase_list">
                                                        @php
                                                            $itemUnitsArray = [];
                                                        @endphp
                                                        @foreach ($order->orderedProducts as $orderProduct)
                                                            @php
                                                                if (isset($orderProduct->product_id)) {
                                                                    $itemUnitsArray[$orderProduct->product_id][] = [
                                                                        'unit_id' => $orderProduct->product->unit->id,
                                                                        'unit_name' => $orderProduct->product->unit->name,
                                                                        'unit_code_name' => $orderProduct->product->unit->code_name,
                                                                        'base_unit_multiplier' => 1,
                                                                        'multiplier_details' => '',
                                                                        'is_base_unit' => 1,
                                                                    ];
                                                                }

                                                                if (count($orderProduct?->product?->unit?->childUnits) > 0) {
                                                                    foreach ($orderProduct?->product?->unit?->childUnits as $unit) {
                                                                        $multiplierDetails = '(1 ' . $unit->name . ' = ' . $unit->base_unit_multiplier . '/' . $orderProduct?->product?->unit?->name . ')';

                                                                        array_push($itemUnitsArray[$orderProduct->product_id], [
                                                                            'unit_id' => $unit->id,
                                                                            'unit_name' => $unit->name,
                                                                            'unit_code_name' => $unit->code_name,
                                                                            'base_unit_multiplier' => $unit->base_unit_multiplier,
                                                                            'multiplier_details' => $multiplierDetails,
                                                                            'is_base_unit' => 0,
                                                                        ]);
                                                                    }
                                                                }

                                                                $variant = $orderProduct->variant ? ' - ' . $orderProduct->variant->variant_name : '';
                                                                $variantId = $orderProduct->product_variant_id ? $orderProduct->product_variant_id : 'noid';
                                                            @endphp

                                                            <tr id="select_item">
                                                                <td>
                                                                    <span id="span_item_name">{{ $orderProduct->product->name . $variant }}</span>

                                                                    <input type="hidden" id="item_name" value="{{ $orderProduct->product->name . $variant }}">
                                                                    <input type="hidden" name="descriptions[]" id="description" value="{{ $orderProduct->description }}">
                                                                    <input type="hidden" name="product_ids[]" id="product_id" value="{{ $orderProduct->product_id }}">
                                                                    <input type="hidden" name="variant_ids[]" id="variant_id" value="{{ $variantId }}">
                                                                    <input type="hidden" name="purchase_product_ids[]" value="{{ $orderProduct->id }}">
                                                                    <input type="hidden" id="{{ $orderProduct->product_id . $variantId }}" value="{{ $orderProduct->product_id . $variantId }}">
                                                                </td>

                                                                <td>
                                                                    @php
                                                                        $baseUnitMultiplier = $orderProduct?->orderUnit?->base_unit_multiplier ? $orderProduct?->orderUnit?->base_unit_multiplier : 1;
                                                                        $orderdQty = $orderProduct->order_quantity / $baseUnitMultiplier;
                                                                    @endphp
                                                                    <span id="span_showing_quantity_unit" class="fw-bold">{{ bcadd($orderdQty, 0, 2) . '/' . $orderProduct?->orderUnit?->name }}</span>
                                                                    <input type="hidden" step="any" id="showing_quantity" value="{{ bcadd($orderdQty, 0, 2) }}">
                                                                    <input type="hidden" name="quantities[]" id="quantity" value="{{ $orderProduct->order_quantity }}">
                                                                    <input type="hidden" name="unit_ids[]" step="any" id="unit_id" value="{{ $orderProduct?->unit_id }}">
                                                                    @if (json_decode($generalSettings->purchase, true)['is_enable_lot_no'] == '1')
                                                                        <p class="p-0 m-0 fw-bold">@lang('menu.lot_no') :
                                                                            <span id="span_lot_number">{{ $orderProduct?->lot_number }}</span>
                                                                            <input type="hidden" name="lot_numbers[]" id="lot_number" value="{{ $orderProduct?->lot_number }}">
                                                                    @endif
                                                                </td>

                                                                <td>
                                                                    <span id="span_showing_unit_cost_exc_tax" class="fw-bold">{{ bcadd($orderProduct->unit_cost * $baseUnitMultiplier, 0, 2) }}</span>
                                                                    <input type="hidden" name="unit_costs_exc_tax[]" id="unit_cost_exc_tax" value="{{ $orderProduct->unit_cost }}">
                                                                    <input type="hidden" id="showing_unit_cost_exc_tax" value="{{ bcadd($orderProduct->unit_cost * $baseUnitMultiplier, 0, 2) }}">
                                                                </td>

                                                                <td>
                                                                    @php
                                                                        $showingUnitDiscount = $orderProduct->unit_discount_type == 1 ? $orderProduct->unit_discount * $baseUnitMultiplier : $orderProduct->unit_discount;
                                                                    @endphp
                                                                    <span id="span_showing_discount_amount" class="fw-bold">{{ bcadd($showingUnitDiscount, 0, 2) }}</span>
                                                                    <input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="{{ $orderProduct->unit_discount_type }}">
                                                                    <input type="hidden" name="unit_discounts[]" id="unit_discount" value="{{ $orderProduct->unit_discount }}">
                                                                    <input type="hidden" id="showing_unit_discount" value="{{ bcadd($orderProduct->unit_discount * $baseUnitMultiplier, 0, 2) }}">
                                                                    <input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="{{ $orderProduct->unit_discount_amount }}">
                                                                    <input type="hidden" id="showing_unit_discount_amount" value="{{ bcadd($orderProduct->unit_discount_amount * $baseUnitMultiplier, 0, 2) }}">

                                                                    <input type="hidden" name="unit_costs_with_discount[]" id="unit_cost_with_discount" value="{{ $orderProduct->unit_cost_with_discount }}">
                                                                    <input type="hidden" id="showing_unit_cost_with_discount" value="{{ bcadd($orderProduct->unit_cost_with_discount * $baseUnitMultiplier, 0, 2) }}">
                                                                    <input type="hidden" name="subtotals[]" id="subtotal" value="{{ $orderProduct->subtotal }}">
                                                                </td>

                                                                <td>
                                                                    <span id="span_tax_percent" class="fw-bold">{{ $orderProduct->unit_tax_percent . '%' }}</span>
                                                                    <input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="{{ $orderProduct->tax_ac_id }}">
                                                                    <input type="hidden" name="tax_types[]" id="tax_type" value="{{ $orderProduct->tax_type }}">
                                                                    <input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="{{ $orderProduct->unit_tax_percent }}">
                                                                    <input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="{{ $orderProduct->unit_tax_amount }}">
                                                                    <input type="hidden" id="showing_unit_tax_amount" value="{{ bcadd($orderProduct->unit_tax_amount * $baseUnitMultiplier, 0, 2) }}">
                                                                </td>

                                                                <td>
                                                                    <span id="span_showing_unit_cost_inc_tax" class="fw-bold">{{ bcadd($orderProduct->net_unit_cost * $baseUnitMultiplier, 0, 2) }}</span>
                                                                    <input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="{{ bcadd($orderProduct->net_unit_cost, 0, 2) }}">
                                                                    <input type="hidden" id="showing_unit_cost_inc_tax" value="{{ bcadd($orderProduct->net_unit_cost * $baseUnitMultiplier, 0, 2) }}">
                                                                    <input type="hidden" name="net_unit_costs[]" id="net_unit_cost" value="{{ bcadd($orderProduct->net_unit_cost, 0, 2) }}">
                                                                </td>

                                                                <td>
                                                                    <span id="span_linetotal" class="fw-bold">{{ bcadd($orderProduct->line_total, 0, 2) }}</span>
                                                                    <input type="hidden" name="linetotals[]" id="linetotal" value="{{ bcadd($orderProduct->line_total, 0, 2) }}">
                                                                </td>

                                                                @if (json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1')
                                                                    <td>
                                                                        <span id="span_profit" class="fw-bold">{{ $orderProduct->profit_margin }}</span>
                                                                        <input type="hidden" name="profits[]" id="profit" value="{{ $orderProduct->profit_margin }}">
                                                                    </td>

                                                                    <td>
                                                                        <span id="span_showing_selling_price" class="fw-bold">{{ bcadd($orderProduct->selling_price * $baseUnitMultiplier, 0, 2) }}</span>
                                                                        <input type="hidden" name="selling_prices[]" id="selling_price" value="{{ bcadd($orderProduct->selling_price, 0, 2) }}">
                                                                        <input type="hidden" id="showing_selling_price" value="{{ bcadd($orderProduct->selling_price * $baseUnitMultiplier, 0, 2) }}">
                                                                    </td>
                                                                @endif

                                                                <td>
                                                                    <a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section>
                        <div class="row g-1">
                            <div class="col-md-6">
                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row g-1">
                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <label class="col-4 text-end pe-2"><b>@lang('menu.total_quantity')</b>
                                                            </label>
                                                            <div class="col-8">
                                                                <input type="hidden" name="total_item" id="total_item" value="{{ $order->total_item }}">
                                                                <input readonly name="total_qty" type="number" step="any" class="form-control fw-bold" id="total_qty" value="{{ $order->po_qty }}" tabindex="-1">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <label class="col-4 text-end pe-2"><b>@lang('menu.net_total_amount')</b>
                                                            </label>
                                                            <div class="col-8">
                                                                <input readonly name="net_total_amount" type="number" step="any" id="net_total_amount" class="form-control fw-bold" value="{{ $order->net_total_amount }}" tabindex="-1">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <label class="col-4 text-end pe-2"><b>@lang('menu.order_discount')</b></label>
                                                            <div class="col-8">
                                                                <div class="input-group">
                                                                    <input name="order_discount" type="number" class="form-control fw-bold" id="order_discount" value="{{ $order->order_discount }}" data-next="order_discount_type">
                                                                    <input name="order_discount_amount" type="number" step="any" class="d-none" id="order_discount_amount" value="{{ $order->order_discount_amount }}" tabindex="-1">
                                                                    <select name="order_discount_type" class="form-control form-select" id="order_discount_type" data-next="purchase_tax_ac_id">
                                                                        <option {{ $order->order_discount_type == 1 ? 'SELECTED' : '' }} value="1">@lang('menu.fixed')(0.00)
                                                                        </option>
                                                                        <option {{ $order->order_discount_type == 2 ? 'SELECTED' : '' }} value="2">@lang('menu.percentage')(%)</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <label class="col-4"><b>@lang('menu.order_tax') </b></label>
                                                            <div class="col-8">
                                                                <select name="purchase_tax_ac_id" class="form-control form-select" id="purchase_tax_ac_id" data-next="shipment_charge">
                                                                    <option data-purchase_tax_percent="0.00" value="">@lang('menu.no_tax')</option>
                                                                    @foreach ($taxAccounts as $taxAccount)
                                                                        <option {{ $taxAccount->id == $order->tax_ac_id ? 'SELECTED' : '' }} data-purchase_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                                            {{ $taxAccount->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>

                                                                <input type="number" step="any" name="purchase_tax_percent" class="d-none" id="purchase_tax_percent" value="{{ $order->purchase_tax_percent }}">
                                                                <input type="number" step="any" name="purchase_tax_amount" class="d-none" id="purchase_tax_amount" value="{{ $order->purchase_tax_amount }}" tabindex="-1">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <label class="col-4 text-end pe-2"><b>@lang('menu.shipment_cost')</b></label>
                                                            <div class="col-8">
                                                                <input name="shipment_charge" type="number" class="form-control fw-bold" id="shipment_charge" value="{{ $order->shipment_charge }}" data-next="shipment_details">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <label class="col-4 text-end pe-2"><b>@lang('menu.shipment_details')</b></label>
                                                            <div class="col-8">
                                                                <input name="shipment_details" type="text" class="form-control" id="shipment_details" value="{{ $order->shipment_details }}" data-next="purchase_note" placeholder="@lang('menu.shipment_details')">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <label class="col-4 text-end pe-2"><b>@lang('menu.order_note')</b></label>
                                                            <div class="col-8">
                                                                <input type="text" name="purchase_note" id="purchase_note" class="form-control" value="{{ $order->note }}" data-next="paying_amount" placeholder="@lang('menu.order_note').">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row g-1">
                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <label class="col-4 text-end pe-2"><b>@lang('menu.total_ordered_amount')</b></label>
                                                            <div class="col-8">
                                                                <input readonly type="number" step="any" name="total_invoice_amount" id="total_invoice_amount" class="form-control fw-bold" value="{{ $order->total_purchase_amount }}" tabindex="-1">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <label class="col-4 text-end pe-2 fw-bold"><b>@lang('menu.paying_amount')</b>
                                                                <strong>>></strong></label>
                                                            <div class="col-8">
                                                                <input {{ !auth()->user()->can('payments_add')? 'readonly': '' }} type="number" step="any" name="paying_amount" class="form-control fw-bold" id="paying_amount" value="0.00" data-next="payment_method_id" autocomplete="off">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <label class="col-4 text-end pe-2"><span class="text-danger">*</span> <b>@lang('menu.payment_method') </b>
                                                            </label>
                                                            <div class="col-8">
                                                                <select required name="payment_method_id" class="form-control form-select" id="payment_method_id" data-next="account_id">
                                                                    @foreach ($methods as $method)
                                                                        <option data-account_id="{{ $method->methodAccount ? $method->methodAccount->account_id : '' }}" value="{{ $method->id }}">
                                                                            {{ $method->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="error error_payment_method_id"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <label class="col-4 text-end pe-2"><span class="text-danger">*</span> <b>@lang('menu.credit_account') </b>
                                                            </label>
                                                            <div class="col-8">
                                                                <select name="account_id" class="form-control select2 form-select" id="account_id" data-next="transaction_no">
                                                                    <option value="">Select Credit A/c</option>
                                                                    @foreach ($accounts as $account)
                                                                        <option value="{{ $account->id }}">
                                                                            @php
                                                                                $bank = $account->bank ? ', Bank: ' . $account->bank : '';
                                                                                $ac_no = $account->account_number ? ', A/c No: ' . '..' . substr($account->account_number, -4) : '';
                                                                            @endphp
                                                                            {{ $account->name . $bank . $ac_no }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="error error_account_id"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <label class="col-4"><strong>@lang('menu.transaction_no')</strong></label>
                                                            <div class="col-8">
                                                                <input type="text" step="any" name="transaction_no" class="form-control" id="transaction_no" data-next="cheque_no" placeholder="@lang('menu.transaction_no')" autocomplete="off">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <label class="col-4"><strong>@lang('menu.cheque_no')</strong></label>
                                                            <div class="col-8">
                                                                <input type="text" step="any" name="cheque_no" class="form-control" id="cheque_no" data-next="payment_note" placeholder="Cheque Number" autocomplete="off">
                                                                <input type="hidden" id="current_balance" value="0.00">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <label class="col-4 text-end pe-2"><b>@lang('menu.payment_note') </b>
                                                            </label>
                                                            <div class="col-8">
                                                                <input type="text" name="payment_note" class="form-control" id="payment_note" data-next="save" placeholder="@lang('menu.payment_note')" autocomplete="off">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="submitBtn p-15 pt-0">
                        <div class="row justify-content-center">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="loading-btn-box">
                                    <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                                    <button id="save" class="btn btn-success submit_button">@lang('menu.save_change')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-shortcut-key-bar.shortcut-key-bar :items="[['key' => 'Ctrl + Enter', 'value' => __('menu.save_changes')], ['key' => 'Alt + S', 'value' => __('menu.add_supplier')], ['key' => 'Alt + I', 'value' => __('menu.add_item')]]">
    </x-shortcut-key-bar.shortcut-key-bar>

    @if (auth()->user()->can('supplier_add'))
        <!-- Add Supplier Modal -->
        <div class="modal fade" id="add_supplier_basic_modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
        <div class="modal fade" id="add_supplier_detailed_modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
        <!-- Add Supplier Modal End -->
    @endif

    @if (auth()->user()->can('product_add'))
        <!--Add Quick Product Modal-->
        <div class="modal fade" id="addQuickProductModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog four-col-modal" role="document" id="quick_product_add_modal_contant"></div>
        </div>
        <!--Add Quick Product Modal End-->

        <div class="modal fade" id="unitAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
        <div class="modal fade" id="brandAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
        <div class="modal fade" id="warrantyAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
        <div class="modal fade" id="categoryAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
        <div class="modal fade" id="subcategoryAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
    @endif

@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @include('procurement.partials.purchaseEditJsScript')
    <script>
        $('.select2').select2();

        var itemUnitsArray = @json($itemUnitsArray);

        var ul = document.getElementById('list');
        var selectObjClassName = 'selectProduct';
        $('#requisition_no').mousedown(function(e) {

            afterClickOrFocusRequisitionNo();
        }).focus(function(e) {

            // afterClickOrFocusRequisitionNo();
        });

        function afterClickOrFocusRequisitionNo() {

            ul = document.getElementById('requisition_list')
            selectObjClassName = 'selected_requisition';
            $('#requisition_no').val('');
            $('#requisition_id').val('');
            $('#search_product').prop('disabled', false);
            $('#purchase_list').empty();
            calculateTotalAmount();
        }

        function afterFocusSearchItemField() {

            ul = document.getElementById('list')
            selectObjClassName = 'selectProduct';
        }

        $('#search_product').focus(function(e) {

            afterFocusSearchItemField();
        });

        $('#requisition_no').on('input', function() {

            $('.invoice_search_result').hide();

            var requisition_no = $(this).val();

            if (requisition_no === '') {

                $('.invoice_search_result').hide();
                $('#requisition_id').val('');
                $('#search_product').prop('disabled', false);
                return;
            }

            var url = "{{ route('common.ajax.call.search.requisitions', ':requisition_no') }}";
            var route = url.replace(':requisition_no', requisition_no);

            $.ajax({
                url: route,
                async: true,
                type: 'get',
                success: function(data) {

                    if (!$.isEmptyObject(data.noResult)) {

                        $('.invoice_search_result').hide();
                    } else {

                        $('.invoice_search_result').show();
                        $('#requisition_list').html(data);
                    }
                }
            });
        });

        // Work will be running from here...
        $(document).on('click', '#selected_requisition', function(e) {
            e.preventDefault();

            if ($(this).data('is_approved') == 0) {

                toastr.error('The Requisition not yet to be appreved');
                $('#requisition_no').focus().select();
                return;
            }

            var requisition_no = $(this).html();
            var requisition_id = $(this).data('id');

            var url = "{{ route('common.ajax.call.get.requisition.products.for.purchase', ':requisition_id') }}";
            var route = url.replace(':requisition_id', requisition_id)
            $.ajax({
                url: route,
                async: true,
                type: 'get',
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        $('#requisition_no').focus().select();
                        return;
                    }

                    itemUnitsArray = jQuery.parseJSON(data.units);

                    $('#requisition_no').val(requisition_no.trim());
                    $('#requisition_id').val(requisition_id);
                    $('#purchase_list').html(data.view);

                    $('#search_product').prop('disabled', true);
                    $('#requisition_list').empty();

                    $('.invoice_search_result').hide();

                    calculateTotalAmount();
                }
            });
        });

        $(document).on('click keypress focus blur change', '.form-control', function(event) {

            $('.submit_button').prop('type', 'button');
        });

        $('.submit_button').on('click', function() {

            $(this).prop('type', 'submit');
        });

        document.onkeyup = function() {

            var e = e || window.event; // for IE to cover IEs window event-object
            if (e.ctrlKey && e.which == 13) {

                $('#save').click();
                return false;
            } else if (e.altKey && e.which == 83) {

                $('#addSupplier').click();
                return false;
            } else if (e.altKey && e.which == 73) {

                $('#add_product').click();
                return false;
            } else if (e.which == 27) {

                $('.select_area').hide();
                $('.invoice_search_result').hide();

                $('#requisition_list').empty();
                $('#list').empty();
                return false;
            }
        }

        //Add purchase request by ajax
        $('#edit_order_form').on('submit', function(e) {
            e.preventDefault();

            $('.loading_button').show();
            var url = $(this).attr('action');

            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg, 'ERROR');
                        $('.loading_button').hide();
                    } else {

                        $('.loading_button').hide();
                        toastr.success(data);

                        window.location = "{{ url()->previous() }}";
                    }
                },
                error: function(err) {
                    $('.loading_button').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server error. Please contact the support team.');
                        return;
                    }

                    toastr.error('Please check again all form fields.', 'Some thing went wrong.');

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
        });

        $(document).on('click', function(e) {

            if (
                $(e.target).closest(".select_area").length === 0 ||
                $(e.target).closest(".invoice_search_result").length === 0
            ) {

                $('.select_area').hide();
                $('.invoice_search_result').hide();
                $('#requisition_list').empty();
                $('#list').empty();
            }
        });

        $(document).on('keyup', 'body', function(e) {

            if (e.keyCode == 13) {

                $('.' + selectObjClassName).click();
                $('.invoice_search_result').hide();
                $('.select_area').hide();
                $('#list').empty();
                $('#requisition_list').empty();
            }
        });

        $('#supplier_account_id').on('change', function() {

            var supplier_account_id = $(this).val();
            $('#closing_balance').val(parseFloat(0).toFixed(2));
            if (supplier_account_id) {

                getAccountClosingBalance(supplier_account_id);
            }
        });

        function getAccountClosingBalance(account_id) {

            var filterObj = {
                user_id: null,
                from_date: null,
                to_date: null,
            };

            var url = "{{ route('vouchers.journals.account.closing.balance', ':account_id') }}";
            var route = url.replace(':account_id', account_id);

            $.ajax({
                url: route,
                type: 'get',
                data: filterObj,
                success: function(data) {

                    $('#closing_balance').val(data['closing_balance_string']);
                    $('#debit_amount').val(data['curr_total_debit']);
                    $('#credit_amount').val(data['curr_total_credit']);
                    calculateTotalAmount();
                }
            });
        }
    </script>

    <script>
        $('select').on('select2:close', function(e) {

            var nextId = $(this).data('next');

            $('#' + nextId).focus();

            setTimeout(function() {

                $('#' + nextId).focus();
            }, 100);
        });

        $(document).on('change keypress click', 'select', function(e) {

            var nextId = $(this).data('next');

            if (e.which == 0) {

                $('#' + nextId).focus().select();
            }
        });

        $(document).on('change keypress', 'input', function(e) {

            var nextId = $(this).data('next');

            if (e.which == 13) {

                if ($(this).attr('id') == 'delivery_date' && $('#search_product').is(':disabled') == true) {

                    $('#e_showing_quantity').focus().select();
                    return;
                }

                if ($(this).attr('id') == 'paying_amount' && ($('#paying_amount').val() == 0 || $('#paying_amount')
                        .val() == '')) {

                    $('#save').focus().select();
                    return;
                }

                $('#' + nextId).focus().select();
            }
        });

        var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
        var _expectedDateFormat = '';
        _expectedDateFormat = dateFormat.replace('d', 'DD');
        _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
        _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');

        new Litepicker({
            singleMode: true,
            element: document.getElementById('date'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: _expectedDateFormat,
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('delivery_date'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: _expectedDateFormat,
        });
    </script>
    <script src="{{ asset('plugins/select_li/selectli.custom.js') }}"></script>
@endpush
