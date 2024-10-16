@extends('layout.master')
@push('css')
    <style>
        .input-group-text {
            font-size: 12px !important;
        }

        .input-group-text-sale {
            font-size: 7px !important;
        }

        .select_area {
            position: relative;
            background: #ffffff;
            box-sizing: border-box;
            position: absolute;
            width: 94%;
            z-index: 9999999;
            padding: 0;
            left: 3%;
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
            font-size: 13px;
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
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
    <div class="body-wraper">
        <div class="container-fluid p-0">
            <div class="sec-name">
                <h6>Edit Transfer Stock</h6>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm float-end back-button"><i class="fa-thin fa-left-to-line fa-2x"></i><br> @lang('menu.back')</a>
            </div>
            <form id="edit_transfer_to_branch_form" class="p-15" action="{{ route('transfer.stock.to.branch.update', $transferId) }}" method="POST">
                @csrf
                <section>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element rounded mt-0 mb-1">

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-xl-3 col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"> <b>@lang('menu.warehouse') </b><span class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <select class="form-control changeable add_input form-select" name="warehouse_id" data-name="Warehouse" id="warehouse_id">
                                                        <option value="">@lang('menu.select_warehouse')</option>
                                                        @foreach ($warehouses as $warehouse)
                                                            <option {{ $transfer->warehouse_id == $warehouse->id ? 'SELECTED' : '' }} value="{{ $warehouse->id }}">
                                                                {{ $warehouse->warehouse_name . '/' . $warehouse->warehouse_code }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="text" name="warehouse_id" id="req_warehouse_id" class="d-none" value="{{ $transfer->warehouse_id }}">
                                                    <span class="error error_warehouse_id"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"> <b>@lang('menu.b_location') </b></label>
                                                <div class="col-8">
                                                    <input readonly type="text" class="form-control" value="{{ json_decode($generalSettings->business, true)['shop_name'] }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.date') </b></label>
                                                <div class="col-8">
                                                    <input required type="text" name="date" class="form-control changeable" autocomplete="off" id="datepicker" value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($transfer->date)) }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-6">
                                            <div class="input-group">
                                                <label class=" col-4"><b>@lang('short.reference_id') </b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="invoice_id" id="invoice_id" class="form-control" autocomplete="off">
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
                    <div class="sale-content mb-1">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="item-details-sec">
                                    <div class="content-inner">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="searching_area" style="position: relative;">
                                                    <label class="col-form-label">@lang('menu.item_search')</label>
                                                    <div class="input-group ">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-barcode text-dark"></i></span>
                                                        </div>
                                                        <input type="text" name="search_product" class="form-control scanable" autocomplete="off" id="search_product" placeholder="@lang('menu.search_item_item_code_scan_bar_code')">
                                                        <div class="input-group-prepend">
                                                            <span id="add_product" class="input-group-text add_button"><i class="fas fa-plus-square text-dark"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="select_area">
                                                        <ul id="list" class="variant_list_area"></ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="sale-item-sec">
                                                <div class="sale-item-inner">
                                                    <div class="table-responsive">
                                                        <table class="table display table-sm data__table">
                                                            <thead class="staky">
                                                                <tr>
                                                                    <th>@lang('menu.item')</th>
                                                                    <th></th>
                                                                    <th class="text-center">@lang('menu.quantity')</th>
                                                                    <th class="text-center">@lang('menu.unit')</th>
                                                                    <th class="text-center">@lang('menu.sub_total')</th>
                                                                    <th><i class="fas fa-trash-alt text-white"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="transfer_list">

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

                <section class="">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element rounded mt-0 mb-1">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-xl-3 col-md-6">
                                            <div class="input-group">
                                                <label class=" col-4">@lang('menu.total_item')</label>
                                                <div class="col-8">
                                                    <input readonly name="total_item" type="number" step="any" class="form-control" id="total_item" value="0.00">
                                                    <input type="number" step="any" class="d-none" name="total_send_quantity" id="total_send_quantity">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-6">
                                            <div class="input-group">
                                                <label class=" col-4">@lang('menu.net_total')
                                                    {{ json_decode($generalSettings->business, true)['currency'] }}</label>
                                                <div class="col-8">
                                                    <input readonly name="net_total_amount" type="number" step="any" id="net_total_amount" class="form-control" value="0.00">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-6">
                                            <div class="input-group">
                                                <label class=" col-4">@lang('menu.shipping_cost')</label>
                                                <div class="col-8">
                                                    <input name="shipping_charge" type="number" class="form-control form-control-sm" id="shipping_charge" value="0.00">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-6">
                                            <div class="input-group">
                                                <label class="col-4">@lang('menu.note')</label>
                                                <div class="col-8">
                                                    <input name="additional_note" type="text" class="form-control" id="additional_note" placeholder="Additional note">
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
                                <button type="submit" class="btn btn-success submit_button">@lang('menu.update') </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('plugins/select_li/selectli.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // Calculate total amount functionalitie
        function calculateTotalAmount() {
            var quantities = document.querySelectorAll('#quantity');
            var subtotals = document.querySelectorAll('#subtotal');
            // Update Total Item
            var total_item = 0;
            var total_send_qty = 0;
            quantities.forEach(function(qty) {
                total_item += 1;
                total_send_qty += parseFloat(qty.value ? qty.value : 0);
            });

            $('#total_send_quantity').val(parseFloat(total_send_qty).toFixed(2));
            $('#total_item').val(parseFloat(total_item));

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
            var product_code = $(this).val();
            var __product_code = product_code.replaceAll('/', '~');
            var warehouse_id = $('#warehouse_id').val();
            if (warehouse_id == "") {
                $('#search_product').val("");
                alert('Warehouse field must not be empty.');
                return;
            }
            delay(function() {
                searchProduct(product_code, warehouse_id);
            }, 200); //sendAjaxical is the name of remote-command
        });

        // add Transfer product by searching product code
        function searchProduct(__product_code, warehouse_id) {

            var url = "{{ route('transfer.to.branch.search.item', [':__product_code', ':warehouse_id']) }}";
            var route = url.replace(':__product_code', __product_code);
            route = route.replace(':warehouse_id', warehouse_id);

            $.ajax({
                url: route,
                dataType: 'json',
                success: function(product) {

                    if (!$.isEmptyObject(product.errorMsg)) {

                        toastr.error(product.errorMsg);
                        $('#search_product').val("");
                        return;
                    }

                    var qty_limit = product.qty_limit;

                    if (!$.isEmptyObject(product.product) || !$.isEmptyObject(product.variant_product) || !$
                        .isEmptyObject(product.namedProducts)) {

                        $('#search_product').addClass('is-valid');
                        if (!$.isEmptyObject(product.product)) {

                            var product = product.product;

                            if (product.variants.length == 0) {

                                $('.select_area').hide();
                                $('#search_product').val('');
                                product_ids = document.querySelectorAll('#product_id');
                                var sameProduct = 0;
                                product_ids.forEach(function(input) {
                                    if (input.value == product.id) {
                                        sameProduct += 1;
                                        var className = input.getAttribute('class');
                                        // get closest table row for increasing qty and re calculate product amount
                                        var closestTr = $('.' + className).closest('tr');
                                        var presentQty = closestTr.find('#quantity').val();
                                        var previousQty = closestTr.find('#previous_quantity').val();
                                        var limit = closestTr.find('#qty_limit').val()
                                        var qty_limit = parseFloat(previousQty) + parseFloat(limit);
                                        if (parseFloat(qty_limit) == parseFloat(presentQty)) {
                                            alert('Quantity exceeds stock quantity!');
                                            return;
                                        }
                                        var updateQty = parseFloat(presentQty) + 1;
                                        closestTr.find('#quantity').val(parseFloat(updateQty).toFixed(
                                            2));

                                        //Update Subtotal
                                        var unitPrice = closestTr.find('#unit_price').val();
                                        var calcSubtotal = parseFloat(unitPrice) * parseFloat(
                                            updateQty);
                                        closestTr.find('#subtotal').val(parseFloat(calcSubtotal)
                                            .toFixed(2));
                                        closestTr.find('.span_subtotal').html(parseFloat(calcSubtotal)
                                            .toFixed(2));
                                        calculateTotalAmount();
                                        productTable();
                                        return;
                                    }
                                });

                                if (sameProduct == 0) {
                                    var tax_percent = product.tax_id != null ? product.tax.tax_percent : 0;
                                    var tr = '';
                                    tr += '<tr>';
                                    tr += '<td class="text-start" colspan="2">';
                                    tr += '<a href="#" class="text-success" id="edit_product">';
                                    tr += '<span class="product_name">' + product.name + '</span>';
                                    tr += '<span class="product_variant"></span>';
                                    tr += '<span class="product_code">' + ' (' + product.product_code + ')' +
                                        '</span>';
                                    tr += '</a>';
                                    tr += '<input value="' + product.id + '" type="hidden" class="productId-' +
                                        product.id + '" id="product_id" name="product_ids[]">';
                                    tr +=
                                        '<input value="noid" type="hidden" class="variantId-" id="variant_id" name="variant_ids[]">';
                                    tr += '<input type="hidden" id="qty_limit" value="' + qty_limit + '">';

                                    var unitPriceIncTax = parseFloat(product.product_price) / 100 * parseFloat(
                                        tax_percent) + parseFloat(product.product_price);
                                    tr +=
                                        '<input readonly name="unit_prices[]" type="hidden" id="unit_price" value="' +
                                        parseFloat(unitPriceIncTax).toFixed(2) + '">';
                                    tr += '</td>';

                                    tr += '<td>';
                                    tr += '<div class="input-group">';
                                    tr += '<div class="input-group-prepend">';
                                    tr +=
                                        '<a href="#" class="input-group-text  input-group-text-sale decrease_qty_btn"><i class="fas fa-minus text-danger"></i></a>';
                                    tr += '</div>';
                                    tr +=
                                        '<input value="1.00"  required name="quantities[]" type="number" step="any" class="form-control text-center" id="quantity">';
                                    tr += '<div class="input-group-prepend">';
                                    tr +=
                                        '<a href="#" class="input-group-text input-group-text-sale increase_qty_btn "><i class="fas fa-plus text-success "></i></a>';
                                    tr += '</div>';
                                    tr += '</div>';
                                    tr += '</td>';
                                    tr += '<td class="text text-center">';
                                    tr += '<span class="span_unit">' + product.unit.name + '</span>';
                                    tr += '<input  name="units[]" type="hidden" id="unit" value="' + product
                                        .unit.name + '">';
                                    tr += '</td>';

                                    tr += '<td class="text text-center">';
                                    tr += '<strong><span class="span_subtotal"> ' + parseFloat(unitPriceIncTax)
                                        .toFixed(2) + ' </span></strong>';
                                    tr += '<input value="' + parseFloat(unitPriceIncTax).toFixed(2) +
                                        '" readonly name="subtotals[]" type="hidden"  id="subtotal">';
                                    tr += '</td>';
                                    tr += '<td class="text-center">';
                                    tr +=
                                        '<a href="#" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                                    tr += '</td>';
                                    tr += '</tr>';
                                    $('#transfer_list').prepend(tr);
                                    calculateTotalAmount();
                                    productTable();
                                }
                            } else {

                                product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/default.jpg') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;
                                var products = product.namedProducts;
                                var li = "";
                                var tax_percent = product.tax_id != null ? product.tax.tax_percent : 0.00;

                                $.each(product.variants, function(key, variant) {

                                    var tax_amount = parseFloat(product.tax != null ? variant
                                        .variant_price / 100 * product.tax.tax_percent : 0.00);
                                    var unitPriceIncTax = (parseFloat(variant.variant_price) / 100 *
                                        tax_percent) + parseFloat(variant.variant_price);
                                    li += '<li id="list" class="mt-1">';
                                    li +=
                                        '<a class="select_variant_product" onclick="salectVariant(this); return false;" data-p_id="' +
                                        product.id + '" data-v_id="' + variant.id + '" data-p_name="' +
                                        product.name + '" data-p_tax_id="' + product.tax_id +
                                        '" data-unit="' + product.unit.name + '" data-tax_percent="' +
                                        tax_percent + '" data-tax_amount="' + tax_amount +
                                        '" data-v_code="' + variant.variant_code + '" data-v_price="' +
                                        variant.variant_price + '" data-v_name="' + variant
                                        .variant_name + '" data-v_cost_inc_tax="' + variant
                                        .variant_cost_with_tax +
                                        '" href="#"><img style="width:30px; height:30px;"  src="' +
                                        product.thumbnail_photo + '"> ' + product.name +
                                        ' - ' + variant.variant_name + ' (' + variant.variant_code +
                                        ')' + ' - Price: ' + parseFloat(unitPriceIncTax).toFixed(2) +
                                        '</a>';
                                    li += '</li>';
                                });

                                $('.variant_list_area').prepend(li);
                                $('.select_area').show();
                                $('#search_product').val('');
                            }

                        } else if (!$.isEmptyObject(product.variant_product)) {
                            $('.select_area').hide();
                            $('#search_product').val('');
                            var variant_product = product.variant_product;
                            var tax_percent = variant_product.product.tax_id != null ? variant_product.product
                                .tax.percent : 0;
                            var tax_rate = parseFloat(variant_product.product.tax != null ? variant_product
                                .variant_price / 100 * tax_percent : 0);
                            var variant_ids = document.querySelectorAll('#variant_id');
                            var sameVariant = 0;
                            variant_ids.forEach(function(input) {

                                if (input.value != 'noid') {

                                    if (input.value == variant_product.id) {

                                        sameVariant += 1;
                                        var className = input.getAttribute('class');
                                        // get closest table row for increasing qty and re calculate product amount
                                        var closestTr = $('.' + className).closest('tr');
                                        var presentQty = closestTr.find('#quantity').val();
                                        var previousQty = closestTr.find('#previous_quantity').val();
                                        var limit = closestTr.find('#qty_limit').val()
                                        var qty_limit = parseFloat(previousQty) + parseFloat(limit);
                                        if (parseFloat(qty_limit) == parseFloat(presentQty)) {

                                            alert('Quantity exceeds stock quantity!');
                                            return;
                                        }
                                        var updateQty = parseFloat(presentQty) + 1;
                                        closestTr.find('#quantity').val(parseFloat(updateQty).toFixed(
                                            2));

                                        //Update Subtotal
                                        var unitPrice = closestTr.find('#unit_price').val();
                                        var calcSubtotal = parseFloat(unitPrice) * parseFloat(
                                            updateQty);
                                        closestTr.find('#subtotal').val(parseFloat(calcSubtotal)
                                            .toFixed(2));
                                        closestTr.find('.span_subtotal').html(parseFloat(calcSubtotal)
                                            .toFixed(2));
                                        calculateTotalAmount();
                                        productTable();
                                        return;
                                    }
                                }
                            });

                            if (sameVariant == 0) {

                                var tax_percent = variant_product.product.tax_id != null ? variant_product
                                    .product.tax.tax_percent : 0;
                                var tr = '';
                                tr += '<tr>';
                                tr += '<td class="text-start" colspan="2">';
                                tr += '<a href="#" class="text-success" id="edit_product">';
                                tr += '<span class="product_name">' + variant_product.product.name + '</span>';
                                tr += '<span class="product_variant">' + ' -' + variant_product.variant_name +
                                    '- ' + '</span>';
                                tr += '<span class="product_code">' + '(' + variant_product.variant_code + ')' +
                                    '</span>';
                                tr += '</a><br/>';
                                tr += '<input value="' + variant_product.product.id +
                                    '" type="hidden" class="productId-' + variant_product.product.id +
                                    '" id="product_id" name="product_ids[]">';
                                tr += '<input value="' + variant_product.id +
                                    '" type="hidden" class="variantId-' + variant_product.id +
                                    '" id="variant_id" name="variant_ids[]">';
                                var unitPriceIncTax = variant_product.variant_price / 100 * tax_percent +
                                    variant_product.variant_price;
                                tr +=
                                    '<input readonly name="unit_prices[]" type="hidden" id="unit_price" value="' +
                                    parseFloat(unitPriceIncTax).toFixed(2) + '">';
                                tr += '<input type="hidden" id="qty_limit" value="' + qty_limit + '">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<div class="input-group">';
                                tr += '<div class="input-group-prepend">';
                                tr +=
                                    '<a href="#" class="input-group-text input-group-text-sale decrease_qty_btn"><i class="fas fa-minus text-danger"></i></a>';
                                tr += '</div>';
                                tr +=
                                    '<input value="1.00" required name="quantities[]" type="number" step="any" class="form-control text-center form-control-sm" id="quantity">';
                                tr += '<div class="input-group-prepend">';
                                tr +=
                                    '<a href="#" class="input-group-text input-group-text-sale increase_qty_btn "><i class="fas fa-plus text-success "></i></a>';
                                tr += '</div>';
                                tr += '</div>';
                                tr += '</td>';
                                tr += '<td class="text text-center">';
                                tr += '<span class="span_unit">' + variant_product.product.unit.name +
                                    '</span>';
                                tr += '<input  name="units[]" type="hidden" id="unit" value="' + variant_product
                                    .product.unit.name + '">';
                                tr += '</td>';
                                tr += '<td class="text text-center">';
                                tr += '<strong><span class="span_subtotal">' + parseFloat(unitPriceIncTax)
                                    .toFixed(2) + '</span></strong>';
                                tr += '<input value="' + parseFloat(unitPriceIncTax).toFixed(2) +
                                    '" readonly name="subtotals[]" type="hidden" id="subtotal">';
                                tr += '</td>';
                                tr += '<td class="text-center">';
                                tr +=
                                    '<a href="#" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                                tr += '</td>';
                                tr += '</tr>';
                                $('#transfer_list').prepend(tr);

                                calculateTotalAmount();
                                productTable();
                            }
                        } else if (!$.isEmptyObject(product.namedProducts)) {

                            if (product.namedProducts.length > 0) {

                                var li = "";
                                var products = product.namedProducts;

                                $.each(products, function(key, product) {

                                    product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/default.jpg') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;

                                    var tax_percent = product.tax_id != null ? product.tax_percent : 0;
                                    if (product.is_variant == 1) {

                                        var tax_amount = parseFloat(product.tax_id != null ? product
                                            .variant_price / 100 * product.tax_percent : 0.00);
                                        var unitPriceIncTax = (parseFloat(product.variant_price) / 100 *
                                            tax_percent) + parseFloat(product.variant_price);

                                        li += '<li id="list" class="mt-1">';
                                        li +=
                                            '<a class="select_variant_product" onclick="salectVariant(this); return false;" data-p_id="' +
                                            product.id + '" data-v_id="' + product.variant_id +
                                            '" data-p_name="' + product.name + '" data-p_tax_id="' +
                                            product.tax_id + '" data-unit="' + product.unit_name +
                                            '" data-tax_percent="' + tax_percent +
                                            '" data-tax_amount="' + tax_amount + '" data-v_code="' +
                                            product.variant_code + '" data-v_price="' + product
                                            .variant_price + '" data-v_name="' + product.variant_name +
                                            '" data-v_cost_inc_tax="' + product.variant_cost_with_tax +
                                            '" href="#"><img style="width:25px; height:25px;" src="' +
                                            product.thumbnail_photo + '"> ' + product
                                            .name + ' - ' + product.variant_name + ' (' + product
                                            .variant_code + ')' + ' - Price: ' + parseFloat(
                                                unitPriceIncTax).toFixed(2) + '</a>';
                                        li += '</li>';
                                    } else {

                                        var tax_amount = parseFloat(product.tax_ac_id != null ? product
                                            .product_price / 100 * product.tax_percent : 0);
                                        var unitPriceIncTax = (parseFloat(product.product_price) / 100 *
                                            tax_percent) + parseFloat(product.product_price);

                                        li += '<li class="mt-1">';
                                        li +=
                                            '<a class="select_single_product mt-1" onclick="singleProduct(this); return false;" data-p_id="' +
                                            product.id + '" data-p_name="' + product.name +
                                            '" data-unit="' + product.unit_name + '" data-p_code="' +
                                            product.product_code + '" data-p_price_exc_tax="' + product
                                            .product_price + '" data-p_tax_percent="' + tax_percent +
                                            '" data-p_tax_amount="' + tax_amount +
                                            '" data-p_cost_inc_tax="' + product.product_cost_with_tax +
                                            '" href="#"><img style="width:25px; height:25px;"  src="' +
                                            product.thumbnail_photo + '"> ' + product
                                            .name + ' (' + product.product_code + ')' + ' - Price: ' +
                                            parseFloat(unitPriceIncTax).toFixed(2) + '</a>';
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
        }

        // select single product and add purchase table
        var keyName = 1;

        function singleProduct(e) {

            if (keyName == 13 || keyName == 1) {

                document.getElementById('search_product').focus();
            }

            $('.select_area').hide();
            $('#search_product').val('');
            document.getElementById('search_product').focus();
            var warehouse_id = $('#warehouse_id').val();
            var product_id = e.getAttribute('data-p_id');
            var product_name = e.getAttribute('data-p_name');
            var product_code = e.getAttribute('data-p_code');
            var product_unit = e.getAttribute('data-unit');
            var product_cost_inc_tax = e.getAttribute('data-p_cost_inc_tax');
            var product_price_exc_tax = e.getAttribute('data-p_price_exc_tax');
            var p_tax_percent = e.getAttribute('data-p_tax_percent');
            var p_tax_amount = e.getAttribute('data-p_tax_amount');

            if (warehouse_id == "") {

                $('#search_product').val("");
                alert('Warehouse field must not be empty.');
                return;
            }

            var url = "{{ route('transfer.to.branch.check.warehouse.single.item', [':product_id', ':warehouse_id']) }}";
            var route = url.replace(':product_id', product_id);
            route = route.replace(':warehouse_id', warehouse_id);

            $.ajax({
                url: route,
                async: true,
                type: 'get',
                dataType: 'json',
                success: function(singleProductQty) {

                    if ($.isEmptyObject(singleProductQty.errorMsg)) {

                        var product_ids = document.querySelectorAll('#product_id');
                        var sameProduct = 0;

                        product_ids.forEach(function(input) {

                            if (input.value == product_id) {

                                sameProduct += 1;
                                var className = input.getAttribute('class');
                                // get closest table row for increasing qty and re calculate product amount
                                var closestTr = $('.' + className).closest('tr');
                                var presentQty = closestTr.find('#quantity').val();
                                var previousQty = closestTr.find('#previous_quantity').val();
                                var limit = closestTr.find('#qty_limit').val()
                                var qty_limit = parseFloat(previousQty) + parseFloat(limit);
                                if (parseFloat(qty_limit) == parseFloat(presentQty)) {
                                    alert('Quantity exceeds stock quantity!');
                                    return;
                                }
                                var updateQty = parseFloat(presentQty) + 1;
                                closestTr.find('#quantity').val(parseFloat(updateQty).toFixed(2));

                                //Update Subtotal
                                var unitPrice = closestTr.find('#unit_price').val();
                                var calcSubtotal = parseFloat(unitPrice) * parseFloat(updateQty);
                                closestTr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                                closestTr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(
                                    2));
                                calculateTotalAmount();
                                productTable();
                                if (keyName == 9) {
                                    closestTr.find('#quantity').focus();
                                    closestTr.find('#quantity').select();
                                    keyName = 1;
                                }
                                return;
                            }
                        });

                        if (sameProduct == 0) {

                            var tr = '';
                            tr += '<tr>';
                            tr += '<td class="text-start" colspan="2" class="">';
                            tr += '<a href="#" class="text-success" id="edit_product">';
                            tr += '<span class="product_name">' + product_name + '</span>';
                            tr += '<span class="product_variant"></span>';
                            tr += '<span class="product_code">' + ' (' + product_code + ')' + '</span>';
                            tr += '</a>';
                            tr += '<input value="' + product_id + '" type="hidden" class="productId-' +
                                product_id + '" id="product_id" name="product_ids[]">';
                            tr +=
                                '<input value="noid" type="hidden" class="variantId-" id="variant_id" name="variant_ids[]">';
                            tr += '<input type="hidden" id="qty_limit" value="' + singleProductQty + '">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<div class="input-group">';
                            tr += '<div class="input-group-prepend">';
                            tr +=
                                '<a href="#" class="input-group-text input-group-text-sale decrease_qty_btn"><i class="fas fa-minus text-danger"></i></a>';
                            tr += '</div>';
                            tr +=
                                '<input type="number" step="any" value="1.00" required name="quantities[]" class="form-control text-center" id="quantity">';
                            tr += '<div class="input-group-prepend">';
                            tr +=
                                '<a href="#" class="input-group-text input-group-text-sale increase_qty_btn"><i class="fas fa-plus text-success "></i></a>';
                            tr += '</div>';
                            tr += '</div>';
                            tr += '</td>';
                            tr += '<td class="text">';
                            tr += '<b><span class="span_unit">' + product_unit + '</span></b>';
                            tr += '<input  name="units[]" type="hidden" id="unit" value="' + product_unit +
                                '">';
                            tr += '</td>';

                            tr += '<td class="text text-center">';
                            var unitPriceIncTax = parseFloat(product_price_exc_tax) / 100 * parseFloat(
                                p_tax_percent) + parseFloat(product_price_exc_tax);
                            tr += '<input name="unit_prices[]" type="hidden" id="unit_price" value="' +
                                parseFloat(unitPriceIncTax).toFixed(2) + '">';
                            tr += '<strong><span class="span_subtotal"> ' + parseFloat(unitPriceIncTax).toFixed(
                                2) + ' </span></strong>';
                            tr += '<input value="' + parseFloat(unitPriceIncTax).toFixed(2) +
                                '" readonly name="subtotals[]" type="hidden" id="subtotal">';
                            tr += '</td>';
                            tr += '<td class="text-center">';
                            tr +=
                                '<a href="#" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                            tr += '</td>';
                            tr += '</tr>';
                            $('#transfer_list').prepend(tr);
                            calculateTotalAmount();
                            productTable();
                            if (keyName == 9) {
                                $("#quantity").select();
                                keyName = 1;
                            }
                        }
                    } else {

                        toastr.error(singleProductQty.errorMsg);
                    }
                }
            });
        }

        // select variant product and add purchase table
        function salectVariant(e) {

            if (keyName == 13 || keyName == 1) {

                document.getElementById('search_product').focus();
            }

            $('.select_area').hide();
            $('#search_product').val('');
            var warehouse_id = $('#warehouse_id').val();
            var product_id = e.getAttribute('data-p_id');
            var product_name = e.getAttribute('data-p_name');
            var tax_percent = e.getAttribute('data-tax_percent');
            var product_unit = e.getAttribute('data-unit');
            var variant_id = e.getAttribute('data-v_id');
            var variant_name = e.getAttribute('data-v_name');
            var variant_code = e.getAttribute('data-v_code');
            var variant_price = e.getAttribute('data-v_price');

            if (warehouse_id == "") {

                $('#search_product').val("");
                alert('Warehouse field must not be empty.');
                return;
            }

            var url =
                "{{ route('transfer.to.branch.check.warehouse.variant.item', [':product_id', ':variant_id', ':warehouse_id']) }}";
            var route = url.replace(':product_id', product_id);
            route = route.replace(':variant_id', variant_id);
            route = route.replace(':warehouse_id', warehouse_id);

            $.ajax({
                url: route,
                async: true,
                type: 'get',
                dataType: 'json',
                success: function(warehouseVariantQty) {

                    if ($.isEmptyObject(warehouseVariantQty.errorMsg)) {

                        var variant_ids = document.querySelectorAll('#variant_id');
                        var sameVariant = 0;

                        variant_ids.forEach(function(input) {

                            if (input.value != 'noid') {

                                if (input.value == variant_id) {

                                    sameVariant += 1;
                                    var className = input.getAttribute('class');
                                    // get closest table row for increasing qty and re calculate product amount
                                    var closestTr = $('.' + className).closest('tr');
                                    var presentQty = closestTr.find('#quantity').val();
                                    var previousQty = closestTr.find('#previous_quantity').val();
                                    var limit = closestTr.find('#qty_limit').val()
                                    var qty_limit = parseFloat(previousQty) + parseFloat(limit);

                                    if (parseFloat(qty_limit) == parseFloat(presentQty)) {

                                        alert('Quantity exceeds stock quantity!');
                                        return;
                                    }
                                    var updateQty = parseFloat(presentQty) + 1;
                                    closestTr.find('#quantity').val(parseFloat(updateQty).toFixed(2));

                                    //Update Subtotal
                                    var unitPrice = closestTr.find('#unit_price').val();
                                    var calcSubtotal = parseFloat(unitPrice) * parseFloat(updateQty);
                                    closestTr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(
                                        2));
                                    closestTr.find('.span_subtotal').html(parseFloat(calcSubtotal)
                                        .toFixed(2));
                                    calculateTotalAmount();
                                    productTable();

                                    if (keyName == 9) {

                                        closestTr.find('#quantity').select();
                                        keyName = 1;
                                    }
                                    return;
                                }
                            }
                        });

                        if (sameVariant == 0) {
                            var tr = '';
                            tr += '<tr>';
                            tr += '<td class="text-start" colspan="2">';
                            tr += '<a href="#" class="text-success" id="edit_product">';
                            tr += '<span class="product_name">' + product_name + '</span>';
                            tr += '<span class="product_variant">' + ' -' + variant_name + '- ' + '</span>';
                            tr += '<span class="product_code">' + '(' + variant_code + ')' + '</span>';
                            tr += '</a>';
                            tr += '<input value="' + product_id + '" type="hidden" class="productId-' +
                                product_id + '" id="product_id" name="product_ids[]">';
                            tr += '<input value="' + variant_id + '" type="hidden" class="variantId-' +
                                variant_id + '" id="variant_id" name="variant_ids[]">';

                            var unitPriceIncTax = parseFloat(variant_price) / 100 * parseFloat(tax_percent) +
                                parseFloat(variant_price);
                            tr += '<input name="unit_prices[]" type="hidden" id="unit_price" value="' +
                                parseFloat(unitPriceIncTax).toFixed(2) + '">';

                            tr += '<input type="hidden" id="qty_limit" value="' + warehouseVariantQty + '">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<div class="input-group">';
                            tr += '<div class="input-group-prepend">';
                            tr +=
                                '<a href="#" class="input-group-text input-group-text-sale decrease_qty_btn"><i class="fas fa-minus text-danger"></i></a>';
                            tr += '</div>';
                            tr +=
                                '<input type="number" step="any" value="1.00" required name="quantities[]" class="form-control text-center" id="quantity">';
                            tr += '<div class="input-group-prepend">';
                            tr +=
                                '<a href="#" class="input-group-text input-group-text-sale increase_qty_btn "><i class="fas fa-plus text-success"></i></a>';
                            tr += '</div>';
                            tr += '</div>';
                            tr += '</td>';
                            tr += '<td class="text text-center">';
                            tr += '<span class="span_unit">' + product_unit + '</span>';
                            tr += '<input  name="units[]" type="hidden" id="unit" value="' + product_unit +
                                '">';
                            tr += '</td>';

                            tr += '<td class="text text-center">';
                            tr += '<strong><span class="span_subtotal">' + parseFloat(unitPriceIncTax).toFixed(
                                2) + '</span></strong>';
                            tr += '<input value="' + parseFloat(unitPriceIncTax).toFixed(2) +
                                '" readonly name="subtotals[]" type="hidden" id="subtotal">';
                            tr += '</td>';
                            tr += '<td class="text-center">';
                            tr +=
                                '<a href="#" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                            tr += '</td>';
                            tr += '</tr>';
                            $('#transfer_list').prepend(tr);
                            calculateTotalAmount();
                            productTable();
                            if (keyName == 9) {
                                $("#quantity").select();
                                keyName = 1;
                            }
                        }
                    } else {
                        toastr.warning(warehouseVariantQty.errorMsg);
                    }
                }
            });
        }

        // Quantity increase or dicrease and clculate row amount
        $(document).on('input', '#quantity', function() {
            var qty = $(this).val() ? $(this).val() : 0;
            if (parseFloat(qty) >= 0) {
                var tr = $(this).closest('tr');
                var qty_limit = tr.find('#qty_limit').val();
                var unit = tr.find('#unit').val();
                if (parseInt(qty) > parseInt(qty_limit)) {
                    alert('Quantity Limit Is - ' + qty_limit + ' ' + unit);
                    $(this).val(qty_limit);
                    var unitPrice = tr.find('#unit_price').val();
                    var calcSubtotal = parseFloat(unitPrice) * parseFloat(qty_limit);
                    tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                    tr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                    calculateTotalAmount();
                    productTable();
                    return;
                }
                var unitPrice = tr.find('#unit_price').val();
                var calcSubtotal = parseFloat(unitPrice) * parseFloat(qty);
                tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                tr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                calculateTotalAmount();
            }
        });

        $(document).on('blur', '#quantity', function() {
            var qty = $(this).val() ? $(this).val() : 0;
            if (parseFloat(qty) >= 0) {
                var tr = $(this).closest('tr');
                var qty_limit = tr.find('#qty_limit').val();
                var unit = tr.find('#unit').val();
                if (parseInt(qty) > parseInt(qty_limit)) {
                    alert('Quantity Limit Is - ' + qty_limit + ' ' + unit);
                    $(this).val(qty_limit);
                    var unitPrice = tr.find('#unit_price').val();
                    var calcSubtotal = parseFloat(unitPrice) * parseFloat(qty_limit);
                    tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                    tr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                    calculateTotalAmount();
                    productTable();
                    return;
                }
                var unitPrice = tr.find('#unit_price').val();
                var calcSubtotal = parseFloat(unitPrice) * parseFloat(qty);
                tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                tr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                calculateTotalAmount();
            }
        });

        // Input shipment charge and clculate total amount
        $(document).on('input', '#shipment_charge', function() {
            calculateTotalAmount();
        });

        // Remove product form purchase product list (Table)
        $(document).on('click', '#remove_product_btn', function(e) {
            e.preventDefault();
            $(this).closest('tr').remove();
            calculateTotalAmount();
            productTable();
        });

        // Decrease qty
        $(document).on('click', '.decrease_qty_btn', function(e) {
            e.preventDefault();
            var tr = $(this).closest('tr');
            var presentQty = tr.find('#quantity').val();
            var updateQty = parseFloat(presentQty) - 1;
            tr.find('#quantity').val(parseFloat(updateQty).toFixed(2));
            tr.find('#quantity').addClass('.form-control:focus');
            tr.find('#quantity').blur();
        });

        // Iecrease qty
        $(document).on('click', '.increase_qty_btn', function(e) {
            e.preventDefault();
            var tr = $(this).closest('tr');
            var presentQty = tr.find('#quantity').val();
            var updateQty = parseFloat(presentQty) + 1;
            tr.find('#quantity').val(parseFloat(updateQty).toFixed(2));
            tr.find('#quantity').addClass('.form-control:focus');
            tr.find('#quantity').blur();
        })

        //Add purchase request by ajax
        $('#edit_transfer_to_branch_form').on('submit', function(e) {
            e.preventDefault();
            var totalItem = $('#total_item').val();
            if (parseFloat(totalItem) == 0) {
                toastr.error('Transfer product table is empty.', 'Some thing went wrong.');
                return;
            }
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.add_input');
            inputs.removeClass('is-invalid');
            $('.error').html('');
            var countErrorField = 0;
            $.each(inputs, function(key, val) {
                var inputId = $(val).attr('id');
                var idValue = $('#' + inputId).val();
                if (idValue == '') {
                    countErrorField += 1;
                    var fieldName = $('#' + inputId).data('name');
                    $('.error_' + inputId).html(fieldName + ' is required.');
                }
            });

            if (countErrorField > 0) {
                $('.loading_button').hide();
                toastr.error('Please check again all form fields.', 'Some thing went wrong.');
                return;
            }

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    if (!$.isEmptyObject(data.errorMsg)) {
                        toastr.error(data.errorMsg, 'ERROR');
                        $('.loading_button').hide();
                    }

                    if (!$.isEmptyObject(data.successMsg)) {
                        $('.loading_button').hide();
                        toastr.success(data.successMsg);
                        window.location = "{{ route('transfer.stock.to.branch.index') }}";
                    }
                }
            });
        });

        // Automatic remove searching product is found signal
        setInterval(function() {
            $('#search_product').removeClass('is-invalid');
        }, 500);

        setInterval(function() {
            $('#search_product').removeClass('is-valid');
        }, 1000);

        // Disable branch field after add the products
        function productTable() {
            var totalItem = $('#total_item').val() ? $('#total_item').val() : 0;
            var role = $('#role').val();
            if (parseFloat(totalItem) > 0) {
                $('#warehouse_id').prop('disabled', true);
            } else {
                $('#warehouse_id').prop('disabled', false);
            }
        }

        $('.submit_button').on('click', function() {
            var value = $(this).val();
            $('#action').val(value);
        });

        $('#warehouse_id').on('change', function(e) {
            e.preventDefault();
            var warehouse_id = $(this).val();
            $('#req_warehouse_id').val(warehouse_id);
        });

        $(document).keypress(".scanable", function(event) {
            if (event.which == '10' || event.which == '13') {
                event.preventDefault();
            }
        });

        $('body').keyup(function(e) {
            if (e.keyCode == 13 || e.keyCode == 9) {
                $(".selectProduct").click();
                $('#list').empty();
                keyName = e.keyCode;
            }
        });

        $(document).on('mouseenter', '#list>li>a', function() {
            $('#list>li>a').removeClass('selectProduct');
            $(this).addClass('selectProduct');
        });

        $(document).on('change', '.add_input', function() {
            document.getElementById('search_product').focus();
        });

        document.getElementById('search_product').focus();

        // Get editable data by ajax
        function getEditableTransfer() {
            $.ajax({
                url: "{{ route('transfer.stock.to.branch.editable.transfer', $transferId) }}",
                async: true,
                type: 'get',
                dataType: 'json',
                success: function(transfer) {
                    var qty_limits = transfer.qty_limits;
                    var transfer = transfer.transfer;
                    $('#invoice_id').val(transfer.invoice_id);
                    $('#shipping_charge').val(transfer.shipping_charge);
                    $('#additional_note').val(transfer.additional_note);

                    $.each(transfer.transfer_products, function(key, transferProduct) {
                        var tr = '';
                        tr += '<tr>';
                        tr += '<td colspan="2" class="text-start">';
                        tr += '<a href="#" class="text-success" id="edit_product">';
                        tr += '<span class="product_name">' + transferProduct.product.name + '</span>';
                        var variant = transferProduct.product_variant_id != null ? ' -' +
                            transferProduct.variant.variant_name + '- ' : '';
                        tr += '<span class="product_variant">' + variant + '</span>';
                        var code = transferProduct.product_variant_id != null ? transferProduct.variant
                            .variant_code : transferProduct.product.product_code;
                        tr += '<span class="product_code">' + '(' + code + ')' + '</span>';
                        tr += '</a>';
                        tr += '<input value="' + transferProduct.product_id +
                            '" type="hidden" class="productId-' + transferProduct.product_id +
                            '" id="product_id" name="product_ids[]">';

                        if (transferProduct.product_variant_id != null) {
                            tr += '<input value="' + transferProduct.product_variant_id +
                                '" type="hidden" class="variantId-' + transferProduct
                                .product_variant_id + '" id="variant_id" name="variant_ids[]">';
                        } else {
                            tr +=
                                '<input value="noid" type="hidden" class="variantId-" id="variant_id" name="variant_ids[]">';
                        }

                        tr +=
                            '<input readonly name="unit_prices[]" type="hidden"  id="unit_price" value="' +
                            transferProduct.unit_price + '">';
                        tr += '<input type="hidden" id="previous_quantity" value="' + transferProduct
                            .quantity + '">';
                        tr += '<input type="hidden" id="qty_limit" value="' + qty_limits[key] + '">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<div class="input-group">';
                        tr += '<div class="input-group-prepend">';
                        tr +=
                            '<a href="#" class="input-group-text input-group-text-sale decrease_qty_btn"><i class="fas fa-minus text-danger"></i></a>';
                        tr += '</div>';
                        tr += '<input type="number" step="any" value="' + transferProduct.quantity +
                            '" required name="quantities[]" class="form-control text-center" id="quantity">';
                        tr += '<div class="input-group-prepend">';
                        tr +=
                            '<a href="#" class="input-group-text input-group-text-sale increase_qty_btn "><i class="fas fa-plus text-success "></i></a>';
                        tr += '</div>';
                        tr += '</div>';
                        tr += '</td>';
                        tr += '<td class="text">';
                        tr += '<span class="span_unit">' + transferProduct.unit + '</span>';
                        tr += '<input  name="units[]" type="hidden" id="unit" value="' + transferProduct
                            .unit + '">';
                        tr += '</td>';

                        tr += '<td class="text text-center">';
                        tr += '<strong><span class="span_subtotal">' + transferProduct.subtotal +
                            '</span></strong>';
                        tr += '<input value="' + transferProduct.subtotal +
                            '" readonly name="subtotals[]" type="hidden" id="subtotal">';
                        tr += '</td>';
                        tr += '<td class="text-center">';
                        tr +=
                            '<a href="#" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                        tr += '</td>';
                        tr += '</tr>';
                        $('#transfer_list').append(tr);
                    });
                    calculateTotalAmount();
                    productTable();
                }
            });
        }
        getEditableTransfer();

        var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
        var _expectedDateFormat = '';
        _expectedDateFormat = dateFormat.replace('d', 'DD');
        _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
        _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');
        new Litepicker({
            singleMode: true,
            element: document.getElementById('datepicker'),
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
@endpush
