@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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

        .c-delete:focus {
            border: 1px solid gray;
            padding: 2px;
        }

        .element-body {
            overflow: initial !important;
        }
    </style>
@endpush
@section('content')
    <div class="body-wraper">
        <div class="sec-name">
            <h6>@lang('menu.add_daily_stock')</h6>
            <x-back-button />
        </div>
        <div class="container-fluid p-0">
            <form id="add_daily_stock_form" action="{{ route('daily.stock.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="action" id="action" value="">

                <section class="p-15">
                    <div class="row g-0">
                        <div class="form_element rounded m-0 mb-1">
                            <div class="element-body">
                                <div class="row">
                                    <div class="col-xl-3 col-md-6">
                                        @if (count($warehouses) > 0)
                                            <input name="warehouse_count" value="YES" type="hidden" />
                                            <div class="input-group">
                                                <label class="col-4"><span class="text-danger">*</span>
                                                    <b>@lang('menu.warehouse') </b> </label>
                                                <div class="col-8">
                                                    <select required class="form-control form-select" name="warehouse_id" id="warehouse_id" data-next="date" autofocus>
                                                        <option value="">@lang('menu.select_warehouse')</option>
                                                        @foreach ($warehouses as $w)
                                                            <option value="{{ $w->id }}">
                                                                {{ $w->warehouse_name . '/' . $w->warehouse_code }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_warehouse_id"></span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.stock_location') </b> </label>
                                                <div class="col-8">
                                                    <input readonly type="text" class="form-control" value="{{ json_decode($generalSettings->business, true)['shop_name'] }}" />
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-xl-3 col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b><span class="text-danger">*</span>
                                                    @lang('menu.date')</b></label>
                                            <div class="col-8">
                                                <input required type="text" name="date" class="form-control" value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}" id="date" data-next="reported_by" placeholder="dd-mm-yyyy" autocomplete="off" autofocus>
                                                <span class="error error_date"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>@lang('menu.reported_by') </b></label>
                                            <div class="col-8">
                                                <input type="text" name="reported_by" class="form-control" id="reported_by" data-next="search_product" placeholder="@lang('menu.reported_by')" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>@lang('menu.voucher_no') </b></label>
                                            <div class="col-8">
                                                <input readonly type="text" name="voucher_no" id="voucher_no" class="form-control fw-bold" placeholder="@lang('menu.voucher_no')" autocomplete="off" tabindex="-1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row sale-content">
                        <div class="col-md-12">
                            <div class="item-details-sec">
                                <div class="content-inner">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="hidden_field">
                                                <input type="hidden" id="e_item_name">
                                                <input type="hidden" id="e_product_id">
                                                <input type="hidden" id="e_variant_id">
                                                <input type="hidden" id="e_tax_amount">
                                                <input type="hidden" id="e_showing_tax_amount">
                                                <input type="hidden" id="e_unit_cost_inc_tax">
                                                <input type="hidden" id="e_showing_unit_cost_inc_tax">
                                                <input type="hidden" id="e_base_unit_cost_exc_tax">
                                            </div>

                                            <div class="row align-items-end">
                                                <div class="col-xl-3">
                                                    <div class="searching_area" style="position: relative;">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-barcode text-dark input_f"></i></span>
                                                            </div>

                                                            <input type="text" name="search_product" class="form-control fw-bold" autocomplete="off" id="search_product" onkeyup="event.preventDefault();" placeholder="@lang('menu.search_item_item_code_scan_bar_code')">
                                                        </div>
                                                        <div class="select_area">
                                                            <ul id="list" class="variant_list_area"></ul>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xl-2 col-md-4">
                                                    <label><b>@lang('menu.stock_qty')</b></label>
                                                    <div class="input-group">
                                                        <input type="number" step="any" class="form-control fw-bold w-50" id="e_showing_quantity" placeholder="@lang('menu.quantity')" value="0.00">
                                                        <input type="hidden" id="e_quantity" value="0.00">
                                                        <select id="e_unit_id" class="form-control w-50 form-select">
                                                            <option value="">@lang('menu.select_unit')</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-xl-2 col-md-4">
                                                    <label><b>@lang('menu.unit_cost_exc_tax')</b></label>
                                                    <input type="number" step="any" class="form-control fw-bold" id="e_showing_unit_cost_exc_tax" value="0.00">
                                                    <input type="hidden" id="e_unit_cost_exc_tax" value="0.00">
                                                </div>

                                                <div class="col-xl-2 col-md-4">
                                                    <label><b>@lang('menu.unit_tax') (@lang('menu.per') @lang('menu.unit'))</b>
                                                    </label>
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

                                                <div class="col-xl-2 col-md-4">
                                                    <label><b>@lang('menu.sub_total')</b></label>
                                                    <input readonly type="number" step="any" class="form-control fw-bold" id="e_subtotal" value="0.00" tabindex="-1">
                                                </div>

                                                <div class="col-xl-1 col-md-4">
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
                                                                <th>@lang('menu.quantity')</th>
                                                                <th>@lang('menu.unit')</th>
                                                                <th>@lang('menu.unit_cost_exc_tax')</th>
                                                                <th>@lang('menu.unit_tax')</th>
                                                                <th>@lang('menu.unit_cost_inc_tax')</th>
                                                                <th>@lang('menu.sub_total')</th>
                                                                <th><i class="fas fa-trash-alt"></i></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="stock_list"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-1 mb-1">
                        <div class="col-xl-6">
                            <div class="form_element rounded mt-1">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row g-1">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>@lang('menu.total_item_and_quantity')</b></label>
                                                        <div class="col-8">
                                                            <div class="input-group">
                                                                <input readonly name="total_item" type="number" step="any" class="form-control fw-bold" id="total_item" value="0.00" tabindex="-1">

                                                                <input readonly name="total_qty" type="number" step="any" class="form-control fw-bold" id="total_qty" value="0.00" tabindex="-1">
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label class=" col-4"><b>@lang('menu.total_stock_value')</b></label>
                                                        <div class="col-8">
                                                            <input readonly name="total_stock_value" type="number" step="any" id="total_stock_value" class="form-control fw-bold" value="0.00" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="form_element rounded mt-1">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row g-1">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>@lang('menu.production_details') </b> </label>
                                                        <div class="col-8">
                                                            <input name="production_details" type="text" step="any" class="form-control" id="production_details" data-next="note" placeholder="@lang('menu.production_details')">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>@lang('menu.special_note')</b> </label>
                                                        <div class="col-8">
                                                            <input name="note" class="form-control" id="note" data-next="save_and_print" placeholder="@lang('menu.special_note')">
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

                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="loading-btn-box">
                                <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                                <button type="submit" id="save_and_print" value="1" class="btn btn-success submit_button me-2">@lang('menu.save_and_print')</button>
                                <button type="submit" id="save" value="2" class="btn btn-success submit_button">@lang('menu.save')</button>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    {{-- <script src="{{ asset('plugins/select_li/selectli.js') }}"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('plugins/select_li/selectli.js') }}"></script>
    <script>
        var itemUnitsArray = [];

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

        function searchProduct(keyWord) {

            $('.variant_list_area').empty();
            $('.select_area').hide();
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
                        $('#search_product').val('');
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

                                $('#search_product').val(product.name);
                                $('#e_item_name').val(product.name);
                                $('#e_showing_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                                $('#e_product_id').val(product.id);
                                $('#e_variant_id').val('noid');
                                $('#e_showing_unit_cost_exc_tax').val(product.product_cost);
                                $('#e_base_unit_cost_exc_tax').val(product.product_cost);
                                $('#e_tax_ac_id').val(product.tax_ac_id);
                                $('#e_tax_type').val(product.tax_type);

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

                                    li += '<li>';
                                    li += '<a href="#" class="select_variant_product" onclick="selectProduct(this); return false;" data-p_id="' + product.id + '" data-v_id="' + variant.id + '" data-p_name="' + product.name + '" data-v_name="' + variant.variant_name + '" data-p_tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-tax_type="' + product.tax_type + '" data-v_code="' + variant.variant_code + '" data-p_cost_exc_tax="' + variant.variant_cost + '"><img style="width:20px; height:20px;" src="' + imgUrl + '/' + product.thumbnail_photo + '"> ' + product.name + ' - ' + variant.variant_name + '</a>';
                                    li += '</li>';
                                });

                                $('.variant_list_area').append(li);
                                $('.select_area').show();
                                $('#search_product').val('');
                            }
                        } else if (!$.isEmptyObject(product.namedProducts)) {

                            if (product.namedProducts.length > 0) {

                                var li = "";
                                var products = product.namedProducts;

                                $.each(products, function(key, product) {

                                    product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/default.jpg') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;

                                    if (product.is_variant == 1) {

                                        li += '<li class="mt-1">';
                                        li += '<a class="select_variant_product" onclick="selectProduct(this); return false;" data-p_id="' + product.id + '" data-v_id="' + product.variant_id + '" data-p_name="' + product.name + '" data-v_name="' + product.variant_name + '" data-p_tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-tax_type="' + product.tax_type + '" data-unit="' + product.unit_name + '" data-p_code="' + product.variant_code + '" data-p_cost_exc_tax="' + product.variant_cost + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + ' - ' + product.variant_name + '</a>';
                                        li += '</li>';
                                    } else {

                                        li += '<li class="mt-1">';
                                        li += '<a class="select_single_product" onclick="selectProduct(this); return false;" data-p_id="' + product.id + '" data-p_name="' + product.name + '" data-p_tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-tax_type="' + product.tax_type + '" data-p_code="' + product.product_code + '" data-p_cost_exc_tax="' + product.product_cost + '" data-p_name="' + product.name + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '">' + product.name + '</a>';
                                        li += '</li>';
                                    }
                                });

                                $('.variant_list_area').html(li);
                                $('.select_area').show();
                            }
                        } else if (!$.isEmptyObject(product.variant_product)) {

                            $('.select_area').hide();
                            $('#search_product').val('');

                            var variant_product = product.variant_product;

                            var productName = variant_product.product.name + ' - ' + variant_product.variant_name;
                            var __productName = variant_product.product.name + ' - ' + variant_product.variant_name;

                            $('#search_product').val(productName);
                            $('#e_item_name').val(__productName);
                            $('#e_product_id').val(variant_product.product.id);
                            $('#e_variant_id').val(variant_product.id);
                            $('#e_showing_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                            $('#e_showing_unit_cost_exc_tax').val(variant_product.variant_cost);
                            $('#e_base_unit_cost_exc_tax').val(variant_product.variant_cost);
                            $('#e_tax_ac_id').val(variant_product.product.tax_ac_id);
                            $('#e_tax_type').val(variant_product.product.tax_type);
                            $('#e_subtotal').val(variant_product.variant_cost);

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
                        }
                    } else {

                        $('#search_product').addClass('is-invalid');
                    }
                }
            });
        }

        function selectProduct(e) {

            $('.select_area').hide();

            var product_id = e.getAttribute('data-p_id');
            var variant_id = e.getAttribute('data-v_id');
            var product_name = e.getAttribute('data-p_name');
            var variant_name = e.getAttribute('data-v_name');
            var tax_ac_id = e.getAttribute('data-p_tax_ac_id');
            var tax_type = e.getAttribute('data-tax_type');
            var product_cost_exc_tax = e.getAttribute('data-p_cost_exc_tax');

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
                    $('#e_showing_unit_cost_exc_tax').val(product_cost_exc_tax);
                    $('#e_base_unit_cost_exc_tax').val(product_cost_exc_tax);
                    $('#e_tax_type').val(tax_type);
                    $('#e_tax_ac_id').val(tax_ac_id);

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

                    calculateEditOrAddAmount();
                    $('#add_item').html('Add');
                }
            });
        }

        $('#add_item').on('click', function(e) {
            e.preventDefault();

            var e_item_name = $('#e_item_name').val();
            var e_product_id = $('#e_product_id').val();
            var e_variant_id = $('#e_variant_id').val();
            var e_unit_id = $('#e_unit_id').val();
            var e_unit_name = $('#e_unit_id').find('option:selected').data('unit_name');
            var e_showing_quantity = $('#e_showing_quantity').val();
            var e_quantity = $('#e_quantity').val();
            var e_showing_unit_cost_exc_tax = $('#e_showing_unit_cost_exc_tax').val();
            var e_unit_cost_exc_tax = $('#e_unit_cost_exc_tax').val();
            var e_tax_ac_id = $('#e_tax_ac_id').val();
            var e_tax_percent = $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') ? $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') : 0;
            var e_tax_amount = $('#e_tax_amount').val() ? $('#e_tax_amount').val() : 0;
            var e_showing_tax_amount = $('#e_showing_tax_amount').val() ? $('#e_showing_tax_amount').val() : 0;
            var e_tax_type = $('#e_tax_type').val();
            var e_unit_cost_inc_tax = $('#e_unit_cost_inc_tax').val();
            var e_showing_unit_cost_inc_tax = $('#e_showing_unit_cost_inc_tax').val();
            var e_subtotal = $('#e_subtotal').val();

            var uniqueId = e_product_id + e_variant_id;

            var uniqueIdValue = $('#' + e_product_id + e_variant_id).val();

            if (e_showing_quantity == '') {

                toastr.error('Quantity field must not be empty.');
                return;
            }

            if (e_product_id == '') {

                toastr.error('Please select a product.');
                return;
            }

            if (uniqueIdValue == undefined) {

                var tr = '';
                tr += '<tr class="text-start" id="select_item">';
                tr += '<td>';
                tr += '<span id="item_name">' + e_item_name + '</span>';
                tr += '<input type="hidden" name="product_ids[]" value="' + e_product_id + '" class="productId-' + e_product_id + '" id="product_id" tabindex="-1">';
                tr += '<input type="hidden" name="variant_ids[]" value="' + e_variant_id + '" id="variant_id" tabindex="-1">';
                tr += '<input type="hidden" id="' + (e_product_id + e_variant_id) + '" value="' + (e_product_id + e_variant_id) + '" tabindex="-1">';
                tr += '</td>';

                tr += '<td>';
                tr += '<b><span class="fw-bold" id="span_showing_quantity">' + e_showing_quantity + '</span></b>';
                tr += '<input type="hidden" name="quantities[]" id="quantity" value="' + parseFloat(e_quantity).toFixed(2) + '">';
                tr += '<input type="hidden" id="showing_quantity" value="' + parseFloat(e_showing_quantity).toFixed(2) + '">';
                tr += '</td>';

                tr += '<td>';
                tr += '<span class="fw-bold" id="span_unit">' + e_unit_name + '</span>';
                tr += '<input type="hidden" name="unit_ids[]" id="unit_id" value="' + e_unit_id + '">';
                tr += '</td>';

                tr += '<td>';
                tr += '<span class="fw-bold" id="span_showing_unit_cost_exc_tax">' + parseFloat(e_showing_unit_cost_exc_tax).toFixed(2) + '</span>';
                tr += '<input type="hidden" id="showing_unit_cost_exc_tax" value="' + parseFloat(e_showing_unit_cost_exc_tax).toFixed(2) + '">';
                tr += '<input type="hidden" name="unit_costs_exc_tax[]" id="unit_cost_exc_tax" value="' + parseFloat(e_unit_cost_exc_tax).toFixed(2) + '">';
                tr += '</td>';

                tr += '<td>';
                tr += '<span class="fw-bold" id="span_tax_percent">' + parseFloat(e_tax_percent).toFixed(2) + '%' + '</span>';
                tr += '<input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="' + e_tax_ac_id + '">';
                tr += '<input type="hidden" name="tax_percents[]" id="tax_percent" value="' + parseFloat(e_tax_percent).toFixed(2) + '">';
                tr += '<input type="hidden" name="tax_types[]" id="tax_type" value="' + e_tax_type + '">';
                tr += '<input type="hidden" name="tax_amounts[]" id="tax_amount" value="' + parseFloat(e_tax_amount).toFixed(2) + '">';
                tr += '<input type="hidden" id="showing_tax_amount" value="' + parseFloat(e_tax_amount).toFixed(2) + '">';
                tr += '</td>';

                tr += '<td>';
                tr += '<span class="fw-bold" id="span_unit_cost_inc_tax">' + parseFloat(e_showing_unit_cost_inc_tax).toFixed(2) + '</span>';
                tr += '<input type="hidden" id="showing_unit_cost_inc_tax" value="' + parseFloat(e_showing_unit_cost_inc_tax).toFixed(2) + '">';
                tr += '<input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="' + parseFloat(e_unit_cost_inc_tax).toFixed(2) + '">';
                tr += '</td>';

                tr += '<td>';
                tr += '<span class="fw-bold" id="span_subtotal">' + parseFloat(e_subtotal).toFixed(2) + '</span>';
                tr += '<input type="hidden" name="subtotals[]" id="subtotal" class="form-control" value="' + parseFloat(e_subtotal).toFixed(2) + '">';
                tr += '</td>';

                tr += '<td class="text-start">';
                tr += '<a href="#" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                tr += '</td>';

                tr += '</tr>';
                $('#stock_list').append(tr);
                clearEditItemFileds();
                calculateTotalAmount();
            } else {

                var tr = $('#' + uniqueId).closest('tr');

                tr.find('#item_name').html(e_item_name);
                tr.find('#showing_quantity').val(parseFloat(e_showing_quantity).toFixed(2));
                tr.find('#quantity').val(parseFloat(e_quantity).toFixed(2));
                tr.find('#product_id').val(e_product_id);
                tr.find('#variant_id').val(e_variant_id);
                tr.find('#span_unit').val(e_unit_name);
                tr.find('#unit_id').val(e_unit_id);
                tr.find('#unit_cost_exc_tax').val(parseFloat(e_unit_cost_exc_tax).toFixed(2));
                tr.find('#showing_unit_cost_exc_tax').val(parseFloat(e_showing_unit_cost_exc_tax).toFixed(2));
                tr.find('#tax_ac_id').val(e_tax_ac_id);
                tr.find('#tax_amount').val(parseFloat(e_tax_amount).toFixed(2));
                tr.find('#showing_tax_amount').val(parseFloat(e_showing_tax_amount).toFixed(2));
                tr.find('#tax_type').val(e_tax_type);
                tr.find('#unit_cost_inc_tax').val(parseFloat(e_unit_cost_inc_tax).toFixed(2));
                tr.find('#showing_unit_cost_inc_tax').val(parseFloat(e_showing_unit_cost_inc_tax).toFixed(2));
                tr.find('#subtotal').val(parseFloat(e_subtotal).toFixed(2));
                clearEditItemFileds();
                calculateTotalAmount();
            }

            $('#add_item').html('Add');
        });

        $(document).on('click', '#select_item', function() {

            var tr = $(this);
            var item_name = tr.find('#item_name').html();
            var quantity = tr.find('#quantity').val();
            var showing_quantity = tr.find('#showing_quantity').val();
            var product_id = tr.find('#product_id').val();
            var variant_id = tr.find('#variant_id').val();
            var unit_id = tr.find('#unit_id').val();
            var unit_cost_exc_tax = tr.find('#unit_cost_exc_tax').val();
            var showing_unit_cost_exc_tax = tr.find('#showing_unit_cost_exc_tax').val();
            var tax_ac_id = tr.find('#tax_ac_id').val();
            var tax_amount = tr.find('#tax_amount').val();
            var showing_tax_amount = tr.find('#showing_tax_amount').val();
            var tax_type = tr.find('#tax_type').val();
            var unit_cost_inc_tax = tr.find('#unit_cost_inc_tax').val();
            var showing_unit_cost_inc_tax = tr.find('#showing_unit_cost_inc_tax').val();
            var subtotal = tr.find('#subtotal').val();

            $('#search_product').val(item_name);
            $('#e_item_name').val(item_name);
            $('#e_product_id').val(product_id);
            $('#e_variant_id').val(variant_id);
            $('#e_showing_quantity').val(parseFloat(showing_quantity).toFixed(2)).focus().select();
            $('#e_quantity').val(parseFloat(quantity).toFixed(2)).focus().select();
            $('#e_unit_cost_exc_tax').val(parseFloat(unit_cost_exc_tax).toFixed(2));
            $('#e_showing_unit_cost_exc_tax').val(parseFloat(showing_unit_cost_exc_tax).toFixed(2));
            $('#e_tax_ac_id').val(tax_ac_id);
            $('#e_tax_amount').val(parseFloat(tax_amount).toFixed(2));
            $('#e_showing_tax_amount').val(parseFloat(showing_tax_amount).toFixed(2));
            $('#e_tax_type').val(tax_type);
            $('#e_unit_cost_inc_tax').val(parseFloat(unit_cost_inc_tax).toFixed(2));
            $('#e_showing_unit_cost_inc_tax').val(parseFloat(showing_unit_cost_inc_tax).toFixed(2));
            $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));

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
            var e_showing_unit_cost_exc_tax = $('#e_showing_unit_cost_exc_tax').val() ? $('#e_showing_unit_cost_exc_tax').val() : 0;
            var e_tax_percent = $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') ? $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') : 0;
            var e_tax_type = $('#e_tax_type').val();

            var quantity = roundOfValue(e_showing_quantity) * roundOfValue(base_unit_multiplier);
            $('#e_quantity').val(parseFloat(quantity));

            var unitCostExcTax = roundOfValue(e_showing_unit_cost_exc_tax) / roundOfValue(base_unit_multiplier);
            $('#e_unit_cost_exc_tax').val(roundOfValue(unitCostExcTax));
            $('#e_base_unit_cost_exc_tax').val(roundOfValue(unitCostExcTax));

            var tax_amount = parseFloat(e_tax_percent) > 0 ? parseFloat(e_unit_cost_exc_tax) / 100 * parseFloat(e_tax_percent) : 0;

            var showing_taxAmount = roundOfValue(e_showing_unit_cost_exc_tax) / 100 * roundOfValue(e_tax_percent);
            var taxAmount = roundOfValue(showing_taxAmount) / roundOfValue(base_unit_multiplier);

            var showingUnitCostIncTax = roundOfValue(e_showing_unit_cost_exc_tax) + roundOfValue(showing_taxAmount);
            var unitCostIncTax = roundOfValue(showingUnitCostIncTax) / roundOfValue(base_unit_multiplier);

            if (e_tax_type == 2) {

                var inclusiveTax = 100 + parseFloat(e_tax_percent);
                var calcTax = parseFloat(e_showing_unit_cost_exc_tax) / parseFloat(inclusiveTax) * 100;
                var __tax_amount = roundOfValue(e_showing_unit_cost_exc_tax) - roundOfValue(calcTax);
                showing_taxAmount = __tax_amount;
                taxAmount = showing_taxAmount / roundOfValue(base_unit_multiplier);
                showingUnitCostIncTax = roundOfValue(e_showing_unit_cost_exc_tax) + roundOfValue(showing_taxAmount);
                unitCostIncTax = roundOfValue(showingUnitCostIncTax) / roundOfValue(base_unit_multiplier);
            }

            $('#e_showing_tax_amount').val(parseFloat(showing_taxAmount));
            $('#e_tax_amount').val(parseFloat(taxAmount));
            $('#e_showing_unit_cost_inc_tax').val(parseFloat(showingUnitCostIncTax));
            $('#e_unit_cost_inc_tax').val(parseFloat(unitCostIncTax));
            var subtotal = parseFloat(unitCostIncTax) * parseFloat(quantity);
            $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));
        }

        $(document).on('input keypress', '#e_showing_quantity', function(e) {

            calculateEditOrAddAmount();

            if (e.which == 13) {

                if ($(this).val() != '') {

                    $('#e_unit_id').focus().select();
                }
            }
        });

        $('#e_unit_id').on('change keypress click', function(e) {

            var isBaseUnit = $(this).find('option:selected').data('is_base_unit');
            var baseUnitCostExcTax = $('#e_base_unit_cost_exc_tax').val() ? $('#e_base_unit_cost_exc_tax').val() :
                0;
            var base_unit_multiplier = $(this).find('option:selected').data('base_unit_multiplier');
            var showingUnitCostExcTax = roundOfValue(baseUnitCostExcTax) * roundOfValue(base_unit_multiplier);

            $('#e_showing_unit_cost_exc_tax').val(parseFloat(showingUnitCostExcTax).toFixed(2));

            if (e.which == 0) {

                $('#e_showing_unit_cost_exc_tax').focus().select();
            }

            calculateEditOrAddAmount();
        });

        // Change tax percent and clculate row amount
        $(document).on('input keypress', '#e_showing_unit_cost_exc_tax', function(e) {

            calculateEditOrAddAmount();

            if (e.which == 13) {

                if ($(this).val() != '') {

                    $('#e_tax_ac_id').focus();
                }
            }
        });

        $('#e_tax_ac_id').on('change keypress click', function(e) {

            calculateEditOrAddAmount();

            if (e.which == 0) {

                $('#e_tax_type').focus();
            }
        });

        $(document).on('change keypress click', '#e_tax_type', function(e) {

            calculateEditOrAddAmount();

            if (e.which == 0) {

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

        function calculateTotalAmount() {

            var quantities = document.querySelectorAll('#showing_quantity');
            var subtotals = document.querySelectorAll('#subtotal');
            var total_item = 0;
            var total_qty = 0;

            quantities.forEach(function(qty) {
                total_item += 1;
                total_qty += parseFloat(qty.value)
            });

            $('#total_qty').val(parseFloat(total_qty));
            $('#total_item').val(parseFloat(total_item));

            //Update Net Total Amount
            var totalStockValue = 0;
            subtotals.forEach(function(subtotal) {
                totalStockValue += parseFloat(subtotal.value);
            });

            $('#total_stock_value').val(parseFloat(totalStockValue).toFixed(2));
        }

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

                $('#save_and_print').click();
                return false;
            } else if (e.shiftKey && e.which == 13) {

                $('#save').click();
                return false;
            } else if (e.which == 27) {

                $('.select_area').hide();
                $('#list').empty();
                return false;
            }
        }

        //Add purchase request by ajax
        $('#add_daily_stock_form').on('submit', function(e) {
            e.preventDefault();

            $('.loading_button').show();
            var url = $(this).attr('action');

            var totalItem = $('#total_item').val();

            if (parseFloat(totalItem) == 0) {

                $('.loading_button').hide();
                toastr.error('Item table is empty.');
                return;
            }

            isAjaxIn = false;
            isAllowSubmit = false;

            $.ajax({
                beforeSend: function() {
                    isAjaxIn = true;
                },
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {

                    isAjaxIn = true;
                    isAllowSubmit = true;
                    $('.error').html('');
                    $('.loading_button').hide();
                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                    } else if (data.successMsg) {

                        toastr.success(data.successMsg);
                        $('#add_daily_stock_form')[0].reset();
                        $('#stock_list').empty();
                        $('#warehouse_id').focus();
                    } else {

                        toastr.success('Successfully daily stock is added.');
                        $('#add_daily_stock_form')[0].reset();
                        $('#stock_list').empty();
                        $('#warehouse_id').focus();

                        $(data).printThis({
                            debug: false,
                            importCSS: true,
                            importStyle: true,
                            loadCSS: "{{ asset('css/print/purchase.print.css') }}",
                            removeInline: false,
                            printDelay: 1000,
                            header: null,
                        });
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

            if (isAjaxIn == false) {

                isAllowSubmit = true;
            }
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

                if ($(this).attr('id') == 'recovered_amount' && ($('#recovered_amount').val() == '' || parseFloat($(
                        '#recovered_amount').val()) == 0)) {

                    $('#save').focus();
                    return;
                }

                $('#' + nextId).focus().select();
            }
        });

        $('body').keyup(function(e) {

            if (e.keyCode == 13 || e.keyCode == 9) {

                if ($(".selectProduct").attr('href') == undefined) {

                    return;
                }

                $(".selectProduct").click();

                $('#list').empty();
                keyName = e.keyCode;
            }
        });

        // Automatic remove searching product is found signal
        setInterval(function() {

            $('#search_product').removeClass('is-invalid');
        }, 500);

        setInterval(function() {

            $('#search_product').removeClass('is-valid');
        }, 1000);

        function clearEditItemFileds() {

            $('#search_product').val('').focus();
            $('#e_item_name').val('');
            $('#e_product_id').val('');
            $('#e_variant_id').val('');
            $('#e_quantity').val(parseFloat(0).toFixed(2));
            $('#e_showing_quantity').val(parseFloat(0).toFixed(2));
            $('#e_unit_cost_exc_tax').val(parseFloat(0).toFixed(2));
            $('#e_showing_unit_cost_exc_tax').val(parseFloat(0).toFixed(2));
            $('#e_tax_amount').val(parseFloat(0).toFixed(2));
            $('#e_showing_tax_amount').val(parseFloat(0).toFixed(2));
            $('#e_tax_type').val(1);
            $('#e_tax_ac_id').val('');
            $('#e_unit_cost_inc_tax').val(parseFloat(0).toFixed(2));
            $('#e_showing_unit_cost_inc_tax').val(parseFloat(0).toFixed(2));
            $('#e_base_unit_cost_inc_tax').val(parseFloat(0).toFixed(2));
            $('#e_subtotal').val(parseFloat(0).toFixed(2));
            $('#add_item').html('Add');
        }

        $(document).on('click', function(e) {

            if ($(e.target).closest(".select_area").length === 0) {

                $('.select_area').hide();
                $('#list').empty();
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
    </script>
@endpush
