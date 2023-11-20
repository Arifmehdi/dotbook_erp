<div class="modal-dialog four-col-modal" role="document">
    <div class="modal-content ">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">Add Insurance Company</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                    class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <!--begin::Form-->
            <form id="add_insurance_company_form" action="{{ route('lc.insurance.companies.store') }}">
                @csrf
                <div class="form-group row mt-1">
                    <div class="col-xl-3 col-md-6">
                        <label><b>Company Name </b> <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control ic_add_input" data-name="Company name"
                            id="name" placeholder="Insurance Company Name" />
                        <span class="error error_ic_name"></span>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <label><b>Branch </b> <span class="text-danger">*</span></label>
                        <input type="text" name="branch" id="branch" class="form-control ic_add_input"
                            data-name="Branch" placeholder="Branch Name" />
                        <span class="error error_ic_branch"></span>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <label><b>@lang('menu.phone') </b> <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control ic_add_input" data-name="Phone number"
                            id="phone" placeholder="Phone number" />
                        <span class="error error_ic_phone"></span>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <label><b>Company ID </b></label>
                        <input type="text" name="contact_id" class="form-control" placeholder="Contact ID" />
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-xl-3 col-md-6">
                        <label><b>@lang('menu.alternative_number') </b></label>
                        <input type="text" name="alternative_phone" class="form-control "
                            placeholder="Alternative phone number" />
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <label><b>@lang('menu.email') </b></label>
                        <input type="text" name="email" class="form-control " placeholder="@lang('menu.email_address')" />
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <b>@lang('menu.landline') </b>
                        <input type="text" name="landline" class="form-control " placeholder="landline number" />
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <b>@lang('menu.tax_number') </b>
                        <input type="text" name="tax_number" class="form-control " placeholder="@lang('menu.tax_number')" />
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-xl-3 col-md-6">
                        <b>@lang('menu.opening_balance') </b>
                        <input type="number" name="opening_balance" class="form-control "
                            placeholder="@lang('menu.opening_balance')" />
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-xl-3 col-md-6">
                        <label><b>@lang('menu.city') </b></label>
                        <input type="text" name="city" class="form-control " placeholder="@lang('menu.city')" />
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <label><b>State </b></label>
                        <input type="text" name="state" class="form-control " placeholder="State" />
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <label><b>@lang('menu.zip_code') </b></label>
                        <input type="text" name="zip_code" class="form-control " placeholder="zip_code" />
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <label><b>@lang('menu.country') </b></label>
                        <input type="text" name="country" class="form-control " placeholder="@lang('menu.country')" />
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-9">
                        <label><b>@lang('menu.address') : </b></label>
                        <textarea name="address" class="form-control ckEditor" cols="10" rows="4" placeholder="Address"></textarea>
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
        </div>
    </div>
</div>

<script>
    // Add Insurance Company by ajax
    $('#add_insurance_company_form').on('submit', function(e) {
        e.preventDefault();

        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        var inputs = $('.ic_add_input');

        $('.error').html('');
        var countErrorField = 0;

        $.each(inputs, function(key, val) {

            var inputId = $(val).attr('id');
            var idValue = $('#' + inputId).val();

            if (idValue == '') {

                countErrorField += 1;
                var fieldName = $('#' + inputId).data('name');
                $('.error_ic_' + inputId).html(fieldName + ' is required.');
            }
        });

        if (countErrorField > 0) {

            $('.loading_button').hide();
            return;
        }

        $('.submit_button').prop('type', 'button');

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                $('#quickAddModal').modal('hide');
                $('.submit_button').prop('type', 'submit');
                toastr.success('Insurance company is Added Successfully.');
                $('.loading_button').hide();
                $('#insurance_company_id').append('<option value="' + data.id + '">' + data.name +
                    '</option>');
                $('#insurance_company_id').val(data.id);

            },
            error: function(err) {

                $('.submit_button').prop('type', 'sumbit');
                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                } else if (err.status == 500) {

                    toastr.error('Server error please contact to the support.');
                } else if (err.status == 403) {

                    toastr.error('Access Denied.');
                }

                toastr.error('Please check again all form fields.', 'Some thing went wrong.');

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_ic_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>
