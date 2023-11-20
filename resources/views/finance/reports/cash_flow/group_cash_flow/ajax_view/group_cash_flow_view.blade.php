<table class="w-100 selectable">
    <thead>
        <tr>
            <th class="header_text ps-1 text-start">@lang('menu.particulars')</th>
            <th class="header_text ps-1 text-end">@lang('menu.inflow')</th>
            <th class="header_text ps-1 text-end" style="border-left: 1px solid black;">@lang('menu.outflow')</th>
        </tr>
    </thead>

    <tbody>
        @php
            $totalInflow = 0;
            $totalOutflow = 0;
        @endphp

        @if (count($groupCashflow->subgroupsAccountsForOthers) > 0)

            @foreach ($groupCashflow->subgroupsAccountsForOthers as $group)

                @if (($cashFlowSide == 'in' && $group->cash_in > 0) || ($cashFlowSide == 'out' && $group->cash_out > 0))

                    @if ($group->sub_sub_group_number != 11 && $group->sub_sub_group_number != 1 && $group->sub_sub_group_number != 2)

                        <tr class="group_tr">
                            <td class="account_group"> <a href="{{ route('reports.group.cash.flow.index', [$group->id, $cashFlowSide, $fromDate, $toDate]) }}" target="_blank" class="fw-bold">{{ $group->name }}</a></td>
                            <td class="text-end fw-bold" style="border-right: 1px solid black;">
                                @if ($cashFlowSide == 'in')
                                    @php
                                        $totalInflow += $group->cash_in;
                                    @endphp
                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                @endif
                            </td>

                            <td class="text-end fw-bold">
                                @if ($cashFlowSide == 'out')
                                    @php
                                        $totalOutflow += $group->cash_out;
                                    @endphp
                                    {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                @endif
                            </td>
                        </tr>
                    @endif
                @endif
            @endforeach
        @endif

        @if (count($groupCashflow->accounts) > 0)

            @foreach ($groupCashflow->accounts as $account)

                @if (($cashFlowSide == 'in' && $account->cash_in > 0) || ($cashFlowSide == 'out' && $account->cash_out > 0))

                    <tr class="account_tr">
                        {{-- <td class="ledger"><a href="#" target="_blank" class="text-black"> {{ $account->name }} (@lang('menu.ledger'))</a></td> --}}
                        <td class="ledger"><a href="{{ route('reports.ledger.cash.flow.index', [$account->id, $cashFlowSide, $fromDate, $toDate]) }}" target="_blank">{{ $account->name }} (@lang('menu.ledger'))</a></td>
                        <td class="text-end fw-bold" style="border-right: 1px solid black;">
                            @if ($cashFlowSide == 'in')
                                @php
                                    $totalInflow += $account->cash_in;
                                @endphp
                                {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                            @endif
                        </td>
                        <td class="text-end fw-bold">
                            @if ($cashFlowSide == 'out')
                                @php
                                    $totalOutflow += $account->cash_out;
                                @endphp
                                {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach
        @endif
    </tbody>

    <tfoot class="net_total_balance_footer">
        <tr>
            <td class="text-start fw-bold">@lang('menu.grand_total') :</td>
            <td class="text-end fw-bold net_debit_total">
                @if ($cashFlowSide == 'in')
                    {{ \App\Utils\Converter::format_in_bdt($totalInflow) }}
                @endif
            </td>

            <td class="text-end fw-bold net_credit_total">
                @if ($cashFlowSide == 'out')
                    {{ \App\Utils\Converter::format_in_bdt($totalOutflow) }}
                @endif
            </td>
        </tr>
    </tfoot>
</table>
