<table class="w-100 selectable">
    <thead>
        <tr>
            <th class="header_text ps-1 text-start">@lang('menu.particulars')</th>
            <th class="header_text ps-1 text-end">@lang('menu.opening_balance')</th>
            <th class="header_text ps-1 text-end">@lang('menu.debit')</th>
            <th class="header_text ps-1 text-end">@lang('menu.credit')</th>
            <th class="header_text ps-1 text-end">@lang('menu.closing_balance')</th>
        </tr>
    </thead>

    @php
        $totalDebitOpeningBalance = 0;
        $totalCreditOpeningBalance = 0;
        $totalDebitTransaction = 0;
        $totalCreditTransaction = 0;
        $totalDebitClosingBalance = 0;
        $totalCreditClosingBalance = 0;
    @endphp

    <tbody class="trial_balance_main_table_body">
        @foreach ($accounts as $account)
            @php
                $debitOpeningBalance = isset($account->opening_total_debit) ? $account->opening_total_debit : 0;
                $creditOpeningBalance = isset($account->opening_total_credit) ? $account->opening_total_credit : 0;

                $currOpeningBalance = 0;
                $currOpeningBalanceSide = 'dr';
                if ($debitOpeningBalance > $creditOpeningBalance) {

                    $currOpeningBalance = $debitOpeningBalance - $creditOpeningBalance;
                    $currOpeningBalanceSide = 'dr';
                }elseif ($creditOpeningBalance > $debitOpeningBalance) {

                    $currOpeningBalance = $creditOpeningBalance - $debitOpeningBalance;
                    $currOpeningBalanceSide = 'cr';
                }

                $currentDebit = $account->curr_total_debit + ($currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0);
                $currentCredit = $account->curr_total_credit + ($currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0);

                $__currentDebit = $account->curr_total_debit;
                $__currentCredit = $account->curr_total_credit;

                $closingBalance = 0;
                $closingBalanceSide = 'dr';
                if ($currentDebit > $currentCredit) {

                    $closingBalance = $currentDebit - $currentCredit;
                } elseif ($currentCredit > $currentDebit) {

                    $closingBalance = $currentCredit - $currentDebit;
                    $closingBalanceSide = 'cr';
                }
            @endphp

            @if ($closingBalance > 0)

                @php
                    $totalDebitOpeningBalance += $currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0;
                    $totalCreditOpeningBalance += $currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0;
                    $totalDebitTransaction += $__currentDebit;
                    $totalCreditTransaction +=  $__currentCredit;
                    $totalDebitClosingBalance += $closingBalanceSide == 'dr' ? $closingBalance : 0;
                    $totalCreditClosingBalance += $closingBalanceSide == 'cr' ? $closingBalance : 0;
                @endphp

                <tr class="account_list">
                    <td class="text-start ps-1"><a href="{{ route('accounting.accounts.ledger', [$account->account_id, 'accountId', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }}</a></td>

                    @if ($currOpeningBalance > 0)

                        <td class="text-end debit_opening_balance fw-bold">
                            {{ \App\Utils\Converter::format_in_bdt($currOpeningBalance) }}
                            {{ $currOpeningBalanceSide == 'dr' ? 'Dr.' : 'Cr.' }}
                        </td>
                    @else

                        <td class="text-end debit_opening_balance fw-bold"></td>
                    @endif

                    @if ($__currentDebit > 0)

                        <td class="text-end credit_opening_balance fw-bold">{{ \App\Utils\Converter::format_in_bdt($__currentDebit) }}</td>
                    @else

                        <td class="text-end debit_opening_balance fw-bold"></td>
                    @endif

                    @if ($__currentCredit > 0)

                        <td class="text-end credit_opening_balance fw-bold">{{ \App\Utils\Converter::format_in_bdt($__currentCredit) }}</td>
                    @else

                        <td class="text-end debit_opening_balance fw-bold"></td>
                    @endif

                    <td class="text-end debit_opening_balance fw-bold">
                        {{ \App\Utils\Converter::format_in_bdt($closingBalance) }}
                        {{ $closingBalanceSide == 'dr' ? 'Dr.' : 'Cr.' }}
                    </td>
                </tr>
            @endif
        @endforeach

        @php
            $openingBalance = 0;
            $openingBalanceSide = 'dr';

            if ($totalDebitOpeningBalance > $totalCreditOpeningBalance) {

                $openingBalance = $totalDebitOpeningBalance - $totalCreditOpeningBalance;
                $openingBalanceSide = 'dr';
            }elseif ($totalCreditOpeningBalance > $totalDebitOpeningBalance) {

                $openingBalance = $totalCreditOpeningBalance - $totalDebitOpeningBalance;
                $openingBalanceSide = 'cr';
            }

            $closingBalance = 0;
            $closingBalanceSide = 'dr';

            if ($totalDebitClosingBalance > $totalCreditClosingBalance) {

                $closingBalance = $totalDebitClosingBalance - $totalCreditClosingBalance;
                $closingBalanceSide = 'dr';
            }elseif ($totalCreditClosingBalance > $totalDebitClosingBalance) {

                $closingBalance = $totalCreditClosingBalance - $totalDebitClosingBalance;
                $closingBalanceSide = 'cr';
            }
        @endphp
    </tbody>

    <tfoot class="net_total_balance_footer">
        <tr>
            <td class="text-end footer_total fw-bold" >@lang('menu.grand_total') :</td>
            <td class="text-end footer_total_debit fw-bold">
                {{ \App\Utils\Converter::format_in_bdt($openingBalance) }}
                {{ $openingBalanceSide == 'dr' ? 'Dr.' : 'Cr.' }}
            </td>
            <td class="text-end footer_total_credit fw-bold">{{ \App\Utils\Converter::format_in_bdt($totalDebitTransaction) }}</td>
            <td class="text-end footer_total_debit fw-bold">{{ \App\Utils\Converter::format_in_bdt($totalCreditTransaction) }}</td>
            <td class="text-end footer_total_credit fw-bold">
                {{ \App\Utils\Converter::format_in_bdt($closingBalance) }}
                {{ $closingBalanceSide == 'dr' ? 'Dr.' : 'Cr.' }}
            </td>
        </tr>
    </tfoot>
</table>
