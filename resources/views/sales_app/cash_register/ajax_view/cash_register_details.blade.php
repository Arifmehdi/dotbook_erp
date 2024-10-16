@php
    use Carbon\Carbon;
@endphp

<div class="modal-header">
    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.register_details') (
        {{ Carbon::createFromFormat('Y-m-d H:i:s', $activeCashRegister->created_at)->format('jS M, Y h:i A') }}
        @if ($activeCashRegister->closed_at)
            - {{ Carbon::createFromFormat('Y-m-d H:i:s', $activeCashRegister->closed_at)->format('jS M, Y h:i A') }}
        @else
            - {{ Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'))->format('jS M, Y h:i A') }}
        @endif
        )
    </h6>

    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
        <span class="fas fa-times"></span>
    </a>
</div>

<div class="modal-body">
    <table class="cash_register_table table modal-table table-sm">
        <tbody>
            <tr>
                <td width="50" class="text-start">@lang('menu.opening_balance') :</td>
                <td width="50" class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }}
                    {{ App\Utils\Converter::format_in_bdt($activeCashRegister->cash_in_hand) }}
                </td>
            </tr>

            @foreach ($paymentMethodPayments as $payment)
                <tr>
                    <td width="50" class="text-start"> {{$payment->name.' Payment' }} :</td>
                    <td width="50" class="text-start">
                        {{ json_decode($generalSettings->business, true)['currency'] }}
                        {{ App\Utils\Converter::format_in_bdt($payment->total_paid) }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <td width="50" class="text-start">
                    @lang('menu.total_credit_sale')
                </td>
                <td width="50" class="text-start text-danger">
                    {{ json_decode($generalSettings->business, true)['currency'] }}
                    {{ App\Utils\Converter::format_in_bdt($totalCredit->sum('total_due')) }}
                </td>
            </tr>
        </tbody>
    </table>
    <hr>

    <p><strong>@lang('menu.collected_amounts_by_account')</strong></p>
    <table class="cash_register_table table modal-table table-sm">
        <tbody>
            @php
                $receivedInCashAccount = 0;
            @endphp
            @foreach ($accountPayments as $accountType)
                @if ($accountType->account_type == 1)
                    @php
                        $receivedInCashAccount += $accountType->total_paid;
                    @endphp
                @endif
                <tr>
                    <td width="50" class="text-start">
                        {{ $accountType->account_type == 1 ? 'Cash-In-Hand' : 'Bank A/c' }} :
                    </td>
                    <td width="50" class="text-start">
                        {{ json_decode($generalSettings->business, true)['currency'] }}
                        {{ App\Utils\Converter::format_in_bdt($accountType->total_paid) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <hr>

    @php
        $__receivedInCashAccount = $receivedInCashAccount + $activeCashRegister->cash_in_hand
    @endphp
    <p><strong>@lang('menu.current_cash_amount') : </strong> {{ json_decode($generalSettings->business, true)['currency'] }}
        {{ App\Utils\Converter::format_in_bdt($__receivedInCashAccount) }}
    </p>

    <hr>
    <div class="cash_register_info">
        <ul class="list-unstyled">
            <li>
                <b>User : </b> {{ $activeCashRegister->u_prefix.' '.$activeCashRegister->u_first_name.' '.$activeCashRegister->u_last_name }}
            </li>

            <li>
                <b>@lang('menu.email') : </b> {{ $activeCashRegister->u_email }}
            </li>

            <li>
                <b>@lang('menu.business_location') : </b>
                {!!
                    $activeCashRegister->b_name
                    ? $activeCashRegister->b_name.'/'.$activeCashRegister->b_code
                    : json_decode($generalSettings->business, true)['shop_name'].'   '
                !!}
            </li>

            <li>
                <b>@lang('menu.cash_counter') : </b> {!! $activeCashRegister->counter_name .' (<b>'.$activeCashRegister->cc_s_name.'</b>)' !!}
            </li>
        </ul>
    </div>

    <div class="form-group text-end mt-3">
        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end">@lang('menu.close')</button>
    </div>
</div>
