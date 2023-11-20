<div class="modal-dialog col-45-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">Edit LC</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                    class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <!--begin::Form-->
            <form id="edit_lc_form" action="{{ route('manage.lc.update', $lc->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group row mt-1">
                    <div class="col-md-4">
                        <label><strong>LC No </strong> </label>
                        <input type="text" name="lc_no" class="form-control" id="e_name" placeholder="LC No"
                            autocomplete="off" value="{{ $lc->lc_no }}" />
                    </div>

                    <div class="col-md-4">
                        <label><strong>Opening Date </strong> <span class="text-danger">*</span></label>
                        <input type="text" name="opening_date" class="form-control add_input"
                            data-name="Opening Date" id="e_opening_date" placeholder="DD-MM-YYYYY"
                            value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($lc->opening_date)) }}"
                            autocomplete="off" />
                        <span class="error error_e_opening_date"></span>
                    </div>

                    <div class="col-md-4">
                        <label><strong>Last Date </strong> <span class="text-danger">*</span></label>
                        <input type="text" name="last_date" class="form-control add_input" data-name="Last Date"
                            id="e_last_date" placeholder="DD-MM-YYYYY"
                            value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($lc->last_date)) }}"
                            autocomplete="off" />
                        <span class="error error_e_last_date"></span>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-4">
                        <label><strong>@lang('menu.expire_date') </strong> <span class="text-danger">*</span></label>
                        <input type="text" name="expire_date" class="form-control add_input" data-name="Expire Date"
                            id="e_expire_date"
                            value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($lc->expire_date)) }}"
                            placeholder="DD-MM-YYYYY" autocomplete="off" />
                        <span class="error error_e_expire_date"></span>
                    </div>

                    <div class="col-md-4">
                        <label><strong>@lang('menu.type') </strong></label>
                        <select name="type" class="form-control form-select">
                            <option value="">@lang('menu.type')</option>
                            <option {{ $lc->type == 1 ? 'SELECTED' : '' }} value="1">Irrevocable LC</option>
                            <option {{ $lc->type == 2 ? 'SELECTED' : '' }} value="2">Revocable LC</option>
                            <option {{ $lc->type == 3 ? 'SELECTED' : '' }} value="3">Stand-by LC</option>
                            <option {{ $lc->type == 4 ? 'SELECTED' : '' }} value="4">Confirmed LC</option>
                            <option {{ $lc->type == 5 ? 'SELECTED' : '' }} value="5">Unconfirmed LC</option>
                            <option {{ $lc->type == 6 ? 'SELECTED' : '' }} value="6">Transferable LC</option>
                            <option {{ $lc->type == 7 ? 'SELECTED' : '' }} value="7">Back-to-Back LC</option>
                            <option {{ $lc->type == 8 ? 'SELECTED' : '' }} value="8">Payment at Sight LC</option>
                            <option {{ $lc->type == 9 ? 'SELECTED' : '' }} value="9">Deferred Payment LC</option>
                            <option {{ $lc->type == 10 ? 'SELECTED' : '' }} value="10">Red Clause LC</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label><strong>Currency </strong> <span class="text-danger">*</span></label>
                        <select name="currency_id" id="e_currency_id" class="form-control form-select">
                            <option value="">Select Currency</option>
                            @foreach ($currencies as $currency)
                                <option {{ $currency->id == $lc->currency_id ? 'SELECTED' : '' }}
                                    value="{{ $currency->id }}">{{ $currency->code }}</option>
                            @endforeach
                        </select>
                        <span class="error error_e_currency_id"></span>
                    </div>
                </div>

                {{-- <div class="form-group row mt-1">
                    <div class="col-md-4">
                        <label><strong>LC Amount </strong> <span class="text-danger">*</span></label>
                        <input type="number" step="any" name="lc_amount" class="form-control add_input" id="e_lc_amount" value="{{ $lc->lc_amount }}" data-name="LC Amount" placeholder="LC Amount" autocomplete="off"/>
                        <span class="error error_e_lc_amount"></span>
                    </div>

                    <div class="col-md-4">
                        <label><strong>Currency </strong></label>
                        <select name="currency" class="form-control form-select">
                            <option {{ $lc->currency == 1 ? 'SELECTED' : '' }} value="1">USD</option>
                            <option {{ $lc->currency == 2 ? 'SELECTED' : '' }} value="2">BDT</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label><strong>Rate </strong> <span class="text-danger">*</span></label>
                        <input type="number" step="any" name="currency_rate" class="form-control add_input" id="e_currency_rate" data-name="Rate" value="{{ $lc->currency_rate }}"  placeholder="Rate" autocomplete="off"/>
                        <span class="error error_e_currency_rate"></span>
                    </div>
                </div> --}}

                {{-- <div class="form-group row mt-1">
                    <div class="col-md-4">
                        <label><strong>@lang('menu.total_amount')(BDT) </strong> <span class="text-danger">*</span></label>
                        <input type="number" step="any" name="total_amount" class="form-control add_input" id="e_total_amount" data-name="@lang('menu.total_amount')(BDT)" value="{{ $lc->total_amount }}" placeholder="@lang('menu.total_amount')(BDT)" autocomplete="off"/>
                        <span class="error error_e_total_amount"></span>
                    </div>

                    <div class="col-md-4">
                        <label><strong>LC Margin Amount </strong></label>
                        <input type="number" step="any" name="lc_margin_amount" class="form-control" id="e_lc_margin_amount" data-name="LC Margin Amount" value="{{ $lc->lc_margin_amount }}" placeholder="LC Margin Amount" autocomplete="off"/>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-4">
                        <label><strong>Insurance Company</strong></label>
                        <input type="text" name="insurance_company" class="form-control" id="e_insurance_company" value="{{ $lc->insurance_company }}" placeholder="Insurance Company Name" autocomplete="off"/>
                    </div>

                    <div class="col-md-4">
                        <label><strong>Insurance Payable </strong> </label>
                        <input type="number" step="any" name="insurance_payable_amt" class="form-control" id="e_insurance_payable_amt" value="{{ $lc->insurance_payable_amt }}" placeholder="Insurance Payable" autocomplete="off"/>
                    </div>
                </div> --}}

                {{-- <div class="form-group row mt-1">
                    <div class="col-md-4">
                        <label><strong>Shipment Mode </strong></label>
                        <select name="shipment_mode" class="form-control form-select">
                            <option {{ $lc->shipment_mode == 1 ? 'SELECTED' : '' }} value="1">C N F</option>
                            <option {{ $lc->shipment_mode == 2 ? 'SELECTED' : '' }} value="2">FOB</option>
                            <option {{ $lc->shipment_mode == 3 ? 'SELECTED' : '' }} value="3">FCA</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label><strong>Mode Of Amount </strong> </label>
                        <input type="number" step="any" name="mode_of_amount" class="form-control" id="e_mode_of_amount" data-name="Mode Of Amount" value="{{ $lc->mode_of_amount }}" placeholder="Mode Of Amount" autocomplete="off"/>
                    </div>

                    <div class="col-md-4">
                        <label><strong>Total Lc Payable Amount </strong></label>
                        <input readonly type="text" name="total_payable_amt" class="form-control" id="e_total_payable_amt" data-name="Total Payable Amount" value="{{ $lc->total_payable_amt }}" placeholder="Total Payable Amount" autocomplete="off"/>
                        <span class="error error_e_total_payable_amt"></span>
                    </div>
                </div> --}}

                {{-- <div class="form-group row mt-1">
                    <div class="col-md-4">
                        <label><strong>@lang('menu.supplier') : </strong> <span class="text-danger">*</span></label>
                        <select name="supplier_id" class="form-control form-select" id="e_supplier_id" data-name="Supplier">
                            <option value="">@lang('menu.select_supplier')</option>
                            @foreach ($suppliers as $supplier)
                                <option {{ $supplier->id == $lc->supplier_id ? 'SELECTED' : '' }} value="{{ $supplier->id }}">{{ $supplier->name.'('.$supplier->phone.')' }}</option>
                            @endforeach
                        </select>
                        <span class="error error_e_supplier_id"></span>
                    </div>

                    <div class="col-md-4">
                        <label><strong>Advising Bank </strong> <span class="text-danger">*</span></label>
                        <select name="advising_bank_id" class="form-control form-select" id="e_advising_bank_id" data-name="Advising Bank">
                            <option value="">Select Advising Bank</option>
                            @foreach ($banks as $bank)
                                <option {{ $bank->id == $lc->advising_bank_id ? 'SELECTED' : '' }} value="{{ $bank->id }}">{{ $bank->name }}</option>
                            @endforeach
                        </select>
                        <span class="error error_e_advising_bank_id"></span>
                    </div>

                    <div class="col-md-4">
                        <label><strong>Issuing Bank </strong> <span class="text-danger">*</span></label>
                        <select name="issuing_bank_id" class="form-control form-select" id="e_issuing_bank_id" data-name="Issuing Bank">
                            <option value="">Select Issuing Bank</option>
                            @foreach ($banks as $bank)
                                <option {{ $bank->id == $lc->issuing_bank_id ? 'SELECTED' : '' }} value="{{ $bank->id }}">{{ $bank->name }}</option>
                            @endforeach
                        </select>
                        <span class="error error_e_issuing_bank_id"></span>
                    </div>
                </div> --}}

                {{-- <div class="form-group row mt-1">
                    <div class="col-md-4">
                        <label><strong>Opening Bank </strong> <span class="text-danger">*</span></label>
                        <select name="opening_bank_id" class="form-control form-select" id="e_opening_bank_id" data-name="Opening Bank">
                            <option value="">Select Opening Bank</option>
                            @foreach ($banks as $bank)
                                <option {{ $bank->id == $lc->opening_bank_id ? 'SELECTED' : '' }} value="{{ $bank->id }}">{{ $bank->name }}</option>
                            @endforeach
                        </select>
                        <span class="error error_e_opening_bank_id"></span>
                    </div>
                </div> --}}

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button display-none"><i
                                    class="fas fa-spinner"></i></button>
                            <button type="submit"
                                class="btn btn-sm btn-success float-end submit_button">@lang('menu.save')</button>
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
    //Add lc request by ajax
    $('#edit_lc_form').on('submit', function(e) {
        e.preventDefault();

        $('.loading_button').show();
        var url = $(this).attr('action');

        var request = $(this).serialize();
        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                $('.error').html('');
                $('.loading_button').hide();

                toastr.success(data);
                $('#editModal').modal('hide');
                lc_table.ajax.reload();
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
                }

                toastr.error('Please check again all form fields.', 'Some thing went wrong.');

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_e_' + key + '').html(error[0]);
                });
            }
        });
    });

    $(document).on('input', '#e_lc_amount', function(e) {

        calculateAmount();
    });

    $(document).on('input', '#e_currency_rate', function(e) {

        calculateAmount();
    });

    $(document).on('input', '#e_lc_margin_amount', function(e) {

        calculateAmount();
    });

    $(document).on('input', '#e_insurance_payable_amt', function(e) {

        calculateAmount();
    });

    $(document).on('input', '#e_mode_of_amount', function(e) {

        calculateAmount();
    });

    function calculateAmount() {

        var lc_amount = $('#e_lc_amount').val() ? $('#e_lc_amount').val() : 0;
        var rate = $('#e_currency_rate').val() ? $('#e_currency_rate').val() : 0;

        var totalAmount = parseFloat(lc_amount) * parseFloat(rate);
        $('#e_total_amount').val(parseFloat(totalAmount).toFixed(2));

        var lc_margin_amount = $('#e_lc_margin_amount').val() ? $('#e_lc_margin_amount').val() : 0;
        var insurance_payable_amt = $('#e_insurance_payable_amt').val() ? $('#e_insurance_payable_amt').val() : 0;
        var mode_of_amount = $('#e_mode_of_amount').val() ? $('#e_mode_of_amount').val() : 0;

        var totalPayableAmount = parseFloat(totalAmount) +
            parseFloat(lc_margin_amount) +
            parseFloat(insurance_payable_amt) +
            parseFloat(mode_of_amount);

        $('#e_total_payable_amt').val(parseFloat(totalPayableAmount).toFixed(2));
    }
</script>

<script type="text/javascript">
    new Litepicker({
        singleMode: true,
        element: document.getElementById('e_opening_date'),
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
        format: 'DD-MM-YYYY',
    });

    new Litepicker({
        singleMode: true,
        element: document.getElementById('e_last_date'),
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
        format: 'DD-MM-YYYY',
    });

    new Litepicker({
        singleMode: true,
        element: document.getElementById('e_expire_date'),
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
        format: 'DD-MM-YYYY',
    });
</script>
