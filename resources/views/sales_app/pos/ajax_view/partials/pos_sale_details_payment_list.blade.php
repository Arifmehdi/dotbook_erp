<div class="payment_table">
    <div class="table-responsive">
        <table class="table modal-table table-sm table-striped custom-table">
            <thead>
                <tr class="bg-primary text-white">
                    <th class="text-startx">@lang('menu.date')</th>
                    <th class="text-startx">@lang('menu.invoice_id')</th>
                    <th class="text-startx">@lang('menu.amount')</th>
                    <th class="text-startx">@lang('menu.account')</th>
                    <th class="text-startx">@lang('menu.method')</th>
                    <th class="text-startx">@lang('menu.type')</th>
                    <th class="text-startx">@lang('menu.action')</th>
                </tr>
            </thead>
            <tbody id="p_details_payment_list">
                @if (count($sale->sale_payments) > 0)
                    @foreach ($sale->sale_payments as $payment)
                        <tr data-info="{{ $payment }}">
                            <td class="text-start">{{ date('d/m/Y', strtotime($payment->date)) }}</td>
                            <td class="text-start">{{ $payment->invoice_id }}</td>
                            
                            <td class="text-start">
                                {{ json_decode($generalSettings->business, true)['currency'] . ' ' . $payment->paid_amount }}
                            </td>

                            <td class="text-start">
                                {{ $payment->account ? $payment->account->name : '----' }}
                            </td>

                            <td class="text-start">
                                {{ $payment->paymentMethod ? $payment->paymentMethod->name : $payment->pay_mode }}
                            </td>

                            <td class="text-start">
                                {{ $payment->payment_type == 1 ? 'Sale due' : 'Return due' }}
                            </td>
                            
                            <td class="text-start">
                                @if ($payment->payment_type == 1)
                                    <a href="{{ route('sales.payment.edit', $payment->id) }}"
                                        id="edit_payment" class="btn-sm"><i
                                            class="fas fa-edit text-info"></i></a>
                                @else
                                    <a href="{{ route('sales.return.payment.edit', $payment->id) }}"
                                        id="edit_return_payment" class="btn-sm"><i
                                            class="fas fa-edit text-info"></i></a>
                                @endif

                                <a href="{{ route('sales.payment.details', $payment->id) }}"
                                    id="payment_details" class="btn-sm">
                                    <i class="fas fa-eye text-primary"></i>
                                </a>

                                <a href="{{ route('sales.payment.delete', $payment->id) }}"
                                    id="delete_payment" class="btn-sm">
                                    <i class="far fa-trash-alt text-danger"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center">@lang('menu.no_data_found')</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>