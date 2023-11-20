<div class="modal-dialog double-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Edit Bank') }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="edit_bank_form" action="{{ route('accounting.banks.update', $bank->id) }}">
                <div class="form-group">
                    <label><b>@lang('menu.bank_name')</b> <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control" id="bank_name" value="{{ $bank->name }}" data-next="bank_save_changes" placeholder="{{ __("Bank Name") }}"/>
                    <span class="error error_bank_name"></span>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button bank_loading_btn display-none"><i class="fas fa-spinner"></i></button>
                            <button type="submit" id="bank_save_changes" class="btn btn-sm btn-success float-end bank_submit_button">{{ __("Save Changes") }}</button>
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

    $('#edit_bank_form').on('submit', function(e) {

        e.preventDefault();
        $('.bank_loading_btn').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                $('.bank_loading_btn').hide();
                $('#bankAddOrEditModal').modal('hide');

                toastr.success(data);
                bankTable.ajax.reload(false, null);

            },
            error: function(err) {

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
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#' + nextId).focus().select();
        }
    });
</script>
