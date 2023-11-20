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

        .po_search_result {
            position: absolute;
            width: 100%;
            border: 1px solid #E4E6EF;
            background: white;
            z-index: 1;
            padding: 3px;
            margin-top: 1px;
        }

        .po_search_result ul li {
            width: 100%;
            border: 1px solid lightgray;
            margin-top: 2px;
        }

        .po_search_result ul li a {
            color: #6b6262;
            font-size: 10px;
            display: block;
            padding: 0px 3px;
        }

        .po_search_result ul li a:hover {
            color: var(--white-color);
            background-color: #ada9a9;
        }

        .selected_po {
            background-color: #645f61;
            color: #fff !important;
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
            width: 137px;
        }

        .sale-item-sec {
            min-height: 220px;
        }
    </style>
@endpush
@section('content')
@section('title', 'Edit Stock Receive - ')
<div class="body-wraper">
    <div class="container-fluid p-0">
        <div class="sec-name">
            <h6>@lang('menu.edit_receive_stock')</h6>
            <x-back-button />
        </div>
        <div class="p-15">
            <form id="edit_receive_stock_form" action="{{ route('purchases.receive.stocks.update', $receiveStock->id) }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row g-0">
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <div class="row">
                                <div class="col-xl-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><span class="text-danger">*</span> <b>@lang('menu.supplier')</b></label>
                                        <div class="col-8">
                                            <div class="input-group select-customer-input-group">
                                                <select required name="supplier_account_id" class="form-control select2 form-select" id="supplier_account_id" data-next="requisition_no">
                                                    <option value="">@lang('menu.select_supplier')</option>
                                                    @foreach ($supplierAccounts as $supplierAccount)
                                                        <option {{ $receiveStock->supplier_account_id == $supplierAccount->id ? 'SELECTED' : '' }} value="{{ $supplierAccount->id }}">
                                                            {{ $supplierAccount->name . '(' . $supplierAccount->phone . ')' }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <div style="display: inline-block;margin-top:0px;" class="style-btn">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text add_button mr-1 {{ !auth()->user()->can('supplier_add')? 'disabled_element': '' }}" id="addSupplier"><i class="fas fa-plus-square text-dark"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="error error_supplier_account_id"></span>
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-4"><b>@lang('menu.req_no') </b></label>
                                        <div class="col-8">
                                            <input type="text" name="requisition_no" class="form-control fw-bold" id="requisition_no" value="{{ $receiveStock->requisition ? $receiveStock->requisition->requisition_no : '' }}" data-next="po_id" placeholder="Search Requistion" autocomplete="off">
                                            <div class="invoice_search_result display-none">
                                                <ul id="requisition_list" class="list-unstyled"></ul>
                                            </div>
                                            <input type="hidden" name="requisition_id" id="requisition_id" value="{{ $receiveStock->requisition_id }}">
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-4"><b>@lang('menu.po_id') </b></label>
                                        <div class="col-8">
                                            <input type="text" name="po_id" id="po_id" class="form-control fw-bold" value="{{ $receiveStock?->purchaseOrder?->invoice_id }}" data-next="challan_no" placeholder="Search Purchase order" autocomplete="off">
                                            <div class="po_search_result display-none">
                                                <ul id="po_list" class="list-unstyled"></ul>
                                            </div>
                                            <input type="hidden" name="purchase_order_id" id="purchase_order_id" value="{{ $receiveStock?->purchaseOrder?->id }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>@lang('menu.challan_no') </b></label>
                                        <div class="col-8">
                                            <input type="text" name="challan_no" id="challan_no" class="form-control" value="{{ $receiveStock->challan_no }}" data-next="challan_date" placeholder="Supplier Challan No" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-4"><b>@lang('menu.challan_date') </b></label>
                                        <div class="col-8">
                                            <input type="text" name="challan_date" id="challan_date" class="form-control" placeholder="Supplier Challan Date" value="{{ $receiveStock->challan_date }}" data-next="net_weight" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-4"><b>@lang('menu.weight_and_vehicle')</b></label>
                                        <div class="col-8">
                                            <div class="input-group">
                                                <input type="number" step="any" name="net_weight" class="form-control" id="net_weight" value="{{ $receiveStock->net_weight }}" data-next="vehicle_no" placeholder="Net Weight" autocomplete="off">
                                                <input type="text" name="vehicle_no" class="form-control" id="vehicle_no" value="{{ $receiveStock->vehicle_no }}" data-next="date" placeholder="Vehicle No." autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>@lang('menu.voucher_no') </b></label>
                                        <div class="col-8">
                                            <input readonly type="text" name="voucher_no" id="voucher_no" class="form-control fw-bold" placeholder="@lang('menu.voucher_no')" value="{{ $receiveStock->voucher_no }}" autocomplete="off">
                                            <span class="error error_voucher_no"></span>
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-4"><b><span class="text-danger">*</span> @lang('menu.date')
                                            </b></label>
                                        <div class="col-8">
                                            <input type="text" name="date" class="form-control" value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($receiveStock->date)) }}" id="date" data-next="warehouse_id" placeholder="dd-mm-yyyy" autocomplete="off" required>
                                            <span class="error error_date"></span>
                                        </div>
                                    </div>

                                    @if ($receiveStock->warehouse_id)

                                        <input name="warehouse_count" value="YES" type="hidden" />
                                        <div class="input-group mt-1">
                                            <label class="col-4"><span class="text-danger">*</span>
                                                <b>@lang('menu.warehouse') </b> </label>
                                            <div class="col-8">
                                                <select required class="form-control form-select" name="warehouse_id" id="warehouse_id" data-next="search_product">
                                                    <option value="">@lang('menu.select_warehouse')</option>
                                                    @foreach ($warehouses as $w)
                                                        <option {{ $w->id == $receiveStock->warehouse_id ? 'SELECTED' : '' }} value="{{ $w->id }}">
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
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>@lang('menu.total_item')</b></label>
                                        <div class="col-8">
                                            <input readonly name="total_item" type="text" class="form-control fw-bold" id="total_item" value="{{ $receiveStock->total_item }}" tabindex="-1">
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-4"><b>@lang('menu.total_quantity')</b></label>
                                        <div class="col-8">
                                            <input readonly type="text" class="form-control fw-bold" id="showing_total_qty" value="{{ $receiveStock->total_qty }}" tabindex="-1">
                                            <input type="hidden" name="total_qty" id="total_qty" value="{{ $receiveStock->total_qty }}" tabindex="-1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <section>
                    <div class="sale-content">
                        <div class="row g-1">
                            <div class="col-md-12">
                                <div class="item-details-sec rounded mt-0 mb-1">
                                    <div class="content-inner">

                                        <div class="row align-items-end">
                                            <div class="col-xl-4 col-md-5">
                                                <div class="searching_area" style="position: relative;">
                                                    <label><strong>@lang('menu.search_item')</strong></label>
                                                    <div class="input-group ">
                                                        <input type="text" name="search_product" @disabled($receiveStock->requisition_id || $receiveStock->purchase_order_id) class="form-control fw-bold" autocomplete="off" id="search_product" onkeyup="event.preventDefault();" placeholder="@lang('menu.search_item_item_code_scan_bar_code')" autofocus>
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

                                            <div class="col-xl-2 col-md-4">
                                                <label><strong>@lang('menu.quantity')</strong></label>
                                                <div class="input-group">
                                                    <input type="number" step="any" class="form-control w-60 fw-bold" id="e_showing_quantity" value="0.00" placeholder="0.00" autocomplete="off">
                                                    <input type="hidden" id="e_quantity">
                                                    <select id="e_unit_id" class="form-control w-40 form-select">
                                                        <option value="">@lang('menu.unit')</option>
                                                    </select>
                                                </div>
                                            </div>

                                            @if (json_decode($generalSettings->purchase, true)['is_enable_lot_no'] == '1')
                                                <div class="col-xl-2 col-md-4">
                                                    <label><strong>@lang('menu.lot_number') </strong></label>
                                                    <input type="text" step="any" class="form-control fw-bold" id="e_lot_number" placeholder="@lang('menu.lot_number')" autocomplete="off">
                                                </div>
                                            @endif

                                            <div class="col-xl-2 col-md-4">
                                                <label><strong>@lang('menu.short_description')</strong></label>
                                                <input type="text" step="any" class="form-control fw-bold" id="e_description" placeholder="@lang('menu.short_description')" autocomplete="off">
                                            </div>

                                            <div class="col-xl-2 col-md-4">
                                                <a href="#" class="btn btn-sm btn-success" id="add_item">@lang('menu.add')</a>
                                                <a href="#" id="reset_add_or_edit_item_fields" class="btn btn-sm btn-danger">@lang('menu.reset')</a>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="sale-item-sec">
                                                <div class="sale-item-inner">
                                                    <div class="table-responsive">
                                                        <table class="display data__table table-striped">
                                                            <thead class="staky">
                                                                <tr>
                                                                    <th>@lang('menu.item')</th>
                                                                    <th>@lang('menu.quantity')</th>
                                                                    <th>@lang('menu.unit')</th>
                                                                    @if (json_decode($generalSettings->purchase, true)['is_enable_lot_no'] == '1')
                                                                        <th>@lang('menu.lot_number')</th>
                                                                    @endif
                                                                    <th><i class="fas fa-trash-alt"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="recieved_item_list">
                                                                @php
                                                                    $totalQty = 0;
                                                                    $itemUnitsArray = [];
                                                                @endphp
                                                                @foreach ($receiveStock->receiveStockProducts as $receiveStockProduct)
                                                                    @php
                                                                        if (isset($receiveStockProduct->product_id)) {
                                                                            $itemUnitsArray[$receiveStockProduct->product_id][] = [
                                                                                'unit_id' => $receiveStockProduct->product->unit->id,
                                                                                'unit_name' => $receiveStockProduct->product->unit->name,
                                                                                'unit_code_name' => $receiveStockProduct->product->unit->code_name,
                                                                                'base_unit_multiplier' => 1,
                                                                                'multiplier_details' => '',
                                                                                'is_base_unit' => 1,
                                                                            ];
                                                                        }

                                                                        if (count($receiveStockProduct?->product?->unit?->childUnits) > 0) {
                                                                            foreach ($receiveStockProduct?->product?->unit?->childUnits as $unit) {
                                                                                $multiplierDetails = '(1 ' . $unit->name . ' = ' . $unit->base_unit_multiplier . '/' . $receiveStockProduct?->product?->unit?->name . ')';

                                                                                array_push($itemUnitsArray[$receiveStockProduct->product_id], [
                                                                                    'unit_id' => $unit->id,
                                                                                    'unit_name' => $unit->name,
                                                                                    'unit_code_name' => $unit->code_name,
                                                                                    'base_unit_multiplier' => $unit->base_unit_multiplier,
                                                                                    'multiplier_details' => $multiplierDetails,
                                                                                    'is_base_unit' => 0,
                                                                                ]);
                                                                            }
                                                                        }

                                                                        $variant = $receiveStockProduct->variant ? ' - ' . $receiveStockProduct->variant->variant_name : '';
                                                                        $variantId = $receiveStockProduct->variant_id ? $receiveStockProduct->variant_id : 'noid';
                                                                    @endphp

                                                                    <tr id="select_item">
                                                                        <td>
                                                                            <span id="span_item_name">
                                                                                {{ $receiveStockProduct->product->name . $variant }}
                                                                            </span>

                                                                            <input type="hidden" id="item_name" value="{{ $receiveStockProduct->product->name . $variant }}">
                                                                            <input type="hidden" name="short_descriptions[]" id="description" value="{{ $receiveStockProduct->short_description }}">
                                                                            <input type="hidden" name="product_ids[]" id="product_id" value="{{ $receiveStockProduct->product_id }}">
                                                                            <input type="hidden" name="variant_ids[]" id="variant_id" value="{{ $variantId }}">
                                                                            <input type="hidden" name="receive_stock_product_ids[]" value="{{ $receiveStockProduct->id }}">
                                                                            <input type="hidden" name="purchase_order_product_ids[]" value="{{ $receiveStockProduct->purchase_order_product_id }}">
                                                                            <input type="hidden" id="{{ $receiveStockProduct->product_id . $variantId }}" value="{{ $receiveStockProduct->product_id . $variantId }}">
                                                                        </td>

                                                                        @php
                                                                            $baseUnitMultiplier = $receiveStockProduct?->receiveUnit?->base_unit_multiplier ? $receiveStockProduct?->receiveUnit?->base_unit_multiplier : 1;
                                                                            $receivedQty = $receiveStockProduct->quantity / $baseUnitMultiplier;
                                                                        @endphp

                                                                        <td>
                                                                            <span id="span_showing_quantity" class="fw-bold">{{ bcadd($receivedQty, 0, 2) }}</span>
                                                                            <input type="hidden" id="showing_quantity" value="{{ bcadd($receivedQty, 0, 2) }}">
                                                                            <input type="hidden" name="quantities[]" class="form-control fw-bold" id="quantity" value="{{ $receiveStockProduct->quantity }}">
                                                                        </td>

                                                                        <td>
                                                                            <span id="span_showing_unit" class="fw-bold">{{ $receiveStockProduct?->receiveUnit?->name }}</span>
                                                                            <input type="hidden" name="unit_ids[]" id="unit_id" value="{{ $receiveStockProduct->unit_id }}">
                                                                        </td>

                                                                        <td>
                                                                            @if (json_decode($generalSettings->purchase, true)['is_enable_lot_no'] == '1')
                                                                                <span id="span_showing_lot_number" class="fw-bold">{{ $receiveStockProduct->lot_number }}</span>
                                                                                <input type="hidden" name="lot_numbers[]" id="lot_number" value="{{ $receiveStockProduct->lot_number }}">
                                                                            @endif
                                                                        </td>

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
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="row g-1">
                        <div class="col-md-12">
                            <div class="form_element rounded mt-0 mb-1">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="input-group mt-1">
                                                    <label class="col-1"><b>@lang('menu.receive_notes') </b></label>
                                                    <div class="col-11">
                                                        <input name="note" class="form-control fw-bold" id="note" value="{{ $receiveStock->note }}" data-next="save" placeholder="@lang('menu.receive_notes')">
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
                                <button type="button" id="save" class="btn btn-success submit_button">@lang('menu.save_changes')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

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

