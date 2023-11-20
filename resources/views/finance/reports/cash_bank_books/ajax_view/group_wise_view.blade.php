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

    <tbody>
        @php
            $totalDebitOpeningBalance = 0;
            $totalCreditOpeningBalance = 0;
            $totalDebitTransaction = 0;
            $totalCreditTransaction = 0;
            $totalDebitClosingBalance = 0;
            $totalCreditClosingBalance = 0;
        @endphp
        @foreach ($mainGroups as $mainGroup)

            @if ($mainGroup->closing_balance > 0)

                @php
                    $totalDebitOpeningBalance += $mainGroup->opening_balance_side == 'dr' ? $mainGroup->opening_balance : 0;
                    $totalCreditOpeningBalance += $mainGroup->opening_balance_side == 'cr' ? $mainGroup->opening_balance : 0;
                    $totalDebitTransaction += $mainGroup->curr_total_debit;
                    $totalCreditTransaction += $mainGroup->curr_total_credit;
                    $totalDebitClosingBalance += $mainGroup->closing_balance_side == 'dr' ? $mainGroup->closing_balance : 0;
                    $totalCreditClosingBalance += $mainGroup->closing_balance_side == 'cr' ? $mainGroup->closing_balance : 0;
                @endphp

                <tr class="group_tr">
                    <td class="fw-bold" style="font-size: 13px!important;">
                        <a target="_blank" href="{{ route('reports.group.summary.index', [$mainGroup->id, $fromDate, $toDate]) }}">{{ $mainGroup->name }} <span class="text-black fw-bold"></span></a>
                    </td>

                    @if ($mainGroup->opening_balance > 0)

                        <td class="text-end fw-bold" style="border-bottom: 1px solid black!important;">
                            {{ \App\Utils\Converter::format_in_bdt($mainGroup->opening_balance) }}
                            {{ $mainGroup->opening_balance_side == 'dr' ? 'Dr.' : 'Cr.' }}
                        </td>
                    @else
                        <td class="text-end fw-bold"></td>
                    @endif

                    @if ($mainGroup->curr_total_debit > 0)
                        <td class="text-end fw-bold" style="border-bottom: 1px solid black!important;">
                            {{ \App\Utils\Converter::format_in_bdt($mainGroup->curr_total_debit) }}
                        </td>
                    @else
                        <td class="text-end fw-bold"></td>
                    @endif

                    @if ($mainGroup->curr_total_credit > 0)

                        <td class="text-end fw-bold" style="border-bottom: 1px solid black!important;">
                            {{ \App\Utils\Converter::format_in_bdt($mainGroup->curr_total_credit) }}
                        </td>
                    @else

                        <td class="text-end fw-bold"></td>
                    @endif

                    <td class="text-end fw-bold" style="border-bottom: 1px solid black!important;">
                        {{ \App\Utils\Converter::format_in_bdt($mainGroup->closing_balance) }}
                        {{ $mainGroup->closing_balance_side == 'dr' ? 'Dr.' : 'Cr.' }}
                    </td>
                </tr>

                @foreach ($mainGroup->subgroupsAccountsForOthers as $group)

                    @if ($group->closing_balance > 0)

                        <tr class="group_tr">
                            <td>
                                <a target="_blank" href="{{ route('reports.group.summary.index', [$group->id, $fromDate, $toDate]) }}">{{ $group->name }} <span class="text-black">(@lang('menu.group'))</span></a>
                            </td>

                            @if ($group->opening_balance > 0)

                                <td class="text-end">
                                    {{ \App\Utils\Converter::format_in_bdt($group->opening_balance) }}
                                    {{ $group->opening_balance_side == 'dr' ? 'Dr.' : 'Cr.' }}
                                </td>
                            @else

                                <td class="text-end"></td>
                            @endif

                            @if ($group->curr_total_debit > 0)

                                <td class="text-end">{{ \App\Utils\Converter::format_in_bdt($group->curr_total_debit) }}</td>
                            @else

                                <td class="text-end"></td>
                            @endif

                            @if ($group->curr_total_credit > 0)

                                <td class="text-end">{{ \App\Utils\Converter::format_in_bdt($group->curr_total_credit) }}</td>
                            @else

                                <td class="text-end"></td>
                            @endif

                            <td class="text-end">
                                {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                {{ $group->closing_balance_side == 'dr' ? 'Dr.' : 'Cr.' }}
                            </td>
                        </tr>
                    @endif
                @endforeach

                @foreach ($mainGroup->accounts as $account)

                    @if ($account->closing_balance > 0)

                        <tr class="account_tr">
                            <td>
                                <a target="_blank" href="{{ route('accounting.accounts.ledger', [$account->id, 'accountId', $fromDate, $toDate]) }}">{{ $account->name }} <span class="text-black">(@lang('menu.ledger'))</span></a>
                            </td>

                            @if ($account->opening_balance > 0)

                                <td class="text-end">
                                    {{ \App\Utils\Converter::format_in_bdt($account->opening_balance) }}
                                    {{ $account->opening_balance_side == 'dr' ? 'Dr.' : 'Cr.' }}
                                </td>
                            @else

                                <td class="text-end"></td>
                            @endif

                            @if ($account->curr_total_debit > 0)

                                <td class="text-end">{{ \App\Utils\Converter::format_in_bdt($account->curr_total_debit) }}</td>
                            @else

                                <td class="text-end"></td>
                            @endif

                            @if ($account->curr_total_credit > 0)

                                <td class="text-end">{{ \App\Utils\Converter::format_in_bdt($account->curr_total_credit) }}</td>
                            @else

                                <td class="text-end"></td>
                            @endif

                            <td class="text-end">
                                {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                {{ $account->closing_balance_side == 'dr' ? 'Dr.' : 'Cr.' }}
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endif
        @endforeach
    </tbody>

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

    <tfoot class="net_total_balance_footer">
        <tr>
            <td class="text-start fw-bold">@lang('menu.grand_total') :</td>
            <td class="text-end fw-bold net_opening_total">
                {{ \App\Utils\Converter::format_in_bdt($openingBalance) }}
                {{ $openingBalanceSide == 'dr' ? 'Dr.' : 'Cr.' }}
            </td>
            <td class="text-end fw-bold net_debit_total">
                {{ \App\Utils\Converter::format_in_bdt($totalDebitTransaction) }}
            </td>
            <td class="text-end fw-bold net_credit_total">
                {{ \App\Utils\Converter::format_in_bdt($totalCreditTransaction) }}
            </td>
            <td class="text-end fw-bold net_closing_total">
                {{ \App\Utils\Converter::format_in_bdt($closingBalance) }}
                {{ $closingBalanceSide == 'dr' ? 'Dr.' : 'Cr.' }}
            </td>
        </tr>
    </tfoot>
</table>
