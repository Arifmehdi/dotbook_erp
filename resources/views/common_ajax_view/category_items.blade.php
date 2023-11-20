@foreach ($items as $item)
    <tr class="{{ $item->category_id.$item->subcategory_id }}">
        @if ($item->v_id)
        
            <td>
                {{ $item->p_name.' - '.$item->v_name }}
                <input type="hidden" name="product_ids[]" value="{{ $item->p_id }}">
                <input type="hidden" name="variant_ids[]" value="{{ $item->v_id }}">
            </td>

            <td>
                <p>{{ $item->cate_name }}</p>
                <p>---{{ $item->sub_cate_name }}</p>
            </td>

            <td>
                {{ $item->v_cost }}
                <input type="hidden" id="unit_cost" value="{{ $item->v_cost }}">
            </td>

            <td>
                {{ $item->v_price }}
                <input type="hidden" name="current_prices[]" value="{{ $item->v_price }}">
            </td>

            <td>
                <input type="number" step="any" name="new_prices[]" class="form-control" id="new_price" placeholder="New Price" autocomplete="off">
                <input type="hidden" name="x_margins[]" id="x_margin">
            </td>
        @else 
            <td>
                {{ $item->p_name }}
                <input type="hidden" name="product_ids[]" value="{{ $item->p_id }}">
                <input type="hidden" name="variant_ids[]" value="">
            </td>

            <td>
                <p>{{ $item->cate_name }}</p>
                <p>---{{ $item->sub_cate_name }}</p>
            </td>

            <td>
                {{ $item->p_cost }}
                <input type="hidden" id="unit_cost" value="{{ $item->p_cost }}">
            </td>

            <td>
                {{ $item->p_price }}
                <input type="hidden" name="current_prices[]" value="{{ $item->p_price }}">
            </td>

            <td>
                <input type="number" step="any" name="new_prices[]" class="form-control" id="new_price" placeholder="New Price" autocomplete="off">
                <input type="hidden" name="x_margins[]" id="x_margin">
            </td>
        @endif
@endforeach