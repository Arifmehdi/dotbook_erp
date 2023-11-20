<table class="w-100">
    <thead>
        <tr>
            <th class="header_text ps-1">@lang('menu.sources')</th>
            <th class="header_text ps-1" style="border-left: 1px solid black;">@lang('menu.applications')</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td class="credit_area" style="width: 50%;">
                @if ($capitalAccount->closing_balance_side == 'cr' && $capitalAccount->closing_balance > 0)
                    <table class="capital_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a target="_blank" href="{{ route('reports.group.summary.index', [$capitalAccount->main_group_id, $fromDate, $toDate]) }}" class="text-black">{{ $capitalAccount->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($capitalAccount->groups as $group)
                                            @if ($group->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1">
                                                        <a target="_blank" href="{{ route('reports.group.summary.index', [$group->group_id, $fromDate, $toDate]) }}">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a>
                                                    </td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $group->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($capitalAccount->accounts as $account)
                                            <tr>
                                                <td class="group_account_name ps-1"><a target="_blank" href="{{ route('accounting.accounts.ledger', [$account->account_id, 'accountId', $fromDate, $toDate]) }}">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                <td class="group_account_balance text-end">
                                                    {{ $group->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                    {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                @endif
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($capitalAccount->closing_balance) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($loanLiabilitiesAccount->closing_balance_side == 'cr' && $loanLiabilitiesAccount->closing_balance > 0)
                    <table class="loan_liabilities_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a target="_blank" href="{{ route('reports.group.summary.index', [$loanLiabilitiesAccount->main_group_id, $fromDate, $toDate]) }}" class="text-black">{{ $loanLiabilitiesAccount->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($loanLiabilitiesAccount->groups as $group)
                                            @if ($group->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1">
                                                        <a target="_blank" href="{{ route('reports.group.summary.index', [$group->group_id, $fromDate, $toDate]) }}">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a>
                                                    </td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $group->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($loanLiabilitiesAccount->accounts as $account)
                                            @if ($account->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a target="_blank" href="{{ route('accounting.accounts.ledger', [$account->account_id, 'accountId', $fromDate, $toDate]) }}">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $account->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($loanLiabilitiesAccount->closing_balance) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($branchAndDivisionsAccount->closing_balance_side == 'cr' && $branchAndDivisionsAccount->closing_balance > 0)
                    <table class="branch_and_divisions_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a target="_blank" href="{{ route('reports.group.summary.index', [$branchAndDivisionsAccount->main_group_id, $fromDate, $toDate]) }}" class="text-black">{{ $branchAndDivisionsAccount->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($branchAndDivisionsAccount->groups as $group)
                                            @if ($group->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1">
                                                        <a target="_blank" href="{{ route('reports.group.summary.index', [$group->group_id, $fromDate, $toDate]) }}">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a>
                                                    </td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $group->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($branchAndDivisionsAccount->accounts as $account)
                                            @if ($account->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a target="_blank" href="{{ route('accounting.accounts.ledger', [$account->account_id, 'accountId', $fromDate, $toDate]) }}">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $account->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($branchAndDivisionsAccount->closing_balance) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($suspenseAccount->closing_balance_side == 'cr' && $suspenseAccount->closing_balance > 0)
                    <table class="suspense_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a target="_blank" href="{{ route('reports.group.summary.index', [$suspenseAccount->main_group_id, $fromDate, $toDate]) }}" class="text-black">{{ $suspenseAccount->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($suspenseAccount->groups as $group)
                                            @if ($group->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1">
                                                        <a target="_blank" href="{{ route('reports.group.summary.index', [$group->group_id, $fromDate, $toDate]) }}">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a>
                                                    </td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $group->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($suspenseAccount->accounts as $account)
                                            @if ($account->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a target="_blank" href="{{ route('accounting.accounts.ledger', [$account->account_id, 'accountId', $fromDate, $toDate]) }}">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $account->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($suspenseAccount->closing_balance) }}</strong></td>
                        </tr>
                    </table>
                @endif
            </td>

            <td class="debit_area">
                @if ($capitalAccount->closing_balance_side == 'dr' && $capitalAccount->closing_balance > 0)
                    <table class="capital_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a target="_blank" href="{{ route('reports.group.summary.index', [$capitalAccount->main_group_id, $fromDate, $toDate]) }}" class="text-black">{{ $capitalAccount->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($capitalAccount->groups as $group)
                                            @if ($group->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="#">
                                                        <a target="_blank" href="{{ route('reports.group.summary.index', [$group->group_id, $fromDate, $toDate]) }}">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a>
                                                    </td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $group->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($capitalAccount->accounts as $account)
                                            @if ($account->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a target="_blank" href="{{ route('accounting.accounts.ledger', [$account->account_id, 'accountId', $fromDate, $toDate]) }}">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $group->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($capitalAccount->closing_balance) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($loanLiabilitiesAccount->closing_balance_side == 'dr' && $loanLiabilitiesAccount->closing_balance > 0)
                    <table class="loan_liabilities_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a target="_blank" href="{{ route('reports.group.summary.index', [$loanLiabilitiesAccount->main_group_id, $fromDate, $toDate]) }}" class="text-black">{{ $loanLiabilitiesAccount->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($loanLiabilitiesAccount->groups as $group)
                                            @if ($group->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1">
                                                        <a target="_blank" href="{{ route('reports.group.summary.index', [$group->group_id, $fromDate, $toDate]) }}">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a>
                                                    </td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $group->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($loanLiabilitiesAccount->accounts as $account)
                                            @if ($account->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a target="_blank" href="{{ route('accounting.accounts.ledger', [$account->account_id, 'accountId', $fromDate, $toDate]) }}">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $account->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($loanLiabilitiesAccount->closing_balance) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($branchAndDivisionsAccount->closing_balance_side == 'dr' && $branchAndDivisionsAccount->closing_balance > 0)
                    <table class="branch_and_divisions_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a target="_blank" href="{{ route('reports.group.summary.index', [$branchAndDivisionsAccount->main_group_id, $fromDate, $toDate]) }}" class="text-black">{{ $branchAndDivisionsAccount->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($branchAndDivisionsAccount->groups as $group)
                                            @if ($group->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1">
                                                        <a target="_blank" href="{{ route('reports.group.summary.index', [$group->group_id, $fromDate, $toDate]) }}">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a>
                                                    </td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $group->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($branchAndDivisionsAccount->accounts as $account)
                                            @if ($account->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a target="_blank" href="{{ route('accounting.accounts.ledger', [$account->account_id, 'accountId', $fromDate, $toDate]) }}">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $account->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($branchAndDivisionsAccount->closing_balance) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($suspenseAccount->closing_balance_side == 'dr' && $suspenseAccount->closing_balance > 0)
                    <table class="suspense_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a target="_blank" href="{{ route('reports.group.summary.index', [$branchAndDivisionsAccount->main_group_id, $fromDate, $toDate]) }}" class="text-black">{{ $branchAndDivisionsAccount->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($suspenseAccount->groups as $group)
                                            @if ($group->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1">
                                                        <a target="_blank" href="{{ route('reports.group.summary.index', [$group->group_id, $fromDate, $toDate]) }}">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a>
                                                    </td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $group->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($suspenseAccount->accounts as $account)
                                            @if ($account->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a target="_blank" href="{{ route('accounting.accounts.ledger', [$account->account_id, 'accountId', $fromDate, $toDate]) }}">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $account->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($suspenseAccount->closing_balance) }}</strong></td>
                        </tr>
                    </table>
                @endif

                <table class="profit_loss_account_group_table w-100 mt-1">
                    <tr>
                        <td class="first_td">
                            <strong class="ps-2">
                                @if ($netProfitLoss['netProfitLossSide'] == 'cr')
                                <a target="_black" href="{{ route('reports.profit.loss.account.index', [$fromDate, $toDate]) }}" class="text-black">@lang('menu.net_profit')</a>
                                @elseif($netProfitLoss['netProfitLossSide'] == 'dr')
                                <a target="_black" href="{{ route('reports.profit.loss.account.index', [$fromDate, $toDate]) }}" class="text-black">@lang('menu.net_loss')</a>
                                @endif
                            </strong>
                        </td>

                        <td class="text-end">
                            <strong>
                                @if ($netProfitLoss['netProfitLossSide'] == 'cr')
                                    {{ \App\Utils\Converter::format_in_bdt($netProfitLoss['netProfit']) }}
                                @elseif($netProfitLoss['netProfitLossSide'] == 'dr')
                                    {{ \App\Utils\Converter::format_in_bdt($netProfitLoss['netLoss']) }}
                                @endif
                            </strong>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </tbody>

    <tfoot class="net_total_balance_footer">
        <tr>
            <td class="text-end fw-bold net_credit_total">@lang('menu.total') : {{ \App\Utils\Converter::format_in_bdt($totalCredit) }}</td>
            <td class="text-end fw-bold net_debit_total">@lang('menu.total') : {{ \App\Utils\Converter::format_in_bdt($totalDebit) }}</td>
        </tr>

        <tr>
            <td colspan="2">
                <table class="w-100">
                    <thead>
                        <tr>
                            <th class="header_text ps-1 text-start w-50">@lang('menu.particulars')</th>
                            <th class="header_text ps-1 text-end">@lang('menu.closing_balance')</th>
                            <th class="header_text ps-1 text-end">@lang('menu.wkg_cap_increase')</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-bold text-start w-50">{{ $currentAssetAccount->main_group_name }}</td>
                            <td class="fw-bold text-end">{{ \App\Utils\Converter::format_in_bdt($currentAssetAccount->closing_balance) }}
                                {{ ucfirst($currentAssetAccount->closing_balance_side) }}.</td>
                            <td class="fw-bold text-end">
                                {{ $currentAssetAccount->closing_balance_side == 'cr' ? '(-)' : '' }}
                                {{ \App\Utils\Converter::format_in_bdt($currentAssetAccount->closing_balance) }}
                            </td>
                        </tr>

                        <tr>
                            <td class="fw-bold text-start w-50">{{ $currentLiabilitiesAccount->main_group_name }}</td>
                            <td class="fw-bold text-end">{{ \App\Utils\Converter::format_in_bdt($currentLiabilitiesAccount->closing_balance) }}
                                {{ ucfirst($currentLiabilitiesAccount->closing_balance_side) }}.</td>
                            <td class="fw-bold text-end">
                                {{ $currentLiabilitiesAccount->closing_balance_side == 'cr' ? '(-)' : '' }}
                                {{ \App\Utils\Converter::format_in_bdt(abs($currentLiabilitiesAccount->closing_balance)) }}
                            </td>
                        </tr>

                        <tr>
                            <td class="fw-bold text-start w-50">@lang('menu.working_capital')</td>
                            @php
                                $debitWkgCap = 0;
                                $creditWkgCap = 0;
                                $debitWkgCap += $currentAssetAccount->closing_balance_side == 'dr' ? $currentAssetAccount->closing_balance : 0;
                                $debitWkgCap += $currentLiabilitiesAccount->closing_balance_side == 'dr' ? $currentLiabilitiesAccount->closing_balance : 0;
                                $creditWkgCap += $currentAssetAccount->closing_balance_side == 'cr' ? $currentAssetAccount->closing_balance : 0;
                                $creditWkgCap += $currentLiabilitiesAccount->closing_balance_side == 'cr' ? $currentLiabilitiesAccount->closing_balance : 0;

                                $wkgBalance = 0;
                                $wkgBalanceSide = 'dr';
                                if ($debitWkgCap > $creditWkgCap) {

                                    $wkgBalance = $debitWkgCap - $creditWkgCap;
                                    $wkgBalanceSide = 'dr';
                                }elseif ($creditWkgCap > $debitWkgCap) {

                                    $wkgBalance = $creditWkgCap - $debitWkgCap;
                                    $wkgBalanceSide = 'cr';
                                }
                            @endphp
                            <td class="fw-bold text-end">{{ \App\Utils\Converter::format_in_bdt($wkgBalance) }} {{ ucfirst($wkgBalanceSide) }}.</td>
                            <td class="fw-bold text-end">{{ $wkgBalanceSide == 'cr' ? '(-)' : '' }} {{ \App\Utils\Converter::format_in_bdt($wkgBalance) }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tfoot>
</table>
