@extends('layout.master')
@push('css')
    <style>
        .data_preloader {
            top: 2.3%
        }

        .selected_invoice {
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

        .selectProduct {
            background-color: #645f61;
            color: #fff !important;
        }

        .select_area {
            position: relative;
            background: #ffffff;
            box-sizing: border-box;
            position: absolute;
            width: 88.3%;
            z-index: 9999999;
            padding: 0;
            left: 6%;
            display: none;
            border: 1px solid #706a6d;
            margin-top: 1px;
            border-radius: 0px;
        }

        .select_area ul {
            list-style: none;
            margin-bottom: 0;
            padding: 0px 2px;
        }

        .select_area ul li a {
            color: #000000;
            text-decoration: none;
            font-size: 11px;
            padding: 2px 2px;
            display: block;
            border: 1px solid lightgray;
            margin: 2px 0px;
        }

        .select_area ul li a:hover {
            background-color: #999396;
            color: #fff;
        }

        .element-body {
            overflow: initial !important;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
@endpush
@section('title', 'Edit Purchase Return - ')
@section('content')
    <div class="body-wraper">
        <div class="container-fluid p-0">
            <div class="sec-name">
                <h6>@lang('menu.edit_purchase_return')</h6>
                <x-back-button />
            </div>
            <form id="edit_purchase_return_form" action="{{ route('purchases.returns.update', $return->id) }}" method="POST">
                @csrf
                <section class="p-15">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element m-0 rounded">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-xl-3 col-md-6">
                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('menu.supplier') </b> <span class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <select required name="supplier_account_id" class="form-control select2 form-select" id="supplier_account_id" data-next="purchase_invoice_id">
                                                        <option value="">@lang('menu.select_supplier')</option>
                                                        @foreach ($supplierAccounts as $supplierAccount)
                                                            <option {{ $supplierAccount->id == $return->supplier_account_id ? 'SELECTED' : '' }} value="{{ $supplierAccount->id }}">
                                                                {{ $supplierAccount->name . '/' . $supplierAccount->phone }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_supplier_account_id"></span>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4 text-danger"><b>@lang('menu.curr_bal') </b></label>
                                                <div class="col-8">
                                                    @php
                                                        $accountUtil = new App\Utils\AccountUtil();
                                                        $amounts = $accountUtil->accountClosingBalance($return->supplier_account_id);
                                                    @endphp
                                                    <input readonly type="text" class="form-control fw-bold" id="current_balance" value="{{ $amounts['closing_balance_string'] }}" tabindex="-1">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-6">
                                            <div class="input-group">
                                                <label class=" col-4"><b>@lang('menu.p_invoice_id')</b> </label>
                                                <div class="col-8">
                                                    <div style="position: relative;">
                                                        <input type="text" name="purchase_invoice_id" id="purchase_invoice_id" class="form-control fw-bold" value="{{ $return?->purchase?->invoice_id }}" data-next="warehouse_id" placeholder="Purchase Invoice ID" autocomplete="off">
                                                        <input type="hidden" name="purchase_id" id="purchase_id" class="resetable" value="{{ $return?->purchase?->id }}">

                                                        <div class="invoice_search_result display-none">
                                                            <ul id="invoice_list" class="list-unstyled"></ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @if (count($warehouses) > 0)

                                                <input name="warehouse_count" value="YES" type="hidden" />
                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.warehouse') </b></label>
                                                    <div class="col-8">
                                                        <select class="form-control form-select" name="warehouse_id" id="warehouse_id" data-next="purchase_account_id">
                                                            <option value="">@lang('menu.select_warehouse')</option>
                                                            @foreach ($warehouses as $w)
                                                                <option data-warehouse_name="{{ $w->warehouse_name }}" data-warehouse_code="{{ $w->warehouse_code }}" value="{{ $w->id }}">
                                                                    {{ $w->warehouse_name . '/' . $w->warehouse_code }}
                                                                </option>
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
                                        </div>

                                        <div class="col-xl-3 col-md-6">
                                            <div class="input-group">
                                                <label class=" col-4"><b>@lang('menu.voucher_no')</b> </label>
                                                <div class="col-8">
                                                    <input readonly type="text" name="voucher_no" id="voucher_no" class="form-control fw-bold" placeholder="@lang('menu.voucher_no')" value="{{ $return->voucher_no }}" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b> @lang('menu.pur_ledger') <span class="text-danger">*</span></b></label>
                                                <div class="col-8">
                                                    <select name="purchase_account_id" class="form-control select2 form-select" id="purchase_account_id" data-next="date">
                                                        @foreach ($purchaseAccounts as $purchaseAccount)
                                                            <option {{ $purchaseAccount->id == $return->purchase_account_id ? 'SELECTED' : '' }} value="{{ $purchaseAccount->id }}">
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
                                                <label class="col-4"><b>@lang('menu.return_date') <span class="text-danger">*</span></b></label>
                                                <div class="col-8">
                                                    <input type="text" name="date" class="form-control" id="date" value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($return->date)) }}" data-next="search_product" autocomplete="off">
                                                    <span class="error error_date"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sale-content py-1">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="item-details-sec">
                                    <div class="content-inner">
                                        <div class="row g-xxl-4 g-3 align-items-end">
                                            <div class="col-xl-6">
                                                <div class="searching_area" style="position: relative;">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="fas fa-barcode text-dark input_f"></i>
                                                            </span>
                                                        </div>

                                                        <input type="text" name="search_product" class="form-control fw-bold" id="search_product" placeholder="Search Product by product code(SKU) / Scan bar code" autocomplete="off" autofocus>
                                                    </div>

                                                    <div class="select_area">
                                                        <ul id="list" class="variant_list_area"></ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-xxl-4 ge-1 align-items-end">
                                            <div class="hidden_fields">
                                                <input type="hidden" id="e_unique_id">
                                                <input type="hidden" id="e_item_name">
                                                <input type="hidden" id="e_product_id">
                                                <input type="hidden" id="e_variant_id">
                                                <input type="hidden" id="e_tax_amount">
                                                <input type="hidden" id="e_showing_tax_amount">
                                                <input type="hidden" id="e_unit_cost_inc_tax">
                                                <input type="hidden" id="e_showing_unit_cost_inc_tax">
                                                <input type="hidden" id="e_base_unit_cost_exc_tax">
                                                <input type="hidden" id="e_current_return_qty" value="0">
                                                <input type="hidden" id="e_current_warehouse_id">
                                            </div>

                                            <div class="col-xl-2 col-md-6">
                                                <label><b>@lang('menu.quantity') </b></label>
                                                <div class="input-group">
                                                    <input type="number" step="any" class="form-control w-60 fw-bold" id="e_showing_return_quantity" placeholder="@lang('menu.return_quantity')" value="0.00">
                                                    <input type="hidden" id="e_return_quantity" value="0.00">
                                                    <select id="e_unit_id" class="form-control w-40 form-select">
                                                        <option value="">@lang('menu.unit')</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-6">
                                                <label><b>@lang('menu.unit_cost_exc_tax')</b></label>
                                                <input type="number" step="any" class="form-control fw-bold" id="e_showing_unit_cost_exc_tax" placeholder="@lang('menu.unit_cost_exc_tax')" value="0.00">
                                                <input type="hidden" id="e_unit_cost_exc_tax" value="0.00">
                                            </div>

                                            <div class="col-xl-2 col-md-6">
                                                <label><b>@lang('menu.discount')</b></label>
                                                <div class="input-group">
                                                    <input type="number" step="any" class="form-control fw-bold" id="e_showing_discount" placeholder="@lang('menu.discount')" value="0.00">
                                                    <input type="hidden" id="e_discount" value="0.00">
                                                    <select id="e_discount_type" class="form-control form-select">
                                                        <option value="1">@lang('menu.fixed')(0.00)</option>
                                                        <option value="2">@lang('menu.percentage')(%)</option>
                                                    </select>
                                                    <input type="hidden" id="e_showing_discount_amount">
                                                    <input type="hidden" id="e_discount_amount">
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-6">
                                                <label><b>@lang('menu.tax') </b></label>
                                                <div class="input-group">
                                                    <select id="e_tax_ac_id" class="form-control form-select">
                                                        <option data-product_tax_percent="0.00" value="">
                                                            @lang('menu.no_tax')</option>
                                                        @foreach ($taxAccounts as $taxAccount)
                                                            <option data-product_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                                {{ $taxAccount->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    <select id="e_tax_type" class="form-control form-select" tabindex="-1">
                                                        <option value="1">@lang('menu.exclusive')</option>
                                                        <option value="2">@lang('menu.inclusive')</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-6">
                                                <label><b>@lang('menu.sub_total')</b></label>
                                                <input readonly type="number" step="any" class="form-control fw-bold" id="e_subtotal" value="0.00" tabindex="-1">
                                            </div>

                                            <div class="col-xl-1 col-md-6">
                                                <div class="btn-box-2">
                                                    <a href="#" class="btn btn-sm btn-success" id="add_item">@lang('menu.add')</a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="sale-item-sec">
                                                <div class="sale-item-inner">
                                                    <div class="table-responsive">
                                                        <table class="display data__table table sale-product-table">
                                                            <thead class="staky">
                                                                <tr>
                                                                    <th>@lang('menu.product')</th>
                                                                    <th>@lang('menu.unit_cost_inc_tax')</th>
                                                                    <th>@lang('menu.purchased_qty')</th>
                                                                    <th>@lang('menu.stock_location')</th>
                                                                    <th>@lang('menu.return_qty')</th>
                                                                    <th>@lang('menu.unit')</th>
                                                                    <th>@lang('menu.sub_total')</th>
                                                                    <th><i class="fas fa-minus text-white"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="return_item_list">
                                                                @php
                                                                    $itemUnitsArray = [];
                                                                @endphp
                                                                @foreach ($return->returnProducts as $returnProduct)
                                                                    @php
                                                                        if (isset($returnProduct->product_id)) {
                                                                            $itemUnitsArray[$returnProduct->product_id][] = [
                                                                                'unit_id' => $returnProduct->product->unit->id,
                                                                                'unit_name' => $returnProduct->product->unit->name,
                                                                                'unit_code_name' => $returnProduct->product->unit->code_name,
                                                                                'base_unit_multiplier' => 1,
                                                                                'multiplier_details' => '',
                                                                                'is_base_unit' => 1,
                                                                            ];
                                                                        }
                                                                        
                                                                        if (count($returnProduct?->product?->unit?->childUnits) > 0) {
                                                                            foreach ($returnProduct?->product?->unit?->childUnits as $unit) {
                                                                                $multiplierDetails = '(1 ' . $unit->name . ' = ' . $unit->base_unit_multiplier . '/' . $returnProduct?->product?->unit?->name . ')';
                                                                        
                                                                                array_push($itemUnitsArray[$returnProduct->product_id], [
                                                                                    'unit_id' => $unit->id,
                                                                                    'unit_name' => $unit->name,
                                                                                    'unit_code_name' => $unit->code_name,
                                                                                    'base_unit_multiplier' => $unit->base_unit_multiplier,
                                                                                    'multiplier_details' => $multiplierDetails,
                                                                                    'is_base_unit' => 0,
                                                                                ]);
                                                                            }
                                                                        }
                                                                        
                                                                        $variantName = $returnProduct?->variant ? ' - ' . $returnProduct?->variant?->variant_name : '';
                                                                        $variantId = $returnProduct->product_variant_id ? $returnProduct->product_variant_id : 'noid';
                                                                        
                                                                        $baseUnitMultiplier = $returnProduct?->returnUnit?->base_unit_multiplier ? $returnProduct?->returnUnit?->base_unit_multiplier : 1;
                                                                        $returnedQty = $returnProduct->return_qty / $baseUnitMultiplier;
                                                                    @endphp

                                                                    <tr id="select_item">
                                                                        <td class="text-start">
                                                                            <span class="product_name">{{ $returnProduct->product->name . $variantName }}</span>
                                                                            <input type="hidden" id="item_name" value="{{ $returnProduct->product->name . $variantName }}">
                                                                            <input type="hidden" name="product_ids[]" id="product_id" value="{{ $returnProduct->product_id }}">
                                                                            <input type="hidden" name="variant_ids[]" id="variant_id" value="{{ $variantId }}">
                                                                            <input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="{{ $returnProduct->tax_ac_id ? $returnProduct->tax_ac_id : '' }}">
                                                                            <input type="hidden" name="tax_types[]" id="tax_type" value="{{ $returnProduct->tax_type }}">
                                                                            <input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="{{ $returnProduct->unit_tax_percent }}">
                                                                            <input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="{{ $returnProduct->unit_tax_amount }}">

                                                                            <input type="hidden" id="showing_unit_tax_amount" value="{{ $returnProduct->unit_tax_amount * $baseUnitMultiplier }}">
                                                                            <input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="{{ $returnProduct->unit_discount_type }}">
                                                                            <input type="hidden" name="unit_discounts[]" id="unit_discount" value="{{ $returnProduct->unit_discount }}">
                                                                            <input type="hidden" id="showing_unit_discount" value="{{ bcadd($returnProduct->unit_discount * $baseUnitMultiplier, 0, 2) }}">

                                                                            <input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="{{ $returnProduct->unit_discount_amount }}">
                                                                            <input type="hidden" id="showing_unit_discount_amount" value="{{ bcadd($returnProduct->unit_discount_amount * $baseUnitMultiplier, 0, 2) }}">
                                                                            <input type="hidden" name="purchase_product_ids[]" value="{{ $returnProduct->purchase_product_id }}">
                                                                            <input type="hidden" name="purchase_return_product_ids[]" value="{{ $returnProduct->id }}">
                                                                            <input type="hidden" class="unique_id" id="{{ $returnProduct->product_id . $variantId . $returnProduct->warehouse_id }}" value="{{ $returnProduct->product_id . $variantId . $returnProduct->warehouse_id }}">
                                                                        </td>

                                                                        <td>
                                                                            <span id="span_showing_unit_cost_inc_tax" class="fw-bold">{{ bcadd($returnProduct->unit_cost_inc_tax * $baseUnitMultiplier, 0, 2) }}</span>
                                                                            <input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="{{ $returnProduct->unit_cost_inc_tax }}">
                                                                            <input type="hidden" id="showing_unit_cost_inc_tax" value="{{ $returnProduct->unit_cost_inc_tax * $baseUnitMultiplier }}">
                                                                            <input type="hidden" name="unit_costs_exc_tax[]" id="unit_cost_exc_tax" value="{{ $returnProduct->unit_cost_exc_tax }}">
                                                                            <input type="hidden" id="showing_unit_cost_exc_tax" value="{{ $returnProduct->unit_cost_exc_tax * $baseUnitMultiplier }}">
                                                                        </td>

                                                                        <td>
                                                                            @php
                                                                                $purchaseBaseUnitMultiplier = $returnProduct?->purchaseProduct?->purchaseUnit?->base_unit_multiplier ? $returnProduct?->purchaseProduct?->purchaseUnit?->base_unit_multiplier : 1;
                                                                            @endphp
                                                                            <span id="span_purchased_qty" class="fw-bold">
                                                                                {{ $returnProduct?->purchaseProduct ? bcadd($returnProduct?->purchaseProduct?->quantity / bcadd($purchaseBaseUnitMultiplier, 0, 2), 0, 2) . '/' . $returnProduct?->purchaseProduct?->purchaseUnit?->name : 0 }}
                                                                            </span>
                                                                            <input type="hidden" name="purchased_quantities[]" value="{{ $returnProduct?->purchaseProduct?->quantity ?? 0 }}">
                                                                        </td>

                                                                        @php
                                                                            $stockLocationName = '';
                                                                            if ($returnProduct->warehouse) {
                                                                                $stockLocationName = $returnProduct->warehouse->warehouse_name;
                                                                            } else {
                                                                                $stockLocationName = json_decode($generalSettings->business, true)['shop_name'];
                                                                            }
                                                                        @endphp

                                                                        <td class="text-start">
                                                                            <input type="hidden" name="warehouse_ids[]" id="warehouse_id" value="{{ $returnProduct->warehouse_id }}">
                                                                            <input type="hidden" id="current_warehouse_id" value="{{ $returnProduct->warehouse_id }}">
                                                                            <span id="stock_location_name">{{ $stockLocationName }}</span>
                                                                        </td>

                                                                        <td>
                                                                            <span id="span_showing_return_quantity" class="fw-bold">
                                                                                {{ bcadd($returnProduct->return_qty / $baseUnitMultiplier, 0, 2) }}
                                                                            </span>
                                                                            <input type="hidden" name="return_quantities[]" id="return_quantity" value="{{ $returnProduct->return_qty }}">
                                                                            <input type="hidden" id="showing_return_quantity" value="{{ bcadd($returnProduct->return_qty / $baseUnitMultiplier, 0, 2) }}">
                                                                            <input type="hidden" id="current_return_qty" value="{{ $returnProduct->return_qty }}">
                                                                        </td>

                                                                        <td class="text">
                                                                            <span id="span_unit" class="fw-bold">{{ $returnProduct?->returnUnit?->name }}</span>
                                                                            <input type="hidden" name="unit_ids[]" id="unit_id" value="{{ $returnProduct->unit_id }}">
                                                                        </td>

                                                                        <td class="text text-center">
                                                                            <span id="span_subtotal" class="fw-bold">{{ $returnProduct->return_subtotal }}</span>
                                                                            <input type="hidden" name="subtotals[]" id="subtotal" value="{{ $returnProduct->return_subtotal }}" tabindex="-1">
                                                                        </td>

                                                                        <td class="text-center">
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
                            </div>
                        </div>
                    </div>

                    <div class="row g-1">
                        <div class="col-md-6">
                            <div class="form_element m-0 rounded">
                                <div class="element-body">
                                    <div class="row g-1">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.total_item') </b> </label>
                                                <div class="col-8">
                                                    <input readonly name="total_item" type="number" step="any" class="form-control fw-bold" id="total_item" value="{{ $return->total_item }}" tabindex="-1">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.total_return_qty') </b> </label>
                                                <div class="col-8">
                                                    <input readonly name="total_qty" type="number" step="any" class="form-control fw-bold" id="total_qty" value="{{ $return->total_qty }}" tabindex="-1">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.net_total_amount') </b></label>
                                                <div class="col-8">
                                                    <input readonly type="number" step="any" name="net_total_amount" class="form-control fw-bold" id="net_total_amount" value="{{ $return->net_total_amount }}" tabindex="-1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form_element m-0 rounded">
                                <div class="element-body">
                                    <div class="row g-1">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class=" col-4"><b>@lang('menu.return_discount') </b></label>
                                                <div class="col-8">
                                                    <div class="input-group">
                                                        <input name="return_discount" type="number" class="form-control fw-bold" id="return_discount" value="0.00" data-next="return_discount_type">
                                                        <input name="return_discount_amount" type="number" step="any" class="d-none" id="return_discount_amount" value="0.00">

                                                        <select name="return_discount_type" class="form-control form-select" id="return_discount_type" data-next="return_tax_ac_id">
                                                            <option value="1">@lang('menu.fixed')(0.00)</option>
                                                            <option value="2">@lang('menu.percentage')(%)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.return_tax') </b></label>

                                                <div class="col-8">
                                                    <select name="return_tax_ac_id" class="form-control form-select" id="return_tax_ac_id" data-next="save">
                                                        <option data-return_tax_percent="0.00" value="">
                                                            @lang('menu.no_tax')</option>
                                                        @foreach ($taxAccounts as $taxAccount)
                                                            <option {{ $taxAccount->id == $return->tax_ac_id ? 'SELECTED' : '' }} data-return_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                                {{ $taxAccount->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input name="return_tax_percent" type="number" step="any" class="d-none" id="return_tax_percent" value="{{ $return->return_tax_percent }}">
                                                    <input name="return_tax_amount" type="number" step="any" class="d-none" id="return_tax_amount" value="{{ $return->return_tax_amount }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.total_return_amount') </b></label>
                                                <div class="col-8">
                                                    <input readonly type="number" step="any" name="total_return_amount" class="form-control fw-bold" id="total_return_amount" value="{{ $return->total_return_amount }}" placeholder="@lang('menu.total_return_amount')" tabindex="-1">
                                                    <input type="hidden" name="purchase_ledger_amount" id="purchase_ledger_amount">
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
                                <button type="button" id="save" class="btn w-auto btn-success submit_button">@lang('menu.save_changes')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <x-shortcut-key-bar.shortcut-key-bar :items="[['key' => 'Ctrl + Enter', 'value' => __('menu.save_changes')]]">
    </x-shortcut-key-bar.shortcut-key-bar>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        var itemUnitsArray = @json($itemUnitsArray);
        var branch_name = "{{ json_decode($generalSettings->business, true)['shop_name'] }}";
        $('.select2').select2();

        var ul = '';
        var selectObjClassName = '';
        $('#purchase_invoice_id').mousedown(function(e) {

            afterClickOrFocusSaleInvoiceId();
        }).focus(function(e) {

            ul = document.getElementById('invoice_list')
            selectObjClassName = 'selected_invoice';
        });

        function afterClickOrFocusSaleInvoiceId() {

            ul = document.getElementById('invoice_list')
            selectObjClassName = 'selected_invoice';
            $('#purchase_invoice_id').val('');
            $('#supplier_account_id').val('').trigger('change');
            $('#current_balance').val(0.00);
            $('#purchase_id').val('');
            $('#search_product').prop('disabled', false);
            $('#return_item_list').empty();
            $('.invoice_search_result').hide();
            $('#invoice_list').empty();
            calculateTotalAmount();
        }

        function afterFocusSearchItemField() {

            ul = document.getElementById('list')
            selectObjClassName = 'selectProduct';

            $('#sale_id').val('');
        }

        $('#search_product').focus(function(e) {

            afterFocusSearchItemField();
        });

        $('#purchase_invoice_id').on('input', function() {

            $('.invoice_search_result').hide();

            var invoice_id = $(this).val();

            if (invoice_id === '') {

                $('.invoice_search_result').hide();
                $('#purchase_id').val('');
                $('#search_product').prop('disabled', false);
                return;
            }

            var url = "{{ route('common.ajax.call.search.purchase', [':invoice_id']) }}";
            var route = url.replace(':invoice_id', invoice_id);

            $.ajax({
                url: route,
                async: true,
                type: 'get',
                success: function(data) {

                    if (!$.isEmptyObject(data.noResult)) {

                        $('.invoice_search_result').hide();
                    } else {

                        $('.invoice_search_result').show();
                        $('#invoice_list').html(data);
                    }
                }
            });
        });

        $(document).on('click', '#selected_invoice', function(e) {
            e.preventDefault();

            var purchase_invoice_id = $(this).html();
            var purchase_id = $(this).data('purchase_id');
            var warehouse_id = $(this).data('warehouse_id');
            var warehouse_name = $(this).data('warehouse_name');
            var supplier_account_id = $(this).data('supplier_account_id');
            var supplier_curr_balance = $(this).data('current_balance');

            var url = "{{ route('common.ajax.call.get.purchase.products', [':purchase_id']) }}";
            var route = url.replace(':purchase_id', purchase_id);

            $.ajax({
                url: route,
                async: true,
                type: 'get',
                success: function(purchase_products) {

                    if (!$.isEmptyObject(purchase_products.errorMsg)) {

                        toastr.error(purchase_products.errorMsg);
                        $('#purchase_invoice_id').focus().select();
                        return;
                    }

                    itemUnitsArray = jQuery.parseJSON(data.units);

                    $('#purchase_invoice_id').val(purchase_invoice_id.trim());
                    $('#purchase_id').val(purchase_id);
                    $('#warehouse_id').val(warehouse_id);
                    $('#supplier_account_id').val(supplier_account_id).trigger('change');
                    $('#current_balance').val(supplier_curr_balance);
                    $('.invoice_search_result').hide();
                    $('#return_item_list').empty();

                    $('#search_product').prop('disabled', true);

                    $('#return_item_list').html(data.view);
                }
            });
        });

        $(document).on('keyup', 'body', function(e) {

            if (e.keyCode == 13) {

                $('.' + selectObjClassName).click();
                $('.invoice_search_result').hide();
                $('.select_area').hide();
                $('#list').empty();
                $('#invoice_list').empty();
            }
        });

        var delay = (function() {
            var timer = 0;
            return function(callback, ms) {

                clearTimeout(timer);
                timer = setTimeout(callback, ms);
            };
        })();

        $('#search_product').on('input', function(e) {
            $('.variant_list_area').empty();
            $('.select_area').hide();

            if ($('#supplier_account_id').val() == '') {

                toastr.error('Please select a listed supplier first.');
                $(this).val('');
                return;
            }

            var keyWord = $(this).val();
            var __keyWord = keyWord.replaceAll('/', '~');
            delay(function() {
                searchProduct(__keyWord);
            }, 200);
        });

        function searchProduct(keyWord) {

            $('#search_product').focus();
            var price_group_id = $('#price_group_id').val();

            var isShowNotForSaleItem = 1;
            var url = "{{ route('general.product.search.common', [':keyWord', ':isShowNotForSaleItem']) }}";
            var route = url.replace(':keyWord', keyWord);
            route = route.replace(':isShowNotForSaleItem', isShowNotForSaleItem);

            $.ajax({
                url: route,
                dataType: 'json',
                success: function(product) {

                    if (keyWord == '') {

                        toastr.error(product.errorMsg);
                        $('#search_product').val("");
                        $('.select_area').hide();
                        return;
                    }

                    if (
                        !$.isEmptyObject(product.product) ||
                        !$.isEmptyObject(product.variant_product) ||
                        !$.isEmptyObject(product.namedProducts)
                    ) {

                        $('#search_product').addClass('is-valid');

                        if (!$.isEmptyObject(product.product)) {

                            var product = product.product;

                            if (product.variants.length == 0) {

                                $('.select_area').hide();
                                $('#search_product').val('');

                                var name = product.name.length > 35 ? product.name.substring(0, 35) + '...' :
                                    product.name;

                                $('#search_product').val(name);
                                $('#e_item_name').val(name);
                                $('#e_product_id').val(product.id);
                                $('#e_variant_id').val('noid');
                                $('#e_showing_return_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                                $('#_showing_unit_discount').val(parseFloat(0).toFixed(2));
                                $('#e_discount_type').val(1);
                                $('#e_tax_ac_id').val(product.tax_ac_id);
                                $('#e_tax_type').val(product.tax_type);
                                $('#e_showing_unit_cost_exc_tax').val(parseFloat(product.product_cost).toFixed(
                                    2));
                                $('#e_showing_unit_cost_inc_tax').val(product.product_cost_with_tax);
                                $('#e_base_unit_cost_exc_tax').val(product.product_cost);

                                $('#e_unit_id').empty();
                                $('#e_unit_id').append('<option value="' + product.unit.id +
                                    '" data-is_base_unit="1" data-unit_name="' + product.unit.name +
                                    '" data-base_unit_multiplier="1">' + product.unit.name + '</option>');

                                itemUnitsArray[product.id] = [{
                                    'unit_id': product.unit.id,
                                    'unit_name': product.unit.name,
                                    'unit_code_name': product.unit.code_name,
                                    'base_unit_multiplier': 1,
                                    'multiplier_details': '',
                                    'is_base_unit': 1,
                                }];

                                if (product.unit.child_units.length > 0) {

                                    product.unit.child_units.forEach(function(unit) {

                                        var multiplierDetails = '(1 ' + unit.name + ' = ' + unit.base_unit_multiplier + '/' + unit.name + ')';

                                        itemUnitsArray[product.id].push({
                                            'unit_id': unit.id,
                                            'unit_name': unit.name,
                                            'unit_code_name': unit.code_name,
                                            'base_unit_multiplier': unit.base_unit_multiplier,
                                            'multiplier_details': multiplierDetails,
                                            'is_base_unit': 1,
                                        });

                                        $('#e_unit_id').append('<option value="' + unit.id +
                                            '" data-is_base_unit="0" data-unit_name="' + unit.name +
                                            '" data-base_unit_multiplier="' + unit
                                            .base_unit_multiplier + '">' + unit.name +
                                            multiplierDetails + '</option>');
                                    });
                                }

                                $('#add_item').html('Add');

                                calculateEditOrAddAmount();
                            } else {

                                var li = "";
                                product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/default.jpg') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;
                                $.each(product.variants, function(key, variant) {

                                    li += '<li>';
                                    li += '<a class="select_variant_product" onclick="selectProduct(this); return false;" data-product_type="variant" data-p_id="' + product.id + '" data-is_manage_stock="' + product.is_manage_stock + '" data-v_id="' + variant.id + '" data-p_name="' + product.name + '" data-v_name="' + variant.variant_name + '" data-p_tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-tax_type="' + product.tax_type + '" data-p_code="' + variant.variant_code + '" data-p_cost_exc_tax="' + variant.variant_cost + '" data-p_cost_inc_tax="' + variant.variant_cost_with_tax + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + ' - ' + variant.variant_name + '</a>';
                                    li += '</li>';
                                });

                                $('.variant_list_area').append(li);
                                $('.select_area').show();
                                $('#search_product').val('');
                            }
                        } else if (!$.isEmptyObject(product.variant_product)) {

                            $('.select_area').hide();
                            $('#search_product').val('');
                            var variant_product = product.variant_product;

                            var name = variant_product.product.name.length > 35 ? variant_product.product.name
                                .substring(0, 35) + '...' : variant_product.product.name;

                            $('#search_product').val(name + ' - ' + variant_product.variant_name);
                            $('#e_item_name').val(name + ' - ' + variant_product.variant_name);
                            $('#e_product_id').val(variant_product.product.id);
                            $('#e_variant_id').val(variant_product.id);
                            $('#e_unit').val(variant_product.product.unit.name);
                            $('#e_showing_return_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                            $('#e_showing_discount').val(parseFloat(0).toFixed(2));
                            $('#e_discount_type').val(1);
                            $('#e_tax_ac_id').val(variant_product.product.tax_id);
                            $('#e_tax_type').val(variant_product.product.tax_type);
                            $('#e_showing_unit_cost_exc_tax').val(variant_product.variant_cost);
                            $('#e_showing_unit_cost_inc_tax').val(variant_product.variant_cost_with_tax);
                            $('#e_base_unit_cost_exc_tax').val(variant_product.variant_cost);

                            $('#e_unit_id').empty();
                            $('#e_unit_id').append('<option value="' + variant.product.unit.id +
                                '" data-is_base_unit="1" data-unit_name="' + variant.product.unit.name +
                                '" data-base_unit_multiplier="1">' + variant.product.unit.name + '</option>'
                            );

                            itemUnitsArray[variant.product.id] = [{
                                'unit_id': variant.product.unit.id,
                                'unit_name': variant.product.unit.name,
                                'unit_code_name': variant.product.unit.code_name,
                                'base_unit_multiplier': 1,
                                'multiplier_details': '',
                                'is_base_unit': 1,
                            }];

                            if (variant.product.unit.child_units.length > 0) {

                                variant.product.unit.child_units.forEach(function(unit) {

                                    var multiplierDetails = '(1 ' + unit.name + ' = ' + unit
                                        .base_unit_multiplier + '/' + unit.name + ')';

                                    itemUnitsArray[variant.product.id].push({
                                        'unit_id': unit.id,
                                        'unit_name': unit.name,
                                        'unit_code_name': unit.code_name,
                                        'base_unit_multiplier': unit.base_unit_multiplier,
                                        'multiplier_details': multiplierDetails,
                                        'is_base_unit': 0,
                                    });

                                    $('#e_unit_id').append('<option value="' + unit.id +
                                        '" data-is_base_unit="0" data-unit_name="' + unit.name +
                                        '" data-base_unit_multiplier="' + unit
                                        .base_unit_multiplier + '">' + unit.name +
                                        multiplierDetails + '</option>');
                                });
                            }

                            $('#add_item').html('Add');

                            calculateEditOrAddAmount();
                        } else if (!$.isEmptyObject(product.namedProducts)) {

                            if (product.namedProducts.length > 0) {

                                product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/default.jpg') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;
                                var li = "";
                                var products = product.namedProducts;

                                $.each(products, function(key, product) {

                                    if (product.is_variant == 1) {

                                        li += '<li>';
                                        li += '<a class="select_variant_product" onclick="selectProduct(this); return false;" data-product_type="variant" data-p_id="' + product.id + '" data-is_manage_stock="' + product.is_manage_stock + '" data-v_id="' + product.variant_id + '" data-p_name="' + product.name + '" data-v_name="' + product.variant_name + '" data-p_tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-tax_type="' + product.tax_type + '" data-p_code="' + product.variant_code + '" data-p_cost_exc_tax="' + product.variant_cost + '" data-p_cost_inc_tax="' + product.variant_cost_with_tax + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + ' - ' + product.variant_name + '</a>';
                                        li += '</li>';
                                    } else {

                                        li += '<li>';
                                        li += '<a class="select_single_product" onclick="selectProduct(this); return false;" data-product_type="single" data-p_id="' + product.id + '" data-v_id="" data-v_name="" data-is_manage_stock="' + product.is_manage_stock + '" data-p_name="' + product.name + '" data-p_code="' + product.product_code + '" data-p_cost_exc_tax="' + product.product_cost + '" data-tax_type="' + product.tax_type + '" data-p_tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-p_cost_inc_tax="' +
                                            product.product_cost_with_tax + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + '</a>';
                                        li += '</li>';
                                    }
                                });

                                $('.variant_list_area').html(li);
                                $('.select_area').show();
                            }
                        }
                    } else {

                        $('#search_product').addClass('is-invalid');
                        toastr.error('Product not found.', 'Failed');
                        $('#search_product').select();
                    }
                }
            });
        }

        function selectProduct(e) {

            $('.select_area').hide();
            $('#search_product').val('');

            var product_id = e.getAttribute('data-p_id');
            var variant_id = e.getAttribute('data-v_id');
            var is_manage_stock = e.getAttribute('data-is_manage_stock');
            var product_name = e.getAttribute('data-p_name');
            var variant_name = e.getAttribute('data-v_name');
            var product_code = e.getAttribute('data-p_code');
            var product_cost_exc_tax = e.getAttribute('data-p_cost_exc_tax');
            var product_cost_inc_tax = e.getAttribute('data-p_cost_inc_tax');

            var p_tax_ac_id = e.getAttribute('data-p_tax_ac_id') != null ? e.getAttribute('data-p_tax_ac_id') : '';
            var p_tax_id = e.getAttribute('data-tax_id');
            var p_tax_type = e.getAttribute('data-tax_type');
            $('#search_product').val('');

            var url = "{{ route('general.product.search.product.unit.and.multiplier.unit', [':product_id']) }}"
            var route = url.replace(':product_id', product_id);

            $.ajax({
                url: route,
                dataType: 'json',
                success: function(baseUnit) {

                    var name = product_name.length > 35 ? product_name.substring(0, 35) + '...' : product_name;

                    $('#search_product').val(name + (variant_name ? ' - ' + variant_name : ''));
                    $('#e_item_name').val(name + (variant_name ? ' - ' + variant_name : ''));
                    $('#e_product_id').val(product_id);
                    $('#e_variant_id').val(variant_id ? variant_id : 'noid');
                    $('#e_showing_return_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                    $('#e_showing_discount').val(parseFloat(0).toFixed(2));
                    $('#e_discount_type').val(1);
                    $('#e_showing_unit_cost_exc_tax').val(parseFloat(product_cost_exc_tax).toFixed(2));
                    $('#e_tax_ac_id').val(p_tax_ac_id);
                    $('#e_tax_type').val(p_tax_type);
                    $('#e_showing_unit_cost_inc_tax').val(parseFloat(product_cost_inc_tax).toFixed(2));
                    $('#e_base_unit_cost_exc_tax').val(parseFloat(product_cost_exc_tax).toFixed(2));

                    $('#e_unit_id').empty();
                    $('#e_unit_id').append('<option value="' + baseUnit.id +
                        '" data-is_base_unit="1" data-unit_name="' + baseUnit.name +
                        '" data-base_unit_multiplier="1">' + baseUnit.name + '</option>');

                    itemUnitsArray[product_id] = [{
                        'unit_id': baseUnit.id,
                        'unit_name': baseUnit.name,
                        'unit_code_name': baseUnit.code_name,
                        'base_unit_multiplier': 1,
                        'multiplier_details': '',
                        'is_base_unit': 1,
                    }];

                    if (baseUnit.child_units.length > 0) {

                        baseUnit.child_units.forEach(function(unit) {

                            var multiplierDetails = '(1 ' + unit.name + ' = ' + unit.base_unit_multiplier + '/' + baseUnit.name + ')';

                            itemUnitsArray[product_id].push({
                                'unit_id': unit.id,
                                'unit_name': unit.name,
                                'unit_code_name': unit.code_name,
                                'base_unit_multiplier': unit.base_unit_multiplier,
                                'multiplier_details': multiplierDetails,
                                'is_base_unit': 0,
                            });

                            $('#e_unit_id').append('<option value="' + unit.id +
                                '" data-is_base_unit="0" data-unit_name="' + unit.name +
                                '" data-base_unit_multiplier="' + unit.base_unit_multiplier + '">' +
                                unit.name + multiplierDetails + '</option>');
                        });
                    }

                    $('#add_item').html('Add');

                    calculateEditOrAddAmount();
                }
            });
        }

        $('#add_item').on('click', function(e) {
            e.preventDefault();

            var e_unique_id = $('#e_unique_id').val();
            var e_item_name = $('#e_item_name').val();
            var e_product_id = $('#e_product_id').val();
            var e_variant_id = $('#e_variant_id').val();
            var e_unit_id = $('#e_unit_id').val();
            var e_unit_name = $('#e_unit_id').find('option:selected').data('unit_name');
            var e_return_quantity = $('#e_return_quantity').val() ? $('#e_return_quantity').val() : 0;
            var e_showing_return_quantity = $('#e_showing_return_quantity').val() ? $('#e_showing_return_quantity').val() : 0;
            var e_current_return_qty = $('#e_current_return_qty').val() ? $('#e_current_return_qty').val() : 0;
            var e_current_warehouse = $('#e_current_warehouse_id').val();
            var e_unit_cost_exc_tax = $('#e_unit_cost_exc_tax').val() ? $('#e_unit_cost_exc_tax').val() : 0;
            var e_showing_unit_cost_exc_tax = $('#e_showing_unit_cost_exc_tax').val() ? $('#e_showing_unit_cost_exc_tax').val() : 0;
            var e_discount = $('#e_discount').val() ? $('#e_discount').val() : 0;
            var e_showing_discount = $('#e_showing_discount').val() ? $('#e_showing_discount').val() : 0;
            var e_discount_type = $('#e_discount_type').val();
            var e_discount_amount = $('#e_discount_amount').val() ? $('#e_discount_amount').val() : 0;
            var e_showing_discount_amount = $('#e_showing_discount_amount').val() ? $('#e_showing_discount_amount').val() : 0;
            var e_tax_ac_id = $('#e_tax_ac_id').val();
            var e_tax_percent = $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') ? $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') : 0;
            var e_tax_amount = $('#e_tax_amount').val() ? $('#e_tax_amount').val() : 0;
            var e_showing_tax_amount = $('#e_showing_tax_amount').val() ? $('#e_showing_tax_amount').val() : 0;
            var e_tax_type = $('#e_tax_type').val();
            var e_unit_cost_inc_tax = $('#e_unit_cost_inc_tax').val() ? $('#e_unit_cost_inc_tax').val() : 0;
            var e_showing_unit_cost_inc_tax = $('#e_showing_unit_cost_inc_tax').val() ? $(
                '#e_showing_unit_cost_inc_tax').val() : 0;
            var e_subtotal = $('#e_subtotal').val() ? $('#e_subtotal').val() : 0;

            var warehouse_id = $('#warehouse_id').val();
            var warehouse_name = $('#warehouse_id').find('option:selected').data('warehouse_name');

            if (e_showing_return_quantity == '') {

                toastr.error('Quantity field must not be empty.');
                return;
            }

            if (e_product_id == '') {

                toastr.error('Please select a product.');
                return;
            }

            var stock_location_name = '';
            if (warehouse_id) {

                stock_location_name = warehouse_name;
            } else {

                stock_location_name = branch_name;
            }

            var route = '';
            if (e_variant_id != 'noid') {

                var url = "{{ route('general.product.search.variant.product.stock', [':e_product_id', ':e_variant_id', ':warehouse_id']) }}";
                route = url.replace(':e_product_id', e_product_id);
                route = route.replace(':e_variant_id', e_variant_id);
                route = route.replace(':warehouse_id', warehouse_id);
            } else {

                var url = "{{ route('general.product.search.single.product.stock', [':e_product_id', ':warehouse_id']) }}";
                route = url.replace(':e_product_id', e_product_id);
                route = route.replace(':warehouse_id', warehouse_id);
            }

            $.ajax({
                url: route,
                type: 'get',
                dataType: 'json',
                success: function(data) {

                    if ($.isEmptyObject(data.errorMsg)) {

                        var check_quantity = parseFloat(e_return_quantity);

                        if (e_current_warehouse_id == warehouse_id) {

                            var check_quantity = parseFloat(e_return_quantity) - parseFloat(
                                e_current_return_qty);
                        }

                        var stockLocationMessage = warehouse_id ? ' in selected warehouse' :
                            ' in the company';

                        if (parseFloat(check_quantity) > parseFloat(data.stock)) {

                            toastr.error('Current stock is ' + parseFloat(data.stock) + stockLocationMessage);
                            return;
                        }

                        var uniqueIdForPreventDuplicateEntry = e_product_id + e_variant_id + warehouse_id;
                        var uniqueIdValue = $('#' + (e_unique_id ? e_unique_id :
                            uniqueIdForPreventDuplicateEntry)).val();

                        if (uniqueIdValue == undefined) {

                            var tr = '';
                            tr += '<tr id="select_item">';
                            tr += '<td class="text-start">';
                            tr += '<span class="product_name">' + e_item_name + '</span>';
                            tr += '<input type="hidden" id="item_name" value="' + e_item_name + '">';
                            tr += '<input type="hidden" name="product_ids[]" id="product_id" value="' + e_product_id + '">';
                            tr += '<input type="hidden" name="variant_ids[]" id="variant_id" value="' + e_variant_id + '">';
                            tr += '<input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="' + e_tax_ac_id + '">';
                            tr += '<input type="hidden" name="tax_types[]" id="tax_type" value="' + e_tax_type + '">';
                            tr += '<input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="' + e_tax_percent + '">';
                            tr += '<input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="' + parseFloat(e_tax_amount).toFixed(2) + '">';
                            tr += '<input type="hidden" id="showing_unit_tax_amount" value="' + parseFloat(e_showing_tax_amount).toFixed(2) + '">';
                            tr += '<input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="' +
                                e_discount_type + '">';
                            tr += '<input type="hidden" name="unit_discounts[]" id="unit_discount" value="' + e_discount + '">';
                            tr += '<input type="hidden" id="showing_unit_discount" value="' +
                                e_showing_discount + '">';
                            tr += '<input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="' + e_discount_amount + '">';
                            tr += '<input type="hidden" id="showing_unit_discount_amount" value="' + e_showing_discount_amount + '">';
                            tr += '<input type="hidden" name="purchase_product_ids[]" value="">';
                            tr += '<input type="hidden" name="purchase_return_product_ids[]" value="">';
                            tr += '<input type="hidden" class="unique_id" id="' + e_product_id + e_variant_id + warehouse_id + '" value="' + e_product_id + e_variant_id + warehouse_id + '">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<span id="span_showing_unit_cost_inc_tax" class="fw-bold">' + parseFloat(e_showing_unit_cost_inc_tax).toFixed(2) + '</span>';
                            tr += '<input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="' + parseFloat(e_unit_cost_inc_tax).toFixed(2) + '">';
                            tr += '<input type="hidden" id="showing_unit_cost_inc_tax" value="' + parseFloat(e_showing_unit_cost_inc_tax).toFixed(2) + '">';
                            tr += '<input type="hidden" name="unit_costs_exc_tax[]" id="unit_cost_exc_tax" value="' + parseFloat(e_unit_cost_exc_tax).toFixed(2) + '">';
                            tr += '<input type="hidden" id="showing_unit_cost_exc_tax" value="' + parseFloat(e_showing_unit_cost_exc_tax).toFixed(2) + '">';

                            tr += '</td>';

                            tr += '<td class="text">';
                            tr += '<span id="span_unit" class="fw-bold">' + e_unit_name + '</span>';
                            tr += '<input type="hidden" name="unit_ids[]" id="unit_id" value="' + e_unit_id + '">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<span id="span_purchased_qty" class="fw-bold">0.00</span>';
                            tr += '<input type="hidden" name="purchased_quantities[]" value="0.00">';
                            tr += '</td>';

                            tr += '<td class="text-start">';
                            tr += '<input type="hidden" name="warehouse_ids[]" id="warehouse_id" value="' + warehouse_id + '">';
                            tr += '<span id="stock_location_name">' + stock_location_name + '</span>';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<span id="span_showing_return_quantity" class="fw-bold">' + parseFloat(e_showing_return_quantity).toFixed(2) + '</span>';
                            tr += '<input type="hidden" name="return_quantities[]" id="return_quantity" value="' + parseFloat(e_return_quantity).toFixed(2) + '">';
                            tr += '<input type="hidden" id="showing_return_quantity" value="' + parseFloat(e_showing_return_quantity).toFixed(2) + '">';
                            tr += '<input type="hidden" id="current_return_qty" value="0">';
                            tr += '</td>';

                            tr += '<td class="text text-center">';
                            tr += '<span id="span_subtotal" class="fw-bold">' + parseFloat(e_subtotal).toFixed(2) + '</span>';
                            tr += '<input type="hidden" name="subtotals[]" id="subtotal" value="' + parseFloat(e_subtotal).toFixed(2) + '" tabindex="-1">';
                            tr += '</td>';

                            tr += '<td class="text-center">';
                            tr +=
                                '<a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                            tr += '</td>';
                            tr += '</tr>';

                            $('#return_item_list').append(tr);
                            clearEditItemFileds();
                            calculateTotalAmount();
                        } else {

                            var tr = $('#' + (e_unique_id ? e_unique_id : uniqueIdForPreventDuplicateEntry)).closest('tr');

                            tr.find('#item_name').val(e_item_name);
                            tr.find('#product_id').val(e_product_id);
                            tr.find('#variant_id').val(e_variant_id);
                            tr.find('#tax_ac_id').val(e_tax_ac_id);
                            tr.find('#tax_type').val(e_tax_type);
                            tr.find('#unit_tax_percent').val(parseFloat(e_tax_percent).toFixed(2));
                            tr.find('#unit_tax_amount').val(parseFloat(e_tax_amount).toFixed(2));
                            tr.find('#showing_unit_tax_amount').val(parseFloat(e_showing_tax_amount).toFixed(2));
                            tr.find('#unit_discount_type').val(e_discount_type);
                            tr.find('#unit_discount_amount').val(parseFloat(e_discount_amount).toFixed(2));
                            tr.find('#showing_unit_discount_amount').val(parseFloat(e_showing_discount_amount).toFixed(2));
                            tr.find('#unit_discount').val(parseFloat(e_discount).toFixed(2));
                            tr.find('#showing_unit_discount').val(parseFloat(e_showing_discount).toFixed(2));
                            tr.find('#span_showing_return_quantity').html(parseFloat(e_showing_return_quantity).toFixed(2));
                            tr.find('#return_quantity').val(parseFloat(e_return_quantity).toFixed(2));
                            tr.find('#showing_return_quantity').val(parseFloat(e_showing_return_quantity).toFixed(2));
                            tr.find('#span_unit').html(e_unit_name);
                            tr.find('#unit_id').val(e_unit_id);
                            tr.find('#unit_cost_exc_tax').val(parseFloat(e_unit_cost_exc_tax).toFixed(2));
                            tr.find('#showing_unit_cost_exc_tax').val(parseFloat(e_showing_unit_cost_exc_tax).toFixed(2));
                            tr.find('#span_showing_unit_cost_inc_tax').html(parseFloat(e_showing_unit_cost_inc_tax).toFixed(2));
                            tr.find('#unit_cost_inc_tax').val(parseFloat(e_unit_cost_inc_tax).toFixed(2));
                            tr.find('#showing_unit_cost_inc_tax').val(parseFloat(e_showing_unit_cost_inc_tax).toFixed(2));
                            tr.find('#span_subtotal').html(parseFloat(e_subtotal).toFixed(2));
                            tr.find('#subtotal').val(parseFloat(e_subtotal).toFixed(2));
                            tr.find('.unique_id').val(e_product_id + e_variant_id + warehouse_id);
                            tr.find('.unique_id').attr('id', e_product_id + e_variant_id + warehouse_id);
                            tr.find('#warehouse_id').val(warehouse_id);
                            tr.find('#stock_location_name').html(stock_location_name);

                            clearEditItemFileds();
                            calculateTotalAmount();
                        }

                        $('#add_item').html('Add');
                    } else {

                        toastr.error(data.errorMsg);
                    }
                }
            });
        });

        $(document).on('click', '#select_item', function(e) {

            var tr = $(this);
            var unique_id = tr.find('.unique_id').val();
            var warehouse_id = tr.find('#warehouse_id').val();
            var current_warehouse_id = tr.find('#current_warehouse_id').val();
            var item_name = tr.find('#item_name').val();
            var product_id = tr.find('#product_id').val();
            var unit_id = tr.find('#unit_id').val();
            var variant_id = tr.find('#variant_id').val();
            var tax_ac_id = tr.find('#tax_ac_id').val();
            var tax_type = tr.find('#tax_type').val();
            var unit_tax_percent = tr.find('#unit_tax_percent').val();
            var unit_tax_amount = tr.find('#unit_tax_amount').val();
            var showing_unit_tax_amount = tr.find('#showing_unit_tax_amount').val();
            var unit_discount_type = tr.find('#unit_discount_type').val();
            var unit_discount_amount = tr.find('#unit_discount_amount').val();
            var showing_unit_discount_amount = tr.find('#showing_unit_discount_amount').val();
            var unit_discount = tr.find('#unit_discount').val();
            var showing_unit_discount = tr.find('#showing_unit_discount').val();
            var unit_cost_exc_tax = tr.find('#unit_cost_exc_tax').val();
            var showing_unit_cost_exc_tax = tr.find('#showing_unit_cost_exc_tax').val();
            var unit_cost_inc_tax = tr.find('#unit_cost_inc_tax').val();
            var showing_unit_cost_inc_tax = tr.find('#showing_unit_cost_inc_tax').val();
            var return_quantity = tr.find('#return_quantity').val();
            var showing_return_quantity = tr.find('#showing_return_quantity').val();
            var current_return_qty = tr.find('#current_return_qty').val();
            var subtotal = tr.find('#subtotal').val();

            $('#e_unit_id').empty();

            itemUnitsArray[product_id].forEach(function(unit) {

                $('#e_unit_id').append('<option ' + (unit_id == unit.unit_id ? 'selected' : '') +
                    ' value="' + unit.unit_id + '" data-is_base_unit="' + unit.is_base_unit +
                    '" data-unit_name="' + unit.unit_name + '" data-base_unit_multiplier="' + unit
                    .base_unit_multiplier + '">' + unit.unit_name + unit.multiplier_details +
                    '</option>');
            });

            $('#search_product').val(item_name);
            $('#e_item_name').val(item_name);
            $('#e_product_id').val(product_id);
            $('#e_variant_id').val(variant_id);
            $('#e_return_quantity').val(parseFloat(return_quantity).toFixed(2));
            $('#e_showing_return_quantity').val(parseFloat(showing_return_quantity).toFixed(2)).focus().select();
            $('#e_unique_id').val(unique_id);
            $('#warehouse_id').val(warehouse_id);
            $('#e_unit_cost_exc_tax').val(unit_cost_exc_tax);
            $('#e_showing_unit_cost_exc_tax').val(showing_unit_cost_exc_tax);
            $('#e_discount').val(unit_discount);
            $('#e_showing_discount').val(showing_unit_discount);
            $('#e_discount_type').val(unit_discount_type);
            $('#e_discount_amount').val(unit_discount_amount);
            $('#e_showing_discount_amount').val(showing_unit_discount_amount);
            $('#e_tax_ac_id').val(tax_ac_id);
            $('#e_tax_amount').val(unit_tax_amount);
            $('#e_showing_tax_amount').val(showing_unit_tax_amount);
            $('#e_tax_type').val(tax_type);
            $('#e_unit_cost_inc_tax').val(unit_cost_inc_tax);
            $('#e_showing_unit_cost_inc_tax').val(showing_unit_cost_inc_tax);
            $('#e_base_unit_cost_exc_tax').val(unit_cost_exc_tax);
            $('#e_subtotal').val(subtotal);
            $('#add_item').html('Edit');
        });

        function calculateEditOrAddAmount() {

            var base_unit_multiplier = $('#e_unit_id').find('option:selected').data('base_unit_multiplier');
            var is_base_unit = $('#e_unit_id').find('option:selected').data('is_base_unit');
            var e_showing_return_quantity = $('#e_showing_return_quantity').val();
            var e_showing_unit_cost_exc_tax = $('#e_showing_unit_cost_exc_tax').val();
            var e_tax_percent = $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') ? $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') : 0.00;
            var e_tax_type = $('#e_tax_type').val();
            var e_discount_type = $('#e_discount_type').val();
            var e_showing_discount = $('#e_showing_discount').val();

            var quantity = roundOfValue(e_showing_return_quantity) * roundOfValue(base_unit_multiplier);
            $('#e_return_quantity').val(parseFloat(quantity).toFixed(2));

            var unitCostExcTax = roundOfValue(e_showing_unit_cost_exc_tax) / roundOfValue(base_unit_multiplier);
            $('#e_unit_cost_exc_tax').val(roundOfValue(unitCostExcTax));
            $('#e_base_unit_cost_exc_tax').val(roundOfValue(unitCostExcTax));

            var showing_discount_amount = 0;
            var discount_amount = 0;
            var unit_discount = 0
            if (e_discount_type == 1) {

                showing_discount_amount = roundOfValue(e_showing_discount);
                discount_amount = roundOfValue(e_showing_discount) / roundOfValue(base_unit_multiplier);
                unit_discount = roundOfValue(e_showing_discount) / roundOfValue(base_unit_multiplier);
            } else {

                showing_discount_amount = (roundOfValue(e_showing_unit_cost_exc_tax) / 100) * roundOfValue(e_showing_discount);
                discount_amount = roundOfValue(showing_discount_amount) / roundOfValue(base_unit_multiplier);
                unit_discount = roundOfValue(e_showing_discount);
            }

            var showingCostWithDiscount = roundOfValue(e_showing_unit_cost_exc_tax) - roundOfValue(showing_discount_amount);
            var showingTaxAmount = parseFloat(showingCostWithDiscount) / 100 * parseFloat(e_tax_percent);
            var taxAmount = roundOfValue(showingTaxAmount) / roundOfValue(base_unit_multiplier);
            var showingUnitCostIncTax = parseFloat(showingCostWithDiscount) + parseFloat(showingTaxAmount);
            var unitCostIncTax = roundOfValue(showingUnitCostIncTax) / roundOfValue(base_unit_multiplier);

            if (e_tax_type == 2) {

                var inclusiveTax = 100 + parseFloat(e_tax_percent);
                var calcTax = parseFloat(showingCostWithDiscount) / parseFloat(inclusiveTax) * 100;
                var __tax_amount = parseFloat(showingCostWithDiscount) - parseFloat(calcTax);
                showingTaxAmount = __tax_amount;
                taxAmount = roundOfValue(showingTaxAmount) / roundOfValue(base_unit_multiplier);
                showingUnitCostIncTax = roundOfValue(showingCostWithDiscount) + roundOfValue(showingTaxAmount);
                unitCostIncTax = roundOfValue(showingUnitCostIncTax) / roundOfValue(base_unit_multiplier);
            }

            $('#e_tax_amount').val(parseFloat(taxAmount).toFixed(2));
            $('#e_showing_tax_amount').val(parseFloat(showingTaxAmount).toFixed(2));
            $('#e_discount').val(parseFloat(roundOfValue(unit_discount)).toFixed(2));
            $('#e_discount_amount').val(parseFloat(roundOfValue(discount_amount)).toFixed(2));
            $('#e_showing_discount_amount').val(parseFloat(roundOfValue(showing_discount_amount)).toFixed(2));
            $('#e_unit_cost_inc_tax').val(parseFloat(roundOfValue(unitCostIncTax)).toFixed(2));
            $('#e_showing_unit_cost_inc_tax').val(parseFloat(roundOfValue(showingUnitCostIncTax)).toFixed(2));

            var subtotal = parseFloat(unitCostIncTax) * parseFloat(quantity);
            $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));
        }

        $('#e_showing_return_quantity').on('input keypress', function(e) {

            calculateEditOrAddAmount();

            if (e.which == 13) {

                if ($(this).val() != '') {

                    $('#e_unit_id').focus();
                }
            }
        });

        $('#e_unit_id').on('change keypress click', function(e) {

            var isBaseUnit = $(this).find('option:selected').data('is_base_unit');
            var baseUnitCostExcTax = $('#e_base_unit_cost_exc_tax').val() ? $('#e_base_unit_cost_exc_tax').val() :
                0;
            var base_unit_multiplier = $(this).find('option:selected').data('base_unit_multiplier');
            var unit_discount_type = $('#e_discount_type').val();

            var showingUnitCostExcTax = roundOfValue(baseUnitCostExcTax) * roundOfValue(base_unit_multiplier);
            $('#e_showing_unit_cost_exc_tax').val(parseFloat(showingUnitCostExcTax).toFixed(2));

            if (e.which == 0) {

                $('#e_showing_unit_cost_exc_tax').focus().select();
            }

            calculateEditOrAddAmount();
        });

        $('#e_showing_unit_cost_exc_tax').on('input keypress', function(e) {

            calculateEditOrAddAmount();

            if (e.which == 13) {

                $('#e_showing_discount').focus().select();
            }
        });

        $('#e_showing_discount').on('input keypress', function(e) {

            calculateEditOrAddAmount();

            if (e.which == 13) {

                $('#e_discount_type').focus();
            }
        });

        $('#e_discount_type').on('change keypress click', function(e) {

            calculateEditOrAddAmount();

            if (e.which == 0) {

                $('#e_tax_ac_id').focus();
            }
        });

        $('#e_tax_ac_id').on('change keypress click', function(e) {

            calculateEditOrAddAmount();

            if (e.which == 0) {

                $('#e_tax_type').focus();
            }
        });

        $('#e_tax_type').on('change keypress click', function(e) {

            calculateEditOrAddAmount();

            if (e.which == 0) {

                $('#add_item').focus();
            }
        });

        // Calculate total amount functionalitie
        function calculateTotalAmount() {

            var quantities = document.querySelectorAll('#showing_return_quantity');
            var subtotals = document.querySelectorAll('#subtotal');
            var unitTaxAmounts = document.querySelectorAll('#showing_unit_tax_amount');
            // Update Total Item

            var total_item = 0;
            var total_qty = 0;
            quantities.forEach(function(qty) {

                total_item += 1;
                total_qty += parseFloat(qty.value);
            });

            $('#total_item').val(parseFloat(total_item));
            $('#total_qty').val(parseFloat(total_qty));

            // Update Net total Amount
            var netTotalAmount = 0;
            var itemTotalTaxAmount = 0;
            var i = 0;
            subtotals.forEach(function(subtotal) {

                netTotalAmount += parseFloat(subtotal.value);
                itemTotalTaxAmount += (quantities[i].value ? quantities[i].value : 0) * (unitTaxAmounts[i].value ? unitTaxAmounts[i].value : 0);
            });

            $('#net_total_amount').val(parseFloat(netTotalAmount).toFixed(2));

            if ($('#return_discount_type').val() == 2) {

                var returnDisAmount = parseFloat(netTotalAmount) / 100 * parseFloat($('#return_discount').val() ? $('#return_discount').val() : 0);
                $('#return_discount_amount').val(parseFloat(returnDisAmount).toFixed(2));
            } else {

                var returnDiscount = $('#return_discount').val() ? $('#return_discount').val() : 0;
                $('#return_discount_amount').val(parseFloat(returnDiscount).toFixed(2));
            }

            var returnDiscountAmount = $('#return_discount_amount').val() ? $('#return_discount_amount').val() : 0;

            // Calc order tax amount
            var returnTaxPercent = $('#return_tax_ac_id').find('option:selected').data('return_tax_percent') ? $('#return_tax_ac_id').find('option:selected').data('return_tax_percent') : 0;
            var calReturnTaxAmount = (parseFloat(netTotalAmount) - parseFloat(returnDiscountAmount)) / 100 * parseFloat(
                returnTaxPercent);

            $('#return_tax_amount').val(parseFloat(calReturnTaxAmount).toFixed(2));

            var calcTotalAmount = parseFloat(netTotalAmount) - parseFloat(returnDiscountAmount) + parseFloat(calReturnTaxAmount);

            $('#total_return_amount').val(parseFloat(calcTotalAmount).toFixed(2));

            var purchaseLedgerAmount = parseFloat(netTotalAmount) -
                parseFloat(returnDiscountAmount) -
                parseFloat(itemTotalTaxAmount) -
                parseFloat(calReturnTaxAmount);

            $('#purchase_ledger_amount').val(purchaseLedgerAmount);
        }

        $(document).on('input', '#return_discount', function() {

            calculateTotalAmount();
        });

        $(document).on('change', '#return_tax_ac_id', function() {

            calculateTotalAmount();
            var returnTaxPercent = $(this).find('option:selected').data('return_tax_percent') ? $(this).find('option:selected').data('return_tax_percent') : 0;
            $('#return_tax_percent').val(parseFloat(returnTaxPercent).toFixed(2));
        });

        // Remove product form purchase product list (Table)
        $(document).on('click', '#remove_product_btn', function(e) {
            e.preventDefault();

            $(this).closest('tr').remove();
            calculateTotalAmount();
        });

        $(document).on('click keypress focus blur change', '.form-control', function(event) {

            $('.submit_button').prop('type', 'button');
        });

        $(document).on('click', '.submit_button', function() {

            $(this).prop('type', 'submit');
        });

        document.onkeyup = function() {

            var e = e || window.event; // for IE to cover IEs window event-object

            if (e.ctrlKey && e.which == 13) {

                $('#save').click();
                return false;
            } else if (e.which == 27) {

                $('.select_area').hide();
                $('.invoice_search_result').hide();

                $('#list').empty();
                $('#invoice_list').empty();

                return false;
            }
        }

        //update purchase return request by ajax
        $('#edit_purchase_return_form').on('submit', function(e) {
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

                    $('.loading_button').hide();
                    $('.error').html('');

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    toastr.success(data);
                    window.location = "{{ url()->previous() }}";

                },
                error: function(err) {

                    $('.loading_button').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server error. Please contact to the support team.');
                        return;
                    }

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
        });

        function clearEditItemFileds() {

            $('#e_unique_id').val('');
            $('#search_product').val('').focus();
            $('#e_item_name').val('');
            $('#e_product_id').val('');
            $('#e_variant_id').val('');
            $('#e_return_quantity').val(0.00);
            $('#e_showing_return_quantity').val(0.00);
            $('#e_discount').val(parseFloat(0).toFixed(2));
            $('#e_showing_discount').val(parseFloat(0).toFixed(2));
            $('#e_discount_type').val(1);
            $('#e_discount_amount').val(parseFloat(0).toFixed(2));
            $('#e_showing_discount_amount').val(parseFloat(0).toFixed(2));
            $('#e_tax_ac_id').val('');
            $('#e_tax_amount').val(parseFloat(0).toFixed(2));
            $('#e_showing_tax_amount').val(parseFloat(0).toFixed(2));
            $('#e_tax_type').val(1);
            $('#e_unit_cost_exc_tax').val(parseFloat(0).toFixed(2));
            $('#e_showing_unit_cost_exc_tax').val(parseFloat(0).toFixed(2));
            $('#e_unit_cost_inc_tax').val(parseFloat(0).toFixed(2));
            $('#e_showing_unit_cost_inc_tax').val(parseFloat(0).toFixed(2));
            $('#e_subtotal').val(parseFloat(0).toFixed(2));
        }

        // Automatic remove searching product is found signal
        setInterval(function() {

            $('#search_product').removeClass('is-invalid');
        }, 500);

        setInterval(function() {

            $('#search_product').removeClass('is-valid');
        }, 1000);

        $('#supplier_account_id').on('change', function() {

            var supplier_account_id = $(this).val();
            $('#current_balance').val(parseFloat(0).toFixed(2));
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

                    $('#current_balance').val(data['closing_balance_string']);
                    calculateTotalAmount();
                }
            });
        }

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

                if ($(this).attr('id') == 'date' && $('#search_product').is(':disabled') == true) {

                    $('#e_return_quantity').focus().select();
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

        function roundOfValue(val) {

            return ((parseFloat(val) * 1000) / 1000);
        }

        calculateTotalAmount();
    </script>
    <script src="{{ asset('plugins/select_li/selectli.custom.js') }}"></script>
@endpush
