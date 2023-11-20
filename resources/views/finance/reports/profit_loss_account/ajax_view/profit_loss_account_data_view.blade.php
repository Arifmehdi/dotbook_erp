<table class="w-100">
    <thead>
        <tr>
            <th class="header_text ps-1">@lang('menu.particulars')</th>
            <th class="header_text ps-1" style="border-left: 1px solid black;">@lang('menu.particulars')</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td class="debit_area" style="width: 50%;">
                <table class="opening_stock_account_group_table w-100 mt-1">
                    <tr>
                        <td><strong class="ps-2">@lang('menu.opening_stock')</strong></td>
                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($openingStock) }}</strong></td>
                    </tr>
                </table>

                @if ($purchaseAccountBalance['groupClosingBalanceSide'] == 'dr' && $purchaseAccountBalance['groupClosingBalance'] > 0)
                    <table class="purchase_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2">{{ $purchaseAccountBalance['groupName'] }}</strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($purchaseAccountBalance['results'] as $res)
                                            @if ($res->account_id && $res->closing_balance > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1">
                                                        <a href="{{ route('accounting.accounts.ledger', [$res->account_id, 'accountId', $fromDate, $toDate]) }}" target="blank">{{ $res->account_name }}-(<span class="text-dark fw-bold">{{ $res->group_name }}</span>)</a>
                                                    </td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $res->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($res->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($purchaseAccountBalance['groupClosingBalance']) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($salesAccountBalance['groupClosingBalanceSide'] == 'dr' && $salesAccountBalance['groupClosingBalance'] > 0)
                    <table class="sales_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2">{{ $salesAccountBalance['groupName'] }}</strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($salesAccountBalance['results'] as $res)
                                            @if ($res->account_id && $res->closing_balance > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('accounting.accounts.ledger', [$res->account_id, 'accountId', $fromDate, $toDate]) }}" target="_blank">{{ $res->account_name }}-(<span class="text-dark fw-bold">{{ $res->group_name }}</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $res->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($res->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($salesAccountBalance['groupClosingBalance']) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($directExpenseAccountBalance['groupClosingBalanceSide'] == 'dr' && $directExpenseAccountBalance['groupClosingBalance'] > 0)
                    <table class="direct_expense_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2">{{ $directExpenseAccountBalance['groupName'] }}</strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($directExpenseAccountBalance['results'] as $res)
                                            @if ($res->account_id && $res->closing_balance > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('accounting.accounts.ledger', [$res->account_id, 'accountId', $fromDate, $toDate]) }}" target="_blank">{{ $res->account_name }}-(<span class="text-dark fw-bold">{{ $res->group_name }}</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $res->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($res->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($directExpenseAccountBalance['groupClosingBalance']) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($directIncomesAccountBalance['groupClosingBalanceSide'] == 'dr' && $directIncomesAccountBalance['groupClosingBalance'] > 0)
                    <table class="direct_income_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2">{{ $directIncomesAccountBalance['groupName'] }}</strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($directIncomesAccountBalance['results'] as $res)
                                            @if ($res->account_id && $res->closing_balance > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('accounting.accounts.ledger', [$res->account_id, 'accountId', $fromDate, $toDate]) }}" target="_blank">{{ $res->account_name }}-(<span class="text-dark fw-bold">{{ $res->group_name }}</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $res->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($res->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($directIncomesAccountBalance['groupClosingBalance']) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($grossAmountOfDebit > $grossAmountOfCredit)
                    <table class="gross_total_balance w-100 mt-1">
                        <tr>
                            <td><strong class="ps-2"></strong></td>
                            <td class="text-end"><strong>@lang('menu.total') : {{ \App\Utils\Converter::format_in_bdt($grossAmountOfDebit) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($grossProfitOrLossSide == 'cr')
                    <table class="gross_profit_account_group_table w-100 mt-1">
                        <tr>
                            <td><strong class="ps-2">@lang('menu.gross_profit_co')</strong></td>
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($grossProfitOrLoss) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($grossProfitOrLossSide == 'dr')
                    <table class="gross_loss_table w-100 mt-1">
                        <tr>
                            <td><strong class="ps-2">@lang('menu.gross_profit_bf')</strong></td>
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($grossProfitOrLoss) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($grossAmountOfCredit > $grossAmountOfDebit)
                    <table class="gross_total_balance w-100 mt-1">
                        <tr>
                            <td><strong class="ps-2"></strong></td>
                            <td class="text-end"><strong>@lang('menu.total') : {{ \App\Utils\Converter::format_in_bdt($grossAmountOfCredit) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($indirectExpenseAccountBalance['groupClosingBalanceSide'] == 'dr' && $indirectExpenseAccountBalance['groupClosingBalance'] > 0)
                    <table class="indirect_expense_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2">{{ $indirectExpenseAccountBalance['groupName'] }}</strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($indirectExpenseAccountBalance['results'] as $res)
                                            @if ($res->account_id && $res->closing_balance > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('accounting.accounts.ledger', [$res->account_id, 'accountId', $fromDate, $toDate]) }}" target="_blank">{{ $res->account_name }}</a>-(<span class="text-dark fw-bold">{{ $res->group_name }}</span>)</td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $res->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($res->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($indirectExpenseAccountBalance['groupClosingBalance']) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($indirectIncomesAccountBalance['groupClosingBalanceSide'] == 'dr' && $indirectIncomesAccountBalance['groupClosingBalance'] > 0)
                    <table class="indirect_income_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2">{{ $indirectIncomesAccountBalance['groupName'] }}</strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($indirectIncomesAccountBalance['results'] as $res)
                                            @if ($res->account_id && $res->closing_balance > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('accounting.accounts.ledger', [$res->account_id, 'accountId', $fromDate, $toDate]) }}" target="_blank">{{ $res->account_name }}-(<span class="text-dark fw-bold">{{ $res->group_name }}</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $res->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($res->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($indirectIncomesAccountBalance['groupClosingBalance']) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($netProfitLossSide == 'cr')
                    <table class="net_loss_account_group_table w-100">
                        <tr>
                            <td><strong class="ps-2">@lang('menu.net_profit')</strong></td>
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($netProfit) }}</strong></td>
                        </tr>
                    </table>
                @endif
            </td>

            <td class="credit_area">
                @if ($purchaseAccountBalance['groupClosingBalanceSide'] == 'cr' && $purchaseAccountBalance['groupClosingBalance'] > 0)
                    <table class="purchase_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2">{{ $purchaseAccountBalance['groupName'] }}</strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($purchaseAccountBalance['results'] as $res)
                                            @if ($res->account_id && $res->closing_balance > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('accounting.accounts.ledger', [$res->account_id, 'accountId', $fromDate, $toDate]) }}" target="_blank">{{ $res->account_name }}-(<span class="text-dark fw-bold">{{ $res->group_name }}</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $res->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($res->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($purchaseAccountBalance['groupClosingBalance']) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($salesAccountBalance['groupClosingBalanceSide'] == 'cr' && $salesAccountBalance['groupClosingBalance'] > 0)
                    <table class="sales_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2">{{ $salesAccountBalance['groupName'] }}</strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($salesAccountBalance['results'] as $res)
                                            @if ($res->account_id && $res->closing_balance > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('accounting.accounts.ledger', [$res->account_id, 'accountId', $fromDate, $toDate]) }}" target="_blank">{{ $res->account_name }}-(<span class="text-dark fw-bold">{{ $res->group_name }}</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $res->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($res->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($salesAccountBalance['groupClosingBalance']) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($directIncomesAccountBalance['groupClosingBalanceSide'] == 'cr' && $directIncomesAccountBalance['groupClosingBalance'] > 0)
                    <table class="direct_income_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2">{{ $directIncomesAccountBalance['groupName'] }}</strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($directIncomesAccountBalance['results'] as $res)
                                            @if ($res->account_id && $res->closing_balance > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('accounting.accounts.ledger', [$res->account_id, 'accountId', $fromDate, $toDate]) }}" target="_blank">{{ $res->account_name }}-(<span class="text-dark fw-bold">{{ $res->group_name }}</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $res->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($res->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($directIncomesAccountBalance['groupClosingBalance']) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($directExpenseAccountBalance['groupClosingBalanceSide'] == 'cr' && $directExpenseAccountBalance['groupClosingBalance'] > 0)
                    <table class="direct_expense_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2">{{ $directExpenseAccountBalance['groupName'] }}</strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($directExpenseAccountBalance['results'] as $res)
                                            @if ($res->account_id && $res->closing_balance > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('accounting.accounts.ledger', [$res->account_id, 'accountId', $fromDate, $toDate]) }}" target="_blank">{{ $res->account_name }}-(<span class="text-dark fw-bold">{{ $res->group_name }}</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $res->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($res->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($directExpenseAccountBalance['groupClosingBalance']) }}</strong></td>
                        </tr>
                    </table>
                @endif

                <table class="closing_stock_account_group_table w-100 mt-1">
                    <tr>
                        <td><strong class="ps-2">@lang('menu.closing_stock')</strong></td>
                        <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($closingStock) }}</strong></td>
                    </tr>
                </table>

                @if ($grossAmountOfCredit > $grossAmountOfDebit)
                    <table class="gross_total_balance w-100 mt-1">
                        <tr>
                            <td><strong class="ps-2"></strong></td>
                            <td class="text-end"><strong>@lang('menu.total') : {{ \App\Utils\Converter::format_in_bdt($grossAmountOfCredit) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($grossProfitOrLossSide == 'dr')
                    <table class="gross_loss_table w-100 mt-1">
                        <tr>
                            <td><strong class="ps-2">@lang('menu.gross_profit_co')</strong></td>
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($grossProfitOrLoss) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($grossProfitOrLossSide == 'cr')
                    <table class="gross_profit_account_group_table w-100 mt-1">
                        <tr>
                            <td><strong class="ps-2">@lang('menu.gross_profit_bf')</strong></td>
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($grossProfitOrLoss) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($grossAmountOfDebit > $grossAmountOfCredit)
                    <table class="gross_total_balance w-100 mt-1">
                        <tr>
                            <td><strong class="ps-2"></strong></td>
                            <td class="text-end"><strong>@lang('menu.total') : {{ \App\Utils\Converter::format_in_bdt($grossAmountOfDebit) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($indirectExpenseAccountBalance['groupClosingBalanceSide'] == 'cr' && $indirectExpenseAccountBalance['groupClosingBalance'] > 0)
                    <table class="indirect_expense_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2">{{ $indirectExpenseAccountBalance['groupName'] }}</strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($indirectExpenseAccountBalance['results'] as $res)
                                            @if ($res->account_id && $res->closing_balance > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('accounting.accounts.ledger', [$res->account_id, 'accountId', $fromDate, $toDate]) }}" target="_blank">{{ $res->account_name }}-(<span class="text-dark fw-bold">{{ $res->group_name }}</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $res->closing_balance_side == 'cr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($res->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($indirectExpenseAccountBalance['groupClosingBalance']) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($indirectIncomesAccountBalance['groupClosingBalanceSide'] == 'cr' && $indirectIncomesAccountBalance['groupClosingBalance'] > 0)
                    <table class="indirect_income_account_group_table w-100 mt-1">
                        <tr>
                            <td class="first_td">
                                <strong class="ps-2">{{ $indirectIncomesAccountBalance['groupName'] }}</strong>
                                @if ($formatOfReport == 'detailed')
                                    <table class="group_account_table ms-2">
                                        @foreach ($indirectIncomesAccountBalance['results'] as $res)
                                            @if ($res->account_id && $res->closing_balance > 0)
                                                <tr>
                                                    <td class="group_account_name ps-1"><a href="{{ route('accounting.accounts.ledger', [$res->account_id, 'accountId', $fromDate, $toDate]) }}" target="_blank">{{ $res->account_name }}-(<span class="text-dark fw-bold">{{ $res->group_name }}</span>)</a></td>
                                                    <td class="group_account_balance text-end">
                                                        {{ $res->closing_balance_side == 'dr' ? '(-)' : '' }}
                                                        {{ \App\Utils\Converter::format_in_bdt($res->closing_balance) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @endif
                            </td>

                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($indirectIncomesAccountBalance['groupClosingBalance']) }}</strong></td>
                        </tr>
                    </table>
                @endif

                @if ($netProfitLossSide == 'dr')
                    <table class="net_loss_account_group_table w-100">
                        <tr>
                            <td><strong class="ps-2">@lang('menu.net_loss')</strong></td>
                            <td class="text-end"><strong>{{ \App\Utils\Converter::format_in_bdt($netLoss) }}</strong></td>
                        </tr>
                    </table>
                @endif
            </td>
        </tr>
    </tbody>
    <tfoot class="net_total_balance_footer">
        <tr>
            <td class="text-end fw-bold net_debit_total">@lang('menu.total') : {{ \App\Utils\Converter::format_in_bdt($totalNetAmountBothSide) }}</td>
            <td class="text-end fw-bold net_credit_total">@lang('menu.total') : {{ \App\Utils\Converter::format_in_bdt($totalNetAmountBothSide) }}</td>
        </tr>
    </tfoot>
</table>
