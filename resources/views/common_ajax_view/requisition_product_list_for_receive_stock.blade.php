@foreach ($requisitionProducts as $rqProduct)
    @php
        $variantName = $rqProduct?->variant ? ' -' . $rqProduct?->variant?->variant_name : '' ;
        $variantId = $rqProduct->variant_id ? $rqProduct->variant_id : 'noid';
    @endphp

    <tr id="select_item">
        <td>
            <span id="span_item_name">{{ $rqProduct?->product?->name . $variantName }}</span>
            <input type="hidden" id="item_name" value="{{ $rqProduct?->product?->name . $variantName }}">
            <input type="hidden" name="short_descriptions[]" id="description" value="">
            <input type="hidden" name="product_ids[]" id="product_id" value="{{ $rqProduct->product_id }}">
            <input type="hidden" name="variant_ids[]" id="variant_id" value="{{ $variantId }}">
            <input type="hidden" name="receive_stock_product_ids[]" value="">
            <input type="hidden" name="purchase_order_product_ids[]" value="">
            <input type="hidden" id="{{ $rqProduct->product_id . $variantId }}" value="{{ $rqProduct->product_id . $variantId }}">
        </td>

        @php
            $baseUnitMultiplier = $rqProduct?->requisitionUnit?->base_unit_multiplier ? $rqProduct?->requisitionUnit?->base_unit_multiplier : 1;
            $leftQty = $rqProduct->left_qty / $baseUnitMultiplier;
        @endphp

        <td>
            <span id="span_showing_quantity" class="fw-bold">{{ bcadd($leftQty, 0, 2) }}</span>
            <input type="hidden" id="showing_quantity" value="{{ bcadd($leftQty, 0, 2) }}">
            <input type="hidden" name="quantities[]" class="form-control fw-bold" id="quantity" value="{{ $rqProduct->left_qty }}">
        </td>

        <td>
            <span id="span_showing_unit" class="fw-bold">{{ $rqProduct?->requisitionUnit?->name }}</span>
            <input type="hidden" name="unit_ids[]" id="unit_id" value="{{ $rqProduct->unit_id }}">
        </td>

        @if (json_decode($generalSettings->purchase, true)['is_enable_lot_no'] == '1')
            <td>
                <span id="span_showing_lot_number" class="fw-bold"></span>
                <input type="hidden" name="lot_numbers[]" id="lot_number" value="">
            </td>
        @endif

        <td class="text-start">
            <a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger"></i></a>
        </td>
    </tr>
@endforeach
