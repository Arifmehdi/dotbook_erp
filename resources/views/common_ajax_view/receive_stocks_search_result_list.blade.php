@foreach ($receiveStocks as $receiveStock)
    <li>
        <a href="#" id="selected_receive_stock" class="name" 
            data-id="{{ $receiveStock->id }}" 
            data-supplier_account_id="{{ $receiveStock->supplier_account_id }}" 
            data-challan_no="{{ $receiveStock->challan_no }}" 
            data-challan_date="{{ $receiveStock->challan_date }}" 
            data-is_purchased="{{ $receiveStock->p_invoice_id ? 'purchased' : 'not-purchased' }}"
            data-net_weight="{{ $receiveStock->net_weight }}"
            data-vehicle_no="{{ $receiveStock->vehicle_no }}"
            >
            {{ $receiveStock->voucher_no }}
        </a>
    </li>
@endforeach