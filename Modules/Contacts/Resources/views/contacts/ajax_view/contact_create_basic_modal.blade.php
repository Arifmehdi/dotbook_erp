<style>
    #submit_customer_basic_form .form-group label {
        text-align: right;
    }
</style>
<div class="modal-dialog col-60-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">Add Contact <span class="type_name"></span></h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                    class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="submit_customer_basic_form" action="{{ route('contacts.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group row mt-1">
                    <div class="col-lg-12">
                        <div class="form-group row p-1" id="business_type_winbox">
                            <label class="col-sm-3 ContactType"><b>Type:</b></label>
                            <div class="col-sm-3 border-box">
                                <span>Company</span>
                                <input type="radio" checked name="contact_type" value="company"
                                    class="basic_contact_type check_company" id="basic_modal_contact_type">
                            </div>

                            <div class="col-sm-3 border-box">
                                <span>Individual</span>
                                <input type="radio" name="contact_type" value="individual"
                                    class="basic_contact_type check_individual" id="basic_modal_contact_type">
                            </div>
                        </div>

                        <div class="form-group row p-1">
                            <label class="col-sm-3"><b>Contact Type :</b> <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-control form-select" name="contact_related" id="" required>
                                    <option value="Contacts">{{ __('Contacts') }}</option>
                                    <option value="Leads">{{ __('Leads') }}</option>
                                    <option value="Customers">{{ __('Customers') }}</option>
                                    <option value="Suppliers">{{ __('Suppliers') }}</option>
                                </select>
                                <span class="error error_contact_related"></span>
                            </div>
                        </div>

                        <div class="form-group row p-1">
                            <label class="col-sm-3"><b>Contact Name :</b> <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input required type="text" id="name" name="name"
                                    class="form-control basic_name" placeholder="Contact Name">
                                <span class="error error_name"></span>
                            </div>
                        </div>

                        <div class="form-group row p-1">
                            <label class="col-sm-3"><b>Phone Number :</b> <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input required type="text" id="phone" name="phone"
                                    class="form-control basic_phone" placeholder="Phone Number">
                                <span class="error error_phone"></span>
                            </div>
                        </div>

                        <div class="form-group row p-1 ">
                            <label class="col-sm-3 basic_modal_company_feild"><b>Company Name :</b></label>
                            <label class="col-sm-3 basic_modal_individual_feild d-none"><b>Companies :</b></label>
                            <div class="col-sm-9">
                                <input type="text" name="business_name" class="form-control basic_business_name"
                                    placeholder="Companies">
                            </div>
                        </div>

                        <div class="form-group row p-1">
                            <label class="col-sm-3 basic_modal_company_feild"><b>Company Address :</b></label>
                            <label class="col-sm-3 basic_modal_individual_feild d-none"><b>Present Address :</b></label>
                            <div class="col-sm-9">
                                <input type="text" name="address" id="address" class="form-control basic_address"
                                    placeholder="Address">
                            </div>
                        </div>

                        <div class="form-group row p-1 basic_modal_company_feild">
                            <label class="col-sm-3"><b>Trade Number :</b></label>
                            <div class="col-sm-9">
                                <input type="text" name="trade_license_no"
                                    class="form-control basic_trade_license_no" placeholder="Trade Number">
                                <span class="error error_trade_license_no"></span>
                            </div>
                        </div>

                    </div>

                    <div class="form-group row justify-content-end mt-3">
                        <div class="col-md-12">
                            <button type="button" id="add_contact_details"
                                class="btn btn-sm btn-primary me-0 float-end">Add More Details</button>
                        </div>
                    </div>

                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                            <button type="submit" class="btn btn-sm btn-success float-end submit_button">@lang('menu.save')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#submit_customer_basic_form').on('submit', function(e) {
        e.preventDefault();
        $('.c_loading_button').show();
        var url = $(this).attr('action');
        $('.submit_button').prop('type', 'button');
        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                $('.error').html('');
                toastr.success('Contacts added successfully.');
                $('.c_loading_button').hide();
                $('#add_contact_basic_modal').modal('hide');
                $('.submit_button').prop('type', 'submit');
                var customerId = $('#customer_id').val();
                $('.contacts_table').DataTable().ajax.reload();

                if (customerId != undefined) {
                    $('#customer_id').append('<option data-customer_name="' + data.name +
                        '" data-customer_phone="' + data.phone + '" value="' + data.id + '">' +
                        data.name + '/' + data.phone + '</option>');
                    $('#customer_id').val(data.id);
                    var user_id = $('#user_id').val();
                    getCustomerAmountsUserWise(user_id, data.id, false);
                    calculateTotalAmount();
                } else {
                    table.ajax.reload();
                    refresh();
                }
            },
            error: function(err) {

                $('.c_loading_button').hide();
                $('.submit_button').prop('type', 'submit');
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
    });

    $('#add_contact_details').on('click', function() {

        $.get("{{ route('contacts.create.details.modal') }}", function(data) {

            $('#add_contact_detailed_modal').html(data);
            $('#add_contact_detailed_modal').modal('show');

            var basic_name = $('.basic_name').val();
            var basic_phone = $('.basic_phone').val();
            var basic_contact_type = $('.basic_contact_type').val();
            var basic_business_name = $('.basic_business_name').val();
            var basic_trade_license_no = $('.basic_trade_license_no').val();
            var basic_address = $('.basic_address').val();
            var basic_balance_type = $('.basic_balance_type').val();
            var basic_customer_type = $('.basic_customer_type').val();

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

    setTimeout(function() {

        $('.basic_name').focus();
    }, 500);
</script>
