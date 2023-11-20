@php
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
@endphp
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

    h6.checkbox_input_wrap {
        border: 1px solid #495677;
        padding: 0px 7px;
    }
</style>
<div class="modal-dialog col-60-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_payment_against_reference')</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                    class="fas fa-times"></span></a>
        </div>

        <div class="modal-body">
            <!--begin::Form-->
            <div class="info_area mb-2">
                <div class="row">
                    <div class="col-md-4">
                        <div class="payment_top_card">
                            <ul class="list-unstyled">
                                <li><strong>@lang('menu.supplier') : </strong><b>{{ $purchase->supplier->name }}</b></li>
                                <li><strong>@lang('menu.address') : </strong><b>{{ $purchase->supplier->address }}</b></li>
                                <li><strong>@lang('menu.phone') : </strong><b>{{ $purchase->supplier->phone }}</b></li>
                                <li>
                                    <strong>@lang('menu.curr_balance') : </strong>
                                    @php
                                        $accountUtil = new App\Utils\AccountUtil();
                                        $amounts = $accountUtil->accountClosingBalance($purchase->supplier_account_id);
                                    @endphp
                                    <b>{{ $amounts['closing_balance_string'] }}</b>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="payment_top_card">
                            <ul class="list-unstyled">
                                <li><strong>@lang('menu.invoice_id') : </strong><b>{{ $purchase->invoice_id }}</b></li>
                                <li><strong>@lang('menu.b_location') : </strong>
                                    <b>{{ json_decode($generalSettings->business, true)['shop_name'] }}</b>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="payment_top_card">
                            <ul class="list-unstyled">
                                <li><b>@lang('menu.invoice_amount') : </b>
                                    <strong>
                                        {{ App\Utils\Converter::format_in_bdt($purchase->total_purchase_amount) }}</strong>
                                </li>

                                <li><b class="text-success">@lang('menu.paid') @lang('menu.on_invoice') : </b>
                                    <strong> {{ App\Utils\Converter::format_in_bdt($purchase->paid) }} </strong>
                                </li>

                                <li><b class="text-danger">@lang('menu.due') @lang('menu.on_invoice') : </b>
                                    <strong> {{ App\Utils\Converter::format_in_bdt($purchase->due) }} </strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <form id="payment_form" action="{{ route('purchases.payments.store', $purchase->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="action" id="action">
                <div class="form-group row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <label><strong>@lang('menu.paying_amount')</strong> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">
                                            <i class="far fa-money-bill-alt text-dark input_i"></i>
                                        </span>
                                    </div>

                                    <input required type="number" step="any" name="paying_amount"
                                        class="form-control fw-bold" id="purchase_payment_paying_amount" value="0.00"
                                        data-next="purchase_payment_date" />
                                </div>
                                <span class="error error_purchase_payment_paying_amount"></span>
                            </div>

                            <div class="col-md-6">
                                <label><strong>@lang('menu.date')</strong> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">
                                            <i class="fas fa-calendar-week text-dark input_i"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="date" class="form-control" autocomplete="off"
                                        id="purchase_payment_date"
                                        value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}"
                                        data-next="purchase_payment_payment_method_id">
                                </div>
                                <span class="error error_purchase_payment_date"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label><strong>@lang('menu.payment_type')</strong> <span class="text-danger">*</span></label>
                                <select name="payment_method_id" class="form-control form-select"
                                    id="purchase_payment_payment_method_id" data-next="purchase_payment_account_id">
                                    @foreach ($methods as $method)
                                        <option
                                            data-account_id="{{ $method->methodAccount ? $method->methodAccount->account_id : '' }}"
                                            value="{{ $method->id }}">
                                            {{ $method->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="error error_purchase_payment_payment_method_id"></span>
                            </div>

                            <div class="col-md-6">
                                <label><strong>@lang('menu.credit_account')</strong> <span class="text-danger">*</span> </label>
                                <select name="account_id" class="form-control select2 form-select"
                                    id="purchase_payment_account_id" data-next="purchase_payment_transaction_no">
                                    <option value="">@lang('menu.select_credit_ac')</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}">
                                            @php
                                                $bank = $account->bank ? ', BK : ' . $account->bank : '';
                                                $ac_no = $account->account_number ? ', A/c No : ' . $account->account_number : '';
                                            @endphp
                                            {{ $account->name . $bank . $ac_no }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="error error_purchase_payment_account_id"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="row" style="border-left:1px solid black;">
                            <div class="col-md-12">
                                <label><strong> @lang('menu.transaction_no') </strong></label>
                                <input name="transaction_no" class="form-control" id="purchase_payment_transaction_no"
                                    data-next="purchase_payment_cheque_no" placeholder="@lang('menu.transaction_no')">
                            </div>

                            <div class="col-md-12">
                                <label><strong> @lang('menu.cheque_no') </strong></label>
                                <input name="cheque_no" class="form-control" id="purchase_payment_cheque_no"
                                    data-next="purchase_payment_cheque_serial_no" placeholder="@lang('menu.cheque_no')">
                            </div>

                            <div class="col-md-12">
                                <label><strong> @lang('menu.cheque_serial_no') </strong></label>
                                <input name="cheque_serial_no" class="form-control"
                                    id="purchase_payment_cheque_serial_no" data-next="purchase_payment_issue_date"
                                    placeholder="@lang('menu.cheque_serial_no')">
                            </div>

                            <div class="col-md-12">
                                <label><strong> @lang('menu.issue_date') </strong></label>
                                <input name="issue_date" class="form-control" id="purchase_payment_issue_date"
                                    data-next="purchase_payment_remarks" placeholder="@lang('menu.issue_date')">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-2">
                    <label><strong> @lang('menu.remarks') </strong></label>
                    <input name="remarks" class="form-control" id="purchase_payment_remarks" data-next="save"
                        placeholder="@lang('menu.remarks')">
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 text-end">
                        <div class="btn-box-2 pt-0 justify-content-end">
                            <button type="button"
                                class="btn btn-sm loading_button purchase_payment_loading_btn d-none"><i
                                    class="fas fa-spinner text-primary"></i><b> @lang('menu.loading')</b></button>

                            <button type="submit" id="save" class="btn btn-sm btn-success submit_button"
                                value="save">@lang('menu.save')</button>
                            <button type="submit" id="save_and_print" class="btn btn-sm btn-success submit_button"
                                value="save_and_print">@lang('menu.save_and_print')</button>
                            <button type="reset" data-bs-dismiss="modal"
                                class="btn btn-sm btn-danger">@lang('menu.close')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('.select2').select2();

    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.submit_button', function() {

        var value = $(this).val();
        $('#action').val(value);

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    //Add purchase payment request by ajax
    $('#payment_form').on('submit', function(e) {
        e.preventDefault();

        $('.purchase_payment_loading_btn').show();

        var url = $(this).attr('action');

        isAjaxIn = false;
        isAllowSubmit = false;

        $.ajax({
            beforeSend: function() {
                isAjaxIn = true;
            },
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                isAjaxIn = true;
                isAllowSubmit = true;

                $('.purchase_payment_loading_btn').hide();

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                    return;
                } else if (data.successMsg) {

                    toastr.success(data.successMsg);
                } else {

                    toastr.success('Payment is added successfully.');

                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('css/print/purchase.print.css') }}",
                        removeInline: false,
                        printDelay: 1000,
                        header: null,
                    });
                }

                $('.purchase_table').DataTable().ajax.reload();
                $('#purchasePaymentModal').modal('hide');
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;

                $('.purchase_payment_loading_btn').hide();
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

                $.each(err.responseJSON.errors, function(key, error) {
                    $('.error_purchase_payment_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });

    $('select').on('select2:select', function(e) {

        var nextId = $(this).data('next');
        $('#' + nextId).focus();

        setTimeout(function() {

            $('#' + nextId).focus();
        }, 100);
    });

    $(document).on('change keypress click', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            $('#' + nextId).focus().select();
        }
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            if ($(this).attr('id') == 'purchase_payment_paying_amount' &&
                ($('#purchase_payment_paying_amount').val() == 0 || $('#purchase_payment_paying_amount')
                .val() == '')
            ) {

                return;
            }

            $('#' + nextId).focus().select();
        }
    });

    var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
    var _expectedDateFormat = '';
    _expectedDateFormat = dateFormat.replace('d', 'DD');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');
    new Litepicker({
        singleMode: true,
        element: document.getElementById('purchase_payment_date'),
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

    $('#purchase_payment_payment_method_id').on('change', function() {

        var account_id = $(this).find('option:selected').data('account_id');
        setMethodAccount(account_id);
    });

    function setMethodAccount(account_id) {

        if (account_id) {

            $('#purchase_payment_account_id').val(account_id).trigger('change');
        } else if (account_id === '') {

            $('#purchase_payment_account_id option:first-child').prop("selected", true);
        }
    }

    setMethodAccount($('#purchase_payment_payment_method_id').find('option:selected').data('account_id'));
</script>
