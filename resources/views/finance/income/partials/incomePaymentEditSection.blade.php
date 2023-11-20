<div class="row">
    <div class="col-12">
        <div class="form_element rounded m-0">
            <div class="element-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <label class="col-4"><strong>@lang('menu.total_receivable') </strong></label>

                            <div class="col-8">
                                <input readonly type="number" step="any" class="form-control" id="total_receivable"
                                    value="{{ $income->total_amount }}" tabindex="-1">
                            </div>
                        </div>

                        <div class="input-group">
                            <label class="col-4"><strong>@lang('menu.previous_received') </strong></label>

                            <div class="col-8">
                                <input readonly type="number" step="any" class="form-control"
                                    id="previous_received" value="{{ $income->received }}" tabindex="-1">
                            </div>
                        </div>

                        <div class="input-group">
                            <label class="col-4"><strong>@lang('menu.current_receivable') </strong></label>

                            <div class="col-8">
                                <input readonly type="number" step="any" class="form-control"
                                    id="current_receivable" value="{{ $income->due }}" tabindex="-1">
                            </div>
                        </div>

                        <div class="input-group">
                            <label class=" col-4"><strong>@lang('menu.received_amount') >></strong></label>

                            <div class="col-8">
                                <input required type="number" step="any" name="received_amount"
                                    class="form-control" id="received_amount" autocomplete="off">
                                <span class="error error_received_amount"></span>
                            </div>
                        </div>

                        <div class="input-group">
                            <label class="col-4"><b>@lang('menu.payment_method') </b></label>
                            <div class="col-8">
                                <select name="payment_method_id" class="form-control form-select"
                                    id="payment_method_id">
                                    @foreach ($methods as $method)
                                        <option
                                            data-account_id="{{ $method->methodAccount ? $method->methodAccount->account_id : '' }}"
                                            value="{{ $method->id }}">
                                            {{ $method->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="input-group mt-1">
                            <label class="col-4"><b>@lang('menu.debit_account')</b></label>
                            <div class="col-8">
                                <select required name="account_id" class="form-control special_text form-select"
                                    id="account_id">
                                    @php
                                        $firstAcBalance = '';
                                    @endphp
                                    @foreach ($accounts as $account)
                                        @php
                                            $accountType = $account->account_type == 1 ? ' Cash-In-Hand : ' : ($account->account_type == 2 ? 'Bank A/c: ' : 'BANK OD A/c: ');
                                            $bank = $account->bank ? ', Bank: ' . $account->bank : '';
                                            $ac_no = $account->account_number ? ', A/c No: ' . '..' . substr($account->account_number, -4) : '';
                                            
                                            if ($loop->index == 0) {
                                                $firstAcBalance = $account->balance;
                                            }
                                        @endphp
                                        <option class="special_text" data-account_balance="{{ $account->balance }}"
                                            value="{{ $account->id }}">
                                            &lt; {{ $accountType . $account->name . $bank . $ac_no }} &gt;
                                        </option>
                                    @endforeach
                                </select>
                                <label><strong>@lang('short.curr_balance') </strong><span
                                        id="account_closing_balance">{{ $firstAcBalance }}</span></label>
                                <span class="error error_account_id"></span>
                            </div>
                        </div>

                        <div class="input-group mt-1">
                            <label class="col-4 text-danger"><strong>@lang('menu.due_amount') </strong> </label>
                            <div class="col-8">
                                <input readonly type="number" step="any" name="due" id="due"
                                    class="form-control" value="{{ $income->due }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group">
                            <label class="col-2"><b>@lang('menu.receipt_note') </b></label>
                            <div class="col-10">
                                <textarea name="receipt_note" class="form-control ckEditor" cols="10" rows="3"
                                    placeholder="@lang('menu.receipt_note')"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 d-flex justify-content-end pt-2">
        <div class="loading-btn-box">
            <button type="button" class="btn btn-sm loading_button display-none"><i
                    class="fas fa-spinner"></i></button>
            <button class="btn btn-success submit_button">@lang('menu.save_change')</button>
        </div>
    </div>
</div>
