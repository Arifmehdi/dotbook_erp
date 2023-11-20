@foreach ($ps_challans as $ps_challan)
    <tr style="cursor: pointer;" class="name" id="selected_challan" data-vehicle_done_href="{{ route('purchase.by.scale.vehicle.done', $ps_challan->id) }}" data-id="{{ $ps_challan->id }}" data-date="{{ $ps_challan->date }}" data-voucher_no="{{ $ps_challan->voucher_no }}" data-supplier_account_id="{{ $ps_challan->supplier_account_id }}" data-challan_no="{{ $ps_challan->challan_no }}" data-challan_date="{{ $ps_challan->challan_date }}" data-vehicle_number="{{ $ps_challan->vehicle_number }}" data-driver_name="{{ $ps_challan->driver_name }}" data-driver_phone="{{ $ps_challan->driver_name }}"  data-first_weight="{{ $ps_challan->first_weight }}" data-last_weight="{{ $ps_challan->last_weight }}" data-net_weight="{{ $ps_challan->net_weight }}">
        <td><strong>{{ $ps_challan->vehicle_number }}</strong></td>
        <td>{{ $ps_challan->supplier_name }} {{ $ps_challan->status == 1 ? '✅' : '' }}</td>
    </tr>
@endforeach