<div class="modal-dialog four-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.change_money_receipt_voucher_status') (@lang('menu.voucher_no') :
                {{ $receipt->invoice_id }} )</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>
        <div class="modal-body">
            <!--begin::Form-->
            <form id="change_voucher_status_form"
                action="{{ route('money.receipt.voucher.status.change', $receipt->id) }}" method="POST">
                @csrf
                <div class="row mt-2">
                    <div class="col-md-4">
                        <label><b>@lang('menu.received_amount') </b> <span class="text-danger">*</span> </label>
                        <input type="number" step="any" name="amount"
                            class="form-control form-control-sm vcs_input" id="received_amount"
                            data-name="Received amount" placeholder="@lang('menu.received_amount')" />
                        <span class="error error_vcs_received_amount"></span>
                    </div>

                    <div class="col-md-4">
                        <label><strong>@lang('menu.status') </strong> </strong> <span class="text-danger">*</span> </label>
                        <select disabled name="status" class="form-control form-control-sm mr_input form-select"
                            data-name="Money receipt status" id="vcs_status">
                            <option value="Pending">@lang('menu.pending')</option>
                            <option selected value="Completed">@lang('menu.completed')</option>
                        </select>
                        <span class="error error_vcs_status"></span>
                    </div>

                    <div class="col-md-4">
                        <label for="p_date"><strong>@lang('menu.date') :</strong> <span
                                class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i
                                        class="fas fa-calendar-week text-dark"></i></span>
                            </div>
                            <input type="date" name="date"
                                class="form-control form-control-sm date-picker p_input" autocomplete="off"
                                id="p_date" data-name="Date" value="{{ date('Y-m-d') }}">
                        </div>
                        <span class="error error_p_date"></span>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-4">
                        <label><strong>@lang('menu.payment_method') </strong> </strong></label>
                        <select name="payment_method_id" class="form-control form-control-sm form-select"
                            id="vcs_status">
                            @foreach ($paymentMethods as $paymentMethod)
                                <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-8">
                        <label><strong>@lang('menu.account') </strong> </strong> </label>
                        <select name="account_id" class="form-control form-control-sm form-select">
                            <option value="">@lang('menu.none')</option>
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->name }} (A/c:
                                    {{ $account->account_number }}) (Balance: {{ $account->balance }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button display-none"><i
                                    class="fas fa-spinner"></i></button>
                            <button type="submit" class="btn btn-sm btn-success float-end">@lang('menu.save')</button>
                            <button type="reset" data-bs-dismiss="modal"
                                class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
