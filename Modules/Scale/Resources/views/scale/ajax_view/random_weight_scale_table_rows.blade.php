@foreach ($random_weights as $weight)
    <tr style="cursor: pointer;" class="name vehicleSelectFromTable" id="selected_weight" data-vehicle_done_href="{{ route('scale.weight-vehicle-done', $weight->id) }}"
        data-id="{{ $weight->id }}" data-date="{{ $weight->date }}"
        data-product_id="{{ $weight->product_id }}" data-client_id="{{ $weight->client_id }}" data-challan_no="{{ $weight->challan_no }}"
        data-challan_date="{{ $weight->challan_date }}" data-vehicle_number="{{ $weight->vehicle_number }}" data-driver_name="{{ $weight->driver_name }}"
        data-driver_phone="{{ $weight->driver_phone }}" data-net_weight="{{ $weight->net_weight }}", data-weight_id="{{ $weight->weight_id }}",
        data-serial_no="{{ $weight->serial_no }}", data-driver_name="{{ $weight->driver_name }}", data-quantity="{{ $weight->quantity }}",

        data-gross_weight_value="{{ $weight->gross_weight }}", data-tare_weight_value="{{ $weight->tare_weight }}", data-net_weight_value="{{ $weight->net_weight }}",
        
        data-weight_scale_primary_id="{{ $weight->id }}"
        >
        
        <td><strong>{{ $weight->vehicle_number }}</strong></td>
        <td>{{ $weight->weight_id }} {{ $weight->status == 1 ? '✅' : '' }}</td>
        {{-- <td>{{ $weight->client_name }} </td> --}}
    </tr>
@endforeach
