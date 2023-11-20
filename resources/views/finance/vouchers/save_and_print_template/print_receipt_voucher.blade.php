@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $inWord = new NumberFormatter("en", NumberFormatter::SPELLOUT);
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
    $dateFormat = json_decode($generalSettings->business, true)['date_format'];
@endphp

<div class="print_details">
    <style>
        div#footer {position:fixed;bottom:20px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
        @page {size:a4; margin-top: 0.8cm; margin-bottom: 35px; margin-left: 4%; margin-right: 4%;}

        h6 { font-size: 16px; }
        p {  font-size: 14px; }
        td {  color: black; }
    </style>

    <div class="details_area">
        <div class="row" style="border-bottom: 1px solid black; padding-botton: 1px;">
            <div class="col-4">
                @if (json_decode($generalSettings->business, true)['business_logo'] != null)

                    <img style="height: auto; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                @else

                    <span style="font-family: 'Anton', sans-serif;font-size:17px;color:gray;font-weight: 550; letter-spacing:1px;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
                @endif
            </div>

            <div class="col-8 text-end">
                <h6 class="company_name" style="text-transform: uppercase;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</h6>
                <p class="company_address">{{ json_decode($generalSettings->business, true)['address'] }}</p>
                <p><strong>@lang('menu.phone')</strong> : {{ json_decode($generalSettings->business, true)['phone'] }}</p>
                <p><strong>@lang('menu.email')</strong> : {{ json_decode($generalSettings->business, true)['email'] }}</p>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12 text-center">
                <h5 style="text-transform: uppercase;">
                    @lang('menu.receipt_voucher')
                </h5>
            </div>
        </div>

        <div class="purchase_and_deal_info mt-2">
            <div class="row">
                <div class="col-6">
                    <ul class="list-unstyled">
                        <li><strong>@lang('menu.date') : </strong> {{ date($dateFormat ,strtotime($receipt->date_ts))}}</li>
                        <li><strong>@lang('menu.voucher_no') : </strong> {{ $receipt->voucher_no }}</li>
                    </ul>
                </div>

                <div class="col-6 text-end">
                    <ul class="list-unstyled">
                        <li>
                            <strong>@lang('menu.reference') : </strong>

                            @if ($receipt?->stockAdjustmentReference?->voucher_no)
                                <b>@lang('menu.stock_adjustment') :</b> {{ $receipt?->stockAdjustmentReference?->voucher_no }}
                            @endif

                            @if ($receipt?->saleReference?->order_id)
                                <b>@lang('menu.sales_order') :</b> {{ $receipt?->saleReference?->order_id }}
                            @endif

                            @if ($receipt?->saleReference?->invoice_id)
                                <b>@lang('menu.sales') :</b> {{ $receipt?->saleReference?->invoice_id }}
                            @endif

                            @if ($receipt?->purchaseReference?->invoice_id)
                                <b>@lang('menu.PI') :</b> {{ $receipt?->purchaseReference?->invoice_id }}
                            @endif
                        </li>

                        <li>
                            <strong>@lang('menu.created_by') : </strong> {{ $receipt?->user?->prefix.' '.$receipt?->user?->name.' '.$receipt?->user?->last_name }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="sale_product_table mt-2">
            <table class="table report-table table-sm table-bordered print_table">
                <thead>
                    <tr>
                        <th class="text-start">@lang('menu.serial')</th>
                        <th class="text-start"></th>
                        <th class="text-start">@lang('menu.descriptions')</th>
                        <th class="text-end">@lang('menu.debit')</th>
                        <th class="text-end">@lang('menu.credit')</th>
                    </tr>
                </thead>
                <tbody class="sale_product_list">
                    @foreach ($receipt->descriptions->sortByDesc('amount_type') as $description)
                    <tr style="border: 0px solid;">
                            <td class="text-start">{{ $loop->index + 1 }}.</td>

                            @php
                                $amountType = $description->amount_type == 'dr' ? ' <span class="fw-bold">Dr. </span>' : ' <span class="fw-bold">Cr. </span>';
                                $assignedUser = $description->user ? (' - <strong>Sr.</strong> ' . $description->user->prefix . ' ' . $description->user->name . ' ' . $description->user->last_name) : '';

                                $transactionDetails = '';
                                if (
                                    $description->payment_method_id || $description->transaction_no || $description->cheque_no || $description->cheque_serial_no || $description->cheque_issue_date
                                ) {
                                    $transactionDetails .= '<p class="p-0 m-0 fw-bold">Bank Transaction Details :</p>';
                                    $transactionDetails .= '<p class="p-0 m-0" style="font-size:11px;"><b>';
                                    $transactionDetails .= $description?->paymentMethod?->name;
                                    $transactionDetails .= ' - TransNo: ' .$description->transaction_no;
                                    $transactionDetails .= ' - ChequeNo: ' .$description->cheque_no;
                                    $transactionDetails .= ' - SerialNo: ' .$description->cheque_serial_no;
                                    $transactionDetails .= ' - IssueDate: ' .$description->cheque_issue_date;
                                    $transactionDetails .= '</b></p>';
                                }
                            @endphp

                            <td>{!! $amountType !!}</td>

                            <td style="border-bottom: 1px solid black!important;">
                                {!! $description?->account?->name.($description?->account?->account_number ? ' - A/c No : ' .$description?->account?->account_number : '').$assignedUser.$transactionDetails !!}
                            </td>

                            <td class="text-end">
                                @php
                                    $debitAmount = $description->amount_type == 'dr' ? $description->amount : 0;
                                @endphp

                                @if ($debitAmount > 0)

                                    <strong>{{ App\Utils\Converter::format_in_bdt($debitAmount) }}</strong>
                                @endif
                            </td>

                            <td class="text-end">
                                @php
                                    $creditAmount = $description->amount_type == 'cr' ? $description->amount : 0;
                                @endphp

                                @if ($creditAmount > 0)

                                    <strong>{{ App\Utils\Converter::format_in_bdt($creditAmount) }}</strong>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end"><span class="text-end">@lang('menu.total') : </span></th>
                        <th class="text-end">{{ App\Utils\Converter::format_in_bdt($receipt->debit_total) }}</th>
                        <th class="text-end">{{ App\Utils\Converter::format_in_bdt($receipt->credit_total) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="row">
            <div class="col-md-12">
                <p style="text-transform: uppercase;"><strong>@lang('menu.in_word') : </strong> {{ $inWord->format($receipt->debit_total) }} ({{ json_decode($generalSettings->business, true)['currency'] }}) @lang('menu.only').</p>
                <p class="p-0 m-0"><strong>@lang('menu.remarks') :</strong>  {{ $receipt->remarks }}</p>
            </div>
        </div>

        <br><br>

        <div class="row">
            <div class="col-4 text-start">
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('menu.prepared_by')</p>
            </div>

            <div class="col-4 text-center">
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('menu.checked_by')</p>
            </div>

            <div class="col-4 text-end">
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('menu.authorize_by')</p>
            </div>
        </div>

        <div id="footer">
            <div class="row">
                <div class="col-4 text-start">
                    <small>@lang('menu.print_date') : {{ date($dateFormat) }}</small>
                </div>

                <div class="col-4 text-center">
                    @if (config('company.print_on_sale'))
                        <small>@lang('menu.powered_by') <b>@lang('menu.speedDigit_software_solution').</b></small>
                    @endif
                </div>

                <div class="col-4 text-end">
                    <small>@lang('menu.print_time') :{{ date($timeFormat) }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
