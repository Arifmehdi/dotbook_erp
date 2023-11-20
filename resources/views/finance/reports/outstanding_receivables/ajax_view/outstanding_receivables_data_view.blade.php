@php
    $__date_format = json_decode($generalSettings->business, true)['date_format'];
@endphp
<table class="w-100 selectable">
    <thead>
        <tr>
            <th class="header_text text-start ps-1">@lang('menu.period')</th>
            <th class="header_text ps-1">@lang('menu.account_name')</th>
            <th class="header_text ps-1">@lang('menu.group')</th>
            <th class="header_text pe-2 text-end">@lang('menu.pending_amount')</th>
        </tr>
    </thead>

    <tbody>
        @php
            $totalPendingAmount = 0;
        @endphp
        @foreach ($receivables as $receivable)
            @php
                $openingBalanceDebit = isset($receivable->opening_total_debit) ? (float)$receivable->opening_total_debit : 0;
                $openingBalanceCredit = isset($receivable->opening_total_credit) ? (float)$receivable->opening_total_credit : 0;

                $CurrTotalDebit = (float)$receivable->curr_total_debit;
                $CurrTotalCredit = (float)$receivable->curr_total_credit;

                $currOpeningBalance = 0;
                $currOpeningBalanceSide = $receivable->sub_sub_group_number == 6 ? 'dr' : 'cr';

                if ($openingBalanceDebit > $openingBalanceCredit) {

                    $currOpeningBalance = $openingBalanceDebit - $openingBalanceCredit;
                    $currOpeningBalanceSide = 'dr';
                } elseif ($openingBalanceCredit > $openingBalanceDebit) {

                    $currOpeningBalance = $openingBalanceCredit - $openingBalanceDebit;
                    $currOpeningBalanceSide = 'cr';
                }

                $CurrTotalDebit += $currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0;
                $CurrTotalCredit += $currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0;

                $closingBalance = 0;
                $closingBalanceSide = $receivable->sub_sub_group_number == 6 ? 'dr' : 'cr';
                if ($CurrTotalDebit > $CurrTotalCredit) {

                    $closingBalance = $CurrTotalDebit - $CurrTotalCredit;
                    $closingBalanceSide = 'dr';
                } elseif ($CurrTotalCredit > $CurrTotalDebit) {

                    $closingBalance = $CurrTotalCredit - $CurrTotalDebit;
                    $closingBalanceSide = 'cr';
                }
            @endphp

            @if ($closingBalanceSide == 'dr' && $closingBalance > 0)
                @php
                    $totalPendingAmount += $closingBalance;
                @endphp
                <tr class="account_list">
                    <td class="text-start ps-1">
                         @if ($fromDate && $toDate)
                            <b>{!! date($__date_format, strtotime($fromDate)) . ' <strong>To</strong> ' . date($__date_format, strtotime($toDate)) !!}</b>
                        @else
                            @php
                                $fromDate = date($__date_format, strtotime($accountStartDate));
                                $toDate = date($__date_format);
                            @endphp
                            {!! date($__date_format, strtotime($accountStartDate)) . ' <strong>To</strong> ' . date($__date_format) !!}
                        @endif
                    </td>
                    <td class="text-start ps-1">
                        <a target="_blank" href="{{ route('accounting.accounts.ledger', [$receivable->account_id, 'accountId', ($fromDate ?? NULL), ($toDate ?? NULL), $receivable?->u_id]) }}">{{ $receivable->account_name }}</a>
                        {!! $receivable?->u_name ? ' - <strong>Sr.</strong> ' . $receivable?->u_prefix .' '. $receivable?->u_name .' '. $receivable?->u_last_name : '' !!}</td>
                    <td class="text-start ps-1">{{ $receivable->group_name }}</td>
                    <td class="text-end fw-bold pe-2">{{ \App\Utils\Converter::format_in_bdt($closingBalance) }}</td>
                </tr>
            @endif
        @endforeach
    </tbody>

    <tfoot  class="net_total_balance_footer">
        <tr>
            <td colspan="3" class="footer_total pe-1 text-end fw-bold">@lang('menu.total') :</td>
            <td class="footer_total_amount fw-bold pe-2 text-end">{{ \App\Utils\Converter::format_in_bdt($totalPendingAmount) }}</td>
        </tr>
    </tfoot>
</table>
