@php
    $productSerial = new App\Utils\InvoiceVoucherRefIdUtil();
@endphp
<div class="modal-content">
    <div class="modal-header">
        <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_item')</h6>
        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
    </div>
    <div class="modal-body" id="add_product_body">
        <form id="add_quick_product_form" action="{{ route('products.add.store') }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="1">
            <input type="hidden" name="is_variant" value="0">
            <input type="hidden" name="has_opening_stock" value="1">
            <input type="hidden" id="product_serial"
                value="{{ str_pad($productSerial->getLastId('products'), 4, '0', STR_PAD_LEFT) }}">
            <input type="hidden" id="code_prefix"
                value="{{ json_decode($generalSettings->product, true)['product_code_prefix'] }}">
            <div class="form-group row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <label><b>@lang('menu.item_name')</b> <span class="text-danger">*</span></label>
                            <input required type="text" name="name" class="form-control fw-bold" id="product_name"
                                data-next="product_code" placeholder="@lang('menu.item_name')" />
                            <span class="error error_quick_product_name"></span>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <label><b>@lang('menu.item_code') (SKU) </b></label>
                            <input type="text" name="code" class="form-control fw-bold" id="product_code"
                                data-next="product_unit_id" placeholder="@lang('menu.item_code')" />
                            <input type="hidden" name="auto_generated_code" id="auto_generated_code">
                            <span class="error error_quick_product_code"></span>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-xl-6 col-md-6">
                            <label><b> @lang('menu.unit') </b> <span class="text-danger">*</span></label>
                            <div class="input-group select-customer-input-group">
                                <select required name="unit_id" class="form-control select2 form-select"
                                    id="product_unit_id" data-next="product_barcode_type">
                                    <option value="">@lang('menu.select_unit')</option>
                                    @php
                                        $defaultUnit = json_decode($generalSettings->product, true)['default_unit_id'];
                                    @endphp
                                    @foreach ($units as $unit)
                                        <option {{ $defaultUnit == $unit->id ? 'SELECTED' : '' }}
                                            value="{{ $unit->id }}">{{ $unit->name . ' (' . $unit->code_name . ')' }}
                                        </option>
                                    @endforeach
                                </select>

                                <div class="input-group-prepend">
                                    <span data-bs-target="unitAddOrEditModal"
                                        class="input-group-text add_button {{ !auth()->user()->can('units')? 'disabled_element': '' }}"
                                        id="addUnit"><i class="fas fa-plus-square input_i"></i></span>
                                </div>
                            </div>

                            <span class="error error_quick_product_unit_id"></span>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <label><b>@lang('menu.barcode_type') </b></label>
                            <select class="form-control form-select" name="barcode_type" id="product_barcode_type"
                                data-next="product_category_id">
                                <option value="CODE128">@lang('menu.code') 128 (C128)</option>
                                <option value="CODE39">@lang('menu.code') 39 (C39)</option>
                                <option value="EAN13">EAN-13</option>
                                <option value="UPC">@lang('menu.upc')</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-1">
                        @if (json_decode($generalSettings->product, true)['is_enable_categories'] == '1')

                            <div class="col-xl-6 col-md-6">
                                <div class="input-group">
                                    <label><b>@lang('menu.category')</b></label>
                                    <div class="input-group select-customer-input-group">
                                        <select class="form-control select2 form-select" name="category_id"
                                            id="product_category_id" data-next="product_sub_category_id">
                                            <option value="">@lang('menu.select_category')</option>
                                            @foreach ($categories as $category)
                                                <option data-cate_name="{{ $category->name }}"
                                                    value="{{ $category->id }}">
                                                    {{ $category->name . '/' . $category->code }}</option>
                                            @endforeach
                                        </select>

                                        <div class="input-group-prepend">
                                            <span class="input-group-text add_button" id="addCategory"><i
                                                    class="fas fa-plus-square input_i"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (json_decode($generalSettings->product, true)['is_enable_categories'] == '1' &&
                                json_decode($generalSettings->product, true)['is_enable_sub_categories'] == '1')
                            <div class="col-xl-6 col-md-6">
                                <label><b>@lang('menu.subcategory') </b></label>
                                <div class="input-group select-customer-input-group">
                                    <select class="form-control select2 form-select" name="sub_category_id"
                                        id="product_sub_category_id" data-next="product_brand_id">
                                        <option value="">@lang('menu.select_category_first')</option>
                                    </select>

                                    <div class="input-group-prepend">
                                        <span class="input-group-text add_button" id="addSubCategory"><i
                                                class="fas fa-plus-square input_i"></i></span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="row mt-1">
                        @if (json_decode($generalSettings->product, true)['is_enable_brands'] == '1')

                            <div class="col-xl-6 col-md-6">
                                <label><b>@lang('menu.brand') </b></label>
                                <div class="input-group select-customer-input-group">
                                    <select class="form-control select2 form-select" name="brand_id"
                                        id="product_brand_id" data-next="product_warranty_id">
                                        <option value="">@lang('menu.select_brand')</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>

                                    <div class="input-group-prepend">
                                        <span
                                            class="input-group-text add_button {{ !auth()->user()->can('brand')? 'disabled_element': '' }}"
                                            id="addBrand"><i class="fas fa-plus-square input_i"></i></span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (json_decode($generalSettings->product, true)['is_enable_warranty'] == '1')

                            <div class="col-xl-6 col-md-6">
                                <label><b>@lang('menu.warranty') </b></label>
                                <div class="input-group select-customer-input-group">
                                    <select class="form-control select2 form-select" name="warranty_id"
                                        id="product_warranty_id" data-next="purchase_type">
                                        <option value="">@lang('menu.select_warranty')</option>
                                        @foreach ($warranties as $warranty)
                                            <option value="{{ $warranty->id }}">{{ $warranty->name }}
                                                ({{ $warranty->type == 1 ? 'Warranty' : 'Guaranty' }})</option>
                                        @endforeach
                                    </select>

                                    <div class="input-group-prepend">
                                        <span
                                            class="input-group-text add_button {{ !auth()->user()->can('warranties')? 'disabled_element': '' }}"
                                            id="addWarranty"><i class="fas fa-plus-square input_i"></i></span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="row mt-1">
                        <div class="col-xl-6 col-md-6">
                            <label><b>@lang('menu.purchase_type')</b></label>
                            <select name="purchase_type" id="purchase_type" class="form-control form-select"
                                data-next="stock_type">
                                <option value="1">@lang('menu.direct_purchase')</option>
                                <option value="2">@lang('menu.purchase_by_weight_scale')</option>
                            </select>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <label><b>@lang('menu.stock_type')</b></label>
                            <select class="form-control form-select" name="stock_type" id="stock_type"
                                data-next="product_is_show_in_ecom">
                                <option value="1">@lang('menu.manageable_stock')</option>
                                <option value="0">@lang('menu.service_digital_item')</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-xl-6 col-md-6">
                            <label><b>@lang('menu.displayed_in_ecom')</b></label>
                            <select name="is_show_in_ecom" class="form-control form-select"
                                id="product_is_show_in_ecom" data-next="product_is_show_emi_on_pos">
                                <option value="0">@lang('menu.no')</option>
                                <option value="1">@lang('menu.yes')</option>
                            </select>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <label><b>@lang('menu.enable_imei_or_sl_no')</b></label>
                            <select name="is_show_emi_on_pos" class="form-control form-select"
                                id="product_is_show_emi_on_pos" data-next="product_product_cost">
                                <option value="0">@lang('menu.no')</option>
                                <option value="1">@lang('menu.yes')</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-6" style="border-left:1px solid black;">
                    <div class="row mt-1">
                        <div class="col-xl-6 col-md-6">
                            <label><b>@lang('menu.item_cost_exc_tax') </b></label>
                            <input type="number" step="any" name="product_cost" class="form-control fw-bold"
                                id="product_product_cost" data-next="product_product_cost_with_tax"
                                placeholder="0.00" autocomplete="off">
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <label><b>@lang('menu.item_cost_inc_tax') </b></label>
                            <input readonly type="number" step="any" name="product_cost_with_tax"
                                class="form-control fw-bold" id="product_product_cost_with_tax"
                                data-next="product_tax_ac_id" placeholder="0.00" tabindex="-1">
                        </div>
                    </div>

                    <div class="row mt-1">
                        @if (json_decode($generalSettings->product, true)['is_enable_price_tax'] == '1')

                            <div class="col-xl-6 col-md-6 ">
                                <label><b>@lang('menu.tax')</b></label>
                                <select class="form-control form-select" name="tax_ac_id" id="product_tax_ac_id"
                                    data-next="product_tax_type">
                                    <option data-product_tax_percent="0" value="">@lang('menu.no_tax')</option>
                                    @foreach ($taxAccounts as $tax)
                                        <option data-product_tax_percent="{{ $tax->tax_percent }}"
                                            value="{{ $tax->id }}">{{ $tax->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <label><b>@lang('menu.tax_type') </b> </label>
                                <select class="form-control form-select" name="tax_type" id="product_tax_type"
                                    data-next="product_profit">
                                    <option value="1">@lang('menu.exclusive')</option>
                                    <option value="2">@lang('menu.inclusive')</option>
                                </select>
                            </div>
                        @endif
                    </div>

                    <div class="row mt-1">
                        <div class="col-xl-6 col-md-6">
                            <label><b>@lang('menu.profit_margin')</b></label>
                            <input type="number" step="any" name="profit" class="form-control fw-bold"
                                autocomplete="off" id="product_profit"
                                value="{{ json_decode($generalSettings->business, true)['default_profit'] }}"
                                data-next="product_product_price">
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <label><b>@lang('menu.price_exc_tax') </b></label>
                            <input type="number" step="any" name="product_price" class="form-control fw-bold"
                                id="product_product_price" data-next="product_alert_quantity" placeholder="0.00"
                                autocomplete="off">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <label><b>@lang('menu.alert_quantity') </b></label>
                            <input type="number" name="alert_quantity" class="form-control fw-bold"
                                autocomplete="off" id="product_alert_quantity" value="0"
                                data-next="product_condition">
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <label><b>@lang('menu.condition') </b></label>
                            <select class="form-control form-select" name="product_condition" id="product_condition"
                                data-next="product_warehouse_id">
                                <option value="New">@lang('menu.new')</option>
                                <option value="Used">@lang('menu.used')</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <label><b>@lang('menu.show_not_for_sale') </b></label>
                            <select name="is_not_for_sale" class="form-control form-select" id="is_not_for_sale"
                                data-next="save_and_new">
                                <option value="0">@lang('menu.no')</option>
                                <option value="1">@lang('menu.yes')</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row mt-3">
                <div class="col-md-12">
                    <p><strong> @lang('menu.add') @lang('menu.opening_stock')</strong></p>
                    <div class="table-responsive">
                        <table class="table modal-table table-sm">
                            <thead>
                                <tr>
                                    <th>@lang('menu.stock_location')</th>
                                    <th>@lang('menu.quantity')</th>
                                    <th>@lang('menu.unit_cost_inc_tax')</th>
                                    <th>@lang('menu.sub_total')</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @if (count($warehouses) > 0)

                                        <td>
                                            <input name="warehouse_count" value="YES" type="hidden" />
                                            <select class="form-control changeable form-select" name="warehouse_id"
                                                id="product_warehouse_id" data-next="product_op_quantity">
                                                <option value="">@lang('menu.select_warehouse')</option>
                                                @foreach ($warehouses as $w)
                                                    <option value="{{ $w->id }}">
                                                        {{ $w->warehouse_name . '/' . $w->warehouse_code }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    @else
                                        <td>
                                            <p>
                                                {!! json_decode($generalSettings->business, true)['shop_name'] !!}
                                            </p>
                                        </td>
                                    @endif

                                    <td>
                                        <input required type="number" step="any" name="quantity"
                                            class="form-control fw-bold" id="product_op_quantity" value="0.00"
                                            data-next="product_op_unit_cost_inc_tax" autocomplete="off">
                                    </td>

                                    <td>
                                        <input type="number" step="any" name="unit_cost_inc_tax"
                                            class="form-control fw-bold" id="product_op_unit_cost_inc_tax"
                                            value="0.00" data-next="quick_product_save" autocomplete="off">
                                    </td>

                                    <td>
                                        <b><span id="product_span_op_subtotal" class="fw-bold">0.00</span></b>
                                        <input type="hidden" name="subtotal" id="product_op_subtotal"
                                            value="0.00">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="form-group row mt-2">
                <div class="col-md-12 d-flex justify-content-end">
                    <div class="loading-btn-box">
                        <button type="button" class="btn btn-sm loading_button display-none"><i
                                class="fas fa-spinner"></i></button>
                        <button type="submit" id="quick_product_save"
                            class="btn btn-sm btn-success quick_product_submit_button float-end">@lang('menu.save')</button>
                        <button type="reset" data-bs-dismiss="modal"
                            class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $('.select2').select2();

    function costCalculate() {

        var tax_percent = $('#product_tax_ac_id').find('option:selected').data('product_tax_percent');
        var product_cost = $('#product_product_cost').val() ? $('#product_product_cost').val() : 0;
        var tax_type = $('#product_tax_type').val();
        var calc_product_cost_tax = parseFloat(product_cost) / 100 * parseFloat(tax_percent);

        if (tax_type == 2) {

            var __tax_percent = 100 + parseFloat(tax_percent);
            var calc_tax = parseFloat(product_cost) / parseFloat(__tax_percent) * 100;
            calc_product_cost_tax = parseFloat(product_cost) - parseFloat(calc_tax);
        }

        var product_cost_with_tax = parseFloat(product_cost) + calc_product_cost_tax;
        $('#product_product_cost_with_tax').val(parseFloat(product_cost_with_tax).toFixed(2));
        var profit = $('#product_profit').val() ? $('#product_profit').val() : 0;

        $('#product_op_unit_cost_inc_tax').val(product_cost_with_tax);

        if (parseFloat(profit)) {

            var calculate_profit = parseFloat(product_cost) / 100 * parseFloat(profit);
            var product_price = parseFloat(product_cost) + parseFloat(calculate_profit);
            $('#product_product_price').val(parseFloat(product_price).toFixed(2));
        }

        calculateOpeningStockSubtotal();
    }

    $(document).on('input', '#product_product_price', function() {

        var selling_price = $(this).val() ? $(this).val() : 0;
        var product_cost = $('#product_product_cost').val() ? $('#product_product_cost').val() : 0;
        var profitAmount = parseFloat(selling_price) - parseFloat(product_cost);
        var __cost = parseFloat(product_cost) > 0 ? parseFloat(product_cost) : parseFloat(profitAmount);
        var calcProfit = parseFloat(profitAmount) / parseFloat(__cost) * 100;
        var __calcProfit = calcProfit ? calcProfit : 0;
        $('#product_profit').val(parseFloat(__calcProfit).toFixed(2));
    });

    $(document).on('input', '#product_profit', function() {

        costCalculate();
    });

    $(document).on('input', '#product_product_cost', function() {

        costCalculate();
    });

    $(document).on('change', '#product_tax_ac_id', function() {

        costCalculate();
    });

    $(document).on('change', '#product_tax_type', function() {

        costCalculate();
    });

    // Reduce empty opening stock qty field
    $(document).on('blur', '#product_op_quantity', function() {

        if ($(this).val() == '') {

            $(this).val(parseFloat(0).toFixed(2));
        }
    });

    // Reduce empty opening stock unit cost field
    $(document).on('blur', '#product_op_unit_cost_inc_tax', function() {

        if ($(this).val() == '') {

            $(this).val(parseFloat(0).toFixed(2));
        }
    });

    $(document).on('input', '#product_op_quantity', function() {

        calculateOpeningStockSubtotal();
    });

    $(document).on('input', '#product_op_unit_cost_inc_tax', function() {

        calculateOpeningStockSubtotal();
    });

    function calculateOpeningStockSubtotal() {

        var unit_cost_exc_tax = $('#product_op_unit_cost_inc_tax').val() ? $('#product_op_unit_cost_inc_tax').val() : 0;
        var qty = $('#product_op_quantity').val() ? $('#product_op_quantity').val() : 0;
        var calcSubtotal = parseFloat(qty) * parseFloat(unit_cost_exc_tax);
        $('#product_span_op_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
        $('#product_op_subtotal').val(parseFloat(calcSubtotal).toFixed(2));
    }

    $('#product_category_id').on('change', function() {

        var category_id = $(this).val();
        $.ajax({
            url: "{{ url('common/ajax/call/category/subcategories/') }}" + "/" + category_id,
            async: true,
            type: 'get',
            dataType: 'json',
            success: function(subcate) {

                $('#product_sub_category_id').empty();
                $('#product_sub_category_id').append(
                    '<option value="">Select Sub-Category</option>');

                $.each(subcate, function(key, val) {

                    $('#product_sub_category_id').append('<option value="' + val.id + '">' +
                        val.name + '</option>');
                });
            }
        });

        generateProductCode();
    });

    $('#product_sub_category_id').on('change', function() {

        generateProductCode();
    });

    $('#addUnit').on('click', function(e) {
        e.preventDefault();

        var url = "{{ route('products.units.create', 0) }}";

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#unitAddOrEditModal').html(data);
                $('#unitAddOrEditModal').modal('show');

                setTimeout(function() {

                    $('#unit_name').focus();
                }, 500);
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if (err.status == 500) {

                    toastr.error('Server error. Please contact to the support team.');
                    return;
                }
            }
        });
    });

    $('#addBrand').on('click', function(e) {
        e.preventDefault();

        var url = "{{ route('product.brands.create') }}";

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#brandAddOrEditModal').html(data);
                $('#brandAddOrEditModal').modal('show');

                setTimeout(function() {

                    $('#brand_name').focus();
                }, 500);
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if (err.status == 500) {

                    toastr.error('Server error. Please contact to the support team.');
                    return;
                }
            }
        });
    });

    $('#addWarranty').on('click', function(e) {
        e.preventDefault();

        var url = "{{ route('product.warranties.create') }}";

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#warrantyAddOrEditModal').html(data);
                $('#warrantyAddOrEditModal').modal('show');

                setTimeout(function() {

                    $('#warranty_name').focus();
                }, 500);
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if (err.status == 500) {

                    toastr.error('Server error. Please contact to the support team.');
                    return;
                }
            }
        });
    });

    $('#addCategory').on('click', function(e) {
        e.preventDefault();

        var url = "{{ route('product.categories.create') }}";

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#categoryAddOrEditModal').html(data);
                $('#categoryAddOrEditModal').modal('show');

                setTimeout(function() {

                    $('#category_name').focus();
                }, 500);
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if (err.status == 500) {

                    toastr.error('Server error. Please contact to the support team.');
                    return;
                }
            }
        });
    });

    $('#addSubCategory').on('click', function(e) {
        e.preventDefault();

        var categoryId = $('#product_category_id').val();
        if (categoryId == '') {

            toastr.error('Please select category first.');
            return;
        }

        var url = "{{ route('product.subcategories.create', ':category_id') }}";
        var route = url.replace(':category_id', categoryId);
        $.ajax({
            url: route,
            type: 'get',
            success: function(data) {

                $('#subcategoryAddOrEditModal').html(data);
                $('#subcategoryAddOrEditModal').modal('show');

                setTimeout(function() {

                    $('#subcategory_name').focus();
                }, 500);
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if (err.status == 500) {

                    toastr.error('Server error. Please contact to the support team.');
                    return;
                }
            }
        });
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            $('#' + nextId).focus().select();
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

    function generateProductCode() {

        var product_serial = $('#product_serial').val();
        var code_prefix = $('#code_prefix').val();
        var category_name = $('#product_category_id').find('option:selected').data('cate_name');
        var subcategory_name = $('#product_sub_category_id').find('option:selected').data('sub_cate_name');
        var product_name = $('#name').val();

        if (code_prefix) {

            var productCode = product_serial + product_serial;
            $('#auto_generated_code').val(productCode);
        } else {

            var categoryFirstLetter = category_name ? category_name[0] : '';
            var subcategoryFirstLetter = subcategory_name ? subcategory_name[0] : '';
            var productNameFirstLetter = product_name ? product_name[0] : '';
            var productCode = categoryFirstLetter + subcategoryFirstLetter + productNameFirstLetter + product_serial;
            $('#auto_generated_code').val(productCode);
        }
    }
    generateProductCode();
</script>
