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
        @foreach ($mainGroup->subgroupsAccountsForOthers as $group)

            @if ($group->closing_balance > 0)

                <tr class="group_tr">
                    <td class="fw-bold">

                        @if (isset($group->id))

                            <a target="_blank" href="{{ route('reports.group.summary.index', [$group->id, $fromDate, $toDate]) }}">{{ $group->name }} <span class="text-black fw-bold">(@lang('menu.group'))</span></a>
                        @else

                            {{ $group->name }}
                        @endif
                    </td>
                    <td class="text-end fw-bold">
                        {{ \App\Utils\Converter::format_in_bdt($group->opening_balance) }}
                        {{ $group->opening_balance_side == 'dr' ? 'Dr.' : 'Cr.' }}
                    </td>
                    <td class="text-end fw-bold">{{ \App\Utils\Converter::format_in_bdt($group->curr_total_debit) }}</td>
                    <td class="text-end fw-bold">{{ \App\Utils\Converter::format_in_bdt($group->curr_total_credit) }}</td>
                    <td class="text-end fw-bold">
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
                    <td class="text-end fw-bold">
                        {{ \App\Utils\Converter::format_in_bdt($account->opening_balance) }}
                        {{ $account->opening_balance_side == 'dr' ? 'Dr.' : 'Cr.' }}
                    </td>
                    <td class="text-end fw-bold">{{ \App\Utils\Converter::format_in_bdt($account->curr_total_debit) }}</td>
                    <td class="text-end fw-bold">{{ \App\Utils\Converter::format_in_bdt($account->curr_total_credit) }}</td>
                    <td class="text-end fw-bold">
                        {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                        {{ $account->closing_balance_side == 'dr' ? 'Dr.' : 'Cr.' }}
                    </td>
                </tr>
            @endif
        @endforeach

    </tbody>

    <tfoot class="net_total_balance_footer">
        <tr>
            <td class="text-start fw-bold">@lang('menu.grand_total') :</td>
            <td class="text-end fw-bold net_opening_total">
                {{ \App\Utils\Converter::format_in_bdt($mainGroup->opening_balance) }}
                {{ $mainGroup->opening_balance_side == 'dr' ? 'Dr.' : 'Cr.' }}
            </td>
            <td class="text-end fw-bold net_debit_total">
                {{ \App\Utils\Converter::format_in_bdt($mainGroup->curr_total_debit) }}
            </td>
            <td class="text-end fw-bold net_credit_total">
                {{ \App\Utils\Converter::format_in_bdt($mainGroup->curr_total_credit) }}
            </td>
            <td class="text-end fw-bold net_closing_total">
                {{ \App\Utils\Converter::format_in_bdt($mainGroup->closing_balance) }}
                {{ $mainGroup->closing_balance_side == 'dr' ? 'Dr.' : 'Cr.' }}
            </td>
        </tr>
    </tfoot>
</table>
