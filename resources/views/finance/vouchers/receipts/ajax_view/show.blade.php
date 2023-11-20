@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $inWord = new NumberFormatter("en", NumberFormatter::SPELLOUT);
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
    $dateFormat = json_decode($generalSettings->business, true)['date_format'];
@endphp
<div class="modal fade extra_show_class" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog four-col-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    @lang('menu.receipt_voucher') (@lang('menu.voucher_no') : <strong><span>{{ $receipt->voucher_no }}</span></strong>)
                </h5>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                    <span class="fas fa-times"></span>
                </a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li>
                                <strong>@lang('menu.date') : </strong> {{ date($dateFormat ,strtotime($receipt->date_ts))}}
                            </li>

                            <li>
                                <strong>@lang('menu.voucher_no') : </strong> {{ $receipt->voucher_no }}
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-start">
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

                <div class="row mt-2">
                    <div class="table-responsive">
                        <table id="" class="table modal-table table-sm table-striped">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th class="text-start">@lang('menu.serial')</th>
                                    <th class="text-start"></th>
                                    <th class="text-start">@lang('menu.descriptions')</th>
                                    <th class="text-end">@lang('menu.debit')</th>
                                    <th class="text-end">@lang('menu.credit')</th>
                                </tr>
                            </thead>
                            <tbody class="sale_product_list">
                                @foreach ($receipt->descriptions->sortByDesc('amount_type') as $description)
                                    <tr>
                                        <td class="text-start">{{ $loop->index + 1 }}</td>
                                        @php

                                            $amountType = $description->amount_type == 'dr' ? ' <span class="fw-bold">Dr. </span>' : ' <span class="fw-bold">Cr. </span>';
                                            $assignedUser = $description->user ? (' - <strong>Sr.</strong> ' . $description->user->prefix . ' ' . $description->user->name . ' ' . $description->user->last_name) : '';

                                            $transactionDetails = '';
                                            if (
                                                $description->payment_method_id || $description->transaction_no || $description->cheque_no || $description->cheque_serial_no || $description->cheque_issue_date
                                            ) {
                                                $transactionDetails .= '<p class="p-0 m-0 fw-bold">Bank Transaction Details :</p>';
                                                $transactionDetails .= '<p class="p-0 m-0"><b>';
                                                $transactionDetails .= $description?->paymentMethod?->name;
                                                $transactionDetails .= ' - TransNo: ' .$description->transaction_no;
                                                $transactionDetails .= ' - ChequeNo: ' .$description->cheque_no;
                                                $transactionDetails .= ' - SerialNo: ' .$description->cheque_serial_no;
                                                $transactionDetails .= ' - IssueDate: ' .$description->cheque_issue_date;
                                                $transactionDetails .= '</b></p>';
                                            }

                                            $referencesDetails = '';
                                            if (count($description->references) > 0) {

                                                $referencesDetails .= '<p class="p-0 m-0 fw-bold">Against References :</p>';
                                                foreach ($description->references as $reference) {

                                                    $sale = '';
                                                    if ($reference->sale) {

                                                        if ($reference->sale->order_status == 1) {

                                                            $sale = '<p class="fw-bold" style="line-height:14px">Sales-Order : ' . $reference->sale->order_id . ' = ' . \App\Utils\Converter::format_in_bdt($reference->amount). ' ' . json_decode($generalSettings->business, true)['currency'].'</p>';
                                                        } else {

                                                            $sale = '<p class="fw-bold" style="line-height:14px">Sales :' . $reference?->sale->invoice_id . ' = ' . \App\Utils\Converter::format_in_bdt($reference->amount). ' ' .  json_decode($generalSettings->business, true)['currency'].'</p>';
                                                        }
                                                    }

                                                    $purchase = '';
                                                    if ($reference->purchase) {

                                                        if ($reference->purchase->purchase_status == 1) {

                                                            $purchase =  '<p style="line-height:14px"><strong>PI: </strong>' . $reference?->purchase?->invoice_id .' = '.\App\Utils\Converter::format_in_bdt($reference->amount) . ' ' . json_decode($generalSettings->business, true)['currency'] . '</p>';
                                                        } else {

                                                            $purchase =  '<p class="fw-bold" style="line-height:14px"><strong>PO: </strong>' . $reference?->purchase->invoice_id .' = '. \App\Utils\Converter::format_in_bdt($reference->amount) . ' ' . json_decode($generalSettings->business, true)['currency']  . '</p>';
                                                        }
                                                    }

                                                    $stockAdjustment = '';
                                                    if ($reference->stockAdjustment) {

                                                        $stockAdjustment =  '<p class="fw-bold" style="line-height:14px">S. Adjustment: ' . $reference?->stockAdjustment->voucher_no . \App\Utils\Converter::format_in_bdt($reference->amount). ' ' . json_decode($generalSettings->business, true)['currency'] .'</p>';
                                                    }

                                                    $referencesDetails .=  $sale . $purchase . $stockAdjustment;
                                                }
                                            }
                                        @endphp

                                        <td>{!! $amountType !!}</td>

                                        <td>
                                            {!! '<strong>'.$description?->account?->name.'</strong>'. ($description?->account?->account_number ? ' - A/c No : ' .$description?->account?->account_number : '') . $assignedUser . $transactionDetails . $referencesDetails !!}
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
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p style="text-transform: uppercase;"><strong>@lang('menu.in_word') : </strong> {{ $inWord->format($receipt->debit_total) }} ({{ json_decode($generalSettings->business, true)['currency'] }}) @lang('menu.only').</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <p><strong>@lang('menu.remarks')</strong> : </p>
                        <p>{{ $receipt->remarks }}</p>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                @if (auth()->user()->can('receipts_edit'))

                    <a href="{{ route('vouchers.receipts.edit', [$receipt->id]) }}" class="btn btn-sm btn-secondary m-0 me-2">@lang('menu.edit')</a>
                @endif

                <button type="button" class="btn btn-sm btn-primary m-0 me-2" id="print_modal_details_btn">@lang('menu.print') </button>
                <button type="button" class="btn btn-sm btn-danger m-0" data-bs-dismiss="modal">@lang('menu.close')</button>
            </div>
        </div>
    </div>
