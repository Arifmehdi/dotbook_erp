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
            padding: 3px 3px;
            display: block;
            border: 1px solid lightgray;
            margin-top: 3px;
        }

        .select_area ul li a:hover {
            background-color: #999396;
            color: #fff;
        }

        .selectProduct {
            background-color: #746e70;
            color: #fff !important;
        }

        .input-group-text-sale {
            font-size: 7px !important;
        }

        b {
            font-weight: 500;
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
@endpush
@section('content')
    <div class="body-wraper">
        <div class="sec-name">
            <h6>@lang('menu.add_stock_adjustment')</h6>
            <x-back-button />
        </div>
        <div class="container-fluid p-0">
            <form id="add_adjustment_form" action="{{ route('stock.adjustments.store') }}" method="POST">
                @csrf
                <section class="p-15">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element mt-0 mb-1 rounded">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-xl-4 col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.expense_ledger') <span class="text-danger">*</span></b></label>
                                                <div class="col-8">
                                                    <select required name="expense_account_id" class="form-control select2 form-select" id="expense_account_id" data-next="warehouse_id">
                                                        <option value="">@lang('menu.select_expense_ledger_ac')</option>
                                                        @foreach ($expenseAccounts as $expenseAccount)
                                                            <option value="{{ $expenseAccount->id }}">
                                                                {{ $expenseAccount->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_expense_account_id"></span>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('menu.warehouse')</b> <span class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <select required class="form-control form-select" name="warehouse_id" id="warehouse_id" data-next="date">
                                                        <option value="">@lang('menu.select_warehouse')</option>
                                                        @foreach ($warehouses as $w)
                                                            <option data-w_name="{{ $w->warehouse_name . '/' . $w->warehouse_code }}" value="{{ $w->id }}">
                                                                {{ $w->warehouse_name . '/' . $w->warehouse_code }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_warehouse_id"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.adjust'). @lang('menu.date') </b>
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <div class="col-8">
                                                    <input required type="text" name="date" class="form-control" value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}" id="date" data-next="type">
                                                    <span class="error error_date"></span>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>@lang('menu.type')</b> <span class="text-danger">*</span>
                                                    <i data-bs-toggle="tooltip" data-bs-placement="top" title="Normal: like Leakage, Damage etc. Abnormal: like Fire, Accident, stolen etc." class="fas fa-info-circle tp"></i>
                                                </label>

                                                <div class="col-8">
                                                    <select required name="type" class="form-control form-select" id="type" data-next="search_product">
                                                        <option value="">@lang('menu.select_type')</option>
                                                        <option value="1">@lang('menu.normal')</option>
                                                        <option value="2">@lang('menu.abnormal')</option>
                                                    </select>
                                                    <span class="error error_type"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-md-6">
                                            <div class="input-group">
                                                <label class=" col-4"><b>@lang('menu.voucher_no') </b></label>
                                                <div class="col-8">
                                                    <input readonly type="text" name="voucher_no" id="voucher_no" class="form-control fw-bold" placeholder="@lang('menu.voucher_no')">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="sale-content mb-1">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="item-details-sec">
                                    <div class="content-inner">
                                        <div class="row">
                                            <div class="col-xl-3">
                                                <div class="searching_area" style="position: relative;">
                                                    <label class="col-form-label">@lang('menu.search_item')</label>
                                                    <div class="input-group ">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-barcode text-dark input_f"></i></span>
                                                        </div>

                                                        <input type="text" name="search_product" class="form-control fw-bold" id="search_product" onkeyup="event.preventDefault();" placeholder="@lang('menu.search_item_item_code_scan_bar_code')" autocomplete="off" autofocus>
                                                    </div>

                                                    <div class="select_area">
                                                        <ul id="list" class="variant_list_area"></ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-9">
                                                <div class="hidden_field">
                                                    <input type="hidden" id="e_unique_id">
                                                    <input type="hidden" id="e_item_name">
                                                    <input type="hidden" id="e_base_unit_name">
                                                    <input type="hidden" id="e_product_id">
                                                    <input type="hidden" id="e_variant_id">
                                                    <input type="hidden" id="e_base_unit_cost_inc_tax">
                                                </div>

                                                <div class="row mt-1">
                                                    <div class="col-xl-3 col-md-4">
                                                        <label><b>@lang('menu.quantity')</b></label>
                                                        <div class="input-group">
                                                            <input type="number" step="any" class="form-control fw-bold w-60" id="e_showing_quantity" placeholder="@lang('menu.quantity')" value="0.00">
                                                            <input type="hidden" id="e_quantity" value="0.00">
                                                            <select id="e_unit_id" class="form-control w-40 form-select">
                                                                <option value="">@lang('menu.select_unit')</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-3 col-md-4">
                                                        <label><b>@lang('menu.unit_cost_inc_tax')</b></label>
                                                        <input type="number" step="any" class="form-control fw-bold" id="e_showing_unit_cost_inc_tax" value="0.00">
                                                        <input type="hidden" id="e_unit_cost_inc_tax" value="0.00">
                                                    </div>

                                                    <div class="col-xl-3 col-md-4">
                                                        <label><b>@lang('menu.lot_number')</b></label>
                                                        <input type="text" class="form-control fw-bold" id="e_lot_number" placeholder="@lang('menu.lot_number')">
                                                    </div>

                                                    <div class="col-xl-2 col-md-4">
                                                        <label><b>@lang('menu.sub_total')</b></label>
                                                        <input readonly type="number" step="any" class="form-control fw-bold" id="e_subtotal" value="0.00" tabindex="-1">
                                                    </div>

                                                    <div class="col-xl-1 col-md-4 mt-4">
                                                        <a href="#" class="btn btn-sm btn-success px-2" id="add_item">@lang('menu.add')</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="sale-item-sec">
                                                <div class="sale-item-inner">
                                                    <div class="table-responsive">
                                                        <table class="display data__table table-striped">
                                                            <thead class="staky">
                                                                <tr>
                                                                    <th>@lang('menu.item')</th>
                                                                    <th>@lang('menu.stock_location')</th>
                                                                    <th class="text-center">@lang('menu.quantity')</th>
                                                                    <th>@lang('menu.unit')</th>
                                                                    <th>@lang('menu.unit_cost_inc_tax')</th>
                                                                    <th>@lang('menu.sub_total')</th>
                                                                    <th><i class="fas fa-trash-alt"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="stock_adjustment_product_list"></tbody>
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

                    <div class="row g-1 mb-1">
                        <div class="col-lg-6">
                            <div class="form_element rounded m-0">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <div class="input-group">
                                                            <label class="col-4"><b>@lang('menu.total_item') </b></label>
                                                            <div class="col-8">
                                                                <input readonly type="number" step="any" name="total_item" class="form-control fw-bold" id="total_item" value="0.00" tabindex="-1">
                                                                <input type="hidden" name="total_qty" class="form-control fw-bold" id="total_qty" value="0.00">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <div class="input-group">
                                                            <label class="col-4"><b>@lang('menu.net_total_amount') </b> </label>
                                                            <div class="col-8">
                                                                <input readonly type="number" class="form-control fw-bold" step="any" step="any" name="net_total_amount" id="net_total_amount" value="0.00" tabindex="-1">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class=" col-4"><b>@lang('menu.reason') </b></label>
                                                        <div class="col-8">
                                                            <input type="text" name="reason" class="form-control" data-next="recovered_amount" placeholder="@lang('menu.reason')" autocomplete="off">
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
                            <div class="form_element rounded m-0 ">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>@lang('menu.recovered_amount_receipt') </b>
                                                            <strong>>></strong></label>
                                                        <div class="col-8">
                                                            <input type="number" step="any" name="recovered_amount" id="recovered_amount" class="form-control fw-bold" value="0.00" data-next="payment_method_id">
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('menu.payment_method') <span class="text-danger">*</span></b> </label>
                                                        <div class="col-8">
                                                            <select required name="payment_method_id" class="form-control form-select" id="payment_method_id" data-next="account_id">
                                                                @foreach ($methods as $method)
                                                                    <option value="{{ $method->id }}">{{ $method->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <span class="error error_payment_method_id"></span>
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('menu.debit_account') <span class="text-danger">*</span></b> </label>
                                                        <div class="col-8">
                                                            <select name="account_id" class="form-control form-select" id="account_id" data-next="save">
                                                                <option value="">Select Debit A/c</option>
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="submit_button_area">
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="loading-btn-box">
                                    <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                                    <button name="save" id="save" value="0" class="btn btn-success submit_button float-end">@lang('menu.save')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>
    <!--Add Product Modal End-->
@endsection
@push('scripts')
    <script src="{{ asset('plugins/select_li/selectli.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <script>
        $('.select2').select2();
        var itemUnitsArray = [];

        var branch_name = "{{ json_decode($generalSettings->business, true)['shop_name'] }}";

        function calculateTotalAmount() {

            var quantities = document.querySelectorAll('#quantity');
            var subtotals = document.querySelectorAll('#subtotal');
            // Update Total Item
            var total_item = 0;
            var total_qty = 0;
            quantities.forEach(function(qty) {

                total_item += 1;
                total_qty += qty.value ? parseFloat(qty.value) : 0;
            });

            $('#total_item').val(parseFloat(total_item));
            $('#total_qty').val(parseFloat(total_qty));
            // Update Net total Amount

            var netTotalAmount = 0;
            subtotals.forEach(function(subtotal) {

                netTotalAmount += parseFloat(subtotal.value);
            });

            $('#net_total_amount').val(parseFloat(netTotalAmount).toFixed(2));
        }

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
            var keyword = $(this).val();
            var __keyword = keyword.replaceAll('/', '~');

            delay(function() {
                searchProduct(__keyword);
            }, 200);
        });

        function searchProduct(keyWord) {

            var isShowNotForSaleItem = 1;
            var url = "{{ route('general.product.search.common', [':keyWord', ':isShowNotForSaleItem']) }}";
            var route = url.replace(':keyWord', keyWord);
            route = route.replace(':isShowNotForSaleItem', isShowNotForSaleItem);

            $.ajax({
                url: route,
                dataType: 'json',
                success: function(product) {

                    if (!$.isEmptyObject(product.errorMsg)) {

                        toastr.error(product.errorMsg);
                        $('#search_product').val("");
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

                                var name = product.name.length > 35 ? product.name.substring(0, 35) + '...' :
                                    product.name;

                                var unique_id = product.id + 'noid';

                                $('#search_product').val(name);
                                $('#e_unique_id').val(unique_id);
                                $('#e_item_name').val(name);
                                $('#e_product_id').val(product.id);
                                $('#e_variant_id').val('noid');
                                $('#e_showing_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                                $('#e_showing_unit_cost_inc_tax').val(product.product_cost_with_tax);
                                $('#e_base_unit_cost_inc_tax').val(product.product_cost_with_tax);
                                $('#e_lot_number').val('');

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

                                        var multiplierDetails = '(1 ' + unit.name + ' = ' + unit
                                            .base_unit_multiplier + '/' + unit.name + ')';

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

                                calculateEditOrAddAmount();
                                $('#add_item').html('Add');
                            } else {

                                var li = "";

                                $.each(product.variants, function(key, variant) {

                                    product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/default.jpg') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;

                                    li += '<li class="mt-1">';
                                    li += '<a class="select_variant_product" onclick="selectProduct(this); return false;" data-p_id="' +
                                        product.id + '" data-v_id="' + variant.id + '" data-p_name="' + product.name + '" data-v_code="' + variant.variant_code + '" data-p_cost_inc_tax="' + variant.variant_cost_with_tax + '" data-v_name="' + variant.variant_name + '" href="#"><img style="width:20px; height:20px;"src="' + product.thumbnail_photo + '"> ' + product.name + ' (' + product.product_code + ')' + '</a>';
                                    li += '</li>';
                                });

                                $('.variant_list_area').html(li);
                                $('.select_area').show();
                                $('#search_product').val('');
                            }
                        } else if (!$.isEmptyObject(product.variant_product)) {

                            $('.select_area').hide();

                            var variant = product.variant_product;
                            var name = variant.product.name.length > 35 ? product.name.substring(0, 35) + '...' : variant_product.product.name;

                            $('#search_product').val(name + ' - ' + variant.variant_name);
                            $('#e_item_name').val(name + ' - ' + variant.variant_name);
                            $('#e_product_id').val(variant.product.id);
                            $('#e_variant_id').val(variant.id);
                            $('#e_showing_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                            $('#e_showing_unit_cost_inc_tax').val(variant.variant_cost_with_tax);
                            $('#e_base_unit_cost_inc_tax').val(variant.variant_cost_with_tax);
                            $('#e_lot_number').val('');

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

                                    var multiplierDetails = '(1 ' + unit.name + ' = ' + unit.base_unit_multiplier + '/' + unit.name + ')';

                                    itemUnitsArray[variant.product.id].push({
                                        'unit_id': unit.id,
                                        'unit_name': unit.name,
                                        'unit_code_name': unit.code_name,
                                        'base_unit_multiplier': unit.base_unit_multiplier,
                                        'multiplier_details': multiplierDetails,
                                        'is_base_unit': 0,
                                    });

                                    $('#e_unit_id').append(
                                        '<option value="' + unit.id +
                                        '" data-is_base_unit="0" data-unit_name="' + unit.name +
                                        '" data-base_unit_multiplier="' + unit
                                        .base_unit_multiplier + '">' + unit.name +
                                        multiplierDetails + '</option>'
                                    );
                                });
                            }

                            calculateEditOrAddAmount();
                            $('#add_item').html('Add');
                        } else if (!$.isEmptyObject(product.namedProducts)) {

                            if (product.namedProducts.length > 0) {

                                var li = "";
                                var products = product.namedProducts;

                                $.each(products, function(key, product) {

                                    product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/default.jpg') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;

                                    if (product.is_variant == 1) {

                                        li += '<li class="mt-1">';
                                        li += '<a class="select_variant_product" onclick="selectProduct(this); return false;" data-p_id="' + product.id + '" data-v_id="' + product.variant_id + '" data-p_name="' + product.name + '" data-unit="' + product.unit_name + '" data-v_code="' + product.variant_code + '" data-p_cost_inc_tax="' + product.variant_cost_with_tax + '" data-v_name="' + product.variant_name + '" href="#"><img style="width:20px; height:20px;" src="' +
                                            product.thumbnail_photo + '"> ' + product.name + ' - ' + product.variant_name + ' (' + product.variant_code + ')' + '</a>';
                                        li += '</li>';

                                    } else {

                                        li += '<li class="mt-1">';
                                        li += '<a class="select_single_product" onclick="selectProduct(this); return false;" data-p_id="' +
                                            product.id + '" data-p_name="' + product.name + '" data-unit="' + product.unit_name + '" data-p_code="' +
                                            product.product_code + '" data-p_cost_inc_tax="' + product.product_cost_with_tax + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + ' (' + product.product_code + ')' + '</a>';
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
                }
            });
        };

        // select single product and add purchase table
        var keyName = 1;

        function selectProduct(e) {

            $('.select_area').hide();
            $('#search_product').val('');

            var product_id = e.getAttribute('data-p_id');
            var variant_id = e.getAttribute('data-v_id');
            var product_name = e.getAttribute('data-p_name');
            var variant_name = e.getAttribute('data-v_name');
            var product_unit = e.getAttribute('data-unit');
            var product_code = e.getAttribute('data-p_code');
            var unit_cost_inc_tax = e.getAttribute('data-p_cost_inc_tax');

            var url = "{{ route('general.product.search.product.unit.and.multiplier.unit', [':product_id']) }}"
            var route = url.replace(':product_id', product_id);

            $.ajax({
                url: route,
                dataType: 'json',
                success: function(baseUnit) {

                    $('#search_product').val(product_name + (variant_name ? ' - ' + variant_name : ''));
                    $('#e_item_name').val(product_name + (variant_name ? ' - ' + variant_name : ''));
                    $('#e_product_id').val(product_id);
                    $('#e_variant_id').val(variant_id ? variant_id : 'noid');
                    $('#e_showing_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                    $('#e_showing_unit_cost_inc_tax').val(parseFloat(unit_cost_inc_tax).toFixed(2));
                    $('#e_base_unit_cost_inc_tax').val(parseFloat(unit_cost_inc_tax).toFixed(2));
                    $('#e_lot_number').val('');

                    $('#e_unit_id').empty();
                    $('#e_unit_id').append(
                        '<option value="' + baseUnit.id +
                        '" data-is_base_unit="1" data-unit_name="' + baseUnit.name +
                        '" data-base_unit_multiplier="1">' + baseUnit.name + '</option>'
                    );

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

                            $('#e_unit_id').append(
                                '<option value="' + unit.id +
                                '" data-is_base_unit="0" data-unit_name="' + unit.name +
                                '" data-base_unit_multiplier="' + unit.base_unit_multiplier + '">' +
                                unit.name + multiplierDetails + '</option>'
                            );
                        });
                    }
                }
            });

            calculateEditOrAddAmount();
        }

        $('#add_item').on('click', function(e) {
            e.preventDefault();

            var e_unique_id = $('#e_unique_id').val();
            var e_item_name = $('#e_item_name').val();
            var e_product_id = $('#e_product_id').val();
            var e_variant_id = $('#e_variant_id').val();
            var e_unit_id = $('#e_unit_id').val();
            var e_unit_name = $('#e_unit_id').find('option:selected').data('unit_name');
            var e_showing_quantity = $('#e_showing_quantity').val() ? $('#e_showing_quantity').val() : 0;
            var e_quantity = $('#e_quantity').val() ? $('#e_quantity').val() : 0;
            var e_showing_unit_cost_inc_tax = $('#e_showing_unit_cost_inc_tax').val() ? $('#e_showing_unit_cost_inc_tax').val() : 0;
            var e_unit_cost_inc_tax = $('#e_unit_cost_inc_tax').val() ? $('#e_unit_cost_inc_tax').val() : 0;
            var e_lot_number = $('#e_lot_number').val() ? $('#e_lot_number').val() : '';
            var e_subtotal = $('#e_subtotal').val() ? $('#e_subtotal').val() : 0;
            var warehouse_id = $('#warehouse_id').val();

            var warehouse_id = $('#warehouse_id').val();
            var warehouse_name = $('#warehouse_id').find('option:selected').data('w_name');

            var stock_location_name = '';
            if (warehouse_id) {

                stock_location_name = warehouse_name;
            } else {

                stock_location_name = branch_name;
            }

            if (e_showing_quantity == '') {

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

                        var stockLocationMessage = warehouse_id ? ' in selected warehouse' :
                            ' in the company';
                        if (parseFloat(e_quantity) > parseFloat(data.stock)) {

                            toastr.error('Current stock is ' + parseFloat(data.stock) +
                                stockLocationMessage);
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
                            tr += '<input type="hidden" class="unique_id" id="' + e_product_id + e_variant_id + warehouse_id + '" value="' + e_product_id + e_variant_id + warehouse_id + '">';
                            tr += '</td>';

                            tr += '<td class="text-start">';
                            tr += '<input type="hidden" name="warehouse_ids[]" id="warehouse_id" value="' + warehouse_id + '">';
                            tr += '<span id="stock_location_name">' + stock_location_name + '</span>';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<b><p id="span_showing_quantity" class="fw-bold">' + parseFloat(e_showing_quantity).toFixed(2) + '</p></b>';
                            tr += '<input type="hidden" id="showing_quantity" value="' + parseFloat(e_showing_quantity).toFixed(2) + '">';
                            tr += '<input type="hidden" name="quantities[]" id="quantity" value="' + parseFloat(e_quantity).toFixed(2) + '">';
                            tr += '<b><p>Lot No: <spna id="span_lot_number" class="fw-bold">' + e_lot_number + '</spna></b>';
                            tr += '<input type="hidden" name="lot_numbers[]" id="lot_number" value="' + e_lot_number + '">';
                            tr += '</td>';

                            tr += '<td class="text">';
                            tr += '<b><span id="span_unit" class="fw-bold">' + e_unit_name + '</span></b>';
                            tr += '<input type="hidden" name="unit_ids[]" id="unit_id" value="' + e_unit_id + '">';
                            tr += '</td>';

                            tr += '<td class="text-center">';
                            tr += '<span id="span_unit_cost_inc_tax" class="fw-bold">' + parseFloat(e_showing_unit_cost_inc_tax).toFixed(2) + '</span>';
                            tr += '<input type="hidden" id="showing_unit_cost_inc_tax" value="' + parseFloat(e_showing_unit_cost_inc_tax).toFixed(2) + '">';
                            tr += '<input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="' + parseFloat(e_unit_cost_inc_tax).toFixed(2) + '">';
                            tr += '</td>';

                            tr += '<td class="text text-center">';
                            tr += '<span id="span_subtotal" class="fw-bold">' + parseFloat(e_subtotal).toFixed(2) + '</span>';
                            tr += '<input type="hidden" name="subtotals[]" id="subtotal" value="' + parseFloat(e_subtotal).toFixed(2) + '">';
                            tr += '</td>';

                            tr += '<td class="text-center">';
                            tr += '<a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                            tr += '</td>';
                            tr += '</tr>';

                            $('#stock_adjustment_product_list').append(tr);
                            clearEditItemFileds();
                            calculateTotalAmount();
                        } else {

                            var tr = $('#' + (e_unique_id ? e_unique_id : uniqueIdForPreventDuplicateEntry)).closest('tr');

                            tr.find('#item_name').val(e_item_name);
                            tr.find('#product_id').val(e_product_id);
                            tr.find('#variant_id').val(e_variant_id);
                            tr.find('#span_unit_cost_inc_tax').html(parseFloat(e_unit_cost_inc_tax).toFixed(2));
                            tr.find('#showing_unit_cost_inc_tax').val(parseFloat(e_showing_unit_cost_inc_tax).toFixed(2));
                            tr.find('#unit_cost_inc_tax').val(parseFloat(e_unit_cost_inc_tax).toFixed(2));
                            tr.find('#span_showing_quantity').html(parseFloat(e_showing_quantity).toFixed(2));
                            tr.find('#showing_quantity').val(parseFloat(e_showing_quantity).toFixed(2));
                            tr.find('#quantity').val(parseFloat(e_quantity).toFixed(2));
                            tr.find('#span_lot_number').html(e_lot_number);
                            tr.find('#lot_number').val(e_lot_number);
                            tr.find('#span_unit').html(e_unit_name);
                            tr.find('#unit_id').val(e_unit_id);
                            tr.find('#span_subtotal').html(parseFloat(e_subtotal).toFixed(2));
                            tr.find('#subtotal').val(parseFloat(e_subtotal).toFixed(2));

                            tr.find('.unique_id').val(e_product_id + e_variant_id + warehouse_id);
                            tr.find('.unique_id').attr('id', e_product_id + e_variant_id + warehouse_id);
                            tr.find('#warehouse_id').val(warehouse_id);
                            tr.find('#stock_location_name').html(stock_location_name);

                            clearEditItemFileds();
                            calculateTotalAmount();
                            return;
                        }
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
            var stock_location_name = tr.find('#stock_location_name').html();
            var item_name = tr.find('#item_name').val();
            var product_id = tr.find('#product_id').val();
            var variant_id = tr.find('#variant_id').val();
            var unit_cost_inc_tax = tr.find('#unit_cost_inc_tax').val();
            var showing_unit_cost_inc_tax = tr.find('#showing_unit_cost_inc_tax').val();
            var quantity = tr.find('#quantity').val();
            var showing_quantity = tr.find('#showing_quantity').val();
            var lot_number = tr.find('#lot_number').val();
            var unit_id = tr.find('#unit_id').val();
            var subtotal = tr.find('#subtotal').val();

            $('#search_product').val(item_name);
            $('#e_unique_id').val(unique_id);
            $('#warehouse_id').val(warehouse_id);
            $('#e_stock_location_name').val(stock_location_name);
            $('#e_item_name').val(item_name);
            $('#e_product_id').val(product_id);
            $('#e_variant_id').val(variant_id);
            $('#e_showing_quantity').val(parseFloat(showing_quantity).toFixed(2)).focus().select();
            $('#e_quantity').val(parseFloat(quantity).toFixed(2)).focus().select();
            $('#e_lot_number').val(lot_number);
            $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));
            $('#e_unit_cost_inc_tax').val(parseFloat(unit_cost_inc_tax).toFixed(2));
            $('#e_showing_unit_cost_inc_tax').val(parseFloat(showing_unit_cost_inc_tax).toFixed(2));
            $('#e_base_unit_cost_inc_tax').val(parseFloat(unit_cost_inc_tax).toFixed(2));

            $('#e_unit_id').empty();

            itemUnitsArray[product_id].forEach(function(unit) {

                $('#e_unit_id').append(
                    '<option ' + (unit_id == unit.unit_id ? 'selected' : '') +
                    ' value="' + unit.unit_id + '" data-is_base_unit="' + unit.is_base_unit +
                    '" data-unit_name="' + unit.unit_name + '" data-base_unit_multiplier="' + unit
                    .base_unit_multiplier + '">' + unit.unit_name + unit.multiplier_details +
                    '</option>'
                );
            });

            $('#add_item').html('Edit');
        });

        function calculateEditOrAddAmount() {

            var base_unit_multiplier = $('#e_unit_id').find('option:selected').data('base_unit_multiplier');
            var is_base_unit = $('#e_unit_id').find('option:selected').data('is_base_unit');
            var e_showing_quantity = $('#e_showing_quantity').val() ? $('#e_showing_quantity').val() : 0;
            var e_showing_unit_cost_inc_tax = $('#e_showing_unit_cost_inc_tax').val() ? $('#e_showing_unit_cost_inc_tax').val() : 0;

            var quantity = roundOfValue(e_showing_quantity) * roundOfValue(base_unit_multiplier);
            $('#e_quantity').val(parseFloat(quantity).toFixed(2));

            var unitCostIncTax = roundOfValue(e_showing_unit_cost_inc_tax) / roundOfValue(base_unit_multiplier);
            $('#e_unit_cost_inc_tax').val(roundOfValue(unitCostIncTax));
            $('#e_base_unit_cost_inc_tax').val(roundOfValue(unitCostIncTax));

            var subtotal = parseFloat(unitCostIncTax) * parseFloat(quantity);
            $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));
        }

        function clearEditItemFileds() {

            $('#search_product').val('').focus();
            $('#e_unique_id').val('');
            $('#e_item_name').val('');
            $('#e_product_id').val('');
            $('#e_variant_id').val('');
            $('#e_quantity').val(parseFloat(0).toFixed(2));
            $('#e_showing_quantity').val(parseFloat(0).toFixed(2));
            $('#e_showing_unit_cost_inc_tax').val(parseFloat(0).toFixed(2));
            $('#e_unit_cost_inc_tax').val(parseFloat(0).toFixed(2));
            $('#e_base_unit_cost_inc_tax').val(parseFloat(0).toFixed(2));
            $('#e_lot_number').val('');
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

                if ($(this).val() != '' && parseFloat($(this).val()) > 0) {

                    $('#e_lot_number').focus().select();
                }
            }
        });

        $('#e_lot_number').on('input keypress', function(e) {

            calculateEditOrAddAmount();
            if (e.which == 13) {

                $('#add_item').focus();
            }
        });

        // Remove product form purchase product list (Table)
        $(document).on('click', '#remove_product_btn', function(e) {
            e.preventDefault();
            $(this).closest('tr').remove();
            calculateTotalAmount();

            setTimeout(function() {

                clearEditItemFileds();
            }, 5);

        });

        $(document).on('click keypress focus blur change', '.form-control', function(event) {

            $('.submit_button').prop('type', 'button');
        });

        var isAllowSubmit = true;
        $(document).on('click', '.submit_button', function() {

            var value = $(this).val();
            $('#action').val(value);

            if (isAllowSubmit) {

                $(this).prop('type', 'submit');
            } else {

                $(this).prop('type', 'button');
            }
        });

        document.onkeyup = function() {

            var e = e || window.event; // for IE to cover IEs window event-object
            if (e.ctrlKey && e.which == 13) {

                $('#save').click();
                return false;
            } else if (e.which == 27) {

                $('.select_area').hide();
                $('#list').empty();
                return false;
            }
        }

        //Add purchase request by ajax
        $('#add_adjustment_form').on('submit', function(e) {
            e.preventDefault();

            var totalItem = $('#total_item').val();
            if (parseFloat(totalItem) == 0) {

                toastr.error('Item table is empty.', 'Some thing went wrong.');
                return;
            }

            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            isAjaxIn = false;
            isAllowSubmit = false;
            $.ajax({
                beforeSend: function() {
                    isAjaxIn = true;
                },
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    $('.loading_button').hide();
                    $('.error').html('');
                    isAjaxIn = true;
                    isAllowSubmit = true;
                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                    } else {

                        toastr.success(data);
                        window.location = "{{ route('stock.adjustments.index') }}";
                    }
                },
                error: function(err) {

                    isAjaxIn = true;
                    isAllowSubmit = true;
                    $('.loading_button').hide();
                    $('.error').html('');

                    if (err.status == 0) {
                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    }

                    toastr.error('Please check again all form fields.', 'Some thing went wrong.');

                    $.each(err.responseJSON.errors, function(key, error) {
                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });

            if (isAjaxIn == false) {

                isAllowSubmit = true;
            }
        });

        // Automatic remove searching product is found signal
        setInterval(function() {
            $('#search_product').removeClass('is-invalid');
        }, 500);

        setInterval(function() {
            $('#search_product').removeClass('is-valid');
        }, 1000);

        $('body').keyup(function(e) {

            if (e.keyCode == 13 || e.keyCode == 9) {

                $(".selectProduct").click();
                $('#list').empty();
            }
        });

        $(document).on('click', function(e) {

            if ($(e.target).closest(".select_area").length === 0) {

                $('.select_area').hide();
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

                $('#' + nextId).focus().select();
            }
        });

        $(document).on('change keypress', 'input', function(e) {

            var nextId = $(this).data('next');

            if (e.which == 13) {

                if ($(this).attr('id') == 'recovered_amount' && ($('#recovered_amount').val() == '' || parseFloat($('#recovered_amount').val()) == 0)) {

                    $('#save').focus();
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

        $('#expense_account_id').focus();
    </script>
@endpush
