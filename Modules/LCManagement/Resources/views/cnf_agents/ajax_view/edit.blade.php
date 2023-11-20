<form id="edit_cnf_agent_form" action="{{ route('lc.cnf.agents.update', $cnfAgent->id) }}">
    @csrf
    <div class="form-group row mt-1">
        <div class="col-xl-3 col-md-6">
            <label><b>Agent Name </b> <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control edit_input" data-name="Company name" id="e_name"
                placeholder="Insurance Company Name" value="{{ $cnfAgent->name }}" required />
            <span class="error error_e_name"></span>
        </div>

        <div class="col-xl-3 col-md-6">
            <label><b>@lang('menu.phone') </b> <span class="text-danger">*</span></label>
            <input type="text" name="phone" class="form-control edit_input" data-name="Phone number" id="e_phone"
                placeholder="Phone Number" value="{{ $cnfAgent->phone }}" required />
            <span class="error error_e_phone"></span>
        </div>

        <div class="col-xl-3 col-md-6">
            <label><b>Agent ID </b></label>
            <input readonly type="text" name="company_id" class="form-control" placeholder="Company ID"
                value="{{ $cnfAgent->agent_id }}" />
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-xl-3 col-md-6">
            <label><b>@lang('menu.alternative_number') </b></label>
            <input type="text" name="alternative_phone" class="form-control " placeholder="Alternative phone number"
                value="{{ $cnfAgent->alternative_phone }}" />
        </div>

        <div class="col-xl-3 col-md-6">
            <label><b>@lang('menu.landline') </b></label>
            <input type="text" name="landline" class="form-control " placeholder="landline number"
                value="{{ $cnfAgent->landline }}" />
        </div>

        <div class="col-xl-3 col-md-6">
            <label><b>@lang('menu.tax_number') </b></label>
            <input type="text" name="tax_number" class="form-control" placeholder="@lang('menu.tax_number')"
                value="{{ $cnfAgent->tax_number }}" />
        </div>

        <div class="col-xl-3 col-md-6">
            <label><b>@lang('menu.email') </b></label>
            <input type="text" name="email" class="form-control " placeholder="@lang('menu.email_address')"
                value="{{ $cnfAgent->email }}" />
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-xl-3 col-md-6">
            <label><b>@lang('menu.opening_balance') </b></label>
            <input type="number" name="opening_balance" class="form-control " placeholder="@lang('menu.opening_balance')"
                value="{{ $cnfAgent->opening_balance }}" />
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-xl-3 col-md-6">
            <label><b>@lang('menu.city') </b></label>
            <input type="text" name="city" class="form-control " placeholder="@lang('menu.city')"
                value="{{ $cnfAgent->city }}" />
        </div>

        <div class="col-xl-3 col-md-6">
            <label><b>State </b></label>
            <input type="text" name="state" class="form-control " placeholder="State"
                value="{{ $cnfAgent->state }}" />
        </div>

        <div class="col-xl-3 col-md-6">
            <label><b>@lang('menu.zip_code') </b></label>
            <input type="text" name="zip_code" class="form-control " placeholder="zip_code"
                value="{{ $cnfAgent->zip_code }}" />
        </div>

        <div class="col-xl-3 col-md-6">
            <label><b>@lang('menu.country') </b></label>
            <input type="text" name="country" class="form-control " placeholder="@lang('menu.country')"
                value="{{ $cnfAgent->country }}" />
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-9">
            <label><b>@lang('menu.address') </b></label>
            <textarea name="address" class="form-control ckEditor" cols="10" rows="4" placeholder="@lang('menu.address')">{{ $cnfAgent->address }}</textarea>
        </div>
    </div>

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

<script>
    // edit CNF agent by ajax
    $('#edit_cnf_agent_form').on('submit', function(e) {
        e.preventDefault();

        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        var inputs = $('.edit_input');
        $('.error').html('');
        var countErrorField = 0;

        $.each(inputs, function(key, val) {

            var inputId = $(val).attr('id');
            var idValue = $('#' + inputId).val();
            if (idValue == '') {

                countErrorField += 1;
                var fieldName = $('#' + inputId).data('name');
                $('.error_e_' + inputId).html(fieldName + ' is required.');
            }
        });

        if (countErrorField > 0) {

            $('.loading_button').hide();
            return;
        }

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                toastr.success(data);
                $('.loading_button').hide();
                table.ajax.reload();
                $('#editModal').modal('hide');
            },
            error: function(err) {

                $('.submit_button').prop('type', 'sumbit');
                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if (err.status == 500) {

                    toastr.error('Server Error. Please to the support team.');
                    return;
                } else if (err.status == 403) {

                    toastr.error('Access Denied');
                    return;
                }

                toastr.error('Please check again all form fields.', 'Some thing went wrong.');

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_e_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>
