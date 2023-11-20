
<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 33px; margin-left: 0px;margin-right: 0px;}
</style>

<div class="row">
    <div class="col-md-12 text-center">
        <div class="heading_area" style="border-bottom: 1px solid black;">
            <img style="height: auto; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
            <p style="text-transform: uppercase;"><strong>{{ json_decode($generalSettings->business, true)['shop_name'] }}</strong></p>
            <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
            <p style="width: 60%; margin:0 auto;"><strong>@lang('menu.phone') : </strong> {{ json_decode($generalSettings->business, true)['phone'] }}</p>
        </div>

        <p style="margin-top: 10px;"><strong>@lang('menu.sr_ledger') </strong></p>

        @if ($fromDate && $toDate)

            <p style="margin-top: 10px;"><strong>@lang('menu.from') :</strong> {{date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($fromDate)) }} <strong>To</strong> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($toDate)) }} </p>
        @endif
    </div>
</div>

<div class="sr_details_area ">
    <div class="row">
        <div class="col-12">
            <ul class="list-unstyled">
                <li><strong>{{ __("Sr.") }} : </strong> {{ $user->prefix.' '.$user->name.' '.$user->last_name }} </li>
                <li><strong>@lang('menu.phone') : </strong> {{ $user->phone }}</li>
            </ul>
        </div>
    </div>
</div>

<div class="row mt-2">
    <p><strong>@lang('menu.filtered_by')</strong></p>
    <div class="col-12">
        <p><strong>@lang('menu.customer') :</strong> {{ $customerName }} </p>
    </div>
</div>

@php
    $totalDebit = 0;
    $totalCredit = 0;
    $totalDebitOpeningBalance = 0;
    $totalCreditOpeningBalance = 0;
    $totalLess = 0;
