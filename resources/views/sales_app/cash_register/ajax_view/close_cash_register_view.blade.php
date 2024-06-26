@php
    use Carbon\Carbon;
@endphp

<div class="modal-header">
    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.register_details') (
        {{ Carbon::createFromFormat('Y-m-d H:i:s', $activeCashRegister->created_at)->format('jS M, Y h:i A') }}
        - {{ Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'))->format('jS M, Y h:i A') }} )
    </h6>
    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
</div>
<div class="modal-body">
    <!--begin::Form-->
    <form action="{{ route('sales.cash.register.close') }}" method="POST">
        @csrf
        @if (auth()->user()->can('register_view'))
            <table class="cash_register_table modal-table table table-sm">
                <tbody>
                    <tr>
                        <td class="text-start">@lang('menu.opening_balance') :</td>
                        <td class="text-start">
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ App\Utils\Converter::format_in_bdt($activeCashRegister->cash_in_hand) }}
                        </td>
                    </tr>

                    @foreach ($paymentMethodPayments as $payment)
                        <tr>
                            <td width="50" class="text-start"> {{ $payment->name . ' Payment' }} :</td>
                            <td width="50" class="text-start">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ App\Utils\Converter::format_in_bdt($payment->total_paid) }}
                            </td>
                        </tr>
                    @endforeach

                    <tr>
                        <td width="50" class="text-start">
                            @lang('menu.total_credit_sale'):
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
        @endif

        <div class="form-group row">
            <div class="col-md-4">
                @php
                    $__receivedInCashAccount = $receivedInCashAccount + $activeCashRegister->cash_in_hand;
                @endphp
                <label><b>@lang('menu.closed_time') </b></label>
                <input required type="number" name="closed_amount" step="any" class="form-control"
                    value="{{ $__receivedInCashAccount }}">
            </div>
        </div>

        <div class="form-group row mt-1">
            <div class="col-md-12">
                <label><b>@lang('menu.closing_note') </b></label>
                <textarea name="closing_note" class="form-control ckEditor" cols="10" rows="3"
                    placeholder="@lang('menu.closing_note')"></textarea>
            </div>
        </div>

        <div class="form-group mt-3">
            <div class="d-flex justify-content-end">
                <div class="loading-btn-box">
                    <button type="button" class="btn btn-sm loading_button display-none"><i
                            class="fas fa-spinner"></i></button>
                    <button type="submit" class="btn btn-sm btn-success float-end">Close Register</button>
                    <button type="reset" data-bs-dismiss="modal"
                        class="btn btn-sm btn-danger float-end me-0">@lang('menu.close')</button>
                </div>
            </div>
        </div>
    </form>
</div>
