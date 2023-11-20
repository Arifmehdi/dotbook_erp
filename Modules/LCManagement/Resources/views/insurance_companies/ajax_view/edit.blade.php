<form id="edit_insurance_company_form" action="{{ route('lc.insurance.companies.update', $insuranceCompany->id) }}">
    @csrf
    <div class="form-group row mt-1">
        <div class="col-md-3">
            <label><b>Company Name </b> <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control edit_input" data-name="Company name" id="e_name"
                placeholder="Insurance Company Name" value="{{ $insuranceCompany->name }}" />
            <span class="error error_e_name"></span>
        </div>

        <div class="col-md-3">
            <label><b>Brnach </b> <span class="text-danger">*</span></label>
            <input type="text" name="branch" class="form-control edit_input" id="e_branch" data-name="Branch name"
                placeholder="Branch Name" value="{{ $insuranceCompany->branch }}" />
            <span class="error error_e_name"></span>
        </div>

        <div class="col-md-3">
            <label><b>@lang('menu.phone') </b> <span class="text-danger">*</span></label>
            <input type="text" name="phone" class="form-control edit_input" data-name="Phone number" id="e_phone"
                placeholder="Phone Number" value="{{ $insuranceCompany->phone }}" />
            <span class="error error_e_phone"></span>
        </div>

        <div class="col-md-3">
            <label><b>Company ID </b></label>
            <input readonly type="text" name="company_id" class="form-control" placeholder="Company ID"
                value="{{ $insuranceCompany->company_id }}" />
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <label><b>@lang('menu.alternative_number') </b></label>
            <input type="text" name="alternative_phone" class="form-control " placeholder="Alternative phone number"
                value="{{ $insuranceCompany->alternative_phone }}" />
        </div>

        <div class="col-md-3">
            <label><b>@lang('menu.landline') </b></label>
            <input type="text" name="landline" class="form-control " placeholder="landline number"
                value="{{ $insuranceCompany->landline }}" />
        </div>

        <div class="col-md-3">
            <label><b>@lang('menu.tax_number') </b></label>
            <input type="text" name="tax_number" class="form-control" placeholder="@lang('menu.tax_number')"
                value="{{ $insuranceCompany->tax_number }}" />
        </div>

        <div class="col-md-3">
            <label><b>@lang('menu.email') </b></label>
            <input type="text" name="email" class="form-control " placeholder="@lang('menu.email_address')"
                value="{{ $insuranceCompany->email }}" />
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <label><b>@lang('menu.opening_balance') </b></label>
            <input type="number" name="opening_balance" class="form-control " placeholder="@lang('menu.opening_balance')"
                value="{{ $insuranceCompany->opening_balance }}" />
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <label><b>@lang('menu.city') </b></label>
            <input type="text" name="city" class="form-control " placeholder="@lang('menu.city')"
                value="{{ $insuranceCompany->city }}" />
        </div>

        <div class="col-md-3">
            <label><b>State </b></label>
            <input type="text" name="state" class="form-control " placeholder="State"
                value="{{ $insuranceCompany->state }}" />
        </div>

        <div class="col-md-3">
            <label><b>@lang('menu.zip_code') </b></label>
            <input type="text" name="zip_code" class="form-control " placeholder="zip_code"
                value="{{ $insuranceCompany->zip_code }}" />
        </div>

        <div class="col-md-3">
            <label><b>@lang('menu.country') </b></label>
            <input type="text" name="country" class="form-control " placeholder="@lang('menu.country')"
                value="{{ $insuranceCompany->country }}" />
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-9">
            <label><b>@lang('menu.address') </b></label>
            <textarea name="address" class="form-control ckEditor" cols="10" rows="4" placeholder="Address">{{ $insuranceCompany->address }}</textarea>
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
    // edit insurance company by ajax
    $('#edit_insurance_company_form').on('submit', function(e) {

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
            }
        });
    });
</script>