</div>
<!-- Details Modal End-->

<!-- Voucher print templete-->
<div class="print_details d-none">
    <style>
        div#footer {position:fixed;bottom:20px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
        @page {size:a4; margin-top: 0.8cm; margin-bottom: 35px; margin-left: 10px; margin-right: 10px;}

        .details_area h6 { font-size: 16px; }
        .details_area p {  font-size: 14px; }
        .details_area td {  color: black; }
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
                <h6 class="text-uppercase">{{ json_decode($generalSettings->business, true)['shop_name'] }}</h6>
                <p><b>{{ json_decode($generalSettings->business, true)['address'] }}</b></p>
                <p>
                    <strong>@lang('menu.email')</strong> : <b>{{ json_decode($generalSettings->business, true)['email'] }}</b>,
                    <strong>@lang('menu.phone')</strong> : <b>{{ json_decode($generalSettings->business, true)['phone'] }}</b>
                </p>
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
                        <li style="font-size:11px!important;">
                            <strong>@lang('menu.date') : </strong> {{ date($dateFormat, strtotime($receipt->date_ts))}}
                        </li>

                        <li style="font-size:11px!important;">
                            <strong>@lang('menu.voucher_no') : </strong> {{ $receipt->voucher_no }}
                        </li>
                    </ul>
                </div>

                <div class="col-6 text-end">
                    <ul class="list-unstyled">
                        <li style="font-size:11px!important;">
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

                        <li style="font-size:11px!important;">
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

                                $referencesDetails = '';
                                if (count($description->references) > 0) {

                                    $referencesDetails .= '<p class="p-0 m-0 fw-bold">Against References :</p>';
                                    foreach ($description->references as $reference) {

                                        $sale = '';
                                        if ($reference->sale) {

                                            if ($reference->sale->order_status == 1) {

                                                $sale = '<p class="fw-bold" style="line-height:14px">Sales-Order : ' . $reference->sale->order_id . ' = ' . \App\Utils\Converter::format_in_bdt($reference->amount). ' ' . json_decode($generalSettings->business, true)['currency'].'</p>';
                                            } else {

                                                $sale = '<p class="fw-bold" style="line-height:14px">Sales :' . $reference?->sale->invoice_id . ' = ' . \App\Utils\Converter::format_in_bdt($reference->amount). ' ' .  json_decode($generalSettings->business, true)['currency'].'</p>';
                                            }
                                        }

                                        $purchase = '';
                                        if ($reference->purchase) {

                                            if ($reference->purchase->purchase_status == 1) {

                                                $purchase =  '<p style="line-height:14px"><strong>PI: </strong>' . $reference?->purchase?->invoice_id .' = '.\App\Utils\Converter::format_in_bdt($reference->amount) . ' ' . json_decode($generalSettings->business, true)['currency'] . '</p>';
                                            } else {

                                                $purchase =  '<p class="fw-bold" style="line-height:14px"><strong>PO: </strong>' . $reference?->purchase->invoice_id .' = '. \App\Utils\Converter::format_in_bdt($reference->amount) . ' ' . json_decode($generalSettings->business, true)['currency']  . '</p>';
                                            }
                                        }

                                        $stockAdjustment = '';
                                        if ($reference->stockAdjustment) {

                                            $stockAdjustment =  '<p class="fw-bold" style="line-height:14px">S. Adjustment: ' . $reference?->stockAdjustment->voucher_no . \App\Utils\Converter::format_in_bdt($reference->amount). ' ' . json_decode($generalSettings->business, true)['currency'] .'</p>';
                                        }

                                        $referencesDetails .=  $sale . $purchase . $stockAdjustment;
                                    }
                                }
                            @endphp

                            <td>{!! $amountType !!}</td>

                            <td style="border-bottom: 1px solid black!important;">
                                {!! $description?->account?->name.($description?->account?->account_number ? ' - A/c No : ' .$description?->account?->account_number : '') . $assignedUser . $transactionDetails . $referencesDetails !!}
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
                        <th colspan="3" class="text-end text-black"><span class="text-end">@lang('menu.total') : </span></th>
                        <th class="text-end text-black">{{ App\Utils\Converter::format_in_bdt($receipt->debit_total) }}</th>
                        <th class="text-end text-black">{{ App\Utils\Converter::format_in_bdt($receipt->credit_total) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="row">
            <div class="col-md-12">
                <p style="text-transform: uppercase; font-size:11px!important;" ><strong>@lang('menu.in_word') : </strong> {{ $inWord->format($receipt->debit_total) }} ({{ json_decode($generalSettings->business, true)['currency'] }}) @lang('menu.only').</p>
                <p class="p-0 m-0" style="font-size:11px!important;"><strong>@lang('menu.remarks') :</strong>  {{ $receipt->remarks }}</p>
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
                        <small>@lang('menu.powered_by') <strong>@lang('menu.speedDigit_software_solution').</strong></small>
                    @endif
                </div>

                <div class="col-4 text-end">
                    <small>@lang('menu.print_time') : {{ date($timeFormat) }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Voucher print templete end-->
