<style>
    .payment_top_card {
        background: #d7dfe8;
    }

    .payment_top_card span {
        font-size: 12px;
        font-weight: 400;
    }

    .payment_top_card li {
        font-size: 12px;
    }

    .payment_top_card ul {
        padding: 6px;
    }

    .payment_list_table {
        position: relative;
    }

    .payment_details_contant {
        background: azure !important;
    }
</style>
<div class="modal-dialog four-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="payment_heading">@lang('menu.edit') @lang('menu.income_receipt')</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                    class="fas fa-times"></span></a>
        </div>
        <div class="modal-body" id="payment-modal-body">
            <div class="info_area mb-2">
                <div class="row">
                    <div class="col-md-6">
                        <div class="payment_top_card">
                            <ul class="list-unstyled">
                                <li><strong> @lang('menu.voucher_no') : </strong>{{ $receipt->income->voucher_no }} </li>
                                <li><strong>@lang('menu.business_location') : </strong>
                                    {{ json_decode($generalSettings->business, true)['shop_name'] }}
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="payment_top_card">
                            <ul class="list-unstyled">
                                <li class="income_due"><strong>@lang('menu.receivable')/@lang('menu.due') :
                                    </strong>{{ $receipt->income->due }} </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <form id="edit_receipt_form" action="{{ route('income.receipts.update', $receipt->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <div class="col-md-4">
                        <label><strong>@lang('menu.received_amount') </strong> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i
                                        class="far fa-money-bill-alt text-dark input_i"></i></span>
                            </div>

                            <input required type="number" name="received_amount" class="form-control" step="any"
                                data-name="Amount" value="{{ $receipt->amount }}" />
                        </div>
                        <span class="error error_amount"></span>
                    </div>

                    <div class="col-md-4">
                        <label for="p_date"><strong>@lang('menu.date') </strong> <span
                                class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i
                                        class="fas fa-calendar-week text-dark input_i"></i></span>
                            </div>
                            <input type="text" name="date" class="form-control" id="date" data-name="Date"
                                value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($receipt->report_date)) }}"
                                autocomplete="off">
                        </div>
                        <span class="error error_date"></span>
                    </div>

                    <div class="col-md-4">
                        <label><strong>@lang('menu.payment_method') </strong> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i
                                        class="fas fa-money-check text-dark input_i"></i></span>
                            </div>
                            <select name="payment_method_id" class="form-control form-select" id="payment_method_id">
                                @foreach ($methods as $method)
                                    <option {{ $receipt->payment_method_id == $method->id ? 'SELECTED' : '' }}
                                        value="{{ $method->id }}">
                                        {{ $method->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-4">
                        <label><strong>@lang('menu.debit_account') </strong> </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i
                                        class="fas fa-money-check-alt text-dark input_i"></i></span>
                            </div>
                            <select required name="account_id" class="form-control special_text form-select"
                                id="account_id">
                                @php
                                    $selectedAcBalance = '';
                                @endphp
                                @foreach ($accounts as $account)
                                    @php
                                        $accountType = $account->account_type == 1 ? ' Cash-In-Hand : ' : ($account->account_type == 2 ? 'Bank A/c: ' : 'BANK OD A/c: ');
                                        $bank = $account->bank ? ', Bank: ' . $account->bank : '';
                                        $ac_no = $account->account_number ? ', A/c No: ' . '..' . substr($account->account_number, -4) : '';
                                        
                                        if ($account->id == $receipt->account_id) {
                                            $selectedAcBalance = $account->balance;
                                        }
                                    @endphp
                                    <option {{ $account->id == $receipt->account_id ? 'SELECTED' : '' }}
                                        class="special_text" data-account_balance="{{ $account->balance }}"
                                        value="{{ $account->id }}">
                                        &lt; {{ $accountType . $account->name . $bank . $ac_no }} &gt;
                                    </option>
                                @endforeach
                            </select>
                            <span class="error error_account_id"></span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label><strong></strong> </label>
                        <div class="input-group">
                            <label><strong>@lang('short.curr_balance') </strong><span
                                    id="account_closing_balance">{{ $selectedAcBalance }}</span></label>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-2">
                    <label><strong> @lang('menu.receipt_note') </strong></label>
                    <textarea name="received_note" class="form-control ckEditor" id="note" cols="30" rows="3"
                        placeholder="@lang('menu.note')">{{ $receipt->note }}</textarea>

                    <div class="form-group row mt-3">
                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="loading-btn-box">
                                <button type="button" class="btn btn-sm loading_button display-none"><i
                                        class="fas fa-spinner"></i></button>
                                <button type="submit"
                                    class="btn btn-sm btn-success float-end">@lang('menu.save_change')</button>
                                <button type="reset" data-bs-dismiss="modal"
                                    class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>

<script>
    //Add income receipt request by ajax
    $('#edit_receipt_form').on('submit', function(e) {
        e.preventDefault();

        $('.loading_button').show();
        var url = $(this).attr('action');

        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                    $('.loading_button').hide();
                } else {

                    $('.loading_button').hide();
                    $('#receiptAddOrEditModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(data);
                }
            },
            error: function(err) {

                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if (err.status == 500) {

                    toastr.error('Server error. Please contact to the support team.');
                    return;
                } else if (err.status == 403) {

                    toastr.error('Access Denied');
                    return;
                }

                toastr.error('Please check again all form fields.', 'Some thing went wrong.');

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });

    var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
    var _expectedDateFormat = '';
    _expectedDateFormat = dateFormat.replace('d', 'DD');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');

    new Litepicker({
        singleMode: true,
        element: document.getElementById('date'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: _expectedDateFormat,
    });

    $(document).on('change', '#account_id', function() {

        var accountBalance = $(this).find('option:selected').data('account_balance');
        $('#account_closing_balance').html(accountBalance);
    });
</script>
