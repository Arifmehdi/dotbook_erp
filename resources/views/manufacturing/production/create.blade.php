@extends('layout.master')
@push('css')
    <style>
        table.display td input {
            height: 26px !important;
            padding: 3px;
        }

        span.input-group-text-custom {
            font-size: 11px;
            padding: 4px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
    <div class="body-wraper">
        <div class="container-fluid p-0">
            <div class="sec-name">
                <h6>@lang('menu.add_production')</h6>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm float-end back-button"><i
                        class="fa-thin fa-left-to-line fa-2x"></i><br> @lang('menu.back')</a>
            </div>
            <form id="add_production_form" class="p-15" action="{{ route('manufacturing.productions.store') }}"
                method="POST">
                <input name="action_type" type="text" id="action_type" class="d-none" value="">
                <input name="product_id" type="text" id="product_id" class="d-none" value="">
                <input name="variant_id" type="text" id="variant_id" class="d-none" value="">
                <input name="unit_id" type="text" id="unit_id" class="d-none" value="">
                @csrf
                <section>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element rounded mt-0">

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label><b>@lang('menu.production_ac') : <span class="text-danger">*</span></b></label>
                                            <select name="production_account_id" class="form-control add_input form-select"
                                                id="production_account_id" data-name="Production A/c" required>
                                                @foreach ($productionAccounts as $productionAccount)
                                                    <option value="{{ $productionAccount->id }}">
                                                        {{ $productionAccount->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="error error_production_account_id"></span>
                                        </div>

                                        <div class="col-md-2">
                                            @if (count($warehouses) > 0)
                                                <input type="hidden" value="YES" name="store_warehouse_count">
                                                <label> <b>@lang('menu.store_location') </b> <span class="text-danger">*</span></label>
                                                <select class="form-control changeable add_input form-select"
                                                    name="store_warehouse_id" data-name="Warehouse" id="store_warehouse_id"
                                                    required>
                                                    <option value="">@lang('menu.select_warehouse')</option>
                                                    @foreach ($warehouses as $w)
                                                        <option value="{{ $w->id }}">
                                                            {{ $w->warehouse_name . '/' . $w->warehouse_code }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="error error_store_warehouse_id"></span>
                                            @else
                                                <label><b>@lang('menu.store_location') </b> </label>
                                                <input readonly type="text" name="store_branch_id"
                                                    class="form-control changeable"
                                                    value="{{ json_decode($generalSettings->business, true)['shop_name'] }}" />
                                            @endif
                                        </div>

                                        <div class="col-md-2">
                                            <label> <b>@lang('menu.voucher_no') </b></label>
                                            <input type="text" name="reference_no" class="form-control changeable"
                                                placeholder="@lang('menu.voucher_no')" />
                                        </div>

                                        <div class="col-md-2">
                                            <label><b>@lang('menu.date') </b></label>
                                            <input type="text" name="date" class="form-control changeable"
                                                value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}"
                                                id="datepicker">
                                            <span class="error error_date"></span>
                                        </div>

                                        <div class="col-md-2">
                                            @if (count($warehouses) > 0)
                                                <input type="hidden" value="YES" name="stock_warehouse_count">
                                                <label> <b>@lang('menu.ingredient_stock_location') </b> <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control changeable add_input form-select"
                                                    name="stock_warehouse_id" data-name="Warehouse" id="stock_warehouse_id"
                                                    required>
                                                    <option value="">@lang('menu.select_warehouse')</option>
                                                    @foreach ($warehouses as $w)
                                                        <option value="{{ $w->id }}">
                                                            {{ $w->warehouse_name . '/' . $w->warehouse_code }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="error error_warehouse_id"></span>
                                            @else
                                                <label><b>@lang('menu.ingredient_stock_location') </b> </label>
                                                <input readonly type="text" name="stock_branch_id"
                                                    class="form-control changeable"
                                                    value="{{ json_decode($generalSettings->business, true)['shop_name'] }}" />
                                            @endif
                                        </div>

                                        <div class="col-md-2">
                                            <label><b>@lang('menu.product') </b> <span class="text-danger">*</span></label>
                                            <select name="process_id" data-name="Product"
                                                class="form-control add_input form-select" id="product_id" required>
                                                <option value="">Select Process</option>
                                                @foreach ($products as $product)
                                                    @php
                                                        $variant_name = $product->v_name ? $product->v_name : '';
                                                        $product_code = $product->v_code ? $product->v_code : $product->p_code;
                                                    @endphp
                                                    <option value="{{ $product->id }}">
                                                        {{ $product->p_name . ' ' . $variant_name . ' (' . $product_code . ')' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="error error_process_id"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="sale-content mt-1">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="item-details-sec">
                                    <div class="content-inner">
                                        <div class="row">
                                            <div class="sale-item-sec">
                                                <div class="sale-item-inner">
                                                    <div class="table-responsive">
                                                        <table class="display data__table table-striped">
                                                            <thead class="staky">
                                                                <tr>
                                                                    <th>@lang('menu.ingredient')</th>
                                                                    <th>@lang('menu.input_quantity')</th>
                                                                    <th>@lang('menu.unit_cost')</th>
                                                                    <th>@lang('menu.sub_total')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="ingredient_list"></tbody>
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

                <div class="row mb-1">
                    <div class="col-md-12">
                        <input type="text" class="d-none" name="total_ingredient_cost" id="total_ingredient_cost">
                        <p class="mt-1 float-end clearfix"><strong>@lang('menu.total_ingredient_cost') : </strong> <span
                                id="span_total_ingredient_cost">0.00</span></p>
                    </div>
                </div>

                <section>
                    <div class="row g-1">
                        <div class="col-md-5">
                            <div class="form_element rounded m-0">
                                <div class="element-body">
                                    <p><strong>@lang('menu.total_production_costing') </strong></p>
                                    <hr class="p-0 m-0 mb-1">
                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.output_qty') </b></label>
                                                <div class="col-md-8">
                                                    <input type="number" step="any" data-name="Quantity"
                                                        class="form-control add_input" name="output_quantity"
                                                        id="output_quantity" value="1.00">
                                                    <input type="text" name="parameter_quantity" class="d-none"
                                                        id="parameter_quantity" value="0.00">
                                                    <span class="error error_output_quantity"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.wasted_qty')</b></label>
                                                <div class="col-md-8">
                                                    <input type="number" step="any" name="wasted_quantity"
                                                        class="form-control" id="wasted_quantity" value="0.00">
                                                    <span class="error error_wasted_quantity"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.final') @lang('menu.output_qty') </b></label>
                                                <div class="col-md-8">
                                                    <input readonly type="text" step="any" class="form-control"
                                                        name="final_output_quantity" id="final_output_quantity"
                                                        value="1.00">
                                                    <span class="error error_final_output_quantity"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.additional_cost') </b></label>
                                                <div class="col-md-8">
                                                    <input name="production_cost" type="number" class="form-control"
                                                        id="production_cost" value="0.00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.total_production_cost') </b></label>
                                                <div class="col-md-8">
                                                    <input readonly type="number" step="any" name="total_cost"
                                                        class="form-control" id="total_cost" value="0.00">
                                                    <span class="error error_total_cost"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="form_element rounded m-0">
                                <div class="element-body">
                                    <p><strong>Pricing</strong></p>
                                    <hr class="p-0 m-0 mb-1">
                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label for="inputEmail3" class="col-4"><b>@lang('menu.tax') </b>
                                                </label>
                                                <div class="col-8">
                                                    <select class="form-control form-select" name="tax_ac_id"
                                                        id="tax_ac_id">
                                                        <option value="">@lang('menu.no_tax')</option>
                                                        @foreach ($taxes as $tax)
                                                            <option value="{{ $tax->id . '-' . $tax->tax_percent }}">
                                                                {{ $tax->tax_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label for="inputEmail3" class="col-4"><b>@lang('menu.tax_type') </b>
                                                </label>
                                                <div class="col-8">
                                                    <select name="tax_type" class="form-control form-select"
                                                        id="tax_type">
                                                        <option value="1">@lang('menu.exclusive')</option>
                                                        <option value="2">@lang('menu.inclusive')</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.par_unit_cost') </b></label>
                                                <div class="col-md-8">
                                                    <input type="text" name="per_unit_cost_exc_tax"
                                                        id="per_unit_cost_exc_tax" class="form-control"
                                                        placeholder="@lang('menu.par_unit_cost')" autocomplete="off"
                                                        value="0.00">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.cost')(Inc.Tax) </b></label>
                                                <div class="col-md-8">
                                                    <input readonly type="text" name="per_unit_cost_inc_tax"
                                                        id="per_unit_cost_inc_tax" class="form-control"
                                                        placeholder="@lang('menu.per_unit_cost_inc_tax')" autocomplete="off"
                                                        value="0.00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('short.x_margin')(%) </b></label>
                                                <div class="col-md-8">
                                                    <input type="text" name="xMargin" id="xMargin"
                                                        class="form-control" placeholder="@lang('short.x_margin')"
                                                        autocomplete="off" value="0.00">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.selling_price') </b></label>
                                                <div class="col-md-8">
                                                    <input type="text" name="selling_price" id="selling_price"
                                                        class="form-control" placeholder="@lang('menu.selling_price')"
                                                        autocomplete="off" value="0.00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <p class="float-end is_final">
                                        <input type="checkbox" name="is_final" id="is_final"> &nbsp; <b>
                                            @lang('menu.finalize')</b> <i data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Once finalized all ingredient stock will be deducted & production item stock will be increased and production item unit cost, price will be updated as well as editing of production will not be allowed."
                                            class="fas fa-info-circle tp"></i>
                                    </p>
                                </div>
                            </div>

                            <div class="submit_button_area">
                                <div class="row mt-1">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <div class="loading-btn-box">
                                            <button type="button" class="btn btn-sm loading_button display-none"><i
                                                    class="fas fa-spinner"></i></button>
                                            <button value="save"
                                                class="btn btn-success submit_button float-end">@lang('menu.save')</button>
                                            <button value="save_and_print"
                                                class="btn btn-success submit_button float-end me-2">@lang('menu.save_and_print')</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var tax_percent = 0;
        $('#tax_ac_id').on('change', function() {
            var tax = $(this).val();

            if (tax) {

                var split = tax.split('-');
                tax_percent = split[1];
            } else {

                tax_percent = 0;
            }
            __productPricingCalculate();
        });

        $('#tax_type').on('change', function() {

            __productPricingCalculate();
        });

        //Get process data
        $(document).on('change', '#product_id', function(e) {
            e.preventDefault();

            var processId = $(this).val();
            var stockWarehouseId = $('#stock_warehouse_id').val() ? $('#stock_warehouse_id').val() : null;

            @if (count($warehouses) > 0)

                if (stockWarehouseId == null) {

                    toastr.error('Ingredials Stock Location must not be empty.');
                    var processId = $(this).val('');
                    return;
                }
            @endif

            var url = "{{ route('manufacturing.productions.get.precess', ':processId') }}";
            var route = url.replace(':processId', processId);

            $.get(route, function(data) {
                $('#product_id').val(data.product_id);
                $('#variant_id').val(data.variant_id);
                $('#output_quantity').val(data.total_output_qty);
                $('#final_output_quantity').val(data.total_output_qty);
                $('#parameter_quantity').val(data.total_output_qty);
                $('#unit_id').val(data.unit_id);
                $('#production_cost').val(data.production_cost);
                $('#total_ingredient_cost').val(data.total_ingredient_cost);
                $('#span_total_ingredient_cost').html(data.total_ingredient_cost);
                $('#total_cost').val(data.total_cost);
                var tax = data.tax_ac_id ? data.tax_ac_id + '-' + data.tax_percent : '';
                tax_percent = data.tax_percent ? data.tax_percent : 0;
                $('#tax_ac_id').val(tax);
                var product_id = data.product_id;
                var variantId = data.variant_id ? data.variant_id : null;

                // var url = "{{ url('manufacturing/productions/get/ingredients') }}" + "/" + processId + "/" + stockWarehouseId;
                var url =
                    "{{ route('manufacturing.productions.get.ingredients', [':processId', ':stockWarehouseId']) }}";
                var route = url.replace(':processId', processId);
                route = route.replace(':stockWarehouseId', stockWarehouseId);

                $.get(route, function(data) {

                    $('#ingredient_list').html(data);
                    __calculateTotalAmount();
                });
            });
        });

        $(document).on('input', '#output_quantity', function() {

            var presentQty = $(this).val() ? $(this).val() : 0;
            var parameterQty = $('#parameter_quantity').val() ? $('#parameter_quantity').val() : 0;
            var meltipilerQty = parseFloat(presentQty) / parseFloat(parameterQty);
            var allTr = $('#ingredient_list').find('tr');
            allTr.each(function() {

                var parameterInputQty = $(this).find('#parameter_input_quantity').val();
                var updateInputQty = parseFloat(meltipilerQty) * parseFloat(parameterInputQty);
                $(this).find('#input_quantity').val(parseFloat(updateInputQty).toFixed(2));
                __calculateIngredientsTableAmount($(this));
            });
            __calculateTotalAmount();
        });

        $(document).on('input', '#wasted_quantity', function() {

            __calculateTotalAmount();
        });

        $(document).on('input', '#production_cost', function() {

            __calculateTotalAmount();
        });

        $(document).on('input', '#input_quantity', function() {

            var value = $(this).val() ? $(this).val() : 0;
            var tr = $(this).closest('tr');
            tr.find('#parameter_input_quantity').val(parseFloat(value).toFixed(2));
            __calculateIngredientsTableAmount(tr);
        });

        var errorCount = 0;

        function __calculateIngredientsTableAmount(tr) {

            var inputQty = tr.find('#input_quantity').val() ? tr.find('#input_quantity').val() : 0;
            var unitCostIncTax = tr.find('#unit_cost_inc_tax').val();
            var limitQty = tr.find('#qty_limit').val();
            var unitName = tr.find('#qty_limit').data('unit');
            var regexp = /^\d+\.\d{0,2}$/;
            tr.find('#input_qty_error').html('');

            if (regexp.test(parseFloat(inputQty)) == true) {

                tr.find('#input_qty_error').html('Deciaml value is not allowed.');
                errorCount++;
            } else if (parseFloat(inputQty) > parseFloat(limitQty)) {

                tr.find('#input_qty_error').html('Only ' + limitQty + ' ' + unitName + ' is available.');
                errorCount++;
            }

            var subtotal = parseFloat(inputQty) * parseFloat(unitCostIncTax);
            tr.find('#subtotal').val(parseFloat(subtotal).toFixed(2));
            tr.find('#span_subtotal').html(parseFloat(subtotal).toFixed(2));
            __calculateTotalAmount();
        }

        function __calculateTotalAmount() {

            var subtotals = document.querySelectorAll('#subtotal');
            var totalIngredientCost = 0;

            subtotals.forEach(function(subtotal) {

                totalIngredientCost += parseFloat(subtotal.value);
            });

            $('#total_ingredient_cost').val(parseFloat(totalIngredientCost));
            $('#span_total_ingredient_cost').html(parseFloat(totalIngredientCost).toFixed(2));
            var output_total_qty = $('#output_quantity').val() ? $('#output_quantity').val() : 0;
            var wast_qty = $('#wasted_quantity').val() ? $('#wasted_quantity').val() : 0;
            var calsQtyWithWastedQty = parseFloat(output_total_qty) - parseFloat(wast_qty);
            $('#final_output_quantity').val(calsQtyWithWastedQty);
            var productionCost = $('#production_cost').val() ? $('#production_cost').val() : 0;
            var totalCost = parseFloat(totalIngredientCost) + parseFloat(productionCost);
            $('#total_cost').val(parseFloat(totalCost).toFixed(2));
            __productPricingCalculate();
        }

        function __productPricingCalculate() {

            var total_cost = $('#total_cost').val() ? $('#total_cost').val() : 0;
            var final_output_qty = $('#final_output_quantity').val() ? $('#final_output_quantity').val() : 0;
            var par_unit_cost = parseFloat(total_cost) / parseFloat(final_output_qty);
            var tax_type = $('#tax_type').val();
            var calc_product_cost_tax = parseFloat(par_unit_cost) / 100 * parseFloat(tax_percent);

            if (tax_type == 2) {

                var inclusive_tax_percent = 100 + parseFloat(tax_percent);
                var calc_tax = parseFloat(par_unit_cost) / parseFloat(inclusive_tax_percent) * 100;
                calc_product_cost_tax = parseFloat(par_unit_cost) - parseFloat(calc_tax);
            }

            var per_unit_cost_inc_tax = parseFloat(par_unit_cost) + parseFloat(calc_product_cost_tax);
            $('#per_unit_cost_exc_tax').val(parseFloat(par_unit_cost).toFixed(2));
            $('#per_unit_cost_inc_tax').val(parseFloat(per_unit_cost_inc_tax).toFixed(2));

            var xMargin = $('#xMargin').val() ? $('#xMargin').val() : 0;

            if (xMargin > 0) {

                var calculate_margin = parseFloat(par_unit_cost) / 100 * parseFloat(xMargin);
                var selling_price = parseFloat(par_unit_cost) + parseFloat(calculate_margin);
                $('#selling_price').val(parseFloat(selling_price).toFixed(2));
            }
        }

        $('#xMargin').on('input', function() {

            __productPricingCalculate();
        });

        $(document).on('input', '#selling_price', function() {

            var selling_price = $(this).val() ? $(this).val() : 0;
            var par_unit_cost = $('#per_unit_cost_exc_tax').val() ? $('#per_unit_cost_exc_tax').val() : 0;
            var profitAmount = parseFloat(selling_price) - parseFloat(par_unit_cost);
            var __cost = parseFloat(par_unit_cost) > 0 ? parseFloat(par_unit_cost) : parseFloat(profitAmount);
            var calcProfit = parseFloat(profitAmount) / parseFloat(__cost) * 100;
            var __calcProfit = calcProfit ? calcProfit : 0;
            $('#xMargin').val(parseFloat(__calcProfit).toFixed(2));
        });

        $('.submit_button').on('click', function() {

            var value = $(this).val();
            $('#action_type').val(value);
        });

        //Add process request by ajax
        $('#add_production_form').on('submit', function(e) {
            e.preventDefault();
            errorCount = 0;
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            var allTr = $('#ingredient_list').find('tr');
            allTr.each(function() {

                __calculateIngredientsTableAmount($(this));
            });

            if (errorCount > 0) {

                $('.loading_button').hide();
                toastr.error('Please check again all form fields.', 'Some thing went wrong.');
                return;
            }

            $('.submit_button').prop('type', 'button');
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    $('.submit_button').prop('type', 'sumbit');
                    $('.loading_button').hide();

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                    } else if (!$.isEmptyObject(data.successMsg)) {

                        $('#add_production_form')[0].reset();
                        $('#ingredient_list').empty();
                        toastr.success(data.successMsg);
                    } else {

                        $('#add_production_form')[0].reset();
                        $('#ingredient_list').empty();
                        toastr.success('Successfully production is created.');
                        $(data).printThis({
                            debug: false,
                            importCSS: true,
                            importStyle: true,
                            loadCSS: "{{ asset('css/print/sale.print.css') }}",
                            removeInline: false,
                            printDelay: 1000,
                            header: null,
                        });
                    }
                },
                error: function(err) {

                    $('.submit_button').prop('type', 'sumbit');
                    $('.loading_button').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server error please contact to the support.');
                        return;
                    }

                    toastr.error('Please check again all form fields.', 'Some thing went wrong.');

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
        });

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