@endphp
<div class="row mt-1">
    <div class="col-12" >
        <table class="table report-table table-sm table-bordered print_table ledger_table_print" >
            <thead>
                <tr>
                    <th class="text-start">@lang('menu.date')</th>
                    <th class="text-start">@lang('menu.particulars')</th>
                    <th class="text-start">@lang('menu.customer')</th>
                    <th class="text-start">@lang('menu.voucher')/@lang('menu.invoice')</th>
                    <th class="text-end">@lang('menu.debit')</th>
                    <th class="text-end">@lang('menu.credit')</th>
                    <th class="text-end">@lang('menu.running_balance')</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $previousBalance = 0;
                    $i = 0;
                @endphp
                @foreach ($ledgers as $row)
                    @php
                        $debit = $row->debit;
                        $credit = $row->credit;

                        if($i == 0) {

                            $previousBalance = $debit - $credit;
                        } else {

                            $previousBalance = $previousBalance + ($debit - $credit) - $row->less_amount;
                        }
                    @endphp

                    <tr>
                        <td class="text-start">
                            @php
                                $dateFormat = json_decode($generalSettings->business, true)['date_format'];
                                $__date_format = str_replace('-', '/', $dateFormat);
                            @endphp

                            {{ date($__date_format, strtotime($row->report_date)) }}
                        </td>

                        <td class="text-start">
                            @php
                                 $soldProducts = '';

                                if (isset($request->show_item_details) && $request->show_item_details == 1) {

                                    if ($row->sale) {

                                        $soldProducts .= '<br>';
                                        if (count($row->sale->saleProducts) > 0) {

                                            foreach ($row->sale->saleProducts as $saleProduct) {

                                                $quantity = 0;
                                                if ($saleProduct->quantity > 0) {

                                                    $quantity = $saleProduct->quantity;
                                                } else {

                                                    $quantity = $saleProduct->ordered_quantity;
                                                }

                                                $product = $saleProduct->product ? $saleProduct->product->name : 'N/A';
                                                $price = $saleProduct->unit_price_exc_tax;
                                                $soldProducts .= '<p class="p-0 m-0 text-dark">-<small>' . $product . ', Qty : ' . $quantity . ', Price : ' . $price . '</small></p>';
                                            }
                                        }
                                    }
                                }

                                $journalAsPerDetails = '';

                                if ($row->journalEntry && $row->voucher_type == 7) {

                                    if ($row->journalEntry->amount_type == 'debit') {

                                        if ($row->journalEntry->journal) {

                                            $journal = $row->journalEntry->journal;

                                            if (count($journal->creditEntries) > 0) {

                                                // $journalAsPerDetails .= '<p class="m-0 p-0">(As Per Details)</p>';
                                                foreach ($journal->creditEntries as $creditEntry) {

                                                    $note = $creditEntry->note ? '/'.$creditEntry->note : '';

                                                    $assignedUser = $creditEntry->assignedUser ? ' - A/c '.$creditEntry->assignedUser->prefix.'  '.$creditEntry->assignedUser->name.'  '.$creditEntry->assignedUser->last_name : '';

                                                    $accountNo = $creditEntry->account ? ($creditEntry->account->account_number ? ' - A/c No.: ..' . substr($creditEntry->account->account_number, -4) : '') : '';

                                                    $journalAsPerDetails .= $creditEntry->account ? '<p class="m-0 p-0"> - Cr <strong>' . $creditEntry->account->name . $accountNo . $note . '</strong></p>' : '';

                                                    $journalAsPerDetails .= $creditEntry->supplier ? '<p class="m-0 p-0"> - Cr <strong>' . $creditEntry->supplier->name . ' - (Supplier) '. $note .'</strong></p>' : '';

                                                    $journalAsPerDetails .= $creditEntry->customer ? '<p class="m-0 p-0"> - Cr <strong>'  . $creditEntry->customer->name . ' - (Customer)'. $assignedUser . $note .'</strong></p>'  : '';
                                                }

                                            }
                                        }
                                    }elseif ($row->journalEntry->amount_type == 'credit') {

                                        if ($row->journalEntry->journal) {

                                            $journal = $row->journalEntry->journal;

                                            if (count($journal->debitEntries) > 0) {

                                                // $journalAsPerDetails .= '<p class="m-0 p-0">(As Per Details)</p>';
                                                foreach ($journal->debitEntries as $debitEntry) {

                                                    $note = $creditEntry->note ? '/'.$creditEntry->note : '';

                                                    $assignedUser = $debitEntry->assignedUser ? __('Sr.').' '.$debitEntry->assignedUser->prefix.'  '.$debitEntry->assignedUser->name.'  '.$debitEntry->assignedUser->last_name : '';

                                                    $accountNo = $debitEntry->account ? ($debitEntry->account->account_number ? ' - A/c No.: ..' . substr($debitEntry->account->account_number, -4) : '') : '';

                                                    $journalAsPerDetails .= $debitEntry->account ? '<p class="m-0 p-0"> - Dr <strong>' . $debitEntry->account->name . $accountNo . $note . ' : </strong>'.$debitEntry->amount.'</p>' : '';

                                                    $journalAsPerDetails .= $debitEntry->supplier ? '<p class="m-0 p-0"> - Dr <strong>' . $debitEntry->supplier->name . ' - (Supplier)'. $note .' : </strong>'.$debitEntry->amount.'</p>' : '';

                                                    $journalAsPerDetails .= $debitEntry->customer ? '<p class="m-0 p-0"> - Dr <strong>'  . $debitEntry->customer->name . ' - (Customer)'.$assignedUser. $note .' : </strong>'. $debitEntry->amount .'</p>'  : '';
                                                }

                                            }
                                        }
                                    }
                                }

                                $type = $customerUtil->voucherType($row->voucher_type);
                                $__agp = $row->ags_sale ? '/' . 'AGS:<b>' . $row->ags_sale . '</b>' : '';
                                $__less = $row->less_amount > 0 ? '/' . 'Less:(<strong class="text-danger">' . $row->less_amount . '</strong>)' : '';
                                $sp_account = $row->sp_account > 0 ? '/' . '<strong>Ac:</strong>' . $row->sp_account . '' : '';
                                $cp_account = $row->cp_account > 0 ? '/' . '<strong>Ac:</strong>' . $row->cp_account . '' : '';
                                $account = isset($request->account_details) && $request->account_details == 1 ? $sp_account . $cp_account : '';
                                $particular = ($row->{$type['par']} ? '/<strong>N:</strong>' . $row->{$type['par']} : '');
                                $__particular = isset($request->notes) && $request->notes == 1 ? $particular : '';


                            @endphp

                            {!! '<strong>' . $type['name'] . ($row->sale_status == 3 || $row->sale_status == 7 ? '-Order' : '') . '</strong>' . $__agp . $account . $__particular . $__less . $soldProducts . $journalAsPerDetails !!}

                            @php $totalLess += $row->less_amount @endphp
                        </td>

                        <td>
                            <strong> {{ $row->c_name }} </strong>
                        </td>

                        <td class="text-start">
                            @php
                                $type = $customerUtil->voucherType($row->voucher_type);

                                if ($row->voucher_type == 0) {

                                    if ($row->amount_type == 'debit') {

                                        $totalDebitOpeningBalance += $row->ld_amount;
                                    }else if ($row->amount_type == 'credit') {

                                        $totalCreditOpeningBalance += $row->ld_amount;
                                    }
                                }
                            @endphp

                            {{ $row->{$type['voucher_no']} }}
                        </td>

                        <td class="text-end">
                           {{ App\Utils\Converter::format_in_bdt($row->debit) }}
                            @php $totalDebit += $row->debit; @endphp
                        </td>

                        <td class="text-end">
                            {{ App\Utils\Converter::format_in_bdt($row->credit) }}
                            @php $totalCredit += $row->credit; @endphp
                        </td>

                        <td class="text-end">{!! App\Utils\Converter::format_in_bdt(abs($previousBalance)) . ($previousBalance < 0 ? ' <strong>Cr</strong>' : ' <strong>Dr</strong>') !!}</td>
                    </tr>

                    @php $i++; @endphp
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-6"></div>
    <div class="col-6">
        <table class="table report-table table-sm table-bordered print_table">
            <tbody>
                <tr>
                    <th class="text-end"></th>
                    <th class="text-end">@lang('menu.debit')</th>
                    <th class="text-end">@lang('menu.credit')</th>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('menu.opening_balance') :</strong></td>

                    @if ($totalDebitOpeningBalance > $totalCreditOpeningBalance)

                        @php
                            $openingBalance = $totalDebitOpeningBalance - $totalCreditOpeningBalance;
                        @endphp

                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($openingBalance) }}</td>

                        <td class="text-end"></td>
                    @elseif($totalCreditOpeningBalance > $totalDebitOpeningBalance)

                        @php
                            $openingBalance =  $totalCreditOpeningBalance - $totalDebitOpeningBalance;
                        @endphp

                        <td class="text-end"></td>

                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($openingBalance) }}</td>
                    @elseif($totalDebit == $totalCredit)

                        <td class="text-end">0.00</td>
                        <td class="text-end">0.000</td>
                    @endif
                    {{-- <td class="text-end">{{ $totalDebitOpeningBalance. '||' .$totalCreditOpeningBalance }}</td>
                    <td class="text-end">{{ $totalDebitOpeningBalance. '||' .$totalCreditOpeningBalance }}</td> --}}
                </tr>

                <tr>
                    <td class="text-end">
                        <strong>@lang('menu.current_total') :</strong>
                    </td>

                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalDebit) }}</td>

                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($totalCredit) }}</td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('menu.closing_balance')  :</strong></td>

                    @if ($totalDebit > $totalCredit)

                        @php
                            $closingBalance = $totalDebit - $totalCredit;
                        @endphp

                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($closingBalance) }}</td>

                        <td class="text-end"></td>
                    @elseif($totalCredit > $totalDebit)

                        @php
                            $closingBalance =  $totalCredit - $totalDebit;
                        @endphp

                        <td class="text-end"></td>

                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($closingBalance) }}</td>
                    @elseif($totalDebit == $totalCredit)

                        <td class="text-end">0.00</td>
                        <td class="text-end">0.000</td>
                    @endif
                </tr>
            </tbody>
        </table>
    </div>
</div>
