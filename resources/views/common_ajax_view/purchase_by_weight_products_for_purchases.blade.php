@foreach ($purchaseByScaleProducts as $psProduct)

    @php
        $taxPercent = $psProduct?->prodcut?->tax ? $psProduct?->prodcut?->tax?->tax_percent : 0;

        $unitCostExcTax = $psProduct?->product->product_cost;

        $taxAmount = ($unitCostExcTax / 100) * $taxPercent;

        $unitCostIncTax = $unitCostExcTax + $taxAmount;

        if ($psProduct->tax_type == 2) {

            $inclusiveTax = 100 + $taxPercent;
            $calcAmount = ($unitCostExcTax / $inclusiveTax) * 100;
            $taxAmount = $unitCostExcTax - $calcAmount;
            $unitCostIncTax = $unitCostExcTax + $taxAmount;
        }
    @endphp

    <tr id="select_item">
        <td>
            <span id="span_item_name">{{ $psProduct?->product->name }}</span>
            <input type="hidden" id="item_name" value="{{ $psProduct?->product?->name }}">
            <input type="hidden" name="descriptions[]" id="description" value="">
            <input type="hidden" name="product_ids[]" id="product_id" value="{{ $psProduct->product_id }}">
            <input type="hidden" name="variant_ids[]" id="variant_id" value="noid">
            <input type="hidden" name="purchase_product_ids[]">
            <input type="hidden" id="{{ $psProduct->product_id . 'noid' }}" value="{{ $psProduct->product_id . 'noid' }}">
        </td>


        <td>
            <span id="span_showing_quantity_unit" class="fw-bold">{{ $psProduct->net_weight.'/'.$psProduct?->product?->unit?->name }}</span>
            <input type="hidden" step="any" id="showing_quantity" value="{{ $psProduct->net_weight }}">
            <input type="hidden" name="quantities[]" id="quantity" value="{{ $psProduct->net_weight }}">
            <input type="hidden" name="unit_ids[]" step="any" id="unit_id" value="{{ $psProduct?->product?->unit_id }}">
            @if (json_decode($generalSettings->purchase, true)['is_enable_lot_no'] == '1')

                <p class="p-0 m-0 fw-bold">@lang('menu.lot_no') : <span id="span_lot_number"></span>
                <input type="hidden" name="lot_numbers[]" id="lot_number" value="">
            @endif
        </td>

        <td>
            <span id="span_showing_unit_cost_exc_tax" class="fw-bold">{{ bcadd($unitCostExcTax, 0, 2) }}</span>
            <input type="hidden" name="unit_costs_exc_tax[]" id="unit_cost_exc_tax" value="{{ bcadd($unitCostExcTax, 0, 2) }}">
            <input type="hidden" id="showing_unit_cost_exc_tax" value="{{ bcadd($unitCostExcTax, 0, 2) }}">
        </td>


        <td>
            <span id="span_showing_discount_amount" class="fw-bold">0.00</span>
            <input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="1">
            <input type="hidden" name="unit_discounts[]" id="unit_discount" value="0.00">
            <input type="hidden" id="showing_unit_discount" value="0.00">
            <input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="0.00">
            <input type="hidden" id="showing_unit_discount_amount" value="0.00">

            @php
                $subtotal = $unitCostExcTax * $psProduct->net_weight;
            @endphp

            <input type="hidden" name="unit_costs_with_discount[]" id="unit_cost_with_discount" value="{{ bcadd($unitCostExcTax, 0, 2) }}">
            <input type="hidden" id="showing_unit_cost_with_discount" value="{{ bcadd($unitCostExcTax, 0, 2) }}">
            <input type="hidden" name="subtotals[]" id="subtotal" value="{{ bcadd($subtotal, 0, 2) }}">
         </td>

         <td>
            <span id="span_tax_percent" class="fw-bold">{{ $taxPercent.'%' }}</span>
            <input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="{{ $psProduct?->product?->tax_ac_id }}">
            <input type="hidden" name="tax_types[]" id="tax_type" value="{{ $psProduct?->product?->tax_type }}">
            <input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="{{ $taxPercent }}">
            <input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="{{ bcadd($taxAmount, 0, 2) }}">
            <input type="hidden" id="showing_unit_tax_amount" value="{{ bcadd($taxAmount, 0, 2) }}">
        </td>

        <td>
            <span id="span_showing_unit_cost_inc_tax" class="fw-bold">{{ bcadd($unitCostIncTax, 0, 2) }}</span>
            <input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="{{ bcadd($unitCostIncTax, 0, 2) }}">
            <input type="hidden" id="showing_unit_cost_inc_tax" value="{{ bcadd($unitCostIncTax, 0, 2) }}">
            <input type="hidden" name="net_unit_costs[]" id="net_unit_cost" value="{{ bcadd($unitCostIncTax, 0, 2) }}">
        </td>

        <td>
            @php
                $linetotal = $unitCostIncTax * $psProduct->net_weight;
            @endphp
            <span id="span_linetotal" class="fw-bold">{{ bcadd($linetotal, 0, 2) }}</span>
            <input type="hidden" name="linetotals[]" id="linetotal" value="{{ bcadd($linetotal, 0, 2) }}">
        </td>

        @if (json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1')
            <td>
                <span id="span_profit" class="fw-bold">{{ $psProduct?->product?->profit }}</span>
                <input type="hidden" name="profits[]" id="profit" value="{{ $psProduct?->product?->profit }}">
            </td>

            <td>
                <span id="span_showing_selling_price" class="fw-bold">{{ $psProduct?->product?->product_price }}</span>
                <input type="hidden" name="selling_prices[]" id="selling_price" value="{{ bcadd($psProduct?->product?->product_price, 0, 2) }}">
                <input type="hidden" id="showing_selling_price" value="{{ bcadd($psProduct?->product?->product_price, 0, 2) }}">
            </td>
        @endif

        <td class="text-start"><i class="fas fa-trash-alt text-secondary"></i></td>
    </tr>
@endforeach
