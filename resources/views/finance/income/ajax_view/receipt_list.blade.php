<style>
    .payment_top_card {background: #d7dfe8;}
    .payment_top_card span {font-size: 12px;font-weight: 400;}
    .payment_top_card li {font-size: 12px;}
    .payment_top_card ul {padding: 6px;}
    .payment_list_table {position: relative;}
    .payment_details_contant{background: azure!important;}
</style>
<div class="modal-dialog four-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.receipt_list')</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <div class="info_area mb-2">
                <div class="row">
                    <div class="col-md-6">
                        <div class="payment_top_card">
                            <ul class="list-unstyled">
                                <li><strong> @lang('menu.voucher_no') : </strong>{{ $income->voucher_no }} </li>
                                <li><strong>@lang('menu.date') : </strong>{{ $income->report_date }}</li>
                                <li><strong>@lang('menu.receive_status') : </strong>
                                    @php
                                       $receivable = $income->total_amount;
                                    @endphp

                                    @if ($income->due <= 0)

                                        <span class="badge bg-success">@lang('menu.paid')</span>
                                    @elseif ($income->due > 0 && $income->due < $receivable)

                                        <span class="badge bg-primary text-white">@lang('menu.partial')</span>
                                    @elseif ($receivable == $income->due)

                                        <span class="badge bg-danger text-white">Due</span>
                                    @endif
                                </li>
                                <li><strong>@lang('menu.business_location') : </strong>
                                    {{ json_decode($generalSettings->business, true)['shop_name'] }}
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="payment_top_card">
                            <ul class="list-unstyled">
                                <li><strong>@lang('menu.total_receivable') : </strong>{{ $income->total_amount }}</li>
                                <li><strong>@lang('menu.total_received') : </strong>{{ $income->received }}</li>
                                <li><strong>@lang('menu.due') : </strong>{{ $income->due }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="payment_list_table">
                <div class="data_preloader modal_preloader"> <h6><i class="fas fa-spinner"></i> @lang('menu.processing')</h6></div>
                <div class="table-responsive">
                    <table class="display modal-table table-sm table-striped">
                        <thead>
                            <tr>
                                <th class="text-startx">@lang('menu.date')</th>
                                <th class="text-startx">@lang('menu.voucher_no')</th>
                                <th class="text-startx">@lang('menu.note')</th>
                                <th class="text-startx">@lang('menu.method')</th>
                                <th class="text-startx">@lang('menu.account')</th>
                                <th class="text-startx">@lang('menu.amount')</th>
                                <th class="text-startx">@lang('menu.action')</th>
                            </tr>
                        </thead>
                        <tbody id="payment_list_body">
                            @if (count($income->incomeReceipts) > 0)

                                @foreach ($income->incomeReceipts as $receipt)
                                    <tr>
                                        <td class="text-start">{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($receipt->report_date)) }}</td>
                                        <td class="text-start">{{ $receipt->voucher_no }}</td>
                                        <td class="text-start">{{ $receipt->note }}</td>

                                        <td class="text-start">{{ $receipt->method ? $receipt->method->name : '' }}</td>

                                        <td class="text-start">{{ $receipt->account ? $receipt->account->name : 'N/A' }}</td>

                                        <td class="text-start">{{ $receipt->amount }}</td>

                                        <td class="text-start">
                                            <a href="{{ route('income.receipts.edit', $receipt->id) }}" id="edit_receipt" class="btn-sm"><i class="fas fa-edit text-info"></i></a>
                                            <a href="{{ route('income.receipts.show', $receipt->id) }}" id="receipt_details" class="btn-sm"><i class="fas fa-eye text-primary"></i></a>
                                            <a href="{{ route('income.receipts.delete', $receipt->id) }}" id="delete_receipt" class="btn-sm"><i class="far fa-trash-alt text-danger"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center">@lang('menu.no_data_found')</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <form id="receipt_deleted_form" action="" method="post">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

