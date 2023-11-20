<table class="w-100 selectable">
    <thead>
        {{-- <tr>
            <th class="header_text text-start">@lang('menu.particulars')</th>
            <th class="header_text text-end">@lang('menu.debit')</th>
            <th class="header_text text-end">@lang('menu.credit')</th>
            <th class="header_text text-end">@lang('menu.closing_balance')</th>
        </tr> --}}

        <tr>
            <th rowspan="2" class="header_text text-center" style="border-top:1px solid black;">@lang('menu.particulars')</th>
            <th colspan="2" class="header_text text-center" style="border:1px solid black;">@lang('menu.opening_balance')</th>
            <th colspan="2" class="header_text text-center" style="border:1px solid black;">@lang('menu.closing_balance')</th>
        </tr>

        <tr>
            <th class="header_text text-end pe-1" style="border-left:1px solid black;border-right:1px solid black;">@lang('menu.debit')</th>
            <th class="header_text text-end pe-1" style="border-right:1px solid black;">@lang('menu.credit')</th>
            <th class="header_text text-end pe-1" style="border-right:1px solid black;">@lang('menu.debit')</th>
            <th class="header_text text-end pe-1" style="border-right:1px solid black;">@lang('menu.credit')</th>
        </tr>
    </thead>
    @php
        $totalDebitOpeningBalance = $openingStock;
        $totalCreditOpeningBalance = 0;
        $totalDebitClosingBalance = $openingStock;
        $totalCreditClosingBalance = 0;
        $proviousGroupId = '';
    @endphp
    <tbody class="trial_balance_main_table_body">
        @if ($openingStock > 0)
            <tr class="opening_stock">
                <td class="text-start fw-bold">@lang('menu.opening_stock')</td>
                <td class="text-end debit_amount fw-bold">{{ \App\Utils\Converter::format_in_bdt($openingStock) }}</td>
                <td class="text-end closing_balance fw-bold"></td>
                <td class="text-end closing_balance fw-bold">{{ \App\Utils\Converter::format_in_bdt($openingStock) }}</td>
                <td class="text-end closing_balance fw-bold"></td>
            </tr>
        @endif

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
                    $totalDebitOpeningBalance += ($currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0);
                    $totalCreditOpeningBalance += ($currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0);
                    $totalDebitClosingBalance += $closingBalanceSide == 'dr' ? $closingBalance : 0;
                    $totalCreditClosingBalance += $closingBalanceSide == 'cr' ? $closingBalance : 0;
                @endphp

                <tr class="account_list">
                    <td class="text-start ps-1"><a href="{{ route('accounting.accounts.ledger', [$account->account_id, 'accountId', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }}</a></td>

                    <td class="text-end debit_opening_balance fw-bold">{{ $currOpeningBalanceSide == 'dr' && $currOpeningBalance > 0 ? \App\Utils\Converter::format_in_bdt($currOpeningBalance) : '' }}</td>

                    <td class="text-end credit_opening_balance fw-bold">{{ $currOpeningBalanceSide == 'cr' && $currOpeningBalance > 0 ? \App\Utils\Converter::format_in_bdt($currOpeningBalance) : '' }}</td>

                    <td class="text-end debit_closing_balance fw-bold">
                        {{ $closingBalanceSide == 'dr' ? App\Utils\Converter::format_in_bdt($closingBalance) : '' }}
                    </td>

                    <td class="text-end credit_closing_balance fw-bold">
                        {{ $closingBalanceSide == 'cr' ? App\Utils\Converter::format_in_bdt($closingBalance) : '' }}
                    </td>
                </tr>
            @endif

        @endforeach
        @php
            $differenceInOpeningBalance = 0;
            $differenceInOpeningBalanceSide = 'dr';
            if ($totalDebitClosingBalance > $totalCreditClosingBalance) {

                $differenceInOpeningBalance = $totalDebitClosingBalance - $totalCreditClosingBalance;
                $differenceInOpeningBalanceSide = 'dr';
            }elseif($totalCreditClosingBalance > $totalDebitClosingBalance) {

                $differenceInOpeningBalance = $totalCreditClosingBalance - $totalDebitClosingBalance;
                $differenceInOpeningBalanceSide = 'cr';
            }

            // dd('DO: '. $differenceInOpeningBalance. 'Side: '.$differenceInOpeningBalanceSide);

            $totalDebitOpeningBalance += $differenceInOpeningBalanceSide == 'cr' ? $differenceInOpeningBalance : 0;
            $totalCreditOpeningBalance += $differenceInOpeningBalanceSide == 'dr' ? $differenceInOpeningBalance : 0;
            $totalDebitClosingBalance += $differenceInOpeningBalanceSide == 'cr' ? $differenceInOpeningBalance : 0;
            $totalCreditClosingBalance += $differenceInOpeningBalanceSide == 'dr' ? $differenceInOpeningBalance : 0;
        @endphp
        <tr class="difference_in_opening_balance_area">
            <td class="text-start fw-bold" style="text-align: right!important;">@lang('menu.differenceInOpeningBalance') :</td>
            <td class="text-end debit_amount fw-bold">{{ $differenceInOpeningBalanceSide == 'cr' ? \App\Utils\Converter::format_in_bdt($differenceInOpeningBalance) : '' }}</td>
            <td class="text-end credit_amount fw-bold">{{ $differenceInOpeningBalanceSide == 'dr' ? \App\Utils\Converter::format_in_bdt($differenceInOpeningBalance) : '' }}</td>
            <td class="text-end debit_amount fw-bold">{{ $differenceInOpeningBalanceSide == 'cr' ? \App\Utils\Converter::format_in_bdt($differenceInOpeningBalance) : '' }}</td>
            <td class="text-end credit_amount fw-bold">{{ $differenceInOpeningBalanceSide == 'dr' ? \App\Utils\Converter::format_in_bdt($differenceInOpeningBalance) : '' }}</td>
        </tr>
    </tbody>

    <tfoot>
        <tfoot class="net_total_balance_footer">
            <td class="text-end footer_total fw-bold" >@lang('menu.total') :</td>
            <td class="text-end footer_total_debit fw-bold">{{ \App\Utils\Converter::format_in_bdt($totalDebitOpeningBalance) }}</td>
            <td class="text-end footer_total_credit fw-bold">{{ \App\Utils\Converter::format_in_bdt($totalCreditOpeningBalance) }}</td>
            <td class="text-end footer_total_debit fw-bold">{{ \App\Utils\Converter::format_in_bdt($totalDebitClosingBalance) }}</td>
            <td class="text-end footer_total_credit fw-bold">{{ \App\Utils\Converter::format_in_bdt($totalCreditClosingBalance) }}</td>
        </tr>
    </tfoot>
</table>
