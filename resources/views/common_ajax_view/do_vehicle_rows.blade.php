@foreach ($weights as $weight)
    @php
        $netWeight = $weight->second_weight - $weight->first_weight;
    @endphp
    <tr style="cursor: pointer;" class="name" id="selected_do" data-do_done_href="{{ route('sales.delivery.done', $weight->id) }}" data-weight_id="{{ $weight->id }}" data-total_item="{{ $weight->total_item }}" data-net_total_amount="{{ $weight->net_total_amount }}" data-total_payable_amount="{{ $weight->total_payable_amount }}" data-total_due="{{ $weight->due }}" data-sale_id="{{ $weight->do_id }}" data-expire_date="{{ $weight->expire_date }}" data-order_tax="{{ $weight->order_tax_percent }}" data-order_discount="{{ $weight->order_discount }}" data-do_id="{{ $weight->do_str_id }}" data-do_car_number="{{ $weight->do_car_number }}" data-shipping_address="{{ $weight->shipping_address }}" data-receiver_phone="{{ $weight->receiver_phone }}"data-do_net_weight="{{ $netWeight }}" data-do_car_last_weight="{{ $weight->do_car_last_weight }}" data-do_driver_name="{{ $weight->do_driver_name }}" data-do_driver_phone="{{ $weight->do_driver_phone }}" data-order_discount_type="{{ $weight->order_discount_type }}" data-sale_note="{{ $weight->sale_note }}" data-shipment_charge="{{ $weight->shipment_charge }}" data-sale_account_id="{{ $weight->sale_account_id }}" data-customer_account_id="{{ $weight->customer_account_id }}" data-customer="{{ $weight->cmr_name }}">
        <td><strong>{{ $weight->do_car_number }}</strong></td>
        <td>{{ $weight->do_str_id }} {{ $weight->sale_id ? '✅' : '' }}</td>
    </tr>
@endforeach
