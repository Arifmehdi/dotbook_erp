<div class="modal-dialog double-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Add Bank') }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="add_bank_form" action="{{ route('accounting.banks.store') }}">
                <div class="form-group">
                    <label><b>@lang('menu.bank_name')</b> <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control" id="bank_name" data-next="bank_save" placeholder="{{ __("Bank Name") }}"/>
                    <span class="error error_bank_name"></span>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button bank_loading_btn display-none"><i class="fas fa-spinner"></i></button>
                            <button type="submit" id="bank_save" class="btn btn-sm btn-success float-end bank_submit_button">{{ __("Save") }}</button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end me-2">{{ __("Close") }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.bank_submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.bank_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#add_bank_form').on('submit', function(e) {

        e.preventDefault();
        $('.bank_loading_btn').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        isAjaxIn = false;
        isAllowSubmit = false;

        $.ajax({
            beforeSend: function() {
                isAjaxIn = true;
            },
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                isAjaxIn = true;
                isAllowSubmit = true;

                $('.bank_loading_btn').hide();
                $('#bankAddOrEditModal').modal('hide');

                toastr.success("{{ __('Bank created successfully.') }}");

                var bank_id = $('#bank_id').val();
                if (bank_id != undefined) {

                    $('#bank_id').append('<option value="' + data.id + '">' + data.name + '</option>');
                    $('#bank_id').val(data.id);

                    var nextId = $('#bank_id').data('next');
                    $('#' + nextId).focus().select();
                }else {

                    bankTable.ajax.reload();
                }
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.bank_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                } else if (err.status == 500) {

                    toastr.error('Server error. Please contact to the support.');
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_bank_' + key + '').html(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#' + nextId).focus().select();
        }
    });
</script>
