@foreach ($purchaseBySales as $ps_challan)
    <li>
        <a href="#" class="name" id="selected_challan" data-challan_done_href="" data-id="{{ $ps_challan->id }}" data-status="{{ $ps_challan->status }}" data-date="{{ $ps_challan->date }}" data-voucher_no="{{ $ps_challan->voucher_no }}" data-supplier_account_id="{{ $ps_challan->supplier_account_id }}" data-challan_no="{{ $ps_challan->challan_no }}" data-challan_date="{{ $ps_challan->challan_date }}" data-vehicle_number="{{ $ps_challan->vehicle_number }}" data-driver_name="{{ $ps_challan->driver_name }}" data-driver_phone="{{ $ps_challan->driver_name }}" data-first_weight="{{ $ps_challan->first_weight }}" data-last_weight="{{ $ps_challan->last_weight }}" data-net_weight="{{ $ps_challan->net_weight }}">
            <strong>@lang('menu.weight_voucher') : </strong>{{ $ps_challan->voucher_no }}, <strong>@lang('menu.challan_no') : </strong>{{ $ps_challan->challan_no }},  <strong>@lang('menu.vehicle') : </strong> {{ $ps_challan->vehicle_number }}
        </a>
    </li>
@endforeach
