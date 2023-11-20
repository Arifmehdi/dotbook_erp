
<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 35px; margin-left: 5px;margin-right: 5px;}
    div#footer {position:fixed;bottom:24px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
    .print_table th { font-size:11px!important; font-weight: 550!important;}
    .print_table td { font-size:10px!important; color: #000;}
    /* tr.main_tr { border-bottom: 1pt solid black!important;} */
    td.main_td { border-bottom: 1px solid black!important;}
</style>

<div class="heading_area" style="border-bottom: 2px solid black;">
    <div class="row">
        <div class="col-4">
            <img style="height: auto; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
        </div>

        <div class="col-8 text-end">
            <p style="text-transform: uppercase; font-size: 14px;"><strong>{{ json_decode($generalSettings->business, true)['shop_name'] }}</strong></p>
            <p style="font-size: 14px;"><b>{{ json_decode($generalSettings->business, true)['address'] }}</b></p>
            <p style="font-size: 14px;">
                <strong>{{ __('Email') }} : </strong><b>{{ json_decode($generalSettings->business, true)['email'] }}</b>,
                <strong>{{ __('Phone') }} : </strong><b>{{ json_decode($generalSettings->business, true)['phone'] }}</b>
            </p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 text-center">
        @if ($by == 'accountId')

            <p style="margin-top: 10px; text-transform: uppercase;"><strong>{{ __('Account Ledger') }}</strong></p>
        @elseif($by == 'userId')

            <p style="margin-top: 10px; text-transform: uppercase;"><strong>{{ __('Ledger Of Sales Representative') }}</strong></p>
        @endif

        @if ($fromDate && $toDate)

            <p style="margin-top: 10px;">
                <strong>{{ __('From') }} : </strong> {{date(json_decode($generalSettings->business, true)['date_format'] , strtotime($fromDate)) }}
                <strong>{{ __('To') }} : </strong> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($toDate)) }}
            </p>
        @else

            <p style="margin-top: 10px;">
                <strong>{{ __('From') }} : </strong> {{date(json_decode($generalSettings->business, true)['date_format'], strtotime($entries->min('date'))) }}
                <strong>{{ __('To') }} : </strong> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($entries->max('date'))) }}
            </p>
        @endif
    </div>
</div>

@php
    $amounts = '';
    if ($by == 'accountId' && $account) {

        $accountUtil = new \App\Utils\AccountUtil();
        $amounts = $accountUtil->accountClosingBalance($account->id, $request->user_id, $request->from_date, $request->to_date);
    }elseif ($by == 'userId' && $user) {

        $srUtil = new \App\Utils\SrUtil();
        $amounts = $srUtil->srClosingBalance($user->id, $request->customer_account_id, $request->from_date, $request->to_date);
    }

    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp

