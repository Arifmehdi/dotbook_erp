<style>
    .modal_payments_or_receipts_list_table table td { font-size: 12px!important;}
    .modal_payments_or_receipts_list_table table th { font-size: 12px!important;}
</style>
<div class="modal_payments_or_receipts_list_table">
    <div class="table-responsive">
        <table class="table modal-table table-striped table-sm">
            <thead>
                <tr class="bg-primary text-white">
                    <th style="font-size:11px!important;">@lang('menu.voucher_type')</th>
                    <th style="font-size:11px!important;">@lang('menu.date')</th>
                    <th style="font-size:11px!important;">@lang('menu.voucher_no')</th>
                    <th style="font-size:11px!important;">@lang('menu.type')</th>
                    <th style="font-size:11px!important;">@lang('menu.account')</th>
                    <th style="font-size:11px!important;">@lang('menu.amount')</th>
                    <th class="action_hideable" style="font-size:11px!important;">@lang('menu.action')</th>
                </tr>
            </thead>
            <tbody id="p_details_payment_list">
                @php
                    $totalReceivedAmount = 0;
                @endphp
                @if (count($order->references) > 0)

                   @foreach ($order->references as $reference)

                        @php
                            $voucherType = '';
                            $cashBankAccount = '';
                            $accountNo = '';
                            $bankBranch = '';
                            $bank = '';
                            $method = '';
                            $date = '';

                            if ($reference?->paymentDescription) {

                                $voucherType = 'Receipt';
                                $date = $reference?->paymentDescription->payment?->date;
                                $descriptions = $reference?->paymentDescription?->payment?->descriptions;

                                $filteredCashOrBankAccounts = $descriptions->filter(function ($description, $key) {

                                    return $description?->account?->group->sub_sub_group_number == 1 || $description?->account?->group->sub_sub_group_number == 2 || $description?->account?->group->sub_sub_group_number == 11;
                                });

                                $cashBankAccount = $filteredCashOrBankAccounts->first();
                                $accountNo = $cashBankAccount->account->account_number ? ' - A/c No:***'. substr($cashBankAccount->account->account_number, -4) : '';
                                $bankBranch = $cashBankAccount?->account?->bank_branch ? '('.$cashBankAccount?->account?->bank_branch.')' : '';
                                $bank = $cashBankAccount?->account?->bank ? '-' . $cashBankAccount?->account?->bank->name.$bankBranch : '';
                                $method = $cashBankAccount?->paymentMethod ? $cashBankAccount?->paymentMethod->name : '';
                            }elseif ($reference?->journalEntry) {

                                $voucherType = 'Journal';
                                $date = $reference?->journalEntry->journal?->date;
                                $entries = $reference?->journalEntry?->journal?->entries;

                                $filteredCashOrBankAccounts = $entries->filter(function ($entry, $key) {

                                    return $entry?->account?->group->sub_sub_group_number == 1 || $entry?->account?->group->sub_sub_group_number == 2 || $entry?->account?->group->sub_sub_group_number == 11;
                                });

                                $cashBankAccount = $filteredCashOrBankAccounts->first();
                                $accountNo = $cashBankAccount->account->account_number ? '/'. substr($cashBankAccount->account->account_number, -4) : '';
                                $bankBranch = $cashBankAccount?->account?->bank_branch ? '('.$cashBankAccount?->account?->bank_branch.')' : '';
                                $bank = $cashBankAccount?->account?->bank ? '-' . $cashBankAccount?->account?->bank->name.$bankBranch : '';
                                $method = $cashBankAccount?->paymentMethod ? $cashBankAccount?->paymentMethod->name : '';
                            }
                        @endphp

                        <tr>
                            <td class="fw-bold" style="font-size:11px!important;">{{ $voucherType }}</td>
                            <td style="font-size:11px!important;"><b>{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($date)) }}</b></td>
                            <td class="fw-bold" style="font-size:11px!important;">{{ $reference->paymentDescription->payment->voucher_no }}</td>
                            <td style="font-size:11px!important;">{{ $method }}</td>
                            <td class="fw-bold" style="font-size:11px!important;">{{ $cashBankAccount->account->name.$accountNo.$bank }}</td>

                            <td class="fw-bold" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($reference?->amount) }}</td>
                            @php
                                $totalReceivedAmount += $reference?->amount ? $reference?->amount : 0;
                            @endphp

                            <td class="action_hideable" style="font-size:11px!important;">
                                @if ($reference->paymentDescription->payment->payment_type == 1)

                                    <a href="{{ route('vouchers.receipts.show', [$reference->paymentDescription->payment->id]) }}" id="details_btn" class="btn-sm">@lang('menu.details')</a>
                                @else
                                    <a href="{{ route('vouchers.payments.show', [$reference->paymentDescription->payment->id]) }}" id="details_btn" class="btn-sm">@lang('menu.details')</a>
                                @endif
                            </td>
                        </tr>
                   @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center" style="font-size:11px!important;">@lang('menu.no_data_found')</td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" class="text-end" style="font-size:11px!important;">@lang('menu.total_received_against_reference') : </th>
                    <th style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($totalReceivedAmount) }}</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
