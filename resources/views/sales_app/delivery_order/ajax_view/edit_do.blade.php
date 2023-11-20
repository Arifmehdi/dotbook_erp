<style>
    .order-item-sec {
        max-height: 245px !important;
        min-height: 245px !important;
    }
</style>
<div class="modal-dialog col-80-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.edit_delivery_order')</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <!--begin::Form-->
            <form id="edit_do_form" action="{{ route('sales.delivery.order.update', $do->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="edo_sale_id" value="{{ $do->id }}">
                <input type="hidden" name="sale_account_id" id="edo_sale_account_id" value="{{ $do->sale_account_id }}">
                <input type="hidden" name="price_group_id" id="edo_price_group_id" value="">
                <input type="hidden" name="expire_date" id="edo_expire_date" value="{{ $do->expire_date ? date('Y-m-d', strtotime($do->expire_date)) : '' }}">
                <input type="hidden" name="expire_time" id="edo_expire_time" value="{{ $do->expire_date ? date('H:i:s', strtotime($do->expire_date)) : '' }}">
                <input type="hidden" name="date" id="edo_date" value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($do->do_date)) }}">
                <input type="hidden" name="do_car_number" id="edo_do_car_number" value="{{ $do->do_car_number }}">
                <input type="hidden" name="do_driver_name" id="edo_do_driver_name" value="{{ $do->do_driver_name }}">
                <input type="hidden" name="do_driver_phone" id="edo_do_driver_phone" value="{{ $do->do_driver_phone }}">
                <input type="hidden" name="sale_note" id="edo_sale_note" value="{{ $do->sale_note }}">
                <div class="form-group row mt-1">
                    <div class="col-md-2">
                        <label><strong>@lang('menu.do_id') </strong> </label>
                        <input readonly type="text" class="form-control fw-bold" value="{{ $do->do_id }}" />
                    </div>

                    <div class="col-md-2">
                        <label><strong>@lang('short.delivery_date') </strong> </label>
                        <input readonly type="text" class="form-control" value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($do->do_date)) }}" />
                    </div>

                    <div class="col-md-2">
                        <label><strong>@lang('menu.customer') </strong></label>
                        <input readonly type="text" class="form-control fw-bold" value="{{ $do->customer ? $do->customer->name : 'Walk-In-Customer' }}" />
                    </div>

                    <div class="col-md-2">
                        <label><strong>@lang('menu.rate_type') </strong></label>
                        <select name="all_price_type" class="form-control form-select" id="e_all_price_type">
                            <option value="">@lang('menu.select_price_type')</option>
                            <option {{ $do->all_price_type == 'MR' ? 'SELECTED' : '' }} value="MR">MR</option>
                            <option {{ $do->all_price_type == 'PR' ? 'SELECTED' : '' }} value="PR">PR</option>
                        </select>
                    </div>

                    <div class="col-md-2 mt-1">
                        <a href="{{ route('sales.print.do', $do->id) }}" class="btn btn-sm btn-info mt-4 text-white" id="printBtn">DO Details</a>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-10">
                        <div class="item-details-sec">
                            <div class="content-inner">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="searching_area" style="position: relative;">
                                                    <label class="col-form-label">@lang('menu.item_search')</label>
                                                    <div class="input-group">
                                                        <input type="text" name="search_product" class="form-control scanable fw-bold" id="search_product" placeholder="@lang('menu.search_item_item_code_scan_bar_code')" autocomplete="off" autofocus>
                                                    </div>

                                                    <div class="select_area">
                                                        <ul id="list" class="variant_list_area"></ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="input-group mt-4">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text add_button p-1 m-0">@lang('menu.stock')</span>
                                                    </div>
                                                    <input type="text" readonly class="form-control text-success stock_quantity" autocomplete="off" id="e_stock_quantity" placeholder="@lang('menu.stock_quantity')" tabindex="-1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-8">
                                        <div class="hidden_fields">
                                            <input type="hidden" id="e_unit_cost_inc_tax">
                                            <input type="hidden" id="e_showing_unit_cost_inc_tax">
                                            <input type="hidden" id="e_item_name">
                                            <input type="hidden" id="e_product_id">
                                            <input type="hidden" id="e_variant_id">
                                            <input type="hidden" id="e_base_unit_name">
                                            <input type="hidden" id="e_tax_amount">
                                            <input type="hidden" id="e_showing_tax_amount">
                                            <input type="hidden" id="e_price_inc_tax">
                                            <input type="hidden" id="e_showing_price_inc_tax">
                                            <input type="hidden" id="e_is_show_emi_on_pos">
                                            <input type="hidden" id="e_base_unit_price_exc_tax">
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-4">
                                                <label><b>@lang('menu.order_quantity')</b></label>
                                                <div class="input-group">
                                                    <input type="number" step="any" class="form-control fw-bold w-60" id="e_showing_quantity" placeholder="@lang('menu.quantity')" value="0.00">
                                                    <input type="hidden" id="e_quantity" value="0.00">
                                                    <select id="e_unit_id" class="form-control w-40 form-select">
                                                        <option value="">@lang('menu.select_unit')</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <label><b>@lang('menu.per') @lang('menu.unit_price_exc_tax')</b></label>
                                                <input {{ auth()->user()->can('edit_price_sale_screen')? '': 'readonly' }} type="number" step="any" class="form-control fw-bold" id="e_showing_price_exc_tax" placeholder="@lang('menu.price_exclude_tax')" value="0.00">
                                                <input type="hidden" id="e_price_exc_tax" value="0.00">
                                            </div>

                                            <div class="col-md-4">
                                                <label><b>@lang('menu.pr_Amount') </b></label>
                                                <input type="number" step="any" class="form-control fw-bold" id="e_pr_amount" value="0.00" {{ $do->all_price_type == 'MR' ? 'readonly tabindex="-1"' : '' }} />
                                            </div>

                                            <div class="col-md-4">
                                                <label><b>@lang('menu.discount') (@lang('menu.per')
                                                        @lang('menu.unit'))</b></label>
                                                <div class="input-group">
                                                    <input {{ auth()->user()->can('edit_discount_sale_screen')? '': 'readonly' }} type="number" step="any" class="form-control fw-bold" id="e_showing_discount" placeholder="@lang('menu.discount')" value="0.00">
                                                    <input type="hidden" id="e_discount" value="0.00">
                                                    <select id="e_discount_type" class="form-control form-select">
                                                        <option value="1">@lang('menu.fixed')(0.00)</option>
                                                        <option value="2">@lang('menu.percentage')(%)</option>
                                                    </select>
                                                    <input type="hidden" id="e_discount_amount">
                                                    <input type="hidden" id="e_showing_discount_amount">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
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

                                            <div class="col-md-4">
                                                <label><b>@lang('menu.sub_total')</b></label>
                                                <input readonly type="number" step="any" class="form-control fw-bold" id="e_subtotal" value="0.00" tabindex="-1">
                                            </div>

                                            <div class="col-md-12">
                                                <a href="#" class="btn btn-sm btn-success mt-2 px-3 float-end" id="edo_add_item">@lang('menu.add')</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="sale-item-sec order-item-sec">
                                        <div class="sale-item-inner">
                                            <div class="table-responsive">
                                                <table class="display data__table table sale-product-table">
                                                    <thead class="staky">
                                                        <tr>
                                                            <th class="text-startx">@lang('menu.item')</th>
                                                            <th class="text-center">@lang('menu.do_quantity')</th>
                                                            <th>@lang('menu.unit')</th>
                                                            <th class="text-center">@lang('menu.price_inc_tax')</th>
                                                            <th class="text-center">@lang('menu.rate_type')</th>
                                                            <th>@lang('menu.sub_total')</th>
                                                            <th><i class="fas fa-minus text-dark"></i></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="edo_do_item_list">
                                                        @php
                                                            $itemUnitsArray = [];
                                                        @endphp
                                                        @foreach ($do->saleProducts as $doProduct)
                                                            @php
                                                                if (isset($doProduct->product_id)) {
                                                                    $itemUnitsArray[$doProduct->product_id][] = [
                                                                        'unit_id' => $doProduct->product->unit->id,
                                                                        'unit_name' => $doProduct->product->unit->name,
                                                                        'unit_code_name' => $doProduct->product->unit->code_name,
                                                                        'base_unit_multiplier' => 1,
                                                                        'multiplier_details' => '',
                                                                        'is_base_unit' => 1,
                                                                    ];
                                                                }

                                                                if (count($doProduct?->product?->unit?->childUnits) > 0) {
                                                                    foreach ($doProduct?->product?->unit?->childUnits as $unit) {
                                                                        $multiplierDetails = '(1 ' . $unit->name . ' = ' . $unit->base_unit_multiplier . '/' . $doProduct?->product?->unit?->name . ')';

                                                                        array_push($itemUnitsArray[$doProduct->product_id], [
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
                                                            <tr id="edo_select_item">
                                                                <td>
                                                                    @php
                                                                        $variant = $doProduct->product_variant_id ? ' -' . $doProduct->variant->variant_name : '';
                                                                        $variantId = $doProduct->product_variant_id ? $doProduct->product_variant_id : 'noid';
                                                                        $currentStock = $doProduct->product_variant_id ? $doProduct->variant->variant_quantity : $doProduct->product->quantity;

                                                                        $baseUnitMultiplier = $doProduct?->saleUnit?->base_unit_multiplier ? $doProduct?->saleUnit?->base_unit_multiplier : 1;
                                                                    @endphp

                                                                    <span class="edo_product_name">{{ $doProduct->product->name . $variant }}</span>

                                                                    <input type="hidden" name="is_show_emi_on_pos[]" id="edo_is_show_emi_on_pos" id="dsc" value="{{ $doProduct->product->is_show_emi_on_pos }}">
                                                                    <input type="hidden" name="descriptions[]" id="edo_descriptions" value="">
                                                                    <input type="hidden" id="edo_item_name" value="{{ $doProduct->product->name . $variant }}">
                                                                    <input type="hidden" name="product_ids[]" id="edo_product_id" value="{{ $doProduct->product_id }}">
                                                                    <input type="hidden" name="variant_ids[]" id="edo_variant_id" value="{{ $variantId }}">
                                                                    <input type="hidden" name="tax_types[]" id="edo_tax_type" value="{{ $doProduct->tax_type }}">
                                                                    <input type="hidden" name="tax_ac_ids[]" id="edo_tax_ac_id" value="{{ $doProduct->tax_ac_id }}">
                                                                    <input type="hidden" name="unit_tax_percents[]" id="edo_unit_tax_percent" value="{{ $doProduct->unit_tax_percent }}">
                                                                    <input type="hidden" name="unit_tax_amounts[]" id="edo_unit_tax_amount" value="{{ $doProduct->unit_tax_amount }}">
                                                                    @php
                                                                        $showingTaxAmount = $doProduct->unit_tax_amount * $baseUnitMultiplier;
                                                                    @endphp

                                                                    <input type="hidden" id="edo_showing_unit_tax_amount" value="{{ bcadd($showingTaxAmount, 0, 2) }}">

                                                                    @php
                                                                        $showingUnitDiscount = $doProduct->unit_discount_type == 1 ? $doProduct->doProduct * $baseUnitMultiplier : $doProduct->unit_discount;
                                                                    @endphp

                                                                    <input type="hidden" name="unit_discount_types[]" id="edo_unit_discount_type" value="{{ $doProduct->unit_discount_type }}">
                                                                    <input type="hidden" name="unit_discounts[]" id="edo_unit_discount" value="{{ $doProduct->unit_discount }}">
                                                                    <input type="hidden" id="edo_showing_unit_discount" value="{{ $showingUnitDiscount }}">
                                                                    <input type="hidden" name="unit_discount_amounts[]" id="edo_unit_discount_amount" value="{{ $doProduct->unit_discount_amount }}">
                                                                    <input type="hidden" id="edo_showing_unit_discount_amount" value="{{ $doProduct->unit_discount_amount * $baseUnitMultiplier }}">
                                                                    <input type="hidden" name="unit_costs_inc_tax[]" id="edo_unit_cost_inc_tax" value="{{ $doProduct->unit_cost_inc_tax }}">

                                                                    @php
                                                                        $showingUnitCostIncTax = $doProduct->unit_cost_inc_tax * $baseUnitMultiplier;
                                                                    @endphp

                                                                    <input type="hidden" id="edo_showing_unit_cost_inc_tax" value="{{ bcadd($showingUnitCostIncTax, 0, 2) }}">
                                                                    <input type="hidden" name="do_product_ids[]" value="{{ $doProduct->id }}">
                                                                    <input type="hidden" id="{{ $doProduct->product_id . $variantId }}" value="{{ $doProduct->product_id . $variantId }}">
                                                                    <input type="hidden" id="edo_current_stock" value="{{ $currentStock }}">
                                                                </td>

                                                                <td>
                                                                    @php
                                                                        $showingQuantity = $doProduct->ordered_quantity / $baseUnitMultiplier;
                                                                    @endphp
                                                                    <span id="edo_span_showing_quantity" class="fw-bold">{{ bcadd($showingQuantity, 0, 2) }}</span>
                                                                    <input type="hidden" name="quantities[]" id="edo_quantity" value="{{ $doProduct->ordered_quantity }}">
                                                                    <input type="hidden" id="edo_showing_quantity" value="{{ bcadd($showingQuantity, 0, 2) }}">
                                                                </td>

                                                                <td class="text">
                                                                    <span id="edo_span_unit">{{ $doProduct?->saleUnit?->name }}</span>
                                                                    <input type="hidden" name="unit_ids[]" id="edo_unit_id" value="{{ $doProduct?->saleUnit?->id }}">
                                                                    <input type="hidden" id="edo_base_unit_name" value="{{ $doProduct?->product?->unit?->name }}">
                                                                </td>

                                                                <td>
                                                                    <input type="hidden" name="unit_prices_exc_tax[]" id="edo_unit_price_exc_tax" value="{{ $doProduct->unit_price_exc_tax }}">

                                                                    @php
                                                                        $showingPriceExcTax = $doProduct->unit_price_exc_tax * $baseUnitMultiplier;
                                                                    @endphp

                                                                    <input type="hidden" id="edo_showing_unit_price_exc_tax" value="{{ bcadd($showingPriceExcTax, 0, 2) }}">
                                                                    <input type="hidden" name="unit_prices_inc_tax[]" id="edo_unit_price_inc_tax" value="{{ $doProduct->unit_price_inc_tax }}">

                                                                    @php
                                                                        $showingPriceIncTax = $doProduct->unit_price_inc_tax * $baseUnitMultiplier;
                                                                    @endphp

                                                                    <input type="hidden" id="edo_showing_unit_price_inc_tax" value="{{ bcadd($showingPriceIncTax, 0, 2) }}">
                                                                    <span id="edo_span_showing_unit_price_inc_tax" class="fw-bold">{{ bcadd($showingPriceIncTax, 0, 2) }}</span>
                                                                </td>

                                                                <td class="text-center">
                                                                    @php
                                                                        $showPrAmount = $doProduct->price_type == 'PR' ? '(' . $doProduct->pr_amount . ')' : '';
                                                                    @endphp
                                                                    <span id="edo_span_price_type">{{ $doProduct->price_type . $showPrAmount }}</span>
                                                                    <input type="hidden" name="price_types[]" id="edo_price_type" value="{{ $doProduct->price_type }}">
                                                                    <input type="hidden" name="pr_amounts[]" id="edo_pr_amount" value="{{ $doProduct->pr_amount }}">
                                                                </td>

                                                                <td class="text text-center">
                                                                    <strong><span class="edo_span_subtotal">{{ $doProduct->subtotal }}</span></strong>
                                                                    <input value="{{ $doProduct->subtotal }}" readonly name="subtotals[]" type="hidden" id="edo_subtotal" tabindex="-1">
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

                    <div class="col-md-2">
                        <div class="row total_item_and_quantity_area">
                            <div class="col-md-12">
                                <label class="fw-bold">@lang('menu.total_item') </label>
                                <input readonly type="number" step="any" name="total_item" id="edo_total_item" class="form-control fw-bold" value="{{ $do->total_item }}" tabindex="-1">
                            </div>

                            <div class="col-md-12 mt-1">
                                <label class="fw-bold">@lang('menu.total_do_qty') </label>
                                <input readonly type="text" step="any" class="form-control fw-bold" value="{{ $do->total_ordered_qty }}" tabindex="-1">
                            </div>

                            <div class="col-md-12 mt-1">
                                <label class="fw-bold">@lang('menu.total_adjusted_qty') </label>
                                <input readonly type="number" step="any" name="total_qty" id="edo_total_qty" class="form-control fw-bold" value="{{ $do->total_ordered_qty }}" tabindex="-1">
                            </div>
                        </div>

                        <div class="hidden_fields">
                            <input type="hidden" name="net_total_amount" id="net_total_amount" value="{{ $do->net_total_amount }}">
                            <input type="hidden" name="order_discount_type" id="edo_order_discount_type" value="{{ $do->order_discount_type }}">
                            <input type="hidden" name="order_discount_type" id="edo_order_discount_type" value="{{ $do->order_discount_type }}">
                            <input type="hidden" name="order_discount" id="edo_order_discount" value="{{ $do->order_discount }}">
                            <input type="hidden" name="order_discount_amount" id="edo_order_discount_amount" value="{{ $do->order_discount_amount }}">
                            <input type="hidden" name="order_tax_percent" id="edo_order_tax_percent" value="{{ $do->order_tax_percent }}">
                            <input type="hidden" name="order_tax_amount" id="edo_order_tax_amount" value="0.00">
                            <input type="hidden" name="shipment_charge" id="edo_shipment_charge" value="{{ $do->shipment_charge }}">
                            <input type="hidden" name="total_invoice_amount" id="edo_total_invoice_amount" value="0.00">
                        </div>
                    </div>
                </div>

                <div class="form-group row text-right mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button do_edit_loading_button display-none"><i class="fas fa-spinner"></i></button>
                            <button type="submit" id="submit_do_edit_btn" class="btn btn-sm btn-success do_submit_button float-end">@lang('menu.save_changes')</button>
                            <button type="button" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var itemUnitsArray = @json($itemUnitsArray);
    var price_groups = [];

    var delay = (function() {
        var timer = 0;
        return function(callback, ms) {

            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    })();

    $('#search_product').on('input', function(e) {

        var all_price_type = $('#e_all_price_type').val();

        if (all_price_type == '') {

            toastr.error('Please a rate type first.');
            return;
        }

        $('.variant_list_area').empty();
        $('.select_area').hide();
        var keyWord = $(this).val();
        var __keyWord = keyWord.replaceAll('/', '~');

        var price_group_id = $('#edo_price_group_id').val() ? $('#edo_price_group_id').val() : 'no_id';
        delay(function() {
            searchProduct(__keyWord, price_group_id);
        }, 200); //sendAjaxical is the name of remote-command
    });

    function searchProduct(keyWord, priceGroupId) {

        $('#search_product').focus();
        var type = 'sales_order';
        var isShowNotForSaleItem = 0;
        var url =
            "{{ route('general.product.search.common', [':keyWord', ':isShowNotForSaleItem', ':priceGroupId', ':type', $do->id]) }}";
        var route = url.replace(':keyWord', keyWord);
        route = route.replace(':isShowNotForSaleItem', isShowNotForSaleItem);
        route = route.replace(':priceGroupId', priceGroupId);
        route = route.replace(':type', type);

        $.ajax({
            url: route,
            dataType: 'json',
            success: function(product) {

                if (!$.isEmptyObject(product.errorMsg || keyWord == '')) {

                    toastr.error(product.errorMsg);
                    $('#search_product').val("");
                    $('.select_area').hide();
                    $('#e_stock_quantity').val(parseFloat(0).toFixed(2));
                    return;
                }

                var discount = product.discount;

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
                            if (product.is_manage_stock == 1) {

                                $('#e_stock_quantity').val(parseFloat(product.quantity).toFixed(2));
                            }

                            var price = 0;
                            var __price = price_groups.filter(function(value) {

                                return value.price_group_id == price_group_id && value.product_id ==
                                    product.id;
                            });

                            if (__price.length != 0) {

                                price = __price[0].price ? __price[0].price : product.product_price;
                            } else {

                                price = product.product_price;
                            }

                            var discount_amount = 0;
                            if (discount.discount_type == 1) {

                                discount_amount = discount.discount_amount
                            } else {

                                discount_amount = (parseFloat(price) / 100) * discount.discount_amount;
                            }

                            var name = product.name.length > 35 ? product.name.substring(0, 35) + '...' :
                                product.name;

                            $('#search_product').val(name);
                            $('#e_item_name').val(name);
                            $('#e_product_id').val(product.id);
                            $('#e_variant_id').val('noid');
                            $('#e_base_unit_name').val(product.unit.name);
                            $('#e_showing_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                            $('#e_showing_price_exc_tax').val(parseFloat(price).toFixed(2));
                            $('#e_showing_discount').val(parseFloat(discount.discount_amount).toFixed(2));
                            $('#e_discount_type').val(discount.discount_type);
                            $('#e_showing_discount_amount').val(parseFloat(discount_amount).toFixed(2));
                            $('#e_tax_ac_id').val(product.tax_ac_id);
                            $('#e_tax_type').val(product.tax_type);
                            $('#e_unit_cost_inc_tax').val(product.update_product_cost ? product.update_product_cost.net_unit_cost : product.product_cost_with_tax);
                            $('#display_unit_cost').html(product.update_product_cost ? product.update_product_cost.net_unit_cost : product.product_cost_with_tax);
                            $('#e_is_show_emi_on_pos').val(product.is_show_emi_on_pos);
                            $('#e_base_unit_price_exc_tax').val(price);

                            $('#e_unit_id').empty();
                            $('#e_unit_id').append(
                                '<option value="' + product.unit.id +
                                '" data-is_base_unit="1" data-unit_name="' + product.unit.name +
                                '" data-base_unit_multiplier="1">' + product.unit.name + '</option>'
                            );

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

                                    $('#e_unit_id').append(
                                        '<option value="' + unit.id +
                                        '" data-is_base_unit="0" data-unit_name="' + unit.name +
                                        '" data-base_unit_multiplier="' + unit
                                        .base_unit_multiplier + '">' + unit.name +
                                        multiplierDetails + '</option>'
                                    );
                                });
                            }

                            $('#edo_add_item').html('Add');
                            edoCalculateEditOrAddAmount();
                        } else {

                            var li = "";
                            $.each(product.variants, function(key, variant) {

                                product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/default.jpg') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;

                                var price = 0;
                                var __price = price_groups.filter(function(value) {

                                    return value.price_group_id == price_group_id && value.product_id == product.id && value.variant_id == variant.id;
                                });

                                if (__price.length != 0) {

                                    price = __price[0].price ? __price[0].price : variant.variant_price;
                                } else {

                                    price = variant.variant_price;
                                }

                                li += '<li>';
                                li += '<a class="select_variant_product" onclick="edoSelectProduct(this); return false;" data-product_type="variant" data-p_id="' + product.id + '" data-is_manage_stock="' + product.is_manage_stock + '" data-v_id="' + variant.id + '" data-p_name="' + product.name + '" data-p_tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-tax_type="' + product.tax_type + '" data-is_show_emi_on_pos="' + product.is_show_emi_on_pos + '" data-p_code="' + variant.variant_code + '" data-p_price_exc_tax="' + price + '" data-v_name="' + variant.variant_name + '" data-p_cost_inc_tax="' + (variant.update_variant_cost ? variant.update_variant_cost.net_unit_cost : variant.variant_cost_with_tax) + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + ' - ' + variant.variant_name + '</a>';
                                li += '</li>';
                            });

                            $('.variant_list_area').append(li);
                            $('.select_area').show();
                            $('#search_product').val('');
                        }
                    } else if (!$.isEmptyObject(product.variant_product)) {

                        $('.select_area').hide();
                        $('#search_product').val('');

                        if (product.is_manage_stock == 1) {

                            $('#e_stock_quantity').val(parseFloat(variant.variant_quantity).toFixed(2));
                        }

                        var variant_product = product.variant_product;

                        var price = 0;
                        var __price = price_groups.filter(function(value) {

                            return value.price_group_id == price_group_id && value.product_id == variant_product.product.id && value.variant_id == variant_product.id;
                        });

                        if (__price.length != 0) {

                            price = __price[0].price ? __price[0].price : variant_product.variant_price;
                        } else {

                            price = variant_product.variant_price;
                        }

                        var discount_amount = 0;
                        if (discount.discount_type == 1) {

                            discount_amount = discount.discount_amount
                        } else {

                            discount_amount = (parseFloat(price) / 100) * discount.discount_amount;
                        }

                        var name = variant_product.product.name.length > 35 ? variant_product.product.name.substring(0, 35) + '...' : variant_product.product.name;

                        $('#search_product').val(name + variant_product.variant_name);
                        $('#e_item_name').val(name + variant_product.variant_name);
                        $('#e_product_id').val(variant_product.product.id);
                        $('#e_variant_id').val(variant_product.id);
                        $('#e_base_unit_name').val(variant_product.product.unit.name);
                        $('#e_showing_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                        $('#e_showing_price_exc_tax').val(parseFloat(price).toFixed(2));
                        $('#e_showing_discount').val(parseFloat(discount.discount_amount).toFixed(2));
                        $('#e_discount_type').val(discount.discount_type);
                        $('#e_showing_discount_amount').val(parseFloat(discount_amount).toFixed(2));
                        $('#e_tax_ac_id').val(variant_product.product.tax_id);
                        $('#e_tax_type').val(variant_product.product.tax_type);
                        $('#e_unit_cost_inc_tax').val(variant_product.update_variant_cost ? variant_product.update_variant_cost.net_unit_cost : variant_product.variant_cost_with_tax);
                        $('#display_unit_cost').html(variant_product.update_variant_cost ? variant_product.update_variant_cost.net_unit_cost : variant_product.variant_cost_with_tax);
                        $('#e_is_show_emi_on_pos').val(product.is_show_emi_on_pos);
                        $('#e_base_unit_price_exc_tax').val(price);

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

                        $('#edo_add_item').html('Add');
                        edoCalculateEditOrAddAmount();
                    } else if (!$.isEmptyObject(product.namedProducts)) {

                        if (product.namedProducts.length > 0) {

                            var li = "";
                            var products = product.namedProducts;

                            $.each(products, function(key, product) {

                                product.thumbnail_photo = product.thumbnail_photo === null ? "{{ asset('images/default.jpg') }}" : "{{ asset('uploads/product/thumbnail') }}" + '/' + product.thumbnail_photo;

                                if (product.is_variant == 1) {

                                    var price = 0;
                                    var __price = price_groups.filter(function(value) {

                                        return value.price_group_id == price_group_id && value.product_id == product.id && value.variant_id == product.variant_id;
                                    });

                                    if (__price.length != 0) {

                                        price = __price[0].price ? __price[0].price : product.variant_price;
                                    } else {

                                        price = product.variant_price;
                                    }

                                    li += '<li>';
                                    li += '<a class="select_variant_product" onclick="edoSelectProduct(this); return false;" data-product_type="variant" data-p_id="' + product.id + '" data-is_manage_stock="' + product.is_manage_stock + '" data-v_id="' + product.variant_id + '" data-p_name="' + product.name + '" data-v_name="' + product.variant_name + '" data-p_tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-tax_type="' + product.tax_type + '" data-is_show_emi_on_pos="' + product.is_show_emi_on_pos + '" data-p_code="' + product.variant_code + '" data-p_price_exc_tax="' + price + '" data-p_cost_inc_tax="' + product.variant_cost_with_tax + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + ' - ' + product.variant_name + '</a>';
                                    li += '</li>';

                                } else {

                                    var price = 0;
                                    var __price = price_groups.filter(function(value) {

                                        return value.price_group_id == price_group_id && value.product_id == product.id;
                                    });

                                    if (__price.length != 0) {

                                        price = __price[0].price ? __price[0].price : product.product_price;
                                    } else {

                                        price = product.product_price;
                                    }

                                    li += '<li>';
                                    li += '<a class="select_single_product" onclick="edoSelectProduct(this); return false;" data-product_type="single" data-p_id="' + product.id + '" data-v_id="" data-v_name="" data-is_manage_stock="' + product.is_manage_stock + '" data-p_name="' + product.name + '" data-p_code="' + product.product_code + '" data-p_price_exc_tax="' + price + '" data-tax_type="' + product.tax_type + '" data-p_tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-is_show_emi_on_pos="' + product.is_show_emi_on_pos + '" data-p_cost_inc_tax="' + product.product_cost_with_tax + '" href="#"><img style="width:20px; height:20px;" src="' + product.thumbnail_photo + '"> ' + product.name + '</a>';
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

    // select single product and add stock adjustment table
    function edoSelectProduct(e) {

        var price_group_id = $('#edo_price_group_id').val() ? $('#edo_price_group_id').val() : 'no_id';
        $('.select_area').hide();
        $('#search_product').val('');

        var product_id = e.getAttribute('data-p_id');
        var variant_id = e.getAttribute('data-v_id') ? e.getAttribute('data-v_id') : 'noid';
        var is_manage_stock = e.getAttribute('data-is_manage_stock');
        var product_name = e.getAttribute('data-p_name');
        var variant_name = e.getAttribute('data-v_name');
        var product_code = e.getAttribute('data-p_code');
        var product_cost_inc_tax = e.getAttribute('data-p_cost_inc_tax');
        var product_price_exc_tax = e.getAttribute('data-p_price_exc_tax');
        var p_tax_ac_id = e.getAttribute('data-p_tax_ac_id');
        var p_tax_type = e.getAttribute('data-tax_type');
        var is_show_emi_on_pos = e.getAttribute('data-is_show_emi_on_pos');

        var e_product_id = $('#e_product_id').val();
        var e_variant_id = $('#e_variant_id').val();

        var url = "{{ route('general.product.search.check.product.discount.with.stock', [':product_id', ':variant_id', ':price_group_id']) }}"
        var route = url.replace(':product_id', product_id);
        route = route.replace(':variant_id', variant_id);
        route = route.replace(':price_group_id', price_group_id);

        $.ajax({
            url: route,
            type: 'get',
            dataType: 'json',
            success: function(data) {

                if ($.isEmptyObject(data.errorMsg)) {

                    if (is_manage_stock == 1) {

                        $('#e_stock_quantity').val(parseFloat(data.stock).toFixed(2));
                    }

                    var price = 0;
                    var __price = price_groups.filter(function(value) {

                        return value.price_group_id == price_group_id && value.product_id == product_id;
                    });

                    if (__price.length != 0) {

                        price = __price[0].price ? __price[0].price : product_price_exc_tax;
                    } else {

                        price = product_price_exc_tax;
                    }

                    var discount = data.discount;

                    var discount_amount = 0;
                    if (discount.discount_type == 1) {

                        discount_amount = discount.discount_amount
                    } else {

                        discount_amount = (parseFloat(price) / 100) * discount.discount_amount;
                    }

                    var name = product_name.length > 35 ? product_name.substring(0, 35) + '...' : product_name;

                    $('#e_unit_cost_inc_tax').val(parseFloat(product_cost_inc_tax).toFixed(2));
                    $('#display_unit_cost').html(product_cost_inc_tax);

                    $('#e_unit_id').empty();
                    $('#e_unit_id').append(
                        '<option value="' + data.unit.id +
                        '" data-is_base_unit="1" data-unit_name="' + data.unit.name +
                        '" data-base_unit_multiplier="1">' + data.unit.name + '</option>'
                    );

                    itemUnitsArray[product_id] = [{
                        'unit_id': data.unit.id,
                        'unit_name': data.unit.name,
                        'unit_code_name': data.unit.code_name,
                        'base_unit_multiplier': 1,
                        'multiplier_details': '',
                        'is_base_unit': 1,
                    }];

                    if (data.unit.child_units.length > 0) {

                        data.unit.child_units.forEach(function(unit) {

                            var multiplierDetails = '(1 ' + unit.name + ' = ' + unit.base_unit_multiplier + '/' + data.unit.name + ')';

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
                                '" data-base_unit_multiplier="' + unit.base_unit_multiplier +
                                '">' + unit.name + multiplierDetails + '</option>'
                            );
                        });
                    }

                    if (!e_product_id && !e_variant_id) {

                        $('#search_product').val(name + (variant_name ? ' - ' + variant_name : ''));
                        $('#e_item_name').val(name + (variant_name ? ' - ' + variant_name : ''));
                        $('#e_product_id').val(product_id);
                        $('#e_variant_id').val(variant_id);
                        $('#e_showing_quantity').val(parseFloat(1).toFixed(2));
                        $('#e_showing_price_exc_tax').val(parseFloat(price).toFixed(2));
                        $('#e_discount_type').val(discount.discount_type);
                        $('#e_showing_discount').val(parseFloat(discount.discount_amount).toFixed(2));
                        $('#e_showing_discount_amount').val(parseFloat(discount_amount).toFixed(2));
                        $('#e_tax_ac_id').val(p_tax_ac_id);
                        $('#e_tax_type').val(p_tax_type);
                        $('#e_is_show_emi_on_pos').val(is_show_emi_on_pos);
                        $('#e_base_unit_price_exc_tax').val(price);
                    } else {

                        $('#search_product').val(name + (variant_name ? ' - ' + variant_name : ''));
                        $('#e_item_name').val(name + (variant_name ? ' - ' + variant_name : ''));
                        $('#e_product_id').val(product_id);
                        $('#e_variant_id').val(variant_id);
                    }

                    $('#e_showing_quantity').focus().select();
                    $('#edo_add_item').html('Add');

                    edoCalculateEditOrAddAmount();
                } else {

                    toastr.error(data.errorMsg);
                }
            }
        });
    }

    $('#edo_add_item').on('click', function(e) {
        e.preventDefault();

        var e_item_name = $('#e_item_name').val();
        var e_product_id = $('#e_product_id').val();
        var e_variant_id = $('#e_variant_id').val();
        var e_unit_id = $('#e_unit_id').val();
        var e_unit_name = $('#e_unit_id').find('option:selected').data('unit_name');
        var e_base_unit_name = $('#e_base_unit_name').val();
        var e_all_price_type = $('#e_all_price_type').val() ? $('#e_all_price_type').val() : 'N/A';
        var e_pr_amount = $('#e_all_price_type').val() == 'PR' ? ($('#e_pr_amount').val() ? $('#e_pr_amount').val() : 0) : 0.00;
        var e_showing_quantity = $('#e_showing_quantity').val() ? $('#e_showing_quantity').val() : 0;
        var e_quantity = $('#e_quantity').val() ? $('#e_quantity').val() : 0;
        var e_showing_price_exc_tax = $('#e_showing_price_exc_tax').val() ? $('#e_showing_price_exc_tax').val() : 0;
        var e_price_exc_tax = $('#e_price_exc_tax').val() ? $('#e_price_exc_tax').val() : 0;
        var e_discount_type = $('#e_discount_type').val();
        var e_discount = $('#e_discount').val() ? $('#e_discount').val() : 0;
        var e_showing_discount = $('#e_showing_discount').val() ? $('#e_showing_discount').val() : 0;
        var e_discount_amount = $('#e_discount_amount').val() ? $('#e_discount_amount').val() : 0;
        var e_showing_discount_amount = $('#e_showing_discount_amount').val() ? $('#e_showing_discount_amount').val() : 0;
        var e_tax_ac_id = $('#e_tax_ac_id').val();
        var e_tax_percent = $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') ? $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') : 0;
        var e_tax_amount = $('#e_tax_amount').val() ? $('#e_tax_amount').val() : 0;
        var e_showing_tax_amount = $('#e_showing_tax_amount').val() ? $('#e_showing_tax_amount').val() : 0;
        var e_tax_type = $('#e_tax_type').val();
        var e_price_inc_tax = $('#e_price_inc_tax').val() ? $('#e_price_inc_tax').val() : 0;
        var e_showing_price_inc_tax = $('#e_showing_price_inc_tax').val() ? $('#e_showing_price_inc_tax').val() : 0;
        var e_subtotal = $('#e_subtotal').val() ? $('#e_subtotal').val() : 0;
        var e_unit_cost_inc_tax = $('#e_unit_cost_inc_tax').val() ? $('#e_unit_cost_inc_tax').val() : 0;
        var e_showing_unit_cost_inc_tax = $('#e_showing_unit_cost_inc_tax').val() ? $('#e_showing_unit_cost_inc_tax').val() : 0;
        var display_unit_cost = $('#display_unit_cost').val();
        var e_is_show_emi_on_pos = $('#e_is_show_emi_on_pos').val();
        var e_stock_quantity = $('#e_stock_quantity').val();
        var showPrAmount = e_all_price_type == 'PR' ? '(' + parseFloat(e_pr_amount).toFixed(2) + ')' : '';

        if (e_quantity == '') {

            toastr.error('Quantity field must not be empty.');
            return;
        }

        if (e_product_id == '') {

            toastr.error('Please select a product.');
            return;
        }

        var uniqueId = e_product_id + e_variant_id;

        var uniqueIdValue = $('#' + e_product_id + e_variant_id).val();

        if (uniqueIdValue == undefined) {

            var tr = '';
            tr += '<tr id="edo_select_item">';
            tr += '<td class="text-start">';
            tr += '<span class="edo_product_name">' + e_item_name + '</span>';
            tr += '<input type="hidden" id="edo_item_name" value="' + e_item_name + '">';
            tr += '<input type="hidden" name="is_show_emi_on_pos[]" id="edo_is_show_emi_on_pos" value="' + e_is_show_emi_on_pos + '">';
            tr += '<input type="hidden" name="descriptions[]" id="edo_descriptions" value="">';
            tr += '<input type="hidden" name="product_ids[]" id="edo_product_id" value="' + e_product_id + '">';
            tr += '<input type="hidden" name="variant_ids[]" id="edo_variant_id" value="' + e_variant_id + '">';
            tr += '<input type="hidden" name="tax_ac_ids[]" id="edo_tax_ac_id" value="' + e_tax_ac_id + '">';
            tr += '<input type="hidden" name="tax_types[]" id="edo_tax_type" value="' + e_tax_type + '">';
            tr += '<input type="hidden" name="unit_tax_percents[]" id="edo_unit_tax_percent" value="' + e_tax_percent + '">';
            tr += '<input type="hidden" name="unit_tax_amounts[]" id="edo_unit_tax_amount" value="' + parseFloat(e_tax_amount).toFixed(2) + '">';
            tr += '<input type="hidden" id="edo_showing_unit_tax_amount" value="' + parseFloat(e_showing_tax_amount).toFixed(2) + '">';
            tr += '<input type="hidden" name="unit_discount_types[]" id="edo_unit_discount_type" value="' + e_discount_type + '">';
            tr += '<input type="hidden" name="unit_discounts[]" id="edo_unit_discount" value="' + e_discount + '">';
            tr += '<input type="hidden" id="edo_showing_unit_discount" value="' + e_showing_discount + '">';
            tr += '<input type="hidden" name="unit_discount_amounts[]" id="edo_unit_discount_amount" value="' + e_discount_amount + '">';
            tr += '<input type="hidden" id="edo_showing_unit_discount_amount" value="' + e_showing_discount_amount + '">';
            tr += '<input type="hidden" name="unit_costs_inc_tax[]" id="edo_unit_cost_inc_tax" value="' + e_unit_cost_inc_tax + '">';
            tr += '<input type="hidden" id="edo_showing_unit_cost_inc_tax" value="' + e_showing_unit_cost_inc_tax + '">';
            tr += '<input type="hidden" name="do_product_ids[]" value="">';
            tr += '<input type="hidden" id="edo_current_stock" value="' + e_stock_quantity + '">';
            tr += '<input type="hidden" id="' + e_product_id + e_variant_id + '" value="' + e_product_id + e_variant_id + '">';
            tr += '</td>';

            tr += '<td>';
            tr += '<span id="edo_span_showing_quantity" class="fw-bold">' + parseFloat(e_showing_quantity).toFixed(2) + '</span>';
            tr += '<input type="hidden" name="quantities[]" id="edo_quantity" value="' + parseFloat(e_quantity).toFixed(2) + '">';
            tr += '<input type="hidden" id="edo_showing_quantity" value="' + parseFloat(e_showing_quantity).toFixed(2) + '">';
            tr += '</td>';

            tr += '<td class="text">';
            tr += '<b><span id="edo_span_unit">' + e_unit_name + '</span></b>';
            tr += '<input type="hidden" name="unit_ids[]" id="edo_unit_id" value="' + e_unit_id + '">';
            tr += '<input type="hidden" id="edo_base_unit_name" value="' + e_base_unit_name + '">';
            tr += '</td>';

            tr += '<td>';
            tr += '<input type="hidden" name="unit_prices_exc_tax[]" id="edo_unit_price_exc_tax" value="' + parseFloat(e_price_exc_tax).toFixed(2) + '">';
            tr += '<input type="hidden" id="edo_showing_unit_price_exc_tax" value="' + parseFloat(e_showing_price_exc_tax).toFixed(2) + '">';
            tr += '<input type="hidden" name="unit_prices_inc_tax[]" id="edo_unit_price_inc_tax" value="' + parseFloat(e_price_inc_tax).toFixed(2) + '">';
            tr += '<input type="hidden" id="edo_showing_unit_price_inc_tax" value="' + parseFloat(e_showing_price_inc_tax).toFixed(2) + '">';
            tr += '<span id="edo_span_showing_unit_price_inc_tax" class="fw-bold">' + parseFloat(e_showing_price_inc_tax).toFixed(2) + '</span>';
            tr += '</td>';

            tr += '<td class="text-center">';
            tr += '<span id="edo_span_price_type">' + e_all_price_type + showPrAmount + '</span>';
            tr += '<input type="hidden" name="price_types[]" id="edo_price_type" value="' + e_all_price_type + '">';
            tr += '<input type="hidden" name="pr_amounts[]" id="edo_pr_amount" value="' + parseFloat(e_pr_amount).toFixed(2) + '">';
            tr += '</td>';

            tr += '<td class="text text-center">';
            tr += '<span id="edo_span_subtotal" class="fw-bold">' + parseFloat(e_subtotal).toFixed(2) + '</span>';
            tr += '<input type="hidden" name="subtotals[]" id="edo_subtotal" value="' + parseFloat(e_subtotal).toFixed(2) + '" tabindex="-1">';
            tr += '</td>';

            tr += '<td class="text-center">';
            tr += '<a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
            tr += '</td>';
            tr += '</tr>';

            $('#edo_do_item_list').append(tr);
            edoClearEditItemFileds();
            edoCalculateTotalAmount();
            $('#edo_add_item').html('Add');
        } else {

            var tr = $('#' + uniqueId).closest('tr');

            tr.find('#edo_item_name').val(e_item_name);
            tr.find('#edo_product_id').val(e_product_id);
            tr.find('#edo_variant_id').val(e_variant_id);
            tr.find('#edo_tax_ac_id').val(e_tax_ac_id);
            tr.find('#edo_tax_type').val(e_tax_type);
            tr.find('#edo_unit_tax_percent').val(parseFloat(e_tax_percent).toFixed(2));
            tr.find('#edo_unit_tax_amount').val(parseFloat(e_tax_amount).toFixed(2));
            tr.find('#edo_showing_unit_tax_amount').val(parseFloat(e_showing_tax_amount).toFixed(2));
            tr.find('#edo_unit_discount_type').val(e_discount_type);
            tr.find('#edo_unit_discount').val(parseFloat(e_discount).toFixed(2));
            tr.find('#edo_showing_unit_discount').val(parseFloat(e_showing_discount).toFixed(2));
            tr.find('#edo_unit_discount_amount').val(parseFloat(e_discount_amount).toFixed(2));
            tr.find('#edo_showing_unit_discount_amount').val(parseFloat(e_showing_discount_amount).toFixed(2));
            tr.find('#edo_unit_cost_inc_tax').val(parseFloat(e_unit_cost_inc_tax).toFixed(2));
            tr.find('#edo_showing_unit_cost_inc_tax').val(parseFloat(e_showing_unit_cost_inc_tax).toFixed(2));
            tr.find('#edo_current_stock').val(parseFloat(e_stock_quantity).toFixed(2));
            tr.find('#edo_quantity').val(parseFloat(e_quantity).toFixed(2));
            tr.find('#edo_showing_quantity').val(parseFloat(e_showing_quantity).toFixed(2));
            tr.find('#edo_span_showing_quantity').html(parseFloat(e_showing_quantity).toFixed(2));
            tr.find('#edo_unit_id').val(e_unit_id);
            tr.find('#edo_span_unit').html(e_unit_name);
            tr.find('#edo_base_unit_name').val(e_base_unit_name);
            tr.find('#edo_price_type').val(e_all_price_type);
            tr.find('#edo_span_price_type').html(e_all_price_type + showPrAmount);
            tr.find('#edo_pr_amount').val(parseFloat(e_pr_amount).toFixed(2));
            tr.find('#edo_unit_price_exc_tax').val(parseFloat(e_price_exc_tax).toFixed(2));
            tr.find('#edo_showing_unit_price_exc_tax').val(parseFloat(e_showing_price_exc_tax).toFixed(2));
            tr.find('#edo_unit_price_inc_tax').val(parseFloat(e_price_inc_tax).toFixed(2));
            tr.find('#edo_showing_unit_price_inc_tax').val(parseFloat(e_showing_price_inc_tax).toFixed(2));
            tr.find('#edo_span_showing_unit_price_inc_tax').html(parseFloat(e_showing_price_inc_tax).toFixed(2));
            tr.find('#edo_subtotal').val(parseFloat(e_subtotal).toFixed(2));
            tr.find('#edo_span_subtotal').html(parseFloat(e_subtotal).toFixed(2));
            tr.find('#edo_is_show_emi_on_pos').val(e_is_show_emi_on_pos);

            edoClearEditItemFileds();
            edoCalculateTotalAmount();
            $('#edo_add_item').html('Add');
        }
    });

    $(document).on('click', '#edo_select_item', function(e) {

        var tr = $(this);
        var item_name = tr.find('#edo_item_name').val();
        var product_id = tr.find('#edo_product_id').val();
        var variant_id = tr.find('#edo_variant_id').val();
        var tax_ac_id = tr.find('#edo_tax_ac_id').val();
        var tax_type = tr.find('#edo_tax_type').val();
        var unit_tax_amount = tr.find('#edo_unit_tax_amount').val();
        var showing_unit_tax_amount = tr.find('#edo_showing_unit_tax_amount').val();
        var unit_discount_type = tr.find('#edo_unit_discount_type').val();
        var unit_discount = tr.find('#edo_unit_discount').val();
        var showing_unit_discount = tr.find('#edo_showing_unit_discount').val();
        var unit_discount_amount = tr.find('#edo_unit_discount_amount').val();
        var showing_unit_discount_amount = tr.find('#edo_showing_unit_discount_amount').val();
        var unit_cost_inc_tax = tr.find('#edo_unit_cost_inc_tax').val();
        var showing_unit_cost_inc_tax = tr.find('#edo_showing_unit_cost_inc_tax').val();
        var current_stock = tr.find('#edo_current_stock').val();
        var quantity = tr.find('#edo_quantity').val();
        var showing_quantity = tr.find('#edo_showing_quantity').val();
        var base_unit_name = tr.find('#edo_base_unit_name').val();
        var unit_id = tr.find('#edo_unit_id').val();
        var unit_price_exc_tax = tr.find('#edo_unit_price_exc_tax').val();
        var showing_unit_price_exc_tax = tr.find('#edo_showing_unit_price_exc_tax').val();
        var pr_amount = tr.find('#edo_pr_amount').val() ? tr.find('#edo_pr_amount').val() : 0;
        var unit_price_inc_tax = tr.find('#edo_unit_price_inc_tax').val();
        var showing_unit_price_inc_tax = tr.find('#edo_showing_unit_price_inc_tax').val();
        var subtotal = tr.find('#edo_subtotal').val();
        var is_show_emi_on_pos = tr.find('#edo_is_show_emi_on_pos').val();

        $('#e_unit_id').empty();
        if (Array.isArray(itemUnitsArray[product_id]) && itemUnitsArray[product_id].length > 0) {

            itemUnitsArray[product_id].forEach(function(unit) {

                $('#e_unit_id').append(
                    '<option ' + (unit_id == unit.unit_id ? 'selected' : '') +
                    ' value="' + unit.unit_id + '" data-is_base_unit="' + unit.is_base_unit +
                    '" data-unit_name="' + unit.unit_name + '" data-base_unit_multiplier="' + unit
                    .base_unit_multiplier + '">' + unit.unit_name + unit.multiplier_details +
                    '</option>'
                );
            });
        }

        $('#search_product').val(item_name);
        $('#e_item_name').val(item_name);
        $('#e_product_id').val(product_id);
        $('#e_variant_id').val(variant_id);
        $('#e_quantity').val(parseFloat(quantity).toFixed(2));
        $('#e_showing_quantity').val(parseFloat(showing_quantity).toFixed(2)).focus().select();
        $('#e_pr_amount').val(pr_amount);
        $('#e_price_exc_tax').val(parseFloat(unit_price_exc_tax).toFixed(2));
        $('#e_showing_price_exc_tax').val(parseFloat(showing_unit_price_exc_tax).toFixed(2));
        $('#e_discount_type').val(unit_discount_type);
        $('#e_discount').val(parseFloat(unit_discount).toFixed(2));
        $('#e_showing_discount').val(parseFloat(showing_unit_discount).toFixed(2));
        $('#e_discount_amount').val(parseFloat(unit_discount_amount).toFixed(2));
        $('#e_showing_discount_amount').val(parseFloat(showing_unit_discount_amount).toFixed(2));
        $('#e_tax_ac_id').val(tax_ac_id);
        $('#e_tax_amount').val(parseFloat(unit_tax_amount).toFixed(2));
        $('#e_showing_tax_amount').val(parseFloat(showing_unit_tax_amount).toFixed(2));
        $('#e_tax_type').val(tax_type);
        $('#e_price_inc_tax').val(parseFloat(unit_price_inc_tax).toFixed(2));
        $('#e_showing_price_inc_tax').val(parseFloat(showing_unit_price_inc_tax).toFixed(2));
        $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));
        $('#e_unit_cost_inc_tax').val(parseFloat(unit_cost_inc_tax).toFixed(2));
        $('#e_showing_unit_cost_inc_tax').val(showing_unit_cost_inc_tax);
        $('#e_stock_quantity').val(current_stock);
        $('#e_is_show_emi_on_pos').val(is_show_emi_on_pos);
        $('#e_base_unit_price_exc_tax').val(unit_price_exc_tax);
        $('#edo_add_item').html('Edit');
    });

    function edoCalculateEditOrAddAmount() {

        var base_unit_multiplier = $('#e_unit_id').find('option:selected').data('base_unit_multiplier');
        var is_base_unit = $('#e_unit_id').find('option:selected').data('is_base_unit');
        var e_showing_quantity = $('#e_showing_quantity').val() ? $('#e_showing_quantity').val() : 0;
        var e_base_unit_price_exc_tax = $('#e_base_unit_price_exc_tax').val() ? $('#e_base_unit_price_exc_tax').val() : 0;
        var e_showing_price_exc_tax = $('#e_showing_price_exc_tax').val() ? $('#e_showing_price_exc_tax').val() : 0.00;
        var e_tax_percent = $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') ? $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') : 0.00;
        var e_tax_type = $('#e_tax_type').val();
        var e_pr_amount = $('#e_pr_amount').val() ? $('#e_pr_amount').val() : 0;
        var __pr_amount = $('#all_price_type').val() == 'PR' ? parseFloat(e_pr_amount) : 0;
        var e_discount_type = $('#e_discount_type').val();
        var e_showing_discount = $('#e_showing_discount').val() ? $('#e_showing_discount').val() : 0;
        var e_unit_cost_inc_tax = $('#e_unit_cost_inc_tax').val() ? $('#e_unit_cost_inc_tax').val() : 0;

        var quantity = roundOfValue(e_showing_quantity) * roundOfValue(base_unit_multiplier);
        $('#e_quantity').val(parseFloat(quantity).toFixed(2));

        var unitPriceExcTax = 0;
        unitPriceExcTax = roundOfValue(e_showing_price_exc_tax) / roundOfValue(base_unit_multiplier);
        $('#e_price_exc_tax').val(roundOfValue(unitPriceExcTax));
        $('#e_base_unit_price_exc_tax').val(roundOfValue(unitPriceExcTax));

        var showingUnitCostIncTax = roundOfValue(e_unit_cost_inc_tax) * roundOfValue(base_unit_multiplier);
        $('#e_showing_unit_cost_inc_tax').val(parseFloat(showingUnitCostIncTax).toFixed(2));
        $('#display_unit_cost').html(parseFloat(showingUnitCostIncTax).toFixed(2));

        var showing_discount_amount = 0;
        var discount_amount = 0;
        var unit_discount = 0
        if (e_discount_type == 1) {

            showing_discount_amount = roundOfValue(e_showing_discount);
            discount_amount = roundOfValue(e_showing_discount) / roundOfValue(base_unit_multiplier);
            unit_discount = roundOfValue(e_showing_discount) / roundOfValue(base_unit_multiplier);
        } else {

            showing_discount_amount = (roundOfValue(e_showing_price_exc_tax) / 100) * roundOfValue(e_showing_discount);
            discount_amount = roundOfValue(showing_discount_amount) / roundOfValue(base_unit_multiplier);
            unit_discount = roundOfValue(e_showing_discount);
        }

        var priceWithDiscount = roundOfValue(e_showing_price_exc_tax) - roundOfValue(showing_discount_amount);

        var showing_taxAmount = roundOfValue(priceWithDiscount) / 100 * roundOfValue(e_tax_percent);
        var taxAmount = roundOfValue(showing_taxAmount) / roundOfValue(base_unit_multiplier);

        var showing_unitPriceIncTax = roundOfValue(priceWithDiscount) + roundOfValue(showing_taxAmount) + roundOfValue(__pr_amount);
        var unitPriceIncTax = roundOfValue(showing_unitPriceIncTax) / roundOfValue(base_unit_multiplier);

        if (e_tax_type == 2) {

            var inclusiveTax = 100 + roundOfValue(e_tax_percent);
            var calcTax = roundOfValue(priceWithDiscount) / roundOfValue(inclusiveTax) * 100;
            var __tax_amount = roundOfValue(priceWithDiscount) - roundOfValue(calcTax);
            showing_taxAmount = __tax_amount;
            taxAmount = showing_taxAmount / roundOfValue(base_unit_multiplier);
            showing_unitPriceIncTax = roundOfValue(priceWithDiscount) + roundOfValue(showing_taxAmount) + roundOfValue(__pr_amount);
            unitPriceIncTax = roundOfValue(showing_unitPriceIncTax) / roundOfValue(base_unit_multiplier);
        }

        $('#e_discount').val(parseFloat(roundOfValue(unit_discount)));
        $('#e_discount_amount').val(parseFloat(roundOfValue(discount_amount)));
        $('#e_showing_discount_amount').val(parseFloat(roundOfValue(showing_discount_amount)));
        $('#e_showing_tax_amount').val(parseFloat(showing_taxAmount).toFixed(2));
        $('#e_tax_amount').val(parseFloat(taxAmount).toFixed(2));
        $('#e_showing_price_inc_tax').val(parseFloat(showing_unitPriceIncTax).toFixed(2));
        $('#e_price_inc_tax').val(parseFloat(unitPriceIncTax).toFixed(2));

        var subtotal = roundOfValue(unitPriceIncTax) * roundOfValue(quantity);
        $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));
    }

    $('#e_showing_quantity').on('input keypress', function(e) {

        edoCalculateEditOrAddAmount();

        if (e.which == 13) {

            if ($(this).val() != '') {

                $('#e_unit_id').focus();
            }
        }
    });

    $('#e_unit_id').on('change keypress click', function(e) {

        var isBaseUnit = $(this).find('option:selected').data('is_base_unit');
        var baseUnitPrice = $('#e_base_unit_price_exc_tax').val() ? $('#e_base_unit_price_exc_tax').val() : 0;
        var base_unit_multiplier = $(this).find('option:selected').data('base_unit_multiplier');

        var showingPriceExcTax = roundOfValue(baseUnitPrice) * roundOfValue(base_unit_multiplier);
        $('#e_showing_price_exc_tax').val(parseFloat(showingPriceExcTax).toFixed(2));

        if (e.which == 0) {

            $('#e_showing_price_exc_tax').focus().select();
        }

        edoCalculateEditOrAddAmount();
    });

    $('#e_showing_price_exc_tax').on('input keypress', function(e) {

        edoCalculateEditOrAddAmount();

        var e_all_price_type = $('#e_all_price_type').val();

        if (e.which == 13) {

            if ($(this).val() != '') {

                if (e_all_price_type == 'PR') {

                    $('#e_pr_amount').focus().select();
                } else {

                    if ($('#e_showing_discount').is('[readonly]') == true) {

                        $('#e_tax_ac_id').focus();
                        return;
                    }

                    $('#e_showing_discount').focus().select();
                }
            }
        }
    });

    $('#e_pr_amount').on('input keypress', function(e) {

        edoCalculateEditOrAddAmount();

        if (e.which == 13) {

            if ($('#e_showing_discount').is('[readonly]') == true) {

                $('#e_tax_ac_id').focus();
                return;
            }

            $('#e_showing_discount').focus().select();
        }
    });

    $('#e_showing_discount').on('input keypress', function(e) {

        edoCalculateEditOrAddAmount();

        if (e.which == 13) {

            if ($(this).val() == '' || $(this).val() == 0) {

                $('#e_tax_ac_id').focus();
                return;
            }

            $('#e_discount_type').focus();
        }
    });

    $('#e_discount_type').on('change keypress click', function(e) {

        edoCalculateEditOrAddAmount();

        if (e.which == 0) {

            $('#e_tax_ac_id').focus();
        }
    });

    $('#e_tax_ac_id').on('change keypress click', function(e) {

        edoCalculateEditOrAddAmount();

        if (e.which == 0) {

            if ($(this).val() == '') {

                $('#edo_add_item').focus();
                return;
            }

            $('#e_tax_type').focus();
        }
    });

    $('#e_tax_type').on('change keypress click', function(e) {

        edoCalculateEditOrAddAmount();

        if (e.which == 0) {

            $('#edo_add_item').focus();
        }
    });

    // Calculate total amount functionalitie
    function edoCalculateTotalAmount() {

        var quantities = document.querySelectorAll('#edo_quantity');
        var subtotals = document.querySelectorAll('#edo_subtotal');

        // Update Total Item
        var total_item = 0;
        var total_qty = 0;
        quantities.forEach(function(qty) {

            total_qty += parseFloat(qty.value);
            total_item += 1;
        });

        $('#edo_total_qty').val(parseFloat(total_qty).toFixed(2));
        $('#edo_total_item').val(parseFloat(total_item));

        // Update Net total Amount
        var netTotalAmount = 0;
        subtotals.forEach(function(subtotal) {

            netTotalAmount += parseFloat(subtotal.value);
        });

        $('#edo_net_total_amount').val(parseFloat(netTotalAmount).toFixed(2));

        if ($('#edo_order_discount_type').val() == 2) {

            var orderDisAmount = parseFloat(netTotalAmount) / 100 * parseFloat($('#order_discount').val() ? $('#order_discount').val() : 0);
            $('#edo_order_discount_amount').val(parseFloat(orderDisAmount).toFixed(2));
        } else {

            var orderDiscount = $('#order_discount').val() ? $('#order_discount').val() : 0
            $('#edo_order_discount_amount').val(parseFloat(orderDiscount).toFixed(2));
        }

        // Calc order tax amount
        var orderDiscountAmount = $('#edo_order_discount_amount').val() ? $('#edo_order_discount_amount').val() : 0;
        var orderTaxPercent = $('#edo_order_tax_percent').val() ? $('#edo_order_tax_percent').val() : 0;
        var calcOrderTaxAmount = (parseFloat(netTotalAmount) - parseFloat(orderDiscountAmount)) / 100 * parseFloat(orderTaxPercent);
        $('#edo_order_tax_amount').val(parseFloat(calcOrderTaxAmount).toFixed(2));

        // Update Total payable Amount
        var shipmentCharge = $('#edo_shipment_charge').val() ? $('#edo_shipment_charge').val() : 0;

        var calcTotalInvoiceAmount = parseFloat(netTotalAmount) -
            parseFloat(orderDiscountAmount) +
            parseFloat(calcOrderTaxAmount) +
            parseFloat(shipmentCharge);

        $('#edo_total_invoice_amount').val(parseFloat(calcTotalInvoiceAmount).toFixed(2));
    }
    edoCalculateTotalAmount();

    function edoClearEditItemFileds() {

        $('#search_product').val('').focus();
        $('#e_item_name').val('');
        $('#e_product_id').val('');
        $('#e_variant_id').val('');
        $('#e_base_unit_name').val('');
        $('#e_quantity').val(parseFloat(0).toFixed(2));
        $('#e_showing_quantity').val(parseFloat(0).toFixed(2));
        $('#e_price_exc_tax').val(parseFloat(0).toFixed(2));
        $('#e_showing_price_exc_tax').val(parseFloat(0).toFixed(2));
        $('#e_pr_amount').val(parseFloat(0).toFixed(2));
        $('#e_discount_type').val(1);
        $('#e_showing_discount').val(parseFloat(0).toFixed(2));
        $('#e_discount_amount').val(parseFloat(0).toFixed(2));
        $('#e_showing_discount_amount').val(parseFloat(0).toFixed(2));
        $('#e_tax_ac_id').val('');
        $('#e_tax_amount').val(parseFloat(0).toFixed(2));
        $('#e_showing_tax_amount').val(parseFloat(0).toFixed(2));
        $('#e_tax_type').val(1);
        $('#e_price_inc_tax').val(parseFloat(0).toFixed(2));
        $('#e_showing_price_inc_tax').val(parseFloat(0).toFixed(2));
        $('#e_subtotal').val(parseFloat(0).toFixed(2));
        $('#e_unit_cost_inc_tax').val(0);
        $('#e_showing_unit_cost_inc_tax').val(0);
        $('#e_is_show_emi_on_pos').val('');
        $('#edo_add_item').html('Add');
    }

    // Remove product form purchase product list (Table)
    $(document).on('click', '#remove_product_btn', function(e) {
        e.preventDefault();

        $(this).closest('tr').remove();

        edoCalculateTotalAmount();

        setTimeout(function() {

            edoClearEditItemFileds();
        }, 5);
    });

    // Input order discount and clculate total amount
    $(document).on('input', '#edo_order_discount', function() {

        edoCalculateTotalAmount();
    });

    // Input order discount type and clculate total amount
    $(document).on('change', '#edo_order_discount_type', function() {

        edoCalculateTotalAmount();
    });

    // Input shipment charge and clculate total amount
    $(document).on('input', '#edo_shipment_charge', function() {

        edoCalculateTotalAmount();
    });

    // chane purchase tax and clculate total amount
    $(document).on('change', '#edo_order_tax_percent', function() {

        edoCalculateTotalAmount();
    });
</script>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.do_submit_button').prop('type', 'button');
    });

    $(document).on('click', '.do_submit_button', function() {

        $(this).prop('type', 'submit');
    });

    $('#edit_do_form').on('submit', function(e) {
        e.preventDefault();

        var totalItem = $('#edo_total_item').val();

        if (parseFloat(totalItem) == 0) {

            toastr.error('Item table is empty.', 'Some thing went wrong.');
            return;
        }

        $('.do_edit_loading_button').show();
        var url = $(this).attr('action');

        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                $('.do_edit_loading_button').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                } else {

                    getDoProducts();
                    toastr.success(data.successMsg);
                    countSalesOrdersQuotationDo();
                    $('#editDoModal').empty();
                }
            },
            error: function(err) {

                $('.do_edit_loading_button').hide();
                $('.error').html('');

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

    setInterval(function() {

        $('#search_product').removeClass('is-invalid');
    }, 500);

    setInterval(function() {

        $('#search_product').removeClass('is-valid');
    }, 1000);

    function getDoProducts() {

        var sale_id = $('#edo_sale_id').val();
        var weight_id = $('#weight_id').val() ? $('#weight_id').val() : null;
        var customer = $('#customer').val();

        var url = "{{ route('common.ajax.call.get.do.products', [':sale_id', ':weight_id']) }}";
        var route = url.replace(':sale_id', sale_id);
        route = route.replace(':weight_id', weight_id);

        $.ajax({
            url: route,
            async: true,
            type: 'get',
            success: function(data) {

                $('#sale_list').empty();
                var do_products = data.do_products;

                $.each(do_products, function(key, do_product) {

                    var variant = do_product.variant_name != null ? ' - ' + do_product.variant_name : '';
                    var tr = '';
                    tr += '<tr id="edit_product">';
                    tr += '<td class="text-start">';
                    tr += '<a href="#" id="item_name" style="color:#000;">';
                    tr += '<span class="product_name">' + do_product.product_name + variant + '</span>';
                    tr += '</a>';
                    tr += '<input type="hidden" name="sale_product_ids[]" class="sale_product_id" id="' + do_product.id + '" value="' + do_product.id + '">';
                    tr += '<input type="hidden" name="is_manage_stocks[]" id="is_manage_stock" value="' + do_product.is_manage_stock + '">';
                    tr += '<input type="hidden" name="product_ids[]" id="product_id" value="' + do_product.product_id + '">';
                    tr += '<input type="hidden" name="variant_ids[]" id="variant_id" value="' + (do_product.variant_id ? do_product.variant_id : 'noid') + '">';
                    tr += '<input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="' + do_product.tax_ac_id + '">';
                    tr += '<input type="hidden" id="tax_type" value="' + do_product.tax_type + '">';
                    tr += '<input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="' + do_product.unit_tax_percent + '">';
                    tr += '<input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="' + parseFloat(do_product.unit_tax_amount).toFixed(2) + '">';
                    tr += '<input type="hidden" name="unit_discount_types[]" value="' + do_product.unit_discount_type + '" id="unit_discount_type">';
                    tr += '<input type="hidden" name="unit_discounts[]" id="unit_discount" value="' + do_product.unit_discount + '">';
                    tr += '<input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="' + do_product.unit_discount_amount + '">';
                    tr += '<input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="' + (do_product.variant_cost_with_tax == null ? do_product.product_cost_with_tax : do_product.variant_cost_with_tax) + '">';
                    tr += '<input type="hidden" name="descriptions[]">';
                    tr += '</td>';

                    var warehouse_id = do_product.stock_warehouse_id ? do_product.stock_warehouse_id : '';

                    tr += '<td class="text-start">';
                    tr += '<input type="hidden" name="warehouse_ids[]" id="warehouse_id" value="' + warehouse_id + '">';

                    if (warehouse_id) {

                        tr += '<span id="span_stock_location_name">' + do_product.w_name + '/' + do_product.w_code + '</span>';
                    } else {

                        tr += '<span id="span_stock_location_name">' + branch_name + '</span>';
                    }

                    tr += '</td>';
                    tr += '<td>';
                    tr += '<span id="span_do_left_quantity" class="fw-bold">' + parseFloat(do_product.do_left_qty).toFixed(2) + '</span>';
                    tr += '<input type="hidden" id="do_left_quantity" value="' + parseFloat(do_product.do_left_qty).toFixed(2) + '">';
                    tr += '</td>';

                    tr += '<td>';
                    tr += '<span id="span_quantity" class="fw-bold">0.00</span>';
                    tr += '<input type="hidden" name="quantities[]" id="quantity" value="0.00">';
                    tr += '</td>';

                    tr += '<td class="text">';
                    tr += '<span id="span_unit" class="fw-bold">' + (do_product.base_unit_name != null ? do_product.base_unit_name : do_product.sale_unit_name) + '</span>';
                    tr += '<input type="hidden" name="unit_ids[]" id="unit_id" value="' + (do_product.base_unit_id != null ? do_product.base_unit_id : do_product.sale_unit_id) + '">';
                    tr += '<input type="hidden" name="price_types[]" id="price_type" value="' + do_product.price_type + '" tabindex="-1">';
                    tr += '<input type="hidden" name="pr_amounts[]" id="pr_amount" value="' + do_product.pr_amount + '" tabindex="-1">';
                    tr += '<input type="hidden" name="subtotals[]" id="subtotal" value="0.00" tabindex="-1">';
                    tr += '<input type="hidden" name="unit_prices_exc_tax[]" id="unit_price_exc_tax" value="' + parseFloat(do_product.unit_price_exc_tax).toFixed(2) + '" tabindex="-1">';
                    tr += '<input type="hidden" name="unit_prices[]" id="unit_price" value="' + parseFloat(do_product.unit_price_inc_tax).toFixed(2) + '" tabindex="-1">';
                    tr += '</td>';
                    tr += '</tr>';
                    $('#sale_list').prepend(tr);

                    calculateTotalAmount();
                    clearOrderItemProcessSection();
                    $('#editDoModal').modal('hide');
                    $('#editDoModal').empty();
                    $('#editDoModal').html('<input type="hidden" id="search_product">');
                });
            }
        });
    }

    $('#e_all_price_type').on('change', function() {

        $(this).val() == 'PR' ? $('#e_pr_amount').prop('readonly', false).attr("tabindex", "") : $('#e_pr_amount').prop('readonly', true).attr("tabindex", "-1").val(0);

        var allPriceType = $(this).val();

        var table = $('.order-item-sec');

        table.find('tbody').find('tr').each(function() {

            $(this).find('#edo_span_price_type').html(allPriceType);
            $(this).find('#edo_price_type').val(allPriceType);
        });
    });

    function roundOfValue(val) {

        return ((parseFloat(val) * 1000) / 1000);
    }
</script>