<div class="account_details_area mt-1">
    <div class="row">
        @if ($by == 'accountId' && $account)
            <div class="col-6">
                <table>
                    <tr style="line-height: 18px;">
                        <td class="fw-bold">{{ __('Group') }}</th>
                        <td><strong>:</strong> {{ $account?->group?->name }}</td>
                    </tr>

                    @if ($account->bank_name)
                        <tr style="line-height: 18px;">
                            <td class="fw-bold">{{ __('Bank') }}</th>
                            <td><strong>:</strong> {{ $account->bank_name }}</td>
                        </tr>
                    @endif

                    <tr style="line-height: 18px;">
                        <td class="fw-bold">{{ __('A/c Name') }}</td>
                        <td><strong>:</strong> {{ $account->name }} {{ $account->account_number ? ' / '.$account->account_number : '' }} {{ $userName ? ' - Sr. '.$userName : '' }}</td>
                    </tr>

                    <tr style="line-height: 18px;">
                        <td class="fw-bold">{{ __('Phone') }}</td>
                        <td><strong>:</strong> {{ $account->phone }}</td>
                    </tr>

                    <tr style="line-height: 18px;">
                        <td class="fw-bold">{{ __('Sr.') }}</td>
                        <td><strong>:</strong> {{ $userName }}</td>
                    </tr>
                </table>
            </div>

            <div class="col-6">
                <table class="table modal-table table-sm print_table">
                    <tbody>
                        <tr>
                            <th class="text-end"></th>
                            <td class="text-end fw-bold">{{ __('Debit') }}</td>
                            <td class="text-end fw-bold">{{ __('Credit') }}</td>
                        </tr>

                        <tr>
                            <td class="text-end fw-bold">{{ __('Opening Balance') }} : </td>
                            <td class="text-end fw-bold">{{ $amounts['opening_balance'] > 0 ? ($amounts['opening_balance_side'] == 'dr' ? App\Utils\Converter::format_in_bdt($amounts['opening_balance']) : '') : '' }}</td>
                            <td class="text-end fw-bold">{{ $amounts['opening_balance'] > 0 ? ($amounts['opening_balance_side'] == 'cr' ? App\Utils\Converter::format_in_bdt($amounts['opening_balance']) : '') : '' }}</td>
                        </tr>

                        <tr>
                            <td class="text-end fw-bold">{{ __('Current Total') }} : </td>
                            <td class="text-end fw-bold">{{ $amounts['curr_total_debit'] > 0 ? App\Utils\Converter::format_in_bdt($amounts['curr_total_debit']) : '' }}</td>
                            <td class="text-end fw-bold">{{ $amounts['curr_total_credit'] > 0 ? App\Utils\Converter::format_in_bdt($amounts['curr_total_credit']) : '' }}</td>
                        </tr>

                        <tr>
                            <td class="text-end fw-bold">{{ __('Closing Balance') }} : </td>

                            <td class="text-end fw-bold">
                                {{ $amounts['closing_balance'] > 0 ? ($amounts['closing_balance_side'] == 'dr' ? App\Utils\Converter::format_in_bdt($amounts['closing_balance']) : '') : '' }}
                            </td>

                            <td class="text-end fw-bold">
                                {{ $amounts['closing_balance'] > 0 ? ($amounts['closing_balance_side'] == 'cr' ? App\Utils\Converter::format_in_bdt($amounts['closing_balance']) : '') : '' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @elseif($by == 'userId' && $user)
            <div class="col-6">
                <ul class="list-unstyled">
                    <table>
                        <tr style="line-height: 18px;">
                            <td><strong>{{ __('Customer') }}</strong></td>
                            <td><strong>:</strong> {{ $customerName }}</td>
                        </tr>

                        <tr style="line-height: 18px;">
                            <td><strong>{{ __('S/r') }}</strong></td>
                            <td><strong>:</strong> {{ $user->prefix.' '.$user->name.' '.$user->last_name }}</td>
                        </tr>
                    </table>
                </ul>
            </div>
        @endif
    </div>
</div>

<div class="row mt-1">
    <div class="col-12 print_table_area" >
        <table class="table report-table table-sm print_table">
            <thead>
                <tr>
                    <th class="text-start">{{ __('Date') }}</th>
                    <th class="text-start">{{ __('Particulars') }}</th>
                    <th class="text-start">{{ __('Voucher Type') }}</th>
                    <th class="text-start">{{ __('Voucher No') }}</th>
                    <th class="text-end">{{ __('Debit') }}</th>
                    <th class="text-end">{{ __('Credit') }}</th>
                    <th class="text-end">{{ __('Running Balance') }}</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $previousDate = '';
                    $isEmptyDate = 0;
                @endphp
                @foreach ($entries as $row)
                    <tr class="main_tr">
                         <td class="text-start fw-bold main_td" style="border-bottom: 0px solid black!important;">
                            @php
                                $dateFormat = json_decode($generalSettings->business, true)['date_format'];
                                $__date_format = str_replace('-', '/', $dateFormat);
                                $date = $row->date ? date($__date_format, strtotime($row->date)) : '';
                            @endphp

                            @if ($previousDate != $date)
                                @php
                                    $previousDate = $date;
                                    $isEmptyDate = 0;
                                @endphp
                                {{ $date }}
                            @endif
                        </td>

                        <td class="text-start main_td">
                            @php
                                $voucherType = $row->voucher_type;
                                $ledgerParticularsUtil = new \App\Utils\LedgerParticularsForPrintUtil();
                            @endphp
                            {!! $ledgerParticularsUtil->particulars($request, $row->voucher_type, $row, $by) !!}
                        </td>

                        <td class="text-start main_td">
                            @php
                                $accountLedgerUtil = new \App\Utils\AccountLedgerUtil();
                                $type = $accountLedgerUtil->voucherType($row->voucher_type);
                            @endphp
                            {!! $row->voucher_type != 0 ? '<strong>' . $type['name'] . '</strong>' : '' !!}
                        </td>

                        <td class="text-start main_td">{!! $row->{$type['voucher_no']} !!}</td>
                        <td class="text-end fw-bold main_td">
                            {{ $row->debit > 0 ? \App\Utils\Converter::format_in_bdt($row->debit) : '' }}
                        </td>

                        <td class="text-end fw-bold main_td">
                            {{ $row->credit > 0 ? \App\Utils\Converter::format_in_bdt($row->credit) : '' }}
                        </td>

                        <td class="text-end fw-bold main_td">
                            {{ $row->running_balance > 0 ? \App\Utils\Converter::format_in_bdt(abs($row->running_balance)) . $row->balance_type : '' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-6"></div>
    <div class="col-6">
        @if ($by == 'accountId' && $account)
            <table class="table report-table table-sm table-bordered print_table">
                <tbody>
                    <tr>
                        <th colspan="3" class="text-center fw-bold">{{ __('Account Summary') }}</th>
                    </tr>

                    <tr>
                        <th class="text-end"></th>
                        <th class="text-end fw-bold">{{ __('Debit') }}</th>
                        <th class="text-end fw-bold">{{ __('Credit') }}</th>
                    </tr>

                    <tr>
                        <td class="text-end fw-bold">{{ __('Opening Balance') }}</td>
                        <td class="text-end fw-bold">{{ $amounts['opening_balance'] > 0 ? ($amounts['opening_balance_side'] == 'dr' ? App\Utils\Converter::format_in_bdt($amounts['opening_balance']) : '') : '' }}</td>
                        <td class="text-end fw-bold">
                            {{ $amounts['opening_balance'] > 0 ? ($amounts['opening_balance_side'] == 'cr' ? App\Utils\Converter::format_in_bdt($amounts['opening_balance']) : '') : '' }}
                        </td>
                    </tr>

                    <tr>
                        <td class="text-end fw-bold">{{ __('Current Total') }}</td>
                        <td class="text-end fw-bold">
                            {{ $amounts['curr_total_debit'] > 0 ? App\Utils\Converter::format_in_bdt($amounts['curr_total_debit']) : '' }}
                        </td>
                        <td class="text-end fw-bold">
                            {{ $amounts['curr_total_credit'] > 0 ? App\Utils\Converter::format_in_bdt($amounts['curr_total_credit']) : '' }}
                        </td>
                    </tr>

                    <tr>
                        <td class="text-end fw-bold">{{ __('Closing Balance') }}</td>
                        <td class="text-end fw-bold">
                            {{ $amounts['closing_balance'] > 0 ? ($amounts['closing_balance_side'] == 'dr' ? App\Utils\Converter::format_in_bdt($amounts['closing_balance']) : '') : '' }}
                        </td>
                        <td class="text-end fw-bold">
                            {{ $amounts['closing_balance'] > 0 ? ($amounts['closing_balance_side'] == 'cr' ? App\Utils\Converter::format_in_bdt($amounts['closing_balance']) : '') : '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        @elseif($by == 'userId' && $user)
            <table class="table report-table table-sm table-bordered print_table">
                <tbody>
                    <tr>
                        <th colspan="3" class="text-center">{{ __('Account Summary') }}</th>
                    </tr>

                    <tr>
                        <th class="text-end"></th>
                        <th class="text-end fw-bold">{{ __('Debit') }}</th>
                        <th class="text-end fw-bold">{{ __('Credit') }}</th>
                    </tr>

                    <tr>
                        <td class="text-end fw-bold">{{ __('Opening Balance') }}</td>
                        <td class="text-end fw-bold">
                            {{ $amounts['opening_balance'] > 0 ? ($amounts['opening_balance_side'] == 'dr' ? App\Utils\Converter::format_in_bdt($amounts['opening_balance']) : '') : '' }}
                        </td>
                        <td class="text-end fw-bold">
                            {{ $amounts['opening_balance'] > 0 ? ($amounts['opening_balance_side'] == 'cr' ? App\Utils\Converter::format_in_bdt($amounts['opening_balance']) : '') : '' }}
                        </td>
                    </tr>

                    <tr>
                        <td class="text-end fw-bold">{{ __('Current Total') }}</td>
                        <td class="text-end fw-bold">
                            {{ $amounts['curr_total_debit'] > 0 ? App\Utils\Converter::format_in_bdt($amounts['curr_total_debit']) : '' }}
                        </td>
                        <td class="text-end fw-bold">
                            {{ $amounts['curr_total_credit'] > 0 ? App\Utils\Converter::format_in_bdt($amounts['curr_total_credit']) : '' }}
                        </td>
                    </tr>

                    <tr>
                        <td class="text-end fw-bold">{{ __('Closing Balance') }}</td>
                        <td class="text-end fw-bold">
                            {{ $amounts['closing_balance'] > 0 ? ($amounts['closing_balance_side'] == 'dr' ? App\Utils\Converter::format_in_bdt($amounts['closing_balance']) : '') : '' }}
                        </td>
                        <td class="text-end fw-bold">
                            {{ $amounts['closing_balance'] > 0 ? ($amounts['closing_balance_side'] == 'cr' ? App\Utils\Converter::format_in_bdt($amounts['closing_balance']) : '') : '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        @endif
    </div>
</div>

<div id="footer">
    <div class="row" style="margin-top: 4px;">
        <div class="col-4 text-start">
            <small>{{ __('Print Date') }} : {{ date(json_decode($generalSettings->business, true)['date_format']) }}</small>
        </div>

        <div class="col-4 text-center">
            @if (config('company.print_on_sale'))
                <small>{{ __('Powered By') }} <strong>{{ __('SpeedDigit Software Solution.') }}</strong></small>
            @endif
        </div>

        <div class="col-4 text-end">
            <small>{{ __('Print Time') }} : {{ date($timeFormat) }}</small>
        </div>
    </div>
</div>
