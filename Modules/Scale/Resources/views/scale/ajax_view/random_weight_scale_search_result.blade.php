@foreach ($randomWeightSales as $weightScale)
    <li>
        <a 
        href="#" 
        id="selected_weight" 
        style="cursor: pointer;" 
        class="name"
        data-id="{{ $weightScale->id }}" 
        data-date="{{ $weightScale->date }}"
        data-product_id="{{ $weightScale->product_id }}" 
        data-client_id="{{ $weightScale->client_id }}" 
        data-challan_no="{{ $weightScale->challan_no }}"
        data-challan_date="{{ $weightScale->challan_date }}"
        data-vehicle_number="{{ $weightScale->vehicle_number }}" 
        data-driver_name="{{ $weightScale->driver_name }}"
        data-driver_phone="{{ $weightScale->driver_phone }}" 
        data-net_weight="{{ $weightScale->net_weight }}", 
        data-weight_id="{{ $weightScale->weight_id }}",
        data-serial_no="{{ $weightScale->serial_no }}", 
        data-driver_name="{{ $weightScale->driver_name }}", 
        data-quantity="{{ $weightScale->quantity }}",
        data-gross_weight_value="{{ $weightScale->gross_weight }}",
        data-tare_weight_value="{{ $weightScale->tare_weight }}", 
        data-net_weight_value="{{ $weightScale->net_weight }}",
        data-weight_scale_primary_id="{{ $weightScale->id }}", 
        >
            @lang('menu.weight_id') : <strong>{{ $weightScale->weight_id }}</strong>, 
            @lang('menu.vehicle_no') : <strong>{{ $weightScale->vehicle_number }}</strong> - <strong>{{ $weightScale->status == 1 ? __('menu.COMPLETED') : __('menu.RUNNING') }}</strong>
        </a>
    </li>
@endforeach
