@foreach ($invoices as $invoice)

    @php
        $userId = $invoice->sr_user_id ? $invoice->sr_user_id : auth()->user()->id;
        $accountUtil = new App\Utils\AccountUtil();
        $amounts = $accountUtil->accountClosingBalance($invoice->customer_account_id, $userId);
    @endphp
    <li>
        <a href="#" id="selected_invoice" class="name" data-sale_id="{{ $invoice->id }}" data-all_price_type="{{ $invoice->all_price_type }}" data-customer_account_id="{{ $invoice->customer_account_id }}" data-current_balance="{{ $amounts['closing_balance_string'] }}" data-user_id="{{ $userId }}">{{ $invoice->invoice_id }}</a>
    </li>
@endforeach