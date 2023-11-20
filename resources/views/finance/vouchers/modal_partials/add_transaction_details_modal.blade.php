<div class="modal fade" id="addTransactionDetailsModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog double-col-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">@lang('menu.transaction_details')</h6>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                    <span class="fas fa-times"></span>
                </a>
            </div>

            <div class="modal-body">
                <form id="add_transaction_details_form" action="" method="POST">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <label class="col-md-3"><b>@lang('menu.type') :</b></label>
                                <div class="col-md-9">
                                    <select id="trans_payment_method_id" class="form-control trans_input form-select">
                                        <option value="">None</option>
                                        @foreach ($paymentMethods as $method)
                                            <option value="{{ $method->id }}">{{ $method->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="input-group">
                                <label class="col-md-3"><b>@lang('menu.transaction_no') :</b></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control trans_input" id="trans_transaction_no" placeholder="@lang('menu.transaction_no')">
                                </div>
                            </div>

                            <div class="input-group mt-1">
                                <label class="col-md-3"><b>@lang('menu.cheque_no') :</b></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control trans_input" id="trans_cheque_no" placeholder="@lang('menu.cheque_no')">
                                </div>
                            </div>

                            <div class="input-group mt-1">
                                <label class="col-md-3"><b>@lang('menu.cheque_serial_no') :</b></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control trans_input" id="trans_cheque_serial_no" placeholder="@lang('menu.cheque_serial_no')">
                                </div>
                            </div>

                            <div class="input-group mt-1">
                                <label class="col-md-3"><b>@lang('menu.cheque_issue_date') :</b></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control trans_input" id="trans_cheque_issue_date" placeholder="@lang('menu.cheque_issue_date')">
                                </div>
                            </div>

                            <div class="input-group mt-1">
                                <label class="col-md-3"><b>@lang('menu.remarkable_note') :</b></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control trans_input" id="trans_remarkable_note" placeholder="@lang('menu.remarkable_note')">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
