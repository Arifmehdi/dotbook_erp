<div class="modal-dialog double-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_income_account')</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                    class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="add_quick_income_account_form" action="{{ route('accounting.accounts.store') }}">
                @csrf
                <div class="form-group">
                    <label><strong>@lang('menu.name') </strong> <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control add_input" data-name="Name"
                        id="income_name" placeholder="@lang('menu.account_name')" autocomplete="off" autofocus />
                    <span class="error error_income_name"></span>
                </div>

                <div class="form-group mt-1">
                    <label><strong>@lang('menu.account_group') <span class="text-danger">*</span></strong></label>
                    <select required name="account_type" class="form-control add_input" data-name="Account Group"
                        id="income_account_type">
                        <option value="">@lang('menu.select') @lang('menu.account_group')</option>
                        @foreach (\App\Utils\AccountUtil::accountsMainGroupBy()['incomes'] as $key => $accountType)
                            <option value="{{ $key }}">{{ $accountType }}</option>
                        @endforeach
                    </select>
                    <span class="error error_income_account_type"></span>
                </div>

                <div class="form-group mt-1">
                    <label><strong>@lang('menu.opening_balance') </strong></label>
                    <div class="input-group">
                        <input type="number" name="opening_balance" class="form-control w-65" id="opening_balance"
                            value="0.00" step="any" />
                        <select name="opening_balance_type" id="" class="form-control w-35 form-select">
                            <option value="debit">@lang('menu.debit')</option>
                            <option value="credit">@lang('menu.credit')</option>
                        </select>
                    </div>
                </div>

                <div class="form-group mt-1">
                    <label><strong>@lang('menu.remark') </strong></label>
                    <input type="text" name="remark" class="form-control" id="remarks"
                        placeholder="@lang('menu.remark')" />
                </div>

                <div class="form-group pt-2">
                    <div class="d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button display-none"><i
                                    class="fas fa-spinner"></i></button>
                            <button type="submit"
                                class="btn btn-sm btn-success submit_button float-end">@lang('menu.save')</button>
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
    // Add account by ajax
    $('#add_quick_income_account_form').on('submit', function(e) {
        e.preventDefault();

        $('.loading_button').show();
        $('.submit_button').prop('type', 'button');
        var request = $(this).serialize();
        var url = $(this).attr('action');

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                $('.loading_button').hide();
                $('.submit_button').prop('type', 'submit');

                if (!$.isEmptyObject(data)) {

                    $('.error_income_').html('');
                    incomeAccountsArr.push(data)
                    $('.income_account_id').each(function() {

                        var accountType = '';
                        if (data.account_type == 24) {

                            accountType = 'Direct Income : ';
                        } else if (data.account_type == 25) {

                            accountType = 'Indirect Income : ';
                        } else {

                            accountType = 'Misc. Income A/c : ';
                        }

                        $(this).append(
                            '<option class="special_text" data-income_ac_balance="' +
                            data.balance + '" value="' + data.id + '">' + accountType +
                            data.name + '</option>');
                    });

                    // $('#add_quick_income_category_form')[0].reset();
                    $('#income_name').val('');
                    $('#remarks').val('');
                    toastr.success('Income A/c created successfully.');
                }
            },
            error: function(err) {

                $('.loading_button').hide();
                $('.error_income_').html('');
                $('.submit_button').prop('type', 'submit');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Please check the connection..');
                    return;
                } else if (err.status == 500) {

                    toastr.error('Server error. Please contact to the support.');
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_income_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>
