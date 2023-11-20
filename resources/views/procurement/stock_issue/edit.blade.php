@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style>
        .input-group-text {
            font-size: 12px !important;
        }

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
            font-size: 10px;
            padding: 2px 2px;
            display: block;
            border: 1px solid lightgray;
            margin: 2px 0px;
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
    </style>
@endpush
@section('title', 'Edit Stock Issue - ')
@section('content')
    <div class="body-wraper">
        <div class="container-fluid p-0">
            <form id="edit_stock_issue_form" action="{{ route('stock.issue.update', $stockIssue->id) }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="sec-name">
                    <h6>@lang('menu.edit_stock_issue')</h6>
                    <x-back-button />
                </div>
                <div class="p-15">
                    <section>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-xl-4 col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.send_from') </b></label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <select name="warehouse_id" class="form-control form-select" id="warehouse_id" data-next="department_id">
                                                                <option value="">@lang('menu.select_warehouse')</option>
                                                                @foreach ($warehouses as $warehouse)
                                                                    <option data-w_name="{{ $warehouse->name . '/' . $warehouse->code }}" {{ $stockIssue->warehouse_id == $warehouse->id ? 'SELECTED' : '' }} value="{{ $warehouse->id }}">{{ $warehouse->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <span class="error error_warehouse_id"></span>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><span class="text-danger">*</span>
                                                        <b>@lang('menu.receiver_dep'). </b></label>
                                                    <div class="col-8">
                                                        <div class="input-group select-customer-input-group">
                                                            <select name="department_id" class="form-control select2 form-select" id="department_id" data-next="stock_event_id">
                                                                <option value="">@lang('menu.select_department')</option>
                                                                @foreach ($departments as $department)
                                                                    <option {{ $stockIssue->department_id == $department->id ? 'SELECTED' : '' }} value="{{ $department->id }}">
                                                                        {{ $department->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div style="display: inline-block;margin-top:0px;" class="style-btn">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text add_button" id="addDepartment"><i class="fas fa-plus-square text-dark"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <span class="error error_department_id"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-4 col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.voucher_no')</b></label>
                                                    <div class="col-8">
                                                        <input readonly type="text" name="voucher_no" id="voucher_no" class="form-control fw-bold" value="{{ $stockIssue->voucher_no }}" data-next="stock_event_id">
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.issue_event')</b></label>
                                                    <div class="col-8">
                                                        <div class="input-group select-customer-input-group">
                                                            <select name="stock_event_id" class="form-control select2 form-select" id="stock_event_id" data-next="date">
                                                                <option value="">@lang('menu.select_stock_issue_event')</option>
                                                                @foreach ($events as $event)
                                                                    <option {{ $stockIssue->stock_event_id == $event->id ? 'SELECTED' : '' }} value="{{ $event->id }}">{{ $event->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>

                                                            <div style="display: inline-block;margin-top:0px;" class="style-btn">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text add_button" id="addQuickStockEvent"><i class="fas fa-plus-square text-dark"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <span class="error error_stock_event_id"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-4 col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b><span class="text-danger">*</span>@lang('menu.issue_date') </b></label>
                                                    <div class="col-8">
                                                        <input type="text" name="date" class="form-control changeable" id="date" value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($stockIssue->date)) }}" data-next="search_product" placeholder="dd-mm-yyyy" autocomplete="off">
                                                        <span class="error error_date"></span>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>@lang('menu.total_item') </b> </label>
                                                    <div class="col-8">
                                                        <input readonly name="total_item" type="number" step="any" class="form-control" id="total_item" value="{{ $stockIssue->total_item }}" tabindex="-1">
                                                        <input type="hidden" name="total_qty" step="any" class="form-control fw-bold" id="total_qty" value="{{ $stockIssue->total_qty }}" tabindex="-1">
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
                        <div class="sale-content">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="item-details-sec">
                                        <div class="content-inner">
                                            <div class="row g-xxl-4 g-3">
                                                <div class="col-xxl-12">
                                                    <div class="hidden_fields">
                                                        <input type="hidden" id="e_unique_id">
                                                        <input type="hidden" id="current_warehouse_id">
                                                        <input type="hidden" id="e_item_name">
                                                        <input type="hidden" id="e_product_id">
                                                        <input type="hidden" id="e_variant_id">
                                                        <input type="hidden" id="e_current_quantity">
                                                        <input type="hidden" id="e_base_unit_cost_inc_tax">
                                                    </div>

                                                    <div class="row gx-xxl-4 gx-3 mt-1 align-items-end">

                                                        <div class="col-xl-4 col-md-6">
                                                            <label class="fw-bold">@lang('menu.search_item')</label>
                                                            <div class="searching_area" style="position: relative;">
                                                                <div class="input-group">
                                                                    <input type="text" name="search_product" class="form-control fw-bold" id="search_product" placeholder="@lang('menu.search_item_item_code_scan_bar_code')" autocomplete="off">
                                                                </div>

                                                                <div class="select_area">
                                                                    <ul id="list" class="variant_list_area"></ul>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-2 col-md-6">
                                                            <label class="fw-bold">@lang('menu.quantity')</label>
                                                            <div class="input-group">
                                                                <input type="number" step="any" class="form-control fw-bold w-60" id="e_showing_quantity" placeholder="@lang('menu.quantity')" value="0.00">
                                                                <input type="hidden" id="e_quantity" value="0.00">
                                                                <select id="e_unit_id" class="form-control w-40 form-select">
                                                                    <option value="">@lang('menu.unit')</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-2 col-md-6">
                                                            <label class="fw-bold">@lang('menu.per')
                                                                @lang('menu.unit_cost_inc_tax')</label>
                                                            <input type="number" step="any" class="form-control fw-bold" id="e_showing_unit_cost_inc_tax" placeholder="@lang('menu.unit_cost_inc_tax')" value="0.00">
                                                            <input type="hidden" id="e_unit_cost_inc_tax" value="0.00">
                                                        </div>

                                                        <div class="col-xl-2 col-md-6">
                                                            <label class="fw-bold">@lang('menu.sub_total')</label>
                                                            <input readonly type="number" step="any" class="form-control fw-bold" id="e_subtotal" value="0.00" tabindex="-1">
                                                        </div>

                                                        <div class="col-xl-2 col-md-6">
                                                            <a href="#" class="btn btn-sm btn-success" id="add_item">@lang('menu.add')</a>
                                                            <input type="reset" id="reset_add_or_edit_item_fields" class="btn btn-sm btn-danger" value="@lang('menu.reset')">
                                                        </div>
                                                    </div>
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
                                                                        <th>@lang('menu.stock_location')</th>
                                                                        <th>@lang('menu.quantity')</th>
                                                                        <th>@lang('menu.unit')</th>
                                                                        <th>@lang('menu.unit_cost_inc_tax')</th>
                                                                        <th>@lang('menu.sub_total')</th>
                                                                        <th><i class="fas fa-trash-alt"></i></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="issue_item_list">
                                                                    @php $itemUnitsArray = []; @endphp

                                                                    @foreach ($stockIssue->issueProducts as $issueProduct)
                                                                        @php
                                                                            if (isset($issueProduct->product_id)) {
                                                                                $itemUnitsArray[$issueProduct->product_id][] = [
                                                                                    'unit_id' => $issueProduct->product->unit->id,
                                                                                    'unit_name' => $issueProduct->product->unit->name,
                                                                                    'unit_code_name' => $issueProduct->product->unit->code_name,
                                                                                    'base_unit_multiplier' => 1,
                                                                                    'multiplier_details' => '',
                                                                                    'is_base_unit' => 1,
                                                                                ];
                                                                            }

                                                                            if (count($issueProduct?->product?->unit?->childUnits) > 0) {
                                                                                foreach ($issueProduct?->product?->unit?->childUnits as $unit) {
                                                                                    $multiplierDetails = '(1 ' . $unit->name . ' = ' . $unit->base_unit_multiplier . '/' . $issueProduct?->product?->unit?->name . ')';

                                                                                    array_push($itemUnitsArray[$issueProduct->product_id], [
                                                                                        'unit_id' => $unit->id,
                                                                                        'unit_name' => $unit->name,
                                                                                        'unit_code_name' => $unit->code_name,
                                                                                        'base_unit_multiplier' => $unit->base_unit_multiplier,
                                                                                        'multiplier_details' => $multiplierDetails,
                                                                                        'is_base_unit' => 0,
                                                                                    ]);
                                                                                }
                                                                            }
                                                                        @endphp

                                                                        <tr id="select_item">
                                                                            <td class="text-start">
                                                                                @php
                                                                                    $variant = $issueProduct->product_variant_id ? ' -' . $issueProduct->variant->variant_name : '';

                                                                                    $variantId = $issueProduct->product_variant_id ? $issueProduct->product_variant_id : 'noid';

                                                                                    $currentStock = $issueProduct->product_variant_id ? $issueProduct->variant->variant_quantity : $issueProduct->product->quantity;

                                                                                    $baseUnitMultiplier = $issueProduct?->issueUnit?->base_unit_multiplier ? $issueProduct?->issueUnit?->base_unit_multiplier : 1;
                                                                                @endphp

                                                                                <span class="product_name">{{ $issueProduct->product->name . $variant }}</span>
                                                                                <input type="hidden" id="item_name" value="{{ $issueProduct->product->name . $variant }}">
                                                                                <input type="hidden" name="product_ids[]" id="product_id" value="{{ $issueProduct->product_id }}">
                                                                                <input type="hidden" value="{{ $variantId }}" id="variant_id" name="variant_ids[]">
                                                                                <input type="hidden" id="current_quantity" value="{{ $issueProduct->quantity }}">
                                                                                <input type="hidden" name="stock_issue_product_ids[]" value="{{ $issueProduct->id }}">
                                                                                <input type="hidden" class="unique_id" id="{{ $issueProduct->product_id . $variantId . $issueProduct->warehouse_id }}" value="{{ $issueProduct->product_id . $variantId . $issueProduct->warehouse_id }}">
                                                                            </td>

                                                                            <td class="text-start">
                                                                                <input type="hidden" name="warehouse_ids[]" id="warehouse_id" value="{{ $issueProduct->warehouse_id }}">

                                                                                <input type="hidden" id="current_warehouse_id" value="{{ $issueProduct->warehouse_id }}">

                                                                                @if ($issueProduct->warehouse_id)
                                                                                    <span id="stock_location_name">
                                                                                        {{ $issueProduct->warehouse->warehouse_name . '/' . $issueProduct->warehouse->warehouse_code }}
                                                                                    </span>
                                                                                @else
                                                                                    <span id="stock_location_name">
                                                                                        {{ json_decode($generalSettings->business, true)['shop_name'] }}
                                                                                    </span>
                                                                                @endif
                                                                            </td>

                                                                            <td>
                                                                                @php
                                                                                    $showingQuantity = $issueProduct->quantity / $baseUnitMultiplier;
                                                                                @endphp
                                                                                <span id="span_showing_quantity" class="fw-bold">{{ bcadd($showingQuantity, 0, 2) }}</span>
                                                                                <input type="hidden" name="quantities[]" id="quantity" value="{{ $issueProduct->quantity }}">
                                                                                <input type="hidden" id="showing_quantity" value="{{ bcadd($showingQuantity, 0, 2) }}">
                                                                            </td>

                                                                            <td class="text">
                                                                                <span id="span_unit">{{ $issueProduct?->issueUnit?->name }}</span>
                                                                                <input type="hidden" name="unit_ids[]" id="unit_id" value="{{ $issueProduct?->issueUnit?->id }}">
                                                                            </td>

                                                                            <td>
                                                                                @php
                                                                                    $showingUnitCostIncTax = $issueProduct->unit_cost_inc_tax * $baseUnitMultiplier;
                                                                                @endphp
                                                                                <span id="span_showing_unit_cost_inc_tax" class="fw-bold">{{ bcadd($showingUnitCostIncTax, 0, 2) }}</span>
                                                                                <input type="hidden" id="showing_unit_cost_inc_tax" value="{{ bcadd($showingUnitCostIncTax, 0, 2) }}">
                                                                                <input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="{{ $issueProduct->unit_cost_inc_tax }}">
                                                                            </td>

                                                                            <td class="text text-center">
                                                                                <strong><span id="span_subtotal">{{ $issueProduct->subtotal }}</span></strong>
                                                                                <input value="{{ $issueProduct->subtotal }}" readonly name="subtotals[]" type="hidden" id="subtotal">
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
                    </section>

                    <section>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form_element mt-1 mb-0 rounded">
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label><b>@lang('menu.stock_issue_note') </b> </label>
                                                <input name="note" class="form-control" placeholder="@lang('menu.stock_issue_note')" value="{{ $stockIssue->note }}">
                                                <input type="hidden" name="net_total_value" id="net_total_value" value="{{ $stockIssue->net_total_value }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                            <button type="submit" id="save" class="btn btn-sm btn-success submit_button">@lang('menu.save_change')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <x-shortcut-key-bar.shortcut-key-bar :items="[['key' => 'Ctrl + Enter', 'value' => __('menu.save_changes')], ['key' => 'Alt + G', 'value' => __('menu.add_department')], ['key' => 'Alt + V', 'value' => __('menu.add_event')]]">
    </x-shortcut-key-bar.shortcut-key-bar>

    <div class="modal fade" id="requisitionDepartmentAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
@endsection
@push('scripts')
    <script src="{{ asset('plugins/select_li/selectli.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $('.select2').select2();
        var itemUnitsArray = @json($itemUnitsArray);
        var branch_name = "{{ json_decode($generalSettings->business, true)['shop_name'] }}";

        $('#addQuickStockEvent').on('click', function() {

            $.get("{{ route('stock.issue.events.quick.add.modal.form') }}", function(data) {

                $('#modal_content').html(data);
                $('#addQuickModal').modal('show');

                setTimeout(function() {
                    document.getElementById('event_name').focus();
                }, 500);
            });
        });

        $('#addDepartment').on('click', function() {

            $.get("{{ route('requisitions.departments.create') }}", function(data) {

                $('#requisitionDepartmentAddOrEditModal').html(data);
                $('#requisitionDepartmentAddOrEditModal').modal('show');

                setTimeout(function() {
                    document.getElementById('department_name').focus();
                }, 500);
            });
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
            var keyWord = $(this).val();
            var __keyWord = keyWord.replaceAll('/', '~');
            delay(function() {
                searchProduct(__keyWord);
            }, 200); //sendAjaxical is the name of remote-command
        });

        function searchProduct(keyWord, price_group_id) {

            $('#search_product').focus();

            var isShowNotForSaleItem = 1;
            var url = "{{ route('general.product.search.common', [':keyWord', ':isShowNotForSaleItem']) }}";
            var route = url.replace(':keyWord', keyWord);
            route = route.replace(':isShowNotForSaleItem', isShowNotForSaleItem);

            $.ajax({
                url: route,
                dataType: 'json',
                success: function(product) {

                    if (!$.isEmptyObject(product.errorMsg || keyWord == '')) {

                        toastr.error(product.errorMsg);
                        $('#search_product').val("");
                        $('.select_area').hide();
                        $('#stock_quantity').val(parseFloat(0).toFixed(2));
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
                                $('#e_showing_quantity').val(parseFloat(1).toFixed(2)).focus().select();

                                $('#e_unit_cost_inc_tax').val(product.update_product_cost ? product.update_product_cost.net_unit_cost : product.product_cost_with_tax);
                                $('#e_showing_unit_cost_inc_tax').val(product.update_product_cost ? product.update_product_cost.net_unit_cost : product.product_cost_with_tax);
                                $('#e_base_unit_cost_inc_tax').val(product.update_product_cost ? product.update_product_cost.net_unit_cost : product.product_cost_with_tax);

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
                            } else {

                                var li = "";
                                $.each(product.variants, function(key, variant) {

                                    product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/default.jpg') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;

                                    var name = product.name.length > 35 ? product.name.substring(0,35) + '...' : product.name;

                                    li += '<li>';
                                    li += '<a onclick="selectProduct(this); return false;" data-p_id="' + product.id + '" data-v_id="' + variant.id + '" data-p_name="' + product.name + '" data-v_name="' + variant.variant_name + '" data-is_show_emi_on_pos="' + product.is_show_emi_on_pos + '" data-p_code="' + variant.variant_code + '" data-p_cost_inc_tax="' + (variant.update_variant_cost ? variant.update_variant_cost.net_unit_cost : variant.variant_cost_with_tax) + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + ' - ' + variant.variant_name + '</a>';
                                    li += '</li>';
                                });

                                $('.variant_list_area').append(li);
                                $('.select_area').show();
                                $('#search_product').val('');
                            }
                        } else if (!$.isEmptyObject(product.variant_product)) {

                            $('.select_area').hide();
                            $('#search_product').val('');

                            var variant = product.variant_product;

                            var name = variant.product.name.length > 35 ? variant.product.name.substring(0, 35) + '...' : variant.product.name;

                            $('#search_product').val(name + ' - ' + variant.variant_name);
                            $('#e_item_name').val(name + ' - ' + variant.variant_name);
                            $('#e_product_id').val(variant.product.id);
                            $('#e_variant_id').val(variant.id);
                            $('#e_showing_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                            $('#e_unit_cost_inc_tax').val(variant.update_variant_cost ? variant.update_variant_cost.net_unit_cost : variant.variant_cost_with_tax);
                            $('#e_showing_unit_cost_inc_tax').val(variant.update_variant_cost ? variant.update_variant_cost.net_unit_cost : variant.variant_cost_with_tax);
                            $('#e_base_unit_cost_inc_tax').val(variant.update_variant_cost ? variant.update_variant_cost.net_unit_cost : variant.variant_cost_with_tax);

                            $('#e_unit_id').empty();
                            $('#e_unit_id').append(
                                '<option value="' + variant.product.unit.id +
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

                                    var multiplierDetails = '(1 ' + unit.name + ' = ' + unit.base_unit_multiplier + '/' + unit.name + ')';

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

                                var li = "";
                                var products = product.namedProducts;

                                $.each(products, function(key, product) {

                                    product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/default.jpg') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;

                                    if (product.is_variant == 1) {

                                        li += '<li>';
                                        li += '<a href="#" onclick="selectProduct(this); return false;" data-product_type="variant" data-p_id="' + product.id + '" data-v_name="' + product.variant_name + '" data-v_id="' + product.variant_id + '" data-p_name="' + product.name + '" data-p_code="' + product.variant_code + '" data-p_cost_inc_tax="' + product.variant_cost_with_tax + '"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + ' - ' + product.variant_name + '</a>';
                                        li += '</li>';
                                    } else {

                                        li += '<li>';
                                        li += '<a onclick="selectProduct(this); return false;" data-product_type="single" data-p_id="' + product.id + '" data-v_id="" data-p_name="' + product.name + '" data-v_name="" data-p_code="' + product.product_code +
                                            '" data-p_cost_inc_tax="' + product.product_cost_with_tax + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + '</a>';
                                        li += '</li>';
                                    }
                                });

                                $('.variant_list_area').html(li);
                                $('.select_area').show();
                            }
                        }
                    } else {

                        $('#search_product').addClass('is-invalid');
                    }
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Please check the connetion.');
                        return;
                    }
                }
            });
        }

        // select single product and add purchase table
        function selectProduct(e) {

            $('.select_area').hide();
            $('#search_product').val('');

            var product_id = e.getAttribute('data-p_id');
            var variant_id = e.getAttribute('data-v_id') ? e.getAttribute('data-v_id') : 'noid';
            var product_name = e.getAttribute('data-p_name');
            var variant_name = e.getAttribute('data-v_name');
            var product_code = e.getAttribute('data-p_code');
            var product_cost_inc_tax = e.getAttribute('data-p_cost_inc_tax');
            $('#search_product').val('');

            var url = "{{ route('general.product.search.product.unit.and.multiplier.unit', [':product_id']) }}"
            var route = url.replace(':product_id', product_id);

            $.ajax({
                url: route,
                type: 'get',
                dataType: 'json',
                success: function(baseUnit) {

                    var name = product_name.length > 35 ? product_name.substring(0, 35) + '...' : product_name;

                    $('#search_product').val(name + (variant_name ? ' - ' + variant_name : ''));
                    $('#e_item_name').val(name + (variant_name ? ' - ' + variant_name : ''));
                    $('#e_product_id').val(product_id);
                    $('#e_variant_id').val(variant_id);
                    $('#e_showing_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                    $('#e_showing_unit_cost_inc_tax').val(parseFloat(product_cost_inc_tax).toFixed(2));
                    $('#e_unit_cost_inc_tax').html(parseFloat(product_cost_inc_tax).toFixed(2));
                    $('#e_base_unit_cost_inc_tax').val(parseFloat(product_cost_inc_tax).toFixed(2));

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
            var e_unit_name = $('#e_unit_id').find('option:selected').data('unit_name');
            var e_unit_id = $('#e_unit_id').val();
            var e_showing_quantity = $('#e_showing_quantity').val() ? $('#e_showing_quantity').val() : 0;
            var e_quantity = $('#e_quantity').val() ? $('#e_quantity').val() : 0;
            var e_unit_cost_inc_tax = $('#e_unit_cost_inc_tax').val() ? $('#e_unit_cost_inc_tax').val() : 0;
            var e_showing_unit_cost_inc_tax = $('#e_showing_unit_cost_inc_tax').val() ? $('#e_showing_unit_cost_inc_tax').val() : 0;
            var e_subtotal = $('#e_subtotal').val() ? $('#e_subtotal').val() : 0;
            var e_current_quantity = $('#e_current_quantity').val();
            var e_current_warehouse_id = $('#e_current_warehouse_id').val();

            var warehouse_id = $('#warehouse_id').val();
            var warehouse_name = $('#warehouse_id').find('option:selected').data('w_name');

            var stock_location_name = '';
            if (warehouse_id) {

                stock_location_name = warehouse_name;
            } else {

                stock_location_name = branch_name;
            }

            if (e_quantity == '') {

                toastr.error('Quantity field must not be empty.');
                return;
            }

            if (e_product_id == '') {

                toastr.error('Please select a product.');
                return;
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

                        var check_quantity = parseFloat(e_quantity) - parseFloat(e_current_quantity);

                        if (e_current_warehouse_id == warehouse_id) {

                            var check_quantity = parseFloat(e_quantity) - parseFloat(e_current_quantity);
                        }

                        var stockLocationMessage = warehouse_id ? ' in selected warehouse' :
                            ' in the company';

                        if (parseFloat(check_quantity) > parseFloat(data.stock)) {

                            toastr.error('Current stock is ' + parseFloat(data.stock) + '/' +
                                stockLocationMessage);
                            return;
                        }

                        var uniqueIdForPreventDuplicateEntry = e_product_id + e_variant_id +
                            warehouse_id;
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
                            tr += '<input type="hidden" name="stock_issue_product_ids[]" value="">';
                            tr += '<input type="hidden" id="current_quantity" value="0.00">';
                            tr += '<input type="hidden" class="unique_id" id="' + e_product_id + e_variant_id + warehouse_id + '" value="' + e_product_id + e_variant_id + warehouse_id + '">';
                            tr += '</td>';

                            tr += '<td class="text-start">';
                            tr += '<input type="hidden" name="warehouse_ids[]" id="warehouse_id" value="' + warehouse_id + '">';
                            tr += '<span id="stock_location_name">' + stock_location_name + '</span>';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<span id="span_showing_quantity" class="fw-bold">' + parseFloat(e_showing_quantity).toFixed(2) + '</span>';
                            tr += '<input type="hidden" name="quantities[]" id="quantity" value="' + parseFloat(e_quantity).toFixed(2) + '">';
                            tr += '<input type="hidden" id="showing_quantity" value="' + parseFloat(e_showing_quantity).toFixed(2) + '">';
                            tr += '</td>';

                            tr += '<td class="text">';
                            tr += '<b><span id="span_unit">' + e_unit_name + '</span></b>';
                            tr += '<input type="hidden" name="unit_ids[]" id="unit_id" value="' + e_unit_id + '">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<span id="span_showing_unit_cost_inc_tax" class="fw-bold">' + parseFloat(e_showing_unit_cost_inc_tax).toFixed(2) + '</span>';
                            tr += '<input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="' + parseFloat(e_unit_cost_inc_tax).toFixed(2) + '">';
                            tr += '<input type="hidden" id="showing_unit_cost_inc_tax" value="' + parseFloat(e_showing_unit_cost_inc_tax).toFixed(2) + '">';
                            tr += '</td>';

                            tr += '<td class="text text-center">';
                            tr += '<span id="span_subtotal" class="fw-bold">' + parseFloat(e_subtotal).toFixed(2) + '</span>';
                            tr += '<input type="hidden" name="subtotals[]" id="subtotal" value="' + parseFloat(e_subtotal).toFixed(2) + '">';
                            tr += '</td>';

                            tr += '<td class="text-center">';
                            tr += '<a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                            tr += '</td>';
                            tr += '</tr>';

                            $('#issue_item_list').append(tr);
                            clearEditItemFileds();
                            calculateTotalAmount();
                        } else {

                            var tr = $('#' + (e_unique_id ? e_unique_id : uniqueIdForPreventDuplicateEntry)).closest('tr');

                            tr.find('#item_name').val(e_item_name);
                            tr.find('#product_id').val(e_product_id);
                            tr.find('#variant_id').val(e_variant_id);

                            tr.find('#quantity').val(parseFloat(e_quantity).toFixed(2));
                            tr.find('#showing_quantity').val(parseFloat(e_showing_quantity).toFixed(2));
                            tr.find('#span_showing_quantity').html(parseFloat(e_showing_quantity).toFixed(2));
                            tr.find('#span_unit').html(e_unit_name);
                            tr.find('#unit_id').val(e_unit_id);
                            tr.find('#unit_cost_inc_tax').val(parseFloat(e_unit_cost_inc_tax).toFixed(2));
                            tr.find('#showing_unit_cost_inc_tax').val(parseFloat(e_showing_unit_cost_inc_tax).toFixed(2));
                            tr.find('#span_showing_unit_cost_inc_tax').html(parseFloat(e_showing_unit_cost_inc_tax).toFixed(2));
                            tr.find('#subtotal').val(parseFloat(e_subtotal).toFixed(2));
                            tr.find('#span_subtotal').html(parseFloat(e_subtotal).toFixed(2));

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
            })
        });

        $(document).on('click', '#select_item', function(e) {

            var tr = $(this);
            var unique_id = tr.find('#unique_id').val();
            var warehouse_id = tr.find('#warehouse_id').val();
            var current_warehouse_id = tr.find('#current_warehouse_id').val();
            var stock_location_name = tr.find('#stock_location_name').html();
            var item_name = tr.find('#item_name').val();
            var product_id = tr.find('#product_id').val();
            var variant_id = tr.find('#variant_id').val();
            var unit_cost_inc_tax = tr.find('#unit_cost_inc_tax').val();
            var showing_unit_cost_inc_tax = tr.find('#showing_unit_cost_inc_tax').val();
            var quantity = tr.find('#quantity').val();
            var showing_quantity = tr.find('#showing_quantity').val();
            var current_quantity = tr.find('#current_quantity').val();
            var unit_id = tr.find('#unit_id').val();
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
            $('#e_unique_id').val(unique_id);
            $('#warehouse_id').val(warehouse_id);
            $('#e_current_warehouse_id').val(current_warehouse_id);
            $('#e_stock_location_name').val(stock_location_name);
            $('#e_item_name').val(item_name);
            $('#e_product_id').val(product_id);
            $('#e_variant_id').val(variant_id);
            $('#e_unit_id').val(unit_id);
            $('#e_quantity').val(parseFloat(quantity).toFixed(2));
            $('#e_showing_quantity').val(parseFloat(showing_quantity).toFixed(2)).focus().select();
            $('#e_unit_cost_inc_tax').val(parseFloat(unit_cost_inc_tax).toFixed(2));
            $('#e_showing_unit_cost_inc_tax').val(parseFloat(showing_unit_cost_inc_tax).toFixed(2));
            $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));
            $('#e_current_quantity').val(parseFloat(current_quantity).toFixed(2));

            $('#add_item').html('Edit');
        });

        function calculateEditOrAddAmount() {

            var base_unit_multiplier = $('#e_unit_id').find('option:selected').data('base_unit_multiplier');
            var is_base_unit = $('#e_unit_id').find('option:selected').data('is_base_unit');
            var e_showing_quantity = $('#e_showing_quantity').val() ? $('#e_showing_quantity').val() : 0;
            var e_showing_unit_cost_inc_tax = $('#e_showing_unit_cost_inc_tax').val() ? $('#e_showing_unit_cost_inc_tax')
                .val() : 0;

            var quantity = roundOfValue(e_showing_quantity) * roundOfValue(base_unit_multiplier);
            $('#e_quantity').val(parseFloat(quantity).toFixed(2));

            unitCostIncTax = roundOfValue(e_showing_unit_cost_inc_tax) / roundOfValue(base_unit_multiplier);
            $('#e_unit_cost_inc_tax').val(roundOfValue(unitCostIncTax));
            $('#e_base_unit_cost_inc_tax').val(roundOfValue(unitCostIncTax));

            var subtotal = roundOfValue(unitCostIncTax) * roundOfValue(quantity);
            $('#e_subtotal').val(parseFloat(roundOfValue(subtotal)).toFixed(2));
        }

        function calculateTotalAmount() {

            var quantities = document.querySelectorAll('#showing_quantity');
            var subtotals = document.querySelectorAll('#subtotal');
            var total_item = 0;
            var total_qty = 0;

            quantities.forEach(function(qty) {
                total_item += 1;
                var __total_qty = parseFloat(qty.value) ? parseFloat(qty.value) : 0;
                total_qty += parseFloat(__total_qty)
            });

            $('#total_qty').val(parseFloat(total_qty));
            $('#total_item').val(parseFloat(total_item));

            var netTotalValue = 0;
            subtotals.forEach(function(subtotal) {

                netTotalValue += parseFloat(subtotal.value);
            });

            $('#net_total_value').val(parseFloat(netTotalValue).toFixed(2));
        }

        calculateTotalAmount();

        function clearEditItemFileds() {

            $('#search_product').val('').focus();
            $('#e_unique_id').val('');
            $('#e_item_name').val('');
            $('#e_product_id').val('');
            $('#e_variant_id').val('');
            $('#e_quantity').val(parseFloat(0).toFixed(2));
            $('#e_showing_quantity').val(parseFloat(0).toFixed(2));
            $('#e_unit_cost_inc_tax').val(0);
            $('#e_showing_unit_cost_inc_tax').val(0);
            $('#e_current_quantity').val(parseFloat(0).toFixed(2));
            $('#add_item').html('Add');
        }

        $('#e_showing_quantity').on('input keypress', function(e) {

            calculateEditOrAddAmount();

            if (e.which == 13) {

                if ($(this).val() != '') {

                    $('#e_unit_id').focus();
                }
            }
        });

        $('#e_unit_id').on('change keypress click', function(e) {

            var isBaseUnit = $(this).find('option:selected').data('is_base_unit');
            var baseUnitCostIncTax = $('#e_base_unit_cost_inc_tax').val() ? $('#e_base_unit_cost_inc_tax').val() : 0;
            var base_unit_multiplier = $(this).find('option:selected').data('base_unit_multiplier');

            var showingUnitCostIncTax = roundOfValue(baseUnitCostIncTax) * roundOfValue(base_unit_multiplier);

            $('#e_showing_unit_cost_inc_tax').val(parseFloat(showingUnitCostIncTax).toFixed(2));

            if (e.which == 0) {

                $('#e_showing_unit_cost_inc_tax').focus().select();
            }

            calculateEditOrAddAmount();
        });

        $('#e_showing_unit_cost_inc_tax').on('input keypress', function(e) {

            calculateEditOrAddAmount();

            if (e.which == 13) {

                if ($(this).val() != '') {

                    $('#add_item').focus();
                }
            }
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

        var isAllowSubmit = true;
        $(document).on('click', '.submit_button', function() {

            if (isAllowSubmit) {

                $(this).prop('type', 'submit');
            }
        });

        document.onkeyup = function() {

            var e = e || window.event; // for IE to cover IEs window event-object

            if (e.ctrlKey && e.which == 13) {

                $('#save').click();
                return false;
            } else if (e.altKey && e.which == 71) {

                $('#addDepartment').click();
            } else if (e.which == 27) {

                $('.select_area').hide();
                $('#list').empty();
                $('.modal').modal('hide');
                return false;
            }
        }

        //Add stock issue request by ajax
        $('#edit_stock_issue_form').on('submit', function(e) {
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

                    toastr.error('Please check again all form fields.', 'Some thing went wrong.');

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
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

                $('#' + nextId).focus().select();
            }
        });

        $(document).on('change keypress', 'input', function(e) {

            var nextId = $(this).data('next');

            if (e.which == 13) {

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

        $('body').keyup(function(e) {

            if (e.keyCode == 13 || e.keyCode == 9) {

                $(".selectProduct").click();
                $('#list').empty();
                keyName = e.keyCode;
            }
        });

        $(document).on('click', '#select_product', function(e) {

            e.preventDefault();
        });

        document.getElementById('search_product').focus();

        $(document).on('click', function(e) {

            if ($(e.target).closest(".select_area").length === 0) {

                $('.select_area').hide();
                $('#list').empty();
            }
        });

        setInterval(function() {
            $('#search_product').removeClass('is-invalid');
        }, 500);

        setInterval(function() {
            $('#search_product').removeClass('is-valid');
        }, 1000);

        function roundOfValue(val) {

            return ((parseFloat(val) * 1000) / 1000);
        }
    </script>
@endpush
