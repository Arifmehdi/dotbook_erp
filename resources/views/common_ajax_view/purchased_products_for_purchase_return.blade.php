@foreach ($purchaseProducts as $purchaseProduct)
    @php
        $variantName = $purchaseProduct?->variant ? ' - '. $purchaseProduct?->variant?->variant_name : '';
        $variantId = $purchaseProduct->variant_id ? ' - ' . $purchase_product->variant_id : 'noid';
        $baseUnitMultiplier = $purchaseProduct?->purchaseUnit?->base_unit_multiplier ? $purchaseProduct?->purchaseUnit?->base_unit_multiplier : 1;
        $purchasedQty = $purchaseProduct->quantity / $baseUnitMultiplier;
    @endphp

    <tr id="select_item">
        <td class="text-start">
            <span class="product_name">{{ $purchaseProduct?->product?->name.$variantName }}</span>
            <input type="hidden" id="item_name" value="{{ $purchaseProduct?->product?->name.$variantName }}">
            <input type="hidden" name="product_ids[]" id="product_id" value="{{ $purchaseProduct->product_id }}">
            <input type="hidden" name="variant_ids[]" id="variant_id" value="{{ $variantId }}">
            <input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="{{ $purchaseProduct->tax_ac_id != null ? $purchaseProduct->tax_ac_id : '' }}">
            <input type="hidden" name="tax_types[]" id="tax_type" value="{{ $purchaseProduct->tax_type }}">
            <input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="{{ $purchaseProduct->unit_tax_percent }}">
            <input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="{{ $purchaseProduct->unit_tax_amount }}">
            <input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="{{ $purchaseProduct->unit_discount_type }}">
            @php
                $showingUnitDiscount = $purchaseProduct->unit_discount_type == 1 ? $purchaseProduct->unit_discount * $baseUnitMultiplier : $purchaseProduct->unit_discount;
            @endphp
            <input type="hidden" name="unit_discounts[]" id="unit_discount" value="{{ $purchaseProduct->unit_discount }}">
            <input type="hidden" id="showing_unit_discount" value="{{ $showingUnitDiscount }}">
            <input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="{{ $purchaseProduct->unit_discount_amount }}">
            <input type="hidden" id="showing_unit_discount_amount" value="{{ $purchaseProduct->unit_discount_amount * $baseUnitMultiplier }}">
            <input type="hidden" name="purchase_product_ids[]" value="{{ $purchaseProduct->id }}">
            <input type="hidden" class="unique_id" id="{{ $purchaseProduct->product_id.$variantId.$purchaseProduct?->purchase?->warehouse_id }}" value="{{ $purchaseProduct->product_id.$variantId.$purchaseProduct?->purchase?->warehouse_id }}">
        </td>

        <td>
            <span id="span_showing_unit_cost_inc_tax" class="fw-bold">{{ bcadd($purchaseProduct->net_unit_cost * $baseUnitMultiplier, 0, 2) }}</span>
            <input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="{{ $purchaseProduct->net_unit_cost }}">
            <input type="hidden" id="showing_unit_cost_inc_tax" value="{{ bcadd($purchaseProduct->net_unit_cost * $baseUnitMultiplier, 0, 2) }}">
            <input type="hidden" name="unit_costs_exc_tax[]" id="unit_cost_exc_tax" value="{{ $purchaseProduct->unit_cost }}">
            <input type="hidden" id="showing_unit_cost_exc_tax" value="{{ bcadd($purchaseProduct->unit_cost * $baseUnitMultiplier, 0, 2) }}">
        </td>

        <td>
            <span id="span_purchased_qty" class="fw-bold">
                {{ bcadd($purchaseProduct->quantity / $baseUnitMultiplier, 0, 2) }}/{{ $purchaseProduct?->purchaseUnit?->name }}
            </span>
            <input type="hidden" name="purchased_quantities[]" value="{{ bcadd($purchaseProduct->quantity / $baseUnitMultiplier, 0, 2) }}">
        </td>

        @php
            $stockLocationName = '';
            if ($purchaseProduct?->purchase?->warehouse) {

                $stockLocationName = $purchaseProduct?->purchase?->warehouse->warehouse_name.'/'.$purchaseProduct?->purchase?->warehouse->warehouse_code;
            } else {

                $stockLocationName = json_decode($generalSettings->business, true)['shop_name'];
            }
        @endphp

        <td class="text-start">
            <input type="hidden" name="warehouse_ids[]" id="warehouse_id" value="{{ $purchaseProduct?->purchase?->warehouse_id }}">
            <input type="hidden" id="current_warehouse_id" value="{{ $purchaseProduct?->purchase?->warehouse_id }}">
            <span id="stock_location_name">{{ $stockLocationName }}</span>
        </td>

        <td>
            <span id="span_showing_return_quantity" class="fw-bold">0.00</span>
            <input type="hidden" name="return_quantities[]" id="return_quantity" value="0.00">
            <input type="hidden" id="showing_return_quantity" value="0.00">
            <input type="hidden" id="current_return_qty" value="0.00">
        </td>

        <td class="text">
            <span id="span_unit" class="fw-bold">{{ $purchaseProduct?->purchaseUnit?->name }}</span>
            <input type="hidden" name="unit_ids[]" id="unit_id" value="{{ $purchaseProduct->unit_id }}">
        </td>

        <td class="text text-center">
            <span id="span_subtotal" class="fw-bold">0.00</span>
            <input type="hidden" name="subtotals[]" id="subtotal" value="0.00" tabindex="-1">
        </td>

        <td class="text-center">
            <a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>
        </td>
    </tr>
@endforeach