@include('procurement.receive_stock.partials.receiveStocksEditJsScript')
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

    function afterClickOrFocusRequisitionNo() {

        ul = document.getElementById('requisition_list')
        selectObjClassName = 'selected_requisition';
        $('#requisition_no').val('');
        $('#requisition_id').val('');
        $('#challan_no').val('');
        $('#challan_date').val('');

        $('.select_area').hide();

        $('#recieved_item_list').empty();

        $('#search_product').prop('disabled', false);
        calculateTotalAmount();
    }

    $('#po_id').mousedown(function(e) {

        afterClickOrFocusPoId();
    }).focus(function(e) {

        ul = document.getElementById('po_list')
        selectObjClassName = 'selected_po';
    });

    function afterClickOrFocusPoId() {

        ul = document.getElementById('requisition_list')
        selectObjClassName = 'selected_requisition';
        $('#requisition_no').val('');
        $('#requisition_id').val('');
        $('#po_id').val('');
        $('#purchase_order_id').val('');
        $('#recieved_item_list').empty();

        $('#search_product').prop('disabled', false);
        calculateTotalAmount();
    }

    function afterFocusSearchItemField() {

        ul = document.getElementById('list');
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

        var url =
            "{{ route('common.ajax.call.get.requisition.products.for.receive.stock', ':requisition_id') }}";
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
                $('#recieved_item_list').html(data.view);

                $('#search_product').prop('disabled', true);
                $('#requisition_list').empty();

                $('.invoice_search_result').hide();

                calculateTotalAmount();
            }
        });
    });

    $('#po_id').on('input', function() {

        $('.po_search_result').hide();

        var po_id = $(this).val();

        if (po_id === '') {

            $('.po_search_result').hide();
            $('#po_id').val('');
            $('#search_product').prop('disabled', false);
            return;
        }

        var url = "{{ route('common.ajax.call.search.po', ':po_id') }}";
        var route = url.replace(':po_id', po_id);

        $.ajax({
            url: route,
            async: true,
            type: 'get',
            success: function(data) {

                if (!$.isEmptyObject(data.noResult)) {

                    $('.po_search_result').hide();
                } else {

                    $('.po_search_result').show();
                    $('#po_list').html(data);
                }
            }
        });
    });

    // Work will be running from here...
    $(document).on('click', '#selected_po', function(e) {
        e.preventDefault();

        var po_id = $(this).data('po_id');
        var purchase_order_id = $(this).data('purchase_order_id');
        var supplier_account_id = $(this).data('supplier_account_id');
        var warehouse_id = $(this).data('warehouse_id');
        var requisition_no = $(this).data('requisition_no');
        var requisition_id = $(this).data('requisition_id');

        var url = "{{ route('common.ajax.call.po.products', ':purchase_order_id') }}";
        var route = url.replace(':purchase_order_id', purchase_order_id);

        $.ajax({
            url: route,
            async: true,
            type: 'get',
            success: function(data) {

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                itemUnitsArray = jQuery.parseJSON(data.units);

                $('#po_id').val(po_id);
                $('#purchase_order_id').val(purchase_order_id);
                $('#supplier_account_id').val(supplier_account_id).trigger('change');
                $('#warehouse_id').val(warehouse_id);
                $('#requisition_no').val(requisition_no);
                $('#requisition_id').val(requisition_id);
                $('#recieved_item_list').html(data.view);

                $('#search_product').prop('disabled', true);

                $('.invoice_search_result').hide();
                $('.po_search_result').hide();
                $('#requisition_list').empty();
                $('#po_list').empty();

                calculateTotalAmount();
            }
        });
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
        } else if (e.which == 27) {

            $('.select_area').hide();
            $('.invoice_search_result').hide();
            $('#requisition_list').empty();
            $('#list').empty();
            return false;
        }
    }

    //Add purchase request by ajax
    $('#edit_receive_stock_form').on('submit', function(e) {
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

                $('.error').html('');
                $('.loading_button').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                } else {

                    toastr.success(data);
                    window.location = "{{ url()->previous() }}";
                }
            },
            error: function(err) {

                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error.');
                    return;
                } else if (err.status == 500) {

                    toastr.error('Server error. Please contact to the support team.');
                    return;
                } else if (err.status == 403) {

                    toastr.error('Access Denied');
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

        if ($(e.target).closest(".select_area").length === 0 || $(e.target).closest(".invoice_search_result")
            .length === 0) {

            $('.select_area').hide();
            $('.invoice_search_result').hide();
            $('#requisition_list').empty();
            $('#list').empty();
        }
    });

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

            if ($(this).attr('id') == 'warehouse_id' && $('#search_product').is(':disabled') == true) {

                $('#e_showing_quantity').focus().select();
                return;
            }

            $('#' + nextId).focus().select();
        }
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            var warehouse_id = $('#warehouse_id').val();

            if ($(this).attr('id') == 'date' && warehouse_id == undefined) {

                if ($('#search_product').is(':disabled') == true) {

                    $('#e_showing_quantity').focus().select();
                    return;
                } else {

                    $('#search_product').focus().select();
                    return;
                }
            }

            if ($(this).attr('id') == 'requisition_no' && $('#requisition_no').val() != '') {

                $('#challan_no').focus();
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
