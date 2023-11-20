<table class="w-100">
    <thead>
        <tr>
            <th class="header_text ps-1 text-center">@lang('menu.INFLOW')</th>
            <th class="header_text ps-1 text-center" style="border-left: 1px solid black;">@lang('menu.OUTFLOW')</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td class="inflow_area" style="width: 50%;">
                @if ($capitalAccountCashFlows->cash_in > 0)
                    <table class="capital_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2">
                                    <a href="{{ route('reports.group.cash.flow.index', [$capitalAccountCashFlows->main_group_id, 'in', $fromDate, $toDate]) }}" target="_blank" class="text-black fw-bold">
                                        {{ $capitalAccountCashFlows->main_group_name }}
                                    </a>
                                </strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($capitalAccountCashFlows->groups as $group)
                                            @if ($group->cash_in > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.group.cash.flow.index', [$group->group_id, 'in', $fromDate, $toDate]) }}" target="_blank">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($capitalAccountCashFlows->accounts as $account)
                                            @if ($account->cash_in > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.ledger.cash.flow.index', [$account->account_id, 'in', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($capitalAccountCashFlows->cash_in) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($branchAndDivisionCashFlows->cash_in > 0)
                    <table class="branch_and_division_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a href="{{ route('reports.group.cash.flow.index', [$branchAndDivisionCashFlows->main_group_id, 'in', $fromDate, $toDate]) }}" target="_blank" class="text-black fw-bold">{{ $branchAndDivisionCashFlows->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($branchAndDivisionCashFlows->groups as $group)
                                            @if ($group->cash_in > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.group.cash.flow.index', [$group->group_id, 'in', $fromDate, $toDate]) }}" target="_blank">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($branchAndDivisionCashFlows->accounts as $account)
                                            @if ($account->cash_in > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.ledger.cash.flow.index', [$account->account_id, 'in', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($branchAndDivisionCashFlows->cash_in) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($suspenseAccountCashFlows->cash_in > 0)
                    <table class="suspense_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a href="{{ route('reports.group.cash.flow.index', [$suspenseAccountCashFlows->main_group_id, 'in', $fromDate, $toDate]) }}" target="_blank" class="text-black fw-bold">{{ $suspenseAccountCashFlows->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($suspenseAccountCashFlows->groups as $group)
                                            @if ($group->cash_in > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.group.cash.flow.index', [$group->group_id, 'in', $fromDate, $toDate]) }}" target="_blank">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($suspenseAccountCashFlows->accounts as $account)
                                            @if ($account->cash_in > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.ledger.cash.flow.index', [$account->account_id, 'in', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($suspenseAccountCashFlows->cash_in) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($currentLiabilitiesCashFlows->cash_in > 0)
                    <table class="current_liabilities_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a href="{{ route('reports.group.cash.flow.index', [$currentLiabilitiesCashFlows->main_group_id , 'in', $fromDate, $toDate]) }}" target="_blank" class="text-black fw-bold">{{ $currentLiabilitiesCashFlows->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($currentLiabilitiesCashFlows->groups as $group)
                                            @if ($group->cash_in > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.group.cash.flow.index', [$group->group_id, 'in', $fromDate, $toDate]) }}" target="_blank">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($currentLiabilitiesCashFlows->accounts as $account)
                                            @if ($account->cash_in > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.ledger.cash.flow.index', [$account->account_id, 'in', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($currentLiabilitiesCashFlows->cash_in) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($loanLiabilitiesCashFlows->cash_in > 0)
                    <table class="loan_liabilities_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a href="{{ route('reports.group.cash.flow.index', [$loanLiabilitiesCashFlows->main_group_id, 'in', $fromDate, $toDate]) }}" target="_blank" class="text-black fw-bold">{{ $loanLiabilitiesCashFlows->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($loanLiabilitiesCashFlows->groups as $group)
                                            @if ($group->cash_in > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.group.cash.flow.index', [$group->group_id, 'in', $fromDate, $toDate]) }}" target="_blank">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($loanLiabilitiesCashFlows->accounts as $account)
                                            @if ($account->cash_in > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.ledger.cash.flow.index', [$account->account_id, 'in', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($loanLiabilitiesCashFlows->cash_in) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($currentAssetsCashFlows->cash_in > 0)
                    <table class="current_assets_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a href="{{ route('reports.group.cash.flow.index', [$currentAssetsCashFlows->main_group_id, 'in', $fromDate, $toDate]) }}" target="_blank" class="text-black fw-bold">{{ $currentAssetsCashFlows->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($currentAssetsCashFlows->groups as $group)
                                            @if ($group->cash_in > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.group.cash.flow.index', [$group->group_id, 'in', $fromDate, $toDate]) }}" target="_blank">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($currentAssetsCashFlows->accounts as $account)
                                            @if ($account->cash_in > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.ledger.cash.flow.index', [$account->account_id, 'in', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($currentAssetsCashFlows->cash_in) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($fixedAssetsCashFlows->cash_in > 0)
                    <table class="fixed_assets_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a href="{{ route('reports.group.cash.flow.index', [$fixedAssetsCashFlows->main_group_id, 'in', $fromDate, $toDate]) }}" target="_blank" class="text-black fw-bold">{{ $fixedAssetsCashFlows->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($fixedAssetsCashFlows->groups as $group)
                                            @if ($group->cash_in > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.group.cash.flow.index', [$group->group_id, 'in', $fromDate, $toDate]) }}" target="_blank">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($fixedAssetsCashFlows->accounts as $account)
                                            @if ($account->cash_in > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.ledger.cash.flow.index', [$account->account_id, 'in', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($fixedAssetsCashFlows->cash_in) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($investmentsCashFlows->cash_in > 0)
                    <table class="investments_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a href="{{ route('reports.group.cash.flow.index', [$investmentsCashFlows->main_group_id, 'in', $fromDate, $toDate]) }}" target="_blank" class="text-black fw-bold">{{ $investmentsCashFlows->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($investmentsCashFlows->groups as $group)
                                            @if ($group->cash_in > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.group.cash.flow.index', [$group->group_id, 'in', $fromDate, $toDate]) }}" target="_blank">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($investmentsCashFlows->accounts as $account)
                                            @if ($account->cash_in > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.ledger.cash.flow.index', [$account->account_id, 'in', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($investmentsCashFlows->cash_in) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($directExpenseCashFlows->cash_in > 0)
                    <table class="direct_expense_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a href="{{ route('reports.group.cash.flow.index', [$directExpenseCashFlows->main_group_id, 'in', $fromDate, $toDate]) }}" target="_blank" class="text-black fw-bold">{{ $directExpenseCashFlows->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($directExpenseCashFlows->groups as $group)
                                            @if ($group->cash_in > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.group.cash.flow.index', [$group->group_id, 'in', $fromDate, $toDate]) }}" target="_blank">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($directExpenseCashFlows->accounts as $account)
                                            @if ($account->cash_in > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.ledger.cash.flow.index', [$account->account_id, 'in', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($directExpenseCashFlows->cash_in) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($indirectExpenseCashFlows->cash_in > 0)
                    <table class="indirect_expense_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a href="{{ route('reports.group.cash.flow.index', [$indirectExpenseCashFlows->main_group_id, 'in', $fromDate, $toDate]) }}" target="_blank" class="text-black fw-bold">{{ $indirectExpenseCashFlows->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($indirectExpenseCashFlows->groups as $group)
                                            @if ($group->cash_in > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.group.cash.flow.index', [$group->group_id, 'in', $fromDate, $toDate]) }}" target="_blank">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($indirectExpenseCashFlows->accounts as $account)
                                            @if ($account->cash_in > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.ledger.cash.flow.index', [$account->account_id, 'in', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($indirectExpenseCashFlows->cash_in) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($purchaseCashFlows->cash_in > 0)
                    <table class="indirect_expense_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a href="{{ route('reports.group.cash.flow.index', [$purchaseCashFlows->main_group_id, 'in', $fromDate, $toDate]) }}" target="_blank" class="text-black fw-bold">{{ $purchaseCashFlows->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($purchaseCashFlows->groups as $group)
                                            @if ($group->cash_in > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.group.cash.flow.index', [$group->group_id, 'in', $fromDate, $toDate]) }}" target="_blank">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($purchaseCashFlows->accounts as $account)
                                            @if ($account->cash_in > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.ledger.cash.flow.index', [$account->account_id, 'in', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($purchaseCashFlows->cash_in) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($directIncomeCashFlows->cash_in > 0)
                    <table class="direct_income_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a href="{{ route('reports.group.cash.flow.index', [$directIncomeCashFlows->main_group_id, 'in', $fromDate, $toDate]) }}" target="_blank" class="text-black fw-bold">{{ $directIncomeCashFlows->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($directIncomeCashFlows->groups as $group)
                                            @if ($group->cash_in > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.group.cash.flow.index', [$group->group_id, 'in', $fromDate, $toDate]) }}" target="_blank">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($directIncomeCashFlows->accounts as $account)
                                            @if ($account->cash_in > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.ledger.cash.flow.index', [$account->account_id, 'in', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($directIncomeCashFlows->cash_in) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($indirectIncomeCashFlows->cash_in > 0)
                    <table class="indirect_income_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a href="{{ route('reports.group.cash.flow.index', [$indirectIncomeCashFlows->main_group_id, 'in', $fromDate, $toDate]) }}" target="_blank" class="text-black fw-bold">{{ $indirectIncomeCashFlows->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($indirectIncomeCashFlows->groups as $group)
                                            @if ($group->cash_in > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.group.cash.flow.index', [$group->group_id, 'in', $fromDate, $toDate]) }}" target="_blank">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($indirectIncomeCashFlows->accounts as $account)
                                            @if ($account->cash_in > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.ledger.cash.flow.index', [$account->account_id, 'in', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($indirectIncomeCashFlows->cash_in) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($salesAccountCashFlows->cash_in > 0)
                    <table class="sales_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a href="{{ route('reports.group.cash.flow.index', [$salesAccountCashFlows->main_group_id, 'in', $fromDate, $toDate]) }}" target="_blank" class="text-black fw-bold">{{ $salesAccountCashFlows->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($salesAccountCashFlows->groups as $group)
                                            @if ($group->cash_in > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.group.cash.flow.index', [$group->group_id, 'in', $fromDate, $toDate]) }}" target="_blank">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($group->cash_in) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($salesAccountCashFlows->accounts as $account)
                                            @if ($account->cash_in > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.ledger.cash.flow.index', [$account->account_id, 'in', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_in) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($salesAccountCashFlows->cash_in) }}</strong></td>
                        </tr>
                    </table>
                @endif
            </td>

            <td class="outflow_area">
                @if ($capitalAccountCashFlows->cash_out > 0)
                    <table class="capital_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a href="{{ route('reports.group.cash.flow.index', [$capitalAccountCashFlows->main_group_id, 'out', $fromDate, $toDate]) }}" target="_blank" class="text-black fw-bold">{{ $capitalAccountCashFlows->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($capitalAccountCashFlows->groups as $group)
                                            @if ($group->cash_out > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.group.cash.flow.index', [$group->group_id, 'out', $fromDate, $toDate]) }}" target="_blank">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($capitalAccountCashFlows->accounts as $account)
                                            @if ($account->cash_out > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.ledger.cash.flow.index', [$account->account_id, 'out', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($capitalAccountCashFlows->cash_out) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($branchAndDivisionCashFlows->cash_out > 0)
                    <table class="branch_and_division_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a href="{{ route('reports.group.cash.flow.index', [$branchAndDivisionCashFlows->main_group_id, 'out', $fromDate, $toDate]) }}" target="_blank" class="text-black fw-bold">{{ $branchAndDivisionCashFlows->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($branchAndDivisionCashFlows->groups as $group)
                                            @if ($group->cash_out > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.group.cash.flow.index', [$group->group_id, 'out', $fromDate, $toDate]) }}" target="_blank">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($branchAndDivisionCashFlows->accounts as $account)
                                            @if ($account->cash_out > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.ledger.cash.flow.index', [$account->account_id, 'out', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($branchAndDivisionCashFlows->cash_out) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($suspenseAccountCashFlows->cash_out > 0)
                    <table class="suspense_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a href="{{ route('reports.group.cash.flow.index', [$suspenseAccountCashFlows->main_group_id, 'out', $fromDate, $toDate]) }}" target="_blank" class="text-black fw-bold">{{ $suspenseAccountCashFlows->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($suspenseAccountCashFlows->groups as $group)
                                            @if ($group->cash_out > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.group.cash.flow.index', [$group->group_id, 'out', $fromDate, $toDate]) }}" target="_blank">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($suspenseAccountCashFlows->accounts as $account)
                                            @if ($account->cash_out > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.ledger.cash.flow.index', [$account->account_id, 'out', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($suspenseAccountCashFlows->cash_out) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($currentLiabilitiesCashFlows->cash_out > 0)
                    <table class="current_liabilities_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a href="{{ route('reports.group.cash.flow.index', [$currentLiabilitiesCashFlows->main_group_id, 'out', $fromDate, $toDate]) }}" target="_blank" class="text-black fw-bold">{{ $currentLiabilitiesCashFlows->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($currentLiabilitiesCashFlows->groups as $group)
                                            @if ($group->cash_out > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.group.cash.flow.index', [$group->group_id, 'out', $fromDate, $toDate]) }}" target="_blank">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($currentLiabilitiesCashFlows->accounts as $account)
                                            @if ($account->cash_out > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.ledger.cash.flow.index', [$account->account_id, 'out', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($currentLiabilitiesCashFlows->cash_out) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($loanLiabilitiesCashFlows->cash_out > 0)
                    <table class="loan_liabilities_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a href="{{ route('reports.group.cash.flow.index', [$loanLiabilitiesCashFlows->main_group_id, 'out', $fromDate, $toDate]) }}" target="_blank" class="text-black fw-bold">{{ $loanLiabilitiesCashFlows->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($loanLiabilitiesCashFlows->groups as $group)
                                            @if ($group->cash_out > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.group.cash.flow.index', [$group->group_id, 'out', $fromDate, $toDate]) }}" target="_blank">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($loanLiabilitiesCashFlows->accounts as $account)
                                            @if ($account->cash_out > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.ledger.cash.flow.index', [$account->account_id, 'out', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($loanLiabilitiesCashFlows->cash_out) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($currentAssetsCashFlows->cash_out > 0)
                    <table class="current_assets_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a href="{{ route('reports.group.cash.flow.index', [$currentAssetsCashFlows->main_group_id, 'out', $fromDate, $toDate]) }}" target="_blank" class="text-black fw-bold">{{ $currentAssetsCashFlows->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($currentAssetsCashFlows->groups as $group)
                                            @if ($group->cash_out > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.group.cash.flow.index', [$group->group_id, 'out', $fromDate, $toDate]) }}" target="_blank">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($currentAssetsCashFlows->accounts as $account)
                                            @if ($account->cash_out > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.ledger.cash.flow.index', [$account->account_id, 'out', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($currentAssetsCashFlows->cash_out) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($fixedAssetsCashFlows->cash_out > 0)
                    <table class="fixed_assets_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a href="{{ route('reports.group.cash.flow.index', [$fixedAssetsCashFlows->main_group_id, 'out', $fromDate, $toDate]) }}" target="_blank" class="text-black fw-bold">{{ $fixedAssetsCashFlows->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($fixedAssetsCashFlows->groups as $group)
                                            @if ($group->cash_out > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.group.cash.flow.index', [$group->group_id, 'out', $fromDate, $toDate]) }}" target="_blank">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($fixedAssetsCashFlows->accounts as $account)
                                            @if ($account->cash_out > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.ledger.cash.flow.index', [$account->account_id, 'out', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($fixedAssetsCashFlows->cash_out) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($investmentsCashFlows->cash_out > 0)
                    <table class="investments_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a href="{{ route('reports.group.cash.flow.index', [$investmentsCashFlows->main_group_id, 'out', $fromDate, $toDate]) }}" target="_blank" class="text-black fw-bold">{{ $investmentsCashFlows->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($investmentsCashFlows->groups as $group)
                                            @if ($group->cash_out > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.group.cash.flow.index', [$group->group_id, 'out', $fromDate, $toDate]) }}" target="_blank">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($investmentsCashFlows->accounts as $account)
                                            @if ($account->cash_out > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.ledger.cash.flow.index', [$account->account_id, 'out', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($investmentsCashFlows->cash_out) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($directExpenseCashFlows->cash_out > 0)
                    <table class="direct_expense_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a href="{{ route('reports.group.cash.flow.index', [$directExpenseCashFlows->main_group_id, 'out', $fromDate, $toDate]) }}" target="_blank" class="text-black fw-bold">{{ $directExpenseCashFlows->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($directExpenseCashFlows->groups as $group)
                                            @if ($group->cash_out > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.group.cash.flow.index', [$group->group_id, 'out', $fromDate, $toDate]) }}" target="_blank">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($directExpenseCashFlows->accounts as $account)
                                            @if ($account->cash_out > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.ledger.cash.flow.index', [$account->account_id, 'out', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($directExpenseCashFlows->cash_out) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($indirectExpenseCashFlows->cash_out > 0)
                    <table class="indirect_expense_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a href="{{ route('reports.group.cash.flow.index', [$indirectExpenseCashFlows->main_group_id, 'out', $fromDate, $toDate]) }}" target="_blank" class="text-black fw-bold">{{ $indirectExpenseCashFlows->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($indirectExpenseCashFlows->groups as $group)
                                            @if ($group->cash_out > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.group.cash.flow.index', [$group->group_id, 'out', $fromDate, $toDate]) }}" target="_blank">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($indirectExpenseCashFlows->accounts as $account)
                                            @if ($account->cash_out > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.ledger.cash.flow.index', [$account->account_id, 'out', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($indirectExpenseCashFlows->cash_out) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($purchaseCashFlows->cash_out > 0)
                    <table class="purchase_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a href="{{ route('reports.group.cash.flow.index', [$purchaseCashFlows->main_group_id, 'out', $fromDate, $toDate]) }}" target="_blank" class="text-black fw-bold">{{ $purchaseCashFlows->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($purchaseCashFlows->groups as $group)
                                            @if ($group->cash_out > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.group.cash.flow.index', [$group->group_id, 'out', $fromDate, $toDate]) }}" target="_blank">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($purchaseCashFlows->accounts as $account)
                                            @if ($account->cash_out > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.ledger.cash.flow.index', [$account->account_id, 'out', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($purchaseCashFlows->cash_out) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($directIncomeCashFlows->cash_out > 0)
                    <table class="direct_income_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a href="{{ route('reports.group.cash.flow.index', [$directIncomeCashFlows->main_group_id, 'out', $fromDate, $toDate]) }}" target="_blank" class="text-black fw-bold">{{ $directIncomeCashFlows->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($directIncomeCashFlows->groups as $group)
                                            @if ($group->cash_out > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.group.cash.flow.index', [$group->group_id, 'out', $fromDate, $toDate]) }}" target="_blank">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($directIncomeCashFlows->accounts as $account)
                                            @if ($account->cash_out > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.ledger.cash.flow.index', [$account->account_id, 'out', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($directIncomeCashFlows->cash_out) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($indirectIncomeCashFlows->cash_out > 0)
                    <table class="indirect_income_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a href="{{ route('reports.group.cash.flow.index', [$indirectIncomeCashFlows->main_group_id, 'out', $fromDate, $toDate]) }}" target="_blank" class="text-black fw-bold">{{ $indirectIncomeCashFlows->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($indirectIncomeCashFlows->groups as $group)
                                            @if ($group->cash_out > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.group.cash.flow.index', [$group->group_id, 'out', $fromDate, $toDate]) }}" target="_blank">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($indirectIncomeCashFlows->accounts as $account)
                                            @if ($account->cash_out > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.ledger.cash.flow.index', [$account->account_id, 'out', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($indirectIncomeCashFlows->cash_out) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($salesAccountCashFlows->cash_out > 0)
                    <table class="indirect_income_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2"><a href="{{ route('reports.group.cash.flow.index', [$salesAccountCashFlows->main_group_id, 'out', $fromDate, $toDate]) }}" target="_blank" class="text-black fw-bold">{{ $salesAccountCashFlows->main_group_name }}</a></strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($salesAccountCashFlows->groups as $group)
                                            @if ($group->cash_out > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.group.cash.flow.index', [$group->group_id, 'out', $fromDate, $toDate]) }}" target="_blank">{{ $group->group_name }}-(<span class="text-dark fw-bold">{{ $group->parent_group_name }}-@lang('menu.group')</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($group->cash_out) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($salesAccountCashFlows->accounts as $account)
                                            @if ($account->cash_out > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('reports.ledger.cash.flow.index', [$account->account_id, 'out', $fromDate, $toDate]) }}" target="_blank">{{ $account->account_name }} (@lang('menu.ledger'))</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ \App\Utils\Converter::format_in_bdt($account->cash_out) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($salesAccountCashFlows->cash_out) }}</strong></td>
                        </tr>
                    </table>
                @endif
            </td>
        </tr>
    </tbody>

    <tfoot class="net_total_balance_footer">
        <tr>
            <td class="text-end fw-bold net_debit_total">@lang('menu.total') : {{ \App\Utils\Converter::format_in_bdt($totalIn) }}</td>
            <td class="text-end fw-bold net_credit_total">@lang('menu.total') : {{ \App\Utils\Converter::format_in_bdt($totalOut) }}</td>
        </tr>

        <tr>
            <td class="text-end fw-bold net_debit_total text-info">@lang('menu.net_inflow') : </td>
            <td class="text-start fw-bold net_credit_total ps-1 text-info">
                {{ $balanceSide == 'out' ? '(-)' : '' }}
                {{ \App\Utils\Converter::format_in_bdt($balance) }}</td>
        </tr>
    </tfoot>
</table>
