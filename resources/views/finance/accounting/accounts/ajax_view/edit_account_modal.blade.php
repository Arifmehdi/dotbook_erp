<style>
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
<div class="modal-dialog col-50-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.edit_account')</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <!--begin::Form-->
            <form id="edit_account_form" action="{{ route('accounting.accounts.update', $account->id) }}">
                @csrf
                <div class="form-group">
                    <label><strong>@lang('menu.name') </strong> <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control" id="account_name" value="{{ $account->name }}" data-next="account_group_id" placeholder="@lang('menu.account_name')" autocomplete="off" autofocus />
                    <span class="error error_name"></span>
                </div>

                @if ($account->is_main_pl_account == 0)
                    <div class="form-group mt-1">
                        <label><strong>@lang('menu.account_group') <span class="text-danger">*</span></strong></label>
                        <div class="input-group select-customer-input-group">
                            <div style="display: inline-block; margin-bottom: 2px;" class="select-half">
                                <select required name="account_group_id" class="form-control select2 form-select" id="account_group_id">
                                    <option value="">@lang('menu.select') @lang('menu.account_group')</option>
                                    @foreach ($groups as $group)
                                        <option {{ $account->account_group_id == $group->id ? 'SELECTED' : '' }} value="{{ $group->id }}" data-is_allowed_bank_details="{{ $group->is_allowed_bank_details }}" data-is_bank_or_cash_ac="{{ $group->is_bank_or_cash_ac }}" data-is_fixed_tax_calculator="{{ $group->is_fixed_tax_calculator }}" data-is_default_tax_calculator="{{ $group->is_default_tax_calculator }}" data-main_group_number="{{ $group->main_group_number }}" data-sub_group_number="{{ $group->sub_group_number }}" data-sub_sub_group_number="{{ $group->sub_sub_group_number }}">
                                            {{ $group->name }}{{ $group->parentGroup ? '-(' . $group->parentGroup->name . ')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="error error_account_group_id"></span>
                            </div>

                            <div style="display: inline-block;" class="style-btn">
                                <div class="input-group-prepend">
                                    <span href="{{ route('accounting.groups.create') }}" class="input-group-text add_button mr-1" id="addAccountGroupBtn" data-group_id=""><i class="fas fa-plus-square text-dark"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="form-group mt-1">
                        <label><strong>@lang('menu.account_group') </strong></label>
                        <input readonly required type="text" class="form-control fw-bold" value="{{ $account?->group?->name }}" />
                        <input type="hidden" name="account_group_id" value="{{ $account->account_group_id }}">
                    </div>
                @endif

                <div class="form-group row mt-1 customer_account_field {{ $account->group->sub_sub_group_number == 6 ? '' : 'display-none' }}">
                    <div class="col-md-12">
                        <label><strong> {{ __("Phone No.") }} : </strong><span class="text-danger">*</span></label>
                        <input {{ $account->group->sub_sub_group_number == 6 ? 'required' : '' }} type="text" name="customer_phone_no" class="form-control hidden_required" id="customer_phone_no" value="{{ $account->phone }}" data-next="customer_type" placeholder="Phone Number" />
                        <span class="error error_customer_phone_no"></span>
                    </div>

                    <div class="col-md-12">
                        <label><strong> {{ __("Type") }} </strong></label>
                        <div class="input-group">
                            <select name="customer_type" class="form-control form-select" id="customer_type" data-next="customer_credit_limit">
                                <option {{ $account?->customer?->customer_type == 1 ? 'SELECTED' : '' }} value="1">Non-Credit</option>
                                <option {{ $account?->customer?->customer_type == 2 ? 'SELECTED' : '' }} value="2">Credit</option>
                            </select>

                            <input type="number" name="customer_credit_limit" class="form-control {{ $account?->customer?->customer_type == 2 ? '' : 'display-none' }}" id="customer_credit_limit" value="{{ $account?->customer?->credit_limit }}" data-next="customer_address" placeholder="Credit Limit" />
                            <span class="error error_customer_credit_limit"></span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label><strong> {{ __("Address") }} </strong></label>
                        <input type="text" name="customer_address" class="form-control" id="customer_address" value="{{ $account->address }}" placeholder="Address" />
                    </div>
                </div>

                <div class="form-group row mt-1 supplier_account_field {{ $account->group->sub_sub_group_number == 10 ? '' : 'display-none' }}">
                    <div class="col-md-12">
                        <label><strong> {{ __("Phone No.") }} </strong><span class="text-danger">*</span></label>
                        <input {{ $account->group->sub_sub_group_number == 10 ? 'required' : '' }} type="text" name="supplier_phone_no" class="form-control hidden_required" id="supplier_phone_no" value="{{ $account->phone }}" data-next="supplier_address" placeholder="Phone number" />
                        <span class="error error_customer_phone_no"></span>
                    </div>

                    <div class="col-md-12">
                        <label><strong> {{ __("Address") }} </strong></label>
                        <input type="text" name="supplier_address" class="form-control" id="supplier_address" value="{{ $account->address }}" data-next="opening_balance" placeholder="Address" />
                    </div>
                </div>

                <div class="form-group row mt-1 duties_and_tax_account_field  {{ $account->group->is_default_tax_calculator == 1 ? '' : 'display-none' }}">
                    <div class="col-md-12">
                        <label><strong>{{ __("Duties & Tax Calculation Percent") }}</strong> <span class="text-danger">*</span></label>
                        <input type="number" step="any" name="tax_percent" class="form-control" id="tax_percent" value="{{ $account->tax_percent }}" data-next="opening_balance" placeholder="Duties & Tax Calculation Percent" />
                        <span class="error error_tax_percent"></span>
                    </div>
                </div>

                <div class="form-group row mt-1 bank_details_field {{ $account->group->is_allowed_bank_details == 1 ? '' : 'display-none' }}">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <label><strong>@lang('menu.account_number') </strong></label>
                                <input type="text" name="account_number" class="form-control" id="account_number" value="{{ $account->account_number }}" data-next="bank_id" placeholder="@lang('menu.account_number')" />
                            </div>

                            <div class="col-md-6">
                                <label><strong>@lang('menu.bank_name')</strong></label>
                                <select name="bank_id" class="form-control form-select" id="bank_id" data-next="bank_code">
                                    <option value="">@lang('menu.select_bank')</option>
                                    @foreach ($banks as $bank)
                                        <option {{ $account->bank_id == $bank->id ? 'SELECTED' : '' }} value="{{ $bank->id }}">
                                            {{ $bank->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <label><strong>@lang('menu.bank_code') </strong></label>
                                <input type="text" name="bank_code" class="form-control" id="bank_code" value="{{ $account->bank_code }}" data-next="swift_code" placeholder="@lang('menu.bank_code')" />
                            </div>

                            <div class="col-md-6">
                                <label><strong>@lang('menu.swift_code') </strong></label>
                                <input type="text" name="swift_code" class="form-control" id="swift_code" data-next="bank_branch" value="{{ $account->swift_code }}" placeholder="@lang('menu.swift_code')" />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <label><strong>Branch </strong></label>
                                <input type="text" name="bank_branch" class="form-control" id="bank_branch" value="{{ $account->bank_branch }}" data-next="bank_address" placeholder="@lang('menu.bank_branch_name')" />
                            </div>

                            <div class="col-md-6">
                                <label><strong>@lang('menu.address') </strong></label>
                                <input type="text" name="bank_address" class="form-control" id="bank_address" value="{{ $account->bank_address }}" data-next="opening_balance" placeholder="Bank Address" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-1 {{ $account->group->sub_sub_group_number == 6 ? 'display-none' : '' }}" id="general_opening_balance">
                    <label><strong>@lang('menu.opening_balance') </strong></label>
                    <div class="input-group">
                        <input readonly type="text" name="opening_balance_date" class="form-control w-25 fw-bold" id="opening_balance_date" value="On : {{ date('d-M-y', strtotime(json_decode($generalSettings->business, true)['start_date'])) }}" tabindex="-1" />
                        <input type="number" step="any" name="opening_balance" class="form-control w-45" id="opening_balance" value="{{ $account->opening_balance }}" data-next="opening_balance_type" />
                        <select name="opening_balance_type" class="form-control w-30 form-select" id="opening_balance_type" data-next="remarks">
                            <option value="debit">@lang('menu.debit')</option>
                            <option {{ $account->opening_balance_type == 'credit' ? 'SELECTED' : '' }} value="credit">@lang('menu.credit')</option>
                        </select>
                    </div>
                </div>

                <div class="form-group mt-1 {{ $account->group->sub_sub_group_number == 6 ? '' : 'display-none' }}" id="sr_wise_opening_balance">
                    <label><strong>@lang('menu.opening_balance_sr_wise')</strong></label>
                    <table id="myTable">
                        <tbody id="sr_body">
                            @if ($account->group->sub_sub_group_number == 6 && $account->customer && count($account?->customer?->openingBalances ?? []) > 0)

                                @foreach ($account?->customer?->openingBalances as $openingBalance)
                                    <tr id="sr_opening_balance_row">
                                        <td style="width: 22.5%" class="align-items-end">
                                            <input readonly type="text" name="opening_balance_date" class="form-control fw-bold w-100" id="opening_balance_date" value="On : {{ date('d-M-y', strtotime(json_decode($generalSettings->business, true)['start_date'])) }}" tabindex="-1" />
                                        </td>

                                        <td style="width: 22.5%" class="align-items-end">
                                            <input readonly type="text" class="form-control w-100" value="{{ $openingBalance->user->prefix . ' ' . $openingBalance->user->name . ' ' . $openingBalance->user->last_name }}">
                                            <input type="hidden" name="sr_user_ids[]" value="{{ $openingBalance->user_id }}">
                                        </td>

                                        <td style="width: 22.5%" class="align-items-end">
                                            <input type="number" step="any" name="sr_opening_balances[]" class="form-control w-100 sr_initial_opening_balance" id="sr_opening_balance" value="{{ $openingBalance->amount }}" placeholder="0.00" />
                                        </td>

                                        <td style="width: 22.5%" class="align-items-end">
                                            <select name="sr_opening_balance_types[]" class="form-control w-100 form-select" id="sr_opening_balance_type">
                                                <option value="debit">@lang('menu.debit')</option>
                                                <option {{ $openingBalance->balance_type == 'credit' ? 'SELECTED' : '' }} value="credit">@lang('menu.credit')</option>
                                            </select>
                                        </td>

                                        <td style="width: 10%" class="text-center" class="align-items-end">
                                            <div class="row g-0">
                                                <div class="col-md-6">
                                                    <a href="#" onclick="addNewRow(this); return false;" id="add_new_opening_balance_row" class="table_tr_add_btn ms-1 d-inline"><i class="fa-solid fa-plus text-success mt-1"></i></a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr id="sr_opening_balance_row">
                                    <td style="width: 22.5%" class="align-items-end">
                                        <input readonly type="text" name="opening_balance_date" class="form-control fw-bold w-100" id="opening_balance_date" value="{{ __("On") }} : {{ date('d-M-y', strtotime(json_decode($generalSettings->business, true)['start_date'])) }}" tabindex="-1"/>
                                    </td>

                                    <td style="width: 22.5%" class="align-items-end">
                                        <select name="sr_user_ids[]" class="form-control w-100 sr_user_id form-select" id="sr_user_id">
                                            <option value="">@lang('menu.select_sr')</option>
                                            @foreach ($srUsers as $user)
                                                <option value="{{ $user->id }}">{{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}</option>
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
                                                <a href="#" class="d-inline" tabindex="-1"><i class="fas fa-trash-alt text-secondary mt-1"></i></a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="form-group mt-1">
                    <label><strong>@lang('menu.remark') </strong></label>
                    <input type="text" name="remark" class="form-control" id="remarks" value="{{ $account->remark }}" data-next="account_save_changes" placeholder="@lang('menu.remark')" />
                </div>

                <div class="form-group pt-2">
                    <div class="d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button account_loading_button display-none"><i class="fas fa-spinner"></i></button>
                            <button type="button" id="account_save_changes" class="btn btn-sm btn-success account_submit_button float-end">@lang('menu.save_changes')</button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var srUsers = @json($srUsers)

    $('#account_group_id').select2();
    $('#sr_user_id').select2();

    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.account_submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.account_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        }
    });

    $('#edit_account_form').on('submit', function(e) {
        e.preventDefault();

        $('.account_loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                $('.account_loading_button').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                    return;
                }

                toastr.success(data.successMsg);
                $('#accountAddOrEditModal').modal('hide');
                $('#accountAddOrEditModal').empty();
                if (typeof lastChartListClass === 'undefined') {

                    if (typeof accounts_table != 'undefined') {

                        accounts_table.ajax.reload(null, false);
                    }

                    if (typeof account_ledger_table != 'undefined') {

                        account_ledger_table.ajax.reload(null, false);
                        var phone = data.data.phone != null ? ' / ' + data.data.phone : '';
                        var accountNumber = data.data.account_number != null ? ' / ' + data.data.account_number : '';
                        $('#ledger_heading').html(data.data.name + phone + accountNumber);
                        getAccountClosingBalance();
                    }
                } else {

                    getAjaxList();
                }
            },
            error: function(err) {

                $('.account_loading_button').hide();
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

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });

    $('#account_group_id').on('change', function() {

        $('.hidden_required').prop('required', false);
        $('.duties_and_tax_account_field').hide();
        $('.bank_details_field').hide();
        $('.customer_account_field').hide();
        $('.supplier_account_field').hide();
        $('#general_opening_balance').show();
        $('#sr_wise_opening_balance').hide();
        var is_allowed_bank_details = $(this).find('option:selected').data('is_allowed_bank_details');
        var is_bank_or_cash_ac = $(this).find('option:selected').data('is_bank_or_cash_ac');
        var is_fixed_tax_calculator = $(this).find('option:selected').data('is_fixed_tax_calculator');
        var is_default_tax_calculator = $(this).find('option:selected').data('is_default_tax_calculator');
        var sub_sub_group_number = $(this).find('option:selected').data('sub_sub_group_number');

        if (sub_sub_group_number == 6) {

            $('#customer_phone_no').prop('required', true);
            $('.customer_account_field').show();
            $('#sr_wise_opening_balance').show();
            $('#general_opening_balance').hide();
        }

        if (sub_sub_group_number == 10) {

            $('#supplier_phone_no').prop('required', true);
            $('.supplier_account_field').show();
        }

        if (is_allowed_bank_details == 1) {

            $('.bank_details_field').show();
        }

        if (is_fixed_tax_calculator == 1 || is_default_tax_calculator == 1) {

            $('.duties_and_tax_account_field').show();
        }
    });

    $(document).on('change', '#customer_type', function() {

        if ($(this).val() == 2) {

            $('#customer_credit_limit').show();
        } else {

            $('#customer_credit_limit').hide();
        }
    });

    var count = 0;

    function addNewRow(e) {

        var tr = '';
        var main = $('.main_select_box').html();
        tr += '<tr id="sr_opening_balance_row" class="user' + count + '">';

        tr += '<td style="width: 22.5%" class="align-items-end">';
        tr += '<input readonly type="text" name="opening_balance_date" class="form-control fw-bold w-100" id="sr_opening_balance_date" value="On : {{ date('d-M-y', strtotime(json_decode($generalSettings->business, true)['start_date'])) }}" tabindex="-1"/>';
        tr += '</td>';

        tr += '<td style="width: 22.5%" class="align-items-end">';
        tr += '<select required name="sr_user_ids[]" class="form-control my-select2 w-100 sr_user_id form-select" id="sr_user_id' + count + '" autofocus>';
        tr += '<option value="">Select Sr.</option>';
        srUsers.forEach(function(user) {
            tr += '<option value="' + user.id + '">' + user.prefix + ' ' + user.name + ' ' + user.last_name + '</option>';
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

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            if ($(this).attr('id') == 'customer_address') {

                $('#sr_user_id').focus().select();
                return;
            }

            $('#' + nextId).focus().select();
        }
    });

    $('select').on('select2:close', function(e) {

        var nextId = '';
        var sub_sub_group_number = $(this).select2().find(":selected").data("sub_sub_group_number");

        if (sub_sub_group_number == 6) {

            nextId = 'customer_phone_no';
        } else if (sub_sub_group_number == 10) {

            nextId = 'supplier_phone_no';
        } else if (sub_sub_group_number == 8) {

            nextId = 'tax_percent';
        } else if (sub_sub_group_number == 1) {

            nextId = 'account_number';
        } else {

            nextId = 'opening_balance';
        }

        setTimeout(function() {

            $('#' + nextId).focus().select();
        }, 100);
    });

    $(document).on('change keypress click', 'select', function(e) {

        var value = $(this).val();

        var nextId = $(this).data('next');

        if (e.which == 0) {

            if ($(this).attr('id') == 'customer_type' && $('#customer_type').val() == 1) {

                $('#customer_address').focus();
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
