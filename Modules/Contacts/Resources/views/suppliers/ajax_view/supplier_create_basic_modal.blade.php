<style>
    #submit_supplier_basic_form .form-group label {
        text-align: right;
    }
</style>
<div class="modal-dialog col-60-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_supplier') <span class="type_name"></span></h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                    class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="submit_supplier_basic_form" action="{{ route('contacts.supplier.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group row mt-1">
                    <div class="col-lg-12">
                        <div class="form-group row p-1">
                            <label class="col-sm-3"><b>Supplier Name : <span class="text-danger">*</span></b></label>
                            <div class="col-sm-9">
                                <input required type="text" name="name" class="form-control basic_name"
                                    id="name" placeholder="Supplier Name">
                                <span class="error error_name"></span>
                            </div>
                        </div>

                        <div class="form-group row p-1">
                            <label class="col-sm-3"><b>Phone Number : <span class="text-danger">*</span></b></label>
                            <div class="col-sm-9">
                                <input required type="text" name="phone" class="form-control basic_phone"
                                    id="phone" placeholder="Phone Number">
                                <span class="error error_phone"></span>
                            </div>
                        </div>

                        <div class="form-group row p-1 trade_hide">
                            <label class="col-sm-3"><b>Business Name :</b></label>
                            <div class="col-sm-9">
                                <input type="text" name="business_name" class="form-control basic_business_name"
                                    placeholder="Business Name">
                            </div>
                        </div>

                        <div class="form-group row p-1 trade_hide">
                            <label class="col-sm-3"><b>Trade Number :</b></label>
                            <div class="col-sm-9">
                                <input type="text" name="trade_license_no"
                                    class="form-control basic_trade_license_no" placeholder="Trade Number">
                                <span class="error error_trade_license_no"></span>
                            </div>
                        </div>

                        <div class="form-group row p-1">
                            <label class="col-sm-3 shop_name"><b>Present Address :</b></label>
                            <div class="col-sm-9">
                                <input type="text" id="phone" name="address" class="form-control basic_address"
                                    placeholder="Present Address">
                            </div>
                        </div>

                        <div class="form-group row p-1">
                            <label class="col-sm-3"><b>Opening Balance :</b></label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <input type="number" id="opening_balance" name="opening_balance"
                                        class="form-control basic_opening_balance" value="0">
                                    <select name="opening_balance_type"
                                        class="form-control basic_balance_type form-select" id="opening_balance_type">
                                        <option value="debit">@lang('menu.debit')</option>
                                        <option value="credit">@lang('menu.credit')</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mt-3">
                        <div class="col-md-12">
                            <button type="button" id="add_supplier_details"
                                class="btn btn-sm btn-primary me-0 float-end">Add More Details</button>
                        </div>
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button display-none"><i
                                    class="fas fa-spinner"></i></button>
                            <button type="submit"
                                class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#submit_supplier_basic_form').on('submit', function(e) {
        e.preventDefault();

        $('.add_supplier_loading_button').show();
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
                toastr.success('Supplier added successfully.');
                $('.add_supplier_loading_button').hide();
                $('#add_supplier_basic_modal').modal('hide');
                $('.submit_button').prop('type', 'submit');

                var supplier_account_id = $('#supplier_account_id').val();
                if (supplier_account_id != undefined) {

                    $('#supplier_account_id').append('<option value="' + data.supplier_account_id +
                        '">' + data.name + '/' + data.phone + '</option>');
                    $('#supplier_account_id').val(data.supplier_account_id);

                    getAccountClosingBalance(data.supplier_account_id);
                } else {

                    refresh();
                    table.ajax.reload();
                }
            },
            error: function(err) {

                $('.add_supplier_loading_button').hide();
                $('.submit_button').prop('type', 'submit');
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if (err.status == 500) {

                    toastr.error('Server Error. Please contact to the support team.');
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });

    $('#add_supplier_details').on('click', function() {

        $.get("{{ route('contacts.supplier.create.detailed.modal') }}", function(data) {

            $('#add_supplier_detailed_modal').html(data);
            $('#add_supplier_detailed_modal').modal('show');

            var basic_name = $('.basic_name').val();
            var basic_phone = $('.basic_phone').val();
            var basic_business_name = $('.basic_business_name').val();
            var basic_trade_license_no = $('.basic_trade_license_no').val();
            var basic_address = $('.basic_address').val();
            var basic_opening_balance = $('.basic_opening_balance').val();
            var basic_balance_type = $('.basic_balance_type').val();

            $('.big_name').val(basic_name);
            $('.big_phone').val(basic_phone);
            $('.big_business_name').val(basic_business_name);
            $('.big_trade_license_no').val(basic_trade_license_no);
            $('.big_address').val(basic_address);
            $('.big_opening_balance').val(basic_opening_balance);
            $('.big_balance_type').val(basic_balance_type);
        });
    });

    setTimeout(function() {

        $('.basic_name').focus();
    }, 500);
</script>
