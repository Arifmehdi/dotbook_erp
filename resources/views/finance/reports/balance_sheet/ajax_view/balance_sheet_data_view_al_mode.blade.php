<table class="w-100">
    <thead>
        <tr>
            <th class="header_text ps-1">@lang('menu.assets')</th>
            <th class="header_text ps-1" style="border-left: 1px solid black;">@lang('menu.liabilities')</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td class="debit_area" style="width: 50%;">
                @if ($capitalAccount->closing_balance_side == 'dr')
                    <table class="capital_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a target="_blank" href="{{ route('reports.group.summary.index', [$capitalAccount->main_group_id, $fromDate, $toDate]) }}" class="text-black">{{ $capitalAccount->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($capitalAccount->groups as $group)
                                            @if ($group->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a target="_blank" href="{{ route('reports.group.summary.index', [$group->group_id, $fromDate, $toDate]) }}">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
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

                @if ($currentLiabilitiesAccount->closing_balance_side == 'dr')
                    <table class="current_liabilities_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a target="_blank" href="{{ route('reports.group.summary.index', [$currentLiabilitiesAccount->main_group_id, $fromDate, $toDate]) }}" class="text-black">{{ $currentLiabilitiesAccount->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($currentLiabilitiesAccount->groups as $group)
                                            @if ($group->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a target="_blank" href="{{ route('reports.group.summary.index', [$group->group_id, $fromDate, $toDate]) }}">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $group->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($currentLiabilitiesAccount->accounts as $account)
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
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($currentLiabilitiesAccount->closing_balance) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($loanLiabilitiesAccount->closing_balance_side == 'dr')
                    <table class="loan_liabilities_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a target="_blank" href="{{ route('reports.group.summary.index', [$loanLiabilitiesAccount->main_group_id, $fromDate, $toDate]) }}" class="text-black">{{ $loanLiabilitiesAccount->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($loanLiabilitiesAccount->groups as $group)
                                            @if ($group->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a target="_blank" href="{{ route('reports.group.summary.index', [$group->group_id, $fromDate, $toDate]) }}">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
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

                @if ($branchAndDivisionsAccountBalance->closing_balance_side == 'dr')
                    <table class="loan_liabilities_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a target="_blank" href="{{ route('reports.group.summary.index', [$branchAndDivisionsAccountBalance->main_group_id, $fromDate, $toDate]) }}" class="text-black">{{ $branchAndDivisionsAccountBalance->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($branchAndDivisionsAccountBalance->groups as $group)
                                            @if ($group->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a target="_blank" href="{{ route('reports.group.summary.index', [$group->group_id, $fromDate, $toDate]) }}">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $group->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($branchAndDivisionsAccountBalance->accounts as $account)
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

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($branchAndDivisionsAccountBalance->closing_balance) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($suspenseAccountBalance->closing_balance_side == 'dr')
                    <table class="loan_liabilities_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a target="_blank" href="{{ route('reports.group.summary.index', [$suspenseAccountBalance->main_group_id, $fromDate, $toDate]) }}" class="text-black">{{ $suspenseAccountBalance->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($suspenseAccountBalance->groups as $group)
                                            @if ($group->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a target="_blank" href="{{ route('reports.group.summary.index', [$group->group_id, $fromDate, $toDate]) }}">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $group->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($suspenseAccountBalance->accounts as $account)
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

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($suspenseAccountBalance->closing_balance) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($fixedAssetsAccount->closing_balance_side == 'dr')
                    <table class="fixed_assets_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a target="_blank" href="{{ route('reports.group.summary.index', [$fixedAssetsAccount->main_group_id, $fromDate, $toDate]) }}" class="text-black">{{ $fixedAssetsAccount->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($fixedAssetsAccount->groups as $group)
                                            @if ($group->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a target="_blank" href="{{ route('reports.group.summary.index', [$group->group_id, $fromDate, $toDate]) }}">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $group->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($fixedAssetsAccount->accounts as $account)
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

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($fixedAssetsAccount->closing_balance) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($investmentsAccount->closing_balance_side == 'dr')
                    <table class="investments_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a target="_blank" href="{{ route('reports.group.summary.index', [$investmentsAccount->main_group_id, $fromDate, $toDate]) }}" class="text-black">{{ $investmentsAccount->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($investmentsAccount->groups as $group)
                                            @if ($group->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a target="_blank" href="{{ route('reports.group.summary.index', [$group->group_id, $fromDate, $toDate]) }}">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $group->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($investmentsAccount->accounts as $account)
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

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($investmentsAccount->closing_balance) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($currentAssetAccount['currentAssetsAccounts']->closing_balance_side == 'dr')
                    <table class="current_assets_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a target="_blank" href="{{ route('reports.group.summary.index', [$currentAssetAccount['currentAssetsAccounts']->main_group_id, $fromDate, $toDate]) }}" class="text-black">{{ $currentAssetAccount['currentAssetsAccounts']->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        <tr>
                                            <td class="group_account_name ps-1"><a href="#">@lang('menu.closing_stock') (@lang('menu.group'))</a></td>
                                            <td class="group_account_balance text-end">
                                                {{ \App\Utils\Converter::format_in_bdt($currentAssetAccount['closingStock']) }}
                                            </td>
                                        </tr>
                                        @foreach ($currentAssetAccount['currentAssetsAccounts']->groups as $group)
                                            @if ($group->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a target="_blank" href="{{ route('reports.group.summary.index', [$group->group_id, $fromDate, $toDate]) }}">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $group->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($currentAssetAccount['currentAssetsAccounts']->accounts as $account)
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

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($currentAssetAccount['currentAssetsAccounts']->closing_balance) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($profitLossAccount['net_profit_loss_side'] == 'dr')
                    <table class="profit_loss_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a target="_black" href="{{ route('reports.profit.loss.account.index', [$fromDate, $toDate]) }}" class="text-black">{{ $profitLossAccount['account_name'] }}</a></strong>
                                <table class="group_account_table ms-2">
                                    <tr>
                                        <td class="group_account_name ps-1">@lang('menu.opening_balance')</td>
                                        <td class="group_account_balance text-end">
                                            @if ($profitLossAccount['ac_opening_balance'] > 0)
                                                {{ $profitLossAccount['ac_opening_balance_side'] == 'cr' ? '(-)' : '' }}
                                                {{ \App\Utils\Converter::format_in_bdt($profitLossAccount['ac_opening_balance']) }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="group_account_name ps-1">@lang('menu.current_period')</td>
                                        <td class="group_account_balance text-end">
                                            @if ($profitLossAccount['previous_profit_loss'] > 0)
                                                {{ $profitLossAccount['previous_profit_loss_side'] == 'cr' ? '(-)' : '' }}
                                                {{ \App\Utils\Converter::format_in_bdt($profitLossAccount['previous_profit_loss']) }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="group_account_name ps-1">@lang('menu.less_transferred')</td>
                                        <td class="group_account_balance text-end">
                                            @if ($profitLossAccount['ac_transferred_balance'] > 0)
                                                {{ $profitLossAccount['ac_transferred_balance_side'] == 'cr' ? '(-)' : '' }}
                                                {{ \App\Utils\Converter::format_in_bdt($profitLossAccount['ac_transferred_balance']) }}
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($profitLossAccount['net_profit_loss']) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($differencesInOpeningBalanceSide == 'cr')
                    <table class="differences_in_opening_balance_table w-100 mt-1">
                        <tr>
                            <td>
                                <strong class="ps-2 text-end">@lang('menu.differenceInOpeningBalance') :</strong>
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($differenceInOpeningBalance) }}</strong></td>
                        </tr>
                    </table>
                @endif
            </td>

            <td class="credit_area" style="border-left: 1px solid #000;">
                @if ($capitalAccount->closing_balance_side == 'cr')
                    <table class="capital_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a target="_blank" href="{{ route('reports.group.summary.index', [$capitalAccount->main_group_id, $fromDate, $toDate]) }}" class="text-black">{{ $capitalAccount->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($capitalAccount->groups as $group)
                                            @if ($group->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a target="_blank" href="{{ route('reports.group.summary.index', [$group->group_id, $fromDate, $toDate]) }}">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
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

                @if ($currentLiabilitiesAccount->closing_balance_side == 'cr')
                    <table class="current_liabilities_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a target="_blank" href="{{ route('reports.group.summary.index', [$currentLiabilitiesAccount->main_group_id, $fromDate, $toDate]) }}" class="text-black">{{ $currentLiabilitiesAccount->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($currentLiabilitiesAccount->groups as $group)
                                            @if ($group->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a target="_blank" href="{{ route('reports.group.summary.index', [$group->group_id, $fromDate, $toDate]) }}">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $group->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($currentLiabilitiesAccount->accounts as $account)
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
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($currentLiabilitiesAccount->closing_balance) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($loanLiabilitiesAccount->closing_balance_side == 'cr')
                    <table class="loan_liabilities_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a target="_blank" href="{{ route('reports.group.summary.index', [$loanLiabilitiesAccount->main_group_id, $fromDate, $toDate]) }}" class="text-black">{{ $loanLiabilitiesAccount->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($loanLiabilitiesAccount->groups as $group)
                                            @if ($group->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a target="_blank" href="{{ route('reports.group.summary.index', [$group->group_id, $fromDate, $toDate]) }}">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
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

                @if ($branchAndDivisionsAccountBalance->closing_balance_side == 'cr')
                    <table class="loan_liabilities_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a target="_blank" href="{{ route('reports.group.summary.index', [$branchAndDivisionsAccountBalance->main_group_id, $fromDate, $toDate]) }}" class="text-black">{{ $branchAndDivisionsAccountBalance->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($branchAndDivisionsAccountBalance->groups as $group)
                                            @if ($group->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a target="_blank" href="{{ route('reports.group.summary.index', [$group->group_id, $fromDate, $toDate]) }}">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $group->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($branchAndDivisionsAccountBalance->accounts as $account)
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

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($branchAndDivisionsAccountBalance->closing_balance) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($suspenseAccountBalance->closing_balance_side == 'cr')
                    <table class="loan_liabilities_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a target="_blank" href="{{ route('reports.group.summary.index', [$suspenseAccountBalance->main_group_id, $fromDate, $toDate]) }}" class="text-black">{{ $suspenseAccountBalance->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($suspenseAccountBalance->groups as $group)
                                            @if ($group->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a target="_blank" href="{{ route('reports.group.summary.index', [$group->group_id, $fromDate, $toDate]) }}">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $group->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($suspenseAccountBalance->accounts as $account)
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

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($suspenseAccountBalance->closing_balance) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($currentAssetAccount['currentAssetsAccounts']->closing_balance_side == 'cr')
                    <table class="current_assets_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a target="_blank" href="{{ route('reports.group.summary.index', [$currentAssetAccount['currentAssetsAccounts']->main_group_id, $fromDate, $toDate]) }}" class="text-black">{{ $currentAssetAccount['currentAssetsAccounts']->main_group_name }}</a></strong>

                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        <tr>
                                            <td class="group_account_name ps-1"><a href="#">@lang('menu.closing_stock') (@lang('menu.group'))</a></td>
                                            <td class="group_account_balance text-end">
                                                (-) {{ \App\Utils\Converter::format_in_bdt($currentAssetAccount['closingStock']) }}
                                            </td>
                                        </tr>
                                        @foreach ($currentAssetAccount['currentAssetsAccounts']->groups as $group)
                                            @if ($group->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a target="_blank" href="{{ route('reports.group.summary.index', [$group->group_id, $fromDate, $toDate]) }}">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $group->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($currentAssetAccount['currentAssetsAccounts']->accounts as $account)
                                            @if ($account->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a target="_blank" href="{{ route('accounting.accounts.ledger', [$account->account_id, 'accountId', $fromDate, $toDate]) }}">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $group->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($account->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($currentAssetAccount['currentAssetsAccounts']->closing_balance) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($investmentsAccount->closing_balance_side == 'cr')
                    <table class="investments_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a target="_blank" href="{{ route('reports.group.summary.index', [$investmentsAccount->main_group_id, $fromDate, $toDate]) }}" class="text-black">{{ $investmentsAccount->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($investmentsAccount->groups as $group)
                                            @if ($group->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a target="_blank" href="{{ route('reports.group.summary.index', [$group->group_id, $fromDate, $toDate]) }}">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $group->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($investmentsAccount->accounts as $account)
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

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($investmentsAccount->closing_balance) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($fixedAssetsAccount->closing_balance_side == 'cr')
                    <table class="fixed_assets_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a target="_blank" href="{{ route('reports.group.summary.index', [$fixedAssetsAccount->main_group_id, $fromDate, $toDate]) }}" class="text-black">{{ $fixedAssetsAccount->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($fixedAssetsAccount->groups as $group)
                                            @if ($group->closing_balance)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a target="_blank" href="{{ route('reports.group.summary.index', [$group->group_id, $fromDate, $toDate]) }}">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $group->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($group->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($fixedAssetsAccount->accounts as $account)
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

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($fixedAssetsAccount->closing_balance) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($profitLossAccount['net_profit_loss_side'] == 'cr')
                    <table class="profit_loss_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a target="_black" href="{{ route('reports.profit.loss.account.index', [$fromDate, $toDate]) }}" class="text-black">{{ $profitLossAccount['account_name'] }}</a></strong>
                                <table class="group_account_table ms-2">
                                    <tr>
                                        <td class="group_account_name ps-1">@lang('menu.opening_balance')</td>
                                        <td class="group_account_balance text-end">
                                            @if ($profitLossAccount['ac_opening_balance'] > 0)
                                                {{ $profitLossAccount['ac_opening_balance_side'] == 'dr' ? '(-)' : '' }}
                                                {{ \App\Utils\Converter::format_in_bdt($profitLossAccount['ac_opening_balance']) }}
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="group_account_name ps-1">@lang('menu.current_period')</td>
                                        <td class="group_account_balance text-end">
                                            @if ($profitLossAccount['previous_profit_loss'] > 0)
                                                {{ $profitLossAccount['previous_profit_loss_side'] == 'dr' ? '(-)' : '' }}
                                                {{ \App\Utils\Converter::format_in_bdt($profitLossAccount['previous_profit_loss']) }}
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="group_account_name ps-1">@lang('menu.less_transferred')</td>
                                        <td class="group_account_balance text-end">
                                            @if ($profitLossAccount['ac_transferred_balance'] > 0)
                                                {{ $profitLossAccount['ac_transferred_balance_side'] == 'dr' ? '(-)' : '' }}
                                                {{ \App\Utils\Converter::format_in_bdt($profitLossAccount['ac_transferred_balance']) }}
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($profitLossAccount['net_profit_loss']) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($differencesInOpeningBalanceSide == 'dr')
                    <table class="differences_in_opening_balance_table w-100 mt-1">
                        <tr>
                            <td>
                                <strong class="ps-2 text-end">@lang('menu.differenceInOpeningBalance') :</strong>
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($differenceInOpeningBalance) }}</strong></td>
                        </tr>
                    </table>
                @endif
            </td>
        </tr>
    </tbody>

    <tfoot class="net_total_balance_footer">
        <tr>
            <td class="text-end fw-bold net_debit_total">@lang('menu.total') : {{ \App\Utils\Converter::format_in_bdt($totalDebit) }} </td>
            <td class="text-end fw-bold net_credit_total">@lang('menu.total') : {{ \App\Utils\Converter::format_in_bdt($totalCredit) }}</td>
        </tr>
    </tfoot>
</table>
