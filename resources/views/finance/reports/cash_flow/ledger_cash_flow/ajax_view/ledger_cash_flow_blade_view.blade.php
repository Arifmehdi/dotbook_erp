<table class="w-100">
    <thead>
        <tr>
            <th class="header_text ps-1 text-start">@lang('menu.date')</th>
            <th class="header_text ps-1 text-start">@lang('menu.particulars')</th>
            <th class="header_text ps-1 text-start">@lang('menu.voucher_type')</th>
            <th class="header_text ps-1 text-start">@lang('menu.voucher_no')</th>
            <th class="header_text ps-1 text-end">@lang('menu.debit')</th>
            <th class="header_text ps-1 text-end">@lang('menu.credit')</th>
        </tr>
    </thead>

    <tbody>
        @php
            $totalDebit = 0;
            $totalCredit = 0;
        @endphp
        @foreach ($ledgerCashflow as $row)
            @php
                $totalDebit += $row->debit;
                $totalCredit += $row->credit;
            @endphp
            <tr class="group_tr" style="border-bottom:1px solid rgb(218, 210, 210);">
                <td class="fw-bold">
                    @php
                        $dateFormat = json_decode($generalSettings->business, true)['date_format'];
                        $__date_format = str_replace('-', '/', $dateFormat);
                    @endphp

                    {{ $row->date ? date($__date_format, strtotime($row->date)) : '' }}
                </td>
                <td class="text-start">
                    @php
                        $voucherType = $row->voucher_type;
                        $ledgerParticularsUtil = new \App\Utils\LedgerParticularsUtil();
                    @endphp
                    {!! $ledgerParticularsUtil->particulars($request, $row->voucher_type, $row, $by) !!}
                </td>
                <td class="text-start fw-bold">
                    @php
                        $accountLedgerUtil = new \App\Utils\AccountLedgerUtil();
                        $type = $accountLedgerUtil->voucherType($row->voucher_type);
                    @endphp
                    {!! $row->voucher_type != 0 ? $type['name'] : '' !!}
                </td>
                <td class="text-start"><a href="{{ (!empty($type['link']) ? route($type['link'], $row->{$type['details_id']}) : '#') }}" id="details_btn" class="fw-bold">{!! $row->{$type['voucher_no']} !!}</a></td>
                <td class="text-end fw-bold">{{ $row->debit > 0 ? \App\Utils\Converter::format_in_bdt($row->debit) : '' }}</td>
                <td class="text-end fw-bold">{{ $row->credit > 0 ? \App\Utils\Converter::format_in_bdt($row->credit) : '' }}</td>
            </tr>
        @endforeach
    </tbody>

    <tfoot class="net_total_balance_footer">
        <tr>
            <td colspan="4" class="text-end fw-bold">@lang('menu.current_total') :</td>
            <td class="text-end fw-bold net_debit_total">{{ $totalDebit > 0 ? \App\Utils\Converter::format_in_bdt($totalDebit) : '' }}</td>
            <td class="text-end fw-bold net_credit_total">{{ $totalCredit > 0 ? \App\Utils\Converter::format_in_bdt($totalCredit) : '' }}</td>
        </tr>
    </tfoot>
</table>
