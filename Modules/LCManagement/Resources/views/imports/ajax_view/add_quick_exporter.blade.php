<div class="modal-dialog four-col-modal" role="document" id="quick_add_exporter_modal_contant">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">Add Exporter</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                    class="fas fa-times"></span></a>
        </div>
        <div class="modal-body" id="add_exporter_modal_body">
            <!--begin::Form-->
            <form id="add_quick_exporter_form" action="{{ route('lc.exporters.store') }}">
                @csrf
                <div class="form-group row mt-1">
                    <div class="col-md-3">
                        <b>@lang('menu.name') :</b> <span class="text-danger">*</span>
                        <input type="text" name="name" class="form-control ex_add_input" data-name="Exporter name"
                            id="name" placeholder="Exporter name" />
                        <span class="error error_ex_name" style="color: red;"></span>
                    </div>

                    <div class="col-md-3">
                        <b>@lang('menu.phone') :</b> <span class="text-danger">*</span>
                        <input type="text" name="phone" class="form-control ex_add_input" data-name="Phone number"
                            id="phone" placeholder="Phone number" />
                        <span class="error error_ex_phone"></span>
                    </div>

                    <div class="col-md-3">
                        <b>Exporter ID :</b> <i data-bs-toggle="tooltip" data-bs-placement="right"
                            title="Leave empty to auto generate." class="fas fa-info-circle tp"></i>
                        <input type="text" name="contact_id" class="form-control" placeholder="Contact ID" />
                    </div>

                    <div class="col-md-3">
                        <b>@lang('menu.business_name') :</b>
                        <input type="text" name="business_name" class="form-control"
                            placeholder="@lang('menu.business_name')" />
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-3">
                        <b>@lang('menu.alternative_number') :</b>
                        <input type="text" name="alternative_number" class="form-control"
                            placeholder="Alternative phone number" />
                    </div>

                    <div class="col-md-3">
                        <b>@lang('menu.landline') :</b>
                        <input type="text" name="land_line" class="form-control" placeholder="landline number" />
                    </div>

                    <div class="col-md-3">
                        <b>@lang('menu.email') :</b>
                        <input type="text" name="email" class="form-control" placeholder="@lang('menu.email_address')" />
                    </div>

                    <div class="col-md-3">
                        <b>@lang('menu.date_of_birth') :</b>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i
                                        class="fas fa-calendar-week input_i"></i></span>
                            </div>
                            <input type="text" name="date_of_birth" class="form-control date-of-birth-picker"
                                autocomplete="off" placeholder="YYYY-MM-DD">
                        </div>
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-3">
                        <b>@lang('menu.id_proof_name') :</b>
                        <input type="text" name="id_proof_name" class="form-control"
                            placeholder="@lang('menu.id_proof_name')" />
                    </div>

                    <div class="col-md-3">
                        <b>@lang('menu.id_proof_number') :</b>
                        <input type="text" name="id_proof_number" class="form-control"
                            placeholder="@lang('menu.id_proof_number')" />
                    </div>

                    <div class="col-md-3">
                        <b>@lang('menu.tax_number') :</b>
                        <input type="text" name="tax_number" class="form-control" placeholder="@lang('menu.tax_number')" />
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-3">
                        <b>@lang('menu.city') :</b>
                        <input type="text" name="city" class="form-control" placeholder="@lang('menu.city')" />
                    </div>

                    <div class="col-md-3">
                        <b>State :</b>
                        <input type="text" name="state" class="form-control" placeholder="State" />
                    </div>

                    <div class="col-md-3">
                        <b>@lang('menu.country') :</b>
                        <input type="text" name="country" class="form-control"
                            placeholder="@lang('menu.country')" />
                    </div>

                    <div class="col-md-3">
                        <b>@lang('menu.zip_code') :</b>
                        <input type="text" name="zip_code" class="form-control" placeholder="zip_code" />
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <div class="col-md-12">
                        <b>@lang('menu.address') : </b>
                        <textarea name="address" class="form-control ckEditor" id="address" cols="10" rows="4"
                            placeholder="Address"></textarea>
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
    // Add Exporter by ajax
    $('#add_quick_exporter_form').on('submit', function(e) {

        e.preventDefault();

        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        var inputs = $('.ex_add_input');

        $('.error').html('');
        var countErrorField = 0;

        $.each(inputs, function(key, val) {

            var inputId = $(val).attr('id');
            var idValue = $('#' + inputId).val();

            if (idValue == '') {

                countErrorField += 1;
                var fieldName = $('#' + inputId).data('name');

                $('.error_ex_' + inputId).html(fieldName + ' is required.');
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
                toastr.success('Exporter Added Successfully.');
                $('.loading_button').hide();
                $('#exporter_id').append('<option value="' + data.id + '">' + data.name + ' (' +
                    data.exporter_id + ')' + '</option>');
                $('#exporter_id').val(data.id);
            },
            error: function(err) {

                $('.submit_button').prop('type', 'sumbit');
                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                } else if (err.status == 500) {

                    toastr.error('Server error. Please contact to the support.');
                } else if (err.status == 403) {

                    toastr.error('Access Denied.');
                }

                toastr.error('Please check again all form fields.', 'Some thing went wrong.');

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_ex_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>
