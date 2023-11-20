@foreach ($purchaseByScaleWeights as $ps_weight)
    <tr id="edit_product" style="cursor: pointer;">
        <td class="fw-bold">{{ $loop->index + 1 }}</td>
        <td class="text-start">
            <a href="#" class="text-dark" id="item_name">
                <span class="product_name">{{ $ps_weight->product_name ? $ps_weight->product_name .($ps_weight->variant_name ? ' - ' . $ps_weight->variant_name : '') : 'Not-Available' }}</span>
            </a>
            <input name="purchase_by_scale_weight_ids[]" type="hidden" id="purchase_by_scale_weight_id" value="{{ $ps_weight->id }}">
            <input name="product_ids[]" type="hidden" id="product_id" value="{{ $ps_weight->product_id }}">
            <input name="variant_ids[]" type="hidden" id="variant_id" value="{{ $ps_weight->variant_id ? $ps_weight->variant_id : '' }}">
        </td>

        <td>
            <input readonly type="text" name="differ_weights[]" step="any" class="form-control text-center fw-bold" id="differ_weight" value="{{ $ps_weight->differ_weight }}" tabindex="-1">
        </td>

        <td>
            <input readonly type="number" step="any" name="wastes[]" step="any" class="form-control text-center fw-bold" id="wast" value="{{ $ps_weight->wast }}">
        </td>

        <td>
            <input readonly type="text" name="net_weights[]" step="any" class="form-control text-center fw-bold" id="net_weight" value="{{ $ps_weight->net_weight }}" tabindex="-1">
        </td>

        <td class="text">
            <b><span class="span_unit">{{ $ps_weight->unit }}</span></b>
        </td>

        <td class="text">
            <b><span class="span_remarks">{{ $ps_weight->remarks }}</span></b>
        </td>
    </tr>
@endforeach

