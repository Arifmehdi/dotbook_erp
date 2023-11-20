@foreach ($purchases as $purchase)

    @php
        $accountUtil = new App\Utils\AccountUtil();
        $amounts = $accountUtil->accountClosingBalance($purchase->supplier_account_id);
    @endphp
    <li>
        <a href="#" id="selected_invoice" class="name" data-purchase_id="{{ $purchase->purchase_id }}" data-warehouse_id="{{ $purchase->warehouse_id }}" data-warehouse_name="{{ $purchase->warehouse_name }}" data-supplier_account_id="{{ $purchase->supplier_account_id }}" data-current_balance="{{ $amounts['closing_balance_string'] }}">{{ $purchase->p_invoice_id }}</a>
    </li>
@endforeach