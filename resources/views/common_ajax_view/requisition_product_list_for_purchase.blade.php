@foreach ($requisitionProducts as $rqProduct)

    @php
        $taxPercent = $rqProduct?->product->tax ? $rqProduct->product->tax->tax_percent : 0;

        $unitCostExcTax = $rqProduct?->variant ? $rqProduct?->variant->variant_cost : $rqProduct?->product?->product_cost;

        $taxAmount = ($unitCostExcTax / 100) * $taxPercent;

        $unitCostIncTax = $unitCostExcTax + $taxAmount;

        if ($rqProduct?->product?->tax_type == 2) {
            $inclusiveTax = 100 + $taxPercent;
            $calcAmount = ($unitCostExcTax / $inclusiveTax) * 100;
            $taxAmount = $unitCostExcTax - $calcAmount;
            $unitCostIncTax = $unitCostExcTax + $taxAmount;
        }

        $variantName = $rqProduct?->variant ? ' -' . $rqProduct?->variant->variant_name : '' ;
        $variant_id = $rqProduct?->variant_id ? $rqProduct?->variant_id : 'noid';
    @endphp

    <tr id="select_item">
        <td>
            <span id="span_item_name">{{ $rqProduct?->product?->name . $variantName }}</span>
            <input type="hidden" id="item_name" value="{{ $rqProduct?->product?->name . $variantName }}">
            <input type="hidden" name="descriptions[]" id="description" value="">
            <input type="hidden" name="product_ids[]" id="product_id" value="{{ $rqProduct->product_id }}">
            <input type="hidden" name="variant_ids[]" id="variant_id" value="{{ $variant_id }}">
            <input type="hidden" name="purchase_product_ids[]" value="">
            <input type="hidden" id="{{ $rqProduct->product_id . $variant_id }}" value="{{ $rqProduct->product_id . $variant_id }}">
        </td>

        <td>
            @php
                $baseUnitMultiplier = $rqProduct?->requisitionUnit?->base_unit_multiplier ? $rqProduct?->requisitionUnit?->base_unit_multiplier : 1;
                $requestedQty = $rqProduct->left_qty / $baseUnitMultiplier;
            @endphp
            <span id="span_showing_quantity_unit" class="fw-bold">{{ $requestedQty.'/'.$rqProduct?->requisitionUnit?->name }}</span>
            <input type="hidden" step="any" id="showing_quantity" value="{{ $requestedQty }}">
            <input type="hidden" name="quantities[]" id="quantity" value="{{ $rqProduct->left_qty }}">
            <input type="hidden" name="unit_ids[]" step="any" id="unit_id" value="{{ $rqProduct?->unit_id }}">
            @if (json_decode($generalSettings->purchase, true)['is_enable_lot_no'] == '1')

                <p class="p-0 m-0 fw-bold">@lang('menu.lot_no') : <span id="span_lot_number"></span>
                <input type="hidden" name="lot_numbers[]" id="lot_number" value="">
            @endif
        </td>

        <td>
            @php
                $showingUnitCostExcTax = $unitCostExcTax * $baseUnitMultiplier;
            @endphp
            <span id="span_showing_unit_cost_exc_tax" class="fw-bold">{{ bcadd($showingUnitCostExcTax, 0, 2) }}</span>
            <input type="hidden" name="unit_costs_exc_tax[]" id="unit_cost_exc_tax" value="{{ $unitCostExcTax }}">
            <input type="hidden" id="showing_unit_cost_exc_tax" value="{{ bcadd($showingUnitCostExcTax, 0, 2) }}">
        </td>

        <td>
            <span id="span_showing_discount_amount" class="fw-bold">0.00</span>
            <input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="1">
            <input type="hidden" name="unit_discounts[]" id="unit_discount" value="0">
            <input type="hidden" id="showing_unit_discount" value="0">
            <input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="0">
            <input type="hidden" id="showing_unit_discount_amount" value="0">
            <input type="hidden" name="subtotals[]" id="subtotal" value="0">
            <input type="hidden" name="unit_costs_with_discount[]" id="unit_cost_with_discount" value="0">
            <input type="hidden" id="showing_unit_cost_with_discount" value="0">
         </td>

         <td>
            <span id="span_tax_percent" class="fw-bold">{{ $taxPercent.'%' }}</span>
            <input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="{{ $rqProduct?->product->tax_ac_id ? $rqProduct?->product->tax_ac_id : '' }}">
            <input type="hidden" name="tax_types[]" id="tax_type" value="{{ $rqProduct?->product?->tax_type }}">
            <input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="{{ $taxPercent }}">
            <input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="{{ bcadd($taxAmount, 0, 2) }}">
            <input type="hidden" id="showing_unit_tax_amount" value="{{ bcadd($taxAmount * $baseUnitMultiplier, 0, 2) }}">
        </td>

        <td>
            <span id="span_showing_unit_cost_inc_tax" class="fw-bold">{{ bcadd($unitCostIncTax * $baseUnitMultiplier, 0, 2) }}</span>
            <input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="{{ bcadd($unitCostIncTax, 0, 2) }}">
            <input type="hidden" id="showing_unit_cost_inc_tax" value="{{ bcadd($unitCostIncTax * $baseUnitMultiplier, 0, 2) }}">
            <input type="hidden" name="net_unit_costs[]" id="net_unit_cost" value="{{ bcadd($unitCostIncTax, 0, 2) }}">
        </td>

        @php
            $linetotal = $unitCostIncTax * $rqProduct->left_qty;
        @endphp

        <td>
            <span id="span_linetotal" class="fw-bold">{{ bcadd($linetotal, 0, 2) }}</span>
            <input type="hidden" name="linetotals[]" id="linetotal" value="{{ bcadd($linetotal, 0, 2) }}">
        </td>

        @if (json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1')

            <td>
                <span id="span_profit" class="fw-bold">{{ $rqProduct->variant == null ? $rqProduct->product->profit : $rqProduct?->variant->variant_profit }}</span>
                <input type="hidden" name="profits[]" id="profit" value="{{ $rqProduct->variant ? $rqProduct->variant->variant->profit : $rqProduct?->product->profit }}">
            </td>

            <td>
                @php
                    $unitPriceExcTax = $rqProduct->variant ? $rqProduct?->variant->variant_price : $rqProduct?->product->product_price;
                @endphp
                <span id="span_showing_selling_price" class="fw-bold">{{ bcadd($unitPriceExcTax * $baseUnitMultiplier, 0, 2) }}</span>
                <input type="hidden" name="selling_prices[]" id="selling_price" value="{{ bcadd($unitPriceExcTax, 0, 2) }}">
                <input type="hidden" id="showing_selling_price" value="{{ bcadd($unitPriceExcTax * $baseUnitMultiplier, 0, 2) }}">
            </td>
        @endif

        <td class="text-start">
            <a href="#" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger"></i></a>
        </td>
    </tr>
@endforeach
