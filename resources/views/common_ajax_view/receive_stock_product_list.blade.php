@foreach ($receiveStockProducts as $receiveStockProduct)
    @php
        $taxPercent = $receiveStockProduct?->product?->tax ? $receiveStockProduct?->product?->tax?->tax_percent : 0;

        $unitCostExcTax = $receiveStockProduct?->variant ? $receiveStockProduct?->variant?->variant_cost : $receiveStockProduct?->product?->product_cost;

        $taxAmount = ($unitCostExcTax / 100) * $taxPercent;
        $unitCostIncTax = $unitCostExcTax + $taxAmount;
        $taxType = $receiveStockProduct?->product?->tax_type;

        if ($taxType == 2) {

            $inclusiveTax = 100 + $taxPercent;
            $calcAmount = ($unitCostExcTax / $inclusiveTax) * 100;
            $taxAmount = $unitCostExcTax - $calcAmount;
            $unitCostIncTax = $unitCostExcTax + $taxAmount;
        }

        $variantName = $receiveStockProduct->variant_name ? ' -' . $receiveStockProduct->variant_name : '' ;
        $variantId = $receiveStockProduct->variant_id ? $receiveStockProduct->variant_id : 'noid';
    @endphp

    <tr id="select_item">
        <td>
            <span id="span_item_name">{{ $receiveStockProduct?->product?->name . $variantName }}</span>
            <input type="hidden" id="item_name" value="{{ $receiveStockProduct?->product?->name . $variantName }}">
            <input type="hidden" name="descriptions[]" id="description" value="{{ $receiveStockProduct->short_description }}">
            <input type="hidden" name="product_ids[]" id="product_id" value="{{ $receiveStockProduct->product_id }}">
            <input type="hidden" name="variant_ids[]" id="variant_id" value="{{ $variantId }}">
            <input type="hidden" name="purchase_product_ids[]" value="">
            <input type="hidden" id="{{ $receiveStockProduct->product_id . $variantId }}" value="{{ $receiveStockProduct->product_id . $variantId }}">
        </td>

        <td>
            @php
                $baseUnitMultiplier = $receiveStockProduct?->receiveUnit?->base_unit_multiplier ? $receiveStockProduct?->receiveUnit?->base_unit_multiplier : 1;
                $receivedQty = $receiveStockProduct->quantity / $baseUnitMultiplier;
            @endphp
            <span id="span_showing_quantity_unit" class="fw-bold">{{ $receivedQty.'/'.$receiveStockProduct?->receiveUnit?->name }}</span>
            <input type="hidden" step="any" id="showing_quantity" value="{{ $receivedQty }}">
            <input type="hidden" name="quantities[]" id="quantity" value="{{ $receiveStockProduct->quantity }}">
            <input type="hidden" name="unit_ids[]" step="any" id="unit_id" value="{{ $receiveStockProduct?->unit_id }}">
            @if (json_decode($generalSettings->purchase, true)['is_enable_lot_no'] == '1')

                <p class="p-0 m-0 fw-bold">@lang('menu.lot_no') : <span id="span_lot_number">{{ $receiveStockProduct->lot_number }}</span>
                <input type="hidden" name="lot_numbers[]" id="lot_number" value="{{ $receiveStockProduct->lot_number }}">
            @endif
        </td>

        <td>
            @php
                $__unitCostExcTax = $receiveStockProduct->poProduct ? $receiveStockProduct->poProduct->unit_cost : $unitCostIncTax;
            @endphp
            <span id="span_showing_unit_cost_exc_tax" class="fw-bold">{{ bcadd($unitCostExcTax * $baseUnitMultiplier, 0, 2) }}</span>
            <input type="hidden" name="unit_costs_exc_tax[]" id="unit_cost_exc_tax" value="{{ $unitCostExcTax }}">
            <input type="hidden" id="showing_unit_cost_exc_tax" value="{{ bcadd($unitCostExcTax * $baseUnitMultiplier, 0, 2) }}">
        </td>

        <td>
            @php
                $unit_discount = $receiveStockProduct?->poProduct ? $receiveStockProduct?->poProduct->unit_discount : 0.00;
                $unit_discount_amount = $receiveStockProduct?->poProduct ? $receiveStockProduct?->poProduct->unit_discount_amount : 0.00;
                $unit_discount_type = $receiveStockProduct?->poProduct ? $receiveStockProduct?->poProduct->unit_discount_type : 1;
            @endphp

            <span id="span_showing_discount_amount" class="fw-bold">{{ bcadd($unit_discount_amount * $baseUnitMultiplier, 0, 2) }}</span>
            <input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="{{ $unit_discount_type }}">
            <input type="hidden" name="unit_discounts[]" id="unit_discount" value="{{ $unit_discount }}">
            <input type="hidden" id="showing_unit_discount" value="{{ bcadd($unit_discount * $baseUnitMultiplier, 0, 2) }}">
            <input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="{{ $unit_discount }}">
            <input type="hidden" id="showing_unit_discount_amount" value="{{ bcadd($unit_discount_amount * $baseUnitMultiplier, 0, 2) }}">

            @php
                $subtotal = $__unitCostExcTax * $receiveStockProduct->quantity;
                $unitCostWithDiscount = $receiveStockProduct?->poProduct ? $receiveStockProduct?->poProduct?->unit_cost_with_discount : $__unitCostExcTax;
            @endphp

            <input type="hidden" name="unit_costs_with_discount[]" id="unit_cost_with_discount" value="{{ bcadd($unitCostWithDiscount, 0, 2) }}">
            <input type="hidden" id="showing_unit_cost_with_discount" value="{{ bcadd($unitCostWithDiscount * $baseUnitMultiplier, 0, 2) }}">
            <input type="hidden" name="subtotals[]" id="subtotal" value="{{ bcadd($subtotal, 0, 2) }}">
         </td>

        <td>
            @php
                $__taxPercent = $receiveStockProduct?->poProduct ? $receiveStockProduct?->poProduct?->unit_tax_percent : $taxPercent;
                $__taxAmount = $receiveStockProduct?->poProduct ? $receiveStockProduct?->poProduct?->unit_tax_amount : $taxAmount;
                $__taxType = $receiveStockProduct?->poProduct ? $receiveStockProduct?->poProduct?->tax_type : $taxType;
                $__taxAcId = $receiveStockProduct->poProduct ? $receiveStockProduct->poProduct->tax_ac_id : $receiveStockProduct?->product?->tax_ac_id;
            @endphp
            <span id="span_tax_percent" class="fw-bold">{{ $__taxPercent.'%' }}</span>
            <input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="{{ $__taxAcId }}">
            <input type="hidden" name="tax_types[]" id="tax_type" value="{{ $__taxType }}">
            <input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="{{ $__taxPercent }}">
            <input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="{{ bcadd($__taxAmount, 0, 2) }}">
            <input type="hidden" id="showing_unit_tax_amount" value="{{ bcadd($__taxAmount * $baseUnitMultiplier, 0, 2) }}">
        </td>

        <td>
            @php
                $__unitCostIncTax = 0;
                if ($receiveStockProduct->poProduct) {

                    $__unitCostIncTax = $receiveStockProduct->unit_cost_inc_tax;
                }else {

                    $__unitCostIncTax = $unitCostIncTax;
                }
            @endphp
            <span id="span_showing_unit_cost_inc_tax" class="fw-bold">{{ bcadd($__unitCostIncTax * $baseUnitMultiplier, 0, 2) }}</span>
            <input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="{{ bcadd($__unitCostIncTax, 0, 2) }}">
            <input type="hidden" id="showing_unit_cost_inc_tax" value="{{ bcadd($__unitCostIncTax * $baseUnitMultiplier, 0, 2) }}">
            <input type="hidden" name="net_unit_costs[]" id="net_unit_cost" value="{{ bcadd($__unitCostIncTax, 0, 2) }}">
        </td>

        <td>
            @php
                $linetotal = $__unitCostIncTax * $receiveStockProduct->quantity;
            @endphp
            <span id="span_linetotal" class="fw-bold">{{ bcadd($linetotal, 0, 2) }}</span>
            <input type="hidden" name="linetotals[]" id="linetotal" value="{{ bcadd($linetotal, 0, 2) }}">
        </td>

        @if (json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1')
            <td>
                @php
                    $productProfitMargin = $receiveStockProduct?->variant ? $receiveStockProduct?->variant?->variant_profit : $receiveStockProduct?->product?->profit;

                    $poProductProfitMargin = $receiveStockProduct?->poProduct ? $receiveStockProduct?->poProduct?->profit_margin : null;
                    $profitMargin = $poProductProfitMargin ? $poProductProfitMargin : $productProfitMargin;
                @endphp

                <span id="span_profit" class="fw-bold">{{ $profitMargin }}</span>
                <input type="hidden" name="profits[]" id="profit" value="{{ $profitMargin }}">
            </td>

            <td>
                @php
                    $productPrice = $receiveStockProduct?->variant ? $receiveStockProduct?->variant?->variant_price : $receiveStockProduct?->product?->product_price;
                    $poProductPrice = $receiveStockProduct?->poProduct ? $receiveStockProduct?->poProduct?->selling_price : null;
                    $sellingPrice = $poProductPrice ? $poProductPrice : $productPrice;
                @endphp

                <span id="span_showing_selling_price" class="fw-bold">{{ bcadd($sellingPrice * $baseUnitMultiplier, 0, 2) }}</span>
                <input type="hidden" name="selling_prices[]" id="selling_price" value="{{ bcadd($sellingPrice, 0, 2) }}">
                <input type="hidden" id="showing_selling_price" value="{{ bcadd($sellingPrice * $baseUnitMultiplier, 0, 2) }}">
            </td>
        @endif

        <td class="text-start"><i class="fas fa-trash-alt text-secondary"></i></td>
    </tr>
@endforeach
