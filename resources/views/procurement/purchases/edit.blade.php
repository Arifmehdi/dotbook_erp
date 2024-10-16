@extends('layout.master')
@push('css')
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
            left: 0%;
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
            font-size: 10px;
            padding: 2px 2px;
            display: block;
            border: 1px solid gray;
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

        .receive_stock_search_result {
            position: absolute;
            width: 100%;
            border: 1px solid #E4E6EF;
            background: white;
            z-index: 1;
            padding: 3px;
            margin-top: 1px;
        }

        .receive_stock_search_result ul li {
            width: 100%;
            border: 1px solid lightgray;
            margin-top: 2px;
        }

        .receive_stock_search_result ul li a {
            color: #6b6262;
            font-size: 10px;
            display: block;
            padding: 0px 3px;
        }

        .receive_stock_search_result ul li a:hover {
            color: var(--white-color);
            background-color: #ada9a9;
        }

        .selected_receive_stock {
            background-color: #645f61;
            color: #fff !important;
        }

        .selected_challan {
            background-color: #645f61;
            color: #fff !important;
        }

        .weight_challan_search_result {
            position: absolute;
            width: 234%;
            border: 1px solid #E4E6EF;
            background: white;
            z-index: 1;
            padding: 3px;
            margin-top: 1px;
            border: 1px solid black;
            right: 0%
        }

        .weight_challan_search_result ul li {
            width: 100%;
            border: 1px solid lightgray;
            margin-top: 2px;
        }

        .weight_challan_search_result ul li a {
            color: #6b6262;
            font-size: 11px;
            display: block;
            padding: 1px 3px;
        }

        .weight_challan_search_result ul li a:hover {
            color: var(--white-color);
            background-color: #ada9a9;
        }

        .element-body {
            overflow: initial !important;
        }

        span.select2-dropdown.select2-dropdown--below {
            border-top: 1px solid gray;
        }

        span.select2-dropdown.select2-dropdown--below {
            width: 283px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            width: auto;
        }

        .sale-item-sec {
            min-height: 240px !important;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
@endpush
@section('title', 'Edit Purchase - ')
@section('content')
    <div class="body-wraper">
        <div class="container-fluid p-0">
            <form id="edit_purchase_form" action="{{ route('purchases.update', $purchase->id) }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" id="previous_paid" value="{{ $purchase->paid }}">
                <section class="mt-5x">
                    <div class="sec-name">
                        <h6>@lang('menu.edit_purchase')</h6>
                        <x-back-button />
                    </div>
                </section>

                <div class="p-15">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element rounded mb-1 mt-0">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-xl-3 col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><span class="text-danger">*</span>
                                                    <b>@lang('menu.supplier') </b></label>
                                                <div class="col-8">
                                                    <div class="input-group select-customer-input-group">
                                                        <div class="select-half">
                                                            <select required name="supplier_account_id" class="form-control select2 form-select" id="supplier_account_id" data-next="date">
                                                                <option value="">@lang('menu.select_supplier')</option>
                                                                @foreach ($supplierAccounts as $supplierAccount)
                                                                    <option {{ $purchase->supplier_account_id == $supplierAccount->id ? 'SELECTED' : '' }} value="{{ $supplierAccount->id }}">
                                                                        {{ $supplierAccount->name . '/' . $supplierAccount->phone }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div style="display: inline-block;" class="style-btn">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text add_button {{ !auth()->user()->can('supplier_add')? 'disabled_element': '' }}" id="addSupplier"><i class="fas fa-plus-square text-dark"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span class="error error_supplier_acccount_id"></span>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                @php
                                                    $accountUtil = new App\Utils\AccountUtil();
                                                    $amounts = $accountUtil->accountClosingBalance($purchase->supplier_account_id);
                                                @endphp
                                                <label class="col-4 text-danger"><b>@lang('menu.closing_bal') </b></label>
                                                <div class="col-8">
                                                    <input readonly type="text" class="form-control fw-bold" id="closing_balance" value="{{ $amounts['closing_balance_string'] }}" placeholder="0.00" tabindex="-1">
                                                    <input readonly type="hidden" id="debit_amount" value="{{ $amounts['curr_total_debit'] }}">
                                                    <input readonly type="hidden" id="credit_amount" value="{{ $amounts['curr_total_credit'] }}">
                                                    <input readonly type="hidden" id="current_supplier_account_id" value="{{ $purchase->supplier_account_id }}">
                                                    <input readonly type="hidden" id="current_invoice_amount" value="{{ $purchase->total_purchase_amount }}">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b><span class="text-danger">*</span> @lang('menu.date') </b></label>
                                                <div class="col-8">
                                                    <input type="text" name="date" class="form-control" id="date" value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($purchase->date)) }}" data-next="requisition_no">
                                                    <span class="error error_date"></span>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('menu.req_no') </b></label>
                                                <div class="col-8">
                                                    <input type="text" name="requisition_no" id="requisition_no" class="form-control fw-bold" placeholder="Search Requistion" value="{{ $purchase->requisition ? $purchase->requisition->requisition_no : '' }}" data-next="purchase_account_id" autocomplete="off">
                                                    <div class="invoice_search_result display-none">
                                                        <ul id="requisition_list" class="list-unstyled">

                                                        </ul>
                                                    </div>
                                                    <input type="hidden" name="requisition_id" id="requisition_id" value="{{ $purchase->requisition_id }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.p_invoice_id') </b></label>
                                                <div class="col-8">
                                                    <input readonly type="text" name="invoice_id" id="invoice_id" class="form-control fw-bold" value="{{ $purchase->invoice_id }}" data-next="purchase_account_id">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b><span class="text-danger">*</span>
                                                        @lang('menu.purchase_ac')</b></label>
                                                <div class="col-8">
                                                    <select name="purchase_account_id" class="form-control select2 form-select" id="purchase_account_id" data-next="purchase_account_id">
                                                        @foreach ($purchaseAccounts as $purchaseAccount)
                                                            <option {{ $purchaseAccount->id == $purchase->purchase_account_id ? 'SELECTED' : '' }} value="{{ $purchaseAccount->id }}">
                                                                {{ $purchaseAccount->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_purchase_account_id"></span>
                                                </div>
                                            </div>

                                            @if (!$purchase->receive_stock_id)

                                                @if ($purchase->warehouse_id)
                                                    <input name="warehouse_count" value="YES" type="hidden" />
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b></b><span class="text-danger">*</span>
                                                            @lang('menu.warehouse') </label>
                                                        <div class="col-8">
                                                            <select required class="form-control form-select" name="warehouse_id" id="warehouse_id" data-next="challan_no">
                                                                <option value="">@lang('menu.select_warehouse')</option>
                                                                @foreach ($warehouses as $warehouse)
                                                                    <option {{ $purchase->warehouse_id == $warehouse->id ? 'SELECTED' : '' }} value="{{ $warehouse->id }}">
                                                                        {{ $warehouse->warehouse_name . '/' . $warehouse->warehouse_code }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <span class="error error_warehouse_id"></span>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('menu.b_location') </b> </label>
                                                        <div class="col-8">
                                                            <input readonly type="text" class="form-control" value="{{ json_decode($generalSettings->business, true)['shop_name'] }}">
                                                        </div>
                                                    </div>
                                                @endif
                                            @else
                                                @if ($purchase->warehouse_id)

                                                    <input name="warehouse_count" value="YES" type="hidden" />
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b></b><span class="text-danger">*</span>
                                                            @lang('menu.warehouse') </label>
                                                        <div class="col-8">
                                                            <select class="form-control form-select" name="warehouse_id" id="warehouse_id" data-next="challan_no">
                                                                <option value="">@lang('menu.select_warehouse')</option>
                                                                @php
                                                                    $receivedStockWarehouseId = $purchase?->receiveStock?->warehouse_id;
                                                                @endphp
                                                                @foreach ($warehouses as $warehouse)
                                                                    <option {{ $receivedStockWarehouseId == $warehouse->id ? 'SELECTED' : '' }} value="{{ $warehouse->id }}">
                                                                        {{ $warehouse->warehouse_name . '/' . $warehouse->warehouse_code }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <span class="error error_warehouse_id"></span>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('menu.b_location') </b> </label>
                                                        <div class="col-8">
                                                            <input readonly type="text" class="form-control" value="{{ json_decode($generalSettings->business, true)['shop_name'] }}">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>

                                        <div class="col-xl-3 col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.challan_no') </b></label>
                                                <div class="col-8">
                                                    <input type="text" name="challan_no" id="challan_no" class="form-control" placeholder="Supplier Challan No" value="{{ $purchase->challan_no }}" data-next="challan_date" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('menu.challan_date')</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="challan_date" id="challan_date" class="form-control" value="{{ $purchase->challan_date ? date(json_decode($generalSettings->business, true)['date_format'], strtotime($purchase->challan_date)) : '' }}" data-next="recieve_stock_voucher" placeholder="Supplier Challan Date" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('menu.rs_voucher') </b></label>
                                                <div class="col-8">
                                                    <input type="text" name="recieve_stock_voucher" id="recieve_stock_voucher" class="form-control fw-bold" value="{{ $purchase->receiveStock ? $purchase->receiveStock->voucher_no : '' }}" data-next="vehicle_no" placeholder="Search Receive Stock Voucher" autocomplete="off">
                                                    <div class="receive_stock_search_result display-none">
                                                        <ul id="receive_stock_list" class="list-unstyled"></ul>
                                                    </div>
                                                    <input type="hidden" name="receive_stock_id" id="receive_stock_id" value="{{ $purchase->receive_stock_id }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.vehicle_no') </b></label>
                                                <div class="col-8">
                                                    <input type="text" name="vehicle_no" id="vehicle_no" class="form-control" value="{{ $purchase->vehicle_no }}" data-next="net_weight" placeholder="@lang('menu.vehicle_no')" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('menu.net_weight') </b></label>
                                                <div class="col-8">
                                                    <input type="number" step="any" name="net_weight" class="form-control" value="{{ $purchase->net_weight }}" data-next="weight_challan" placeholder="@lang('menu.net_weight')" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('menu.wt_challan')</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="weight_challan" id="weight_challan" class="form-control fw-bold" value="{{ $purchase->purchaseByScale ? $purchase->purchaseByScale->voucher_no : '' }}" data-next="search_product" placeholder="@lang('menu.weight_challan')/@lang('menu.voucher_no')" autocomplete="off">
                                                    <input type="hidden" name="purchase_by_scale_id" id="purchase_by_scale_id" value="{{ $purchase->purchase_by_scale_id }}">
                                                    <div class="weight_challan_search_result display-none">
                                                        <ul id="weight_challan_list" class="list-unstyled"></ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <section>
                        <div class="sale-content mb-1">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="item-details-sec">
                                        <div class="content-inner">
                                            <div class="row g-xxl-4 g-3">
                                                <div class="col-md-12">
                                                    <div class="row align-items-end g-2">
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

                                                        <div class="col-xl-4 col-md-5">
                                                            <div class="searching_area" style="position: relative;">
                                                                <label class="fw-bold">@lang('menu.search_item')</label>
                                                                <div class="input-group">
                                                                    <input type="text" @disabled($purchase->requisition_id || $purchase->receive_stock_id) name="search_product" class="form-control fw-bold" autocomplete="off" id="search_product" onkeyup="event.preventDefault();" placeholder="@lang('menu.search_item_item_code_scan_bar_code')" autofocus>

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

                                                        <div class="col-xl-2 col-md-4">
                                                            <label class="fw-bold">@lang('menu.quantity')</label>
                                                            <div class="input-group">
                                                                <input {{ $purchase->receive_stock_id || $purchase->purchase_by_scale_id ? 'readonly' : '' }} type="number" step="any" class="form-control w-60 fw-bold" id="e_showing_quantity" value="0.00" placeholder="0.00" autocomplete="off">
                                                                <input type="hidden" step="any" id="e_quantity" value="0.00">
                                                                <select id="e_unit_id" class="form-control w-40 form-select">
                                                                    <option value="">@lang('menu.unit')</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-2 col-md-4">
                                                            <label class="fw-bold">@lang('menu.unit_cost_exc_tax')</label>
                                                            <input type="number" step="any" class="form-control fw-bold" id="e_showing_unit_cost_exc_tax" value="0.00" placeholder="0.00" autocomplete="off">
                                                            <input type="hidden" id="e_unit_cost_exc_tax" value="0.00">
                                                        </div>

                                                        <div class="col-xl-2 col-md-4">
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

                                                        <div class="col-xl-2 col-md-4">
                                                            <label class="fw-bold">@lang('menu.tax')</label>
                                                            <div class="input-group">
                                                                <select id="e_tax_ac_id" class="form-control w-50 form-select">
                                                                    <option data-product_tax_percent="0.00" value="">@lang('menu.no_tax')</option>
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

                                                        <div class="col-xl-2 col-md-4">
                                                            <label class="fw-bold">@lang('menu.line_total')</label>
                                                            <input readonly type="number" step="any" class="form-control fw-bold" id="e_linetotal" value="0.00" placeholder="0.00" tabindex="-1">
                                                        </div>

                                                        <div class="col-xl-2 col-md-4">
                                                            <label class="fw-bold">@lang('menu.lot_number')</label>
                                                            <input type="text" step="any" class="form-control fw-bold" id="e_lot_number" placeholder="@lang('menu.lot_number')" autocomplete="off">
                                                        </div>

                                                        <div class="col-xl-4 col-md-4">
                                                            <label class="fw-bold">@lang('menu.short_description')</label>
                                                            <input type="text" step="any" class="form-control fw-bold" id="e_description" placeholder="@lang('menu.short_description')" autocomplete="off">
                                                        </div>

                                                        @if (json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1')
                                                            <div class="col-xl-2 col-md-4">
                                                                <label class="fw-bold">@lang('menu.profit_margin')</label>
                                                                <input type="number" step="any" class="form-control fw-bold" id="e_profit_margin" value="0.00" placeholder="0.00" autocomplete="off">
                                                            </div>

                                                            <div class="col-xl-2 col-md-4">
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
                                                                        <th>@lang('menu.unit_cost_exc_tax')</th>
                                                                        <th>@lang('menu.discount')</th>
                                                                        <th>@lang('menu.unit_tax')</th>
                                                                        <th>@lang('menu.net') @lang('menu.unit_cost')
                                                                            (@lang('menu.inc_tax'))</th>
                                                                        <th>@lang('menu.line_total')</th>
                                                                        @if (json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1')
                                                                            <th>@lang('menu.profit_margin')(%)</th>
                                                                            <th>@lang('menu.selling_price_exc_tax')</th>
                                                                        @endif
                                                                        <th><i class="fas fa-trash-alt"></i></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="purchase_list">
                                                                    @php
                                                                        $itemUnitsArray = [];
                                                                    @endphp
                                                                    @foreach ($purchase->purchaseProducts as $purchaseProduct)
                                                                        @php
                                                                            if ($purchase->receive_stock_id || $purchase->purchase_by_scale_id) {
                                                                                if (isset($purchaseProduct->product_id)) {
                                                                                    $itemUnitsArray[$purchaseProduct->product_id][] = [
                                                                                        'unit_id' => $purchaseProduct->unit_id,
                                                                                        'unit_name' => $purchaseProduct->purchaseUnit->name,
                                                                                        'unit_code_name' => $purchaseProduct->purchaseUnit->code_name,
                                                                                        'base_unit_multiplier' => $purchaseProduct->purchaseUnit->base_unit_multiplier ? $purchaseProduct->purchaseUnit->base_unit_multiplier : 1,
                                                                                        'multiplier_details' => '',
                                                                                        'is_base_unit' => $purchaseProduct->purchaseUnit->base_unit_multiplier ? 0 : 1,
                                                                                    ];
                                                                                }
                                                                            } else {
                                                                                if (isset($purchaseProduct->product_id)) {
                                                                                    $itemUnitsArray[$purchaseProduct->product_id][] = [
                                                                                        'unit_id' => $purchaseProduct->product->unit->id,
                                                                                        'unit_name' => $purchaseProduct->product->unit->name,
                                                                                        'unit_code_name' => $purchaseProduct->product->unit->code_name,
                                                                                        'base_unit_multiplier' => 1,
                                                                                        'multiplier_details' => '',
                                                                                        'is_base_unit' => 1,
                                                                                    ];
                                                                                }

                                                                                if (count($purchaseProduct?->product?->unit?->childUnits) > 0) {
                                                                                    foreach ($purchaseProduct?->product?->unit?->childUnits as $unit) {
                                                                                        $multiplierDetails = '(1 ' . $unit->name . ' = ' . $unit->base_unit_multiplier . '/' . $purchaseProduct?->product?->unit?->name . ')';

                                                                                        array_push($itemUnitsArray[$purchaseProduct->product_id], [
                                                                                            'unit_id' => $unit->id,
                                                                                            'unit_name' => $unit->name,
                                                                                            'unit_code_name' => $unit->code_name,
                                                                                            'base_unit_multiplier' => $unit->base_unit_multiplier,
                                                                                            'multiplier_details' => $multiplierDetails,
                                                                                            'is_base_unit' => 0,
                                                                                        ]);
                                                                                    }
                                                                                }
                                                                            }

                                                                            $variant = $purchaseProduct->variant ? ' - ' . $purchaseProduct->variant->variant_name : '';
                                                                            $variantId = $purchaseProduct->product_variant_id ? $purchaseProduct->product_variant_id : 'noid';
                                                                        @endphp

                                                                        <tr id="select_item">
                                                                            <td>
                                                                                <span id="span_item_name">{{ $purchaseProduct->product->name . $variant }}</span>

                                                                                <input type="hidden" id="item_name" value="{{ $purchaseProduct->product->name . $variant }}">
                                                                                <input type="hidden" name="descriptions[]" id="description" value="{{ $purchaseProduct->description }}">
                                                                                <input type="hidden" name="product_ids[]" id="product_id" value="{{ $purchaseProduct->product_id }}">
                                                                                <input type="hidden" name="variant_ids[]" id="variant_id" value="{{ $variantId }}">
                                                                                <input type="hidden" name="purchase_product_ids[]" value="{{ $purchaseProduct->id }}">
                                                                                <input type="hidden" id="{{ $purchaseProduct->product_id . $variantId }}" value="{{ $purchaseProduct->product_id . $variantId }}">
                                                                            </td>

                                                                            <td>
                                                                                @php
                                                                                    $baseUnitMultiplier = $purchaseProduct?->purchaseUnit?->base_unit_multiplier ? $purchaseProduct?->purchaseUnit?->base_unit_multiplier : 1;
                                                                                    $purchasedQty = $purchaseProduct->quantity / $baseUnitMultiplier;
                                                                                @endphp
                                                                                <span id="span_showing_quantity_unit" class="fw-bold">{{ $purchasedQty . '/' . $purchaseProduct?->purchaseUnit?->name }}</span>
                                                                                <input type="hidden" step="any" id="showing_quantity" value="{{ $purchasedQty }}">
                                                                                <input type="hidden" name="quantities[]" id="quantity" value="{{ $purchaseProduct->quantity }}">
                                                                                <input type="hidden" name="unit_ids[]" step="any" id="unit_id" value="{{ $purchaseProduct?->unit_id }}">
                                                                                @if (json_decode($generalSettings->purchase, true)['is_enable_lot_no'] == '1')
                                                                                    <p class="p-0 m-0 fw-bold">
                                                                                        @lang('menu.lot_no') : <span id="span_lot_number">{{ $purchaseProduct?->lot_number }}</span>
                                                                                        <input type="hidden" name="lot_numbers[]" id="lot_number" value="{{ $purchaseProduct?->lot_number }}">
                                                                                @endif
                                                                            </td>

                                                                            <td>
                                                                                <span id="span_showing_unit_cost_exc_tax" class="fw-bold">{{ bcadd($purchaseProduct->unit_cost * $baseUnitMultiplier, 0, 2) }}</span>
                                                                                <input type="hidden" name="unit_costs_exc_tax[]" id="unit_cost_exc_tax" value="{{ $purchaseProduct->unit_cost }}">
                                                                                <input type="hidden" id="showing_unit_cost_exc_tax" value="{{ bcadd($purchaseProduct->unit_cost * $baseUnitMultiplier, 0, 2) }}">
                                                                            </td>

                                                                            <td>
                                                                                @php
                                                                                    $showingUnitDiscount = $purchaseProduct->unit_discount_type == 1 ? $purchaseProduct->unit_discount * $baseUnitMultiplier : $purchaseProduct->unit_discount;
                                                                                @endphp
                                                                                <span id="span_showing_discount_amount" class="fw-bold">{{ bcadd($showingUnitDiscount, 0, 2) }}</span>
                                                                                <input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="{{ $purchaseProduct->unit_discount_type }}">
                                                                                <input type="hidden" name="unit_discounts[]" id="unit_discount" value="{{ $purchaseProduct->unit_discount }}">
                                                                                <input type="hidden" id="showing_unit_discount" value="{{ bcadd($purchaseProduct->unit_discount * $baseUnitMultiplier, 0, 2) }}">
                                                                                <input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="{{ $purchaseProduct->unit_discount_amount }}">
                                                                                <input type="hidden" id="showing_unit_discount_amount" value="{{ bcadd($purchaseProduct->unit_discount_amount * $baseUnitMultiplier, 0, 2) }}">

                                                                                <input type="hidden" name="unit_costs_with_discount[]" id="unit_cost_with_discount" value="{{ $purchaseProduct->unit_cost_with_discount }}">
                                                                                <input type="hidden" id="showing_unit_cost_with_discount" value="{{ bcadd($purchaseProduct->unit_cost_with_discount * $baseUnitMultiplier, 0, 2) }}">
                                                                                <input type="hidden" name="subtotals[]" id="subtotal" value="{{ $purchaseProduct->subtotal }}">
                                                                            </td>

                                                                            <td>
                                                                                <span id="span_tax_percent" class="fw-bold">{{ $purchaseProduct->unit_tax_percent . '%' }}</span>
                                                                                <input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="{{ $purchaseProduct->tax_ac_id }}">
                                                                                <input type="hidden" name="tax_types[]" id="tax_type" value="{{ $purchaseProduct->tax_type }}">
                                                                                <input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="{{ $purchaseProduct->unit_tax_percent }}">
                                                                                <input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="{{ $purchaseProduct->unit_tax_amount }}">
                                                                                <input type="hidden" id="showing_unit_tax_amount" value="{{ bcadd($purchaseProduct->unit_tax_amount * $baseUnitMultiplier, 0, 2) }}">
                                                                            </td>

                                                                            <td>
                                                                                <span id="span_showing_unit_cost_inc_tax" class="fw-bold">{{ bcadd($purchaseProduct->net_unit_cost * $baseUnitMultiplier, 0, 2) }}</span>
                                                                                <input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="{{ bcadd($purchaseProduct->net_unit_cost, 0, 2) }}">
                                                                                <input type="hidden" id="showing_unit_cost_inc_tax" value="{{ bcadd($purchaseProduct->net_unit_cost * $baseUnitMultiplier, 0, 2) }}">
                                                                                <input type="hidden" name="net_unit_costs[]" id="net_unit_cost" value="{{ bcadd($purchaseProduct->net_unit_cost, 0, 2) }}">
                                                                            </td>

                                                                            <td>
                                                                                <span id="span_linetotal" class="fw-bold">{{ bcadd($purchaseProduct->line_total, 0, 2) }}</span>
                                                                                <input type="hidden" name="linetotals[]" id="linetotal" value="{{ bcadd($purchaseProduct->line_total, 0, 2) }}">
                                                                            </td>

                                                                            @if (json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1')
                                                                                <td>
                                                                                    <span id="span_profit" class="fw-bold">{{ $purchaseProduct->profit_margin }}</span>
                                                                                    <input type="hidden" name="profits[]" id="profit" value="{{ $purchaseProduct->profit_margin }}">
                                                                                </td>

                                                                                <td>
                                                                                    <span id="span_showing_selling_price" class="fw-bold">{{ bcadd($purchaseProduct->selling_price * $baseUnitMultiplier, 0, 2) }}</span>
                                                                                    <input type="hidden" name="selling_prices[]" id="selling_price" value="{{ bcadd($purchaseProduct->selling_price, 0, 2) }}">
                                                                                    <input type="hidden" id="showing_selling_price" value="{{ bcadd($purchaseProduct->selling_price * $baseUnitMultiplier, 0, 2) }}">
                                                                                </td>
                                                                            @endif

                                                                            @if ($purchase->receive_stock_id || $purchase->purchase_by_scale_id)
                                                                                <td class="text-start"><i class="fas fa-trash-alt text-secondary"></i>
                                                                                </td>
                                                                            @else
                                                                                <td>
                                                                                    <a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>
                                                                                </td>
                                                                            @endif
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
                    </section>

                    <section>
                        <div class="row g-1 mb-1">
                            <div class="col-lg-6">
                                <div class="form_element rounded m-0">
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row g-1">
                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <label class="col-4"><strong>@lang('menu.additional_expenses')</strong>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <label class="col-4"><b>@lang('menu.labour_cost') </b> </label>
                                                            <div class="col-8">
                                                                <input type="number" step="any" name="labour_cost" class="form-control add_ex fw-bold" id="labour_cost" value="{{ $purchase->labour_cost }}" data-next="transport_cost" placeholder="0.00">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <label class="col-4"><b>@lang('menu.transport_cost') </b></label>
                                                            <div class="col-8">
                                                                <input type="number" step="any" name="transport_cost" class="form-control add_ex fw-bold" id="transport_cost" value="{{ $purchase->transport_cost }}" data-next="scale_charge" placeholder="0.00">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <label class="col-4"><b>@lang('menu.scale_charge') </b> </label>
                                                            <div class="col-8">
                                                                <input type="number" step="any" name="scale_charge" class="form-control add_ex fw-bold" id="scale_charge" value="{{ $purchase->scale_charge }}" data-next="others" placeholder="0.00">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <label class="col-4"><b>@lang('menu.others') </b> </label>
                                                            <div class="col-8">
                                                                <input type="number" step="any" name="others" class="form-control add_ex fw-bold" id="others" value="{{ $purchase->others }}" data-next="expense_credit_account_id" placeholder="0.00">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <label class="col-4 text-danger"><strong>@lang('menu.total_additional_expense')
                                                                </strong> </label>
                                                            <div class="col-8">
                                                                <input readonly type="text" name="total_additional_expense" step="any" class="form-control fw-bold" id="total_additional_expense" value="{{ $purchase->total_additional_expense }}" tabindex="-1">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form_element rounded m-0">
                                    <div class="element-body">
                                        <div class="row g-1">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label class="col-4"><strong>@lang('menu.expenses_payment') </strong> </label>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.expense_credit_ac') <span class="text-danger">*</span></b> </label>
                                                    <div class="col-8">
                                                        <select name="expense_credit_account_id" class="form-control select2 form-select" id="expense_credit_account_id" data-next="expense_payment_method_id">
                                                            <option value="">@lang('menu.select_expense_payment_ac')</option>
                                                            @foreach ($accounts as $account)
                                                                <option value="{{ $account->id }}" {{ $purchase?->expense?->singleModeCreditDescription?->account_id == $account->id ? 'SELECTED' : '' }}>
                                                                    @php
                                                                        $bank = $account->bank ? ', BK : ' . $account->bank : '';
                                                                        $ac_no = $account->account_number ? ', A/c No : ' . $account->account_number : '';
                                                                    @endphp
                                                                    {{ $account->name . $bank . $ac_no }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="error error_expense_credit_account_id"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('menu.expense_pay_method') <span class="text-danger">*</span></b> </label>
                                                <div class="col-8">
                                                    <select required name="expense_payment_method_id" class="form-control form-select" id="expense_payment_method_id" data-next="expense_transaction_no">
                                                        @foreach ($methods as $method)
                                                            <option value="{{ $method->id }}" {{ $purchase?->expense?->singleModeCreditDescription?->payment_method_id == $method->id ? 'SELECTED' : '' }}>
                                                                {{ $method->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_payment_method_id"></span>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label class="col-4"><strong>@lang('menu.transaction_no')</strong></label>
                                                    <div class="col-8">
                                                        <input type="text" step="any" name="expense_transaction_no" class="form-control" id="expense_transaction_no" data-next="expense_cheque_no" value="{{ $purchase?->expense?->singleModeCreditDescription?->transaction_no }}" placeholder="Transaction Number For Expense" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label class="col-4"><strong>@lang('menu.cheque_no')</strong></label>
                                                    <div class="col-8">
                                                        <input type="text" step="any" name="expense_cheque_no" class="form-control" id="expense_cheque_no" value="{{ $purchase?->expense?->singleModeCreditDescription?->cheque_no }}" placeholder="Cheque Number For Expense" data-next="expense_note" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.expense_note') </b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="expense_note" class="form-control" id="expense_note" data-next="order_discount" value="{{ $purchase?->expense?->note }}" placeholder="@lang('menu.expense_note')">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section>
                        <div class="row g-1 mb-1">
                            <div class="col-lg-6">
                                <div class="form_element m-0 rounded">
                                    <div class="element-body">
                                        <div class="row g-1">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.total_item') </b></label>
                                                    <div class="col-8">
                                                        <input readonly name="total_item" step="any" type="number" class="form-control fw-bold" id="total_item" value="{{ $purchase->total_item }}">
                                                        <input name="total_qty" type="hidden" id="total_qty" value="{{ $purchase->total_qty }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.net_total_amount') </b></label>
                                                    <div class="col-8">
                                                        <input readonly name="net_total_amount" type="number" step="any" id="net_total_amount" class="form-control fw-bold" value="{{ $purchase->net_total_amount }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.purchase_discount') </b></label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <input name="order_discount" type="number" class="form-control fw-bold" id="order_discount" value="{{ $purchase->order_discount }}" data-next="order_discount_type">

                                                            <select name="order_discount_type" class="form-control form-select" id="order_discount_type" data-next="purchase_tax_ac_id">
                                                                <option {{ $purchase->order_discount_type == 1 ? 'SELECTED' : '' }} value="1">@lang('menu.fixed')(0.0)</option>
                                                                <option {{ $purchase->order_discount_type == 2 ? 'SELECTED' : '' }} value="2">@lang('menu.percentage')(%)</option>
                                                            </select>
                                                        </div>

                                                        <input name="order_discount_amount" type="number" step="any" class="d-none" id="order_discount_amount" value="{{ $purchase->order_discount_amount }}" tabindex="-1">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.purchase_tax') </b><span class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <select name="purchase_tax_ac_id" class="form-control form-select" id="purchase_tax_ac_id" data-next="shipment_charge">
                                                            <option data-purchase_tax_percent="0.00" value="">
                                                                @lang('menu.no_tax')</option>
                                                            @foreach ($taxAccounts as $taxAccount)
                                                                <option {{ $taxAccount->id == $purchase->tax_ac_id ? 'SELECTED' : '' }} data-purchase_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                                    {{ $taxAccount->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        <input type="number" step="any" name="purchase_tax_percent" class="d-none" id="purchase_tax_percent" value="{{ $purchase->purchase_tax_percent }}">
                                                        <input type="number" step="any" name="purchase_tax_amount" class="d-none" id="purchase_tax_amount" value="{{ $purchase->purchase_tax_amount }}" tabindex="-1">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.shipment_cost') </b> </label>
                                                    <div class="col-8">
                                                        <input name="shipment_charge" type="number" class="form-control fw-bold" id="shipment_charge" value="{{ $purchase->shipment_charge }}" data-next="ait_deduction">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('short.a_l_t_eduction') </b></label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <input type="number" step="any" name="ait_deduction" class="form-control fw-bold" id="ait_deduction" value="{{ $purchase->ait_deduction }}" data-next="ait_deduction_type">
                                                            <select name="ait_deduction_type" class="form-control form-select" id="ait_deduction_type" data-next="purchase_note">
                                                                <option {{ $purchase->ait_deduction_type == 1 ? 'SELECTED' : '' }} value="1">@lang('menu.fixed')(0.00)</option>
                                                                <option {{ $purchase->ait_deduction_type == 2 ? 'SELECTED' : '' }} value="2">@lang('menu.percentage')(%)</option>
                                                            </select>
                                                        </div>

                                                        <input name="ait_deduction_amount" type="number" step="any" class="d-none" id="ait_deduction_amount" value="{{ $purchase->ait_deduction_amount }}" tabindex="-1">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.purchase_not') </b></label>
                                                    <div class="col-8">
                                                        <input type="text" name="purchase_note" id="purchase_note" class="form-control" value="{{ $purchase->purchase_note }}" data-next="payment_note" placeholder="@lang('menu.purchase_not').">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label class=" col-4"><b>@lang('menu.payment_note') </b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="payment_note" class="form-control" id="payment_note" data-next="paying_amount" placeholder="Payment note" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form_element m-0 rounded">
                                    <div class="element-body">
                                        <div class="row g-1">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('short.total_add_expense_item') </b> </label>
                                                    <div class="col-8">
                                                        <input readonly name="total_expense_with_item" type="number" class="form-control fw-bold" id="total_expense_with_item" value="{{ $purchase->total_expense_with_item }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label class="col-4 text-danger"><strong>@lang('menu.total_invoice_amount')
                                                        </strong></label>
                                                    <div class="col-8">
                                                        <input readonly type="number" step="any" name="total_invoice_amount" id="total_invoice_amount" class="form-control fw-bold" value="{{ $purchase->total_purchase_amount }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label class="col-4"><strong>@lang('menu.paying_amount') >></strong></label>
                                                    <div class="col-8">
                                                        <input {{ !auth()->user()->can('payments_add')? 'readonly': '' }} type="number" step="any" name="paying_amount" class="form-control fw-bold" id="paying_amount" value="0.00" data-next="payment_method_id" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.payment_method') <span class="text-danger">*</span></b> </label>
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
                                                    <label class="col-4"><b>@lang('menu.credit_account') <span class="text-danger">*</span></b> </label>
                                                    <div class="col-8">
                                                        <select name="account_id" class="form-control select2 form-select" id="account_id" data-next="transaction_no">
                                                            <option value="">@lang('menu.select_credit_ac')</option>
                                                            @foreach ($accounts as $account)
                                                                <option value="{{ $account->id }}">
                                                                    @php
                                                                        $bank = $account->bank ? ', BK : ' . $account->bank : '';
                                                                        $ac_no = $account->account_number ? ', A/c No : ' . $account->account_number : '';
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
                                                <div class="input-group mt-1">
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
                                                        <input type="text" step="any" name="cheque_no" class="form-control" id="cheque_no" data-next="save" placeholder="Cheque Number" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label class="col-4 text-danger"><strong>@lang('menu.curr_balance')
                                                        </strong></label>
                                                    <div class="col-8">
                                                        <input readonly type="text" class="form-control fw-bold" id="current_balance" value="{{ $amounts['closing_balance_string'] }}" tabindex="-1">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="submit_button_area">
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="loading-btn-box">
                                    <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                                    <button type="button" id="save" class="btn btn-success submit_button float-end">@lang('menu.save_change')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <x-shortcut-key-bar.shortcut-key-bar :items="[['key' => 'Ctrl + Enter', 'value' => __('menu.save_changes')], ['key' => 'Alt + S', 'value' => __('menu.add_supplier')], ['key' => 'Alt + I', 'value' => __('menu.add_item')]]">
    </x-shortcut-key-bar.shortcut-key-bar>

    @if (auth()->user()->can('product_add'))
        <!--Add Quick Product Modal-->
        <div class="modal fade" id="addQuickProductModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop">
            <div class="modal-dialog four-col-modal" role="document" id="quick_product_add_modal_contant"></div>
        </div>
        <!--Add Quick Product Modal End-->

        <div class="modal fade" id="unitAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
        <div class="modal fade" id="brandAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
        <div class="modal fade" id="warrantyAddOrEditModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
        <div class="modal fade" id="categoryAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
        <div class="modal fade" id="subcategoryAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
    @endif

    @if (auth()->user()->can('supplier_add'))
        <!-- Add Supplier Modal -->
        <div class="modal fade" id="add_supplier_basic_modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
        <div class="modal fade" id="add_supplier_detailed_modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
    @endif

@endsection
@push('scripts')
    @include('procurement.partials.purchaseEditJsScript')
    <script>
        $('.select2').select2();
        var itemUnitsArray = @json($itemUnitsArray);

        var ul = document.getElementById('list');
        var selectObjClassName = 'selectProduct';

        $('#requisition_no').mousedown(function(e) {

            afterClickOrFocusRequisitionNo();
        }).focus(function(e) {

            ul = document.getElementById('requisition_list')
            selectObjClassName = 'selected_requisition';
        });

        $('#weight_challan').mousedown(function(e) {

            afterClickOrFocusWeightChallanNo();
        }).focus(function(e) {

            ul = document.getElementById('weight_challan_list')
            selectObjClassName = 'selected_challan';
        });

        $('#recieve_stock_voucher').mousedown(function(e) {

            afterClickOrFocusReceiveStockVoucher();
        }).focus(function(e) {

            ul = document.getElementById('receive_stock_list')
            selectObjClassName = 'selected_receive_stock';
        });

        function afterClickOrFocusRequisitionNo() {

            ul = document.getElementById('requisition_list')
            selectObjClassName = 'selected_requisition';
            $('#requisition_no').val('');
            $('#weight_challan').val('');
            $('#requisition_id').val('');
            $('#recieve_stock_voucher').val('');
            $('#challan_no').val('');
            $('#vehicle_no').val('');
            $('#net_weight').val('');
            $('#challan_date').val('');
            $('#purchase_by_scale_id').val('');
            $('#receive_stock_id').val('');

            $('.weight_challan_search_result').hide();
            $('.receive_stock_search_result').hide();
            $('.select_area').hide();

            $('#purchase_list').empty();
            $('#weight_challan_list').empty();
            $('#receive_stock_list').empty();
            $('#list').empty();

            $('#search_product').prop('disabled', false);
            $('#e_showing_quantity').prop('readonly', false);
            $('#warehouse_id').prop('required', true);
            calculateTotalAmount();
        }

        function afterClickOrFocusReceiveStockVoucher() {

            ul = document.getElementById('receive_stock_list')
            selectObjClassName = 'selected_receive_stock';
            $('#requisition_no').val('');
            $('#weight_challan').val('');
            $('#requisition_id').val('');
            $('#recieve_stock_voucher').val('');
            $('#challan_no').val('');
            $('#vehicle_no').val('');
            $('#net_weight').val('');
            $('#challan_date').val('');
            $('#purchase_by_scale_id').val('');
            $('#receive_stock_id').val('');

            $('.weight_challan_search_result').hide();
            $('.invoice_search_result').hide();
            $('.select_area').hide();

            $('#purchase_list').empty();
            $('#weight_challan_list').empty();
            $('#requisition_list').empty();
            $('#list').empty();

            $('#search_product').prop('disabled', false);
            $('#e_showing_quantity').prop('readonly', false);
            $('#warehouse_id').prop('required', true);
            calculateTotalAmount();
        }

        function afterClickOrFocusWeightChallanNo() {

            ul = document.getElementById('weight_challan_list')
            selectObjClassName = 'selected_challan';
            $('#weight_challan').val('');
            $('#requisition_no').val('');
            $('#recieve_stock_voucher').val('');
            $("#supplier_account_id").val('');
            $("#supplier_account_id").select2("destroy");
            $("#supplier_account_id").select2();
            $('#challan_no').val('');
            $('#vehicle_no').val('');
            $('#net_weight').val('');
            $('#challan_date').val('');
            $('#requisition_id').val('');
            $('#purchase_by_scale_id').val('');
            $('#receive_stock_id').val('');

            $('.invoice_search_result').hide();
            $('.receive_stock_search_result').hide();
            $('.select_area').hide();

            $('#purchase_list').empty();
            $('#requisition_list').empty();
            $('#receive_stock_list').empty();
            $('#list').empty();

            $('#search_product').prop('disabled', false);
            $('#e_showing_quantity').prop('readonly', false);
            $('#warehouse_id').prop('required', true);
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

                if ($('#requisition_id').val()) {

                    afterClickOrFocusRequisitionNo();
                }

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

        $('#recieve_stock_voucher').on('input', function() {

            $('.receive_stock_search_result').hide();

            var recieve_stock_voucher = $(this).val();

            if (recieve_stock_voucher === '') {

                if ($('#receive_stock_id').val()) {

                    afterClickOrFocusReceiveStockVoucher();
                }

                $('.receive_stock_search_result').hide();
                $('#receive_stock_id').val('');
                $('#search_product').prop('disabled', false);
                return;
            }

            var url = "{{ route('common.ajax.call.search.receive.stocks', ':recieve_stock_voucher') }}";
            var route = url.replace(':recieve_stock_voucher', recieve_stock_voucher);

            $.ajax({
                url: route,
                async: true,
                type: 'get',
                success: function(data) {

                    if (!$.isEmptyObject(data.noResult)) {

                        $('.receive_stock_search_result').hide();
                    } else {

                        if (recieve_stock_voucher) {

                            $('.receive_stock_search_result').show();
                            $('#receive_stock_list').html(data);
                        }
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
            var route = url.replace(':requisition_id', requisition_id);

            $.ajax({
                url: route,
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
                    clearEditItemFileds();
                }
            });
        });

        // Work will be running from here...
        $(document).on('click', '#selected_receive_stock', function(e) {
            e.preventDefault();

            if ($(this).data('is_purchased') == 'purchased') {

                $('#recieve_stock_voucher').focus().select();
                toastr.error('Purchase invoice has already been created under this receive stock voucher.');
                return;
            }

            var receive_stock_voucher = $(this).html();
            var receive_stock_id = $(this).data('id');

            var supplier_account_id = $(this).data('supplier_account_id');
            var challan_no = $(this).data('challan_no');
            var challan_date = $(this).data('challan_date');
            var net_weight = $(this).data('net_weight');
            var vehicle_no = $(this).data('vehicle_no');

            var url = "{{ route('common.ajax.call.get.receive.stock.products', ':receive_stock_id') }}";
            var route = url.replace(':receive_stock_id', receive_stock_id)

            $.ajax({
                url: route,
                type: 'get',
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        $('#recieve_stock_voucher').focus().select();
                        return;
                    }

                    itemUnitsArray = jQuery.parseJSON(data.units);

                    $('#supplier_account_id').val(supplier_account_id).trigger('change');
                    $('#challan_no').val(challan_no);
                    $('#challan_date').val(challan_date);
                    $('#net_weight').val(net_weight);
                    $('#vehicle_no').val(vehicle_no);

                    $('#recieve_stock_voucher').val(receive_stock_voucher.trim());
                    $('#receive_stock_id').val(receive_stock_id);
                    $('#purchase_list').html(data.view);

                    $('#search_product').prop('disabled', true);
                    $('#e_showing_quantity').prop('readonly', true);
                    $('#warehouse_id').prop('required', false);
                    $('#receive_stock_list').empty();
                    $('.receive_stock_search_result').hide();

                    calculateTotalAmount();
                    clearEditItemFileds();
                }
            });
        });

        // Work will be running from here...
        $(document).on('click', '#selected_challan', function(e) {
            e.preventDefault();

            var purchase_by_scale_id = $(this).data('id');
            var challan_no = $(this).data('challan_no');

            var status = $(this).data('status');
            var supplier_account_id = $(this).data('supplier_account_id');
            var date = $(this).data('date');
            var challan_date = $(this).data('challan_date');
            var voucher_no = $(this).data('voucher_no');
            var vehicle_no = $(this).data('vehicle_number');
            var net_weight = $(this).data('net_weight');

            if (status == 0) {

                toastr.error('Weight scaling is running.');
                $('#weight_challan').focus().select();
                return;
            }

            var url = "{{ route('common.ajax.call.purchase.scale.product.list.for.purchase', ':purchase_by_scale_id') }}";
            var route = url.replace(':purchase_by_scale_id', purchase_by_scale_id);

            $.ajax({
                url: route,
                async: true,
                type: 'get',
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        $('#weight_challan').focus().select();
                        toastr.error(data.errorMsg);
                        return;
                    }

                    itemUnitsArray = jQuery.parseJSON(data.units);

                    $('#weight_challan').val(voucher_no);
                    $('#purchase_by_scale_id').val(purchase_by_scale_id);
                    $('#supplier_account_id').val(supplier_account_id).trigger('change');
                    $('#voucher_no').val(voucher_no);
                    $('#challan_no').val(challan_no);
                    $('#challan_date').val(challan_date);
                    $('#vehicle_no').val(vehicle_no);
                    $('#net_weight').val(parseFloat(net_weight).toFixed(2));
                    $('.weight_challan_search_result ').hide();
                    $('#weight_challan_list ').empty();

                    $('#purchase_list').empty();
                    $('#purchase_list').html(data.view);

                    $('#requisition_no').val('');
                    $('#requisition_id').val('');
                    $('#search_product').prop('disabled', true);
                    $('#e_showing_quantity').focus().select();

                    calculateTotalAmount();
                    clearEditItemFileds();
                }
            });
        });

        $(document).on('keyup', 'body', function(e) {

            if (e.keyCode == 13) {

                $('.' + selectObjClassName).click();
                $('.invoice_search_result').hide();
                $('.receive_stock_search_result').hide();
                $('.select_area').hide();
                $('.weight_challan_search_result').hide();

                $('#list').empty();
                $('#requisition_list').empty();
                $('#weight_challan_list').empty();
                $('#receive_stock_list').empty();
            }
        });

        $(document).on('click', function(e) {

            if (
                $(e.target).closest(".select_area").length === 0 ||
                $(e.target).closest(".invoice_search_result").length === 0 ||
                $(e.target).closest(".weight_challan_search_result").length === 0 ||
                $(e.target).closest(".receive_stock_search_result ").length === 0
            ) {

                $('.select_area').hide();
                $('.invoice_search_result').hide();
                $('.weight_challan_search_result').hide();
                $('.receive_stock_search_result').hide();
                $('#requisition_list').empty();
                $('#weight_challan_list').empty();
                $('#list').empty();
                $('#receive_stock_list').empty();
            }
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
                $('.weight_challan_search_result').hide();
                $('.receive_stock_search_result').hide();
                $('#requisition_list').empty();
                $('#weight_challan_list').empty();
                $('#list').empty();
                $('#receive_stock_list').empty();

                return false;
            }
        }

        //Add purchase request by ajax
        $('#edit_purchase_form').on('submit', function(e) {
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
    </script>

    <script>
        $('#weight_challan').on('input', function() {

            $('.weight_challan_list').hide();

            var key_word = $(this).val();

            if (key_word === '') {

                if ($('#purchase_by_scale_id').val()) {

                    afterClickOrFocusWeightChallanNo();
                }

                $('.weight_challan_search_result').hide();
                $('#purchase_by_scale_id').val('');
                return;
            }

            var url = "{{ route('common.ajax.call.purchase.weight.search.challan.list', ':key_word') }}";
            var route = url.replace(':key_word', key_word);

            $.ajax({
                url: route,
                async: true,
                type: 'get',
                success: function(data) {

                    if (!$.isEmptyObject(data.noResult)) {

                        $('.weight_challan_search_result').hide();
                    } else {

                        $('.weight_challan_search_result').show();
                        $('#weight_challan_list').html(data);
                    }
                }
            });
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

                if ($(this).attr('id') == 'weight_challan' && $('#weight_challan').val()) {

                    return;
                }

                if ($(this).attr('id') == 'others' && $('#total_additional_expense').val() == 0) {

                    $('#order_discount').focus().select();
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
            element: document.getElementById('challan_date'),
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
