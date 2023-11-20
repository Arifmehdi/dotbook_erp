<style>
    #submit_customer_basic_form .form-group label {
        text-align: right;
    }

    .table_tr_remove_btn:focus {
        box-sizing: border-box;
        box-shadow: 0 0 0 0.18rem rgb(231 49 49 / 50%);
        border: none;
        padding: 0px 1px;
        border-radius: 2px;
    }

    .table_tr_add_btn:focus {
        box-sizing: border-box;
        box-shadow: 0 0 0 0.18rem rgb(49 231 71 / 59%);
        border: none;
        padding: 0px 1px;
        border-radius: 2px;
    }
</style>
<div class="modal-dialog col-60-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_customer') <span class="type_name"></span></h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="submit_customer_basic_form" action="{{ route('contacts.customers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row mt-1">
                    <div class="col-lg-12">
                        <div class="form-group row p-1" id="business_type_winbox">
                            <label class="col-sm-3 ContactType"><b>{{ __("Contact Type") }}:</b></label>
                            <div class="col-sm-3 border-box">
                                <span>{{ __("Company") }}</span>
                                <input type="radio" checked name="contact_type" value="company" class="basic_contact_type check_company" id="basic_modal_contact_type">
                            </div>

                            <div class="col-sm-3 border-box">
                                <span>{{ __("Individual") }}</span>
                                <input type="radio" name="contact_type" value="individual" class="basic_contact_type check_individual" id="basic_modal_contact_type">
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3"><b>{{ __("Customer Name") }} :</b> <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input required type="text" id="name" name="name" class="form-control basic_name" data-next="phone" placeholder="Customer Name">
                                <span class="error error_name"></span>
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3"><b>{{ __("Phone Number") }} :</b> <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input required type="text" id="phone" name="phone" class="form-control basic_phone" data-next="business_name" placeholder="Phone Number">
                                <span class="error error_phone"></span>
                            </div>
                        </div>
                        <div class="form-group row p-1 ">
                            <label class="col-sm-3 basic_modal_company_feild"><b>{{ __("Company Name") }} :</b></label>
                            <label class="col-sm-3 basic_modal_individual_feild d-none"><b>{{ __("Companies") }} :</b></label>
                            <div class="col-sm-9">
                                <input type="text" name="business_name" id="business_name" class="form-control basic_business_name" data-next="address" placeholder="Companies">
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3 basic_modal_company_feild"><b>{{ __("Company Address") }} :</b></label>
                            <label class="col-sm-3 basic_modal_individual_feild d-none"><b>{{ __("Present Address") }} :</b></label>
                            <div class="col-sm-9">
                                <input type="text" name="address" id="address" class="form-control basic_address" data-next="trade_license_no" placeholder="Address">
                            </div>
                        </div>
                        <div class="form-group row p-1 basic_modal_company_feild">
                            <label class="col-sm-3"><b>{{ __("Trade Number") }} :</b></label>
                            <div class="col-sm-9">
                                <input type="text" name="trade_license_no" class="form-control basic_trade_license_no" id="trade_license_no" data-next="sr_user_id" placeholder="Trade Number">
                                <span class="error error_trade_license_no"></span>
                            </div>
                        </div>
                        {{-- <div class="form-group row p-1">
                            <label class="col-sm-3"><b>Opening Balance (Sr. Wise):</b></label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <input type="number" id="opening_balance" name="opening_balance" class="form-control basic_opening_balance" value="0">
                                    <select  class="form-control basic_balance_type form-select" name="opening_balance_type" id="opening_balance_type">
                                        <option value="debit">@lang('menu.debit')</option>
                                        <option value="credit">@lang('menu.credit')</option>
                                    </select>
                                </div>
                            </div>
                        </div> --}}
                        <div class="form-group row p-1 basic_sr_wise_opening_balance" id="basic_sr_wise_opening_balance">
                            <label class="col-sm-3"><b>{{ __("Opening Balance") }} (<strong>{{ __("Sr. Wise") }}</strong>):</b></label>
                            <div class="col-sm-9">
                                <table id="myTable">
                                    <tbody id="sr_body">
                                        <tr id="sr_opening_balance_row">
                                            <td style="width: 15%" class="align-items-end">
                                                <input readonly type="text" name="opening_balance_date" class="form-control fw-bold w-100" id="opening_balance_date" value="On : {{ date('d-M-y', strtotime(json_decode($generalSettings->business, true)['start_date'])) }}" tabindex="-1" />
                                            </td>
                                            <td style="width: 30%" class="align-items-end">
                                                <select name="sr_user_ids[]" class="form-control w-100 sr_user_id form-select" id="sr_user_id">
                                                    <option value="">@lang('menu.select_sr')</option>
                                                    @foreach ($srUsers as $user)
                                                        <option value="{{ $user->id }}">
                                                            {{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td style="width: 22.5%" class="align-items-end">
                                                <input type="number" step="any" name="sr_opening_balances[]" class="form-control w-100 sr_initial_opening_balance" id="sr_opening_balance" value="0.00" placeholder="0.00" />
                                            </td>

                                            <td style="width: 22.5%" class="align-items-end">
                                                <select name="sr_opening_balance_types[]" class="form-control w-100 form-select" id="sr_opening_balance_type">
                                                    <option value="debit">@lang('menu.debit')</option>
                                                    <option value="credit">@lang('menu.credit')</option>
                                                </select>
                                            </td>

                                            <td style="width: 10%" class="text-center" class="align-items-end">
                                                <div class="row g-0">
                                                    <div class="col-md-6">
                                                        <a href="#" onclick="addNewRow(this); return false;" id="add_new_opening_balance_row" class="table_tr_add_btn ms-1 d-inline"><i class="fa-solid fa-plus text-success mt-1"></i></a>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <a href="#" tabindex="-1" class="d-inline"><i class="fas fa-trash-alt text-secondary mt-1"></i></a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="form-group row p-1">
                            <label class="col-sm-3"><b>{{ __("Customer Type") }} :</b></label>
                            <div class="col-sm-3">
                                <select class=" form-control basic_customer_type form-select" name="customer_type" id="basic_modal_customer_type" data-next="credit_limit">
                                    <option value="1">@lang('menu.non_credit')</option>
                                    <option value="2">@lang('menu.credit')</option>
                                </select>
                            </div>

                            <label class="col-sm-2 basic_modal_term_hide d-none"><b>{{ __("Credit Limit") }} :</b></label>
                            <div class="col-sm-4 basic_modal_term_hide d-none">
                                <input type="number" name="credit_limit" class="form-control basic_credit_limit" id="credit_limit" value="0" data-next="pay_term">
                            </div>
                        </div>

                        <div class="form-group row p-1 basic_modal_term_hide d-none">
                            <label class="col-sm-3"><b>{{ __("Term") }} :</b></label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <select class="form-control basic_pay_term form-select" name="pay_term" id="pay_term" data-next="pay_term_number">
                                        <option value="2">{{ __("Days") }}</option>
                                        <option value="1">{{ __("Months") }}</option>
                                    </select>

                                    <input type="text" name="pay_term_number" class="form-control basic_pay_term_number" id="pay_term_number" data-next="basic_customer_save" placeholder="Number">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row justify-content-end mt-3">
                        <div class="col-md-12">
                            <button type="button" id="add_customer_details" class="btn btn-sm btn-primary me-0 float-end">{{ __("Add More Details") }}</button>
                        </div>
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button c_loading_button display-none"><i class="fas fa-spinner"></i></button>
                            <button type="button" id="basic_customer_save" class="btn btn-sm btn-success basic_customer_submit_button px-3">@lang('menu.save')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var srUsers = @json($srUsers)

    $('#sr_user_id').select2();

    $(document).on('click keypress focus blur change', '.form-control', function(event) {
        $('.basic_customer_submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.basic_customer_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#submit_customer_basic_form').on('submit', function(e) {
        e.preventDefault();

        $('.c_loading_button').show();
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
                $('.error').html('');
                toastr.success('Customer added successfully.');
                $('.c_loading_button').hide();
                $('#add_customer_basic_modal').modal('hide');

                var customerAccountId = $('#customer_account_id').val();

                if (customerAccountId != undefined) {

                    $('#customer_account_id').append('<option data-customer_name="' + data.name + '" data-customer_phone="' + data.phone + '" value="' + data.customer_account_id + '">' + data.name + '/' + data.phone + '</option>'
                    );

                    $('#customer_account_id').val(data.customer_account_id);

                    var user_id = $('#user_id').val();
                    getCustomerAmountsUserWise(user_id, data.customer_account_id, false);
                    calculateTotalAmount();
                } else {

                    table.ajax.reload();
                    refresh();
                }
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.c_loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if (err.status == 500) {

                    toastr.error('Server Error. Please contact to the support team.');
                    return;
                }

                toastr.error('Please check again all form fields.', 'Some thing went wrong.');

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });

    $('#add_customer_details').on('click', function() {

        $.get("{{ route('contacts.customers.create.detailed.modal') }}", function(data) {

            $('#add_customer_detailed_modal').html(data);
            $('#add_customer_detailed_modal').modal('show');

            var basic_name = $('.basic_name').val();
            var basic_phone = $('.basic_phone').val();
            var basic_contact_type = $('.basic_contact_type').val();
            var basic_business_name = $('.basic_business_name').val();
            var basic_trade_license_no = $('.basic_trade_license_no').val();
            var basic_address = $('.basic_address').val();
            var basic_opening_balance = $('.basic_opening_balance').val();
            var basic_balance_type = $('.basic_balance_type').val();
            var basic_customer_type = $('.basic_customer_type').val();
            var basic_credit_limit = $('.basic_credit_limit').val();
            var basic_pay_term = $('.basic_pay_term').val();
            var basic_pay_term_number = $('.basic_pay_term_number').val();

            $('.big_name').val(basic_name);
            $('.big_phone').val(basic_phone);
            // $('.big_contact_type').val(basic_contact_type);
            $('.big_business_name').val(basic_business_name);
            $('.big_trade_license_no').val(basic_trade_license_no);
            $('.big_address').val(basic_address);
            $('.big_opening_balance').val(basic_opening_balance);
            $('.big_balance_type').val(basic_balance_type);
            $('.big_customer_type').val(basic_customer_type);
            $('.big_credit_limit').val(basic_credit_limit);
            $('.big_pay_term').val(basic_pay_term);
            $('.big_pay_term_number').val(basic_pay_term_number);

            if (basic_customer_type == 2) {

                $('#credit_limit').val('');
                $('.big_modal_term_hide').removeClass('d-none');
            } else {

                $('#credit_limit').val(0);
                $('.big_modal_term_hide').addClass('d-none');
            }

            if ($('.check_individual').is(':checked')) {

                $('.big_individual').prop('checked', true);
                $('.big_modal_company_feild').addClass('d-none');
                $('.big_modal_individual_feild').removeClass('d-none')
            } else if ($('.check_company').is(':checked')) {

                $('.big_company').prop('checked', true);
                $('.big_modal_company_feild').removeClass('d-none');
                $('.big_modal_individual_feild').addClass('d-none');
            }
        });
    });

    $('#basic_modal_customer_type').on('change', function() {

        if ($(this).val() == 1) {
            $('.basic_modal_term_hide').addClass('d-none');
        } else {
            $('.basic_modal_term_hide').removeClass('d-none');
        }
    });

    $(document).on('change', '#basic_modal_contact_type', function() {

        if ($(this).val() == 'individual') {

            $('.basic_modal_company_feild').addClass('d-none');
            $('.basic_modal_individual_feild').removeClass('d-none')
        } else {

            $('.basic_modal_company_feild').removeClass('d-none');
            $('.basic_modal_individual_feild').addClass('d-none');
        }
    });

    var count = 0;

    function addNewRow(val) {

        var tr = '';
        var main = $('.main_select_box').html();
        tr += '<tr id="sr_opening_balance_row" class="user' + count + '">';

        tr += '<td style="width: 15%" class="align-items-end">';
        tr += '<input readonly type="text" name="opening_balance_date" class="form-control fw-bold w-100" id="sr_opening_balance_date" value="On : {{ date('d-M-y', strtotime(json_decode($generalSettings->business, true)['start_date'])) }}" tabindex="-1"/>';
        tr += '</td>';

        tr += '<td style="width: 30%" class="align-items-end">';
        tr += '<select required name="sr_user_ids[]" class="form-control my-select2 w-100 sr_user_id form-select" id="sr_user_id' + count + '" autofocus>';
        tr += '<option value="">Select Sr.</option>';

        srUsers.forEach(function(user) {

            tr += '<option value="' + user.id + '">' + user.prefix + ' ' + user.name + ' ' + user.last_name +
                '</option>';
        });

        tr += '</select>';
        tr += '</td>';

        tr += '<td style="width: 22.5%" class="align-items-end">';
        tr += '<input required type="number" step="any" name="sr_opening_balances[]" class="form-control w-100" id="sr_opening_balance" value="0.00" placeholder="0.00"/>';
        tr += '</td>';

        tr += '<td style="width: 22.5%" class="align-items-end">';
        tr += '<select name="sr_opening_balance_types[]" class="form-control w-100 form-select" id="sr_opening_balance_type">';
        tr += '<option value="debit">@lang('menu.debit')</option>';
        tr += '<option value="credit">@lang('menu.credit')</option>';
        tr += '</select>';
        tr += '</td>';

        tr += '<td style="width: 10%" class="text-center" class="align-items-end">';
        tr += '<div class="row g-0">';
        tr += '<div class="col-md-6">';
        tr += '<a href="#" onclick="addNewRow(this); return false;" id="add_new_opening_balance_row" class="table_tr_add_btn ms-1 d-inline"><i class="fa-solid fa-plus text-success mt-1"></i></a>';
        tr += '</div>';

        tr += '<div class="col-md-6">';
        tr += '<a href="#" id="remove_row_btn" class="table_tr_remove_btn d-inline"><i class="fas fa-trash-alt text-danger mt-1"></i></a>';
        tr += '</div>';
        tr += '</div>';
        tr += '</td>';

        tr += '</tr>';

        $('#sr_body').append(tr);
        $('#sr_user_id' + count, '#sr_body').select2();
        count++;
    }

    $(document).on('click', '#remove_row_btn', function(e) {
        e.preventDefault();
        var tr = $(this).closest('tr');
        previousTr = tr.prev();
        nxtTr = tr.next();
        tr.remove();
    });

    setTimeout(function() {

        $('.basic_name').focus();
    }, 500);

    $(document).on('change keypress', 'input', function(e) {
        var nextId = $(this).data('next');
        if (e.which == 13) {
            e.preventDefault();
            if ($(this).attr('id') == 'customer_address') {

                $('#sr_user_id').focus();
                return;
            }
            $('#' + nextId).focus().select();
        }
    });

    $(document).on('change keypress click', 'select', function(e) {
        var value = $(this).val();
        var nextId = $(this).data('next');
        if (e.which == 0) {

            if ($(this).attr('id') == 'basic_modal_customer_type' && $('#basic_modal_customer_type').val() ==
                1) {

                $('#basic_customer_save').focus();
                return;
            }
            $('#' + nextId).focus().select();
        }
    });

    $(document).on('select2:close', '.sr_user_id', function(e) {
        var nextId = $(this).closest('tr').find('#sr_opening_balance');
        setTimeout(function() {

            nextId.focus().select();
        }, 100);
    });

    $(document).on('keypress', '#sr_opening_balance', function(e) {
        var nextId = $(this).closest('tr').find('#sr_opening_balance_type');
        if (e.which == 13) {

            nextId.focus().select();
        }
    });

    $(document).on('change keypress click', '#sr_opening_balance_type', function(e) {
        var nextId = $(this).closest('tr').find('#add_new_opening_balance_row');
        if (e.which == 0) {

            nextId.focus();
        }
    });

    $(document).on('input', '.sr_initial_opening_balance', function(e) {
        var value = $(this).val();
        if (value != '' && parseFloat(value) > 0) {
            $(this).closest('tr').find('#sr_user_id').prop('required', true);
        } else {
            $(this).closest('tr').find('#sr_user_id').prop('required', false);
        }
    });
</script>
