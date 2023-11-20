@foreach ($dos as $do)
    <li>
        <a href="#" id="selected_do" class="name" do_done_href="" data-total_item="{{ $do->total_item }}" data-net_total_amount="{{ $do->net_total_amount }}" data-total_payable_amount="{{ $do->total_payable_amount }}" data-total_due="{{ $do->due }}" data-sale_id="{{ $do->id }}" data-expire_date="{{ $do->expire_date }}" data-order_tax_percent="{{ $do->order_tax_percent }}" data-order_discount="{{ $do->order_discount }}" data-do_id="{{ $do->do_id }}" data-do_car_number="" data-shipping_address="{{ $do->shipping_address }}" data-receiver_phone="{{ $do->receiver_phone }}"data-do_net_weight="0" data-do_car_last_weight="0" data-do_driver_name="" data-do_driver_phone="" data-order_discount_type="{{ $do->order_discount_type }}" data-on_order_paid="{{ $do->paid }}" data-sale_note="{{ $do->sale_note }}" data-shipment_charge="{{ $do->shipment_charge }}" data-sale_account_id="{{ $do->sale_account_id }}" data-customer_accont_id="{{ $do->customer_account_id }}" data-customer="{{ $do->cmr_name }}">
            {{ $do->do_id }}
        </a>
    </li>
@endforeach
